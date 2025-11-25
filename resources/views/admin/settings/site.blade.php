@extends('layouts.admin')

@section('title', 'Paramètres du Site')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Paramètres du Site</h1>
        <div class="text-sm text-gray-500">
            <i class="fas fa-cog mr-2"></i>
            Configuration générale
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 rounded-xl bg-green-50 border border-green-200 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <i class="fas fa-check-circle text-green-400"></i>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">
                        {{ session('success') }}
                    </p>
                </div>
            </div>
        </div>
    @endif

    <form action="{{ route('admin.settings.site.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf
        @method('PUT')

        @foreach($settings as $group => $groupSettings)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-900 capitalize">
                        @switch($group)
                            @case('hero')
                                <i class="fas fa-image mr-2 text-blue-500"></i>
                                Section Hero
                                @break
                            @case('general')
                                <i class="fas fa-globe mr-2 text-green-500"></i>
                                Paramètres Généraux
                                @break
                            @case('contact')
                                <i class="fas fa-phone mr-2 text-purple-500"></i>
                                Informations de Contact
                                @break
                            @case('features')
                                <i class="fas fa-star mr-2 text-yellow-500"></i>
                                Fonctionnalités
                                @break
                            @case('social')
                                <i class="fas fa-share-alt mr-2 text-pink-500"></i>
                                Réseaux Sociaux
                                @break
                            @default
                                <i class="fas fa-cog mr-2 text-gray-500"></i>
                                {{ ucfirst($group) }}
                        @endswitch
                    </h2>
                </div>

                <div class="p-6 space-y-6">
                    @foreach($groupSettings as $setting)
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 items-start">
                            <div class="lg:col-span-1">
                                <label class="block text-sm font-medium text-gray-700 mb-1">
                                    {{ $setting['label'] }}
                                </label>
                                @if($setting['description'])
                                    <p class="text-xs text-gray-500">{{ $setting['description'] }}</p>
                                @endif
                            </div>

                            <div class="lg:col-span-2">
                                @switch($setting['type'])
                                    @case('image')
                                        <div class="space-y-3">
                                            @if($setting['value'])
                                                <div class="relative inline-block">
                                                    <img src="{{ asset('storage/' . $setting['value']) }}" 
                                                         alt="{{ $setting['label'] }}"
                                                         class="w-32 h-20 object-cover rounded-lg border border-gray-300">
                                                    <button type="button" 
                                                            onclick="deleteImage('{{ $setting['key'] }}')"
                                                            class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center text-xs hover:bg-red-600">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </div>
                                            @endif
                                            <input type="file" 
                                                   name="files[{{ $setting['key'] }}]" 
                                                   accept="image/*"
                                                   class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                        </div>
                                        @break

                                    @case('boolean')
                                        <label class="relative inline-flex items-center cursor-pointer">
                                            <input type="hidden" name="settings[{{ $setting['key'] }}]" value="false">
                                            <input type="checkbox" 
                                                   name="settings[{{ $setting['key'] }}]" 
                                                   value="true"
                                                   {{ $setting['value'] === 'true' ? 'checked' : '' }}
                                                   class="sr-only peer">
                                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                        </label>
                                        @break

                                    @case('text')
                                    @default
                                        <input type="text" 
                                               name="settings[{{ $setting['key'] }}]" 
                                               value="{{ $setting['value'] }}"
                                               class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                        @break
                                @endswitch
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach

        <div class="flex justify-end space-x-4">
            <button type="button" 
                    onclick="window.location.reload()"
                    class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <i class="fas fa-undo mr-2"></i>
                Annuler
            </button>
            <button type="submit"
                    class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                <i class="fas fa-save mr-2"></i>
                Enregistrer les modifications
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
function deleteImage(key) {
    if (!confirm('Êtes-vous sûr de vouloir supprimer cette image ?')) {
        return;
    }

    var token = document.querySelector('meta[name="csrf-token"]').content;
    var url = '{{ url("admin/settings/site/delete-image") }}/' + key;

    fetch(url, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json'
        }
    })
    .then(function(response) {
        return response.json();
    })
    .then(function(data) {
        if (data.message) {
            window.location.reload();
        } else {
            alert('Erreur lors de la suppression de l\'image');
        }
    })
    .catch(function(error) {
        console.error('Error:', error);
        alert('Erreur lors de la suppression de l\'image');
    });
}
</script>
@endpush
@endsection
