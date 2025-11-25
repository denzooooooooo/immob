@extends('layouts.admin')

@section('title', 'Message de ' . $message->sender->name)

@section('header', 'Détails du message')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Message Header -->
    <div class="bg-white rounded-lg shadow mb-6">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="flex-shrink-0">
                        @if($message->sender->avatar)
                            <img class="h-12 w-12 rounded-full" src="{{ $message->sender->avatar }}" alt="">
                        @else
                            <img class="h-12 w-12 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode($message->sender->name) }}&color=7F9CF5&background=EBF4FF" alt="">
                        @endif
                    </div>
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">{{ $message->sender->name }}</h3>
                        <p class="text-sm text-gray-500">{{ $message->sender->email }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-500">{{ $message->created_at->format('d/m/Y à H:i') }}</p>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $message->read_at ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                        {{ $message->read_at ? 'Lu' : 'Non lu' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Message Content -->
        <div class="p-6">
            @if($message->is_system_message && str_contains($message->content, 'Nouveau message de contact'))
                <!-- Contact Form Message -->
                <div class="mb-4 p-4 bg-blue-50 rounded-lg border-l-4 border-blue-400">
                    <div class="flex items-center mb-2">
                        <i class="fas fa-envelope text-blue-600 mr-2"></i>
                        <h4 class="font-medium text-blue-900">Message de contact</h4>
                    </div>
                    <div class="text-sm text-blue-800">
                        Ce message provient du formulaire de contact du site web.
                    </div>
                </div>

                <!-- Extract contact info -->
                @php
                    $content = $message->content;
                    preg_match('/Nom: ([^\n]+)/', $content, $nameMatch);
                    preg_match('/Email: ([^\n]+)/', $content, $emailMatch);
                    preg_match('/Sujet: ([^\n]+)/', $content, $subjectMatch);
                    preg_match('/Message:\n(.+)/s', $content, $messageMatch);

                    $contactName = $nameMatch[1] ?? '';
                    $contactEmail = $emailMatch[1] ?? '';
                    $contactSubject = $subjectMatch[1] ?? '';
                    $contactMessage = $messageMatch[1] ?? '';
                @endphp

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nom</label>
                        <p class="text-gray-900 bg-gray-50 px-3 py-2 rounded">{{ $contactName }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <p class="text-gray-900 bg-gray-50 px-3 py-2 rounded">
                            <a href="mailto:{{ $contactEmail }}" class="text-blue-600 hover:text-blue-800">{{ $contactEmail }}</a>
                        </p>
                    </div>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sujet</label>
                    <p class="text-gray-900 bg-gray-50 px-3 py-2 rounded">{{ $contactSubject }}</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Message</label>
                    <div class="text-gray-900 bg-gray-50 px-3 py-2 rounded whitespace-pre-line">{{ $contactMessage }}</div>
                </div>
            @else
                @if($message->property)
                    <div class="mb-4 p-4 bg-gray-50 rounded-lg">
                        <h4 class="font-medium text-gray-900 mb-2">À propos de la propriété</h4>
                        <div class="flex items-center space-x-3">
                            @if($message->property->featured_image)
                                <img class="h-16 w-16 rounded-lg object-cover" src="{{ $message->property->featured_image }}" alt="">
                            @endif
                            <div>
                                <p class="font-medium text-gray-900">{{ $message->property->title }}</p>
                                <p class="text-sm text-gray-500">{{ number_format($message->property->price) }} XAF - {{ $message->property->city }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="prose max-w-none">
                    <p class="text-gray-700 whitespace-pre-line">{{ $message->content }}</p>
                </div>

                @if($message->attachment_path)
                    <div class="mt-4 p-4 bg-blue-50 rounded-lg">
                        <div class="flex items-center">
                            <i class="fas fa-paperclip text-blue-600 mr-2"></i>
                            <span class="text-sm text-blue-700">Pièce jointe</span>
                            <a href="{{ route('admin.messages.download-attachment', $message) }}" class="ml-auto text-blue-600 hover:text-blue-800">
                                <i class="fas fa-download mr-1"></i>Télécharger
                            </a>
                        </div>
                    </div>
                @endif
            @endif
        </div>

        <!-- Actions -->
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
            <div class="flex justify-between items-center">
                <div class="flex space-x-3">
                    @if(!$message->read_at)
                        <form action="{{ route('admin.messages.mark-as-read', $message) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                <i class="fas fa-envelope-open mr-2"></i>Marquer comme lu
                            </button>
                        </form>
                    @endif

                    @if(!$message->is_system_message)
                        <form action="{{ route('admin.messages.report', $message) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir signaler ce message ?');">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-yellow-600 hover:bg-yellow-700">
                                <i class="fas fa-flag mr-2"></i>Signaler
                            </button>
                        </form>
                    @endif

                    @if($message->is_system_message && str_contains($message->content, 'Nouveau message de contact'))
                        @php
                            preg_match('/Email: ([^\n]+)/', $message->content, $emailMatch);
                            $contactEmail = $emailMatch[1] ?? '';
                        @endphp
                        @if($contactEmail)
                            <a href="mailto:{{ $contactEmail }}" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                                <i class="fas fa-reply mr-2"></i>Répondre par email
                            </a>
                        @endif
                    @endif
                </div>

                <div class="flex space-x-3">
                    <a href="{{ route('admin.messages.index') }}" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        <i class="fas fa-arrow-left mr-2"></i>Retour à la liste
                    </a>

                    <form action="{{ route('admin.messages.destroy', $message) }}" method="POST" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce message ?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-red-600 hover:bg-red-700">
                            <i class="fas fa-trash mr-2"></i>Supprimer
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Conversation History (if applicable) -->
    @if($conversation->count() > 1)
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Historique de la conversation</h3>
            </div>
            <div class="divide-y divide-gray-200">
                @foreach($conversation as $msg)
                    @if($msg->id !== $message->id)
                        <div class="p-6 {{ $msg->sender_id === $message->sender_id ? 'bg-blue-50' : 'bg-gray-50' }}">
                            <div class="flex items-start space-x-3">
                                <div class="flex-shrink-0">
                                    @if($msg->sender->avatar)
                                        <img class="h-8 w-8 rounded-full" src="{{ $msg->sender->avatar }}" alt="">
                                    @else
                                        <img class="h-8 w-8 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode($msg->sender->name) }}&color=7F9CF5&background=EBF4FF" alt="">
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center space-x-2">
                                        <p class="text-sm font-medium text-gray-900">{{ $msg->sender->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $msg->created_at->format('d/m/Y H:i') }}</p>
                                    </div>
                                    <p class="text-sm text-gray-700 mt-1 whitespace-pre-line">{{ $msg->content }}</p>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
