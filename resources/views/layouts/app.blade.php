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
                        'gabon': {
                            'green': '#009639',
                            'yellow': '#FCD116',
                            'blue': '#3A75C4',
                            'green-light': '#00b344',
                            'blue-light': '#4a85d4',
                            'yellow-light': '#fcd726',
                            'green-dark': '#007a2e',
                            'blue-dark': '#2d5ba3'
                        },
                        'primary': {
                            50: '#f0fdf4',
                            100: '#dcfce7',
                            200: '#bbf7d0',
                            300: '#86efac',
                            400: '#4ade80',
                            500: '#009639',
                            600: '#007a2e',
                            700: '#15803d',
                            800: '#166534',
                            900: '#14532d',
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
    </style>
    
    @stack('styles')
</head>
<body class="font-sans bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-white/95 backdrop-blur-lg shadow-lg sticky top-0 z-50 border-b border-white/20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="{{ route('home') }}" class="flex items-center space-x-3 group">
                        <div class="w-12 h-12 bg-gradient-to-br from-violet-600 to-violet-800 rounded-xl flex items-center justify-center shadow-lg group-hover:shadow-xl transition-all duration-300">
                            <i class="fas fa-home text-white text-xl"></i>
                        </div>
                        <div>
                            <span class="text-2xl font-bold bg-gradient-to-r from-violet-600 to-violet-800 bg-clip-text text-transparent">
                                Carre Premium
                            </span>
                            <div class="text-xs text-gray-500 -mt-1">Immobilier</div>
                        </div>
                    </a>
                </div>

                <!-- Navigation Links -->
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

                <!-- Auth Buttons -->
                <div class="flex items-center space-x-3">
                    @guest
                        <a href="{{ route('login') }}" class="px-4 py-2 rounded-lg text-violet-600 hover:text-violet-700 hover:bg-violet-50 transition-all duration-300 font-medium">
                            <i class="fas fa-sign-in-alt mr-2"></i>
                            Connexion
                        </a>
                        <a href="{{ route('register') }}" class="bg-gradient-to-r from-violet-600 to-violet-800 text-white px-6 py-2.5 rounded-lg hover:shadow-lg hover:shadow-violet-500/25 transform hover:scale-105 transition-all duration-300 font-medium">
                            <i class="fas fa-user-plus mr-2"></i>
                            Inscription
                        </a>
                    @else
                        <div class="relative group">
                            <button class="flex items-center space-x-3 px-3 py-2 rounded-lg hover:bg-gray-50 transition-all duration-300">
                                <img src="{{ auth()->user()->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode(auth()->user()->name) }}"
                                     alt="{{ auth()->user()->name }}"
                                     class="w-8 h-8 rounded-full border-2 border-violet-200">
                                <span class="font-medium text-gray-700">{{ auth()->user()->name }}</span>
                                <i class="fas fa-chevron-down text-xs text-gray-500 group-hover:text-violet-600 transition-colors duration-200"></i>
                            </button>

                            <!-- Dropdown Menu -->
                            <div class="absolute right-0 mt-3 w-56 bg-white/95 backdrop-blur-lg rounded-xl shadow-xl border border-white/20 py-2 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform translate-y-2 group-hover:translate-y-0">
                                <div class="px-4 py-3 border-b border-gray-100">
                                    <p class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</p>
                                    <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
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

                    <!-- Mobile menu button -->
                    <div class="lg:hidden">
                        <button id="mobile-menu-button" class="p-2 rounded-lg text-gray-700 hover:text-violet-600 hover:bg-violet-50 transition-all duration-300">
                            <i class="fas fa-bars text-lg"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile Navigation -->
        <div id="mobile-menu" class="lg:hidden hidden bg-white/95 backdrop-blur-lg border-t border-white/20">
            <div class="px-4 py-4 space-y-1">
                <a href="{{ route('home') }}" class="flex flex-col items-center px-4 py-3 rounded-lg text-gray-700 hover:text-violet-600 hover:bg-violet-50 transition-all duration-300">
                    <i class="fas fa-home text-lg mb-1"></i>
                    <span class="text-xs">Accueil</span>
                </a>
                <a href="{{ route('properties.index') }}" class="flex flex-col items-center px-4 py-3 rounded-lg text-gray-700 hover:text-violet-600 hover:bg-violet-50 transition-all duration-300">
                    <i class="fas fa-building text-lg mb-1"></i>
                    <span class="text-xs">Propriétés</span>
                </a>
                <a href="{{ route('search.index') }}" class="flex flex-col items-center px-4 py-3 rounded-lg text-gray-700 hover:text-violet-600 hover:bg-violet-50 transition-all duration-300">
                    <i class="fas fa-search text-lg mb-1"></i>
                    <span class="text-xs">Recherche</span>
                </a>
                <a href="{{ route('about') }}" class="flex flex-col items-center px-4 py-3 rounded-lg text-gray-700 hover:text-violet-600 hover:bg-violet-50 transition-all duration-300">
                    <i class="fas fa-info-circle text-lg mb-1"></i>
                    <span class="text-xs">À propos</span>
                </a>
                <a href="{{ route('contact') }}" class="flex flex-col items-center px-4 py-3 rounded-lg text-gray-700 hover:text-violet-600 hover:bg-violet-50 transition-all duration-300">
                    <i class="fas fa-envelope text-lg mb-1"></i>
                    <span class="text-xs">Contact</span>
                </a>
                @guest
                    <hr class="my-4 border-gray-200">
                    <a href="{{ route('login') }}" class="flex items-center px-4 py-3 rounded-lg text-violet-600 hover:text-violet-700 hover:bg-violet-50 transition-all duration-300">
                        <i class="fas fa-sign-in-alt mr-3 text-sm"></i>
                        <span>Connexion</span>
                    </a>
                    <a href="{{ route('register') }}" class="flex items-center px-4 py-3 rounded-lg bg-gradient-to-r from-violet-600 to-violet-800 text-white hover:shadow-lg transition-all duration-300">
                        <i class="fas fa-user-plus mr-3 text-sm"></i>
                        <span>Inscription</span>
                    </a>
                @endguest
            </div>
        </div>
    </nav>

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
                        <div class="w-10 h-10 bg-gradient-to-r from-gabon-green to-gabon-blue rounded-lg flex items-center justify-center">
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
                        <a href="#" class="text-gray-400 hover:text-gabon-yellow transition-colors duration-200">
                            <i class="fab fa-facebook-f text-xl"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-gabon-yellow transition-colors duration-200">
                            <i class="fab fa-twitter text-xl"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-gabon-yellow transition-colors duration-200">
                            <i class="fab fa-instagram text-xl"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-gabon-yellow transition-colors duration-200">
                            <i class="fab fa-linkedin-in text-xl"></i>
                        </a>
                    </div>
                </div>

                <!-- Liens Rapides -->
                <div>
                    <h3 class="text-lg font-semibold mb-4 text-gabon-yellow">Liens Rapides</h3>
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
                    <h3 class="text-lg font-semibold mb-4 text-gabon-yellow">Contact</h3>
                    <ul class="space-y-2 text-gray-300">
                        <li class="flex items-center">
                            <i class="fas fa-map-marker-alt mr-2 text-carre-purple"></i>
                            Abidjan Marcory Biétry Boulevard de Marseille, Côte d'Ivoire
                        </li>
                        <li class="flex items-center">
                        <i class="fas fa-phone mr-2 text-carre-purple"></i>
                        <a href="tel:+2250101221515" class="hover:underline">+225 01 01 22 15 15</a>
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-envelope mr-2 text-carre-purple"></i>
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
        // Mobile menu toggle
        document.getElementById('mobile-menu-button').addEventListener('click', function() {
            const mobileMenu = document.getElementById('mobile-menu');
            mobileMenu.classList.toggle('hidden');
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
    </script>
    
        @stack('scripts')
        
        <!-- Cookie Banner -->
        <x-cookie-banner />
    </body>
</html>
