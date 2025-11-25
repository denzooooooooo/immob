@extends('layouts.admin')

@section('title', 'Modifier une propriété')

@section('content')
<div class="container-fluid px-4">
    <!-- En-tête moderne avec gradient violet-rouge -->
    <div class="property-header mb-5">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center mb-3">
                    <span class="badge badge-edit">Modification en cours</span>
                </div>
                <h1 class="property-title">Modifier la propriété</h1>
                <p class="property-subtitle">Mettez à jour les informations de cette propriété</p>
            </div>
            <div class="col-lg-4 text-end">
                <div class="action-buttons">
                    <a href="{{ route('admin.properties.show', $property) }}" class="btn btn-outline-primary btn-modern">
                        <i class="fas fa-eye"></i> Voir
                    </a>
                    <a href="{{ route('admin.properties.index') }}" class="btn btn-outline-secondary btn-modern">
                        <i class="fas fa-arrow-left"></i> Retour
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger mb-4">
            <h5><i class="fas fa-exclamation-triangle"></i> Erreurs de validation</h5>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.properties.update', $property->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Informations de base -->
        <div class="info-card mb-4">
            <div class="card-header-modern">
                <h3><i class="fas fa-info-circle"></i> Informations de base</h3>
            </div>
            <div class="card-body-modern">
                <div class="row g-4">
                    <div class="col-12">
                        <div class="form-group-modern">
                            <label for="title" class="form-label-modern">Titre de la propriété *</label>
                            <input type="text" name="title" id="title" value="{{ old('title', $property->title) }}" required
                                class="form-control-modern">
                            <div class="form-help">Un titre descriptif et accrocheur pour la propriété</div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group-modern">
                            <label for="type" class="form-label-modern">Type de propriété *</label>
                            <select name="type" id="type" required class="form-control-modern">
                                <option value="">Sélectionnez un type</option>
                                <option value="apartment" {{ old('type', $property->type) == 'apartment' ? 'selected' : '' }}>🏢 Appartement</option>
                                <option value="house" {{ old('type', $property->type) == 'house' ? 'selected' : '' }}>🏠 Maison</option>
                                <option value="villa" {{ old('type', $property->type) == 'villa' ? 'selected' : '' }}>🏰 Villa</option>
                                <option value="studio" {{ old('type', $property->type) == 'studio' ? 'selected' : '' }}>🛋️ Studio</option>
                                <option value="office" {{ old('type', $property->type) == 'office' ? 'selected' : '' }}>🏢 Bureau</option>
                                <option value="shop" {{ old('type', $property->type) == 'shop' ? 'selected' : '' }}>🏪 Commerce</option>
                                <option value="land" {{ old('type', $property->type) == 'land' ? 'selected' : '' }}>🌳 Terrain</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group-modern">
                            <label for="status" class="form-label-modern">Statut *</label>
                            <select name="status" id="status" required class="form-control-modern">
                                <option value="">Sélectionnez un statut</option>
                                <option value="for_sale" {{ old('status', $property->status) == 'for_sale' ? 'selected' : '' }}>💰 À vendre</option>
                                <option value="for_rent" {{ old('status', $property->status) == 'for_rent' ? 'selected' : '' }}>🏠 À louer</option>
                                <option value="sold" {{ old('status', $property->status) == 'sold' ? 'selected' : '' }}>✅ Vendu</option>
                                <option value="rented" {{ old('status', $property->status) == 'rented' ? 'selected' : '' }}>✅ Loué</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group-modern">
                            <label for="price" class="form-label-modern">Prix *</label>
                            <div class="input-group-modern">
                                <input type="number" name="price" id="price" value="{{ old('price', $property->price) }}" min="0" step="0.01" required
                                    class="form-control-modern">
                                <select name="currency" id="currency" class="form-control-modern currency-select">
                                    <option value="XAF" {{ old('currency', $property->currency) == 'XAF' ? 'selected' : '' }}>XAF</option>
                                    <option value="EUR" {{ old('currency', $property->currency) == 'EUR' ? 'selected' : '' }}>EUR</option>
                                    <option value="USD" {{ old('currency', $property->currency) == 'USD' ? 'selected' : '' }}>USD</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group-modern">
                            <label for="surface_area" class="form-label-modern">Surface (m²) *</label>
                            <input type="number" name="surface_area" id="surface_area" value="{{ old('surface_area', $property->surface_area) }}" min="0" step="0.01" required
                                class="form-control-modern">
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-group-modern">
                            <label for="description" class="form-label-modern">Description détaillée *</label>
                            <textarea name="description" id="description" rows="6" required
                                class="form-control-modern textarea-modern" placeholder="Décrivez la propriété en détail...">{{ old('description', $property->description) }}</textarea>
                            <div class="form-help">Une description complète et attractive aidera les visiteurs à mieux comprendre la propriété</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Caractéristiques détaillées -->
        <div class="info-card mb-4">
            <div class="card-header-modern">
                <h3><i class="fas fa-cogs"></i> Caractéristiques détaillées</h3>
            </div>
            <div class="card-body-modern">
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="form-group-modern">
                            <label for="bedrooms" class="form-label-modern">Nombre de chambres</label>
                            <input type="number" name="bedrooms" id="bedrooms" value="{{ old('bedrooms', $property->bedrooms) }}" min="0"
                                class="form-control-modern">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group-modern">
                            <label for="bathrooms" class="form-label-modern">Nombre de salles de bain</label>
                            <input type="number" name="bathrooms" id="bathrooms" value="{{ old('bathrooms', $property->bathrooms) }}" min="0"
                                class="form-control-modern">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group-modern">
                            <label for="year_built" class="form-label-modern">Année de construction</label>
                            <input type="number" name="year_built" id="year_built" value="{{ old('year_built', $property->year_built) }}" min="1900"
                                class="form-control-modern">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group-modern">
                            <label for="parking_spaces" class="form-label-modern">Places de parking</label>
                            <input type="number" name="parking_spaces" id="parking_spaces" value="{{ old('parking_spaces', $property->parking_spaces) }}" min="0"
                                class="form-control-modern">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group-modern">
                            <label class="form-label-modern">Équipements disponibles</label>
                            <div class="amenities-grid-edit">
                                <label class="amenity-checkbox">
                                    <input type="checkbox" name="furnished" value="1" {{ old('furnished', $property->furnished) ? 'checked' : '' }}>
                                    <span class="checkmark"></span>
                                    <i class="fas fa-couch"></i> Meublé
                                </label>
                                <label class="amenity-checkbox">
                                    <input type="checkbox" name="air_conditioning" value="1" {{ old('air_conditioning', $property->air_conditioning) ? 'checked' : '' }}>
                                    <span class="checkmark"></span>
                                    <i class="fas fa-snowflake"></i> Climatisation
                                </label>
                                <label class="amenity-checkbox">
                                    <input type="checkbox" name="swimming_pool" value="1" {{ old('swimming_pool', $property->swimming_pool) ? 'checked' : '' }}>
                                    <span class="checkmark"></span>
                                    <i class="fas fa-swimming-pool"></i> Piscine
                                </label>
                                <label class="amenity-checkbox">
                                    <input type="checkbox" name="security_system" value="1" {{ old('security_system', $property->security_system) ? 'checked' : '' }}>
                                    <span class="checkmark"></span>
                                    <i class="fas fa-shield-alt"></i> Sécurité
                                </label>
                                <label class="amenity-checkbox">
                                    <input type="checkbox" name="internet" value="1" {{ old('internet', $property->internet) ? 'checked' : '' }}>
                                    <span class="checkmark"></span>
                                    <i class="fas fa-wifi"></i> Internet
                                </label>
                                <label class="amenity-checkbox">
                                    <input type="checkbox" name="garden" value="1" {{ old('garden', $property->garden) ? 'checked' : '' }}>
                                    <span class="checkmark"></span>
                                    <i class="fas fa-seedling"></i> Jardin
                                </label>
                                <label class="amenity-checkbox">
                                    <input type="checkbox" name="balcony" value="1" {{ old('balcony', $property->balcony) ? 'checked' : '' }}>
                                    <span class="checkmark"></span>
                                    <i class="fas fa-building"></i> Balcon
                                </label>
                                <label class="amenity-checkbox">
                                    <input type="checkbox" name="elevator" value="1" {{ old('elevator', $property->elevator) ? 'checked' : '' }}>
                                    <span class="checkmark"></span>
                                    <i class="fas fa-elevator"></i> Ascenseur
                                </label>
                                <label class="amenity-checkbox">
                                    <input type="checkbox" name="garage" value="1" {{ old('garage', $property->garage) ? 'checked' : '' }}>
                                    <span class="checkmark"></span>
                                    <i class="fas fa-warehouse"></i> Garage
                                </label>
                                <label class="amenity-checkbox">
                                    <input type="checkbox" name="terrace" value="1" {{ old('terrace', $property->terrace) ? 'checked' : '' }}>
                                    <span class="checkmark"></span>
                                    <i class="fas fa-umbrella-beach"></i> Terrasse
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Localisation -->
        <div class="info-card mb-4">
            <div class="card-header-modern">
                <h3><i class="fas fa-map-marker-alt"></i> Localisation</h3>
            </div>
            <div class="card-body-modern">
                <div class="row g-4">
                    <div class="col-12">
                        <div class="form-group-modern">
                            <label for="address" class="form-label-modern">Adresse complète *</label>
                            <input type="text" name="address" id="address" value="{{ old('address', $property->address) }}" required
                                class="form-control-modern" placeholder="Numéro, rue, quartier...">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group-modern">
                            <label for="city" class="form-label-modern">Ville *</label>
                            <select name="city" id="city" required class="form-control-modern">
                                <option value="">Sélectionnez une ville</option>
                                @foreach ($cities as $city)
                                    <option value="{{ $city->slug }}" {{ old('city', $property->city ?? '') == $city->slug ? 'selected' : '' }}>
                                        {{ $city->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group-modern">
                            <label for="neighborhood" class="form-label-modern">Quartier</label>
                            <select name="neighborhood" id="neighborhood" class="form-control-modern"
                                data-current-neighborhood="{{ old('neighborhood', $property->neighborhood) }}">
                                <option value="">Sélectionnez un quartier</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gestion des médias -->
        <div class="info-card mb-4">
            <div class="card-header-modern">
                <h3><i class="fas fa-images"></i> Médias de la propriété</h3>
            </div>
            <div class="card-body-modern">
                @if($property->media->isNotEmpty())
                    <div class="mb-6">
                        <h4 class="text-lg font-semibold mb-4 text-gray-800">Images existantes</h4>
                        <div class="media-grid">
                            @foreach($property->media as $media)
                                <div class="media-item">
                                    @if($media->type === 'image')
                                        <img src="{{ $media->url }}" alt="Image de la propriété" class="media-preview">
                                    @elseif($media->type === 'video')
                                        <div class="video-preview">
                                            <video class="media-video">
                                                <source src="{{ $media->url }}" type="video/mp4">
                                            </video>
                                            <div class="video-overlay">
                                                <i class="fas fa-play-circle text-2xl"></i>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="media-actions">
                                        @if($media->is_featured)
                                            <span class="featured-indicator">
                                                <i class="fas fa-star"></i> Image principale
                                            </span>
                                        @endif
                                        <button type="button" onclick="deleteMedia({{ $media->id }})" class="delete-media-btn">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="form-group-modern">
                            <label class="form-label-modern">Ajouter des images</label>
                            <div class="file-upload-area" onclick="document.getElementById('images-input').click()">
                                <i class="fas fa-cloud-upload-alt upload-icon"></i>
                                <p class="upload-text">Cliquez pour ajouter des images</p>
                                <p class="upload-subtext">JPG, JPEG, PNG - Max 10MB chacune</p>
                                <input type="file" name="images[]" id="images-input" multiple accept="image/jpeg,image/png,image/jpg" style="display: none;">
                            </div>
                            <div id="images-preview" class="file-preview"></div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group-modern">
                            <label class="form-label-modern">Ajouter des vidéos</label>
                            <div class="file-upload-area" onclick="document.getElementById('videos-input').click()">
                                <i class="fas fa-video upload-icon"></i>
                                <p class="upload-text">Cliquez pour ajouter des vidéos</p>
                                <p class="upload-subtext">MP4, MOV - Max 50MB chacune</p>
                                <input type="file" name="videos[]" id="videos-input" multiple accept="video/mp4,video/quicktime" style="display: none;">
                            </div>
                            <div id="videos-preview" class="file-preview"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Options de publication -->
        <div class="info-card mb-4">
            <div class="card-header-modern">
                <h3><i class="fas fa-bullhorn"></i> Options de publication</h3>
            </div>
            <div class="card-body-modern">
                <div class="publication-options">
                    <div class="option-item">
                        <div class="option-toggle">
                            <input type="checkbox" name="featured" value="1" id="featured" {{ old('featured', $property->featured) ? 'checked' : '' }}>
                            <label for="featured" class="toggle-slider"></label>
                        </div>
                        <div class="option-content">
                            <h4>Mettre en avant</h4>
                            <p>Cette propriété sera mise en évidence dans les recherches</p>
                        </div>
                    </div>

                    <div class="option-item">
                        <div class="option-toggle">
                            <input type="checkbox" name="published" value="1" id="published" {{ old('published', $property->published) ? 'checked' : '' }}>
                            <label for="published" class="toggle-slider"></label>
                        </div>
                        <div class="option-content">
                            <h4>Publier la propriété</h4>
                            <p>Rendre cette propriété visible pour tous les visiteurs</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Boutons d'action -->
        <div class="action-footer">
            <div class="action-buttons">
                <a href="{{ route('admin.properties.index') }}" class="btn btn-secondary-modern">
                    <i class="fas fa-times"></i> Annuler
                </a>
                <button type="submit" class="btn btn-primary-modern">
                    <i class="fas fa-save"></i> Mettre à jour la propriété
                </button>
            </div>
        </div>
    </form>
</div>

<style>
/* Styles modernes pour la page d'édition avec thème violet-rouge */
.property-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 20px;
    padding: 2rem;
    color: white;
    margin-bottom: 2rem;
}

.property-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    color: white;
}

.property-subtitle {
    color: rgba(255, 255, 255, 0.8);
    font-size: 1.1rem;
}

.badge-edit {
    background: linear-gradient(45deg, #ffd700, #ffa500);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 50px;
    font-weight: 600;
}

/* Cartes d'information */
.info-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    overflow: hidden;
    margin-bottom: 2rem;
}

.card-header-modern {
    padding: 1.5rem;
    border-bottom: 1px solid #eee;
    background: linear-gradient(to right, #f8f9fa, #ffffff);
}

.card-body-modern {
    padding: 2rem;
}

/* Formulaires modernes */
.form-group-modern {
    margin-bottom: 1.5rem;
}

.form-label-modern {
    display: block;
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 0.5rem;
    font-size: 0.95rem;
}

.form-control-modern {
    width: 100%;
    padding: 0.875rem 1rem;
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: white;
}

.form-control-modern:focus {
    outline: none;
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.textarea-modern {
    resize: vertical;
    min-height: 120px;
}

.form-help {
    color: #718096;
    font-size: 0.875rem;
    margin-top: 0.25rem;
}

.input-group-modern {
    display: flex;
    gap: 0.5rem;
}

.currency-select {
    flex: 0 0 100px;
}

/* Cases à cocher des équipements */
.amenities-grid-edit {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-top: 1rem;
}

.amenity-checkbox {
    display: flex;
    align-items: center;
    padding: 0.75rem;
    background: #f8f9fa;
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
}

.amenity-checkbox:hover {
    background: #e9ecef;
}

.amenity-checkbox input[type="checkbox"] {
    position: absolute;
    opacity: 0;
    cursor: pointer;
}

.checkmark {
    width: 20px;
    height: 20px;
    background: white;
    border: 2px solid #cbd5e0;
    border-radius: 4px;
    margin-right: 0.75rem;
    position: relative;
    transition: all 0.3s ease;
}

.amenity-checkbox input[type="checkbox"]:checked ~ .checkmark {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-color: #667eea;
}

.amenity-checkbox input[type="checkbox"]:checked ~ .checkmark::after {
    content: '✓';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    color: white;
    font-size: 12px;
    font-weight: bold;
}

.amenity-checkbox i {
    color: #667eea;
    margin-right: 0.5rem;
}

/* Gestion des médias */
.media-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 1rem;
    margin-bottom: 2rem;
}

.media-item {
    position: relative;
    border-radius: 10px;
    overflow: hidden;
    background: #f8f9fa;
}

.media-preview {
    width: 100%;
    height: 150px;
    object-fit: cover;
}

.video-preview {
    position: relative;
    width: 100%;
    height: 150px;
}

.media-video {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.video-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
}

.media-actions {
    position: absolute;
    top: 10px;
    right: 10px;
    display: flex;
    gap: 0.5rem;
}

.featured-indicator {
    background: rgba(255, 215, 0, 0.9);
    color: white;
    padding: 0.25rem 0.5rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
}

.delete-media-btn {
    background: rgba(220, 38, 38, 0.9);
    color: white;
    border: none;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
}

.delete-media-btn:hover {
    background: rgba(185, 28, 28, 0.9);
    transform: scale(1.1);
}

/* Zone de téléchargement de fichiers */
.file-upload-area {
    border: 2px dashed #cbd5e0;
    border-radius: 10px;
    padding: 2rem;
    text-align: center;
    cursor: pointer;
    transition: all 0.3s ease;
    background: #f8f9fa;
}

.file-upload-area:hover {
    border-color: #667eea;
    background: #f0f4ff;
}

.upload-icon {
    font-size: 3rem;
    color: #a0aec0;
    margin-bottom: 1rem;
}

.upload-text {
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 0.5rem;
}

.upload-subtext {
    color: #718096;
    font-size: 0.875rem;
}

.file-preview {
    margin-top: 1rem;
}

/* Options de publication */
.publication-options {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.option-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 10px;
}

.option-toggle {
    position: relative;
}

.toggle-slider {
    position: relative;
    display: inline-block;
    width: 50px;
    height: 24px;
    background: #cbd5e0;
    border-radius: 24px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.toggle-slider::before {
    content: '';
    position: absolute;
    top: 2px;
    left: 2px;
    width: 20px;
    height: 20px;
    background: white;
    border-radius: 50%;
    transition: all 0.3s ease;
}

input[type="checkbox"]:checked + .toggle-slider {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

input[type="checkbox"]:checked + .toggle-slider::before {
    transform: translateX(26px);
}

.option-content h4 {
    margin: 0;
    color: #2d3748;
    font-weight: 600;
}

.option-content p {
    margin: 0.25rem 0 0 0;
    color: #718096;
    font-size: 0.875rem;
}

/* Pied de page des actions */
.action-footer {
    background: white;
    border-radius: 15px;
    padding: 2rem;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.action-buttons {
    display: flex;
    justify-content: flex-end;
    gap: 1rem;
}

.btn-modern {
    padding: 0.75rem 1.5rem;
    border-radius: 10px;
    font-weight: 600;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
}

.btn-primary-modern {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.btn-primary-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 6px rgba(102, 126, 234, 0.3);
}

.btn-secondary-modern {
    background: #6c757d;
    color: white;
}

.btn-secondary-modern:hover {
    background: #5a6268;
    transform: translateY(-2px);
}

/* Responsive */
@media (max-width: 768px) {
    .property-header {
        padding: 1.5rem;
    }

    .property-title {
        font-size: 2rem;
    }

    .card-body-modern {
        padding: 1.5rem;
    }

    .action-buttons {
        flex-direction: column;
    }

    .btn-modern {
        width: 100%;
    }
}
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gestion des quartiers dynamiques
    const citySelect = document.getElementById('city');
    const neighborhoodSelect = document.getElementById('neighborhood');
    const currentNeighborhood = neighborhoodSelect.getAttribute('data-current-neighborhood');

    function updateNeighborhoods(citySlug) {
        neighborhoodSelect.innerHTML = '<option value="">Chargement...</option>';
        neighborhoodSelect.disabled = true;

        if (!citySlug) {
            neighborhoodSelect.innerHTML = '<option value="">Sélectionnez un quartier</option>';
            neighborhoodSelect.disabled = false;
            return;
        }

        fetch('/api/cities/' + citySlug + '/neighborhoods')
            .then(response => response.json())
            .then(neighborhoods => {
                neighborhoodSelect.innerHTML = '<option value="">Sélectionnez un quartier</option>';
                neighborhoods.forEach(neighborhood => {
                    const option = document.createElement('option');
                    option.value = neighborhood.slug;
                    option.textContent = neighborhood.name;
                    if (neighborhood.slug === currentNeighborhood) {
                        option.selected = true;
                    }
                    neighborhoodSelect.appendChild(option);
                });
                neighborhoodSelect.disabled = false;
            })
            .catch(error => {
                console.error('Erreur lors du chargement des quartiers:', error);
                neighborhoodSelect.innerHTML = '<option value="">Erreur de chargement</option>';
            });
    }

    citySelect.addEventListener('change', function(e) {
        updateNeighborhoods(e.target.value);
    });

    // Initialisation si une ville est déjà sélectionnée
    if (citySelect.value) {
        updateNeighborhoods(citySelect.value);
    }

    // Gestion des fichiers
    function handleFilePreview(input, previewContainer, type) {
        const files = Array.from(input.files);
        previewContainer.innerHTML = '';

        files.forEach((file, index) => {
            const previewItem = document.createElement('div');
            previewItem.className = 'file-preview-item';

            if (type === 'image' && file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewItem.innerHTML = `
                        <img src="${e.target.result}" alt="${file.name}" class="file-thumbnail">
                        <div class="file-info">
                            <span class="file-name">${file.name}</span>
                            <span class="file-size">${(file.size / 1024 / 1024).toFixed(2)} MB</span>
                        </div>
                        <button type="button" class="remove-file" onclick="this.parentElement.remove()">×</button>
                    `;
                };
                reader.readAsDataURL(file);
            } else if (type === 'video' && file.type.startsWith('video/')) {
                previewItem.innerHTML = `
                    <div class="file-thumbnail video-placeholder">
                        <i class="fas fa-video"></i>
                    </div>
                    <div class="file-info">
                        <span class="file-name">${file.name}</span>
                        <span class="file-size">${(file.size / 1024 / 1024).toFixed(2)} MB</span>
                    </div>
                    <button type="button" class="remove-file" onclick="this.parentElement.remove()">×</button>
                `;
            }

            previewContainer.appendChild(previewItem);
        });
    }

    // Gestion des aperçus de fichiers
    document.getElementById('images-input').addEventListener('change', function() {
        handleFilePreview(this, document.getElementById('images-preview'), 'image');
    });

    document.getElementById('videos-input').addEventListener('change', function() {
        handleFilePreview(this, document.getElementById('videos-preview'), 'video');
    });

    // Suppression de média existant
    window.deleteMedia = function(mediaId) {
        if (!confirm('Êtes-vous sûr de vouloir supprimer ce média ?')) {
            return;
        }

        const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        fetch('/admin/properties/media/' + mediaId, {
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
            if (data.success) {
                location.reload();
            } else {
                alert('Une erreur est survenue lors de la suppression');
            }
        })
        .catch(function(error) {
            console.error('Erreur:', error);
            alert('Une erreur est survenue lors de la suppression');
        });
    };

    // Animation des toggles
    const toggles = document.querySelectorAll('input[type="checkbox"]');
    toggles.forEach(toggle => {
        toggle.addEventListener('change', function() {
            const slider = this.nextElementSibling;
            if (this.checked) {
                slider.style.transform = 'scale(1.05)';
                setTimeout(() => slider.style.transform = '', 150);
            }
        });
    });
});
</script>

<style>
/* Styles supplémentaires pour les aperçus de fichiers */
.file-preview-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 8px;
    margin-bottom: 0.5rem;
}

.file-thumbnail {
    width: 50px;
    height: 50px;
    object-fit: cover;
    border-radius: 6px;
}

.video-placeholder {
    display: flex;
    align-items: center;
    justify-content: center;
    background: #e9ecef;
    color: #6c757d;
    font-size: 1.5rem;
}

.file-info {
    flex: 1;
}

.file-name {
    display: block;
    font-weight: 500;
    color: #2d3748;
    font-size: 0.9rem;
}

.file-size {
    display: block;
    color: #718096;
    font-size: 0.8rem;
}

.remove-file {
    background: #dc3545;
    color: white;
    border: none;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    transition: all 0.3s ease;
}

.remove-file:hover {
    background: #c82333;
    transform: scale(1.1);
}
</style>
@endpush
@endsection
