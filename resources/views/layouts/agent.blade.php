<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Espace Agent Monnkama</title>
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Couleurs du Côte d'Ivoire -->
    <style>
        :root {
            --violet-600: #009639;
            --violet-400: #FCD116;
            --violet-600: #3A75C4;
        }
        
        body {
            font-family: 'Poppins', sans-serif;
        }
        
        .sidebar-link.active {
            background-color: var(--violet-600);
            color: white;
        }
        
        .btn-primary {
            background-color: var(--violet-600);
        }
        
        .btn-secondary {
            background-color: var(--violet-600);
        }
        
        .badge-warning {
            background-color: var(--violet-400);
        }
    </style>
    
    @stack('styles')
</head>
<body class="bg-gray-100">
    <!-- Top Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <!-- Logo -->
                    <div class="flex-shrink-0 flex items-center">
                        <a href="{{ route('agent.dashboard') }}" class="text-2xl font-bold text-green-600">
                            Monnkama
                        </a>
                        <span class="ml-2 text-sm text-gray-500">Agent</span>
                    </div>
                </div>
                
                <!-- Right Navigation -->
                <div class="flex items-center">
                    <!-- Subscription Status -->
                    @if(auth()->user()->current_subscription)
                        <div class="mr-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                {{ ucfirst(auth()->user()->current_subscription->plan) }}
                            </span>
                        </div>
                    @else
                        <div class="mr-4">
                            <a href="{{ route('agent.subscription.show') }}" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                Aucun abonnement
                            </a>
                        </div>
                    @endif
                    
                    <!-- Notifications -->
                    <div class="relative mr-4">
                        <button class="text-gray-600 hover:text-gray-800">
                            <i class="fas fa-bell"></i>
                            @if(auth()->user()->unread_messages_count > 0)
                                <span class="absolute -top-1 -right-1 bg-red-500 text-white rounded-full text-xs w-4 h-4 flex items-center justify-center">
                                    {{ auth()->user()->unread_messages_count }}
                                </span>
                            @endif
                        </button>
                    </div>
                    
                    <!-- Profile Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center">
                            @if(auth()->user()->avatar)
                                <img src="{{ auth()->user()->avatar }}" alt="Avatar" class="h-8 w-8 rounded-full">
                            @else
                                <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&color=7F9CF5&background=EBF4FF" alt="Avatar" class="h-8 w-8 rounded-full">
                            @endif
                            <span class="ml-2 text-gray-700">{{ auth()->user()->name }}</span>
                        </button>
                        
                        <!-- Dropdown Menu -->
                        <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1">
                            <a href="{{ route('agent.profile') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Mon profil</a>
                            <a href="{{ route('agent.subscription.show') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Mon abonnement</a>
                            <a href="{{ route('home') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Voir le site</a>
                            <hr class="my-1">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                    Déconnexion
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    
    <div class="flex h-screen bg-gray-100">
        <!-- Sidebar -->
        <div class="w-64 bg-white shadow-lg">
            <nav class="mt-5 px-2">
                <a href="{{ route('agent.dashboard') }}" class="group flex items-center px-2 py-2 text-base leading-6 font-medium rounded-md text-gray-600 hover:text-gray-900 hover:bg-gray-50 focus:outline-none focus:text-gray-900 focus:bg-gray-100 transition ease-in-out duration-150 {{ request()->routeIs('agent.dashboard') ? 'sidebar-link active' : '' }}">
                    <i class="fas fa-home mr-3 text-gray-400 group-hover:text-gray-500"></i>
                    Dashboard
                </a>
                
                <a href="{{ route('agent.properties.index') }}" class="mt-1 group flex items-center px-2 py-2 text-base leading-6 font-medium rounded-md text-gray-600 hover:text-gray-900 hover:bg-gray-50 focus:outline-none focus:text-gray-900 focus:bg-gray-100 transition ease-in-out duration-150 {{ request()->routeIs('agent.properties.*') ? 'sidebar-link active' : '' }}">
                    <i class="fas fa-building mr-3 text-gray-400 group-hover:text-gray-500"></i>
                    Mes Propriétés
                    @if(auth()->user()->properties_count > 0)
                        <span class="ml-auto bg-gray-200 text-gray-600 text-xs rounded-full px-2 py-1">
                            {{ auth()->user()->properties_count }}
                        </span>
                    @endif
                </a>
                
                <a href="{{ route('agent.messages.index') }}" class="mt-1 group flex items-center px-2 py-2 text-base leading-6 font-medium rounded-md text-gray-600 hover:text-gray-900 hover:bg-gray-50 focus:outline-none focus:text-gray-900 focus:bg-gray-100 transition ease-in-out duration-150 {{ request()->routeIs('agent.messages.*') ? 'sidebar-link active' : '' }}">
                    <i class="fas fa-envelope mr-3 text-gray-400 group-hover:text-gray-500"></i>
                    Messages
                    @if(auth()->user()->unread_messages_count > 0)
                        <span class="ml-auto bg-red-500 text-white text-xs rounded-full px-2 py-1">
                            {{ auth()->user()->unread_messages_count }}
                        </span>
                    @endif
                </a>
                
                <a href="{{ route('agent.subscription.show') }}" class="mt-1 group flex items-center px-2 py-2 text-base leading-6 font-medium rounded-md text-gray-600 hover:text-gray-900 hover:bg-gray-50 focus:outline-none focus:text-gray-900 focus:bg-gray-100 transition ease-in-out duration-150 {{ request()->routeIs('agent.subscription.*') ? 'sidebar-link active' : '' }}">
                    <i class="fas fa-credit-card mr-3 text-gray-400 group-hover:text-gray-500"></i>
                    Abonnement
                </a>
                
                <div class="mt-8">
                    <h3 class="px-3 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        Compte
                    </h3>
                    
                    <a href="{{ route('agent.profile') }}" class="mt-1 group flex items-center px-2 py-2 text-base leading-6 font-medium rounded-md text-gray-600 hover:text-gray-900 hover:bg-gray-50 focus:outline-none focus:text-gray-900 focus:bg-gray-100 transition ease-in-out duration-150 {{ request()->routeIs('agent.profile') ? 'sidebar-link active' : '' }}">
                        <i class="fas fa-user mr-3 text-gray-400 group-hover:text-gray-500"></i>
                        Mon Profil
                    </a>
                    
                    <a href="{{ route('agent.statistics') }}" class="mt-1 group flex items-center px-2 py-2 text-base leading-6 font-medium rounded-md text-gray-600 hover:text-gray-900 hover:bg-gray-50 focus:outline-none focus:text-gray-900 focus:bg-gray-100 transition ease-in-out duration-150 {{ request()->routeIs('agent.statistics') ? 'sidebar-link active' : '' }}">
                        <i class="fas fa-chart-bar mr-3 text-gray-400 group-hover:text-gray-500"></i>
                        Statistiques
                    </a>
                </div>
                
                <!-- Subscription Progress -->
                @if(auth()->user()->current_subscription)
                    <div class="mt-8 px-3">
                        <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">
                            Utilisation
                        </h3>
                        <div class="bg-gray-200 rounded-full h-2">
                            @php
                                $percentage = auth()->user()->current_subscription->properties_limit > 0 
                                    ? (auth()->user()->current_subscription->properties_used / auth()->user()->current_subscription->properties_limit) * 100 
                                    : 0;
                            @endphp
                            <div class="bg-green-600 h-2 rounded-full" style="width: {{ min($percentage, 100) }}%"></div>
                        </div>
                        <p class="text-xs text-gray-500 mt-2">
                            {{ auth()->user()->current_subscription->properties_used }} / {{ auth()->user()->current_subscription->properties_limit }} propriétés
                        </p>
                    </div>
                @endif
            </nav>
        </div>
        
        <!-- Main Content -->
        <div class="flex-1 overflow-auto">
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
    @stack('scripts')
</body>
</html>
