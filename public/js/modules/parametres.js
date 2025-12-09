// ============================================================================
// Parameters (Settings) Management
// ============================================================================
// Handles application settings/parameters like company info

import { ensureAuth } from './auth.js';
import { showToast, extractErrorMessage } from './state.js';

// DOM Elements
const paramForm = document.getElementById('param-form');

export async function loadParametres() {
    ensureAuth();
    const res = await axios.get('/api/parametres');
    if (res.data) {
        paramForm.nom_entreprise.value = res.data.nom_entreprise || '';
        paramForm.lien_archive_facture.value = res.data.lien_archive_facture || '';
    }
}

// ============================================================================
// Event Listeners
// ============================================================================

export function initializeParametresEvents() {
    paramForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        ensureAuth();
        const payload = Object.fromEntries(new FormData(e.target).entries());
        try {
            await axios.put('/api/parametres', payload);
            await loadParametres();
            showToast('Paramètres enregistrés.');
        } catch (err) {
            showToast(extractErrorMessage(err), 'error');
            console.error(err);
        }
    });
}
