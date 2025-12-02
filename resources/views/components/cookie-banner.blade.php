<div x-data="{ show: !localStorage.getItem('cookie-consent') }" 
     x-show="show" 
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 transform translate-y-full"
     x-transition:enter-end="opacity-100 transform translate-y-0"
     x-transition:leave="transition ease-in duration-300"
     x-transition:leave-start="opacity-100 transform translate-y-0"
     x-transition:leave-end="opacity-0 transform translate-y-full"
     class="fixed bottom-0 inset-x-0 z-50">
    
    <div class="bg-white border-t border-gray-200 shadow-lg">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                <div class="flex-1">
                    <p class="text-gray-700">
                        Nous utilisons des cookies pour améliorer votre expérience sur notre site. En continuant à naviguer, vous acceptez notre utilisation des cookies.
                    </p>
                </div>
                <div class="flex gap-4">
                    <a href="{{ route('privacy') }}" class="text-violet-600 hover:text-violet-600-dark transition-colors duration-200">
                        En savoir plus
                    </a>
                    <button @click="localStorage.setItem('cookie-consent', '1'); show = false" 
                            class="bg-violet-600 hover:bg-violet-600-dark text-white font-semibold px-6 py-2 rounded-lg transition-colors duration-200">
                        Accepter
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
