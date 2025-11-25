@extends('layouts.admin')

@section('title', 'Détails de la propriété')

@section('content')
<div class="container-fluid px-4">
    <!-- En-tête moderne avec gradient violet-rouge -->
    <div class="property-header mb-5">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <div class="d-flex align-items-center mb-3">
                    <div class="property-status-badge me-3">
                        @if($property->featured)
                            <span class="badge badge-featured"><i class="fas fa-star"></i> Vedette</span>
                        @endif
                        <span class="badge badge-{{ $property->published ? 'published' : 'draft' }}">
                            {{ $property->published ? 'Publié' : 'Brouillon' }}
                        </span>
                    </div>
                </div>
                <h1 class="property-title">{{ $property->title }}</h1>
                <div class="property-meta">
                    <span class="meta-item">
                        <i class="fas fa-map-marker-alt"></i>
                        {{ $property->address }}, {{ $property->city }}
                    </span>
                    <span class="meta-item">
                        <i class="fas fa-calendar"></i>
                        Créé le {{ $property->created_at->format('d/m/Y') }}
                    </span>
                </div>
            </div>
            <div class="col-lg-4 text-end">
                <div class="property-price mb-3">
                    {{ number_format($property->price, 0, ',', ' ') }} <span class="currency">{{ $property->currency }}</span>
                </div>
                <div class="action-buttons">
                    <a href="{{ route('admin.properties.edit', $property) }}" class="btn btn-primary btn-modern">
                        <i class="fas fa-edit"></i> Modifier
                    </a>
                    <div class="dropdown d-inline-block">
                        <button class="btn btn-outline-secondary btn-modern dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="fas fa-ellipsis-v"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <form action="{{ route('admin.properties.toggle-featured', $property) }}" method="POST" class="d-inline">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="dropdown-item">
                                        <i class="fas fa-star text-warning"></i>
                                        {{ $property->featured ? 'Retirer de la une' : 'Mettre en une' }}
                                    </button>
                                </form>
                            </li>
                            <li>
                                <form action="{{ route('admin.properties.toggle-published', $property) }}" method="POST" class="d-inline">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="dropdown-item">
                                        <i class="fas fa-eye text-info"></i>
                                        {{ $property->published ? 'Dépublier' : 'Publier' }}
                                    </button>
                                </form>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('admin.properties.destroy', $property) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette propriété ?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="fas fa-trash"></i> Supprimer
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Contenu principal -->
        <div class="col-lg-8">
            <!-- Galerie de médias -->
            @if($property->media->count() > 0)
            <div class="media-gallery mb-4">
                <div class="gallery-header">
                    <h3><i class="fas fa-images"></i> Galerie ({{ $property->media->count() }} médias)</h3>
                </div>
                <div class="gallery-grid">
                    @foreach($property->media->take(6) as $index => $media)
                    <div class="gallery-item {{ $index === 0 ? 'featured' : '' }}">
                        @if($media->type === 'image')
                            <img src="{{ $media->url }}" alt="Image de la propriété" class="gallery-image">
                            @if($media->is_featured)
                                <div class="featured-badge"><i class="fas fa-star"></i></div>
                            @endif
                        @elseif($media->type === 'video')
                            <div class="video-thumbnail">
                                <video class="gallery-video">
                                    <source src="{{ $media->url }}" type="video/mp4">
                                </video>
                                <div class="play-button"><i class="fas fa-play"></i></div>
                            </div>
                        @endif
                        @if($index === 5 && $property->media->count() > 6)
                            <div class="more-overlay">
                                <span>+{{ $property->media->count() - 6 }}</span>
                            </div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Informations principales -->
            <div class="info-card mb-4">
                <div class="card-header-modern">
                    <h3><i class="fas fa-info-circle"></i> Informations générales</h3>
                </div>
                <div class="card-body-modern">
                    <div class="property-specs">
                        <div class="spec-item">
                            <div class="spec-icon"><i class="fas fa-home"></i></div>
                            <div class="spec-content">
                                <span class="spec-label">Type</span>
                                <span class="spec-value">{{ ucfirst($property->type) }}</span>
                            </div>
                        </div>
                        <div class="spec-item">
                            <div class="spec-icon"><i class="fas fa-tag"></i></div>
                            <div class="spec-content">
                                <span class="spec-label">Statut</span>
                                <span class="spec-value">{{ ucfirst(str_replace('_', ' ', $property->status)) }}</span>
                            </div>
                        </div>
                        <div class="spec-item">
                            <div class="spec-icon"><i class="fas fa-expand-arrows-alt"></i></div>
                            <div class="spec-content">
                                <span class="spec-label">Surface</span>
                                <span class="spec-value">{{ $property->surface_area }} m²</span>
                            </div>
                        </div>
                        @if($property->bedrooms)
                        <div class="spec-item">
                            <div class="spec-icon"><i class="fas fa-bed"></i></div>
                            <div class="spec-content">
                                <span class="spec-label">Chambres</span>
                                <span class="spec-value">{{ $property->bedrooms }}</span>
                            </div>
                        </div>
                        @endif
                        @if($property->bathrooms)
                        <div class="spec-item">
                            <div class="spec-icon"><i class="fas fa-bath"></i></div>
                            <div class="spec-content">
                                <span class="spec-label">Salles de bain</span>
                                <span class="spec-value">{{ $property->bathrooms }}</span>
                            </div>
                        </div>
                        @endif
                    </div>

                    <div class="description-section">
                        <h4>Description</h4>
                        <p class="description-text">{{ $property->description }}</p>
                    </div>
                </div>
            </div>

            <!-- Détails et équipements -->
            @if($property->details)
            <div class="info-card mb-4">
                <div class="card-header-modern">
                    <h3><i class="fas fa-cogs"></i> Équipements et détails</h3>
                </div>
                <div class="card-body-modern">
                    <div class="amenities-grid">
                        @if($property->details->year_built)
                        <div class="amenity-item">
                            <i class="fas fa-calendar-alt"></i>
                            <span>Construit en {{ $property->details->year_built }}</span>
                        </div>
                        @endif
                        @if($property->details->parking_spaces)
                        <div class="amenity-item">
                            <i class="fas fa-car"></i>
                            <span>{{ $property->details->parking_spaces }} place(s) de parking</span>
                        </div>
                        @endif
                        @if($property->details->furnished)
                        <div class="amenity-item active">
                            <i class="fas fa-couch"></i>
                            <span>Meublé</span>
                        </div>
                        @endif
                        @if($property->details->air_conditioning)
                        <div class="amenity-item active">
                            <i class="fas fa-snowflake"></i>
                            <span>Climatisation</span>
                        </div>
                        @endif
                        @if($property->details->swimming_pool)
                        <div class="amenity-item active">
                            <i class="fas fa-swimming-pool"></i>
                            <span>Piscine</span>
                        </div>
                        @endif
                        @if($property->details->security_system)
                        <div class="amenity-item active">
                            <i class="fas fa-shield-alt"></i>
                            <span>Système de sécurité</span>
                        </div>
                        @endif
                        @if($property->details->internet)
                        <div class="amenity-item active">
                            <i class="fas fa-wifi"></i>
                            <span>Internet</span>
                        </div>
                        @endif
                        @if($property->details->garden)
                        <div class="amenity-item active">
                            <i class="fas fa-seedling"></i>
                            <span>Jardin</span>
                        </div>
                        @endif
                        @if($property->details->balcony)
                        <div class="amenity-item active">
                            <i class="fas fa-building"></i>
                            <span>Balcon</span>
                        </div>
                        @endif
                        @if($property->details->elevator)
                        <div class="amenity-item active">
                            <i class="fas fa-elevator"></i>
                            <span>Ascenseur</span>
                        </div>
                        @endif
                        @if($property->details->garage)
                        <div class="amenity-item active">
                            <i class="fas fa-warehouse"></i>
                            <span>Garage</span>
                        </div>
                        @endif
                        @if($property->details->terrace)
                        <div class="amenity-item active">
                            <i class="fas fa-umbrella-beach"></i>
                            <span>Terrasse</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Agent -->
            <div class="sidebar-card mb-4">
                <div class="agent-card">
                    <div class="agent-avatar">
                        {{ strtoupper(substr($property->user->name, 0, 2)) }}
                    </div>
                    <div class="agent-info">
                        <h4>{{ $property->user->name }}</h4>
                        <p>{{ $property->user->email }}</p>
                        <span class="agent-role">Agent immobilier</span>
                    </div>
                </div>
            </div>

            <!-- Statistiques -->
            <div class="sidebar-card mb-4">
                <h4><i class="fas fa-chart-line"></i> Statistiques</h4>
                <div class="stats-grid">
                    <div class="stat-item">
                        <div class="stat-number">{{ $property->views_count ?? 0 }}</div>
                        <div class="stat-label">Vues</div>
                        <div class="stat-icon"><i class="fas fa-eye"></i></div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-number">{{ $property->favorites_count ?? 0 }}</div>
                        <div class="stat-label">Favoris</div>
                        <div class="stat-icon"><i class="fas fa-heart"></i></div>
                    </div>
                </div>
            </div>

            <!-- Localisation -->
            <div class="sidebar-card mb-4">
                <h4><i class="fas fa-map-marker-alt"></i> Localisation</h4>
                <div class="location-info">
                    <div class="location-item">
                        <strong>Adresse :</strong>
                        <span>{{ $property->address }}</span>
                    </div>
                    <div class="location-item">
                        <strong>Ville :</strong>
                        <span>{{ $property->city }}</span>
                    </div>
                    <div class="location-item">
                        <strong>Quartier :</strong>
                        <span>{{ $property->neighborhood }}</span>
                    </div>
                </div>
            </div>

            <!-- Informations système -->
            <div class="sidebar-card">
                <h4><i class="fas fa-cog"></i> Informations système</h4>
                <div class="system-info">
                    <div class="info-row">
                        <span class="info-label">ID :</span>
                        <span class="info-value">#{{ $property->id }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Slug :</span>
                        <span class="info-value"><code>{{ $property->slug }}</code></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Créé le :</span>
                        <span class="info-value">{{ $property->created_at->format('d/m/Y à H:i') }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Modifié le :</span>
                        <span class="info-value">{{ $property->updated_at->format('d/m/Y à H:i') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Styles modernes pour la page de détails avec thème violet-rouge */
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
    margin-bottom: 1rem;
    color: white;
}

.property-meta {
    display: flex;
    gap: 2rem;
    margin-bottom: 1rem;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    opacity: 0.9;
}

.property-price {
    font-size: 2rem;
    font-weight: 700;
    color: #ffd700;
}

.currency {
    font-size: 1.2rem;
    opacity: 0.8;
}

.property-status-badge .badge {
    padding: 0.5rem 1rem;
    border-radius: 50px;
    font-weight: 600;
}

.badge-featured {
    background: linear-gradient(45deg, #ffd700, #ffa500);
    color: white;
}

.badge-published {
    background: #28a745;
    color: white;
}

.badge-draft {
    background: #6c757d;
    color: white;
}

/* Galerie de médias */
.media-gallery {
    background: white;
    border-radius: 15px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    overflow: hidden;
}

.gallery-header {
    padding: 1.5rem;
    border-bottom: 1px solid #eee;
}

.gallery-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 1rem;
    padding: 1.5rem;
}

.gallery-item {
    position: relative;
    border-radius: 10px;
    overflow: hidden;
    aspect-ratio: 1;
}

.gallery-item.featured {
    grid-column: span 2;
    grid-row: span 2;
}

.gallery-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.gallery-item:hover .gallery-image {
    transform: scale(1.05);
}

.featured-badge {
    position: absolute;
    top: 10px;
    left: 10px;
    background: rgba(255, 215, 0, 0.9);
    color: white;
    padding: 0.5rem;
    border-radius: 50%;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.video-thumbnail {
    position: relative;
    width: 100%;
    height: 100%;
}

.gallery-video {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.play-button {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: rgba(0,0,0,0.7);
    color: white;
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
}

.more-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0,0,0,0.7);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    font-weight: bold;
}

/* Cartes d'information */
.info-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    overflow: hidden;
}

.card-header-modern {
    padding: 1.5rem;
    border-bottom: 1px solid #eee;
    background: linear-gradient(to right, #f8f9fa, #ffffff);
}

.card-body-modern {
    padding: 1.5rem;
}

/* Spécifications de la propriété */
.property-specs {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.spec-item {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.spec-icon {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
}

.spec-content {
    display: flex;
    flex-direction: column;
}

.spec-label {
    color: #6c757d;
    font-size: 0.875rem;
}

.spec-value {
    font-weight: 600;
    color: #2d3748;
}

/* Grille d'équipements */
.amenities-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
    gap: 1rem;
}

.amenity-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 10px;
    transition: all 0.3s ease;
}

.amenity-item.active {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.amenity-item i {
    font-size: 1.2rem;
}

/* Carte agent et statistiques */
.sidebar-card {
    background: white;
    border-radius: 15px;
    padding: 1.5rem;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.agent-card {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.agent-avatar {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    font-weight: bold;
}

.agent-info h4 {
    margin: 0;
    color: #2d3748;
}

.agent-info p {
    margin: 0.25rem 0;
    color: #6c757d;
}

.agent-role {
    font-size: 0.875rem;
    color: #667eea;
    font-weight: 500;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
    margin-top: 1rem;
}

.stat-item {
    text-align: center;
    padding: 1rem;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-radius: 10px;
    position: relative;
}

.stat-number {
    font-size: 1.5rem;
    font-weight: 700;
    color: #2d3748;
}

.stat-label {
    color: #6c757d;
    font-size: 0.875rem;
}

.stat-icon {
    position: absolute;
    top: -10px;
    right: -10px;
    width: 30px;
    height: 30px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.875rem;
}

/* Boutons modernes */
.btn-modern {
    padding: 0.75rem 1.5rem;
    border-radius: 10px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}

/* Informations système */
.system-info {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.info-row {
    display: flex;
    justify-content: space-between;
    padding: 0.5rem 0;
    border-bottom: 1px solid #eee;
}

.info-row:last-child {
    border-bottom: none;
}

.info-label {
    color: #6c757d;
}

.info-value {
    font-weight: 500;
    color: #2d3748;
}

.info-value code {
    background: #f8f9fa;
    padding: 0.25rem 0.5rem;
    border-radius: 4px;
    font-size: 0.875rem;
}
</style>
@endsection
