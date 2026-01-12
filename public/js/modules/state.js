// ============================================================================
// Global Application State
// ============================================================================
// Manages all application data and UI state

export const state = {
    token: localStorage.getItem('token') || null,
    chauffeurs: [],
    selectedChauffeurId: null,
    chauffeurEditingId: null,
    chauffeurSearch: '',
    chauffeurStatutFilter: 'all',
    chauffeurComportementFilter: 'all',
    vehicules: [],
    selectedVehiculeId: null,
    vehiculeEditingId: null,
    users: [],
    userEditingId: null,
    userSearch: '',
    documents: {
        assurance: [],
        vignette: [],
        controle: [],
        entretien: [],
        reparation: [],
        bon_essence: [],
    },
    documentCurrentType: 'assurance',
    documentEditingId: null,
    sinistres: [],
    selectedSinistreId: null,
    sinistreEditingId: null,
    sinistreCurrentTab: 'tableau',
    assuranceEditingId: null,
    reparationEditingId: null,
    sinistreStats: {
        par_periode: [],
        cout_par_vehicule: [],
        classement_chauffeurs: [],
        taux_prise_en_charge_moyen: 0,
        vehicules_plus_sinistres: [],
    },
};

// ============================================================================
// Toast Notification System (Enhanced)
// ============================================================================

const toastContainer = document.createElement('div');
toastContainer.id = 'toast-container';
toastContainer.className = 'toast-container';
document.body.appendChild(toastContainer);

/**
 * Show a styled toast notification
 * @param {string} message - Main message to display
 * @param {string} type - Type: 'success', 'error', 'warning', 'info'
 * @param {object} options - Optional: { title, duration, closable }
 */
export function showToast(message, type = 'success', options = {}) {
    const {
        title = getDefaultTitle(type),
        duration = 4000,
        closable = true
    } = options;
    
    const toast = document.createElement('div');
    toast.className = `toast ${type === 'error' ? 'danger' : type}`;
    
    toast.innerHTML = `
        <div class="toast-icon">
            ${getToastIcon(type)}
        </div>
        <div class="toast-content">
            <div class="toast-title">${title}</div>
            <div class="toast-message">${message}</div>
        </div>
        ${closable ? `
        <button class="toast-close" type="button" aria-label="Fermer">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M18 6L6 18M6 6l12 12"/>
            </svg>
        </button>
        ` : ''}
    `;
    
    // Add close functionality
    if (closable) {
        const closeBtn = toast.querySelector('.toast-close');
        closeBtn?.addEventListener('click', () => removeToast(toast));
    }
    
    toastContainer.appendChild(toast);
    
    // Trigger animation
    requestAnimationFrame(() => {
        toast.classList.add('visible');
    });
    
    // Auto remove after duration
    if (duration > 0) {
        setTimeout(() => removeToast(toast), duration);
    }
    
    return toast;
}

function removeToast(toast) {
    toast.classList.add('hiding');
    toast.classList.remove('visible');
    setTimeout(() => toast.remove(), 300);
}

function getDefaultTitle(type) {
    switch (type) {
        case 'success': return 'Succès';
        case 'error': return 'Erreur';
        case 'warning': return 'Attention';
        case 'info': return 'Information';
        default: return 'Notification';
    }
}

function getToastIcon(type) {
    switch (type) {
        case 'success':
            return `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                <polyline points="22 4 12 14.01 9 11.01"/>
            </svg>`;
        case 'error':
            return `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"/>
                <line x1="15" y1="9" x2="9" y2="15"/>
                <line x1="9" y1="9" x2="15" y2="15"/>
            </svg>`;
        case 'warning':
            return `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                <line x1="12" y1="9" x2="12" y2="13"/>
                <line x1="12" y1="17" x2="12.01" y2="17"/>
            </svg>`;
        case 'info':
            return `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"/>
                <line x1="12" y1="16" x2="12" y2="12"/>
                <line x1="12" y1="8" x2="12.01" y2="8"/>
            </svg>`;
        default:
            return `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"/>
            </svg>`;
    }
}

export function extractErrorMessage(err) {
    if (err?.response?.data?.message) return err.response.data.message;
    const errors = err?.response?.data?.errors;
    if (errors && typeof errors === 'object') {
        const first = Object.values(errors).flat()[0];
        if (first) return first;
    }
    return 'Une erreur est survenue.';
}

// ============================================================================
// Confirmation Dialog Component
// ============================================================================

/**
 * Show a styled confirmation dialog
 * @param {object} options - Configuration options
 * @returns {Promise<boolean>} - Resolves to true if confirmed, false otherwise
 */
export function showConfirm(options = {}) {
    const {
        title = 'Confirmation',
        message = 'Êtes-vous sûr de vouloir continuer ?',
        confirmText = 'Confirmer',
        cancelText = 'Annuler',
        type = 'warning', // 'warning', 'danger', 'info'
        icon = null
    } = options;
    
    return new Promise((resolve) => {
        // Create overlay
        const overlay = document.createElement('div');
        overlay.className = 'confirm-overlay';
        
        // Create dialog
        const dialog = document.createElement('div');
        dialog.className = `confirm-dialog confirm-${type}`;
        
        dialog.innerHTML = `
            <div class="confirm-icon">
                ${icon || getConfirmIcon(type)}
            </div>
            <div class="confirm-content">
                <h3 class="confirm-title">${title}</h3>
                <p class="confirm-message">${message}</p>
            </div>
            <div class="confirm-actions">
                <button type="button" class="confirm-btn confirm-cancel">${cancelText}</button>
                <button type="button" class="confirm-btn confirm-ok ${type}">${confirmText}</button>
            </div>
        `;
        
        overlay.appendChild(dialog);
        document.body.appendChild(overlay);
        
        // Animate in
        requestAnimationFrame(() => {
            overlay.classList.add('visible');
            dialog.classList.add('visible');
        });
        
        // Handle clicks
        const closeDialog = (result) => {
            overlay.classList.remove('visible');
            dialog.classList.remove('visible');
            setTimeout(() => {
                overlay.remove();
                resolve(result);
            }, 200);
        };
        
        dialog.querySelector('.confirm-cancel').addEventListener('click', () => closeDialog(false));
        dialog.querySelector('.confirm-ok').addEventListener('click', () => closeDialog(true));
        overlay.addEventListener('click', (e) => {
            if (e.target === overlay) closeDialog(false);
        });
        
        // Handle escape key
        const handleEscape = (e) => {
            if (e.key === 'Escape') {
                document.removeEventListener('keydown', handleEscape);
                closeDialog(false);
            }
        };
        document.addEventListener('keydown', handleEscape);
        
        // Focus confirm button
        dialog.querySelector('.confirm-ok').focus();
    });
}

function getConfirmIcon(type) {
    switch (type) {
        case 'danger':
            return `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"/>
                <line x1="15" y1="9" x2="9" y2="15"/>
                <line x1="9" y1="9" x2="15" y2="15"/>
            </svg>`;
        case 'warning':
            return `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/>
                <line x1="12" y1="9" x2="12" y2="13"/>
                <line x1="12" y1="17" x2="12.01" y2="17"/>
            </svg>`;
        case 'info':
            return `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"/>
                <line x1="12" y1="16" x2="12" y2="12"/>
                <line x1="12" y1="8" x2="12.01" y2="8"/>
            </svg>`;
        default:
            return `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"/>
                <path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/>
                <line x1="12" y1="17" x2="12.01" y2="17"/>
            </svg>`;
    }
}
