// ============================================================================
// Chauffeurs (Drivers) Management
// ============================================================================
// Handles driver CRUD operations, modal interactions, and rendering

import { state, showToast, showConfirm, extractErrorMessage } from './state.js';
import { ensureAuth } from './auth.js';
import { formatComportement, formatComportementBadge, formatDate, formatMention, formatMentionStars, formatStatut } from './utils.js';

// DOM Elements
export const chauffeurFormTitle = document.getElementById('chauffeur-form-title');
export const chauffeurFormSubmit = document.getElementById('chauffeur-form-submit');
export const chauffeurForm = document.getElementById('chauffeur-form');
export const chauffeurTableBody = document.getElementById('chauffeur-rows');
export const chauffeurDetailName = document.getElementById('chauffeur-detail-name');
export const chauffeurDetailStatut = document.getElementById('chauffeur-detail-statut');
export const detailMatricule = document.getElementById('detail-matricule');
export const detailTelephone = document.getElementById('detail-telephone');
export const detailAdresse = document.getElementById('detail-adresse');
export const detailDateNaissance = document.getElementById('detail-date-naissance');
export const detailDateRecrutement = document.getElementById('detail-date-recrutement');
export const detailNumeroPermis = document.getElementById('detail-numero-permis');
export const detailDatePermis = document.getElementById('detail-date-permis');
export const detailLieuPermis = document.getElementById('detail-lieu-permis');
export const detailMention = document.getElementById('detail-mention');
export const detailComportement = document.getElementById('detail-comportement');
export const chauffeurModal = document.getElementById('chauffeur-modal');
export const chauffeurDetailModal = document.getElementById('chauffeur-detail-modal');
export const openChauffeurModalBtn = document.getElementById('open-chauffeur-modal');
export const closeChauffeurModalBtn = document.getElementById('close-chauffeur-modal');
export const closeChauffeurDetailModalBtn = document.getElementById('close-chauffeur-detail-modal');
export const chauffeurSearchInput = document.getElementById('chauffeur-search');
export const chauffeurStatutFilter = document.getElementById('chauffeur-statut-filter');
export const chauffeurComportementFilter = document.getElementById('chauffeur-comportement-filter');

export function setChauffeurFormMode(mode, chauffeur = null) {
    if (mode === 'edit' && chauffeur) {
        state.chauffeurEditingId = chauffeur.id;
        chauffeurFormTitle.textContent = 'Modifier le chauffeur';
        chauffeurFormSubmit.textContent = 'Mettre à jour';
        chauffeurForm.matricule.value = chauffeur.matricule || '';
        chauffeurForm.nom.value = chauffeur.nom || '';
        chauffeurForm.prenom.value = chauffeur.prenom || '';
        chauffeurForm.telephone.value = chauffeur.telephone || '';
        chauffeurForm.adresse.value = chauffeur.adresse || '';
        chauffeurForm.date_naissance.value = chauffeur.date_naissance || '';
        chauffeurForm.date_recrutement.value = chauffeur.date_recrutement || '';
        chauffeurForm.numero_permis.value = chauffeur.numero_permis || '';
        chauffeurForm.date_permis.value = chauffeur.date_permis || '';
        chauffeurForm.lieu_permis.value = chauffeur.lieu_permis || '';
        chauffeurForm.statut.value = chauffeur.statut || 'contractuel';
        chauffeurForm.mention.value = chauffeur.mention || 'bon';
        chauffeurForm.comportement.value = chauffeur.comportement || 'satisfaisant';
    } else {
        state.chauffeurEditingId = null;
        chauffeurForm.reset();
        chauffeurFormTitle.textContent = 'Ajouter un chauffeur';
        chauffeurFormSubmit.textContent = 'Enregistrer';
        chauffeurForm.mention.value = 'bon';
        chauffeurForm.comportement.value = 'satisfaisant';
    }
}

export function openChauffeurModal(mode, chauffeur = null) {
    setChauffeurFormMode(mode, chauffeur);
    chauffeurModal.classList.remove('hidden');
}

export function closeChauffeurModal() {
    chauffeurModal.classList.add('hidden');
    setChauffeurFormMode('create');
}

function filteredChauffeurs() {
    const search = (state.chauffeurSearch || '').trim().toLowerCase();
    const statut = state.chauffeurStatutFilter || 'all';
    const comportement = state.chauffeurComportementFilter || 'all';
    return state.chauffeurs.filter(ch => {
        const matchesSearch = !search || [ch.matricule, ch.nom, ch.prenom]
            .filter(Boolean)
            .some(value => String(value).toLowerCase().includes(search));
        const matchesStatut = statut === 'all' || ch.statut === statut;
        const matchesComportement = comportement === 'all' || ch.comportement === comportement;
        return matchesSearch && matchesStatut && matchesComportement;
    });
}

export function renderChauffeurRows() {
    const chauffeursToRender = filteredChauffeurs();
    const rows = chauffeursToRender.map(ch => `
        <tr data-id="${ch.id}">
            <td><span class="matricule-badge">${ch.matricule}</span></td>
            <td>
                <div class="driver-name">
                    <span class="driver-avatar">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                            <circle cx="12" cy="7" r="4"/>
                        </svg>
                    </span>
                    ${ch.nom} ${ch.prenom}
                </div>
            </td>
            <td><span class="contact-cell">${ch.telephone || '-'}</span></td>
            <td><span class="badge ${ch.statut}">${formatStatut(ch.statut)}</span></td>
            <td>${formatMentionStars(ch.mention)}</td>
            <td>${formatComportementBadge(ch.comportement)}</td>
            <td class="row-actions">
                <button class="action-btn view" data-action="view" type="button" title="Voir">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                        <circle cx="12" cy="12" r="3"/>
                    </svg>
                </button>
                <button class="action-btn edit" data-action="edit" type="button" title="Modifier">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                    </svg>
                </button>
                <button class="action-btn delete" data-action="delete" type="button" title="Supprimer">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M3 6h18"/>
                        <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/>
                        <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/>
                        <line x1="10" x2="10" y1="11" y2="17"/>
                        <line x1="14" x2="14" y1="11" y2="17"/>
                    </svg>
                </button>
            </td>
        </tr>
    `).join('');
    chauffeurTableBody.innerHTML = rows;
    
    // Update count display
    const countEl = document.querySelector('#chauffeurs-count .count');
    if (countEl) {
        countEl.textContent = chauffeursToRender.length;
    }
}

export function clearChauffeurDetail() {
    closeDetailModal();
}

// Removed toggle function - no longer needed with modal

export function renderChauffeurDetail(ch) {
    chauffeurDetailName.textContent = `${ch.nom} ${ch.prenom}`;
    chauffeurDetailStatut.textContent = formatStatut(ch.statut);
    chauffeurDetailStatut.className = `pill ${ch.statut}`;
    detailMatricule.textContent = ch.matricule || '-';
    detailTelephone.textContent = ch.telephone || '-';
    detailAdresse.textContent = ch.adresse || '-';
    detailDateNaissance.textContent = formatDate(ch.date_naissance);
    detailDateRecrutement.textContent = formatDate(ch.date_recrutement);
    detailNumeroPermis.textContent = ch.numero_permis || '-';
    detailDatePermis.textContent = formatDate(ch.date_permis);
    detailLieuPermis.textContent = ch.lieu_permis || '-';
    detailMention.textContent = formatMention(ch.mention);
    detailComportement.textContent = formatComportement(ch.comportement);
    
    // Open the detail modal
    openDetailModal();
    
    // Highlight selected row
    document.querySelectorAll('#chauffeur-rows tr.selected').forEach(row => {
        row.classList.remove('selected');
    });
    const selectedRow = document.querySelector(`#chauffeur-rows tr[data-id="${ch.id}"]`);
    if (selectedRow) selectedRow.classList.add('selected');
}

function openDetailModal() {
    if (chauffeurDetailModal) {
        chauffeurDetailModal.classList.remove('hidden');
    }
}

function closeDetailModal() {
    if (chauffeurDetailModal) {
        chauffeurDetailModal.classList.add('hidden');
    }
    // Clear row selection
    document.querySelectorAll('#chauffeur-rows tr.selected').forEach(row => {
        row.classList.remove('selected');
    });
    state.selectedChauffeurId = null;
}

async function fetchChauffeur(id) {
    ensureAuth();
    const res = await axios.get(`/api/chauffeurs/${id}`);
    return res.data;
}

export async function showChauffeurDetail(id) {
    const chauffeur = await fetchChauffeur(id);
    state.selectedChauffeurId = chauffeur.id;
    renderChauffeurDetail(chauffeur);
}

export async function handleDeleteChauffeur(id) {
    ensureAuth();
    const chauffeur = state.chauffeurs.find(ch => ch.id === Number(id));
    const confirmed = await showConfirm({
        title: 'Supprimer le chauffeur ?',
        message: `Êtes-vous sûr de vouloir supprimer le chauffeur <strong>${chauffeur?.nom || ''} ${chauffeur?.prenom || ''}</strong> ? Cette action est irréversible.`,
        confirmText: 'Supprimer',
        cancelText: 'Annuler',
        type: 'danger'
    });
    if (!confirmed) return;
    try {
        await axios.delete(`/api/chauffeurs/${id}`);
        clearChauffeurDetail();
        await loadChauffeurs();
        showToast('Chauffeur supprimé avec succès.', 'success', { title: 'Suppression effectuée' });
    } catch (err) {
        showToast(extractErrorMessage(err), 'error', { title: 'Échec de la suppression' });
    }
}

export async function loadChauffeurs() {
    ensureAuth();
    const res = await axios.get('/api/chauffeurs');
    state.chauffeurs = res.data.data || [];
    renderChauffeurRows();
    if (state.selectedChauffeurId) {
        const stillExists = state.chauffeurs.find(ch => ch.id === state.selectedChauffeurId);
        if (stillExists) {
            showChauffeurDetail(state.selectedChauffeurId);
        } else {
            clearChauffeurDetail();
        }
    }
    document.dispatchEvent(new CustomEvent('data:chauffeurs:updated'));
}

// ============================================================================
// Event Listeners
// ============================================================================

export function initializeChauffeurEvents() {
    // Move modal to body to prevent clipping issues
    if (chauffeurModal && chauffeurModal.parentElement !== document.body) {
        document.body.appendChild(chauffeurModal);
    }
    
    chauffeurForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        ensureAuth();
        const payload = Object.fromEntries(new FormData(e.target).entries());
        const isEdit = Boolean(state.chauffeurEditingId);
        try {
            if (isEdit) {
                await axios.put(`/api/chauffeurs/${state.chauffeurEditingId}`, payload);
            } else {
                await axios.post('/api/chauffeurs', payload);
            }
            closeChauffeurModal();
            await loadChauffeurs();
            showToast(isEdit ? 'Chauffeur mis à jour avec succès.' : 'Chauffeur créé avec succès.', 'success', { title: isEdit ? 'Modification effectuée' : 'Création effectuée' });
        } catch (err) {
            showToast(extractErrorMessage(err), 'error', { title: 'Échec de l\'enregistrement' });
        }
    });

    chauffeurTableBody.addEventListener('click', async (e) => {
        const row = e.target.closest('tr[data-id]');
        if (!row) return;
        const id = row.dataset.id;
        
        // Handle action buttons
        const actionBtn = e.target.closest('[data-action]');
        if (actionBtn) {
            e.stopPropagation();
            const action = actionBtn.dataset.action;
            if (action === 'view') {
                showChauffeurDetail(id);
                return;
            }
            if (action === 'edit') {
                const chauffeur = await fetchChauffeur(id);
                setChauffeurFormMode('edit', chauffeur);
                openChauffeurModal('edit', chauffeur);
                return;
            }
            if (action === 'delete') {
                await handleDeleteChauffeur(id);
                return;
            }
        }
    });

    openChauffeurModalBtn.addEventListener('click', () => openChauffeurModal('create'));
    closeChauffeurModalBtn.addEventListener('click', () => closeChauffeurModal());
    
    // Detail modal close button
    if (closeChauffeurDetailModalBtn) {
        closeChauffeurDetailModalBtn.addEventListener('click', () => closeDetailModal());
    }
    
    // Detail modal backdrop click
    if (chauffeurDetailModal) {
        chauffeurDetailModal.addEventListener('click', (e) => {
            if (e.target.dataset.close === 'chauffeur-detail-modal') {
                closeDetailModal();
            }
        });
        // Move detail modal to body
        if (chauffeurDetailModal.parentElement !== document.body) {
            document.body.appendChild(chauffeurDetailModal);
        }
    }
    
    // Cancel button
    const cancelBtn = document.getElementById('cancel-chauffeur-form');
    if (cancelBtn) {
        cancelBtn.addEventListener('click', () => closeChauffeurModal());
    }
    
    chauffeurModal.addEventListener('click', (e) => {
        if (e.target.dataset.close === 'chauffeur-modal') {
            closeChauffeurModal();
        }
    });

    if (chauffeurSearchInput) {
        chauffeurSearchInput.addEventListener('input', (e) => {
            state.chauffeurSearch = e.target.value;
            renderChauffeurRows();
        });
    }

    if (chauffeurStatutFilter) {
        chauffeurStatutFilter.addEventListener('change', (e) => {
            state.chauffeurStatutFilter = e.target.value || 'all';
            renderChauffeurRows();
        });
    }

    if (chauffeurComportementFilter) {
        chauffeurComportementFilter.addEventListener('change', (e) => {
            state.chauffeurComportementFilter = e.target.value || 'all';
            renderChauffeurRows();
        });
    }
}
