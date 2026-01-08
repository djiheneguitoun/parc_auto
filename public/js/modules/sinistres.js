// ============================================================================
// Sinistres Management (Suivi, Assurance, Réparations, Statistiques)
// ============================================================================

import { state, showToast, showConfirm, extractErrorMessage } from './state.js';
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
const sinistreStatutHidden = document.getElementById('sinistre-statut-hidden');
const sinistreNumeroInput = document.getElementById('sinistre-numero');
const sinistreDateInput = document.querySelector('#sinistre-form [name="date_sinistre"]');
const sinistreHeureInput = document.querySelector('#sinistre-form [name="heure_sinistre"]');

const sinistreDetailCard = document.getElementById('sinistre-detail-card');
const sinistreDetailEmpty = document.getElementById('sinistre-detail-empty');
const sinistreDetail = document.getElementById('sinistre-detail');
const sinistreDetailModal = document.getElementById('sinistre-detail-modal');
const closeSinistreDetailModalBtn = document.getElementById('close-sinistre-detail-modal');
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
const assuranceSinistreSelectModal = document.getElementById('assurance-sinistre-select-modal');
const assuranceNumeroDossier = document.getElementById('assurance-numero-dossier');
const assuranceDecisionSelect = document.getElementById('assurance-decision-select');
const assuranceDecisionHelp = document.getElementById('assurance-decision-help');
const assuranceStatutHidden = document.getElementById('assurance-statut-hidden');
const assuranceStatutSelect = document.getElementById('assurance-statut-select');

const reparationSinistreSelect = document.getElementById('reparation-sinistre-select');
const reparationTableBody = document.getElementById('reparation-rows');
const reparationModal = document.getElementById('reparation-modal');
const openReparationModalBtn = document.getElementById('open-reparation-modal');
const closeReparationModalBtn = document.getElementById('close-reparation-modal');
const reparationForm = document.getElementById('reparation-form');
const reparationFormTitle = document.getElementById('reparation-form-title');
const reparationFormSubmit = document.getElementById('reparation-form-submit');
const reparationSinistreSelectModal = document.getElementById('reparation-sinistre-select-modal');
const reparationPriseEnChargeHidden = document.getElementById('reparation-prise-en-charge-hidden');
const reparationPriseEnChargeSelect = document.getElementById('reparation-prise-en-charge-select');
const reparationPriseEnChargeHelp = document.getElementById('reparation-prise-en-charge-help');
const reparationStatutHidden = document.getElementById('reparation-statut-hidden');
const reparationStatutSelect = document.getElementById('reparation-statut-select');
const reparationStatutHelp = document.getElementById('reparation-statut-help');

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

function formatAssuranceDecision(value) {
    const map = { 
        en_attente: 'En attente', 
        accepte: 'Acceptée', 
        refuse: 'Refusée', 
        partiel: 'Partielle'
    };
    return map[value] || value || '-';
}

function formatAssuranceStatut(value) {
    const map = { 
        en_attente: 'En attente', 
        en_cours: 'En cours', 
        valide: 'Validé',
        refuse: 'Refusé',
        cloture: 'Clôturé', 
        termine: 'Terminé'
    };
    return map[value] || value || '-';
}

// Helpers
function sinistreLabel(s) {
    const vehicule = s.vehicule;
    const vehiculeLabel = vehicule ? (vehicule.numero || `Véhicule ${vehicule.id}`) : 'Véhicule';
    return `${s.numero_sinistre || 'Nouveau'} — ${vehiculeLabel}`;
}

function populateVehiculeOptions(selectEl, placeholder = 'Choisir un véhicule') {
    if (!selectEl) return;
    // If target is a native select element, populate normally
    if (selectEl.tagName === 'SELECT') {
        const options = state.vehicules.map(v => {
            const label = v.numero || `Véhicule ${v.id}`;
            return `<option value="${v.id}">${label}</option>`;
        }).join('');
        selectEl.innerHTML = `<option value="">${placeholder}</option>${options}`;
        return;
    }

    // If target is a hidden input (custom-select), populate the custom-select options list
    if (selectEl.tagName === 'INPUT') {
        // find the custom-select container by data-name matching the input id
        const container = document.querySelector(`.custom-select[data-name="${selectEl.id}"]`);
        if (!container) return;
        const optionsEl = container.querySelector('.custom-select__options');
        const trigger = container.querySelector('.custom-select__trigger');
        const valueElem = container.querySelector('.custom-select__value');
        // build items
        const items = state.vehicules.map(v => {
            const label = v.numero || `Véhicule ${v.id}`;
            return `<li role="option" data-value="${v.id}">${label}</li>`;
        }).join('');
        // include placeholder as first option, and re-assert it as the selected value so the filter defaults to "tous"
        optionsEl.innerHTML = `<li role="option" data-value="" aria-selected="true">${placeholder}</li>${items}`;
        selectEl.value = '';
        if (valueElem) valueElem.textContent = placeholder;
        if (trigger) trigger.classList.add('selected');
        // if searchable, ensure search input exists at top
        if (container.dataset.searchable === 'true') {
            var search = optionsEl.querySelector('.custom-select__search');
            if (!search) {
                var wrap = document.createElement('div');
                wrap.className = 'custom-select__searchwrap';
                wrap.innerHTML = '<input type="search" class="custom-select__search" placeholder="Rechercher véhicule...">';
                optionsEl.insertBefore(wrap, optionsEl.firstChild);
                search = wrap.querySelector('.custom-select__search');
            }
            // attach handler to filter options
            if (search) {
                search.addEventListener('input', function(e){
                    var q = (e.target.value || '').toLowerCase();
                    optionsEl.querySelectorAll('li[role="option"]').forEach(function(li){
                        var txt = li.textContent.trim().toLowerCase();
                        li.style.display = txt.indexOf(q) !== -1 ? '' : 'none';
                    });
                });
                search.addEventListener('click', function(e){ e.stopPropagation(); });
            }
        }
        return;
    }
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
    
    // For assurance modal - only show sinistres WITHOUT insurance
    const sinistresWithoutAssurance = state.sinistres.filter(s => !s.assurance);
    const optionsWithoutAssurance = sinistresWithoutAssurance.map(s => `<option value="${s.id}">${sinistreLabel(s)}</option>`).join('');
    
    if (assuranceSinistreSelect) assuranceSinistreSelect.innerHTML = `<option value="">Choisir un sinistre</option>${options}`;
    if (assuranceSinistreSelectModal) assuranceSinistreSelectModal.innerHTML = `<option value="">- Choisir un sinistre -</option>${optionsWithoutAssurance}`;
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

    // Update count badge
    const countBadge = document.getElementById('sinistres-count');
    if (countBadge) {
        countBadge.innerHTML = `<span class="count">${filtered.length}</span> sinistre${filtered.length > 1 ? 's' : ''}`;
    }

    const rows = filtered.map(s => {
        const vehicule = s.vehicule;
        const vehiculeLabel = vehicule ? (vehicule.numero || `Véhicule ${vehicule.id}`) : '-';
        const isClosed = s.statut_sinistre === 'clos';
        
        // RÈGLE: Le bouton clôturer n'est plus nécessaire car le sinistre se clôture automatiquement
        // quand toutes les réparations sont terminées
        
        // Build action buttons - close option always visible
        const editDisabledClass = isClosed ? ' disabled' : '';
        const editDisabledAttr = isClosed ? 'disabled' : '';
        const closeDisabledClass = isClosed ? ' disabled' : '';
        const closeDisabledAttr = isClosed ? 'disabled' : '';

        let actionButtons = `
            <button class="action-btn view" data-action="view" type="button" title="Voir détails">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
            </button>`;
        
        actionButtons += `
            <button class="action-btn edit${editDisabledClass}" data-action="edit" type="button" title="Modifier" ${editDisabledAttr}>
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
            </button>`;

        // Always show close button (no conditions)
        actionButtons += `
            <button class="action-btn close-sinistre${closeDisabledClass}" data-action="close" type="button" title="Clôturer" ${closeDisabledAttr}>
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 11l3 3L22 4"/><path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"/></svg>
            </button>`;
        
        // Always show delete button
        actionButtons += `
            <button class="action-btn delete" data-action="delete" type="button" title="Supprimer">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
            </button>`;
        
        return `
            <tr data-sinistre-id="${s.id}">
                <td><span class="numero-badge">${s.numero_sinistre || '-'}</span></td>
                <td>${vehiculeLabel || '-'}</td>
                <td>${formatDate(s.date_sinistre)}</td>
                <td>${formatSinistreType(s.type_sinistre)}</td>
                <td><span class="pill ${s.gravite || ''}">${formatSinistreGravite(s.gravite)}</span></td>
                <td><span class="pill ${s.statut_sinistre || ''}">${formatSinistreStatut(s.statut_sinistre)}</span></td>
                <td>${formatCurrency(s.cout_total)}</td>
                <td class="action-btns">${actionButtons}</td>
            </tr>
        `;
    }).join('');

    sinistreTableBody.innerHTML = rows || '<tr><td colspan="8" class="muted" style="text-align:center;padding:var(--space-6);">Aucun sinistre trouvé.</td></tr>';
}

function renderSinistreDetail(s) {
    if (!s || !sinistreDetailModal) return;

    sinistreDetailNumero.textContent = s.numero_sinistre || 'Sinistre';
    sinistreDetailStatut.textContent = formatSinistreStatut(s.statut_sinistre);
    sinistreDetailStatut.className = `pill ${s.statut_sinistre || ''}`;
    sinistreDetailVehicule.textContent = sinistreLabel(s);
    sinistreDetailDate.textContent = formatDate(s.date_sinistre);
    sinistreDetailHeure.textContent = formatTime(s.heure_sinistre);
    sinistreDetailLieu.textContent = s.lieu_sinistre || '-';
    sinistreDetailType.textContent = formatSinistreType(s.type_sinistre);
    sinistreDetailGravite.innerHTML = `<span class="pill ${s.gravite || ''}">${formatSinistreGravite(s.gravite)}</span>`;
    sinistreDetailResponsable.textContent = formatSinistreResponsable(s.responsable);
    sinistreDetailMontant.textContent = formatCurrency(s.montant_estime);
    sinistreDetailCoutTotal.textContent = formatCurrency(s.cout_total);
    sinistreDetailDescription.textContent = s.description || 'Aucune description.';

    if (s.assurance) {
        const a = s.assurance;
        sinistreDetailAssurance.innerHTML = `
            <div class="assurance-info">
                <span class="compagnie">${a.compagnie_assurance || 'Compagnie inconnue'}</span>
                <span class="dossier">Dossier ${a.numero_dossier || '-'}</span>
            </div>
            <div class="assurance-status">
                <span class="pill ${a.decision || 'en_attente'}">${formatAssuranceDecision(a.decision)}</span>
                <span class="pill ${a.statut_assurance || ''}">${formatAssuranceStatut(a.statut_assurance)}</span>
            </div>
        `;
    } else {
        sinistreDetailAssurance.innerHTML = '<span class="muted-small">Aucune fiche assurance enregistrée.</span>';
    }

    if (s.reparations && s.reparations.length) {
        const reps = s.reparations.map(r => `
            <div class="reparation-item">
                <span class="garage">${r.garage || 'Garage'}</span>
                <span class="type">${formatReparationType(r.type_reparation)}</span>
                <span class="pill ${r.statut_reparation || ''}">${formatReparationStatut(r.statut_reparation)}</span>
                <span class="cout">${formatCurrency(r.cout_reparation)}</span>
            </div>
        `).join('');
        sinistreDetailReparations.innerHTML = reps;
    } else {
        sinistreDetailReparations.innerHTML = '<span class="muted-small">Aucune réparation enregistrée.</span>';
    }

    // Show the modal
    sinistreDetailModal.classList.remove('hidden');
}

function renderAssuranceRows() {
    // Only show sinistres that have insurance declared
    const sinistresWithAssurance = state.sinistres.filter(s => s.assurance);
    
    const rows = sinistresWithAssurance.map(s => {
        const a = s.assurance;
        const statutClass = a?.statut_assurance || 'en_attente';
        const decisionClass = a?.decision || 'en_attente';
        return `
            <tr data-sinistre-id="${s.id}" data-assurance-id="${a?.id || ''}">
                <td><span class="numero-badge">${s.numero_sinistre || '-'}</span></td>
                <td>${a?.compagnie_assurance || '-'}</td>
                <td>${a?.numero_dossier || '-'}</td>
                <td><span class="pill ${decisionClass}">${formatAssuranceDecision(a.decision)}</span></td>
                <td>${formatCurrency(a?.montant_pris_en_charge)}</td>
                <td>${formatCurrency(a?.franchise)}</td>
                <td><span class="pill ${statutClass}">${formatAssuranceStatut(a.statut_assurance)}</span></td>
                <td class="action-btns">
                    <button class="action-btn view" data-assurance-action="view" type="button" title="Voir détails">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                    </button>
                    <button class="action-btn edit" data-assurance-action="edit" type="button" title="Modifier">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                    </button>
                    <button class="action-btn delete" data-assurance-action="delete" type="button" title="Supprimer">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                    </button>
                </td>
            </tr>
        `;
    }).join('');
    assuranceTableBody.innerHTML = rows || '<tr><td colspan="8" class="muted" style="text-align:center;padding:var(--space-6);">Aucune assurance déclarée.</td></tr>';
}

function renderAssuranceDetail(sinistre) {
    const a = sinistre?.assurance;
    const modal = document.getElementById('assurance-detail-modal');
    if (!modal) return;

    document.getElementById('assurance-detail-sinistre').textContent = sinistre?.numero_sinistre || 'Sinistre';
    document.getElementById('assurance-detail-compagnie').textContent = a?.compagnie_assurance || '-';
    document.getElementById('assurance-detail-dossier').textContent = a?.numero_dossier || '-';
    document.getElementById('assurance-detail-date-declaration').textContent = formatDate(a?.date_declaration);
    document.getElementById('assurance-detail-expert').textContent = a?.expert_nom || '-';
    document.getElementById('assurance-detail-date-expertise').textContent = formatDate(a?.date_expertise);
    document.getElementById('assurance-detail-decision').innerHTML = `<span class="pill ${a?.decision || 'en_attente'}">${formatAssuranceDecision(a?.decision)}</span>`;
    document.getElementById('assurance-detail-montant').textContent = formatCurrency(a?.montant_pris_en_charge);
    document.getElementById('assurance-detail-franchise').textContent = formatCurrency(a?.franchise);
    document.getElementById('assurance-detail-date-validation').textContent = formatDate(a?.date_validation);
    document.getElementById('assurance-detail-statut').innerHTML = `<span class="pill ${a?.statut_assurance || 'en_attente'}">${formatAssuranceStatut(a?.statut_assurance)}</span>`;

    modal.classList.remove('hidden');
}

function renderReparationRows() {
    const selectedId = reparationSinistreSelect?.value;
    const list = selectedId ? state.sinistres.filter(s => Number(s.id) === Number(selectedId)) : state.sinistres;
    const rows = list.flatMap(s => (s.reparations || []).map(r => `
        <tr data-sinistre-id="${s.id}" data-reparation-id="${r.id}">
            <td><span class="numero-badge">${s.numero_sinistre}</span></td>
            <td>${r.garage || '-'}</td>
            <td>${formatReparationType(r.type_reparation)}</td>
            <td>${formatDate(r.date_debut)}</td>
            <td>${formatDate(r.date_fin_prevue)}</td>
            <td>${formatCurrency(r.cout_reparation)}</td>
            <td><span class="pill ${r.statut_reparation || ''}">${formatReparationStatut(r.statut_reparation)}</span></td>
            <td class="action-btns">
                <button class="action-btn view" data-reparation-action="view" type="button" title="Voir détails">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                </button>
                <button class="action-btn edit" data-reparation-action="edit" type="button" title="Modifier">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                </button>
                <button class="action-btn delete" data-reparation-action="delete" type="button" title="Supprimer">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                </button>
            </td>
        </tr>
    `));

    reparationTableBody.innerHTML = rows.join('') || '<tr><td colspan="8" class="muted" style="text-align:center;padding:var(--space-6);">Aucune réparation enregistrée.</td></tr>';
}

function renderReparationDetail(sinistre, reparation) {
    const modal = document.getElementById('reparation-detail-modal');
    if (!modal || !reparation) return;

    document.getElementById('reparation-detail-sinistre').textContent = sinistre?.numero_sinistre || 'Sinistre';
    document.getElementById('reparation-detail-garage').textContent = reparation.garage || '-';
    document.getElementById('reparation-detail-type').textContent = formatReparationType(reparation.type_reparation);
    document.getElementById('reparation-detail-date-debut').textContent = formatDate(reparation.date_debut);
    document.getElementById('reparation-detail-date-fin-prevue').textContent = formatDate(reparation.date_fin_prevue);
    document.getElementById('reparation-detail-date-fin-reelle').textContent = formatDate(reparation.date_fin_reelle);
    document.getElementById('reparation-detail-cout').textContent = formatCurrency(reparation.cout_reparation);
    document.getElementById('reparation-detail-prise-en-charge').textContent = reparation.prise_en_charge === 'assurance' ? 'Assurance' : 'Société';
    document.getElementById('reparation-detail-statut').innerHTML = `<span class="pill ${reparation.statut_reparation || ''}">${formatReparationStatut(reparation.statut_reparation)}</span>`;
    document.getElementById('reparation-detail-facture').textContent = reparation.facture_reference || '-';

    modal.classList.remove('hidden');
}

function renderTypeChart() {
    const chartContainer = document.getElementById('stats-type-chart');
    if (!chartContainer) return;
    
    // Count sinistres by type
    const typeCounts = {};
    state.sinistres.forEach(s => {
        const type = s.type_sinistre || 'autre';
        typeCounts[type] = (typeCounts[type] || 0) + 1;
    });
    
    const typeLabels = {
        accident: { label: 'Accident', color: '#ef4444' },
        panne: { label: 'Panne', color: '#f59e0b' },
        vol: { label: 'Vol', color: '#8b5cf6' },
        incendie: { label: 'Incendie', color: '#f97316' },
        autre: { label: 'Autre', color: '#6b7280' }
    };
    
    const total = Object.values(typeCounts).reduce((a, b) => a + b, 0);
    
    if (total === 0) {
        chartContainer.innerHTML = '<div class="chart-empty"><span class="muted-small">Aucune donnée disponible</span></div>';
        return;
    }
    
    const bars = Object.entries(typeCounts).map(([type, count]) => {
        const info = typeLabels[type] || { label: type, color: '#6b7280' };
        const percentage = ((count / total) * 100).toFixed(1);
        return `
            <div class="chart-bar-item">
                <div class="chart-bar-header">
                    <span class="chart-bar-label">${info.label}</span>
                    <span class="chart-bar-value">${count} (${percentage}%)</span>
                </div>
                <div class="chart-bar-track">
                    <div class="chart-bar-fill" style="width: ${percentage}%; background: ${info.color};"></div>
                </div>
            </div>
        `;
    }).join('');
    
    chartContainer.innerHTML = `<div class="chart-bars">${bars}</div>`;
}

function renderStats() {
    const stats = state.sinistreStats || {};
    
    // Calculate total from sinistres data if stats are empty
    const totalFromStats = (stats.par_periode || []).reduce((acc, p) => acc + (p.total || 0), 0);
    const total = totalFromStats || state.sinistres.length;
    statsTotalSinistres.textContent = total || '0';
    
    const taux = (stats.taux_prise_en_charge_moyen || 0) * 100;
    statsTauxPrise.textContent = `${taux ? taux.toFixed(1) : '0'} %`;
    
    // Calculate total cost
    const statsCoutTotal = document.getElementById('stats-cout-total');
    if (statsCoutTotal) {
        const totalCout = state.sinistres.reduce((acc, s) => acc + (parseFloat(s.cout_total) || 0), 0);
        statsCoutTotal.textContent = formatCurrency(totalCout);
    }
    
    // Render type distribution chart
    renderTypeChart();
    
    // Calculate most sinistered vehicle from local data
    const vehicleSinistres = {};
    state.sinistres.forEach(s => {
        const vId = s.vehicule_id;
        const vLabel = s.vehicule?.numero || `Véhicule ${vId}`;
        if (!vehicleSinistres[vId]) {
            vehicleSinistres[vId] = { label: vLabel, count: 0 };
        }
        vehicleSinistres[vId].count++;
    });
    
    const sortedVehicles = Object.values(vehicleSinistres).sort((a, b) => b.count - a.count);
    if (sortedVehicles.length > 0 && statsVehiculesPlus) {
        statsVehiculesPlus.textContent = sortedVehicles[0].label;
    } else if (statsVehiculesPlus) {
        statsVehiculesPlus.textContent = '-';
    }
}

// Modals helpers
function openSinistreModal(mode = 'create', sinistre = null) {
    state.sinistreEditingId = mode === 'edit' && sinistre ? sinistre.id : null;
    sinistreForm.reset();
    populateVehiculeOptions(sinistreVehiculeSelect);
    populateChauffeurOptions(sinistreChauffeurSelect);
    if (sinistreStatutHidden) sinistreStatutHidden.value = 'declare';
    if (sinistreStatutSelect) sinistreStatutSelect.value = 'declare';
    if (sinistreNumeroInput) sinistreNumeroInput.value = 'Génération...';
    if (sinistreDateInput) sinistreDateInput.value = toInputDate(new Date());
    if (sinistreHeureInput) {
        const now = new Date();
        sinistreHeureInput.value = String(now.getHours()).padStart(2, '0') + ':' + String(now.getMinutes()).padStart(2, '0');
    }
    // generate numero locally based on current year and existing sinistres
    try {
        const year = new Date().getFullYear();
        const matches = (state.sinistres || []).map(s => s.numero_sinistre || '').filter(Boolean);
        let maxIndex = 0;
        matches.forEach(n => {
            const m = n.match(/(\d{4})-(\d{3,})$/);
            if (m && Number(m[1]) === year) maxIndex = Math.max(maxIndex, Number(m[2]));
        });
        const next = String(maxIndex + 1).padStart(3, '0');
        const generated = `SIN-${year}-${next}`;
        if (sinistreNumeroInput) sinistreNumeroInput.value = generated;
    } catch (e) {
        if (sinistreNumeroInput) sinistreNumeroInput.value = '';
    }

    if (mode === 'edit' && sinistre) {
        sinistreFormTitle.textContent = 'Modifier un sinistre';
        sinistreFormSubmit.textContent = 'Mettre à jour';
        if (sinistreNumeroInput) sinistreNumeroInput.value = sinistre.numero_sinistre || '';
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
            if (sinistreStatutSelect) sinistreStatutSelect.value = sinistre.statut_sinistre;
            if (sinistreStatutHidden) sinistreStatutHidden.value = sinistre.statut_sinistre;
        }
    } else {
        sinistreFormTitle.textContent = 'Déclarer un sinistre';
        sinistreFormSubmit.textContent = 'Enregistrer';
        sinistreForm.gravite.value = 'mineur';
        sinistreForm.type_sinistre.value = 'accident';
        sinistreForm.responsable.value = 'inconnu';
        if (sinistreStatutSelect) sinistreStatutSelect.value = 'declare';
        if (sinistreStatutHidden) sinistreStatutHidden.value = 'declare';
    }

    sinistreModal.classList.remove('hidden');
}

function closeSinistreModal() {
    state.sinistreEditingId = null;
    sinistreModal.classList.add('hidden');
}

function openAssuranceModal(sinistreId = '', isEdit = false) {
    state.assuranceEditingId = null;
    assuranceForm.reset();
    populateSinistreSelects();
    
    // Generate numero dossier for new insurance
    const generateNumeroDossier = () => {
        const year = new Date().getFullYear();
        const existingNumbers = state.sinistres
            .filter(s => s.assurance?.numero_dossier)
            .map(s => s.assurance.numero_dossier);
        let maxIndex = 0;
        existingNumbers.forEach(n => {
            const m = n.match(/ASS-(\d{4})-(\d+)$/);
            if (m && Number(m[1]) === year) {
                maxIndex = Math.max(maxIndex, Number(m[2]));
            }
        });
        return `ASS-${year}-${String(maxIndex + 1).padStart(3, '0')}`;
    };

    const sinistre = state.sinistres.find(s => Number(s.id) === Number(sinistreId));
    
    if (sinistre?.assurance) {
        // Edit mode
        const a = sinistre.assurance;
        state.assuranceEditingId = a.id;
        assuranceFormTitle.textContent = 'Mettre à jour l\'assurance';
        
        // In edit mode, show sinistre but disable select
        if (assuranceSinistreSelectModal) {
            assuranceSinistreSelectModal.innerHTML = `<option value="${sinistre.id}" selected>${sinistreLabel(sinistre)}</option>`;
            assuranceSinistreSelectModal.disabled = true;
        }
        if (assuranceSinistreInput) assuranceSinistreInput.value = sinistreId;
        
        // Fill form values
        assuranceForm.compagnie_assurance.value = a.compagnie_assurance || '';
        if (assuranceNumeroDossier) assuranceNumeroDossier.value = a.numero_dossier || '';
        assuranceForm.date_declaration.value = toInputDate(a.date_declaration);
        assuranceForm.expert_nom.value = a.expert_nom || '';
        assuranceForm.date_expertise.value = toInputDate(a.date_expertise);
        
        // Decision is editable in edit mode
        if (assuranceDecisionSelect) {
            assuranceDecisionSelect.value = a.decision || 'en_attente';
            assuranceDecisionSelect.disabled = false;
        }
        if (assuranceDecisionHelp) assuranceDecisionHelp.style.display = 'none';
        
        assuranceForm.montant_pris_en_charge.value = a.montant_pris_en_charge || '';
        assuranceForm.franchise.value = a.franchise || '';
        assuranceForm.date_validation.value = toInputDate(a.date_validation);
        
        // Status is auto-updated based on decision
        if (assuranceStatutSelect) assuranceStatutSelect.value = a.statut_assurance || 'en_cours';
        if (assuranceStatutHidden) assuranceStatutHidden.value = a.statut_assurance || 'en_cours';
    } else {
        // Create mode
        assuranceFormTitle.textContent = 'Déclarer Assurance';
        
        // Enable sinistre selection
        if (assuranceSinistreSelectModal) {
            assuranceSinistreSelectModal.disabled = false;
            if (sinistreId) {
                assuranceSinistreSelectModal.value = sinistreId;
            }
        }
        if (assuranceSinistreInput) assuranceSinistreInput.value = sinistreId || '';
        
        // Auto-generate numero dossier
        if (assuranceNumeroDossier) assuranceNumeroDossier.value = generateNumeroDossier();
        
        // Auto-fill date déclaration with today's date
        if (assuranceForm.date_declaration) {
            assuranceForm.date_declaration.value = toInputDate(new Date());
        }
        
        // Decision defaults to "En attente" but is editable
        if (assuranceDecisionSelect) {
            assuranceDecisionSelect.value = 'en_attente';
            assuranceDecisionSelect.disabled = false;
        }
        if (assuranceDecisionHelp) assuranceDecisionHelp.style.display = '';
        
        // Status is locked to "En cours" at creation
        if (assuranceStatutSelect) assuranceStatutSelect.value = 'en_cours';
        if (assuranceStatutHidden) assuranceStatutHidden.value = 'en_cours';
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
    
    // RÈGLE: En mode création, vérifier que l'assurance a une décision (acceptée ou refusée)
    if (mode === 'create' && sinistreId) {
        const sinistre = state.sinistres.find(s => Number(s.id) === Number(sinistreId));
        if (sinistre && sinistre.assurance && sinistre.assurance.decision === 'en_attente') {
            showToast('Veuillez attendre la décision de l\'assurance avant de créer une réparation.', 'warning', { title: 'Action impossible' });
            return;
        }
    }
    
    // Function to determine prise en charge based on sinistre's assurance decision
    const determinePriseEnCharge = (sId) => {
        const sinistre = state.sinistres.find(s => Number(s.id) === Number(sId));
        if (!sinistre) return 'societe';
        
        const assurance = sinistre.assurance;
        // If no assurance, or assurance is refused -> societe
        if (!assurance || assurance.decision === 'refuse') {
            return 'societe';
        }
        // If assurance is accepted -> assurance
        if (assurance.decision === 'accepte') {
            return 'assurance';
        }
        return 'societe';
    };

    if (mode === 'edit' && reparation) {
        reparationFormTitle.textContent = 'Mettre à jour la réparation';
        reparationForm.garage.value = reparation.garage || '';
        reparationForm.type_reparation.value = reparation.type_reparation || '';
        reparationForm.date_debut.value = toInputDate(reparation.date_debut);
        reparationForm.date_fin_prevue.value = toInputDate(reparation.date_fin_prevue);
        reparationForm.date_fin_reelle.value = toInputDate(reparation.date_fin_reelle);
        reparationForm.cout_reparation.value = reparation.cout_reparation || '';
        reparationForm.facture_reference.value = reparation.facture_reference || '';
        
        // In edit mode, fields are editable
        if (reparationPriseEnChargeSelect) {
            reparationPriseEnChargeSelect.value = reparation.prise_en_charge || 'societe';
            reparationPriseEnChargeSelect.disabled = false;
            reparationPriseEnChargeSelect.name = 'prise_en_charge';
        }
        if (reparationPriseEnChargeHidden) reparationPriseEnChargeHidden.disabled = true;
        if (reparationPriseEnChargeHelp) reparationPriseEnChargeHelp.style.display = 'none';
        
        if (reparationStatutSelect) {
            reparationStatutSelect.value = reparation.statut_reparation || 'en_cours';
            reparationStatutSelect.disabled = false;
            reparationStatutSelect.name = 'statut_reparation';
        }
        if (reparationStatutHidden) reparationStatutHidden.disabled = true;
        if (reparationStatutHelp) reparationStatutHelp.style.display = 'none';
    } else {
        reparationFormTitle.textContent = 'Ajouter une réparation';
        
        // Status defaults to "En cours" but is editable
        if (reparationStatutSelect) {
            reparationStatutSelect.value = 'en_cours';
            reparationStatutSelect.disabled = false;
            reparationStatutSelect.name = 'statut_reparation';
        }
        if (reparationStatutHidden) {
            reparationStatutHidden.disabled = true;
        }
        if (reparationStatutHelp) reparationStatutHelp.style.display = '';
        
        // Prise en charge defaults based on assurance status but is editable
        const priseEnCharge = determinePriseEnCharge(sinistreId);
        if (reparationPriseEnChargeSelect) {
            reparationPriseEnChargeSelect.value = priseEnCharge;
            reparationPriseEnChargeSelect.disabled = false;
            reparationPriseEnChargeSelect.name = 'prise_en_charge';
        }
        if (reparationPriseEnChargeHidden) {
            reparationPriseEnChargeHidden.disabled = true;
        }
        if (reparationPriseEnChargeHelp) reparationPriseEnChargeHelp.style.display = '';
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
    renderStats();
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

    // Detail modal close handlers
    closeSinistreDetailModalBtn?.addEventListener('click', () => {
        sinistreDetailModal?.classList.add('hidden');
    });
    sinistreDetailModal?.querySelector('.modal-backdrop')?.addEventListener('click', () => {
        sinistreDetailModal?.classList.add('hidden');
    });

    sinistreTableBody?.addEventListener('click', async (e) => {
        const row = e.target.closest('tr[data-sinistre-id]');
        if (!row) return;
        const btn = e.target.closest('button');
        if (btn && (btn.disabled || btn.classList.contains('disabled'))) return;
        const id = row.dataset.sinistreId;
        const sinistre = state.sinistres.find(s => Number(s.id) === Number(id));
        const action = e.target.dataset.action || btn?.dataset?.action;

        if (action === 'view') {
            e.stopPropagation();
            state.selectedSinistreId = Number(id);
            renderSinistreDetail(sinistre);
            sinistreDetailModal?.classList.remove('hidden');
            return;
        }
        if (action === 'edit') {
            e.stopPropagation();
            // Prevent editing closed sinistres
            if (sinistre.statut_sinistre === 'clos') {
                showToast('Ce sinistre est clôturé et ne peut plus être modifié.', 'warning', { title: 'Modification impossible' });
                return;
            }
            openSinistreModal('edit', sinistre);
            return;
        }
        
        if (action === 'delete') {
            e.stopPropagation();
            const hasRelated = sinistre.assurance || (sinistre.reparations && sinistre.reparations.length > 0);
            const confirmed = await showConfirm({
                title: 'Supprimer le sinistre ?',
                message: hasRelated 
                    ? `Êtes-vous sûr de vouloir supprimer le sinistre <strong>${sinistre.numero_sinistre || ''}</strong> ? Cela supprimera également l'assurance et les réparations associées.`
                    : `Êtes-vous sûr de vouloir supprimer le sinistre <strong>${sinistre.numero_sinistre || ''}</strong> ?`,
                confirmText: 'Supprimer',
                cancelText: 'Annuler',
                type: 'danger'
            });
            if (!confirmed) return;
            try {
                await axios.delete(`/api/sinistres/${id}`);
                await loadSinistres();
                showToast(`Le sinistre ${sinistre.numero_sinistre || ''} a été supprimé avec succès.`, 'success', { title: 'Sinistre supprimé' });
            } catch (err) {
                showToast(extractErrorMessage(err), 'error', { title: 'Échec de la suppression' });
            }
            return;
        }

        if (action === 'close') {
            e.stopPropagation();
            const confirmed = await showConfirm({
                title: 'Clôturer le sinistre ?',
                message: `Confirmez-vous la clôture du sinistre <strong>${sinistre.numero_sinistre || ''}</strong> ?`,
                confirmText: 'Clôturer',
                cancelText: 'Annuler',
                type: 'info'
            });
            if (!confirmed) return;
            try {
                await axios.put(`/api/sinistres/${id}`, { statut_sinistre: 'clos' });
                await loadSinistres();
                showToast('Sinistre clôturé avec succès.', 'success', { title: 'Clôture effectuée' });
            } catch (err) {
                showToast(extractErrorMessage(err), 'error', { title: 'Échec de la clôture' });
            }
            return;
        }

        state.selectedSinistreId = Number(id);
        renderSinistreDetail(sinistre);
    });

    sinistreForm?.addEventListener('submit', async (e) => {
        e.preventDefault();
        ensureAuth();
        const payload = Object.fromEntries(new FormData(sinistreForm).entries());
        if (sinistreStatutHidden) {
            payload.statut_sinistre = sinistreStatutHidden.value || 'declare';
        }
        if (!state.sinistreEditingId) {
            // If frontend generated a number, include it; otherwise let backend generate
            if (sinistreNumeroInput && sinistreNumeroInput.value && sinistreNumeroInput.value.trim() !== '' && sinistreNumeroInput.value.trim() !== 'Génération...') {
                payload.numero_sinistre = sinistreNumeroInput.value.trim();
            } else {
                delete payload.numero_sinistre;
            }
            if (!payload.date_sinistre && sinistreDateInput) {
                payload.date_sinistre = toInputDate(new Date());
            }
            if (!payload.heure_sinistre && sinistreHeureInput) {
                payload.heure_sinistre = sinistreHeureInput.value || '';
            }
        }
        try {
            if (state.sinistreEditingId) {
                await axios.put(`/api/sinistres/${state.sinistreEditingId}`, payload);
            } else {
                await axios.post('/api/sinistres', payload);
            }
            await loadSinistres();
            const action = state.sinistreEditingId ? 'mis à jour' : 'créé';
            showToast(`Le sinistre a été ${action} avec succès.`, 'success', { title: state.sinistreEditingId ? 'Sinistre modifié' : 'Sinistre créé' });
        } catch (err) {
            showToast(extractErrorMessage(err), 'error', { title: 'Échec de l\'enregistrement' });
        } finally {
            closeSinistreModal();
        }
    });

    openSinistreModalBtn?.addEventListener('click', () => openSinistreModal('create'));
    closeSinistreModalBtn?.addEventListener('click', closeSinistreModal);
    document.getElementById('cancel-sinistre-form')?.addEventListener('click', closeSinistreModal);
    sinistreModal?.addEventListener('click', (e) => {
        if (e.target.dataset.close === 'sinistre-modal') closeSinistreModal();
    });

    assuranceTableBody?.addEventListener('click', async (e) => {
        const btn = e.target.closest('[data-assurance-action]');
        if (!btn) return;
        const row = btn.closest('tr[data-sinistre-id]');
        const sinistreId = row?.dataset.sinistreId;
        const assuranceId = row?.dataset.assuranceId;
        const sinistre = state.sinistres.find(s => Number(s.id) === Number(sinistreId));
        
        if (btn.dataset.assuranceAction === 'view') {
            renderAssuranceDetail(sinistre);
            return;
        }
        if (btn.dataset.assuranceAction === 'edit') {
            openAssuranceModal(sinistreId, true);
            return;
        }
        if (btn.dataset.assuranceAction === 'delete') {
            const confirmed = await showConfirm({
                title: 'Supprimer l\'assurance ?',
                message: 'Êtes-vous sûr de vouloir supprimer cette déclaration d\'assurance ? Cette action est irréversible.',
                confirmText: 'Supprimer',
                cancelText: 'Annuler',
                type: 'danger'
            });
            if (!confirmed) return;
            try {
                await axios.delete(`/api/assurance-sinistres/${assuranceId}`);
                await loadSinistres();
                showToast('La déclaration d\'assurance a été supprimée avec succès.', 'success', { title: 'Assurance supprimée' });
            } catch (err) {
                showToast(extractErrorMessage(err), 'error', { title: 'Échec de la suppression' });
            }
            return;
        }
    });
    
    // Assurance detail modal close
    const assuranceDetailModal = document.getElementById('assurance-detail-modal');
    document.getElementById('close-assurance-detail-modal')?.addEventListener('click', () => {
        assuranceDetailModal?.classList.add('hidden');
    });
    assuranceDetailModal?.querySelector('.modal-backdrop')?.addEventListener('click', () => {
        assuranceDetailModal?.classList.add('hidden');
    });

    openAssuranceModalBtn?.addEventListener('click', () => {
        openAssuranceModal('', false);
    });
    closeAssuranceModalBtn?.addEventListener('click', closeAssuranceModal);
    document.getElementById('cancel-assurance-form')?.addEventListener('click', closeAssuranceModal);
    assuranceModal?.addEventListener('click', (e) => {
        if (e.target.dataset.close === 'assurance-modal') closeAssuranceModal();
    });
    
    // Sync assurance sinistre select with hidden input
    assuranceSinistreSelectModal?.addEventListener('change', function() {
        if (assuranceSinistreInput) assuranceSinistreInput.value = this.value;
    });

    assuranceForm?.addEventListener('submit', async (e) => {
        e.preventDefault();
        ensureAuth();
        const formData = new FormData(assuranceForm);
        const payload = {};
        
        // Get sinistre_id from the modal select or hidden input
        const sinistreIdFromSelect = assuranceSinistreSelectModal?.value;
        const sinistreIdFromHidden = assuranceSinistreInput?.value;
        payload.sinistre_id = sinistreIdFromSelect || sinistreIdFromHidden;
        
        // Add other form fields
        for (const [key, value] of formData.entries()) {
            if (key !== 'sinistre_id' && key !== 'sinistre_id_select') {
                payload[key] = value;
            }
        }
        
        // For new assurances, don't send decision and statut (they are auto-set by backend)
        if (!state.assuranceEditingId) {
            delete payload.decision;
            delete payload.statut_assurance;
        }
        
        try {
            if (state.assuranceEditingId) {
                await axios.put(`/api/assurance-sinistres/${state.assuranceEditingId}`, payload);
            } else {
                await axios.post('/api/assurance-sinistres', payload);
            }
            await loadSinistres();
            const action = state.assuranceEditingId ? 'mis à jour' : 'créé';
            showToast(`Le dossier assurance a été ${action} avec succès.`, 'success', { title: state.assuranceEditingId ? 'Assurance modifiée' : 'Assurance créée' });
        } catch (err) {
            showToast(extractErrorMessage(err), 'error', { title: 'Échec de l\'enregistrement' });
        } finally {
            closeAssuranceModal();
        }
    });

    reparationTableBody?.addEventListener('click', async (e) => {
        const btn = e.target.closest('[data-reparation-action]');
        if (!btn) return;
        const row = btn.closest('tr[data-reparation-id]');
        const sinistreId = row?.dataset.sinistreId;
        const reparationId = row?.dataset.reparationId;
        const sinistre = state.sinistres.find(s => Number(s.id) === Number(sinistreId));
        const repar = sinistre?.reparations?.find(r => Number(r.id) === Number(reparationId));

        if (btn.dataset.reparationAction === 'view') {
            renderReparationDetail(sinistre, repar);
            return;
        }
        if (btn.dataset.reparationAction === 'edit') {
            openReparationModal('edit', sinistreId, repar);
            return;
        }
        if (btn.dataset.reparationAction === 'delete') {
            const confirmed = await showConfirm({
                title: 'Supprimer la réparation ?',
                message: 'Êtes-vous sûr de vouloir supprimer cette réparation ? Cette action est irréversible.',
                confirmText: 'Supprimer',
                cancelText: 'Annuler',
                type: 'danger'
            });
            if (!confirmed) return;
            try {
                await axios.delete(`/api/reparation-sinistres/${reparationId}`);
                await loadSinistres();
                showToast('La réparation a été supprimée avec succès.', 'success', { title: 'Réparation supprimée' });
            } catch (err) {
                showToast(extractErrorMessage(err), 'error', { title: 'Échec de la suppression' });
            }
        }
    });

    openReparationModalBtn?.addEventListener('click', () => {
        const selected = reparationSinistreSelect?.value || '';
        openReparationModal('create', selected);
    });
    closeReparationModalBtn?.addEventListener('click', closeReparationModal);
    document.getElementById('cancel-reparation-form')?.addEventListener('click', closeReparationModal);
    reparationModal?.addEventListener('click', (e) => {
        if (e.target.dataset.close === 'reparation-modal') closeReparationModal();
    });
    
    // Reparation detail modal close
    const reparationDetailModal = document.getElementById('reparation-detail-modal');
    document.getElementById('close-reparation-detail-modal')?.addEventListener('click', () => {
        reparationDetailModal?.classList.add('hidden');
    });
    reparationDetailModal?.querySelector('.modal-backdrop')?.addEventListener('click', () => {
        reparationDetailModal?.classList.add('hidden');
    });

    reparationForm?.addEventListener('submit', async (e) => {
        e.preventDefault();
        ensureAuth();
        const formData = new FormData(reparationForm);
        const payload = {};
        
        // Build payload, excluding display-only fields
        for (const [key, value] of formData.entries()) {
            if (!key.endsWith('_display')) {
                payload[key] = value;
            }
        }
        
        // For new reparations, ensure statut and prise_en_charge come from hidden inputs
        if (!state.reparationEditingId) {
            if (reparationStatutHidden && !reparationStatutHidden.disabled) {
                payload.statut_reparation = reparationStatutHidden.value;
            }
            if (reparationPriseEnChargeHidden && !reparationPriseEnChargeHidden.disabled) {
                payload.prise_en_charge = reparationPriseEnChargeHidden.value;
            }
        }
        
        try {
            if (state.reparationEditingId) {
                await axios.put(`/api/reparation-sinistres/${state.reparationEditingId}`, payload);
            } else {
                await axios.post('/api/reparation-sinistres', payload);
            }
            await loadSinistres();
            const action = state.reparationEditingId ? 'mise à jour' : 'créée';
            showToast(`La réparation a été ${action} avec succès.`, 'success', { title: state.reparationEditingId ? 'Réparation modifiée' : 'Réparation créée' });
        } catch (err) {
            showToast(extractErrorMessage(err), 'error', { title: 'Échec de l\'enregistrement' });
        } finally {
            closeReparationModal();
        }
    });

    reparationSinistreSelect?.addEventListener('change', renderReparationRows);
    
    // Update prise en charge when sinistre changes in reparation modal (for create mode)
    reparationSinistreSelectModal?.addEventListener('change', function() {
        // Only update if we're in create mode (no editing id)
        if (!state.reparationEditingId) {
            const sId = this.value;
            const sinistre = state.sinistres.find(s => Number(s.id) === Number(sId));
            let priseEnCharge = 'societe';
            
            if (sinistre) {
                const assurance = sinistre.assurance;
                // If no assurance, or assurance is refused, or assurance is en_cours -> societe
                if (assurance && assurance.statut_assurance === 'valide') {
                    priseEnCharge = 'assurance';
                }
            }
            
            if (reparationPriseEnChargeSelect) reparationPriseEnChargeSelect.value = priseEnCharge;
            if (reparationPriseEnChargeHidden) reparationPriseEnChargeHidden.value = priseEnCharge;
        }
    });

    refreshStatsBtn?.addEventListener('click', () => {
        loadSinistreStats({
            date_start: statsDateStart?.value || undefined,
            date_end: statsDateEnd?.value || undefined,
        });
    });
}
