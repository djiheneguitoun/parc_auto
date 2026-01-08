// ============================================================================
// Vehicules (Vehicles) Management
// ============================================================================
// Handles vehicle CRUD operations, modal interactions, rendering, and images

import { state, showToast, showConfirm, extractErrorMessage } from './state.js';
import { ensureAuth } from './auth.js';
import { formatDate, formatVehiculeStatut, formatEtatFonctionnel, formatCategorie, formatOptionVehicule, formatEnergie, formatBoite, formatLeasing, formatUtilisation, formatCurrency, resolveVehiculeImageSrc } from './utils.js';

// DOM Elements
export const vehiculeTableBody = document.getElementById('vehicule-rows');
export const vehiculesCount = document.getElementById('vehicules-count');
export const vehiculeDetailModal = document.getElementById('vehicule-detail-modal');
export const vehiculeDetailTitle = document.getElementById('vehicule-detail-title');
export const vehiculeDetailEtat = document.getElementById('vehicule-detail-etat');
export const vehiculeDetailStatut = document.getElementById('vehicule-detail-statut');
export const vehiculeDetailCategory = document.getElementById('vehicule-detail-category');
export const detailEtat = document.getElementById('detail-etat');
export const detailStatut = document.getElementById('detail-statut');
export const detailCode = document.getElementById('detail-code');
export const detailNumero = document.getElementById('detail-numero');
export const detailModele = document.getElementById('detail-modele');
export const detailAnnee = document.getElementById('detail-annee');
export const detailCouleur = document.getElementById('detail-couleur');
export const detailChassis = document.getElementById('detail-chassis');
export const detailEnergie = document.getElementById('detail-energie');
export const detailBoite = document.getElementById('detail-boite');
export const detailOption = document.getElementById('detail-option');
export const detailUtilisation = document.getElementById('detail-utilisation');
export const detailLeasing = document.getElementById('detail-leasing');
export const detailAffectation = document.getElementById('detail-affectation');
export const detailValeur = document.getElementById('detail-valeur');
export const detailDateAcquisition = document.getElementById('detail-date-acquisition');
export const detailDateCreation = document.getElementById('detail-date-creation');
export const detailDescription = document.getElementById('detail-description');
export const detailChauffeurNom = document.getElementById('detail-chauffeur-nom');
export const detailChauffeurTelephone = document.getElementById('detail-chauffeur-telephone');
export const detailChauffeurStatut = document.getElementById('detail-chauffeur-statut');
export const vehiculeDocuments = document.getElementById('vehicule-documents');
export const vehiculeImages = document.getElementById('vehicule-images');
export const vehiculeModal = document.getElementById('vehicule-modal');
export const vehiculeForm = document.getElementById('vehicule-form');
export const vehiculeFormTitle = document.getElementById('vehicule-form-title');
export const vehiculeFormSubmit = document.getElementById('vehicule-form-submit');
export const openVehiculeModalBtn = document.getElementById('open-vehicule-modal');
export const closeVehiculeModalBtn = document.getElementById('close-vehicule-modal');
export const closeVehiculeDetailModalBtn = document.getElementById('close-vehicule-detail-modal');
export const cancelVehiculeFormBtn = document.getElementById('cancel-vehicule-form');
export const vehiculeImagesInput = document.getElementById('vehicule-images-input');
export const imageViewerModal = document.getElementById('image-viewer-modal');
export const imageViewerImg = document.getElementById('image-viewer-img');
export const imageViewerCaption = document.getElementById('image-viewer-caption');
export const closeImageViewerBtn = document.getElementById('close-image-viewer');

const ETAT_STATUT_MAP = {
    disponible: ['disponible'],
    utilisation: ['en_service', 'reserve'],
    technique: ['en_maintenance', 'en_panne', 'en_reparation'],
    reglementaire: ['non_conforme', 'interdit'],
    incident: ['sinistre', 'en_expertise'],
    fin_de_vie: ['reforme', 'sorti_du_parc'],
};

// Image viewer zoom management
let imageViewerZoom = 1;

async function syncVehiculeChauffeurSelect(selectedId = '') {
    const select = vehiculeForm?.chauffeur_id;
    if (!select) return;

    if (!Array.isArray(state.chauffeurs) || !state.chauffeurs.length) {
        try {
            ensureAuth();
            const res = await axios.get('/api/chauffeurs');
            const list = res.data?.data ?? res.data ?? [];
            state.chauffeurs = Array.isArray(list) ? list : [];
        } catch (err) {
            console.error(err);
            state.chauffeurs = [];
        }
    }

    const selectedValue = selectedId ? String(selectedId) : '';
    select.innerHTML = '';
    const placeholder = document.createElement('option');
    placeholder.value = '';
    placeholder.textContent = '- Choisir un chauffeur -';
    select.appendChild(placeholder);

    for (const ch of state.chauffeurs) {
        const opt = document.createElement('option');
        opt.value = String(ch.id);
        opt.textContent = `${ch.nom || ''} ${ch.prenom || ''}`.trim();
        select.appendChild(opt);
    }

    select.value = selectedValue;
}

export function openImageViewer(src, caption = '') {
    imageViewerZoom = 1;
    imageViewerImg.style.transform = 'scale(1)';
    imageViewerImg.src = src;
    imageViewerCaption.textContent = caption;
    imageViewerModal.classList.remove('hidden');
}

export function closeImageViewer() {
    imageViewerModal.classList.add('hidden');
}

export function adjustImageViewerZoom(delta) {
    imageViewerZoom = Math.min(3, Math.max(1, imageViewerZoom + delta));
    imageViewerImg.style.transform = `scale(${imageViewerZoom})`;
}

function filterStatutOptions(selectedEtat, selectedStatut = '') {
    const select = vehiculeForm?.statut;
    if (!select) return;

    const allowed = ETAT_STATUT_MAP[selectedEtat] || [];
    Array.from(select.options).forEach((opt) => {
        if (!opt.value) {
            opt.disabled = false;
            opt.hidden = false;
            return;
        }
        const enabled = allowed.length === 0 || allowed.includes(opt.value);
        opt.disabled = !enabled;
        opt.hidden = !enabled;
    });

    if (selectedStatut && allowed.includes(selectedStatut)) {
        select.value = selectedStatut;
        return;
    }

    if (!allowed.includes(select.value)) {
        select.value = allowed[0] ?? '';
    }
}

export function setVehiculeFormMode(mode, vehicule = null) {
    if (vehiculeImagesInput) vehiculeImagesInput.value = '';
    if (mode === 'edit' && vehicule) {
        state.vehiculeEditingId = vehicule.id;
        vehiculeFormTitle.innerHTML = `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9"/><path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z"/></svg> Modifier le véhicule`;
        vehiculeFormSubmit.textContent = 'Mettre à jour';
        vehiculeForm.numero.value = vehicule.numero || '';
        vehiculeForm.code.value = vehicule.code || '';
        vehiculeForm.marque.value = vehicule.marque || '';
        vehiculeForm.modele.value = vehicule.modele || '';
        vehiculeForm.annee.value = vehicule.annee || '';
        vehiculeForm.couleur.value = vehicule.couleur || '';
        vehiculeForm.chassis.value = vehicule.chassis || '';
        syncVehiculeChauffeurSelect(vehicule.chauffeur_id);
        vehiculeForm.date_acquisition.value = vehicule.date_acquisition ? vehicule.date_acquisition.slice(0, 10) : '';
        vehiculeForm.valeur.value = vehicule.valeur || '';
        const etat = vehicule.etat_fonctionnel || '';
        vehiculeForm.etat_fonctionnel.value = etat;
        filterStatutOptions(etat, vehicule.statut || '');
        vehiculeForm.statut.value = vehicule.statut || '';
        vehiculeForm.date_creation.value = vehicule.date_creation ? vehicule.date_creation.slice(0, 10) : '';
        vehiculeForm.categorie.value = vehicule.categorie || '';
        vehiculeForm.option_vehicule.value = vehicule.option_vehicule || '';
        vehiculeForm.energie.value = vehicule.energie || '';
        vehiculeForm.boite.value = vehicule.boite || '';
        vehiculeForm.leasing.value = vehicule.leasing || '';
        vehiculeForm.utilisation.value = vehicule.utilisation || '';
        vehiculeForm.affectation.value = vehicule.affectation || '';
        vehiculeForm.description.value = vehicule.description || '';
    } else {
        state.vehiculeEditingId = null;
        vehiculeForm.reset();
        if (vehiculeImagesInput) vehiculeImagesInput.value = '';
        vehiculeFormTitle.innerHTML = `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9L18.4 8.5c-.3-.5-.8-.9-1.4-1h-5.5c-.6 0-1.1.4-1.4 1L8.1 11.1c-.8.2-1.5 1-1.5 1.9v3c0 .6.4 1 1 1h2"/><circle cx="7" cy="17" r="2"/><path d="M9 17h6"/><circle cx="17" cy="17" r="2"/></svg> Nouveau véhicule`;
        vehiculeFormSubmit.textContent = 'Enregistrer';
        syncVehiculeChauffeurSelect('');
        filterStatutOptions('');
    }
}

export function openVehiculeModal(mode, vehicule = null) {
    setVehiculeFormMode(mode, vehicule);
    vehiculeModal.classList.remove('hidden');
}

export function closeVehiculeModal() {
    vehiculeModal.classList.add('hidden');
    setVehiculeFormMode('create');
}

export function renderVehiculeRows() {
    const rows = state.vehicules.map(v => `
        <tr data-id="${v.id}">
            <td><span class="code-badge">${v.code || '-'}</span></td>
            <td><span class="plate-badge">${v.numero || '-'}</span></td>
            <td>
                <div class="vehicle-name">
                    <span class="vehicle-avatar">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.4 10.6 16 8 16 8h-5s-2.4 2.6-4.5 3.1C5.7 11.3 5 12.1 5 13v3c0 .6.4 1 1 1h2"/>
                            <circle cx="7.5" cy="17" r="2.5"/>
                            <circle cx="16.5" cy="17" r="2.5"/>
                        </svg>
                    </span>
                    ${[v.marque, v.modele].filter(Boolean).join(' ') || '-'}
                </div>
            </td>
            <td><span class="chauffeur-cell ${v.chauffeur ? '' : 'empty'}">${v.chauffeur ? `${v.chauffeur.nom || ''} ${v.chauffeur.prenom || ''}`.trim() : 'Non affecté'}</span></td>
            <td><span class="badge ${v.etat_fonctionnel || 'default'}">${formatEtatFonctionnel(v.etat_fonctionnel)}</span></td>
            <td><span class="badge ${v.statut || 'default'}">${formatVehiculeStatut(v.statut)}</span></td>
            <td>${formatCategorie(v.categorie)}</td>
            <td class="row-actions">
                <button class="action-btn view" data-action="view" type="button" title="Voir">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                        <circle cx="12" cy="12" r="3"/>
                    </svg>
                </button>
                <button class="action-btn edit" data-action="edit" type="button" title="Modifier">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                        <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                    </svg>
                </button>
                <button class="action-btn delete" data-action="delete" type="button" title="Supprimer">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M3 6h18"/>
                        <path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/>
                        <path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/>
                    </svg>
                </button>
            </td>
        </tr>
    `).join('');
    vehiculeTableBody.innerHTML = rows;
    
    // Update count badge
    const countEl = document.querySelector('#vehicules-count .count');
    if (countEl) {
        countEl.textContent = state.vehicules.length;
    }
}

export function openVehiculeDetailModal() {
    vehiculeDetailModal.classList.remove('hidden');
}

export function closeVehiculeDetailModal() {
    vehiculeDetailModal.classList.add('hidden');
}

export function renderVehiculeDetail(v) {
    vehiculeDetailTitle.textContent = `${v.marque || ''} ${v.modele || ''}`.trim() || v.code || 'Véhicule';
    vehiculeDetailEtat.textContent = formatEtatFonctionnel(v.etat_fonctionnel);
    vehiculeDetailEtat.className = `pill pill-${v.etat_fonctionnel || 'default'}`;
    vehiculeDetailStatut.textContent = formatVehiculeStatut(v.statut);
    vehiculeDetailStatut.className = `pill pill-${v.statut || 'default'}`;
    vehiculeDetailCategory.textContent = formatCategorie(v.categorie);
    detailEtat.textContent = formatEtatFonctionnel(v.etat_fonctionnel);
    detailStatut.textContent = formatVehiculeStatut(v.statut);
    detailCode.textContent = v.code || '-';
    detailNumero.textContent = v.numero || '-';
    detailModele.textContent = `${v.marque || '-'} ${v.modele || ''}`.trim();
    detailAnnee.textContent = v.annee || '-';
    detailCouleur.textContent = v.couleur || '-';
    detailChassis.textContent = v.chassis || '-';
    detailEnergie.textContent = formatEnergie(v.energie);
    detailBoite.textContent = formatBoite(v.boite);
    detailOption.textContent = formatOptionVehicule(v.option_vehicule);
    detailUtilisation.textContent = formatUtilisation(v.utilisation);
    detailLeasing.textContent = formatLeasing(v.leasing);
    detailAffectation.textContent = v.affectation || '-';
    detailValeur.textContent = formatCurrency(v.valeur);
    detailDateAcquisition.textContent = formatDate(v.date_acquisition);
    detailDateCreation.textContent = formatDate(v.date_creation);
    detailDescription.textContent = v.description || 'Aucune description.';

    if (v.chauffeur) {
        detailChauffeurNom.textContent = `${v.chauffeur.nom || ''} ${v.chauffeur.prenom || ''}`.trim();
        detailChauffeurTelephone.textContent = v.chauffeur.telephone || '-';
        detailChauffeurStatut.textContent = v.chauffeur.statut === 'contractuel' ? 'Contractuel' : 'Permanent';
    } else {
        detailChauffeurNom.textContent = '-';
        detailChauffeurTelephone.textContent = '-';
        detailChauffeurStatut.textContent = '-';
    }

    if (v.images && v.images.length) {
        vehiculeImages.innerHTML = v.images.map(img => `
            <div class="image-thumb" data-image-src="${img.image_path}" data-caption="${img.legende || 'Photo'}">
                <img src="${resolveVehiculeImageSrc(img.image_path)}" alt="vehicule" />
                <div class="image-caption">${img.legende || 'Photo'}</div>
            </div>
        `).join('');
    } else {
        vehiculeImages.innerHTML = '<div class="muted-small">Aucune image disponible.</div>';
    }
}

async function fetchVehicule(id) {
    ensureAuth();
    const res = await axios.get(`/api/vehicules/${id}`);
    return res.data;
}

export async function showVehiculeDetail(id, focusImages = false) {
    const vehicule = await fetchVehicule(id);
    state.selectedVehiculeId = vehicule.id;
    renderVehiculeDetail(vehicule);
    openVehiculeDetailModal();
    if (focusImages) {
        setTimeout(() => {
            vehiculeImages.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }, 150);
    }
}

export async function handleDeleteVehicule(id) {
    ensureAuth();
    const vehicule = state.vehicules.find(v => v.id === Number(id));
    const confirmed = await showConfirm({
        title: 'Supprimer le véhicule ?',
        message: `Êtes-vous sûr de vouloir supprimer le véhicule <strong>${vehicule?.numero || vehicule?.code || ''}</strong> ? Cette action est irréversible.`,
        confirmText: 'Supprimer',
        cancelText: 'Annuler',
        type: 'danger'
    });
    if (!confirmed) return;
    try {
        await axios.delete(`/api/vehicules/${id}`);
        if (state.selectedVehiculeId === Number(id)) {
            closeVehiculeDetailModal();
            state.selectedVehiculeId = null;
        }
        await loadVehicules();
        showToast('Véhicule supprimé avec succès.', 'success', { title: 'Suppression effectuée' });
    } catch (err) {
        showToast(extractErrorMessage(err), 'error', { title: 'Échec de la suppression' });
    }
}

export async function loadVehicules() {
    ensureAuth();
    const res = await axios.get('/api/vehicules');
    const list = res.data.data || res.data;
    state.vehicules = list;
    renderVehiculeRows();
    document.dispatchEvent(new CustomEvent('data:vehicules:updated'));
}

// ============================================================================
// Event Listeners
// ============================================================================

export function initializeVehiculeEvents() {
    // Move modals to body to prevent clipping issues (same as chauffeurs)
    if (vehiculeModal && vehiculeModal.parentElement !== document.body) {
        document.body.appendChild(vehiculeModal);
    }
    if (vehiculeDetailModal && vehiculeDetailModal.parentElement !== document.body) {
        document.body.appendChild(vehiculeDetailModal);
    }
    if (imageViewerModal && imageViewerModal.parentElement !== document.body) {
        document.body.appendChild(imageViewerModal);
    }

    if (vehiculeForm?.etat_fonctionnel) {
        vehiculeForm.etat_fonctionnel.addEventListener('change', (e) => {
            filterStatutOptions(e.target.value);
        });
    }

    vehiculeForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        ensureAuth();
        const formData = new FormData(e.target);
        const isEdit = Boolean(state.vehiculeEditingId);
        const url = isEdit ? `/api/vehicules/${state.vehiculeEditingId}` : '/api/vehicules';

        if (isEdit) {
            formData.append('_method', 'PUT');
        }

        try {
            const res = await axios.post(url, formData, { headers: { 'Content-Type': 'multipart/form-data' } });

            const vehiculeId = res.data?.id;
            const files = vehiculeImagesInput?.files || [];
            if (vehiculeId && files.length) {
                const uploads = Array.from(files).map(file => {
                    const fd = new FormData();
                    fd.append('vehicule_id', vehiculeId);
                    fd.append('image', file);
                    return axios.post('/api/vehicule-images', fd, { headers: { 'Content-Type': 'multipart/form-data' } });
                });
                await Promise.all(uploads);
            }
            closeVehiculeModal();
            await loadVehicules();
            showToast(isEdit ? 'Véhicule mis à jour avec succès.' : 'Véhicule créé avec succès.', 'success', { title: isEdit ? 'Modification effectuée' : 'Création effectuée' });
        } catch (err) {
            showToast(extractErrorMessage(err), 'error', { title: 'Échec de l\'enregistrement' });
        }
    });

    vehiculeTableBody.addEventListener('click', async (e) => {
        const row = e.target.closest('tr[data-id]');
        if (!row) return;
        const id = row.dataset.id;
        const btn = e.target.closest('[data-action]');
        const action = btn?.dataset.action;
        
        if (action === 'view') {
            e.stopPropagation();
            await showVehiculeDetail(id);
            return;
        }
        if (action === 'edit') {
            e.stopPropagation();
            const vehicule = await fetchVehicule(id);
            setVehiculeFormMode('edit', vehicule);
            openVehiculeModal('edit', vehicule);
            return;
        }
        if (action === 'delete') {
            e.stopPropagation();
            await handleDeleteVehicule(id);
            return;
        }
        // Click on row (not on action button) opens detail
        await showVehiculeDetail(id);
    });

    openVehiculeModalBtn.addEventListener('click', () => openVehiculeModal('create'));
    closeVehiculeModalBtn.addEventListener('click', () => closeVehiculeModal());
    if (cancelVehiculeFormBtn) {
        cancelVehiculeFormBtn.addEventListener('click', () => closeVehiculeModal());
    }
    vehiculeModal.addEventListener('click', (e) => {
        if (e.target.dataset.close === 'vehicule-modal') {
            closeVehiculeModal();
        }
    });

    // Detail modal events
    if (closeVehiculeDetailModalBtn) {
        closeVehiculeDetailModalBtn.addEventListener('click', () => closeVehiculeDetailModal());
    }
    if (vehiculeDetailModal) {
        vehiculeDetailModal.addEventListener('click', (e) => {
            if (e.target.dataset.close === 'vehicule-detail-modal') {
                closeVehiculeDetailModal();
            }
        });
    }

    vehiculeImages.addEventListener('click', (e) => {
        const card = e.target.closest('[data-image-src]');
        if (!card) return;
        const src = card.dataset.imageSrc;
        const caption = card.dataset.caption || '';
        openImageViewer(resolveVehiculeImageSrc(src), caption);
    });

    closeImageViewerBtn.addEventListener('click', closeImageViewer);
    imageViewerModal.addEventListener('click', (e) => {
        if (e.target.dataset.close === 'image-viewer-modal') {
            closeImageViewer();
        }
    });
    imageViewerImg.addEventListener('wheel', (e) => {
        e.preventDefault();
        adjustImageViewerZoom(e.deltaY < 0 ? 0.12 : -0.12);
    });
    imageViewerImg.addEventListener('dblclick', () => {
        imageViewerZoom = 1;
        imageViewerImg.style.transform = 'scale(1)';
    });
}
