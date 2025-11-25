# Architecture Technique - Monnkama

## Vue d'Ensemble

Monnkama est construit sur une architecture MVC (Modèle-Vue-Contrôleur) utilisant Laravel comme framework principal. Le système est conçu pour être modulaire, évolutif et maintenable.

## Structure du Projet

```
monnkama/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/
│   │   │   ├── Agent/
│   │   │   └── Public/
│   │   ├── Middleware/
│   │   └── Requests/
│   ├── Models/
│   ├── Services/
│   └── Providers/
├── config/
├── database/
│   ├── migrations/
│   └── seeders/
├── public/
├── resources/
│   ├── views/
│   ├── css/
│   └── js/
└── routes/
```

## Couches Applicatives

### 1. Présentation

#### Views (Blade Templates)
- Séparation en layouts réutilisables
- Components Blade pour les éléments communs
- Intégration Tailwind CSS pour le style
- Scripts Alpine.js pour l'interactivité

#### JavaScript
- Modules ES6
- Gestion des événements
- Validation côté client
- Intégration de Chart.js pour les graphiques

### 2. Logique Métier

#### Controllers
- Organisation par domaine fonctionnel
- Actions CRUD standardisées
- Validation des entrées
- Gestion des réponses

#### Services
- Encapsulation de la logique métier complexe
- Réutilisation du code
- Gestion des transactions
- Services spécialisés (ex: ImageService)

### 3. Accès aux Données

#### Models
- Relations Eloquent
- Scopes pour les requêtes communes
- Accesseurs et mutateurs
- Events et Observers

#### Repositories (optionnel)
- Abstraction de la couche données
- Requêtes complexes
- Cache des résultats
- Tests unitaires facilités

## Base de Données

### Schéma Relationnel

```sql
-- Utilisateurs et Authentification
CREATE TABLE users (
    id BIGINT PRIMARY KEY,
    name VARCHAR(255),
    email VARCHAR(255) UNIQUE,
    password VARCHAR(255),
    role ENUM('admin', 'agent', 'user'),
    status ENUM('active', 'inactive', 'banned'),
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);

-- Propriétés
CREATE TABLE properties (
    id BIGINT PRIMARY KEY,
    user_id BIGINT,
    title VARCHAR(255),
    description TEXT,
    price DECIMAL(12,2),
    type ENUM('house', 'apartment', 'land', 'commercial'),
    status ENUM('published', 'draft', 'pending'),
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Détails des Propriétés
CREATE TABLE property_details (
    id BIGINT PRIMARY KEY,
    property_id BIGINT,
    bedrooms INT,
    bathrooms INT,
    surface DECIMAL(10,2),
    furnished BOOLEAN,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (property_id) REFERENCES properties(id)
);

-- Médias des Propriétés
CREATE TABLE property_media (
    id BIGINT PRIMARY KEY,
    property_id BIGINT,
    type ENUM('image', 'video', 'document'),
    path VARCHAR(255),
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (property_id) REFERENCES properties(id)
);

-- Abonnements
CREATE TABLE subscriptions (
    id BIGINT PRIMARY KEY,
    user_id BIGINT,
    plan ENUM('basic', 'standard', 'premium', 'enterprise'),
    price DECIMAL(10,2),
    starts_at TIMESTAMP,
    expires_at TIMESTAMP,
    status ENUM('active', 'expired', 'cancelled'),
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Messages
CREATE TABLE messages (
    id BIGINT PRIMARY KEY,
    sender_id BIGINT,
    receiver_id BIGINT,
    property_id BIGINT,
    content TEXT,
    read_at TIMESTAMP NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (sender_id) REFERENCES users(id),
    FOREIGN KEY (receiver_id) REFERENCES users(id),
    FOREIGN KEY (property_id) REFERENCES properties(id)
);
```

### Indexation
```sql
-- Optimisation des recherches
CREATE INDEX idx_properties_status ON properties(status);
CREATE INDEX idx_properties_type ON properties(type);
CREATE INDEX idx_properties_price ON properties(price);
CREATE INDEX idx_messages_read_at ON messages(read_at);
CREATE INDEX idx_subscriptions_status ON subscriptions(status);
```

## Sécurité

### Authentification
- Laravel Sanctum pour l'authentification
- Sessions sécurisées
- Protection CSRF
- Rate limiting

### Autorisation
```php
// Exemple de politique d'autorisation
class PropertyPolicy
{
    public function update(User $user, Property $property)
    {
        return $user->id === $property->user_id || $user->isAdmin();
    }
}
```

### Middleware
```php
// Vérification des rôles
class CheckRole
{
    public function handle($request, $next, $role)
    {
        if (!$request->user() || !$request->user()->hasRole($role)) {
            abort(403);
        }
        return $next($request);
    }
}

// Vérification des abonnements
class CheckSubscription
{
    public function handle($request, $next)
    {
        if (!$request->user()->hasActiveSubscription()) {
            return redirect()->route('agent.subscription.show');
        }
        return $next($request);
    }
}
```

## Cache et Performance

### Configuration Cache
```php
// config/cache.php
return [
    'default' => env('CACHE_DRIVER', 'redis'),
    'stores' => [
        'redis' => [
            'driver' => 'redis',
            'connection' => 'cache',
        ],
    ],
];
```

### Exemple de Cache
```php
// Cache des propriétés en vedette
public function getFeaturedProperties()
{
    return Cache::remember('featured_properties', 3600, function () {
        return Property::featured()->with('media')->get();
    });
}
```

## File d'Attente

### Configuration
```php
// config/queue.php
return [
    'default' => env('QUEUE_CONNECTION', 'redis'),
    'connections' => [
        'redis' => [
            'driver' => 'redis',
            'connection' => 'default',
            'queue' => 'default',
            'retry_after' => 90,
        ],
    ],
];
```

### Jobs
```php
// Exemple de job pour le traitement d'image
class ProcessPropertyImage implements ShouldQueue
{
    public function handle()
    {
        // Redimensionnement et optimisation
        // Génération des vignettes
        // Stockage sur le CDN
    }
}
```

## Tests

### Structure
```
tests/
├── Unit/
│   ├── Models/
│   └── Services/
└── Feature/
    ├── Admin/
    ├── Agent/
    └── Public/
```

### Exemple de Test
```php
class PropertyTest extends TestCase
{
    use RefreshDatabase;

    public function test_agent_can_create_property()
    {
        $agent = User::factory()->agent()->create();
        $this->actingAs($agent);

        $response = $this->post('/agent/properties', [
            'title' => 'Test Property',
            'price' => 100000,
            // ...
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('properties', [
            'title' => 'Test Property'
        ]);
    }
}
```

## Déploiement

### Configuration Production
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://monnkama.ga

DB_CONNECTION=mysql
DB_HOST=production-db-host
DB_DATABASE=monnkama_prod

CACHE_DRIVER=redis
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis

MAIL_MAILER=smtp
MAIL_HOST=smtp.postmark.com
```

### Processus de Déploiement
1. Tests automatisés
2. Construction des assets
3. Optimisation du cache
4. Migration de la base de données
5. Déploiement sans interruption

## Monitoring

### Logs
- Logs d'erreurs
- Logs d'accès
- Logs de performance
- Logs de sécurité

### Métriques
- Temps de réponse
- Utilisation des ressources
- Taux d'erreur
- Statistiques d'utilisation

## Conclusion

Cette architecture est conçue pour être :
- Évolutive : facilement extensible
- Maintenable : code organisé et documenté
- Performante : optimisations intégrées
- Sécurisée : bonnes pratiques appliquées
- Testable : couverture de tests complète
