document.addEventListener('DOMContentLoaded', function() {
    // Gestionnaire pour les boutons de favoris
    document.querySelectorAll('.favorite-btn').forEach(button => {
        button.addEventListener('click', async function(e) {
            e.preventDefault();
            
            // Vérifier si l'utilisateur est connecté
            const isAuthenticated = document.querySelector('meta[name="user-authenticated"]').getAttribute('content') === 'true';
            
            if (!isAuthenticated) {
                const loginUrl = document.querySelector('meta[name="login-url"]').getAttribute('content');
                window.location.href = loginUrl;
                return;
            }
            
            const propertyId = this.getAttribute('data-property-id');
            const icon = this.querySelector('i');
            const originalClass = icon.className;
            
            // Animation de chargement
            icon.className = 'fas fa-spinner fa-spin';
            this.disabled = true;
            
            try {
                const response = await fetch(`/proprietes/${propertyId}/favori`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                });
                
                const data = await response.json();
                
                if (response.ok) {
                    // Mettre à jour l'icône selon l'état
                    if (data.favorited) {
                        icon.className = 'fas fa-heart text-red-500';
                        this.classList.add('favorited');
                        showNotification('Ajouté aux favoris', 'success');
                    } else {
                        icon.className = 'far fa-heart';
                        this.classList.remove('favorited');
                        showNotification('Retiré des favoris', 'info');
                    }
                } else {
                    throw new Error(data.message || 'Erreur lors de la mise à jour des favoris');
                }
            } catch (error) {
                console.error('Erreur:', error);
                icon.className = originalClass;
                showNotification('Erreur lors de la mise à jour des favoris', 'error');
            } finally {
                this.disabled = false;
            }
        });
    });
    
    // Fonction pour afficher les notifications
    function showNotification(message, type = 'info') {
        // Créer l'élément de notification
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg shadow-lg transform transition-all duration-300 translate-x-full`;
        
        // Définir les couleurs selon le type
        const colors = {
            success: 'bg-green-500 text-white',
            error: 'bg-red-500 text-white',
            info: 'bg-blue-500 text-white',
            warning: 'bg-yellow-500 text-black'
        };
        
        notification.className += ` ${colors[type] || colors.info}`;
        notification.innerHTML = `
            <div class="flex items-center">
                <span>${message}</span>
                <button class="ml-4 text-white hover:text-gray-200" onclick="this.parentElement.parentElement.remove()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        `;
        
        document.body.appendChild(notification);
        
        // Animation d'entrée
        setTimeout(() => {
            notification.classList.remove('translate-x-full');
        }, 100);
        
        // Suppression automatique après 5 secondes
        setTimeout(() => {
            notification.classList.add('translate-x-full');
            setTimeout(() => {
                if (notification.parentElement) {
                    notification.remove();
                }
            }, 300);
        }, 5000);
    }
    
    // Gestionnaire pour la page des favoris
    if (window.location.pathname.includes('/favoris')) {
        // Bouton pour vider tous les favoris
        const clearAllBtn = document.getElementById('clear-all-favorites');
        if (clearAllBtn) {
            clearAllBtn.addEventListener('click', async function(e) {
                e.preventDefault();
                
                if (!confirm('Êtes-vous sûr de vouloir supprimer tous vos favoris ?')) {
                    return;
                }
                
                try {
                    const response = await fetch('/favoris', {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        }
                    });
                    
                    if (response.ok) {
                        location.reload();
                    } else {
                        throw new Error('Erreur lors de la suppression');
                    }
                } catch (error) {
                    console.error('Erreur:', error);
                    showNotification('Erreur lors de la suppression des favoris', 'error');
                }
            });
        }
        
        // Boutons pour supprimer un favori individuel
        document.querySelectorAll('.remove-favorite-btn').forEach(button => {
            button.addEventListener('click', async function(e) {
                e.preventDefault();
                
                const propertyId = this.getAttribute('data-property-id');
                const propertyCard = this.closest('.property-card');
                
                try {
                    const response = await fetch(`/favoris/${propertyId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Accept': 'application/json'
                        }
                    });
                    
                    if (response.ok) {
                        // Animation de suppression
                        propertyCard.style.transform = 'scale(0.95)';
                        propertyCard.style.opacity = '0';
                        
                        setTimeout(() => {
                            propertyCard.remove();
                            
                            // Vérifier s'il reste des favoris
                            const remainingCards = document.querySelectorAll('.property-card');
                            if (remainingCards.length === 0) {
                                location.reload();
                            }
                        }, 300);
                        
                        showNotification('Favori supprimé', 'success');
                    } else {
                        throw new Error('Erreur lors de la suppression');
                    }
                } catch (error) {
                    console.error('Erreur:', error);
                    showNotification('Erreur lors de la suppression du favori', 'error');
                }
            });
        });
    }
    
    // Charger l'état des favoris au chargement de la page
    loadFavoritesState();
    
    async function loadFavoritesState() {
        const isAuthenticated = document.querySelector('meta[name="user-authenticated"]').getAttribute('content') === 'true';
        
        if (!isAuthenticated) return;
        
        try {
            const response = await fetch('/api/favoris', {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });
            
            if (response.ok) {
                const favorites = await response.json();
                
                // Mettre à jour l'état des boutons favoris
                document.querySelectorAll('.favorite-btn').forEach(button => {
                    const propertyId = parseInt(button.getAttribute('data-property-id'));
                    const icon = button.querySelector('i');
                    
                    if (favorites.includes(propertyId)) {
                        icon.className = 'fas fa-heart text-red-500';
                        button.classList.add('favorited');
                    }
                });
            }
        } catch (error) {
            console.error('Erreur lors du chargement des favoris:', error);
        }
    }
});

// Fonction utilitaire pour les animations
function animateElement(element, animation) {
    element.classList.add('animate-' + animation);
    element.addEventListener('animationend', function() {
        element.classList.remove('animate-' + animation);
    }, { once: true });
}

// Export pour utilisation dans d'autres modules
window.FavoritesManager = {
    showNotification,
    animateElement,
    loadFavoritesState
};
