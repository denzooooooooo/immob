@extends('layouts.agent')

@section('title', 'Modifier ' . $property->title)

@section('header')
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Modifier la propriété</h1>
        <a href="{{ route('agent.properties.show', $property) }}" 
           class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
            <i class="fas fa-arrow-left mr-2"></i>
            Retour
        </a>
    </div>
@endsection

@section('content')
<div class="max-w-4xl mx-auto">
    <form action="{{ route('agent.properties.update', $property) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf
        @method('PUT')
        
        <!-- Informations de base -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-6">Informations de base</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Titre -->
                <div class="md:col-span-2">
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                        Titre de la propriété *
                    </label>
                    <input type="text" name="title" id="title" value="{{ old('title', $property->title) }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent @error('title') border-red-500 @enderror"
                           placeholder="Ex: Appartement moderne 3 pièces avec vue mer" required>
                    @error('title')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Type -->
                <div>
                    <label for="type" class="block text-sm font-medium text-gray-700 mb-2">
                        Type de propriété *
                    </label>
                    <select name="type" id="type" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent @error('type') border-red-500 @enderror" required>
                        <option value="">Sélectionner un type</option>
                        <option value="apartment" {{ old('type', $property->type) == 'apartment' ? 'selected' : '' }}>Appartement</option>
                        <option value="house" {{ old('type', $property->type) == 'house' ? 'selected' : '' }}>Maison</option>
                        <option value="villa" {{ old('type', $property->type) == 'villa' ? 'selected' : '' }}>Villa</option>
                        <option value="studio" {{ old('type', $property->type) == 'studio' ? 'selected' : '' }}>Studio</option>
                        <option value="office" {{ old('type', $property->type) == 'office' ? 'selected' : '' }}>Bureau</option>
                        <option value="shop" {{ old('type', $property->type) == 'shop' ? 'selected' : '' }}>Commerce</option>
                        <option value="land" {{ old('type', $property->type) == 'land' ? 'selected' : '' }}>Terrain</option>
                        <option value="warehouse" {{ old('type', $property->type) == 'warehouse' ? 'selected' : '' }}>Entrepôt</option>
                    </select>
                    @error('type')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Statut -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                        Statut *
                    </label>
                    <select name="status" id="status" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent @error('status') border-red-500 @enderror" required>
                        <option value="">Sélectionner un statut</option>
                        <option value="for_sale" {{ old('status', $property->status) == 'for_sale' ? 'selected' : '' }}>À vendre</option>
                        <option value="for_rent" {{ old('status', $property->status) == 'for_rent' ? 'selected' : '' }}>À louer</option>
                        <option value="hotel" {{ old('status', $property->status) == 'hotel' ? 'selected' : '' }}>Hôtel</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Prix -->
                <div>
                    <label for="price" class="block text-sm font-medium text-gray-700 mb-2">
                        Prix *
                    </label>
                    <input type="number" name="price" id="price" value="{{ old('price', $property->price) }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent @error('price') border-red-500 @enderror"
                           placeholder="0" min="0" step="1000" required>
                    @error('price')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Devise -->
                <div>
                    <label for="currency" class="block text-sm font-medium text-gray-700 mb-2">
                        Devise *
                    </label>
                    <select name="currency" id="currency" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent @error('currency') border-red-500 @enderror" required>
                        <option value="XAF" {{ old('currency', $property->currency) == 'XAF' ? 'selected' : '' }}>XAF (Franc CFA)</option>
                        <option value="EUR" {{ old('currency', $property->currency) == 'EUR' ? 'selected' : '' }}>EUR (Euro)</option>
                        <option value="USD" {{ old('currency', $property->currency) == 'USD' ? 'selected' : '' }}>USD (Dollar)</option>
                    </select>
                    @error('currency')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="md:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Description *
                    </label>
                    <textarea name="description" id="description" rows="4" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent @error('description') border-red-500 @enderror"
                              placeholder="Décrivez votre propriété en détail..." required>{{ old('description', $property->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Caractéristiques -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-6">Caractéristiques</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Surface -->
                <div>
                    <label for="surface_area" class="block text-sm font-medium text-gray-700 mb-2">
                        Surface (m²) *
                    </label>
                    <input type="number" name="surface_area" id="surface_area" value="{{ old('surface_area', $property->surface_area) }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent @error('surface_area') border-red-500 @enderror"
                           placeholder="0" min="1" step="1" required>
                    @error('surface_area')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Chambres -->
                <div>
                    <label for="bedrooms" class="block text-sm font-medium text-gray-700 mb-2">
                        Nombre de chambres
                    </label>
                    <input type="number" name="bedrooms" id="bedrooms" value="{{ old('bedrooms', $property->bedrooms) }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent @error('bedrooms') border-red-500 @enderror"
                           placeholder="0" min="0" step="1">
                    @error('bedrooms')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Salles de bain -->
                <div>
                    <label for="bathrooms" class="block text-sm font-medium text-gray-700 mb-2">
                        Nombre de salles de bain
                    </label>
                    <input type="number" name="bathrooms" id="bathrooms" value="{{ old('bathrooms', $property->bathrooms) }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent @error('bathrooms') border-red-500 @enderror"
                           placeholder="0" min="0" step="1">
                    @error('bathrooms')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Localisation -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-6">Localisation</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Adresse -->
                <div class="md:col-span-2">
                    <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                        Adresse complète *
                    </label>
                    <input type="text" name="address" id="address" value="{{ old('address', $property->address) }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent @error('address') border-red-500 @enderror"
                           placeholder="Ex: 123 Rue de la Paix, Quartier Bonanjo" required>
                    @error('address')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Ville -->
                <div>
                    <label for="city" class="block text-sm font-medium text-gray-700 mb-2">
                        Ville *
                    </label>
                    <select name="city" id="city" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent @error('city') border-red-500 @enderror" required>
                        <option value="">Sélectionner une ville</option>
                        @foreach($cities as $city)
                            <option value="{{ $city->name }}" {{ old('city', $property->city) == $city->name ? 'selected' : '' }}>
                                {{ $city->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('city')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Quartier -->
                <div>
                    <label for="neighborhood" class="block text-sm font-medium text-gray-700 mb-2">
                        Quartier
                    </label>
                    <select name="neighborhood" id="neighborhood" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent @error('neighborhood') border-red-500 @enderror">
                        <option value="">Sélectionner un quartier</option>
                        @foreach($neighborhoods as $neighborhood)
                            <option value="{{ $neighborhood->name }}" {{ old('neighborhood', $property->neighborhood) == $neighborhood->name ? 'selected' : '' }}>
                                {{ $neighborhood->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('neighborhood')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Coordonnées GPS -->
                <div>
                    <label for="latitude" class="block text-sm font-medium text-gray-700 mb-2">
                        Latitude
                    </label>
                    <input type="number" name="latitude" id="latitude" value="{{ old('latitude', $property->latitude) }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent @error('latitude') border-red-500 @enderror"
                           placeholder="Ex: 4.0511" step="any">
                    @error('latitude')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="longitude" class="block text-sm font-medium text-gray-700 mb-2">
                        Longitude
                    </label>
                    <input type="number" name="longitude" id="longitude" value="{{ old('longitude', $property->longitude) }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent @error('longitude') border-red-500 @enderror"
                           placeholder="Ex: 9.7679" step="any">
                    @error('longitude')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Images existantes -->
        @if($property->media->count() > 0)
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-6">Images existantes</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach($property->media as $media)
                        <div class="relative group">
                            <img src="{{ $media->path }}" alt="Image de la propriété" 
                                 class="w-full h-32 object-cover rounded-lg">
                            <div class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity rounded-lg">
                                <button type="button" onclick="deleteImage({{ $media->id }})" 
                                        class="text-white hover:text-red-500">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Nouvelles images -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-6">Ajouter des images</h3>
            
            <div>
                <label for="images" class="block text-sm font-medium text-gray-700 mb-2">
                    Nouvelles photos
                </label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                    <div class="space-y-1 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <div class="flex text-sm text-gray-600">
                            <label for="images" class="relative cursor-pointer bg-white rounded-md font-medium text-green-600 hover:text-green-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-green-500">
                                <span>Télécharger des images</span>
                                <input id="images" name="images[]" type="file" class="sr-only" multiple accept="image/*">
                            </label>
                            <p class="pl-1">ou glisser-déposer</p>
                        </div>
                        <p class="text-xs text-gray-500">
                            PNG, JPG, JPEG jusqu'à 5MB chacune
                        </p>
                    </div>
                </div>
                @error('images.*')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Options de publication -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-6">Options de publication</h3>
            
            <div class="space-y-4">
                <!-- Publier -->
                <div class="flex items-center">
                    <input type="checkbox" name="published" id="published" value="1" 
                           class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded"
                           {{ old('published', $property->published) ? 'checked' : '' }}>
                    <label for="published" class="ml-2 block text-sm text-gray-900">
                        Publier
                    </label>
                </div>

                <!-- Mettre en vedette -->
                <div class="flex items-center">
                    <input type="checkbox" name="featured" id="featured" value="1" 
                           class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded"
                           {{ old('featured', $property->featured) ? 'checked' : '' }}>
                    <label for="featured" class="ml-2 block text-sm text-gray-900">
                        Mettre en vedette
                    </label>
                </div>
            </div>
        </div>

        <!-- Boutons d'action -->
        <div class="flex justify-end space-x-4">
            <a href="{{ route('agent.properties.show', $property) }}" 
               class="px-6 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                Annuler
            </a>
            <button type="submit" 
                    class="px-6 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                <i class="fas fa-save mr-2"></i>
                Enregistrer les modifications
            </button>
        </div>
    </form>
</div>

<script>
// Prévisualisation des images
document.getElementById('images').addEventListener('change', function(e) {
    const files = e.target.files;
    const preview = document.getElementById('image-preview');
    
    if (!preview) {
        const previewDiv = document.createElement('div');
        previewDiv.id = 'image-preview';
        previewDiv.className = 'mt-4 grid grid-cols-2 md:grid-cols-4 gap-4';
        e.target.closest('.bg-white').appendChild(previewDiv);
    }
    
    document.getElementById('image-preview').innerHTML = '';
    
    for (let i = 0; i < files.length; i++) {
        const file = files[i];
        const reader = new FileReader();
        
        reader.onload = function(e) {
            const img = document.createElement('img');
            img.src = e.target.result;
            img.className = 'w-full h-24 object-cover rounded-md border';
            document.getElementById('image-preview').appendChild(img);
        };
        
        reader.readAsDataURL(file);
    }
});

// Suppression d'une image
function deleteImage(mediaId) {
    if (confirm('Voulez-vous vraiment supprimer cette image ?')) {
        fetch(`/agent/properties/media/${mediaId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Erreur lors de la suppression');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Erreur lors de la suppression');
        });
    }
}

// Filtrage des quartiers par ville
document.getElementById('city').addEventListener('change', function() {
    const selectedCity = this.value;
    const neighborhoodSelect = document.getElementById('neighborhood');
    
    // Ici vous pourriez faire un appel AJAX pour récupérer les quartiers de la ville sélectionnée
    // Pour l'instant, on garde tous les quartiers visibles
});
</script>
@endsection
