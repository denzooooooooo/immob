<!-- Chatbot Component -->
<div x-data="chatbot()" class="fixed bottom-6 right-6 z-50">
    <!-- Chatbot Button -->
    <button
        @click="toggleChat()"
        :class="{ 'scale-110': isOpen }"
        class="bg-gradient-to-r from-violet-600 to-violet-800 hover:from-violet-700 hover:to-violet-900 text-white rounded-full w-16 h-16 flex items-center justify-center shadow-2xl hover:shadow-violet-500/50 transition-all duration-300 group"
    >
        <i :class="isOpen ? 'fas fa-times' : 'fas fa-comments'" class="text-xl group-hover:scale-110 transition-transform duration-200"></i>
    </button>

    <!-- Chat Window -->
    <div
        x-show="isOpen"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform scale-95 translate-y-4"
        x-transition:enter-end="opacity-100 transform scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 transform scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 transform scale-95 translate-y-4"
        class="absolute bottom-20 right-0 w-96 bg-white rounded-2xl shadow-2xl border border-violet-200 overflow-hidden"
        style="display: none;"
    >
        <!-- Chat Header -->
        <div class="bg-gradient-to-r from-violet-600 to-violet-800 text-white p-4 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                    <i class="fas fa-robot text-xl"></i>
                </div>
                <div>
                    <h3 class="font-bold text-lg">Assistant Immobilier</h3>
                    <p class="text-xs text-violet-100">En ligne</p>
                </div>
            </div>
            <button @click="toggleChat()" class="hover:bg-white/20 rounded-full p-2 transition-colors duration-200">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>

        <!-- Chat Messages -->
        <div x-ref="messagesContainer" class="h-96 overflow-y-auto p-4 bg-gray-50 space-y-4">
            <template x-for="(message, index) in messages" :key="index">
                <div :class="message.isBot ? 'flex justify-start' : 'flex justify-end'">
                    <div :class="message.isBot ? 'bg-white border border-violet-200' : 'bg-gradient-to-r from-violet-600 to-violet-800 text-white'" class="max-w-xs rounded-2xl p-3 shadow-md">
                        <div class="flex items-start space-x-2" x-show="message.isBot">
                            <i class="fas fa-robot text-violet-600 mt-1"></i>
                            <div>
                                <p class="text-sm" x-text="message.text"></p>
                                <span class="text-xs text-gray-500 mt-1 block" x-text="message.time"></span>
                            </div>
                        </div>
                        <div x-show="!message.isBot">
                            <p class="text-sm" x-text="message.text"></p>
                            <span class="text-xs text-violet-100 mt-1 block" x-text="message.time"></span>
                        </div>
                    </div>
                </div>
            </template>

            <!-- Typing Indicator -->
            <div x-show="isTyping" class="flex justify-start">
                <div class="bg-white border border-violet-200 rounded-2xl p-3 shadow-md">
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-robot text-violet-600"></i>
                        <div class="flex space-x-1">
                            <div class="w-2 h-2 bg-violet-600 rounded-full animate-bounce"></div>
                            <div class="w-2 h-2 bg-violet-600 rounded-full animate-bounce" style="animation-delay: 0.1s"></div>
                            <div class="w-2 h-2 bg-violet-600 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chat Input -->
        <div class="p-4 bg-white border-t border-gray-200">
            <form @submit.prevent="sendMessage()" class="flex items-center space-x-2">
                <input
                    x-model="newMessage"
                    type="text"
                    placeholder="Tapez votre message..."
                    class="flex-1 px-4 py-2 border border-gray-300 rounded-full focus:outline-none focus:ring-2 focus:ring-violet-600 focus:border-transparent"
                    @keydown.enter.prevent="sendMessage()"
                >
                <button
                    type="submit"
                    :disabled="!newMessage.trim()"
                    :class="newMessage.trim() ? 'bg-gradient-to-r from-violet-600 to-violet-800 hover:from-violet-700 hover:to-violet-900' : 'bg-gray-300 cursor-not-allowed'"
                    class="p-3 rounded-full text-white transition-all duration-200 transform hover:scale-110"
                >
                    <i class="fas fa-paper-plane"></i>
                </button>
            </form>
        </div>
    </div>
</div>
