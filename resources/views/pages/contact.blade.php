@extends('layouts.app')

@section('title', 'Contact - Carre Premium Immo')
@section('description', 'Contactez l\'équipe Carre Premium Immo. Nous sommes là pour vous aider dans tous vos projets immobiliers en Côte d\'Ivoire.')

@section('content')
<!-- Hero Section -->
<section class="relative py-20 overflow-hidden">
    <!-- Background -->
    <div class="absolute inset-0 bg-gradient-to-br from-violet-600 to-red-500"></div>
    
    <!-- Decorative elements -->
    <div class="absolute inset-0">
        <div class="absolute top-10 left-10 w-32 h-32 bg-white/10 rounded-full animate-pulse"></div>
        <div class="absolute top-40 right-20 w-24 h-24 bg-gabon-yellow/20 rounded-full animate-bounce"></div>
        <div class="absolute bottom-20 left-1/4 w-40 h-40 bg-white/5 rounded-full animate-pulse"></div>
    </div>
    
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-white">
        <h1 class="text-4xl md:text-6xl font-bold mb-6">
            Contactez-
            <span class="bg-gradient-to-r from-violet-600 to-red-500 bg-clip-text text-transparent">nous</span>
        </h1>
        <p class="text-xl md:text-2xl mb-8 max-w-3xl mx-auto opacity-90">
            Notre équipe est là pour vous accompagner dans tous vos projets immobiliers
        </p>
    </div>
</section>

<!-- Contact Section -->
<section class="py-16 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Contact Form -->
            <div class="bg-white rounded-2xl shadow-xl p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">
                    Envoyez-nous un
                    <span class="bg-gradient-to-r from-violet-600 to-red-500 bg-clip-text text-transparent">message</span>
                </h2>
                
                @if(session('success'))
                    <div class="bg-gabon-green/10 border border-gabon-green/20 text-gabon-green px-4 py-3 rounded-lg mb-6">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle mr-2"></i>
                            {{ session('success') }}
                        </div>
                    </div>
                @endif
                
                <form action="{{ route('contact.submit') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Nom complet *
                            </label>
                            <input type="text" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name') }}"
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gabon-green focus:border-transparent transition-all duration-200 @error('name') border-red-500 @enderror">
                            @error('name')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                Email *
                            </label>
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email') }}"
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gabon-green focus:border-transparent transition-all duration-200 @error('email') border-red-500 @enderror">
                            @error('email')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <div>
                        <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">
                            Sujet *
                        </label>
                        <input type="text" 
                               id="subject" 
                               name="subject" 
                               value="{{ old('subject') }}"
                               required
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gabon-green focus:border-transparent transition-all duration-200 @error('subject') border-red-500 @enderror">
                        @error('subject')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="message" class="block text-sm font-medium text-gray-700 mb-2">
                            Message *
                        </label>
                        <textarea id="message" 
                                  name="message" 
                                  rows="6" 
                                  required
                                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gabon-green focus:border-transparent transition-all duration-200 resize-none @error('message') border-red-500 @enderror">{{ old('message') }}</textarea>
                        @error('message')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <button type="submit"
                            class="w-full bg-gradient-to-r from-violet-600 to-red-500 text-white font-bold py-4 px-6 rounded-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                        <i class="fas fa-paper-plane mr-2"></i>
                        Envoyer le message
                    </button>
                </form>
            </div>
            
            <!-- Contact Information -->
            <div class="space-y-8">
                <!-- Contact Cards -->
                <div class="bg-gradient-to-br from-violet-600 via-red-600 to-violet-800 rounded-2xl p-8 text-white">
                    <h3 class="text-2xl font-bold mb-6">Informations de contact</h3>
                    
                    <div class="space-y-6">
                        <div class="flex items-start">
                            <div class="w-12 h-12 bg-gabon-yellow rounded-full flex items-center justify-center mr-4 flex-shrink-0">
                                <i class="fas fa-map-marker-alt text-gray-900"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold mb-1">Adresse</h4>
                                <p class="opacity-90">
                                    Boulevard de la République<br>
                                    Plateau, Abidjan<br>
                                    Côte d'Ivoire
                                </p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="w-12 h-12 bg-gabon-yellow rounded-full flex items-center justify-center mr-4 flex-shrink-0">
                                <i class="fas fa-phone text-gray-900"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold mb-1">Téléphone</h4>
                                <p class="opacity-90">+225 01 XX XX XX</p>
                                <p class="opacity-90">+225 05 XX XX XX</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="w-12 h-12 bg-gabon-yellow rounded-full flex items-center justify-center mr-4 flex-shrink-0">
                                <i class="fas fa-envelope text-gray-900"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold mb-1">Email</h4>
                                <p class="opacity-90">contact@carrepremiumimmo.ci</p>
                                <p class="opacity-90">support@carrepremiumimmo.ci</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start">
                            <div class="w-12 h-12 bg-gabon-yellow rounded-full flex items-center justify-center mr-4 flex-shrink-0">
                                <i class="fas fa-clock text-gray-900"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold mb-1">Horaires</h4>
                                <p class="opacity-90">Lun - Ven: 8h00 - 18h00</p>
                                <p class="opacity-90">Sam: 9h00 - 15h00</p>
                                <p class="opacity-90">Dim: Fermé</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Quick Contact Options -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <a href="tel:+22501XXXXXX"
                       class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 text-center group">
                        <div class="w-12 h-12 bg-gradient-to-r from-violet-600 to-red-500 rounded-full flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform duration-200">
                            <i class="fas fa-phone text-white"></i>
                        </div>
                        <h4 class="font-bold text-gray-900 mb-1">Appelez-nous</h4>
                        <p class="text-gray-600 text-sm">Réponse immédiate</p>
                    </a>

                    <a href="https://wa.me/22505XXXXXX"
                       target="_blank"
                       class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 text-center group">
                        <div class="w-12 h-12 bg-gradient-to-r from-violet-600 to-red-500 rounded-full flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform duration-200">
                            <i class="fab fa-whatsapp text-white"></i>
                        </div>
                        <h4 class="font-bold text-gray-900 mb-1">WhatsApp</h4>
                        <p class="text-gray-600 text-sm">Chat en direct</p>
                    </a>
                </div>
                
                <!-- Social Media -->
                <div class="bg-white rounded-xl p-6 shadow-lg">
                    <h4 class="font-bold text-gray-900 mb-4 text-center">Suivez-nous</h4>
                    <div class="flex justify-center space-x-4">
                        <a href="#" class="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center text-white hover:scale-110 transition-transform duration-200">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="w-12 h-12 bg-blue-400 rounded-full flex items-center justify-center text-white hover:scale-110 transition-transform duration-200">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="w-12 h-12 bg-pink-600 rounded-full flex items-center justify-center text-white hover:scale-110 transition-transform duration-200">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="w-12 h-12 bg-blue-700 rounded-full flex items-center justify-center text-white hover:scale-110 transition-transform duration-200">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="py-16 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">
                Questions
                <span class="bg-gradient-to-r from-violet-600 to-red-500 bg-clip-text text-transparent">fréquentes</span>
            </h2>
            <p class="text-xl text-gray-600">
                Trouvez rapidement les réponses à vos questions
            </p>
        </div>
        
        <div class="space-y-6">
            <div class="bg-gray-50 rounded-xl p-6">
                <button class="faq-toggle w-full text-left flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900">
                        Comment publier une propriété sur Carre Premium Immo ?
                    </h3>
                    <i class="fas fa-chevron-down text-gabon-blue transform transition-transform duration-200"></i>
                </button>
                <div class="faq-content hidden mt-4 text-gray-600">
                    <p>Pour publier une propriété, vous devez d'abord créer un compte agent ou propriétaire. Une fois connecté, utilisez notre interface simple pour ajouter les détails, photos et informations de votre bien. Notre équipe vérifie chaque annonce avant publication.</p>
                </div>
            </div>
            
            <div class="bg-gray-50 rounded-xl p-6">
                <button class="faq-toggle w-full text-left flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900">
                        Quels sont les frais pour utiliser Carre Premium Immo ?
                    </h3>
                    <i class="fas fa-chevron-down text-gabon-blue transform transition-transform duration-200"></i>
                </button>
                <div class="faq-content hidden mt-4 text-gray-600">
                    <p>La recherche et la consultation des propriétés sont entièrement gratuites. Pour les propriétaires et agents, nous proposons différents plans d'abonnement selon vos besoins. Contactez-nous pour plus de détails sur nos tarifs.</p>
                </div>
            </div>
            
            <div class="bg-gray-50 rounded-xl p-6">
                <button class="faq-toggle w-full text-left flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900">
                        Comment vérifiez-vous les propriétés listées ?
                    </h3>
                    <i class="fas fa-chevron-down text-gabon-blue transform transition-transform duration-200"></i>
                </button>
                <div class="faq-content hidden mt-4 text-gray-600">
                    <p>Chaque propriété est vérifiée par notre équipe. Nous contrôlons les documents de propriété, visitons les biens quand c'est possible, et vérifions l'identité des propriétaires. Nous nous réservons le droit de refuser toute annonce suspecte.</p>
                </div>
            </div>
            
            <div class="bg-gray-50 rounded-xl p-6">
                <button class="faq-toggle w-full text-left flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900">
                        Puis-je modifier ou supprimer mon annonce ?
                    </h3>
                    <i class="fas fa-chevron-down text-gabon-blue transform transition-transform duration-200"></i>
                </button>
                <div class="faq-content hidden mt-4 text-gray-600">
                    <p>Oui, vous pouvez modifier ou supprimer vos annonces à tout moment depuis votre tableau de bord. Les modifications importantes peuvent nécessiter une nouvelle vérification de notre part.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-16 bg-gradient-to-r from-gabon-green to-gabon-blue text-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl md:text-4xl font-bold mb-6">
            Besoin d'aide immédiate ?
        </h2>
        <p class="text-xl mb-8 opacity-90">
            Notre équipe de support est disponible pour vous aider
        </p>
        
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="tel:+22501XXXXXX"
               class="bg-gradient-to-r from-violet-600 to-red-500 text-white font-bold py-4 px-8 rounded-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200 flex items-center justify-center">
                <i class="fas fa-phone mr-2"></i>
                Appelez maintenant
            </a>
            <a href="https://wa.me/22505XXXXXX"
               target="_blank"
               class="border-2 border-white text-white font-bold py-4 px-8 rounded-lg hover:bg-white hover:text-violet-600 transform hover:scale-105 transition-all duration-200 flex items-center justify-center">
                <i class="fab fa-whatsapp mr-2"></i>
                Chat WhatsApp
            </a>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    // FAQ Toggle functionality
    document.querySelectorAll('.faq-toggle').forEach(button => {
        button.addEventListener('click', () => {
            const content = button.nextElementSibling;
            const icon = button.querySelector('i');
            
            content.classList.toggle('hidden');
            icon.classList.toggle('rotate-180');
        });
    });
</script>
@endpush
