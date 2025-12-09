// ============================================================================
// Parameters (Settings) Management
// ============================================================================
// Handles application settings/parameters like company info

import { ensureAuth } from './auth.js';
import { showToast, extractErrorMessage } from './state.js';

// Grab form on demand to avoid null when script loads before DOM is ready
const getParamForm = () => document.getElementById('param-form');

export async function loadParametres() {
    ensureAuth();
    const form = getParamForm();
    if (!form) return;
    const res = await axios.get('/api/parametres');
    if (res.data) {
        form.nom_entreprise.value = res.data.nom_entreprise || '';
        form.lien_archive_facture.value = res.data.lien_archive_facture || '';
    }
}

// ============================================================================
// Event Listeners
// ============================================================================

export function initializeParametresEvents() {
    const form = getParamForm();
    if (!form) return;

    form.addEventListener('submit', async (e) => {
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
