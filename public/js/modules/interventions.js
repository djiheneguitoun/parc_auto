// ============================================================================
// Interventions Management (Entretien & Réparation)
// ============================================================================

import { state, showToast, showConfirm, extractErrorMessage } from './state.js';
import { ensureAuth } from './auth.js';
import { formatDate, formatCurrency, toInputDate } from './utils.js';

// ============================================================================
// DOM Elements
// ============================================================================

// Panels
const interventionPanels = document.querySelectorAll('[data-intervention-panel]');

// Table
const interventionTableBody = document.getElementById('intervention-rows');
const interventionFilterType = document.getElementById('intervention-filter-type');
const interventionFilterVehicule = document.getElementById('intervention-filter-vehicule');
const interventionFilterCategorie = document.getElementById('intervention-filter-categorie');

// Modal Intervention
const interventionModal = document.getElementById('intervention-modal');
const interventionForm = document.getElementById('intervention-form');
const interventionFormTitle = document.getElementById('intervention-form-title');
const openInterventionModalBtn = document.getElementById('open-intervention-modal');
const closeInterventionModalBtn = document.getElementById('close-intervention-modal');
const cancelInterventionFormBtn = document.getElementById('cancel-intervention-form');
const interventionVehiculeSelect = document.getElementById('intervention-vehicule-select');
const interventionTypeSelect = document.getElementById('intervention-type-select');
const interventionOperationSelect = document.getElementById('intervention-operation-select');

// Modal Catégorie
const categorieModal = document.getElementById('categorie-modal');
const categorieForm = document.getElementById('categorie-form');
const categorieFormTitle = document.getElementById('categorie-form-title');
const openCategorieModalBtn = document.getElementById('open-categorie-modal');
const closeCategorieModalBtn = document.getElementById('close-categorie-modal');
const cancelCategorieFormBtn = document.getElementById('cancel-categorie-form');

// Modal Opération
const operationModal = document.getElementById('operation-modal');
const operationForm = document.getElementById('operation-form');
const operationFormTitle = document.getElementById('operation-form-title');
const openOperationModalBtn = document.getElementById('open-operation-modal');
const closeOperationModalBtn = document.getElementById('close-operation-modal');
const cancelOperationFormBtn = document.getElementById('cancel-operation-form');
const operationTypeSelect = document.getElementById('operation-type-select');
const operationCategorieSelect = document.getElementById('operation-categorie-select');

// Modal Détail
const interventionDetailModal = document.getElementById('intervention-detail-modal');
const closeInterventionDetailModalBtn = document.getElementById('close-intervention-detail-modal');

// Tables catalogue
const categoriesRows = document.getElementById('categories-rows');
const operationsRows = document.getElementById('operations-rows');
const catalogueFilterType = document.getElementById('catalogue-filter-type');

// Alertes
const alertesRows = document.getElementById('alertes-rows');
const refreshAlertesBtn = document.getElementById('refresh-alertes');

// Stats
const refreshInterventionStatsBtn = document.getElementById('refresh-intervention-stats');
const applyInterventionStatsFiltersBtn = document.getElementById('apply-intervention-stats-filters');
const interventionStatsDateStart = document.getElementById('intervention-stats-date-start');
const interventionStatsDateEnd = document.getElementById('intervention-stats-date-end');

// ============================================================================
// State
// ============================================================================

let interventions = [];
let types = [];
let categories = [];
let operations = [];
let suivis = [];
let editingInterventionId = null;
let editingCategorieId = null;
let editingOperationId = null;

// Chart instances
let chartInterventionType = null;
let chartCoutCategorie = null;
let chartCoutVehiculeInterv = null;
let chartEvolutionMensuelle = null;

const chartPalette = ['#1e3a5f', '#1e9e6d', '#f4b000', '#d9534f', '#5a6c90', '#7a3ff2', '#e91e63', '#00bcd4'];

// ============================================================================
// Helpers
// ============================================================================

function formatInterventionType(code) {
    const map = { ENT: 'Entretien', REP: 'Réparation' };
    return map[code] || code || '-';
}

function getTypeClass(code) {
    return code === 'ENT' ? 'pill-success' : 'pill-warning';
}

function vehiculeLabel(v) {
    if (!v) return '-';
    return v.numero || v.code || `${v.marque || ''} ${v.modele || ''}`.trim() || `Véhicule ${v.id}`;
}

// ============================================================================
// API Functions
// ============================================================================

async function fetchTypes() {
    try {
        const res = await axios.get('/api/interventions/types');
        types = res.data;
        return types;
    } catch (err) {
        console.error('Erreur chargement types:', err);
        return [];
    }
}

async function fetchCategories() {
    try {
        const res = await axios.get('/api/interventions/categories');
        categories = res.data;
        return categories;
    } catch (err) {
        console.error('Erreur chargement catégories:', err);
        return [];
    }
}

async function fetchOperations(typeCode = null) {
    try {
        const params = typeCode ? { type_code: typeCode } : {};
        const res = await axios.get('/api/interventions/operations', { params });
        operations = res.data;
        return operations;
    } catch (err) {
        console.error('Erreur chargement opérations:', err);
        return [];
    }
}

async function fetchInterventions() {
    try {
        const params = {};
        if (interventionFilterType?.value) params.type_code = interventionFilterType.value;
        if (interventionFilterVehicule?.value) params.vehicule_id = interventionFilterVehicule.value;
        if (interventionFilterCategorie?.value) params.categorie_id = interventionFilterCategorie.value;
        
        const res = await axios.get('/api/interventions', { params });
        interventions = res.data.data || res.data;
        return interventions;
    } catch (err) {
        console.error('Erreur chargement interventions:', err);
        return [];
    }
}

async function fetchAlertes() {
    try {
        const res = await axios.get('/api/interventions/alertes', { params: { jours: 30 } });
        return res.data;
    } catch (err) {
        console.error('Erreur chargement alertes:', err);
        return { proches: [], depassees: [], total_alertes: 0 };
    }
}

async function fetchSuivis() {
    try {
        const res = await axios.get('/api/interventions/suivis');
        suivis = res.data;
        return suivis;
    } catch (err) {
        console.error('Erreur chargement suivis:', err);
        return [];
    }
}

async function fetchStats(dateStart = null, dateEnd = null) {
    try {
        const params = {};
        if (dateStart) params.date_start = dateStart;
        if (dateEnd) params.date_end = dateEnd;
        
        const res = await axios.get('/api/interventions/stats', { params });
        return res.data;
    } catch (err) {
        console.error('Erreur chargement stats:', err);
        return null;
    }
}

// ============================================================================
// Render Functions
// ============================================================================

function renderInterventionsTable() {
    if (!interventionTableBody) return;
    
    const countEl = document.querySelector('#interventions-count .count');
    if (countEl) countEl.textContent = interventions.length;
    
    if (!interventions.length) {
        interventionTableBody.innerHTML = `
            <tr><td colspan="8" class="empty-state">
                <p>Aucune intervention enregistrée</p>
            </td></tr>`;
        return;
    }
    
    interventionTableBody.innerHTML = interventions.map(i => {
        const op = i.operation || {};
        const type = op.type || {};
        const cat = op.categorie || {};
        const v = i.vehicule || {};
        
        return `
            <tr data-id="${i.id}">
                <td>${formatDate(i.date_intervention)}</td>
                <td>${vehiculeLabel(v)}</td>
                <td><span class="pill ${getTypeClass(type.code)}">${formatInterventionType(type.code)}</span></td>
                <td title="${op.libelle || '-'}">${(op.libelle || '-').substring(0, 35)}${(op.libelle || '').length > 35 ? '...' : ''}</td>
                <td>${cat.code || '-'}</td>
                <td>${i.kilometrage ? i.kilometrage.toLocaleString() : '-'}</td>
                <td>${formatCurrency(i.cout)}</td>
                <td>
                    <div class="action-btns">
                        <button class="action-btn view" title="Voir" data-action="view" data-id="${i.id}">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        </button>
                        <button class="action-btn edit" title="Modifier" data-action="edit" data-id="${i.id}">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"/></svg>
                        </button>
                        <button class="action-btn delete" title="Supprimer" data-action="delete" data-id="${i.id}">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                        </button>
                    </div>
                </td>
            </tr>`;
    }).join('');
}

function renderCategoriesTable() {
    if (!categoriesRows) return;
    
    const countEl = document.querySelector('#categories-count .count');
    if (countEl) countEl.textContent = categories.length;
    
    if (!categories.length) {
        categoriesRows.innerHTML = `<tr><td colspan="5" class="empty-state">Aucune catégorie</td></tr>`;
        return;
    }
    
    categoriesRows.innerHTML = categories.map(c => {
        const opCount = operations.filter(o => o.categorie_id === c.id).length;
        return `
            <tr data-id="${c.id}">
                <td><code>${c.code}</code></td>
                <td>${c.libelle}</td>
                <td>${opCount}</td>
                <td><span class="pill ${c.actif ? 'pill-success' : 'pill-muted'}">${c.actif ? 'Oui' : 'Non'}</span></td>
                <td>
                    <div class="action-btns">
                        <button class="action-btn edit" title="Modifier" data-action="edit-categorie" data-id="${c.id}">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"/></svg>
                        </button>
                    </div>
                </td>
            </tr>`;
    }).join('');
}

function renderOperationsTable() {
    if (!operationsRows) return;
    
    let filtered = operations;
    if (catalogueFilterType?.value) {
        filtered = operations.filter(o => o.type?.code === catalogueFilterType.value);
    }
    
    const countEl = document.querySelector('#operations-count .count');
    if (countEl) countEl.textContent = filtered.length;
    
    if (!filtered.length) {
        operationsRows.innerHTML = `<tr><td colspan="8" class="empty-state">Aucune opération</td></tr>`;
        return;
    }
    
    operationsRows.innerHTML = filtered.map(o => {
        const type = o.type || {};
        const cat = o.categorie || {};
        return `
            <tr data-id="${o.id}">
                <td><code>${o.code}</code></td>
                <td title="${o.libelle}">${o.libelle.substring(0, 40)}${o.libelle.length > 40 ? '...' : ''}</td>
                <td><span class="pill ${getTypeClass(type.code)}">${formatInterventionType(type.code)}</span></td>
                <td>${cat.code || '-'}</td>
                <td>${o.periodicite_km ? o.periodicite_km.toLocaleString() : '-'}</td>
                <td>${o.periodicite_mois || '-'}</td>
                <td><span class="pill ${o.actif ? 'pill-success' : 'pill-muted'}">${o.actif ? 'Oui' : 'Non'}</span></td>
                <td>
                    <div class="action-btns">
                        <button class="action-btn edit" title="Modifier" data-action="edit-operation" data-id="${o.id}">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"/></svg>
                        </button>
                    </div>
                </td>
            </tr>`;
    }).join('');
}

function renderAlertesTable(alertes) {
    if (!alertesRows) return;
    
    const depasseesEl = document.getElementById('alertes-depassees');
    const prochesEl = document.getElementById('alertes-proches');
    const okEl = document.getElementById('alertes-ok');
    
    if (depasseesEl) depasseesEl.textContent = alertes.depassees?.length || 0;
    if (prochesEl) prochesEl.textContent = alertes.proches?.length || 0;
    
    // Calculer véhicules à jour (tous - avec alertes)
    const vehiculesAvecAlertes = new Set([
        ...(alertes.depassees || []).map(a => a.suivi?.vehicule_id),
        ...(alertes.proches || []).map(a => a.suivi?.vehicule_id)
    ]);
    const totalVehicules = state.vehicules?.length || 0;
    if (okEl) okEl.textContent = Math.max(0, totalVehicules - vehiculesAvecAlertes.size);
    
    const allItems = [
        ...(alertes.depassees || []).map(a => ({ ...a, alertType: 'depassee' })),
        ...(alertes.proches || []).map(a => ({ ...a, alertType: 'proche' }))
    ];
    
    if (!allItems.length) {
        alertesRows.innerHTML = `
            <tr><td colspan="8" class="empty-state">
                <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
                    <polyline points="22 4 12 14.01 9 11.01"/>
                </svg>
                <p>Aucune alerte - Tous les entretiens sont à jour !</p>
            </td></tr>`;
        return;
    }
    
    alertesRows.innerHTML = allItems.map(item => {
        const s = item.suivi || {};
        const v = s.vehicule || {};
        const op = s.operation || {};
        const isDepassee = item.alertType === 'depassee';
        
        const statutClass = isDepassee ? 'pill-danger' : 'pill-warning';
        const statutLabel = isDepassee ? `${item.jours_retard}j de retard` : `Dans ${item.jours_restants}j`;
        
        return `
            <tr>
                <td>${vehiculeLabel(v)}</td>
                <td title="${op.libelle || '-'}">${(op.libelle || '-').substring(0, 30)}...</td>
                <td>${s.dernier_km ? s.dernier_km.toLocaleString() : '-'}</td>
                <td>${formatDate(s.derniere_date)}</td>
                <td>${s.prochaine_echeance_km ? s.prochaine_echeance_km.toLocaleString() : '-'}</td>
                <td>${formatDate(s.prochaine_echeance_date)}</td>
                <td><span class="pill ${statutClass}">${statutLabel}</span></td>
                <td>
                    <button class="btn primary sm" onclick="window.dispatchEvent(new CustomEvent('createInterventionFromAlert', { detail: { vehicule_id: ${s.vehicule_id}, operation_id: ${s.operation_id} } }))">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" x2="12" y1="5" y2="19"/><line x1="5" x2="19" y1="12" y2="12"/></svg>
                        Créer
                    </button>
                </td>
            </tr>`;
    }).join('');
}

// ============================================================================
// Stats & Charts
// ============================================================================

async function loadAndRenderStats() {
    const dateStart = interventionStatsDateStart?.value || null;
    const dateEnd = interventionStatsDateEnd?.value || null;
    
    const stats = await fetchStats(dateStart, dateEnd);
    if (!stats) return;
    
    // KPIs
    const totalEl = document.getElementById('stats-total-interventions');
    const entretiensEl = document.getElementById('stats-total-entretiens');
    const reparationsEl = document.getElementById('stats-total-reparations');
    const coutEl = document.getElementById('stats-cout-total-interv');
    
    if (totalEl) totalEl.textContent = stats.totaux?.interventions || 0;
    
    const entretienData = stats.par_type?.find(t => t.code === 'ENT');
    const reparationData = stats.par_type?.find(t => t.code === 'REP');
    
    if (entretiensEl) entretiensEl.textContent = entretienData?.total || 0;
    if (reparationsEl) reparationsEl.textContent = reparationData?.total || 0;
    if (coutEl) coutEl.textContent = formatCurrency(stats.totaux?.cout_global);
    
    // Chart: Répartition par type
    renderTypeChart(stats.par_type || []);
    
    // Chart: Coût par catégorie
    renderCategorieChart(stats.par_categorie || []);
    
    // Chart: Coût par véhicule
    renderVehiculeChart(stats.cout_par_vehicule || []);
    
    // Chart: Évolution mensuelle
    renderEvolutionChart(stats.par_periode || []);
    
    // Ranking opérations
    renderOperationsRanking(stats.operations_frequentes || []);
}

function renderTypeChart(data) {
    const canvas = document.getElementById('chart-intervention-type');
    if (!canvas) return;
    
    if (chartInterventionType) chartInterventionType.destroy();
    
    const labels = data.map(d => d.libelle);
    const values = data.map(d => d.total);
    
    chartInterventionType = new Chart(canvas, {
        type: 'doughnut',
        data: {
            labels,
            datasets: [{
                data: values,
                backgroundColor: ['#1e9e6d', '#f4b000'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });
}

function renderCategorieChart(data) {
    const canvas = document.getElementById('chart-cout-categorie');
    if (!canvas) return;
    
    if (chartCoutCategorie) chartCoutCategorie.destroy();
    
    const sorted = data.slice().sort((a, b) => b.cout_total - a.cout_total).slice(0, 10);
    const labels = sorted.map(d => d.categorie?.libelle || 'Inconnu');
    const values = sorted.map(d => d.cout_total);
    
    chartCoutCategorie = new Chart(canvas, {
        type: 'bar',
        data: {
            labels,
            datasets: [{
                label: 'Coût (DH)',
                data: values,
                backgroundColor: chartPalette[0],
                borderRadius: 4
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                x: { beginAtZero: true }
            }
        }
    });
}

function renderVehiculeChart(data) {
    const canvas = document.getElementById('chart-cout-vehicule-interv');
    if (!canvas) return;
    
    const countEl = document.getElementById('cout-vehicule-interv-count');
    if (countEl) countEl.textContent = `${data.length} véhicules`;
    
    if (chartCoutVehiculeInterv) chartCoutVehiculeInterv.destroy();
    
    const sorted = data.slice().sort((a, b) => b.cout_total - a.cout_total).slice(0, 10);
    const labels = sorted.map(d => d.vehicule?.label || 'Inconnu');
    const entretiensData = sorted.map(d => d.cout_entretien || 0);
    const reparationsData = sorted.map(d => d.cout_reparation || 0);
    
    chartCoutVehiculeInterv = new Chart(canvas, {
        type: 'bar',
        data: {
            labels,
            datasets: [
                {
                    label: 'Entretien',
                    data: entretiensData,
                    backgroundColor: '#1e9e6d',
                    borderRadius: 4
                },
                {
                    label: 'Réparation',
                    data: reparationsData,
                    backgroundColor: '#f4b000',
                    borderRadius: 4
                }
            ]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'top' } },
            scales: {
                x: { stacked: true, beginAtZero: true },
                y: { stacked: true }
            }
        }
    });
}

function renderEvolutionChart(data) {
    const canvas = document.getElementById('chart-evolution-mensuelle');
    if (!canvas) return;
    
    if (chartEvolutionMensuelle) chartEvolutionMensuelle.destroy();
    
    const labels = data.map(d => d.periode);
    const entretiensData = data.map(d => d.entretiens || 0);
    const reparationsData = data.map(d => d.reparations || 0);
    
    chartEvolutionMensuelle = new Chart(canvas, {
        type: 'line',
        data: {
            labels,
            datasets: [
                {
                    label: 'Entretiens',
                    data: entretiensData,
                    borderColor: '#1e9e6d',
                    backgroundColor: 'rgba(30, 158, 109, 0.1)',
                    fill: true,
                    tension: 0.3
                },
                {
                    label: 'Réparations',
                    data: reparationsData,
                    borderColor: '#f4b000',
                    backgroundColor: 'rgba(244, 176, 0, 0.1)',
                    fill: true,
                    tension: 0.3
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'top' } },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
}

function renderOperationsRanking(data) {
    const container = document.getElementById('ranking-operations');
    if (!container) return;
    
    if (!data.length) {
        container.innerHTML = '<p class="empty-state">Aucune donnée</p>';
        return;
    }
    
    container.innerHTML = data.slice(0, 5).map((item, idx) => `
        <div class="ranking-item">
            <span class="ranking-position">${idx + 1}</span>
            <div class="ranking-info">
                <span class="ranking-label">${item.operation?.libelle || 'Inconnu'}</span>
                <span class="ranking-value">${item.total} interventions</span>
            </div>
        </div>
    `).join('');
}

// ============================================================================
// Modal Functions
// ============================================================================

function openInterventionModal(intervention = null) {
    if (!interventionModal) return;
    
    editingInterventionId = intervention?.id || null;
    interventionFormTitle.innerHTML = intervention 
        ? `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"/></svg> Modifier l'intervention`
        : `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg> Nouvelle intervention`;
    
    interventionForm.reset();
    
    // Populate véhicules
    populateVehiculeSelect(interventionVehiculeSelect);
    
    // Populate type select
    populateTypeSelect(interventionTypeSelect);
    
    if (intervention) {
        interventionVehiculeSelect.value = intervention.vehicule_id || '';
        const typeCode = intervention.operation?.type?.code || '';
        interventionTypeSelect.value = typeCode;
        
        // Load operations for this type then set value
        loadOperationsForType(typeCode).then(() => {
            interventionOperationSelect.value = intervention.operation_id || '';
        });
        
        document.getElementById('intervention-date').value = toInputDate(intervention.date_intervention);
        document.getElementById('intervention-km').value = intervention.kilometrage || '';
        document.getElementById('intervention-cout').value = intervention.cout || '';
        document.getElementById('intervention-prestataire').value = intervention.prestataire || '';
        document.getElementById('intervention-immob').value = intervention.immobilisation_jours || 0;
        document.getElementById('intervention-description').value = intervention.description || '';
    } else {
        interventionOperationSelect.innerHTML = '<option value="">- Sélectionnez d\'abord un type -</option>';
        document.getElementById('intervention-date').value = toInputDate(new Date());
    }
    
    interventionModal.classList.remove('hidden');
}

function closeInterventionModal() {
    if (interventionModal) interventionModal.classList.add('hidden');
    editingInterventionId = null;
}

function openCategorieModal(categorie = null) {
    if (!categorieModal) return;
    
    editingCategorieId = categorie?.id || null;
    categorieFormTitle.innerHTML = categorie 
        ? `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"/></svg> Modifier la catégorie`
        : `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"/></svg> Nouvelle catégorie`;
    
    categorieForm.reset();
    
    if (categorie) {
        document.getElementById('categorie-code').value = categorie.code || '';
        document.getElementById('categorie-libelle').value = categorie.libelle || '';
        document.getElementById('categorie-actif').checked = categorie.actif !== false;
    }
    
    categorieModal.classList.remove('hidden');
}

function closeCategorieModal() {
    if (categorieModal) categorieModal.classList.add('hidden');
    editingCategorieId = null;
}

function openOperationModal(operation = null) {
    if (!operationModal) return;
    
    editingOperationId = operation?.id || null;
    operationFormTitle.innerHTML = operation 
        ? `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"/></svg> Modifier l'opération`
        : `<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></svg> Nouvelle opération`;
    
    operationForm.reset();
    
    // Populate type and categorie selects
    populateTypeSelect(operationTypeSelect, true);
    populateCategorieSelect(operationCategorieSelect);
    
    if (operation) {
        document.getElementById('operation-code').value = operation.code || '';
        document.getElementById('operation-libelle').value = operation.libelle || '';
        operationTypeSelect.value = operation.type_id || '';
        operationCategorieSelect.value = operation.categorie_id || '';
        document.getElementById('operation-periodicite-km').value = operation.periodicite_km || '';
        document.getElementById('operation-periodicite-mois').value = operation.periodicite_mois || '';
        document.getElementById('operation-actif').checked = operation.actif !== false;
        
        updatePeriodiciteVisibility(operation.type?.code);
    }
    
    operationModal.classList.remove('hidden');
}

function closeOperationModal() {
    if (operationModal) operationModal.classList.add('hidden');
    editingOperationId = null;
}

function openInterventionDetailModal(intervention) {
    if (!interventionDetailModal || !intervention) return;
    
    const op = intervention.operation || {};
    const type = op.type || {};
    const cat = op.categorie || {};
    const v = intervention.vehicule || {};
    
    document.getElementById('intervention-detail-operation').textContent = op.libelle || '-';
    document.getElementById('intervention-detail-type-badge').textContent = formatInterventionType(type.code);
    document.getElementById('intervention-detail-type-badge').className = `pill ${getTypeClass(type.code)}`;
    document.getElementById('intervention-detail-vehicule').textContent = vehiculeLabel(v);
    document.getElementById('intervention-detail-date').textContent = formatDate(intervention.date_intervention);
    document.getElementById('intervention-detail-categorie').textContent = cat.libelle || '-';
    document.getElementById('intervention-detail-km').textContent = intervention.kilometrage ? intervention.kilometrage.toLocaleString() + ' km' : '-';
    document.getElementById('intervention-detail-cout').textContent = formatCurrency(intervention.cout);
    document.getElementById('intervention-detail-prestataire').textContent = intervention.prestataire || '-';
    document.getElementById('intervention-detail-immob').textContent = intervention.immobilisation_jours ? intervention.immobilisation_jours + ' jours' : '-';
    document.getElementById('intervention-detail-description').textContent = intervention.description || 'Aucune description.';
    
    
    // Update avatar color based on type
    const avatar = document.getElementById('intervention-detail-avatar');
    if (avatar) {
        avatar.style.background = type.code === 'ENT' ? 'var(--success-100)' : 'var(--warning-100)';
        avatar.style.color = type.code === 'ENT' ? 'var(--success-600)' : 'var(--warning-600)';
    }
    
    interventionDetailModal.classList.remove('hidden');
}

function closeInterventionDetailModal() {
    if (interventionDetailModal) interventionDetailModal.classList.add('hidden');
}

// ============================================================================
// Select Population
// ============================================================================

function populateVehiculeSelect(selectEl) {
    if (!selectEl || !state.vehicules) return;
    const options = state.vehicules.map(v => {
        const label = v.numero || v.code || `${v.marque || ''} ${v.modele || ''}`.trim() || `Véhicule ${v.id}`;
        return `<option value="${v.id}">${label}</option>`;
    }).join('');
    selectEl.innerHTML = `<option value="">- Choisir un véhicule -</option>${options}`;
}

function populateTypeSelect(selectEl, useId = false) {
    if (!selectEl) return;
    const options = types.map(t => {
        const val = useId ? t.id : t.code;
        return `<option value="${val}">${t.libelle}</option>`;
    }).join('');
    selectEl.innerHTML = `<option value="">- Choisir -</option>${options}`;
}

function populateCategorieSelect(selectEl) {
    if (!selectEl) return;
    const options = categories.map(c => `<option value="${c.id}">${c.libelle}</option>`).join('');
    selectEl.innerHTML = `<option value="">- Choisir -</option>${options}`;
}

async function loadOperationsForType(typeCode) {
    if (!interventionOperationSelect) return;
    
    if (!typeCode) {
        interventionOperationSelect.innerHTML = '<option value="">- Sélectionnez d\'abord un type -</option>';
        return;
    }
    
    await fetchOperations(typeCode);
    
    const options = operations.map(o => `<option value="${o.id}">${o.libelle}</option>`).join('');
    interventionOperationSelect.innerHTML = `<option value="">- Choisir une opération -</option>${options}`;
}

function updatePeriodiciteVisibility(typeCode) {
    const kmGroup = document.getElementById('periodicite-km-group');
    const moisGroup = document.getElementById('periodicite-mois-group');
    
    if (!kmGroup || !moisGroup) return;
    
    // Afficher périodicité uniquement pour Entretien
    const isEntretien = typeCode === 'ENT' || types.find(t => t.id == typeCode)?.code === 'ENT';
    
    kmGroup.style.opacity = isEntretien ? '1' : '0.5';
    moisGroup.style.opacity = isEntretien ? '1' : '0.5';
    
    if (!isEntretien) {
        document.getElementById('operation-periodicite-km').value = '';
        document.getElementById('operation-periodicite-mois').value = '';
    }
}

function populateFilterSelects() {
    // Véhicules filter
    const vehFilterEl = document.querySelector('.custom-select[data-name="intervention-filter-vehicule"] .custom-select__options');
    if (vehFilterEl && state.vehicules) {
        const items = state.vehicules.map(v => {
            const label = v.numero || v.code || `${v.marque || ''} ${v.modele || ''}`.trim() || `Véhicule ${v.id}`;
            return `<li role="option" data-value="${v.id}">${label}</li>`;
        }).join('');
        vehFilterEl.innerHTML = `<li role="option" data-value="" aria-selected="true">Tous les véhicules</li>${items}`;
    }
    
    // Catégories filter
    const catFilterEl = document.querySelector('.custom-select[data-name="intervention-filter-categorie"] .custom-select__options');
    if (catFilterEl && categories.length) {
        const items = categories.map(c => `<li role="option" data-value="${c.id}">${c.libelle}</li>`).join('');
        catFilterEl.innerHTML = `<li role="option" data-value="" aria-selected="true">Toutes catégories</li>${items}`;
    }
}

// ============================================================================
// Form Submissions
// ============================================================================

async function handleInterventionSubmit(e) {
    e.preventDefault();
    
    const formData = new FormData(interventionForm);
    const data = Object.fromEntries(formData.entries());
    
    // Convert empty strings to null for numeric fields
    if (data.kilometrage === '') data.kilometrage = null;
    if (data.cout === '') data.cout = null;
    if (data.immobilisation_jours === '') data.immobilisation_jours = 0;
    
    try {
        if (editingInterventionId) {
            await axios.put(`/api/interventions/${editingInterventionId}`, data);
            showToast('Intervention modifiée avec succès', 'success');
        } else {
            await axios.post('/api/interventions', data);
            showToast('Intervention créée avec succès', 'success');
        }
        
        closeInterventionModal();
        await loadInterventions();
        await loadAlertes();
    } catch (err) {
        showToast(extractErrorMessage(err), 'error');
    }
}

async function handleCategorieSubmit(e) {
    e.preventDefault();
    
    const formData = new FormData(categorieForm);
    const data = {
        code: formData.get('code'),
        libelle: formData.get('libelle'),
        actif: formData.get('actif') === 'on'
    };
    
    try {
        if (editingCategorieId) {
            await axios.put(`/api/interventions/categories/${editingCategorieId}`, data);
            showToast('Catégorie modifiée avec succès', 'success');
        } else {
            await axios.post('/api/interventions/categories', data);
            showToast('Catégorie créée avec succès', 'success');
        }
        
        closeCategorieModal();
        await loadCatalogue();
    } catch (err) {
        showToast(extractErrorMessage(err), 'error');
    }
}

async function handleOperationSubmit(e) {
    e.preventDefault();
    
    const formData = new FormData(operationForm);
    const data = {
        code: formData.get('code'),
        libelle: formData.get('libelle'),
        type_id: formData.get('type_id'),
        categorie_id: formData.get('categorie_id'),
        periodicite_km: formData.get('periodicite_km') || null,
        periodicite_mois: formData.get('periodicite_mois') || null,
        actif: formData.get('actif') === 'on'
    };
    
    try {
        if (editingOperationId) {
            await axios.put(`/api/interventions/operations/${editingOperationId}`, data);
            showToast('Opération modifiée avec succès', 'success');
        } else {
            await axios.post('/api/interventions/operations', data);
            showToast('Opération créée avec succès', 'success');
        }
        
        closeOperationModal();
        await loadCatalogue();
    } catch (err) {
        showToast(extractErrorMessage(err), 'error');
    }
}

async function handleDeleteIntervention(id) {
    const confirmed = await showConfirm('Êtes-vous sûr de vouloir supprimer cette intervention ?');
    if (!confirmed) return;
    
    try {
        await axios.delete(`/api/interventions/${id}`);
        showToast('Intervention supprimée', 'success');
        await loadInterventions();
    } catch (err) {
        showToast(extractErrorMessage(err), 'error');
    }
}

// ============================================================================
// Load Functions
// ============================================================================

export async function loadInterventions() {
    await ensureAuth();
    await fetchInterventions();
    renderInterventionsTable();
}

async function loadCatalogue() {
    await fetchTypes();
    await fetchCategories();
    await fetchOperations();
    renderCategoriesTable();
    renderOperationsTable();
    populateFilterSelects();
}

async function loadAlertes() {
    const alertes = await fetchAlertes();
    renderAlertesTable(alertes);
}

// ============================================================================
// Panel Navigation
// ============================================================================

function switchInterventionPanel(panelName) {
    interventionPanels.forEach(panel => {
        panel.classList.toggle('active', panel.dataset.interventionPanel === panelName);
    });
    
    // Load data for specific panels
    if (panelName === 'stats') {
        loadAndRenderStats();
    } else if (panelName === 'alertes') {
        loadAlertes();
    } else if (panelName === 'catalogue') {
        loadCatalogue();
    }
}

// ============================================================================
// Event Listeners
// ============================================================================

export function initializeInterventionEvents() {
    // Open/Close modals
    openInterventionModalBtn?.addEventListener('click', () => openInterventionModal());
    closeInterventionModalBtn?.addEventListener('click', closeInterventionModal);
    cancelInterventionFormBtn?.addEventListener('click', closeInterventionModal);
    
    openCategorieModalBtn?.addEventListener('click', () => openCategorieModal());
    closeCategorieModalBtn?.addEventListener('click', closeCategorieModal);
    cancelCategorieFormBtn?.addEventListener('click', closeCategorieModal);
    
    openOperationModalBtn?.addEventListener('click', () => openOperationModal());
    closeOperationModalBtn?.addEventListener('click', closeOperationModal);
    cancelOperationFormBtn?.addEventListener('click', closeOperationModal);
    
    closeInterventionDetailModalBtn?.addEventListener('click', closeInterventionDetailModal);
    
    // Form submissions
    interventionForm?.addEventListener('submit', handleInterventionSubmit);
    categorieForm?.addEventListener('submit', handleCategorieSubmit);
    operationForm?.addEventListener('submit', handleOperationSubmit);
    
    // Type selection change (for operations filter)
    interventionTypeSelect?.addEventListener('change', (e) => {
        loadOperationsForType(e.target.value);
    });
    
    // Operation type change (show/hide periodicite)
    operationTypeSelect?.addEventListener('change', (e) => {
        const typeId = e.target.value;
        const type = types.find(t => t.id == typeId);
        updatePeriodiciteVisibility(type?.code);
    });
    
    // Table filters
    interventionFilterType?.addEventListener('change', loadInterventions);
    interventionFilterVehicule?.addEventListener('change', loadInterventions);
    interventionFilterCategorie?.addEventListener('change', loadInterventions);
    catalogueFilterType?.addEventListener('change', renderOperationsTable);
    
    // Table actions (delegation)
    interventionTableBody?.addEventListener('click', async (e) => {
        const btn = e.target.closest('[data-action]');
        if (!btn) return;
        
        const action = btn.dataset.action;
        const id = parseInt(btn.dataset.id);
        const intervention = interventions.find(i => i.id === id);
        
        if (action === 'view' && intervention) {
            openInterventionDetailModal(intervention);
        } else if (action === 'edit' && intervention) {
            openInterventionModal(intervention);
        } else if (action === 'delete') {
            await handleDeleteIntervention(id);
        }
    });
    
    // Categories table actions
    categoriesRows?.addEventListener('click', (e) => {
        const btn = e.target.closest('[data-action="edit-categorie"]');
        if (!btn) return;
        
        const id = parseInt(btn.dataset.id);
        const categorie = categories.find(c => c.id === id);
        if (categorie) openCategorieModal(categorie);
    });
    
    // Operations table actions
    operationsRows?.addEventListener('click', (e) => {
        const btn = e.target.closest('[data-action="edit-operation"]');
        if (!btn) return;
        
        const id = parseInt(btn.dataset.id);
        const operation = operations.find(o => o.id === id);
        if (operation) openOperationModal(operation);
    });
    
    // Refresh buttons
    refreshAlertesBtn?.addEventListener('click', loadAlertes);
    refreshInterventionStatsBtn?.addEventListener('click', loadAndRenderStats);
    applyInterventionStatsFiltersBtn?.addEventListener('click', loadAndRenderStats);
    
    // Panel navigation (submenu)
    document.querySelectorAll('[data-intervention-tab]').forEach(btn => {
        btn.addEventListener('click', () => {
            const tab = btn.dataset.interventionTab;
            if (tab) switchInterventionPanel(tab);
        });
    });
    
    // Create intervention from alert
    window.addEventListener('createInterventionFromAlert', async (e) => {
        const { vehicule_id, operation_id } = e.detail;
        
        await fetchTypes();
        await fetchCategories();
        await fetchOperations();
        
        const operation = operations.find(o => o.id === operation_id);
        
        openInterventionModal();
        
        setTimeout(() => {
            if (interventionVehiculeSelect) interventionVehiculeSelect.value = vehicule_id;
            if (operation && interventionTypeSelect) {
                interventionTypeSelect.value = operation.type?.code || '';
                loadOperationsForType(operation.type?.code).then(() => {
                    if (interventionOperationSelect) interventionOperationSelect.value = operation_id;
                });
            }
        }, 100);
    });
    
    // Modal backdrop close
    document.querySelectorAll('[data-close]').forEach(el => {
        el.addEventListener('click', () => {
            const modalId = el.dataset.close;
            const modal = document.getElementById(modalId);
            if (modal) modal.classList.add('hidden');
        });
    });
}

// ============================================================================
// Tab Switching - Called from utils.js when submenu clicked
// ============================================================================

export function activateInterventionTab(tabKey) {
    // Switch active panel
    interventionPanels.forEach(panel => {
        const key = panel.dataset.interventionPanel;
        panel.classList.toggle('active', key === tabKey);
    });
    
    // Load data for the selected tab
    switch (tabKey) {
        case 'tableau':
            loadInterventions();
            break;
        case 'catalogue':
            loadCatalogue();
            break;
        case 'alertes':
            loadAlertes();
            break;
        case 'stats':
            loadAndRenderStats();
            break;
    }
}

// ============================================================================
// Initial Load
// ============================================================================

export async function initializeInterventions() {
    await loadCatalogue();
    await loadInterventions();
    await loadAlertes();
}
