// ============================================================================
// Sinistres Management (Suivi, Assurance, Réparations, Statistiques)
// ============================================================================

import { state, showToast, extractErrorMessage } from './state.js';
import { ensureAuth } from './auth.js';
import {
    formatDate,
    formatTime,
    formatCurrency,
    formatSinistreStatut,
    formatSinistreType,
    formatSinistreGravite,
    formatSinistreResponsable,
    toInputDate,
} from './utils.js';

// DOM Elements
const sinistreTabs = document.querySelectorAll('[data-sinistre-tab]');
const sinistrePanels = document.querySelectorAll('[data-sinistre-panel]');
const sinistreTableBody = document.getElementById('sinistre-rows');
const sinistreFilterStatut = document.getElementById('sinistre-filter-statut');
const sinistreFilterVehicule = document.getElementById('sinistre-filter-vehicule');
const refreshSinistresBtn = document.getElementById('refresh-sinistres');
const sinistreModal = document.getElementById('sinistre-modal');
const sinistreForm = document.getElementById('sinistre-form');
const sinistreFormTitle = document.getElementById('sinistre-form-title');
const sinistreFormSubmit = document.getElementById('sinistre-form-submit');
const openSinistreModalBtn = document.getElementById('open-sinistre-modal');
const closeSinistreModalBtn = document.getElementById('close-sinistre-modal');
const sinistreVehiculeSelect = document.getElementById('sinistre-vehicule-select');
const sinistreChauffeurSelect = document.getElementById('sinistre-chauffeur-select');
const sinistreStatutSelect = document.getElementById('sinistre-statut-select');

const sinistreDetailCard = document.getElementById('sinistre-detail-card');
const sinistreDetailEmpty = document.getElementById('sinistre-detail-empty');
const sinistreDetail = document.getElementById('sinistre-detail');
const sinistreDetailNumero = document.getElementById('sinistre-detail-numero');
const sinistreDetailStatut = document.getElementById('sinistre-detail-statut');
const sinistreDetailVehicule = document.getElementById('sinistre-detail-vehicule');
const sinistreDetailDate = document.getElementById('sinistre-detail-date');
const sinistreDetailHeure = document.getElementById('sinistre-detail-heure');
const sinistreDetailLieu = document.getElementById('sinistre-detail-lieu');
const sinistreDetailType = document.getElementById('sinistre-detail-type');
const sinistreDetailGravite = document.getElementById('sinistre-detail-gravite');
const sinistreDetailResponsable = document.getElementById('sinistre-detail-responsable');
const sinistreDetailMontant = document.getElementById('sinistre-detail-montant');
const sinistreDetailCoutTotal = document.getElementById('sinistre-detail-cout-total');
const sinistreDetailDescription = document.getElementById('sinistre-detail-description');
const sinistreDetailAssurance = document.getElementById('sinistre-detail-assurance');
const sinistreDetailReparations = document.getElementById('sinistre-detail-reparations');

const assuranceSinistreSelect = document.getElementById('assurance-sinistre-select');
const assuranceTableBody = document.getElementById('assurance-rows');
const assuranceModal = document.getElementById('assurance-modal');
const openAssuranceModalBtn = document.getElementById('open-assurance-modal');
const closeAssuranceModalBtn = document.getElementById('close-assurance-modal');
const assuranceForm = document.getElementById('assurance-form');
const assuranceFormTitle = document.getElementById('assurance-form-title');
const assuranceFormSubmit = document.getElementById('assurance-form-submit');
const assuranceSinistreInput = document.getElementById('assurance-sinistre-input');

const reparationSinistreSelect = document.getElementById('reparation-sinistre-select');
const reparationTableBody = document.getElementById('reparation-rows');
const reparationModal = document.getElementById('reparation-modal');
const openReparationModalBtn = document.getElementById('open-reparation-modal');
const closeReparationModalBtn = document.getElementById('close-reparation-modal');
const reparationForm = document.getElementById('reparation-form');
const reparationFormTitle = document.getElementById('reparation-form-title');
const reparationFormSubmit = document.getElementById('reparation-form-submit');
const reparationSinistreSelectModal = document.getElementById('reparation-sinistre-select-modal');

const statsDateStart = document.getElementById('stats-date-start');
const statsDateEnd = document.getElementById('stats-date-end');
const refreshStatsBtn = document.getElementById('refresh-sinistre-stats');
const statsTotalSinistres = document.getElementById('stats-total-sinistres');
const statsTauxPrise = document.getElementById('stats-taux-prise');
const statsVehiculesPlus = document.getElementById('stats-vehicules-plus');
const statsClassementChauffeurs = document.getElementById('stats-classement-chauffeurs');
const statsCoutParVehicule = document.getElementById('stats-cout-par-vehicule');
const statsParPeriode = document.getElementById('stats-par-periode');

function formatReparationType(value) {
    const map = { mecanique: 'Mécanique', carrosserie: 'Carrosserie' };
    return map[value] || '-';
}

function formatReparationStatut(value) {
    const map = { en_attente: 'En attente', en_cours: 'En cours', termine: 'Terminé' };
    return map[value] || '-';
}

// Helpers
function sinistreLabel(s) {
    const vehicule = s.vehicule;
    const vehiculeLabel = vehicule ? (vehicule.numero || `Véhicule ${vehicule.id}`) : 'Véhicule';
    return `${s.numero_sinistre || 'Nouveau'} — ${vehiculeLabel}`;
}

function populateVehiculeOptions(selectEl, placeholder = 'Choisir un véhicule') {
    if (!selectEl) return;
    const options = state.vehicules.map(v => {
        const label = v.numero || `Véhicule ${v.id}`;
        return `<option value="${v.id}">${label}</option>`;
    }).join('');
    selectEl.innerHTML = `<option value="">${placeholder}</option>${options}`;
}

function populateChauffeurOptions(selectEl) {
    if (!selectEl) return;
    const options = state.chauffeurs.map(ch => {
        const label = `${ch.nom || ''} ${ch.prenom || ''}`.trim() || `Chauffeur ${ch.id}`;
        return `<option value="${ch.id}">${label}</option>`;
    }).join('');
    selectEl.innerHTML = `<option value="">Aucun</option>${options}`;
}

function populateSinistreSelects() {
    populateVehiculeOptions(sinistreVehiculeSelect);
    populateChauffeurOptions(sinistreChauffeurSelect);
    populateVehiculeOptions(sinistreFilterVehicule, 'Tous les véhicules');

    const options = state.sinistres.map(s => `<option value="${s.id}">${sinistreLabel(s)}</option>`).join('');
    if (assuranceSinistreSelect) assuranceSinistreSelect.innerHTML = `<option value="">Choisir un sinistre</option>${options}`;
    if (reparationSinistreSelect) reparationSinistreSelect.innerHTML = `<option value="">Choisir un sinistre</option>${options}`;
    if (reparationSinistreSelectModal) reparationSinistreSelectModal.innerHTML = `<option value="">Choisir un sinistre</option>${options}`;
}

// Rendering
function renderSinistreRows() {
    const statutFilter = sinistreFilterStatut?.value || '';
    const vehiculeFilter = sinistreFilterVehicule?.value || '';

    const filtered = state.sinistres.filter(s => {
        const matchStatut = statutFilter ? s.statut_sinistre === statutFilter : true;
        const matchVehicule = vehiculeFilter ? Number(s.vehicule_id) === Number(vehiculeFilter) : true;
        return matchStatut && matchVehicule;
    });

    const rows = filtered.map(s => {
        const vehicule = s.vehicule;
        const vehiculeLabel = vehicule ? (vehicule.numero || `Véhicule ${vehicule.id}`) : '-';
        return `
            <tr data-sinistre-id="${s.id}">
                <td>${s.numero_sinistre || '-'}</td>
                <td>${vehiculeLabel || '-'}</td>
                <td>${formatDate(s.date_sinistre)}</td>
                <td>${formatSinistreGravite(s.gravite)}</td>
                <td><span class="badge">${formatSinistreStatut(s.statut_sinistre)}</span></td>
                <td>${formatCurrency(s.cout_total)}</td>
                <td class="row-actions">
                    <button class="btn secondary xs" data-action="view" type="button">Voir</button>
                    <button class="btn secondary xs" data-action="edit" type="button">Modifier</button>
                    <button class="btn danger xs" data-action="close" type="button">Clôturer</button>
                </td>
            </tr>
        `;
    }).join('');

    sinistreTableBody.innerHTML = rows || '<tr><td colspan="7" class="muted">Aucun sinistre trouvé.</td></tr>';
}

function renderSinistreDetail(s) {
    if (!s) {
        sinistreDetail.style.display = 'none';
        sinistreDetailEmpty.style.display = 'block';
        return;
    }

    sinistreDetailNumero.textContent = s.numero_sinistre || 'Sinistre';
    sinistreDetailStatut.textContent = formatSinistreStatut(s.statut_sinistre);
    sinistreDetailVehicule.textContent = sinistreLabel(s);
    sinistreDetailDate.textContent = formatDate(s.date_sinistre);
    sinistreDetailHeure.textContent = formatTime(s.heure_sinistre);
    sinistreDetailLieu.textContent = s.lieu_sinistre || '-';
    sinistreDetailType.textContent = formatSinistreType(s.type_sinistre);
    sinistreDetailGravite.textContent = formatSinistreGravite(s.gravite);
    sinistreDetailResponsable.textContent = formatSinistreResponsable(s.responsable);
    sinistreDetailMontant.textContent = formatCurrency(s.montant_estime);
    sinistreDetailCoutTotal.textContent = formatCurrency(s.cout_total);
    sinistreDetailDescription.textContent = s.description || 'Aucune description.';

    if (s.assurance) {
        const a = s.assurance;
        sinistreDetailAssurance.innerHTML = `
            <div class="chip">${a.compagnie_assurance || 'Compagnie inconnue'}</div>
            <div class="muted-small">Dossier ${a.numero_dossier || '-'} · ${formatDate(a.date_declaration)}</div>
            <div class="muted-small">Décision ${a.decision || 'en_attente'} · Statut ${a.statut_assurance}</div>
        `;
    } else {
        sinistreDetailAssurance.innerHTML = '<span class="muted-small">Aucune fiche assurance.</span>';
    }

    if (s.reparations && s.reparations.length) {
        const parts = s.reparations.map(r => `${r.garage || 'Garage'} · ${formatReparationType(r.type_reparation)} · ${formatReparationStatut(r.statut_reparation)} · ${formatCurrency(r.cout_reparation)}`);
        sinistreDetailReparations.textContent = parts.join(' | ');
    } else {
        sinistreDetailReparations.textContent = 'Aucune réparation enregistrée.';
    }

    sinistreDetailEmpty.style.display = 'none';
    sinistreDetail.style.display = 'block';
}

function renderAssuranceRows() {
    const rows = state.sinistres.map(s => {
        const a = s.assurance;
        return `
            <tr data-sinistre-id="${s.id}" data-assurance-id="${a?.id || ''}">
                <td>${s.numero_sinistre || '-'}</td>
                <td>${a?.compagnie_assurance || '-'}</td>
                <td>${a?.numero_dossier || '-'}</td>
                <td>${a ? a.decision : '-'}</td>
                <td>${a ? a.statut_assurance : '-'}</td>
                <td>${formatCurrency(a?.montant_pris_en_charge)}</td>
                <td>${formatCurrency(a?.franchise)}</td>
                <td class="row-actions">
                    <button class="btn secondary xs" data-assurance-action="edit" type="button">${a ? 'Modifier' : 'Déclarer'}</button>
                </td>
            </tr>
        `;
    }).join('');
    assuranceTableBody.innerHTML = rows || '<tr><td colspan="8" class="muted">Aucun sinistre à afficher.</td></tr>';
}

function renderReparationRows() {
    const selectedId = reparationSinistreSelect?.value;
    const list = selectedId ? state.sinistres.filter(s => Number(s.id) === Number(selectedId)) : state.sinistres;
    const rows = list.flatMap(s => (s.reparations || []).map(r => `
        <tr data-sinistre-id="${s.id}" data-reparation-id="${r.id}">
            <td>${s.numero_sinistre}</td>
            <td>${r.garage || '-'}</td>
            <td>${formatReparationType(r.type_reparation)}</td>
            <td>${formatDate(r.date_debut)}</td>
            <td>${formatDate(r.date_fin_prevue)}</td>
            <td>${formatReparationStatut(r.statut_reparation)}</td>
            <td>${formatCurrency(r.cout_reparation)}</td>
            <td class="row-actions">
                <button class="btn secondary xs" data-reparation-action="edit" type="button">Modifier</button>
                <button class="btn danger xs" data-reparation-action="delete" type="button">Supprimer</button>
            </td>
        </tr>
    `));

    reparationTableBody.innerHTML = rows.join('') || '<tr><td colspan="8" class="muted">Aucune réparation enregistrée.</td></tr>';
}

function renderStats() {
    const stats = state.sinistreStats || {};
    const total = (stats.par_periode || []).reduce((acc, p) => acc + (p.total || 0), 0);
    statsTotalSinistres.textContent = total || '-';
    const taux = (stats.taux_prise_en_charge_moyen || 0) * 100;
    statsTauxPrise.textContent = `${taux ? taux.toFixed(1) : '0'} %`;

    const vehicules = (stats.vehicules_plus_sinistres || []).map(item => `<div>${item.vehicule.label || 'Véhicule'} · ${item.sinistres} sin.</div>`).join('');
    statsVehiculesPlus.innerHTML = vehicules || '<span class="muted-small">Pas de données.</span>';

    const chauffeurs = (stats.classement_chauffeurs || []).map(item => `<div>${item.chauffeur.nom || 'Chauffeur'} · ${item.sinistres} sin.</div>`).join('');
    statsClassementChauffeurs.innerHTML = chauffeurs || '<span class="muted-small">Pas de données.</span>';

    const couts = (stats.cout_par_vehicule || []).map(item => `<div>${item.vehicule.label || 'Véhicule'} · ${formatCurrency(item.cout_total)}</div>`).join('');
    statsCoutParVehicule.innerHTML = couts || '<span class="muted-small">Pas de données.</span>';

    const periodes = (stats.par_periode || []).map(item => `<div>${item.periode} · ${item.total}</div>`).join('');
    statsParPeriode.innerHTML = periodes || '<span class="muted-small">Pas de données.</span>';
}

// Modals helpers
function openSinistreModal(mode = 'create', sinistre = null) {
    state.sinistreEditingId = mode === 'edit' && sinistre ? sinistre.id : null;
    sinistreForm.reset();
    populateVehiculeOptions(sinistreVehiculeSelect);
    populateChauffeurOptions(sinistreChauffeurSelect);

    if (mode === 'edit' && sinistre) {
        sinistreFormTitle.textContent = 'Modifier un sinistre';
        sinistreFormSubmit.textContent = 'Mettre à jour';
        sinistreForm.numero_sinistre.value = sinistre.numero_sinistre || '';
        sinistreForm.vehicule_id.value = sinistre.vehicule_id || '';
        sinistreForm.chauffeur_id.value = sinistre.chauffeur_id || '';
        sinistreForm.date_sinistre.value = toInputDate(sinistre.date_sinistre);
        sinistreForm.heure_sinistre.value = sinistre.heure_sinistre || '';
        sinistreForm.lieu_sinistre.value = sinistre.lieu_sinistre || '';
        sinistreForm.type_sinistre.value = sinistre.type_sinistre || 'accident';
        sinistreForm.gravite.value = sinistre.gravite || 'mineur';
        sinistreForm.responsable.value = sinistre.responsable || 'inconnu';
        sinistreForm.montant_estime.value = sinistre.montant_estime || '';
        sinistreForm.description.value = sinistre.description || '';
        if (sinistre.statut_sinistre) {
            sinistreStatutSelect.value = sinistre.statut_sinistre;
        }
    } else {
        sinistreFormTitle.textContent = 'Déclarer un sinistre';
        sinistreFormSubmit.textContent = 'Enregistrer';
        sinistreForm.gravite.value = 'mineur';
        sinistreForm.type_sinistre.value = 'accident';
        sinistreForm.responsable.value = 'inconnu';
        sinistreStatutSelect.value = 'declare';
    }

    sinistreModal.classList.remove('hidden');
}

function closeSinistreModal() {
    state.sinistreEditingId = null;
    sinistreModal.classList.add('hidden');
}

function openAssuranceModal(sinistreId = '') {
    state.assuranceEditingId = null;
    assuranceForm.reset();
    assuranceSinistreInput.value = sinistreId || '';
    populateSinistreSelects();

    const sinistre = state.sinistres.find(s => Number(s.id) === Number(sinistreId));
    if (sinistre?.assurance) {
        const a = sinistre.assurance;
        state.assuranceEditingId = a.id;
        assuranceFormTitle.textContent = 'Mettre à jour l\'assurance';
        assuranceForm.compagnie_assurance.value = a.compagnie_assurance || '';
        assuranceForm.numero_dossier.value = a.numero_dossier || '';
        assuranceForm.date_declaration.value = toInputDate(a.date_declaration);
        assuranceForm.expert_nom.value = a.expert_nom || '';
        assuranceForm.date_expertise.value = toInputDate(a.date_expertise);
        assuranceForm.decision.value = a.decision || 'en_attente';
        assuranceForm.montant_pris_en_charge.value = a.montant_pris_en_charge || '';
        assuranceForm.franchise.value = a.franchise || '';
        assuranceForm.date_validation.value = toInputDate(a.date_validation);
        assuranceForm.statut_assurance.value = a.statut_assurance || 'en_cours';
    } else {
        assuranceFormTitle.textContent = 'Déclaration assurance';
        assuranceForm.decision.value = 'en_attente';
        assuranceForm.statut_assurance.value = 'en_cours';
    }

    assuranceModal.classList.remove('hidden');
}

function closeAssuranceModal() {
    state.assuranceEditingId = null;
    assuranceModal.classList.add('hidden');
}

function openReparationModal(mode = 'create', sinistreId = '', reparation = null) {
    state.reparationEditingId = mode === 'edit' && reparation ? reparation.id : null;
    reparationForm.reset();
    populateSinistreSelects();
    if (sinistreId) {
        reparationSinistreSelectModal.value = sinistreId;
    }

    if (mode === 'edit' && reparation) {
        reparationFormTitle.textContent = 'Mettre à jour la réparation';
        reparationForm.garage.value = reparation.garage || '';
        reparationForm.type_reparation.value = reparation.type_reparation || '';
        reparationForm.date_debut.value = toInputDate(reparation.date_debut);
        reparationForm.date_fin_prevue.value = toInputDate(reparation.date_fin_prevue);
        reparationForm.date_fin_reelle.value = toInputDate(reparation.date_fin_reelle);
        reparationForm.cout_reparation.value = reparation.cout_reparation || '';
        reparationForm.prise_en_charge.value = reparation.prise_en_charge || 'societe';
        reparationForm.statut_reparation.value = reparation.statut_reparation || 'en_attente';
        reparationForm.facture_reference.value = reparation.facture_reference || '';
    } else {
        reparationFormTitle.textContent = 'Ajouter une réparation';
        reparationForm.prise_en_charge.value = 'societe';
        reparationForm.statut_reparation.value = 'en_attente';
    }

    reparationModal.classList.remove('hidden');
}

function closeReparationModal() {
    state.reparationEditingId = null;
    reparationModal.classList.add('hidden');
}

// API
export async function loadSinistres() {
    ensureAuth();
    const res = await axios.get('/api/sinistres', { params: { per_page: 200 } });
    state.sinistres = res.data.data || res.data || [];
    populateSinistreSelects();
    renderSinistreRows();
    renderAssuranceRows();
    renderReparationRows();

    if (state.selectedSinistreId) {
        const found = state.sinistres.find(s => Number(s.id) === Number(state.selectedSinistreId));
        renderSinistreDetail(found);
    } else if (state.sinistres.length) {
        renderSinistreDetail(state.sinistres[0]);
        state.selectedSinistreId = state.sinistres[0].id;
    } else {
        renderSinistreDetail(null);
    }
}

export async function loadSinistreStats(params = {}) {
    ensureAuth();
    const res = await axios.get('/api/sinistres/stats', { params });
    state.sinistreStats = res.data || {};
    renderStats();
}

// Tab activation (used by navigation)
export function activateSinistreTab(tabKey = 'tableau') {
    state.sinistreCurrentTab = tabKey;
    sinistreTabs.forEach(tab => tab.classList.toggle('active', tab.dataset.sinistreTab === tabKey));
    sinistrePanels.forEach(panel => panel.classList.toggle('active', panel.dataset.sinistrePanel === tabKey));
}

// Events
export function initializeSinistreEvents() {
    if (!sinistreForm || !sinistreTableBody) return;

    sinistreTabs.forEach(tab => {
        tab.addEventListener('click', () => activateSinistreTab(tab.dataset.sinistreTab));
    });

    document.addEventListener('data:vehicules:updated', populateSinistreSelects);
    document.addEventListener('data:chauffeurs:updated', populateSinistreSelects);

    sinistreFilterStatut?.addEventListener('change', renderSinistreRows);
    sinistreFilterVehicule?.addEventListener('change', renderSinistreRows);
    refreshSinistresBtn?.addEventListener('click', loadSinistres);

    sinistreTableBody.addEventListener('click', async (e) => {
        const row = e.target.closest('tr[data-sinistre-id]');
        if (!row) return;
        const id = row.dataset.sinistreId;
        const sinistre = state.sinistres.find(s => Number(s.id) === Number(id));
        const action = e.target.dataset.action;

        if (action === 'edit') {
            e.stopPropagation();
            openSinistreModal('edit', sinistre);
            return;
        }
        if (action === 'close') {
            e.stopPropagation();
            const confirmed = window.confirm('Clôturer ce sinistre ?');
            if (!confirmed) return;
            try {
                await axios.put(`/api/sinistres/${id}`, { statut_sinistre: 'clos' });
                await loadSinistres();
                showToast('Sinistre clôturé.');
            } catch (err) {
                showToast(extractErrorMessage(err), 'error');
            }
            return;
        }

        state.selectedSinistreId = Number(id);
        renderSinistreDetail(sinistre);
    });

    sinistreForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        ensureAuth();
        const payload = Object.fromEntries(new FormData(sinistreForm).entries());
        try {
            if (state.sinistreEditingId) {
                await axios.put(`/api/sinistres/${state.sinistreEditingId}`, payload);
            } else {
                await axios.post('/api/sinistres', payload);
            }
            closeSinistreModal();
            await loadSinistres();
            showToast('Sinistre enregistré.');
        } catch (err) {
            showToast(extractErrorMessage(err), 'error');
        }
    });

    openSinistreModalBtn?.addEventListener('click', () => openSinistreModal('create'));
    closeSinistreModalBtn?.addEventListener('click', closeSinistreModal);
    sinistreModal?.addEventListener('click', (e) => {
        if (e.target.dataset.close === 'sinistre-modal') closeSinistreModal();
    });

    assuranceTableBody.addEventListener('click', (e) => {
        const btn = e.target.closest('[data-assurance-action]');
        if (!btn) return;
        const row = btn.closest('tr[data-sinistre-id]');
        openAssuranceModal(row?.dataset.sinistreId || '');
    });

    openAssuranceModalBtn?.addEventListener('click', () => {
        const selected = assuranceSinistreSelect?.value || '';
        openAssuranceModal(selected);
    });
    closeAssuranceModalBtn?.addEventListener('click', closeAssuranceModal);
    assuranceModal?.addEventListener('click', (e) => {
        if (e.target.dataset.close === 'assurance-modal') closeAssuranceModal();
    });

    assuranceForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        ensureAuth();
        const payload = Object.fromEntries(new FormData(assuranceForm).entries());
        try {
            if (state.assuranceEditingId) {
                await axios.put(`/api/assurance-sinistres/${state.assuranceEditingId}`, payload);
            } else {
                await axios.post('/api/assurance-sinistres', payload);
            }
            closeAssuranceModal();
            await loadSinistres();
            showToast('Dossier assurance enregistré.');
        } catch (err) {
            showToast(extractErrorMessage(err), 'error');
        }
    });

    reparationTableBody.addEventListener('click', async (e) => {
        const btn = e.target.closest('[data-reparation-action]');
        if (!btn) return;
        const row = btn.closest('tr[data-reparation-id]');
        const sinistreId = row?.dataset.sinistreId;
        const reparationId = row?.dataset.reparationId;
        const sinistre = state.sinistres.find(s => Number(s.id) === Number(sinistreId));
        const repar = sinistre?.reparations?.find(r => Number(r.id) === Number(reparationId));

        if (btn.dataset.reparationAction === 'edit') {
            openReparationModal('edit', sinistreId, repar);
            return;
        }
        if (btn.dataset.reparationAction === 'delete') {
            const confirmed = window.confirm('Supprimer cette réparation ?');
            if (!confirmed) return;
            try {
                await axios.delete(`/api/reparation-sinistres/${reparationId}`);
                await loadSinistres();
                showToast('Réparation supprimée.');
            } catch (err) {
                showToast(extractErrorMessage(err), 'error');
            }
        }
    });

    openReparationModalBtn?.addEventListener('click', () => {
        const selected = reparationSinistreSelect?.value || '';
        openReparationModal('create', selected);
    });
    closeReparationModalBtn?.addEventListener('click', closeReparationModal);
    reparationModal?.addEventListener('click', (e) => {
        if (e.target.dataset.close === 'reparation-modal') closeReparationModal();
    });

    reparationForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        ensureAuth();
        const payload = Object.fromEntries(new FormData(reparationForm).entries());
        try {
            if (state.reparationEditingId) {
                await axios.put(`/api/reparation-sinistres/${state.reparationEditingId}`, payload);
            } else {
                await axios.post('/api/reparation-sinistres', payload);
            }
            closeReparationModal();
            await loadSinistres();
            showToast('Réparation enregistrée.');
        } catch (err) {
            showToast(extractErrorMessage(err), 'error');
        }
    });

    reparationSinistreSelect?.addEventListener('change', renderReparationRows);

    refreshStatsBtn?.addEventListener('click', () => {
        loadSinistreStats({
            date_start: statsDateStart?.value || undefined,
            date_end: statsDateEnd?.value || undefined,
        });
    });
}
