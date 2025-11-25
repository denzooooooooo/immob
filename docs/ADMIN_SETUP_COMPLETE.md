# Configuration de l'Interface d'Administration - Monnkama

## Installation Initiale

### 1. Configuration de la Base de Données
```sql
-- Création de l'administrateur principal
INSERT INTO users (
    name,
    email,
    password,
    role,
    status,
    email_verified_at,
    created_at,
    updated_at
) VALUES (
    'Admin Principal',
    'admin@monnkama.ga',
    '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- password
    'admin',
    'active',
    NOW(),
    NOW(),
    NOW()
);

-- Configuration des permissions
INSERT INTO permissions (name, guard_name) VALUES
('manage_users', 'web'),
('manage_properties', 'web'),
('manage_subscriptions', 'web'),
('manage_messages', 'web'),
('manage_settings', 'web');

-- Attribution des permissions à l'admin
INSERT INTO model_has_roles (role_id, model_type, model_id)
VALUES (1, 'App\\Models\\User', 1);
```

### 2. Configuration Environnement
```env
# .env
ADMIN_EMAIL=admin@monnkama.ga
ADMIN_PASSWORD=votre_mot_de_passe_securise

# Configuration Email Admin
ADMIN_NOTIFICATION_EMAIL=notifications@monnkama.ga
ADMIN_NOTIFICATION_NAME="Monnkama Admin"

# Configuration Sécurité
ADMIN_2FA_ENABLED=true
ADMIN_SESSION_LIFETIME=120
ADMIN_LOGIN_ATTEMPTS=5
```

## Sécurité

### 1. Configuration du Middleware Admin
```php
// app/Http/Middleware/AdminAccess.php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminAccess
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->user() || !$request->user()->isAdmin()) {
            return redirect()->route('login')->with('error', 'Accès non autorisé.');
        }

        if (config('admin.2fa_enabled') && !$request->user()->hasValid2FA()) {
            return redirect()->route('admin.2fa.setup');
        }

        return $next($request);
    }
}
```

### 2. Protection des Routes
```php
// routes/admin.php
Route::middleware(['auth', 'admin', '2fa', 'activity.log'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    
    // Gestion des utilisateurs
    Route::resource('users', UserController::class);
    Route::post('users/bulk', [UserController::class, 'bulk'])->name('users.bulk');
    
    // Gestion des propriétés
    Route::resource('properties', PropertyController::class);
    Route::post('properties/bulk', [PropertyController::class, 'bulk'])->name('properties.bulk');
    
    // Gestion des abonnements
    Route::resource('subscriptions', SubscriptionController::class);
    Route::patch('subscriptions/{subscription}/cancel', [SubscriptionController::class, 'cancel'])
        ->name('subscriptions.cancel');
    
    // Gestion des messages
    Route::resource('messages', MessageController::class);
    Route::patch('messages/{message}/mark-as-read', [MessageController::class, 'markAsRead'])
        ->name('messages.mark-as-read');
});
```

## Configuration des Services

### 1. Service de Notification
```php
// app/Services/AdminNotificationService.php
namespace App\Services;

use Illuminate\Support\Facades\Mail;
use App\Mail\AdminNotification;

class AdminNotificationService
{
    public function sendAlert($type, $message, $data = [])
    {
        Mail::to(config('admin.notification_email'))
            ->send(new AdminNotification($type, $message, $data));
        
        // Log de l'alerte
        \Log::channel('admin')->info("Alert: {$type} - {$message}");
    }
    
    public function notifyUserAction($action, $user, $admin)
    {
        $this->sendAlert(
            'user_action',
            "{$admin->name} a {$action} l'utilisateur {$user->name}",
            compact('action', 'user', 'admin')
        );
    }
    
    public function notifySystemEvent($event, $details)
    {
        $this->sendAlert('system', $event, $details);
    }
}
```

### 2. Service de Journalisation
```php
// app/Services/AdminLogService.php
namespace App\Services;

use App\Models\AdminLog;

class AdminLogService
{
    public function log($action, $model = null, $details = [])
    {
        AdminLog::create([
            'admin_id' => auth()->id(),
            'action' => $action,
            'model_type' => $model ? get_class($model) : null,
            'model_id' => $model ? $model->id : null,
            'details' => $details,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent()
        ]);
    }
    
    public function getRecentActivity($limit = 50)
    {
        return AdminLog::with('admin')
            ->latest()
            ->limit($limit)
            ->get();
    }
}
```

## Personnalisation de l'Interface

### 1. Configuration du Thème
```php
// config/admin.php
return [
    'theme' => [
        'primary_color' => '#009639',
        'secondary_color' => '#1a1a1a',
        'accent_color' => '#ffd700',
        'sidebar_style' => 'dark',
        'header_style' => 'light',
    ],
    
    'menu' => [
        'dashboard' => [
            'icon' => 'fas fa-tachometer-alt',
            'route' => 'admin.dashboard',
        ],
        'users' => [
            'icon' => 'fas fa-users',
            'route' => 'admin.users.index',
            'badge' => 'getUserCount',
        ],
        // ...
    ],
    
    'widgets' => [
        'quick_stats' => true,
        'recent_activity' => true,
        'revenue_chart' => true,
        'user_map' => true,
    ],
];
```

### 2. Composants Personnalisés
```php
// app/View/Components/Admin/Card.php
namespace App\View\Components\Admin;

use Illuminate\View\Component;

class Card extends Component
{
    public $title;
    public $icon;
    public $value;
    public $trend;
    
    public function __construct($title, $icon, $value, $trend = null)
    {
        $this->title = $title;
        $this->icon = $icon;
        $this->value = $value;
        $this->trend = $trend;
    }
    
    public function render()
    {
        return view('components.admin.card');
    }
}
```

## Tâches de Maintenance

### 1. Commandes Artisan
```php
// app/Console/Commands/AdminCleanup.php
namespace App\Console\Commands;

use Illuminate\Console\Command;

class AdminCleanup extends Command
{
    protected $signature = 'admin:cleanup';
    protected $description = 'Nettoie les données administratives obsolètes';
    
    public function handle()
    {
        // Suppression des anciens logs
        \DB::table('admin_logs')
            ->where('created_at', '<', now()->subMonths(3))
            ->delete();
            
        // Nettoyage des sessions expirées
        \DB::table('sessions')
            ->where('last_activity', '<', now()->subDays(7))
            ->delete();
            
        $this->info('Nettoyage administratif terminé.');
    }
}
```

### 2. Tâches Planifiées
```php
// app/Console/Kernel.php
protected function schedule(Schedule $schedule)
{
    // Nettoyage hebdomadaire
    $schedule->command('admin:cleanup')->weekly();
    
    // Sauvegarde quotidienne
    $schedule->command('backup:run')->daily();
    
    // Rapport d'activité quotidien
    $schedule->command('admin:report')->dailyAt('08:00');
}
```

## Surveillance et Rapports

### 1. Tableau de Bord
- Statistiques en temps réel
- Graphiques d'activité
- Alertes système
- Journal des actions

### 2. Rapports Automatisés
```php
// app/Console/Commands/GenerateAdminReport.php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ReportService;

class GenerateAdminReport extends Command
{
    protected $signature = 'admin:report';
    
    public function handle(ReportService $reportService)
    {
        $report = $reportService->generateDailyReport();
        
        // Envoi par email
        Mail::to(config('admin.notification_email'))
            ->send(new DailyReport($report));
            
        $this->info('Rapport quotidien généré et envoyé.');
    }
}
```

## Dépannage

### 1. Journal des Erreurs
```php
// config/logging.php
'channels' => [
    'admin' => [
        'driver' => 'daily',
        'path' => storage_path('logs/admin.log'),
        'level' => 'debug',
        'days' => 14,
    ],
],
```

### 2. Commandes de Diagnostic
```php
// app/Console/Commands/AdminDiagnostic.php
namespace App\Console\Commands;

use Illuminate\Console\Command;

class AdminDiagnostic extends Command
{
    protected $signature = 'admin:diagnostic';
    
    public function handle()
    {
        $this->info('Vérification de la configuration admin...');
        
        // Vérification des permissions
        $this->checkPermissions();
        
        // Vérification de la base de données
        $this->checkDatabase();
        
        // Vérification du cache
        $this->checkCache();
        
        // Vérification des services
        $this->checkServices();
    }
}
```

## Conclusion

Cette configuration fournit une base solide pour l'interface d'administration de Monnkama. Elle peut être étendue selon les besoins spécifiques du projet.
