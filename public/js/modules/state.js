// ============================================================================
// Global Application State
// ============================================================================
// Manages all application data and UI state

export const state = {
    token: localStorage.getItem('token') || null,
    chauffeurs: [],
    selectedChauffeurId: null,
    chauffeurEditingId: null,
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
};

// ============================================================================
// Toast Notification System
// ============================================================================

const toastContainer = document.createElement('div');
toastContainer.id = 'toast-container';
document.body.appendChild(toastContainer);

export function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    toast.textContent = message;
    toastContainer.appendChild(toast);
    requestAnimationFrame(() => toast.classList.add('visible'));
    setTimeout(() => {
        toast.classList.remove('visible');
        setTimeout(() => toast.remove(), 200);
    }, 2600);
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
