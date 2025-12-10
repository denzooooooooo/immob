<!DOCTYPE html>
<html lang="fr" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Carre Premium - Immobilier en Côte d\'Ivoire')</title>
    <meta name="description" content="@yield('description', 'La première plateforme immobilière 100% ivoirienne. Trouvez votre prochain chez-vous en Côte d\'Ivoire.')">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="user-authenticated" content="{{ auth()->check() ? 'true' : 'false' }}">
    <meta name="login-url" content="{{ route('login') }}">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        'violet': {
                            '50': '#faf5ff',
                            '100': '#f3e8ff',
                            '200': '#e9d5ff',
                            '300': '#d8b4fe',
                            '400': '#c084fc',
                            '500': '#a855f7',
                            '600': '#9333ea',
                            '700': '#7c3aed',
                            '800': '#6b21a8',
                            '900': '#581c87'
                        },
                        'primary': {
                            50: '#faf5ff',
                            100: '#f3e8ff',
                            200: '#e9d5ff',
                            300: '#d8b4fe',
                            400: '#c084fc',
                            500: '#a855f7',
                            600: '#9333ea',
                            700: '#7c3aed',
                            800: '#6b21a8',
                            900: '#581c87',
                        }
                    },
                    fontFamily: {
                        'sans': ['Inter', 'Poppins', 'sans-serif'],
                        'display': ['Poppins', 'sans-serif'],
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-in-out',
                        'slide-up': 'slideUp 0.5s ease-out',
                        'slide-down': 'slideDown 0.3s ease-out',
                        'scale-in': 'scaleIn 0.3s ease-out',
                        'bounce-slow': 'bounce 2s infinite',
                        'pulse-slow': 'pulse 3s infinite',
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' },
                        },
                        slideUp: {
                            '0%': { transform: 'translateY(20px)', opacity: '0' },
                            '100%': { transform: 'translateY(0)', opacity: '1' },
                        },
                        slideDown: {
                            '0%': { transform: 'translateY(-10px)', opacity: '0' },
                            '100%': { transform: 'translateY(0)', opacity: '1' },
                        },
                        scaleIn: {
                            '0%': { transform: 'scale(0.95)', opacity: '0' },
                            '100%': { transform: 'scale(1)', opacity: '1' },
                        }
                    },
                    backdropBlur: {
                        xs: '2px',
                    }
                }
            }
        }
    </script>
    
    <!-- Custom Styles -->
    <style>
        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .gradient-text {
            background: linear-gradient(135deg, #009639, #3A75C4);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .hover-lift {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .hover-lift:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        .nav-link {
            position: relative;
            overflow: hidden;
        }
        
        .nav-link::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background: linear-gradient(90deg, #009639, #3A75C4);
            transition: width 0.3s ease;
        }
        
        .nav-link:hover::before {
            width: 100%;
        }
        
        .btn-gradient {
            background: linear-gradient(135deg, #009639, #3A75C4);
            background-size: 200% 200%;
            animation: gradient-shift 3s ease infinite;
        }
        
        @keyframes gradient-shift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        
        .dropdown-menu {
            transform: translateY(-10px);
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .dropdown:hover .dropdown-menu {
            transform: translateY(0);
            opacity: 1;
            visibility: visible;
        }
        
        /* Mobile Menu Styles */
        .mobile-menu-overlay {
            backdrop-filter: blur(8px);
            background-color: rgba(0, 0, 0, 0.5);
            transition: opacity 0.3s ease-in-out;
        }
        
        .mobile-menu-overlay.show {
            opacity: 1;
        }
        
        .mobile-menu-container {
            transform: translateX(100%);
            transition: transform 0.3s ease-in-out;
        }
        
        .mobile-menu-container.open {
            transform: translateX(0);
        }
        
        /* Hamburger Animation */
        .hamburger-line {
            transition: all 0.3s ease-in-out;
        }
        
        .hamburger.active .hamburger-line:nth-child(1) {
            transform: rotate(45deg) translate(6px, 6px);
        }
        
        .hamburger.active .hamburger-line:nth-child(2) {
            opacity: 0;
        }
        
        .hamburger.active .hamburger-line:nth-child(3) {
            transform: rotate(-45deg) translate(6px, -6px);
        }
        
        /* Mobile Dropdown */
        .mobile-dropdown-content {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-in-out;
        }
        
        .mobile-dropdown-content.open {
            max-height: 500px;
        }
        
        /* Floating animations for hero section */
        @keyframes float {
            0%, 100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-20px);
            }
        }
        
        @keyframes float-delayed {
            0%, 100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-15px);
            }
        }
        
        .animate-float {
            animation: float 6s ease-in-out infinite;
        }
        
        .animate-float-delayed {
            animation: float-delayed 8s ease-in-out infinite;
            animation-delay: 2s;
        }
        
        /* Enhanced property card effects */
        .property-card {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .property-card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }
        
        /* Improved search form styling */
        .search-form-container {
            backdrop-filter: blur(20px);
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
        }
        
        /* Statistics section enhancements */
        .stat-card {
            transition: all 0.3s ease;
        }
        
        .stat-card:hover {
            transform: translateY(-5px);
        }
        
        /* Parallax effect for hero background */
        .hero-bg {
            will-change: transform;
        }
        
        /* Smooth scroll behavior */
        html {
            scroll-behavior: smooth;
        }
        
        /* Loading animation for images */
        .image-loading {
            background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
            background-size: 200% 100%;
            animation: loading 1.5s infinite;
        }
        
        @keyframes loading {
            0% {
                background-position: 200% 0;
            }
            100% {
                background-position: -200% 0;
            }
        }

        /* Prevent body scroll when mobile menu is open */
        body.mobile-menu-open {
            overflow: hidden;
        }
    </style>
    
    @stack('styles')
</head>
<body class="font-sans bg-gray-50">
    <!-- Mobile Menu Overlay -->
    <div id="mobile-overlay" class="mobile-menu-overlay fixed inset-0 z-40 hidden" onclick="closeMobileMenu()"></div>

    <!-- Navigation -->
    <nav class="bg-white/95 backdrop-blur-lg shadow-lg sticky top-0 z-50 border-b border-white/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16 sm:h-20">
                <!-- Logo -->
                <div class="flex items-center flex-shrink-0">
                    <a href="{{ route('home') }}" class="flex items-center space-x-2 sm:space-x-3 group">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-violet-600 to-violet-800 rounded-xl flex items-center justify-center shadow-lg group-hover:shadow-xl transition-all duration-300">
                            <i class="fas fa-home text-white text-lg sm:text-xl"></i>
                        </div>
                        <div class="hidden sm:block">
                            <span class="text-xl sm:text-2xl font-bold bg-gradient-to-r from-violet-600 to-violet-800 bg-clip-text text-transparent">
                                Carre Premium
                            </span>
                            <div class="text-xs text-gray-500 -mt-1">Immobilier</div>
                        </div>
                        <div class="block sm:hidden">
                            <span class="text-lg font-bold bg-gradient-to-r from-violet-600 to-violet-800 bg-clip-text text-transparent">
                                Carre
                            </span>
                        </div>
                    </a>
                </div>

                <!-- Desktop Navigation Links -->
                <div class="hidden lg:flex items-center space-x-1">
                    <a href="{{ route('home') }}" class="nav-link px-4 py-2 rounded-lg text-gray-700 hover:text-violet-600 hover:bg-violet-50 transition-all duration-300 font-medium relative flex items-center">
                        <i class="fas fa-home mr-2 text-sm"></i>
                        <span>Accueil</span>
                    </a>
                    <a href="{{ route('properties.index') }}" class="nav-link px-4 py-2 rounded-lg text-gray-700 hover:text-violet-600 hover:bg-violet-50 transition-all duration-300 font-medium relative flex items-center">
                        <i class="fas fa-building mr-2 text-sm"></i>
                        <span>Propriétés</span>
                    </a>
                    <a href="{{ route('search.index') }}" class="nav-link px-4 py-2 rounded-lg text-gray-700 hover:text-violet-600 hover:bg-violet-50 transition-all duration-300 font-medium relative flex items-center">
                        <i class="fas fa-search mr-2 text-sm"></i>
                        <span>Recherche</span>
                    </a>
                    <a href="{{ route('about') }}" class="nav-link px-4 py-2 rounded-lg text-gray-700 hover:text-violet-600 hover:bg-violet-50 transition-all duration-300 font-medium relative flex items-center">
                        <i class="fas fa-info-circle mr-2 text-sm"></i>
                        <span>À propos</span>
                    </a>
                    <a href="{{ route('contact') }}" class="nav-link px-4 py-2 rounded-lg text-gray-700 hover:text-violet-600 hover:bg-violet-50 transition-all duration-300 font-medium relative flex items-center">
                        <i class="fas fa-envelope mr-2 text-sm"></i>
                        <span>Contact</span>
                    </a>
                </div>

                <!-- Desktop Auth Buttons -->
                <div class="hidden lg:flex items-center space-x-3">
                    @guest
                        <a href="{{ route('login') }}" class="px-4 py-2 rounded-lg text-violet-600 hover:text-violet-700 hover:bg-violet-50 transition-all duration-300 font-medium">
                            <i class="fas fa-sign-in-alt mr-2"></i>
                            <span class="hidden xl:inline">Connexion</span>
                            <span class="xl:hidden">Login</span>
                        </a>
                        <a href="{{ route('register') }}" class="bg-gradient-to-r from-violet-600 to-violet-800 text-white px-4 xl:px-6 py-2.5 rounded-lg hover:shadow-lg hover:shadow-violet-500/25 transform hover:scale-105 transition-all duration-300 font-medium">
                            <i class="fas fa-user-plus mr-2"></i>
                            <span class="hidden xl:inline">Inscription</span>
                            <span class="xl:hidden">Sign up</span>
                        </a>
                    @else
                        <div class="relative dropdown group">
                            <button class="flex items-center space-x-2 px-3 py-2 rounded-lg hover:bg-gray-50 transition-all duration-300">
                                <img src="{{ auth()->user()->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) }}"
                                     alt="{{ auth()->user()->name }}"
                                     class="w-8 h-8 rounded-full border-2 border-violet-200">
                                <span class="hidden xl:block font-medium text-gray-700 max-w-[120px] truncate">{{ auth()->user()->name }}</span>
                                <i class="fas fa-chevron-down text-xs text-gray-500 group-hover:text-violet-600 transition-colors duration-200"></i>
                            </button>

                            <!-- Desktop Dropdown Menu -->
                            <div class="dropdown-menu absolute right-0 mt-3 w-56 bg-white/95 backdrop-blur-lg rounded-xl shadow-xl border border-white/20 py-2">
                                <div class="px-4 py-3 border-b border-gray-100">
                                    <p class="text-sm font-medium text-gray-900 truncate">{{ auth()->user()->name }}</p>
                                    <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
                                </div>
                                <a href="{{ route('profile') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-violet-50 hover:text-violet-600 transition-all duration-200">
                                    <i class="fas fa-user mr-3 text-sm"></i>
                                    <span>Mon Profil</span>
                                </a>
                                <a href="{{ route('favorites.index') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-violet-50 hover:text-violet-600 transition-all duration-200">
                                    <i class="fas fa-heart mr-3 text-sm"></i>
                                    <span>Mes Favoris</span>
                                </a>
                                @if(auth()->user()->role === 'admin')
                                    <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-violet-50 hover:text-violet-600 transition-all duration-200">
                                        <i class="fas fa-tachometer-alt mr-3 text-sm"></i>
                                        <span>Administration</span>
                                    </a>
                                @elseif(auth()->user()->role === 'agent')
                                    <a href="{{ route('agent.dashboard') }}" class="flex items-center px-4 py-3 text-gray-700 hover:bg-violet-50 hover:text-violet-600 transition-all duration-200">
                                        <i class="fas fa-tachometer-alt mr-3 text-sm"></i>
                                        <span>Tableau de bord</span>
                                    </a>
                                @endif
                                <hr class="my-2 border-gray-100">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="flex items-center w-full text-left px-4 py-3 text-red-600 hover:bg-red-50 transition-all duration-200">
                                        <i class="fas fa-sign-out-alt mr-3 text-sm"></i>
                                        <span>Déconnexion</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endguest
                </div>

                <!-- Mobile menu button -->
                <div class="lg:hidden flex items-center">
                    <button id="mobile-menu-button" class="hamburger p-2 rounded-lg text-gray-700 hover:text-violet-600 hover:bg-violet-50 transition-all duration-300" onclick="toggleMobileMenu()">
                        <div class="w-6 h-5 flex flex-col justify-between">
                            <span class="hamburger-line w-full h-0.5 bg-current rounded-full"></span>
                            <span class="hamburger-line w-full h-0.5 bg-current rounded-full"></span>
                            <span class="hamburger-line w-full h-0.5 bg-current rounded-full"></span>
                        </div>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Mobile Menu Sidebar -->
    <div id="mobile-menu" class="mobile-menu-container fixed top-0 right-0 h-full w-80 max-w-[85vw] bg-white shadow-2xl z-50 overflow-y-auto">
        <div class="p-6">
            <!-- Mobile Menu Header -->
            <div class="flex items-center justify-between mb-8">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-violet-600 to-violet-800 rounded-xl flex items-center justify-center">
                        <i class="fas fa-home text-white text-lg"></i>
                    </div>
                    <div>
                        <span class="text-lg font-bold bg-gradient-to-r from-violet-600 to-violet-800 bg-clip-text text-transparent">
                            Carre Premium
                        </span>
                        <div class="text-xs text-gray-500 -mt-1">Menu</div>
                    </div>
                </div>
                <button onclick="closeMobileMenu()" class="p-2 rounded-lg text-gray-500 hover:text-gray-700 hover:bg-gray-100 transition-all duration-300">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <!-- User Info (if authenticated) -->
            @auth
                <div class="mb-6 p-4 bg-gradient-to-r from-violet-50 to-violet-100 rounded-xl">
                    <div class="flex items-center space-x-3 mb-3">
                        <img src="{{ auth()->user()->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) }}"
                             alt="{{ auth()->user()->name }}"
                             class="w-12 h-12 rounded-full border-2 border-violet-200">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-900 truncate">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-600 truncate">{{ auth()->user()->email }}</p>
                        </div>
                    </div>
                    <button onclick="toggleMobileDropdown()" class="w-full flex items-center justify-between px-3 py-2 bg-white rounded-lg text-sm font-medium text-violet-600 hover:bg-violet-50 transition-all duration-200">
                        <span>Mon compte</span>
                        <i id="dropdown-icon" class="fas fa-chevron-down text-xs transition-transform duration-200"></i>
                    </button>
                    <div id="mobile-dropdown" class="mobile-dropdown-content mt-2 bg-white rounded-lg overflow-hidden">
                        <a href="{{ route('profile') }}" class="flex items-center px-3 py-2.5 text-sm text-gray-700 hover:bg-violet-50 hover:text-violet-600 transition-all duration-200" onclick="closeMobileMenu()">
                            <i class="fas fa-user mr-3 text-xs"></i>
                            <span>Mon Profil</span>
                        </a>
                        <a href="{{ route('favorites.index') }}" class="flex items-center px-3 py-2.5 text-sm text-gray-700 hover:bg-violet-50 hover:text-violet-600 transition-all duration-200" onclick="closeMobileMenu()">
                            <i class="fas fa-heart mr-3 text-xs"></i>
                            <span>Mes Favoris</span>
                        </a>
                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="flex items-center px-3 py-2.5 text-sm text-gray-700 hover:bg-violet-50 hover:text-violet-600 transition-all duration-200" onclick="closeMobileMenu()">
                                <i class="fas fa-tachometer-alt mr-3 text-xs"></i>
                                <span>Administration</span>
                            </a>
                        @elseif(auth()->user()->role === 'agent')
                            <a href="{{ route('agent.dashboard') }}" class="flex items-center px-3 py-2.5 text-sm text-gray-700 hover:bg-violet-50 hover:text-violet-600 transition-all duration-200" onclick="closeMobileMenu()">
                                <i class="fas fa-tachometer-alt mr-3 text-xs"></i>
                                <span>Tableau de bord</span>
                            </a>
                        @endif
                        <hr class="my-1 border-gray-100">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex items-center w-full text-left px-3 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-all duration-200">
                                <i class="fas fa-sign-out-alt mr-3 text-xs"></i>
                                <span>Déconnexion</span>
                            </button>
                        </form>
                    </div>
                </div>
            @endauth

            <!-- Mobile Navigation Links -->
            <nav class="space-y-2">
                <a href="{{ route('home') }}" class="flex items-center px-4 py-3 rounded-lg text-gray-700 hover:bg-violet-50 hover:text-violet-600 transition-all duration-200 font-medium" onclick="closeMobileMenu()">
                    <i class="fas fa-home mr-3 text-lg w-6"></i>
                    <span>Accueil</span>
                </a>
                <a href="{{ route('properties.index') }}" class="flex items-center px-4 py-3 rounded-lg text-gray-700 hover:bg-violet-50 hover:text-violet-600 transition-all duration-200 font-medium" onclick="closeMobileMenu()">
                    <i class="fas fa-building mr-3 text-lg w-6"></i>
                    <span>Propriétés</span>
                </a>
                <a href="{{ route('search.index') }}" class="flex items-center px-4 py-3 rounded-lg text-gray-700 hover:bg-violet-50 hover:text-violet-600 transition-all duration-200 font-medium" onclick="closeMobileMenu()">
                    <i class="fas fa-search mr-3 text-lg w-6"></i>
                    <span>Recherche</span>
                </a>
                <a href="{{ route('about') }}" class="flex items-center px-4 py-3 rounded-lg text-gray-700 hover:bg-violet-50 hover:text-violet-600 transition-all duration-200 font-medium" onclick="closeMobileMenu()">
                    <i class="fas fa-info-circle mr-3 text-lg w-6"></i>
                    <span>À propos</span>
                </a>
                <a href="{{ route('contact') }}" class="flex items-center px-4 py-3 rounded-lg text-gray-700 hover:bg-violet-50 hover:text-violet-600 transition-all duration-200 font-medium" onclick="closeMobileMenu()">
                    <i class="fas fa-envelope mr-3 text-lg w-6"></i>
                    <span>Contact</span>
                </a>
            </nav>

            <!-- Mobile Auth Buttons (for guests) -->
            @guest
                <div class="mt-6 space-y-3 pt-6 border-t border-gray-200">
                    <a href="{{ route('login') }}" class="flex items-center justify-center px-4 py-3 rounded-lg text-violet-600 bg-violet-50 hover:bg-violet-100 transition-all duration-300 font-medium" onclick="closeMobileMenu()">
                        <i class="fas fa-sign-in-alt mr-2"></i>
                        <span>Connexion</span>
                    </a>
                    <a href="{{ route('register') }}" class="flex items-center justify-center bg-gradient-to-r from-violet-600 to-violet-800 text-white px-4 py-3 rounded-lg hover:shadow-lg hover:shadow-violet-500/25 transition-all duration-300 font-medium" onclick="closeMobileMenu()">
                        <i class="fas fa-user-plus mr-2"></i>
                        <span>Inscription</span>
                    </a>
                </div>
            @endguest
        </div>
    </div>

    <!-- Main Content -->
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <!-- Logo et Description -->
                <div class="col-span-1 md:col-span-2">
                    <div class="flex items-center space-x-2 mb-4">
                        <div class="w-10 h-10 bg-gradient-to-r from-violet-600 to-violet-800 rounded-lg flex items-center justify-center">
                            <i class="fas fa-home text-white text-xl"></i>
                        </div>
                        <span class="text-2xl font-bold text-white">
                            Carre Premium
                        </span>
                    </div>
                    <p class="text-gray-300 mb-4 max-w-md">
                        La première plateforme immobilière 100% ivoirienne. Nous connectons les propriétaires, agents et locataires pour faciliter vos transactions immobilières en Côte d'Ivoire.
                    </p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-violet-400 transition-colors duration-200">
                            <i class="fab fa-facebook-f text-xl"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-violet-400 transition-colors duration-200">
                            <i class="fab fa-twitter text-xl"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-violet-400 transition-colors duration-200">
                            <i class="fab fa-instagram text-xl"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-violet-400 transition-colors duration-200">
                            <i class="fab fa-linkedin-in text-xl"></i>
                        </a>
                    </div>
                </div>

                <!-- Liens Rapides -->
                <div>
                    <h3 class="text-lg font-semibold mb-4 text-violet-400">Liens Rapides</h3>
                    <ul class="space-y-2">
                        <li><a href="{{ route('home') }}" class="text-gray-300 hover:text-white transition-colors duration-200">Accueil</a></li>
                        <li><a href="{{ route('properties.index') }}" class="text-gray-300 hover:text-white transition-colors duration-200">Propriétés</a></li>
                        <li><a href="{{ route('cities.index') }}" class="text-gray-300 hover:text-white transition-colors duration-200">Villes</a></li>
                        <li><a href="{{ route('about') }}" class="text-gray-300 hover:text-white transition-colors duration-200">À propos</a></li>
                        <li><a href="{{ route('contact') }}" class="text-gray-300 hover:text-white transition-colors duration-200">Contact</a></li>
                    </ul>
                </div>

                <!-- Contact -->
                <div>
                    <h3 class="text-lg font-semibold mb-4 text-violet-400">Contact</h3>
                    <ul class="space-y-2 text-gray-300">
                        <li class="flex items-center">
                            <i class="fas fa-map-marker-alt mr-2 text-violet-400"></i>
                            Abidjan Marcory Biétry Boulevard de Marseille, Côte d'Ivoire
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-phone mr-2 text-violet-400"></i>
                            <a href="tel:+2250101221515" class="hover:underline">+225 01 01 22 15 15</a>
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-envelope mr-2 text-violet-400"></i>
                            <a href="mailto:infos@carrepremium.com" class="hover:underline">infos@carrepremium.com</a>
                        </li>
                    </ul>
                </div>
            </div>

            <hr class="border-gray-700 my-8">

            <div class="flex flex-col md:flex-row justify-between items-center">
                <p class="text-gray-400 text-sm">
                    © {{ date('Y') }} Carre Premium. Tous droits réservés.
                </p>
                <div class="flex space-x-6 mt-4 md:mt-0">
                    <a href="#" class="text-gray-400 hover:text-white text-sm transition-colors duration-200">
                        Politique de confidentialité
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white text-sm transition-colors duration-200">
                        Conditions d'utilisation
                    </a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script>
        // Mobile menu functions
        function toggleMobileMenu() {
            const mobileMenu = document.getElementById('mobile-menu');
            const overlay = document.getElementById('mobile-overlay');
            const hamburger = document.getElementById('mobile-menu-button');
            const body = document.body;
            
            const isOpen = mobileMenu.classList.contains('open');
            
            if (isOpen) {
                closeMobileMenu();
            } else {
                // Open menu
                mobileMenu.classList.add('open');
                overlay.classList.remove('hidden');
                overlay.classList.add('show');
                hamburger.classList.add('active');
                body.classList.add('mobile-menu-open');
            }
        }

        function closeMobileMenu() {
            const mobileMenu = document.getElementById('mobile-menu');
            const overlay = document.getElementById('mobile-overlay');
            const hamburger = document.getElementById('mobile-menu-button');
            const body = document.body;
            
            mobileMenu.classList.remove('open');
            overlay.classList.remove('show');
            hamburger.classList.remove('active');
            body.classList.remove('mobile-menu-open');
            
            // Hide overlay after transition
            setTimeout(() => {
                overlay.classList.add('hidden');
            }, 300);
        }

        function toggleMobileDropdown() {
            const dropdown = document.getElementById('mobile-dropdown');
            const icon = document.getElementById('dropdown-icon');
            
            dropdown.classList.toggle('open');
            icon.classList.toggle('rotate-180');
        }

        // Close mobile menu when clicking on navigation links
        document.addEventListener('DOMContentLoaded', function() {
            const mobileLinks = document.querySelectorAll('#mobile-menu a');
            mobileLinks.forEach(link => {
                link.addEventListener('click', function() {
                    closeMobileMenu();
                });
            });
        });

        // Close mobile menu on window resize if screen becomes large
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 1024) {
                closeMobileMenu();
            }
        });

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Chatbot functionality
        function chatbot() {
            return {
                isOpen: false,
                messages: [
                    {
                        text: "Bonjour ! Je suis votre assistant immobilier. Comment puis-je vous aider aujourd'hui ?",
                        isBot: true,
                        time: new Date().toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' })
                    }
                ],
                newMessage: '',
                isTyping: false,

                toggleChat() {
                    this.isOpen = !this.isOpen;
                },

                sendMessage() {
                    if (this.newMessage.trim() === '') return;

                    // Add user message
                    this.messages.push({
                        text: this.newMessage,
                        isBot: false,
                        time: new Date().toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' })
                    });

                    const userMessage = this.newMessage.toLowerCase();
                    this.newMessage = '';

                    // Show typing indicator
                    this.isTyping = true;

                    // Simulate bot response
                    setTimeout(() => {
                        this.isTyping = false;
                        const response = this.generateResponse(userMessage);
                        this.messages.push({
                            text: response,
                            isBot: true,
                            time: new Date().toLocaleTimeString('fr-FR', { hour: '2-digit', minute: '2-digit' })
                        });

                        // Scroll to bottom
                        this.$nextTick(() => {
                            const messagesContainer = this.$refs.messagesContainer;
                            if (messagesContainer) {
                                messagesContainer.scrollTop = messagesContainer.scrollHeight;
                            }
                        });
                    }, 1000);

                    // Scroll to bottom
                    this.$nextTick(() => {
                        const messagesContainer = this.$refs.messagesContainer;
                        if (messagesContainer) {
                            messagesContainer.scrollTop = messagesContainer.scrollHeight;
                        }
                    });
                },

                generateResponse(message) {
                    // Réponses intelligentes et complètes
                    
                    // Salutations
                    if (message.includes('bonjour') || message.includes('salut') || message.includes('hello') || message.includes('bonsoir') || message.includes('hey')) {
                        return "Bonjour ! 👋 Je suis ravi de vous accueillir sur Carre Premium. Je peux vous aider à trouver votre propriété idéale, répondre à vos questions sur nos services, ou vous guider dans votre recherche. Comment puis-je vous assister aujourd'hui ?";
                    }
                    
                    // Prix et budget
                    if (message.includes('prix') || message.includes('coût') || message.includes('tarif') || message.includes('budget') || message.includes('combien')) {
                        return "💰 Nos propriétés ont des prix variés selon le type et la localisation :\n\n• Appartements à Abidjan : 500 000 - 5 000 000 FCFA/mois\n• Maisons à Yamoussoukro : 300 000 - 3 000 000 FCFA/mois\n• Terrains : À partir de 10 000 000 FCFA\n• Commerces : Sur devis selon l'emplacement\n\nQuel est votre budget approximatif ? Je peux vous aider à trouver des options adaptées !";
                    }
                    
                    // Recherche de propriétés
                    if (message.includes('recherche') || message.includes('cherche') || message.includes('trouver') || message.includes('propriété') || message.includes('bien')) {
                        return "🔍 Je peux vous aider à trouver la propriété parfaite ! Voici comment :\n\n1. Utilisez notre recherche avancée en haut de la page\n2. Filtrez par type (maison, appartement, terrain, commercial)\n3. Sélectionnez votre ville préférée\n4. Définissez votre budget\n\nQue recherchez-vous exactement ? Un appartement, une maison, un terrain ou un local commercial ?";
                    }
                    
                    // Location
                    if (message.includes('location') || message.includes('louer') || message.includes('locat')) {
                        return "🏠 Excellente nouvelle ! Nous avons de nombreuses propriétés à louer :\n\n• Abidjan : Plus de 50 appartements et maisons\n• Yamoussoukro : Résidences modernes disponibles\n• Bouaké : Options variées pour tous budgets\n• San Pedro : Propriétés près de la mer\n\nDans quelle ville souhaitez-vous louer ? Je peux vous montrer les meilleures options !";
                    }
                    
                    // Achat/Vente
                    if (message.includes('achat') || message.includes('acheter') || message.includes('vendre') || message.includes('vente')) {
                        return "🏡 Nous avons une large sélection de propriétés à vendre :\n\n• Maisons modernes avec jardin\n• Appartements neufs et rénovés\n• Terrains viabilisés\n• Locaux commerciaux stratégiques\n\nQuel type de bien vous intéresse ? Et dans quelle ville cherchez-vous ?";
                    }
                    
                    // Villes
                    if (message.includes('abidjan') || message.includes('yamoussoukro') || message.includes('bouaké') || message.includes('ville') || message.includes('quartier')) {
                        return "🌍 Nous couvrons les principales villes de Côte d'Ivoire :\n\n• Abidjan : Cocody, Plateau, Marcory, Yopougon\n• Yamoussoukro : Centre-ville, zones résidentielles\n• Bouaké : Tous quartiers\n• San Pedro, Daloa, Korhogo et plus !\n\nQuelle ville vous intéresse ? Je peux vous montrer les propriétés disponibles !";
                    }
                    
                    // Types de propriétés
                    if (message.includes('appartement') || message.includes('studio') || message.includes('f2') || message.includes('f3') || message.includes('f4')) {
                        return "🏢 Nos appartements disponibles :\n\n• Studios : Parfaits pour célibataires (250k - 500k FCFA/mois)\n• F2 : Idéal pour couples (400k - 800k FCFA/mois)\n• F3 : Pour petites familles (600k - 1.5M FCFA/mois)\n• F4+ : Grandes familles (1M - 3M FCFA/mois)\n\nTous avec commodités modernes ! Quel type vous intéresse ?";
                    }
                    
                    if (message.includes('maison') || message.includes('villa') || message.includes('duplex')) {
                        return "🏘️ Nos maisons et villas :\n\n• Maisons simples : 2-3 chambres avec jardin\n• Villas modernes : 4-6 chambres, piscine optionnelle\n• Duplex : Design contemporain, espaces optimisés\n• Résidences sécurisées : Gardiennage 24/7\n\nQue préférez-vous ? Je peux vous montrer nos meilleures offres !";
                    }
                    
                    if (message.includes('terrain') || message.includes('parcelle') || message.includes('lot')) {
                        return "🏗️ Terrains disponibles :\n\n• Terrains résidentiels viabilisés\n• Parcelles commerciales bien situées\n• Grands lots pour projets immobiliers\n• Titres fonciers sécurisés\n\nSuperficies de 200m² à plusieurs hectares. Quel projet avez-vous en tête ?";
                    }
                    
                    if (message.includes('commercial') || message.includes('bureau') || message.includes('magasin') || message.includes('boutique')) {
                        return "🏪 Locaux commerciaux disponibles :\n\n• Bureaux modernes : Climatisés, internet haut débit\n• Magasins : Zones commerciales stratégiques\n• Entrepôts : Grandes surfaces, accès facile\n• Restaurants/Cafés : Emplacements premium\n\nQuel type de local cherchez-vous ?";
                    }
                    
                    // Contact et horaires
                    if (message.includes('contact') || message.includes('appeler') || message.includes('téléphone') || message.includes('email') || message.includes('joindre')) {
                        return "📞 Contactez-nous facilement :\n\n• Téléphone : +225 01 01 22 15 15\n• Email : infos@carrepremium.com\n• Adresse : Abidjan Marcory Biétry, Boulevard de Marseille\n\n⏰ Horaires : Lundi - Vendredi, 8h - 18h\nSamedi : 9h - 13h\n\nNotre équipe est à votre écoute !";
                    }
                    
                    // Visite
                    if (message.includes('visite') || message.includes('visiter') || message.includes('voir') || message.includes('rendez-vous')) {
                        return "👁️ Organiser une visite :\n\n1. Choisissez la propriété qui vous intéresse\n2. Cliquez sur 'Contacter' sur la fiche\n3. Ou appelez-nous au +225 01 01 22 15 15\n\nNos agents sont disponibles pour des visites du lundi au samedi. Visites virtuelles également disponibles !";
                    }
                    
                    // Documents et procédures
                    if (message.includes('document') || message.includes('papier') || message.includes('dossier') || message.includes('procédure')) {
                        return "📄 Documents nécessaires :\n\nPour la location :\n• Pièce d'identité\n• Justificatif de revenus\n• Caution (2-3 mois de loyer)\n\nPour l'achat :\n• Pièce d'identité\n• Justificatif de fonds\n• Acte notarié (nous vous assistons)\n\nNous vous accompagnons dans toutes les démarches !";
                    }
                    
                    // Paiement
                    if (message.includes('paiement') || message.includes('payer') || message.includes('mode de paiement') || message.includes('facilité')) {
                        return "💳 Modes de paiement acceptés :\n\n• Virement bancaire\n• Mobile Money (Orange, MTN, Moov)\n• Espèces (à l'agence)\n• Chèque certifié\n\nFacilités de paiement disponibles pour l'achat. Contactez-nous pour plus d'infos !";
                    }
                    
                    // Services
                    if (message.includes('service') || message.includes('aide') || message.includes('assistance') || message.includes('accompagnement')) {
                        return "🤝 Nos services :\n\n• Recherche personnalisée de propriétés\n• Visites guidées\n• Assistance juridique\n• Aide au financement\n• Gestion locative\n• Estimation gratuite\n\nNous vous accompagnons de A à Z dans votre projet immobilier !";
                    }
                    
                    // Sécurité
                    if (message.includes('sécurité') || message.includes('sécurisé') || message.includes('fiable') || message.includes('arnaque')) {
                        return "🔒 Votre sécurité est notre priorité :\n\n✓ Toutes nos propriétés sont vérifiées\n✓ Titres fonciers authentifiés\n✓ Contrats légaux sécurisés\n✓ Paiements tracés\n✓ Équipe professionnelle certifiée\n\nCarre Premium = Confiance et transparence garanties !";
                    }
                    
                    // Inscription/Compte
                    if (message.includes('inscription') || message.includes('compte') || message.includes('inscrire') || message.includes('créer un compte')) {
                        return "👤 Créer votre compte :\n\n1. Cliquez sur 'Inscription' en haut\n2. Remplissez vos informations\n3. Validez votre email\n4. Accédez à votre espace personnel\n\nAvantages : Sauvegarder vos favoris, alertes personnalisées, historique de recherches !";
                    }
                    
                    // Merci
                    if (message.includes('merci') || message.includes('thank')) {
                        return "😊 Avec plaisir ! C'est un honneur de vous aider. N'hésitez pas si vous avez d'autres questions. Bonne recherche sur Carre Premium ! 🏠✨";
                    }
                    
                    // Au revoir
                    if (message.includes('au revoir') || message.includes('bye') || message.includes('à bientôt') || message.includes('adieu')) {
                        return "👋 Au revoir ! Merci d'avoir visité Carre Premium. Revenez quand vous voulez, je serai toujours là pour vous aider. Bonne journée ! 🌟";
                    }
                    
                    // Réponse par défaut enrichie
                    return "🤔 Je suis là pour vous aider ! Voici ce que je peux faire pour vous :\n\n• 🏠 Vous aider à trouver une propriété\n• 💰 Vous renseigner sur les prix\n• 📍 Vous informer sur les villes disponibles\n• 📞 Vous donner nos coordonnées\n• 📄 Expliquer les procédures\n• 🔍 Guider votre recherche\n\nPosez-moi n'importe quelle question sur l'immobilier en Côte d'Ivoire !";
                }
            };
        }
    </script>
    
    @stack('scripts')
    
    <!-- Cookie Banner -->
    <x-cookie-banner />

    <!-- Chatbot -->
    @include('components.chatbot')
</body>
</html>
