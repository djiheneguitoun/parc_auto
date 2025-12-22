// ============================================================================
// Chauffeurs (Drivers) Management
// ============================================================================
// Handles driver CRUD operations, modal interactions, and rendering

import { state, showToast } from './state.js';
import { ensureAuth } from './auth.js';
import { formatComportement, formatDate, formatMention, formatMentionStars, formatStatut } from './utils.js';

// DOM Elements
export const chauffeurFormTitle = document.getElementById('chauffeur-form-title');
export const chauffeurFormSubmit = document.getElementById('chauffeur-form-submit');
export const chauffeurForm = document.getElementById('chauffeur-form');
export const chauffeurTableBody = document.getElementById('chauffeur-rows');
export const chauffeurDetailEmpty = document.getElementById('chauffeur-detail-empty');
export const chauffeurDetail = document.getElementById('chauffeur-detail');
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
export const openChauffeurModalBtn = document.getElementById('open-chauffeur-modal');
export const closeChauffeurModalBtn = document.getElementById('close-chauffeur-modal');

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

export function renderChauffeurRows() {
    const rows = state.chauffeurs.map(ch => `
        <tr data-id="${ch.id}">
            <td>${ch.matricule}</td>
            <td>${ch.nom} ${ch.prenom}</td>
            <td>${ch.telephone || ''}</td>
            <td><span class="badge">${formatStatut(ch.statut)}</span></td>
            <td>${formatMentionStars(ch.mention)}</td>
            <td>${formatComportement(ch.comportement)}</td>
            <td class="row-actions">
                <button class="btn secondary xs" data-action="edit" type="button">Modifier</button>
                <button class="btn danger xs" data-action="delete" type="button">Supprimer</button>
            </td>
        </tr>
    `).join('');
    chauffeurTableBody.innerHTML = rows;
}

export function clearChauffeurDetail() {
    chauffeurDetail.style.display = 'none';
    chauffeurDetailEmpty.style.display = 'block';
    state.selectedChauffeurId = null;
}

export function renderChauffeurDetail(ch) {
    chauffeurDetailName.textContent = `${ch.nom} ${ch.prenom}`;
    chauffeurDetailStatut.textContent = formatStatut(ch.statut);
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
    chauffeurDetailEmpty.style.display = 'none';
    chauffeurDetail.style.display = 'block';
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
    const confirmed = window.confirm('Supprimer ce chauffeur ?');
    if (!confirmed) return;
    await axios.delete(`/api/chauffeurs/${id}`);
    clearChauffeurDetail();
    await loadChauffeurs();
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
}

// ============================================================================
// Event Listeners
// ============================================================================

export function initializeChauffeurEvents() {
    chauffeurForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        ensureAuth();
        const payload = Object.fromEntries(new FormData(e.target).entries());
        if (state.chauffeurEditingId) {
            await axios.put(`/api/chauffeurs/${state.chauffeurEditingId}`, payload);
        } else {
            await axios.post('/api/chauffeurs', payload);
        }
        closeChauffeurModal();
        await loadChauffeurs();
    });

    chauffeurTableBody.addEventListener('click', async (e) => {
        const row = e.target.closest('tr[data-id]');
        if (!row) return;
        const id = row.dataset.id;
        if (e.target.matches('[data-action="edit"]')) {
            e.stopPropagation();
            const chauffeur = await fetchChauffeur(id);
            setChauffeurFormMode('edit', chauffeur);
            openChauffeurModal('edit', chauffeur);
            return;
        }
        if (e.target.matches('[data-action="delete"]')) {
            e.stopPropagation();
            await handleDeleteChauffeur(id);
            return;
        }
        showChauffeurDetail(id);
    });

    openChauffeurModalBtn.addEventListener('click', () => openChauffeurModal('create'));
    closeChauffeurModalBtn.addEventListener('click', () => closeChauffeurModal());
    chauffeurModal.addEventListener('click', (e) => {
        if (e.target.dataset.close === 'chauffeur-modal') {
            closeChauffeurModal();
        }
    });
}
