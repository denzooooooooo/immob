<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Administration Carre Premium</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Couleurs du Violet-Rouge -->
    <style>
        :root {
            --violet-primary: #7C3AED;
            --violet-secondary: #A855F7;
            --red-primary: #DC2626;
            --red-secondary: #EF4444;
        }

        body {
            font-family: 'Poppins', sans-serif;
        }

        .sidebar-link.active {
            background: linear-gradient(135deg, var(--violet-primary), var(--red-primary));
            color: white;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--violet-primary), var(--red-primary));
        }

        .btn-secondary {
            background: linear-gradient(135deg, var(--violet-secondary), var(--red-secondary));
        }

        .badge-warning {
            background-color: #FCD116;
        }

        /* Mobile menu styles */
        .mobile-menu-overlay {
            backdrop-filter: blur(4px);
            background-color: rgba(0, 0, 0, 0.5);
        }

        .mobile-sidebar {
            transform: translateX(-100%);
            transition: transform 0.3s ease-in-out;
        }

        .mobile-sidebar.open {
            transform: translateX(0);
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                z-index: 50;
                top: 0;
                left: 0;
                height: 100vh;
                width: 280px;
            }

            .main-content {
                margin-left: 0;
            }
        }
    </style>

    @stack('styles')
</head>
<body class="bg-gray-100">
    <!-- Mobile Menu Overlay -->
    <div id="mobile-menu-overlay" class="mobile-menu-overlay fixed inset-0 z-40 hidden lg:hidden" onclick="closeMobileMenu()"></div>

    <!-- Top Navigation -->
    <nav class="bg-white shadow-lg sticky top-0 z-30">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <!-- Mobile menu button -->
                    <button id="mobile-menu-button" class="lg:hidden mr-4 p-2 rounded-md text-gray-600 hover:text-gray-900 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-violet-500" onclick="toggleMobileMenu()">
                        <i class="fas fa-bars text-xl"></i>
                    </button>

                    <!-- Logo -->
                    <div class="flex-shrink-0 flex items-center">
                        <a href="{{ route('admin.dashboard') }}" class="text-2xl font-bold bg-gradient-to-r from-violet-600 to-red-500 bg-clip-text text-transparent">
                            Carre Premium
                        </a>
                    </div>
                </div>

                <!-- Right Navigation -->
                <div class="flex items-center space-x-4">
                    <!-- Notifications -->
                    <div class="relative">
                        <button id="notifications-button" class="relative p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-full focus:outline-none focus:ring-2 focus:ring-violet-500" onclick="toggleNotifications()">
                            <i class="fas fa-bell text-xl"></i>
                            @if($stats['unread_messages'] ?? 0 > 0)
                                <span class="absolute -top-1 -right-1 bg-red-500 text-white rounded-full text-xs w-5 h-5 flex items-center justify-center font-bold">
                                    {{ $stats['unread_messages'] ?? 0 }}
                                </span>
                            @endif
                        </button>

                        <!-- Notifications Dropdown -->
                        <div id="notifications-dropdown" class="hidden absolute right-0 mt-3 w-80 bg-white rounded-lg shadow-xl border border-gray-200 py-2 z-50">
                            <div class="px-4 py-3 border-b border-gray-200">
                                <h3 class="text-sm font-semibold text-gray-900">Notifications</h3>
                            </div>
                            <div class="max-h-64 overflow-y-auto">
                                @if($latestMessages ?? collect()->count() > 0)
                                    @foreach($latestMessages->take(5) as $message)
                                        <div class="px-4 py-3 border-b border-gray-100 hover:bg-gray-50 cursor-pointer">
                                            <div class="flex items-start">
                                                <img src="https://ui-avatars.com/api/?name={{ urlencode($message->sender->name ?? 'User') }}&color=7F9CF5&background=EBF4FF" alt="Avatar" class="w-8 h-8 rounded-full mr-3">
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm font-medium text-gray-900 truncate">
                                                        Nouveau message de {{ $message->sender->name ?? 'Utilisateur' }}
                                                    </p>
                                                    <p class="text-xs text-gray-500 truncate">
                                                        {{ $message->property->title ?? 'Propriété' }}
                                                    </p>
                                                    <p class="text-xs text-gray-400">
                                                        {{ $message->created_at->diffForHumans() }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="px-4 py-8 text-center text-gray-500">
                                        <i class="fas fa-bell-slash text-2xl mb-2"></i>
                                        <p class="text-sm">Aucune notification</p>
                                    </div>
                                @endif
                            </div>
                            <div class="px-4 py-2 border-t border-gray-200">
                                <a href="{{ route('admin.messages.index') }}" class="text-sm text-violet-600 hover:text-violet-800 font-medium">
                                    Voir tous les messages
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Profile Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center space-x-2 p-2 rounded-lg hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-violet-500">
                            <img src="{{ auth()->user() ? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) . '&color=7F9CF5&background=EBF4FF' : 'https://ui-avatars.com/api/?name=Admin&color=7F9CF5&background=EBF4FF' }}" alt="Avatar" class="h-8 w-8 rounded-full">
                            <span class="hidden sm:block text-gray-700 font-medium">{{ auth()->user()->name ?? 'Admin' }}</span>
                            <i class="fas fa-chevron-down text-gray-400 text-sm"></i>
                        </button>

                        <!-- Dropdown Menu -->
                        <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-xl border border-gray-200 py-2 z-50">
                            <div class="px-4 py-3 border-b border-gray-200">
                                <p class="text-sm font-semibold text-gray-900">{{ auth()->user()->name ?? 'Admin' }}</p>
                                <p class="text-sm text-gray-500">{{ auth()->user()->email ?? 'admin@carrepremium.ci' }}</p>
                            </div>
                            <a href="#" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-violet-50 hover:text-violet-600 transition-colors">
                                <i class="fas fa-user mr-3 text-gray-400"></i>
                                Mon profil
                            </a>
                            <a href="{{ route('admin.settings.index') }}" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-violet-50 hover:text-violet-600 transition-colors">
                                <i class="fas fa-cog mr-3 text-gray-400"></i>
                                Paramètres
                            </a>
                            <hr class="my-2 border-gray-200">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="flex items-center w-full text-left px-4 py-3 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                    <i class="fas fa-sign-out-alt mr-3"></i>
                                    Déconnexion
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="flex min-h-screen bg-gray-100">
        <!-- Sidebar -->
        <div id="sidebar" class="sidebar w-64 bg-white shadow-lg lg:block hidden">
            <nav class="mt-8 px-4">
                <a href="{{ route('admin.dashboard') }}" class="group flex items-center px-3 py-3 text-base leading-6 font-medium rounded-lg text-gray-600 hover:text-gray-900 hover:bg-gray-50 focus:outline-none focus:text-gray-900 focus:bg-gray-100 transition ease-in-out duration-150 {{ request()->routeIs('admin.dashboard') ? 'sidebar-link active' : '' }}">
                    <i class="fas fa-home mr-3 text-gray-400 group-hover:text-gray-500"></i>
                    Dashboard
                </a>

                <a href="{{ route('admin.properties.index') }}" class="mt-2 group flex items-center px-3 py-3 text-base leading-6 font-medium rounded-lg text-gray-600 hover:text-gray-900 hover:bg-gray-50 focus:outline-none focus:text-gray-900 focus:bg-gray-100 transition ease-in-out duration-150 {{ request()->routeIs('admin.properties.*') ? 'sidebar-link active' : '' }}">
                    <i class="fas fa-building mr-3 text-gray-400 group-hover:text-gray-500"></i>
                    Propriétés
                </a>

                <a href="{{ route('admin.users.index') }}" class="mt-2 group flex items-center px-3 py-3 text-base leading-6 font-medium rounded-lg text-gray-600 hover:text-gray-900 hover:bg-gray-50 focus:outline-none focus:text-gray-900 focus:bg-gray-100 transition ease-in-out duration-150 {{ request()->routeIs('admin.users.*') ? 'sidebar-link active' : '' }}">
                    <i class="fas fa-users mr-3 text-gray-400 group-hover:text-gray-500"></i>
                    Utilisateurs
                </a>

                <a href="{{ route('admin.subscriptions.index') }}" class="mt-2 group flex items-center px-3 py-3 text-base leading-6 font-medium rounded-lg text-gray-600 hover:text-gray-900 hover:bg-gray-50 focus:outline-none focus:text-gray-900 focus:bg-gray-100 transition ease-in-out duration-150 {{ request()->routeIs('admin.subscriptions.*') ? 'sidebar-link active' : '' }}">
                    <i class="fas fa-credit-card mr-3 text-gray-400 group-hover:text-gray-500"></i>
                    Abonnements
                </a>

                <a href="{{ route('admin.messages.index') }}" class="mt-2 group flex items-center px-3 py-3 text-base leading-6 font-medium rounded-lg text-gray-600 hover:text-gray-900 hover:bg-gray-50 focus:outline-none focus:text-gray-900 focus:bg-gray-100 transition ease-in-out duration-150 {{ request()->routeIs('admin.messages.*') ? 'sidebar-link active' : '' }}">
                    <i class="fas fa-envelope mr-3 text-gray-400 group-hover:text-gray-500"></i>
                    Messages
                </a>

                <div class="mt-8">
                    <h3 class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        Configuration
                    </h3>

                    <a href="{{ route('admin.locations.cities.index') }}" class="mt-2 group flex items-center px-3 py-3 text-base leading-6 font-medium rounded-lg text-gray-600 hover:text-gray-900 hover:bg-gray-50 focus:outline-none focus:text-gray-900 focus:bg-gray-100 transition ease-in-out duration-150 {{ request()->routeIs('admin.locations.cities.*') ? 'sidebar-link active' : '' }}">
                        <i class="fas fa-city mr-3 text-gray-400 group-hover:text-gray-500"></i>
                        Villes
                    </a>

                    <a href="{{ route('admin.locations.neighborhoods.index') }}" class="mt-2 group flex items-center px-3 py-3 text-base leading-6 font-medium rounded-lg text-gray-600 hover:text-gray-900 hover:bg-gray-50 focus:outline-none focus:text-gray-900 focus:bg-gray-100 transition ease-in-out duration-150 {{ request()->routeIs('admin.locations.neighborhoods.*') ? 'sidebar-link active' : '' }}">
                        <i class="fas fa-map-marker-alt mr-3 text-gray-400 group-hover:text-gray-500"></i>
                        Quartiers
                    </a>

                    <a href="{{ route('admin.settings.index') }}" class="mt-2 group flex items-center px-3 py-3 text-base leading-6 font-medium rounded-lg text-gray-600 hover:text-gray-900 hover:bg-gray-50 focus:outline-none focus:text-gray-900 focus:bg-gray-100 transition ease-in-out duration-150 {{ request()->routeIs('admin.settings.*') ? 'sidebar-link active' : '' }}">
                        <i class="fas fa-cog mr-3 text-gray-400 group-hover:text-gray-500"></i>
                        Paramètres
                    </a>
                </div>
            </nav>
        </div>

        <!-- Mobile Sidebar -->
        <div id="mobile-sidebar" class="mobile-sidebar sidebar fixed w-64 bg-white shadow-lg lg:hidden z-50">
            <div class="flex items-center justify-between p-4 border-b border-gray-200">
                <span class="text-lg font-semibold text-gray-900">Menu</span>
                <button onclick="closeMobileMenu()" class="p-2 rounded-md text-gray-600 hover:text-gray-900 hover:bg-gray-100">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <nav class="mt-4 px-4">
                <a href="{{ route('admin.dashboard') }}" class="group flex items-center px-3 py-3 text-base leading-6 font-medium rounded-lg text-gray-600 hover:text-gray-900 hover:bg-gray-50 focus:outline-none focus:text-gray-900 focus:bg-gray-100 transition ease-in-out duration-150 {{ request()->routeIs('admin.dashboard') ? 'sidebar-link active' : '' }}">
                    <i class="fas fa-home mr-3 text-gray-400 group-hover:text-gray-500"></i>
                    Dashboard
                </a>

                <a href="{{ route('admin.properties.index') }}" class="mt-2 group flex items-center px-3 py-3 text-base leading-6 font-medium rounded-lg text-gray-600 hover:text-gray-900 hover:bg-gray-50 focus:outline-none focus:text-gray-900 focus:bg-gray-100 transition ease-in-out duration-150 {{ request()->routeIs('admin.properties.*') ? 'sidebar-link active' : '' }}">
                    <i class="fas fa-building mr-3 text-gray-400 group-hover:text-gray-500"></i>
                    Propriétés
                </a>

                <a href="{{ route('admin.users.index') }}" class="mt-2 group flex items-center px-3 py-3 text-base leading-6 font-medium rounded-lg text-gray-600 hover:text-gray-900 hover:bg-gray-50 focus:outline-none focus:text-gray-900 focus:bg-gray-100 transition ease-in-out duration-150 {{ request()->routeIs('admin.users.*') ? 'sidebar-link active' : '' }}">
                    <i class="fas fa-users mr-3 text-gray-400 group-hover:text-gray-500"></i>
                    Utilisateurs
                </a>

                <a href="{{ route('admin.subscriptions.index') }}" class="mt-2 group flex items-center px-3 py-3 text-base leading-6 font-medium rounded-lg text-gray-600 hover:text-gray-900 hover:bg-gray-50 focus:outline-none focus:text-gray-900 focus:bg-gray-100 transition ease-in-out duration-150 {{ request()->routeIs('admin.subscriptions.*') ? 'sidebar-link active' : '' }}">
                    <i class="fas fa-credit-card mr-3 text-gray-400 group-hover:text-gray-500"></i>
                    Abonnements
                </a>

                <a href="{{ route('admin.messages.index') }}" class="mt-2 group flex items-center px-3 py-3 text-base leading-6 font-medium rounded-lg text-gray-600 hover:text-gray-900 hover:bg-gray-50 focus:outline-none focus:text-gray-900 focus:bg-gray-100 transition ease-in-out duration-150 {{ request()->routeIs('admin.messages.*') ? 'sidebar-link active' : '' }}">
                    <i class="fas fa-envelope mr-3 text-gray-400 group-hover:text-gray-500"></i>
                    Messages
                </a>

                <div class="mt-8">
                    <h3 class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        Configuration
                    </h3>

                    <a href="{{ route('admin.locations.cities.index') }}" class="mt-2 group flex items-center px-3 py-3 text-base leading-6 font-medium rounded-lg text-gray-600 hover:text-gray-900 hover:bg-gray-50 focus:outline-none focus:text-gray-900 focus:bg-gray-100 transition ease-in-out duration-150 {{ request()->routeIs('admin.locations.cities.*') ? 'sidebar-link active' : '' }}">
                        <i class="fas fa-city mr-3 text-gray-400 group-hover:text-gray-500"></i>
                        Villes
                    </a>

                    <a href="{{ route('admin.locations.neighborhoods.index') }}" class="mt-2 group flex items-center px-3 py-3 text-base leading-6 font-medium rounded-lg text-gray-600 hover:text-gray-900 hover:bg-gray-50 focus:outline-none focus:text-gray-900 focus:bg-gray-100 transition ease-in-out duration-150 {{ request()->routeIs('admin.locations.neighborhoods.*') ? 'sidebar-link active' : '' }}">
                        <i class="fas fa-map-marker-alt mr-3 text-gray-400 group-hover:text-gray-500"></i>
                        Quartiers
                    </a>

                    <a href="{{ route('admin.settings.index') }}" class="mt-2 group flex items-center px-3 py-3 text-base leading-6 font-medium rounded-lg text-gray-600 hover:text-gray-900 hover:bg-gray-50 focus:outline-none focus:text-gray-900 focus:bg-gray-100 transition ease-in-out duration-150 {{ request()->routeIs('admin.settings.*') ? 'sidebar-link active' : '' }}">
                        <i class="fas fa-cog mr-3 text-gray-400 group-hover:text-gray-500"></i>
                        Paramètres
                    </a>
                </div>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="main-content flex-1 lg:ml-0">
            <main class="py-6">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 md:px-8">
                    <!-- Page Header -->
                    <div class="pb-5 border-b border-gray-200 sm:flex sm:items-center sm:justify-between">
                        <h3 class="text-2xl leading-6 font-medium text-gray-900">
                            @yield('header')
                        </h3>
                        <div class="mt-3 sm:mt-0 sm:ml-4">
                            @yield('actions')
                        </div>
                    </div>

                    <!-- Page Content -->
                    <div class="py-6">
                        @if(session('success'))
                            <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                                <span class="block sm:inline">{{ session('success') }}</span>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                                <span class="block sm:inline">{{ session('error') }}</span>
                            </div>
                        @endif

                        @yield('content')
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Alpine.js -->
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Custom Scripts -->
    <script>
        // Mobile menu functions
        function toggleMobileMenu() {
            const sidebar = document.getElementById('mobile-sidebar');
            const overlay = document.getElementById('mobile-menu-overlay');
            sidebar.classList.toggle('open');
            overlay.classList.toggle('hidden');
        }

        function closeMobileMenu() {
            const sidebar = document.getElementById('mobile-sidebar');
            const overlay = document.getElementById('mobile-menu-overlay');
            sidebar.classList.remove('open');
            overlay.classList.add('hidden');
        }

        // Notifications dropdown
        function toggleNotifications() {
            const dropdown = document.getElementById('notifications-dropdown');
            dropdown.classList.toggle('hidden');
        }

        // Close notifications when clicking outside
        document.addEventListener('click', function(event) {
            const button = document.getElementById('notifications-button');
            const dropdown = document.getElementById('notifications-dropdown');

            if (!button.contains(event.target) && !dropdown.contains(event.target)) {
                dropdown.classList.add('hidden');
            }
        });

        // Close mobile menu on window resize
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 1024) {
                closeMobileMenu();
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
