@extends('layouts.agent')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="mb-6">
        <a href="{{ route('agent.messages.index') }}" class="inline-flex items-center text-green-600 hover:text-green-700">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Retour aux messages
        </a>
    </div>

    <!-- Détails de la conversation -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="p-6 border-b border-gray-200">
            <h1 class="text-xl font-semibold text-gray-900">Conversation</h1>
            <div class="mt-2 text-sm text-gray-600">
                <p><strong>Propriété:</strong> {{ $message->property->title }}</p>
                <p><strong>Participants:</strong> {{ $message->sender->name }} et {{ $message->receiver->name }}</p>
            </div>
        </div>

        <!-- Messages de la conversation -->
        <div class="p-6 space-y-4 max-h-96 overflow-y-auto">
            @foreach($conversation as $msg)
                <div class="flex {{ $msg->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                    <div class="max-w-xs lg:max-w-md px-4 py-2 rounded-lg {{ $msg->sender_id === auth()->id() ? 'bg-green-500 text-white' : 'bg-gray-100 text-gray-900' }}">
                        <div class="text-sm">
                            {{ $msg->content }}
                        </div>
                        <div class="text-xs mt-1 {{ $msg->sender_id === auth()->id() ? 'text-green-100' : 'text-gray-500' }}">
                            {{ $msg->created_at->format('d/m/Y H:i') }}
                            @if($msg->sender_id === auth()->id())
                                - Vous
                            @else
                                - {{ $msg->sender->name }}
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Formulaire de réponse -->
    <div class="bg-white rounded-lg shadow">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-4">Répondre</h2>
            <form action="{{ route('agent.messages.reply', $message) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label for="content" class="block text-sm font-medium text-gray-700 mb-2">Message</label>
                    <textarea 
                        name="content" 
                        id="content" 
                        rows="4" 
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500"
                        placeholder="Tapez votre réponse..."
                        required
                    >{{ old('content') }}</textarea>
                    @error('content')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex justify-end">
                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-md">
                        Envoyer la réponse
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Auto-scroll vers le bas de la conversation
    document.addEventListener('DOMContentLoaded', function() {
        const conversationContainer = document.querySelector('.overflow-y-auto');
        if (conversationContainer) {
            conversationContainer.scrollTop = conversationContainer.scrollHeight;
        }
    });
</script>
@endpush
@endsection
