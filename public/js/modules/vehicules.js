// ============================================================================
// Vehicules (Vehicles) Management
// ============================================================================
// Handles vehicle CRUD operations, modal interactions, rendering, and images

import { state } from './state.js';
import { ensureAuth } from './auth.js';
import { formatDate, formatVehiculeStatut, formatCategorie, formatOptionVehicule, formatEnergie, formatBoite, formatLeasing, formatUtilisation, formatCurrency, resolveVehiculeImageSrc } from './utils.js';

// DOM Elements
export const vehiculeTableBody = document.getElementById('vehicule-rows');
export const vehiculeDetailEmpty = document.getElementById('vehicule-detail-empty');
export const vehiculeDetail = document.getElementById('vehicule-detail');
export const vehiculeDetailTitle = document.getElementById('vehicule-detail-title');
export const vehiculeDetailStatut = document.getElementById('vehicule-detail-statut');
export const vehiculeDetailCategory = document.getElementById('vehicule-detail-category');
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
export const vehiculeImagesInput = document.getElementById('vehicule-images-input');
export const imageViewerModal = document.getElementById('image-viewer-modal');
export const imageViewerImg = document.getElementById('image-viewer-img');
export const imageViewerCaption = document.getElementById('image-viewer-caption');
export const closeImageViewerBtn = document.getElementById('close-image-viewer');

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

export function setVehiculeFormMode(mode, vehicule = null) {
    if (vehiculeImagesInput) vehiculeImagesInput.value = '';
    if (mode === 'edit' && vehicule) {
        state.vehiculeEditingId = vehicule.id;
        vehiculeFormTitle.textContent = 'Modifier le véhicule';
        vehiculeFormSubmit.textContent = 'Mettre à jour';
        vehiculeForm.numero.value = vehicule.numero || '';
        vehiculeForm.code.value = vehicule.code || '';
        vehiculeForm.marque.value = vehicule.marque || '';
        vehiculeForm.modele.value = vehicule.modele || '';
        vehiculeForm.annee.value = vehicule.annee || '';
        vehiculeForm.couleur.value = vehicule.couleur || '';
        vehiculeForm.chassis.value = vehicule.chassis || '';
        syncVehiculeChauffeurSelect(vehicule.chauffeur_id);
        vehiculeForm.date_acquisition.value = vehicule.date_acquisition || '';
        vehiculeForm.valeur.value = vehicule.valeur || '';
        vehiculeForm.statut.value = Number(vehicule.statut) === 0 ? '0' : '1';
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
        vehiculeFormTitle.textContent = 'Ajouter un véhicule';
        vehiculeFormSubmit.textContent = 'Enregistrer';
        syncVehiculeChauffeurSelect('');
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
            <td>${v.code || ''}</td>
            <td>${v.numero || ''}</td>
            <td>${[v.marque, v.modele].filter(Boolean).join(' ')}</td>
            <td>${v.chauffeur ? `${v.chauffeur.nom || ''} ${v.chauffeur.prenom || ''}`.trim() : '-'}</td>
            <td><span class="badge">${formatVehiculeStatut(v.statut)}</span></td>
            <td>${formatCategorie(v.categorie)}</td>
            <td class="row-actions">
                <button class="btn secondary xs" data-action="edit" type="button">Modifier</button>
                <button class="btn danger xs" data-action="delete" type="button">Supprimer</button>
            </td>
        </tr>
    `).join('');
    vehiculeTableBody.innerHTML = rows;
}

export function clearVehiculeDetail() {
    vehiculeDetail.style.display = 'none';
    vehiculeDetailEmpty.style.display = 'block';
    state.selectedVehiculeId = null;
}

export function renderVehiculeDetail(v) {
    vehiculeDetailTitle.textContent = `${v.marque || ''} ${v.modele || ''}`.trim() || v.code || 'Véhicule';
    vehiculeDetailStatut.textContent = formatVehiculeStatut(v.statut);
    vehiculeDetailCategory.textContent = `${formatCategorie(v.categorie)} • ${formatOptionVehicule(v.option_vehicule)}`;
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
            <div class="card vehicule-thumb" data-image-src="${img.image_path}" data-caption="${img.legende || 'Photo'}">
                <div class="muted-small">${img.legende || 'Photo'}</div>
                <img src="${resolveVehiculeImageSrc(img.image_path)}" alt="vehicule" />
            </div>
        `).join('');
    } else {
        vehiculeImages.innerHTML = '<div class="muted-small">Aucune image disponible.</div>';
    }

    vehiculeDetailEmpty.style.display = 'none';
    vehiculeDetail.style.display = 'block';
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
    if (focusImages) {
        setTimeout(() => {
            vehiculeImages.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }, 150);
    }
}

export async function handleDeleteVehicule(id) {
    ensureAuth();
    const confirmed = window.confirm('Supprimer ce véhicule ?');
    if (!confirmed) return;
    await axios.delete(`/api/vehicules/${id}`);
    if (state.selectedVehiculeId === Number(id)) {
        clearVehiculeDetail();
    }
    await loadVehicules();
}

export async function loadVehicules() {
    ensureAuth();
    const res = await axios.get('/api/vehicules');
    const list = res.data.data || res.data;
    state.vehicules = list;
    renderVehiculeRows();
    if (state.selectedVehiculeId) {
        const exists = state.vehicules.find(v => v.id === state.selectedVehiculeId);
        if (exists) {
            showVehiculeDetail(state.selectedVehiculeId).catch(console.error);
        } else {
            clearVehiculeDetail();
        }
    }
    document.dispatchEvent(new CustomEvent('data:vehicules:updated'));
}

// ============================================================================
// Event Listeners
// ============================================================================

export function initializeVehiculeEvents() {
    vehiculeForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        ensureAuth();
        const formData = new FormData(e.target);
        const isEdit = Boolean(state.vehiculeEditingId);
        const url = isEdit ? `/api/vehicules/${state.vehiculeEditingId}` : '/api/vehicules';

        if (isEdit) {
            formData.append('_method', 'PUT');
        }

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
    });

    vehiculeTableBody.addEventListener('click', async (e) => {
        const row = e.target.closest('tr[data-id]');
        if (!row) return;
        const id = row.dataset.id;
        const action = e.target.dataset.action;
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
        await showVehiculeDetail(id);
    });

    openVehiculeModalBtn.addEventListener('click', () => openVehiculeModal('create'));
    closeVehiculeModalBtn.addEventListener('click', () => closeVehiculeModal());
    vehiculeModal.addEventListener('click', (e) => {
        if (e.target.dataset.close === 'vehicule-modal') {
            closeVehiculeModal();
        }
    });

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
