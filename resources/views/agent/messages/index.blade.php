@extends('layouts.agent')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Messages</h1>
        <div class="flex space-x-4">
            <span class="inline-flex items-center px-4 py-2 bg-white rounded-md shadow-sm text-sm font-medium">
                Total: {{ $stats['total'] }}
            </span>
            <span class="inline-flex items-center px-4 py-2 bg-red-50 text-red-700 rounded-md shadow-sm text-sm font-medium">
                Non lus: {{ $stats['unread'] }}
            </span>
            <span class="inline-flex items-center px-4 py-2 bg-blue-50 text-blue-700 rounded-md shadow-sm text-sm font-medium">
                Envoyés: {{ $stats['sent'] }}
            </span>
        </div>
    </div>

    <!-- Filtres -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="p-4">
            <form action="{{ route('agent.messages.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label for="property" class="block text-sm font-medium text-gray-700">Propriété</label>
                    <select name="property" id="property" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                        <option value="">Toutes</option>
                        @foreach($properties as $property)
                            <option value="{{ $property->id }}" {{ request('property') == $property->id ? 'selected' : '' }}>
                                {{ $property->title }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Statut</label>
                    <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                        <option value="">Tous</option>
                        <option value="unread" {{ request('status') === 'unread' ? 'selected' : '' }}>Non lus</option>
                        <option value="read" {{ request('status') === 'read' ? 'selected' : '' }}>Lus</option>
                    </select>
                </div>

                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700">Type</label>
                    <select name="type" id="type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500">
                        <option value="">Tous</option>
                        <option value="received" {{ request('type') === 'received' ? 'selected' : '' }}>Reçus</option>
                        <option value="sent" {{ request('type') === 'sent' ? 'selected' : '' }}>Envoyés</option>
                    </select>
                </div>

                <div class="flex items-end">
                    <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-md">
                        Filtrer
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des messages -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="min-w-full divide-y divide-gray-200">
            @forelse($messages as $message)
                <div class="p-4 hover:bg-gray-50 flex items-center justify-between {{ $message->read_at ? 'bg-white' : 'bg-blue-50' }}">
                    <div class="flex-1">
                        <div class="flex justify-between">
                            <div class="text-sm font-medium text-gray-900">
                                @if($message->sender_id === auth()->id())
                                    À: {{ $message->receiver->name }}
                                @else
                                    De: {{ $message->sender->name }}
                                @endif
                            </div>
                            <div class="text-sm text-gray-500">
                                {{ $message->created_at->format('d/m/Y H:i') }}
                            </div>
                        </div>
                        <div class="mt-1">
                            <p class="text-sm text-gray-900 line-clamp-1">
                                {{ $message->content }}
                            </p>
                        </div>
                        <div class="mt-2 text-xs text-gray-500">
                            Propriété: {{ $message->property->title }}
                        </div>
                    </div>
                    <div class="ml-4">
                        <a href="{{ route('agent.messages.show', $message) }}" class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-5 font-medium rounded-md text-green-700 bg-green-100 hover:bg-green-200">
                            Voir
                        </a>
                    </div>
                </div>
            @empty
                <div class="p-4 text-center text-gray-500">
                    Aucun message trouvé.
                </div>
            @endforelse
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $messages->links() }}
    </div>
</div>

@push('scripts')
<script>
    // Script pour marquer les messages comme lus automatiquement
    document.addEventListener('DOMContentLoaded', function() {
        const markAsRead = async (messageId) => {
            try {
                const response = await fetch(`/agent/messages/${messageId}/mark-as-read`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                if (response.ok) {
                    const messageElement = document.querySelector(`[data-message-id="${messageId}"]`);
                    if (messageElement) {
                        messageElement.classList.remove('bg-blue-50');
                        messageElement.classList.add('bg-white');
                    }
                }
            } catch (error) {
                console.error('Erreur lors du marquage du message comme lu:', error);
            }
        };

        // Marquer les messages comme lus lorsqu'ils deviennent visibles
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const messageId = entry.target.dataset.messageId;
                    if (messageId) {
                        markAsRead(messageId);
                    }
                }
            });
        });

        document.querySelectorAll('[data-message-id]').forEach(message => {
            observer.observe(message);
        });
    });
</script>
@endpush
@endsection
