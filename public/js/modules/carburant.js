// ============================================================================
// Carburant Management (Gestion Carburant)
// ============================================================================

import { state, showToast, showConfirm, extractErrorMessage } from './state.js';
import { ensureAuth } from './auth.js';
import { formatDate, formatCurrency, toInputDate } from './utils.js';

// ============================================================================
// DOM Elements
// ============================================================================

// Panels
const carburantPanels = document.querySelectorAll('[data-carburant-panel]');

// Table
const carburantTableBody = document.getElementById('carburant-rows');

// Modal Plein
const carburantModal = document.getElementById('carburant-modal');
const carburantForm = document.getElementById('carburant-form');
const carburantFormTitle = document.getElementById('carburant-form-title');
const openCarburantModalBtn = document.getElementById('open-carburant-modal');
const closeCarburantModalBtn = document.getElementById('close-carburant-modal');
const cancelCarburantFormBtn = document.getElementById('cancel-carburant-form');
const carburantVehiculeSelect = document.getElementById('carburant-vehicule-select');
const carburantChauffeurSelect = document.getElementById('carburant-chauffeur-select');
const carburantQuantiteInput = document.getElementById('carburant-quantite');
const carburantPrixInput = document.getElementById('carburant-prix');
const carburantMontantInput = document.getElementById('carburant-montant');

// Modal Détail
const carburantDetailModal = document.getElementById('carburant-detail-modal');
const closeCarburantDetailModalBtn = document.getElementById('close-carburant-detail-modal');

// Consommation
const consommationVehiculeRows = document.getElementById('consommation-vehicule-rows');


// Alertes
const carburantAlertesRows = document.getElementById('carburant-alertes-rows');

// Comparaison
const comparaisonRows = document.getElementById('comparaison-rows');
const comparaisonMoyenneCout = document.getElementById('comparaison-moyenne-cout');

// Stats
const refreshCarburantStatsBtn = document.getElementById('refresh-carburant-stats');
const applyCarburantStatsFiltersBtn = document.getElementById('apply-carburant-stats-filters');
const carburantStatsDateStart = document.getElementById('carburant-stats-date-start');
const carburantStatsDateEnd = document.getElementById('carburant-stats-date-end');

// ============================================================================
// State
// ============================================================================

let pleins = [];
let editingPleinId = null;

// Chart instances
let chartCarburantType = null;
let chartCarburantVehicule = null;
let chartCarburantMode = null;
let chartCarburantEvolution = null;

const chartPalette = ['#1e3a5f', '#1e9e6d', '#f4b000', '#d9534f', '#5a6c90', '#7a3ff2', '#e91e63', '#00bcd4'];

// ============================================================================
// Helpers
// ============================================================================

const TYPE_CARBURANT_LABELS = {
    diesel: 'Diesel',
    essence: 'Essence',
    gpl: 'GPL',
    electrique: 'Electrique',
};

const MODE_PAIEMENT_LABELS = {
    especes: 'Espèces',
    carte_carburant: 'Carte carburant',
    bon: 'Bon',
    cheque: 'Chèque',
};

function formatTypeCarburant(val) {
    return TYPE_CARBURANT_LABELS[val] || val || '-';
}

function formatModePaiement(val) {
    return MODE_PAIEMENT_LABELS[val] || val || '-';
}

function getTypeClass(type) {
    const map = { diesel: 'pill-accent', essence: 'pill-success', gpl: 'pill-warning', electrique: 'pill-info' };
    return map[type] || 'pill-muted';
}

function getModeClass(mode) {
    const map = { especes: 'pill-success', carte_carburant: 'pill-accent', bon: 'pill-warning', cheque: 'pill-info' };
    return map[mode] || 'pill-muted';
}

function getStatutClass(statut) {
    const map = { normal: 'pill-success', a_surveiller: 'pill-warning', eleve: 'pill-danger' };
    return map[statut] || 'pill-muted';
}

function getStatutLabel(statut) {
    const map = { normal: 'Normal', a_surveiller: 'À surveiller', eleve: 'Élevé' };
    return map[statut] || statut || '-';
}

function vehiculeLabel(v) {
    if (!v) return '-';
    return v.numero || v.code || `${v.marque || ''} ${v.modele || ''}`.trim() || `Véhicule ${v.id}`;
}

function chauffeurLabel(c) {
    if (!c) return '-';
    return `${c.nom || ''} ${c.prenom || ''}`.trim() || `Chauffeur ${c.id}`;
}

function getDefaultLabel(inputEl, fallback = '') {
    if (!inputEl) return fallback;
    const container = document.querySelector(`.custom-select[data-name="${inputEl.id}"]`);
    return container?.dataset.defaultLabel || fallback;
}

function updateCustomSelectDisplay(inputEl, fallback = '') {
    if (!inputEl) return;
    const container = document.querySelector(`.custom-select[data-name="${inputEl.id}"]`);
    if (!container) return;

    const valueEl = container.querySelector('.custom-select__value');
    const options = Array.from(container.querySelectorAll('.custom-select__options li[role="option"]'));
    const defaultLabel = getDefaultLabel(inputEl, fallback);

    let label = defaultLabel;
    if (inputEl.value) {
        const match = options.find(li => String(li.dataset.value) === String(inputEl.value));
        if (match) label = match.textContent.trim();
    }

    options.forEach((li, idx) => {
        const selected = inputEl.value ? String(li.dataset.value) === String(inputEl.value) : (idx === 0 && li.dataset.value === '');
        li.setAttribute('aria-selected', selected ? 'true' : 'false');
    });

    if (valueEl) valueEl.textContent = label || defaultLabel;
}

// ============================================================================
// Custom Select Initialization
// ============================================================================

const initializedSelects = new WeakMap();

function setupCustomSelect(root, forceReinit = false) {
    const optionsList = root.querySelector('.custom-select__options');
    if (!optionsList) return;

    const optionCount = optionsList.querySelectorAll('li').length;
    const prevCount = initializedSelects.get(root);

    if (!forceReinit && prevCount !== undefined && prevCount === optionCount) return;
    initializedSelects.set(root, optionCount);

    let trigger = root.querySelector('.custom-select__trigger');
    let valueElem = root.querySelector('.custom-select__value');
    const hidden = root.querySelector('input[type="hidden"]');
    let options = optionsList;

    function syncValue() {
        const currentVal = hidden.value || '';
        const allLis = Array.from(options.querySelectorAll('li[role="option"]'));
        // Try to match the current hidden value first
        let pre = allLis.find(li => String(li.getAttribute('data-value') || '') === String(currentVal));
        // Fallback: first aria-selected or first li
        if (!pre) {
            pre = options.querySelector('li[aria-selected="true"]') || options.querySelector('li');
        }
        if (pre) {
            const pv = pre.getAttribute('data-value') || '';
            hidden.value = pv;
            valueElem.textContent = pre.textContent.trim();
            allLis.forEach(x => x.setAttribute('aria-selected', 'false'));
            pre.setAttribute('aria-selected', 'true');
            trigger.classList.add('selected');
        }
    }

    syncValue();

    function open() {
        root.classList.add('open');
        trigger.setAttribute('aria-expanded', 'true');
    }

    function close() {
        root.classList.remove('open');
        trigger.setAttribute('aria-expanded', 'false');
    }

    const newTrigger = trigger.cloneNode(true);
    trigger.parentNode.replaceChild(newTrigger, trigger);
    trigger = newTrigger;
    // Re-query valueElem from the new cloned trigger
    valueElem = trigger.querySelector('.custom-select__value');

    const newOptions = options.cloneNode(true);
    options.parentNode.replaceChild(newOptions, options);
    options = newOptions;

    trigger.addEventListener('click', (e) => {
        e.stopPropagation();
        if (root.classList.contains('open')) close(); else open();
    });

    options.addEventListener('click', (e) => {
        const li = e.target.closest('li[role="option"]');
        if (!li) return;
        const v = li.getAttribute('data-value') || '';
        const text = li.textContent.trim();
        hidden.value = v;
        valueElem.textContent = text;
        options.querySelectorAll('li').forEach(x => x.setAttribute('aria-selected', 'false'));
        li.setAttribute('aria-selected', 'true');
        close();
        hidden.dispatchEvent(new Event('change', { bubbles: true }));
    });

    document.addEventListener('click', () => close());
    document.addEventListener('keydown', (e) => { if (e.key === 'Escape') close(); });
}

function initCarburantCustomSelects() {
    const elems = document.querySelectorAll('#carburant .custom-select');
    elems.forEach(el => setupCustomSelect(el, true));
}

// ============================================================================
// API Functions
// ============================================================================

async function fetchPleins() {
    try {
        const params = {};
        const filterType = document.getElementById('carburant-filter-type');
        const filterVehicule = document.getElementById('carburant-filter-vehicule');
        const filterMode = document.getElementById('carburant-filter-mode');
        const filterDateStart = document.getElementById('carburant-filter-date-start');
        const filterDateEnd = document.getElementById('carburant-filter-date-end');

        if (filterType?.value) params.type_carburant = filterType.value;
        if (filterVehicule?.value) params.vehicule_id = filterVehicule.value;
        if (filterMode?.value) params.mode_paiement = filterMode.value;
        if (filterDateStart?.value) params.date_start = filterDateStart.value;
        if (filterDateEnd?.value) params.date_end = filterDateEnd.value;

        const res = await axios.get('/api/carburant', { params });
        pleins = res.data.data || res.data;
        return pleins;
    } catch (err) {
        console.error('Erreur chargement pleins:', err);
        return [];
    }
}

async function fetchStats(dateStart = null, dateEnd = null) {
    try {
        const params = {};
        if (dateStart) params.date_start = dateStart;
        if (dateEnd) params.date_end = dateEnd;

        const res = await axios.get('/api/carburant/stats', { params });
        return res.data;
    } catch (err) {
        console.error('Erreur chargement stats carburant:', err);
        return null;
    }
}

async function fetchComparaison(dateStart = null, dateEnd = null, typeCarburant = null, categorie = null) {
    try {
        const params = {};
        if (dateStart) params.date_start = dateStart;
        if (dateEnd) params.date_end = dateEnd;
        if (typeCarburant) params.type_carburant = typeCarburant;
        if (categorie) params.categorie = categorie;

        const res = await axios.get('/api/carburant/comparaison', { params });
        return res.data;
    } catch (err) {
        console.error('Erreur chargement comparaison:', err);
        return null;
    }
}

async function fetchAlertes() {
    try {
        const res = await axios.get('/api/carburant/alertes');
        return res.data;
    } catch (err) {
        console.error('Erreur chargement alertes carburant:', err);
        return { alertes: [], total_alertes: 0 };
    }
}

// ============================================================================
// Populate Filter Selects
// ============================================================================

function populateFilterSelects() {
    const vehicules = state.vehicules || [];
    const chauffeurs = state.chauffeurs || [];

    // Filters - vehicule
    const filterVehiculeUl = document.querySelector('[data-name="carburant-filter-vehicule"] .custom-select__options');
    const filterVehiculeHidden = document.getElementById('carburant-filter-vehicule');
    const curVehiculeVal = filterVehiculeHidden ? filterVehiculeHidden.value : '';
    if (filterVehiculeUl) {
        filterVehiculeUl.innerHTML = `<li role="option" data-value="" aria-selected="${curVehiculeVal === '' ? 'true' : 'false'}">Tous les véhicules</li>` +
            vehicules.map(v => `<li role="option" data-value="${v.id}" aria-selected="${String(v.id) === String(curVehiculeVal) ? 'true' : 'false'}">${vehiculeLabel(v)}</li>`).join('');
    }

    // Form - vehicule select
    if (carburantVehiculeSelect) {
        const currentVal = carburantVehiculeSelect.value;
        carburantVehiculeSelect.innerHTML = `<option value="">- Choisir un véhicule -</option>` +
            vehicules.map(v => `<option value="${v.id}">${vehiculeLabel(v)}</option>`).join('');
        if (currentVal) carburantVehiculeSelect.value = currentVal;
    }

    // Form - chauffeur select
    if (carburantChauffeurSelect) {
        const currentVal = carburantChauffeurSelect.value;
        carburantChauffeurSelect.innerHTML = `<option value="">- Optionnel -</option>` +
            chauffeurs.map(c => `<option value="${c.id}">${chauffeurLabel(c)}</option>`).join('');
        if (currentVal) carburantChauffeurSelect.value = currentVal;
    }

    initCarburantCustomSelects();
}

// ============================================================================
// Render Functions
// ============================================================================

function renderPleinsTable() {
    if (!carburantTableBody) return;

    const countEl = document.querySelector('#carburant-count .count');
    if (countEl) countEl.textContent = pleins.length;

    if (!pleins.length) {
        carburantTableBody.innerHTML = `
            <tr><td colspan="10" class="empty-state">
                <p>Aucun plein carburant enregistré</p>
            </td></tr>`;
        return;
    }

    carburantTableBody.innerHTML = pleins.map(p => {
        const v = p.vehicule || {};
        return `
            <tr data-id="${p.id}">
                <td>${formatDate(p.date_plein)}</td>
                <td>${vehiculeLabel(v)}</td>
                <td><span class="pill ${getTypeClass(p.type_carburant)}">${formatTypeCarburant(p.type_carburant)}</span></td>
                <td>${p.kilometrage ? p.kilometrage.toLocaleString() : '-'}</td>
                <td>${p.quantite ? Number(p.quantite).toFixed(2) : '-'}</td>
                <td>${p.prix_unitaire ? Number(p.prix_unitaire).toFixed(2) : '-'}</td>
                <td>${formatCurrency(p.montant_total)}</td>
                <td><span class="pill ${getModeClass(p.mode_paiement)}">${formatModePaiement(p.mode_paiement)}</span></td>
                <td>${chauffeurLabel(p.chauffeur)}</td>
                <td>
                    <div class="action-btns">
                        <button class="action-btn view" title="Voir" data-action="view" data-id="${p.id}">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                        </button>
                        <button class="action-btn edit" title="Modifier" data-action="edit" data-id="${p.id}">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"/></svg>
                        </button>
                        <button class="action-btn delete" title="Supprimer" data-action="delete" data-id="${p.id}">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                        </button>
                    </div>
                </td>
            </tr>`;
    }).join('');
}

// ============================================================================
// Consommation
// ============================================================================

async function loadConsommation() {
    const stats = await fetchStats();
    if (!stats) return;

    // Par véhicule
    if (consommationVehiculeRows) {
        const parV = stats.par_vehicule || [];
        if (!parV.length) {
            consommationVehiculeRows.innerHTML = `<tr><td colspan="7" class="empty-state"><p>Aucune donnée de consommation</p></td></tr>`;
        } else {
            consommationVehiculeRows.innerHTML = parV.map(v => `
                <tr>
                    <td>${vehiculeLabel(v.vehicule)}</td>
                    <td>${v.km_parcourus ? v.km_parcourus.toLocaleString() : '-'}</td>
                    <td>${v.nb_pleins}</td>
                    <td>${v.total_litres ? v.total_litres.toFixed(2) : '-'}</td>
                    <td>${formatCurrency(v.total_depenses)}</td>
                    <td><strong>${v.cout_par_km ? v.cout_par_km.toFixed(2) : '-'}</strong></td>
                    <td><strong>${v.conso_moyenne ? v.conso_moyenne.toFixed(2) + ' L/km' : '-'}</strong></td>
                </tr>`).join('');
        }
    }

    // Coût global du parc KPIs
    const coutGlobal = stats.cout_global_parc || {};
    const consoGlobalEl = document.getElementById('conso-cout-global');
    const consoMensuelParcEl = document.getElementById('conso-cout-mensuel-parc');
    const consoNbVehiculesEl = document.getElementById('conso-nb-vehicules');
    const consoMoyenneCoutKmEl = document.getElementById('conso-moyenne-cout-km');

    if (consoGlobalEl) consoGlobalEl.textContent = formatCurrency(coutGlobal.total_depenses);
    if (consoMensuelParcEl) consoMensuelParcEl.textContent = formatCurrency(coutGlobal.cout_mensuel_moyen);
    if (consoNbVehiculesEl) consoNbVehiculesEl.textContent = coutGlobal.nb_vehicules || 0;
    if (consoMoyenneCoutKmEl) consoMoyenneCoutKmEl.textContent = `${stats.totaux?.moyenne_cout_km || 0} DA`;
}

// ============================================================================
// Alertes
// ============================================================================

async function loadAlertes() {
    const data = await fetchAlertes();

    // KPIs
    const alertes = data.alertes || [];
    const surconsoEl = document.getElementById('carburant-alertes-surconso');
    const kmEl = document.getElementById('carburant-alertes-km');
    const rapprochesEl = document.getElementById('carburant-alertes-rapproches');

    if (surconsoEl) surconsoEl.textContent = alertes.filter(a => a.type === 'surconsommation').length;
    if (kmEl) kmEl.textContent = alertes.filter(a => a.type === 'km_incoherent').length;
    if (rapprochesEl) rapprochesEl.textContent = alertes.filter(a => a.type === 'plein_rapproche').length;

    // Table
    if (carburantAlertesRows) {
        if (!alertes.length) {
            carburantAlertesRows.innerHTML = `
                <tr><td colspan="4" class="empty-state">
                    <p>Aucune alerte - Tout est normal !</p>
                </td></tr>`;
        } else {
            const typeLabels = {
                surconsommation: { label: 'Surconsommation', cls: 'pill-danger' },
                km_incoherent: { label: 'Km incohérent', cls: 'pill-warning' },
                plein_rapproche: { label: 'Plein rapproché', cls: 'pill-accent' },
            };
            carburantAlertesRows.innerHTML = alertes.map(a => {
                const tl = typeLabels[a.type] || { label: a.type, cls: 'pill-muted' };
                return `
                    <tr>
                        <td>${vehiculeLabel(a.vehicule)}</td>
                        <td><span class="pill ${tl.cls}">${tl.label}</span></td>
                        <td>${formatDate(a.plein?.date_plein)}</td>
                        <td>${a.message}</td>
                    </tr>`;
            }).join('');
        }
    }
}

// ============================================================================
// Comparaison
// ============================================================================

async function loadComparaison() {
    const dateStart = document.getElementById('comparaison-date-start')?.value || null;
    const dateEnd = document.getElementById('comparaison-date-end')?.value || null;
    const typeCarburant = document.getElementById('comparaison-type-carburant')?.value || null;
    const categorie = document.getElementById('comparaison-categorie')?.value || null;

    const data = await fetchComparaison(dateStart, dateEnd, typeCarburant, categorie);
    if (!data) return;

    if (comparaisonMoyenneCout) comparaisonMoyenneCout.textContent = data.moyenne_cout_km || '0';

    if (comparaisonRows) {
        const items = data.comparaison || [];
        if (!items.length) {
            comparaisonRows.innerHTML = `<tr><td colspan="7" class="empty-state"><p>Aucune donnée de comparaison</p></td></tr>`;
        } else {
            comparaisonRows.innerHTML = items.map(v => `
                <tr>
                    <td>${vehiculeLabel(v.vehicule)}</td>
                    <td>${v.km_parcourus ? v.km_parcourus.toLocaleString() : '-'}</td>
                    <td>${formatCurrency(v.total_depenses)}</td>
                    <td><strong>${v.cout_par_km ? v.cout_par_km.toFixed(2) : '-'}</strong></td>
                    <td><strong>${v.conso_moyenne ? v.conso_moyenne.toFixed(2) + ' L/km' : '-'}</strong></td>
                    <td>${v.nb_pleins}</td>
                    <td><span class="pill ${getStatutClass(v.statut)}">${getStatutLabel(v.statut)}</span></td>
                </tr>`).join('');
        }
    }
}

// ============================================================================
// Stats & Charts
// ============================================================================

async function loadAndRenderStats() {
    const dateStart = carburantStatsDateStart?.value || null;
    const dateEnd = carburantStatsDateEnd?.value || null;

    const stats = await fetchStats(dateStart, dateEnd);
    if (!stats) return;

    // KPIs
    const totalPleinsEl = document.getElementById('stats-total-pleins');
    const totalLitresEl = document.getElementById('stats-total-litres');
    const totalDepensesEl = document.getElementById('stats-total-depenses');
    const prixMoyenEl = document.getElementById('stats-prix-moyen-litre');

    if (totalPleinsEl) totalPleinsEl.textContent = stats.totaux?.pleins || 0;
    if (totalLitresEl) totalLitresEl.textContent = `${stats.totaux?.litres || 0} L`;
    if (totalDepensesEl) totalDepensesEl.textContent = formatCurrency(stats.totaux?.depenses);
    if (prixMoyenEl) prixMoyenEl.textContent = `${stats.totaux?.prix_moyen_litre || 0} DA`;

    // Chart: Répartition par type carburant
    const typeData = stats.par_type || [];
    renderChartType(typeData);

    // Chart: Dépenses par véhicule
    const vehiculeData = stats.par_vehicule || [];
    renderChartVehicule(vehiculeData);

    // Chart: Par mode de paiement
    const modeData = stats.par_mode || [];
    renderChartMode(modeData);

    // Chart: Évolution mensuelle
    const periodeData = stats.par_periode || [];
    renderChartEvolution(periodeData);

    // Ranking: Véhicules les plus gourmands
    renderRankingGourmands(vehiculeData);
}

function renderChartType(typeData) {
    const canvas = document.getElementById('chart-carburant-type');
    if (!canvas) return;

    const labels = typeData.map(t => formatTypeCarburant(t.type));
    const data = typeData.map(t => t.depenses);

    if (chartCarburantType) {
        chartCarburantType.data.labels = labels;
        chartCarburantType.data.datasets[0].data = data;
        chartCarburantType.update();
    } else {
        chartCarburantType = new Chart(canvas, {
            type: 'doughnut',
            data: {
                labels,
                datasets: [{
                    data,
                    backgroundColor: chartPalette.slice(0, labels.length),
                    borderWidth: 2,
                    borderColor: '#fff',
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom', labels: { font: { size: 11 } } },
                },
            },
        });
    }
}

function renderChartVehicule(vehiculeData) {
    const canvas = document.getElementById('chart-carburant-vehicule');
    if (!canvas) return;

    const sorted = [...vehiculeData].sort((a, b) => b.total_depenses - a.total_depenses).slice(0, 10);
    const labels = sorted.map(v => vehiculeLabel(v.vehicule));
    const data = sorted.map(v => v.total_depenses);

    const countEl = document.getElementById('depense-vehicule-count');
    if (countEl) countEl.textContent = `${vehiculeData.length} véhicules`;

    if (chartCarburantVehicule) {
        chartCarburantVehicule.data.labels = labels;
        chartCarburantVehicule.data.datasets[0].data = data;
        chartCarburantVehicule.update();
    } else {
        chartCarburantVehicule = new Chart(canvas, {
            type: 'bar',
            data: {
                labels,
                datasets: [{
                    label: 'Dépenses (DA)',
                    data,
                    backgroundColor: chartPalette.slice(0, labels.length),
                    borderRadius: 6,
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                indexAxis: 'y',
                plugins: { legend: { display: false } },
                scales: { x: { beginAtZero: true } },
            },
        });
    }
}

function renderChartMode(modeData) {
    const canvas = document.getElementById('chart-carburant-mode');
    if (!canvas) return;

    const labels = modeData.map(m => formatModePaiement(m.mode));
    const data = modeData.map(m => m.depenses);

    if (chartCarburantMode) {
        chartCarburantMode.data.labels = labels;
        chartCarburantMode.data.datasets[0].data = data;
        chartCarburantMode.update();
    } else {
        chartCarburantMode = new Chart(canvas, {
            type: 'pie',
            data: {
                labels,
                datasets: [{
                    data,
                    backgroundColor: chartPalette.slice(0, labels.length),
                    borderWidth: 2,
                    borderColor: '#fff',
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom', labels: { font: { size: 11 } } },
                },
            },
        });
    }
}

function renderChartEvolution(periodeData) {
    const canvas = document.getElementById('chart-carburant-evolution');
    if (!canvas) return;

    const labels = periodeData.map(p => p.periode);
    const depenses = periodeData.map(p => p.depenses);
    const litres = periodeData.map(p => p.litres);

    if (chartCarburantEvolution) {
        chartCarburantEvolution.data.labels = labels;
        chartCarburantEvolution.data.datasets[0].data = depenses;
        chartCarburantEvolution.data.datasets[1].data = litres;
        chartCarburantEvolution.update();
    } else {
        chartCarburantEvolution = new Chart(canvas, {
            type: 'line',
            data: {
                labels,
                datasets: [
                    {
                        label: 'Dépenses (DA)',
                        data: depenses,
                        borderColor: '#1e3a5f',
                        backgroundColor: 'rgba(30, 58, 95, 0.1)',
                        fill: true,
                        tension: 0.4,
                        yAxisID: 'y',
                    },
                    {
                        label: 'Litres',
                        data: litres,
                        borderColor: '#1e9e6d',
                        backgroundColor: 'rgba(30, 158, 109, 0.1)',
                        fill: true,
                        tension: 0.4,
                        yAxisID: 'y1',
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: { position: 'top', labels: { font: { size: 11 } } },
                },
                scales: {
                    y: { type: 'linear', display: true, position: 'left', beginAtZero: true },
                    y1: { type: 'linear', display: true, position: 'right', beginAtZero: true, grid: { drawOnChartArea: false } },
                },
            },
        });
    }
}

function renderRankingGourmands(vehiculeData) {
    const container = document.getElementById('ranking-vehicules-gourmands');
    if (!container) return;

    const sorted = [...vehiculeData]
        .filter(v => v.conso_moyenne > 0)
        .sort((a, b) => b.conso_moyenne - a.conso_moyenne)
        .slice(0, 5);

    if (!sorted.length) {
        container.innerHTML = '<p style="text-align: center; color: var(--text-tertiary); font-size: 13px;">Aucune donnée</p>';
        return;
    }

    container.innerHTML = sorted.map((v, i) => `
        <div class="ranking-item">
            <span class="ranking-rank">${i + 1}</span>
            <div class="ranking-info">
                <div class="ranking-label">${vehiculeLabel(v.vehicule)}</div>
                <div class="ranking-sublabel">${v.km_parcourus.toLocaleString()} km · ${v.nb_pleins} pleins</div>
            </div>
            <span class="ranking-value">${v.conso_moyenne.toFixed(2)} L/km</span>
        </div>
    `).join('');
}

// ============================================================================
// Modal Functions
// ============================================================================

function openCarburantModal(plein = null) {
    editingPleinId = plein ? plein.id : null;

    // Ensure selects are populated with latest data
    populateFilterSelects();

    if (carburantFormTitle) {
        carburantFormTitle.innerHTML = `
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 22h12"/><path d="M4 9h10"/><path d="M4 22V4a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v18"/><path d="M14 15a2 2 0 1 0 4 0v-3a2 2 0 0 0-2-2h-2"/><path d="M16 10V4"/></svg>
            ${plein ? 'Modifier le plein carburant' : 'Nouveau plein carburant'}`;
    }

    if (carburantForm) carburantForm.reset();

    if (plein) {
        const dateInput = document.getElementById('carburant-date');
        const kmInput = document.getElementById('carburant-km');
        const quantiteInput = document.getElementById('carburant-quantite');
        const prixInput = document.getElementById('carburant-prix');
        const montantInput = document.getElementById('carburant-montant');
        const typeSelect = document.getElementById('carburant-type-select');
        const stationInput = document.getElementById('carburant-station');
        const modeSelect = document.getElementById('carburant-mode-select');
        const observationInput = document.getElementById('carburant-observation');

        if (carburantVehiculeSelect) carburantVehiculeSelect.value = plein.vehicule_id || '';
        if (carburantChauffeurSelect) carburantChauffeurSelect.value = plein.chauffeur_id || '';
        if (dateInput) dateInput.value = toInputDate(plein.date_plein);
        if (kmInput) kmInput.value = plein.kilometrage || '';
        if (quantiteInput) quantiteInput.value = plein.quantite || '';
        if (prixInput) prixInput.value = plein.prix_unitaire || '';
        if (montantInput) montantInput.value = plein.montant_total || '';
        if (typeSelect) typeSelect.value = plein.type_carburant || '';
        if (stationInput) stationInput.value = plein.station || '';
        if (modeSelect) modeSelect.value = plein.mode_paiement || '';
        if (observationInput) observationInput.value = plein.observation || '';
    }

    if (carburantModal) carburantModal.classList.remove('hidden');
}

function closeCarburantModal() {
    if (carburantModal) carburantModal.classList.add('hidden');
    editingPleinId = null;
    if (carburantForm) carburantForm.reset();
}

function showDetail(plein) {
    if (!carburantDetailModal) return;

    const vehiculeName = document.getElementById('carburant-detail-vehicule-name');
    const typeBadge = document.getElementById('carburant-detail-type-badge');
    const modeBadge = document.getElementById('carburant-detail-mode-badge');
    const dateEl = document.getElementById('carburant-detail-date');
    const kmEl = document.getElementById('carburant-detail-km');
    const quantiteEl = document.getElementById('carburant-detail-quantite');
    const prixEl = document.getElementById('carburant-detail-prix');
    const montantEl = document.getElementById('carburant-detail-montant');
    const stationEl = document.getElementById('carburant-detail-station');
    const chauffeurEl = document.getElementById('carburant-detail-chauffeur');
    const observationEl = document.getElementById('carburant-detail-observation');

    if (vehiculeName) vehiculeName.textContent = vehiculeLabel(plein.vehicule);
    if (typeBadge) {
        typeBadge.textContent = formatTypeCarburant(plein.type_carburant);
        typeBadge.className = `pill ${getTypeClass(plein.type_carburant)}`;
    }
    if (modeBadge) {
        modeBadge.textContent = formatModePaiement(plein.mode_paiement);
        modeBadge.className = `pill ${getModeClass(plein.mode_paiement)}`;
    }
    if (dateEl) dateEl.textContent = formatDate(plein.date_plein);
    if (kmEl) kmEl.textContent = plein.kilometrage ? `${plein.kilometrage.toLocaleString()} km` : '-';
    if (quantiteEl) quantiteEl.textContent = plein.quantite ? `${Number(plein.quantite).toFixed(2)} L` : '-';
    if (prixEl) prixEl.textContent = plein.prix_unitaire ? `${Number(plein.prix_unitaire).toFixed(2)} DA/L` : '-';
    if (montantEl) montantEl.textContent = formatCurrency(plein.montant_total);
    if (stationEl) stationEl.textContent = plein.station || '-';
    if (chauffeurEl) chauffeurEl.textContent = chauffeurLabel(plein.chauffeur);
    if (observationEl) observationEl.textContent = plein.observation || 'Aucune observation.';

    carburantDetailModal.classList.remove('hidden');
}

// ============================================================================
// CRUD Operations
// ============================================================================

async function handleSubmitPlein(e) {
    e.preventDefault();
    ensureAuth();

    const formData = new FormData(carburantForm);
    const data = {};
    formData.forEach((value, key) => {
        if (key === 'montant_total') return; // Server calculates
        if (value !== '') data[key] = value;
    });

    // Ensure chauffeur_id is null if empty
    if (!data.chauffeur_id) data.chauffeur_id = null;

    try {
        if (editingPleinId) {
            await axios.put(`/api/carburant/${editingPleinId}`, data);
            showToast('Plein carburant modifié avec succès');
        } else {
            await axios.post('/api/carburant', data);
            showToast('Plein carburant enregistré avec succès');
        }
        closeCarburantModal();
        await loadPleins();
    } catch (err) {
        const msg = extractErrorMessage(err);
        showToast(msg || 'Erreur lors de l\'enregistrement', 'error');
    }
}

async function deletePlein(id) {
    ensureAuth();

    const confirmed = await showConfirm('Êtes-vous sûr de vouloir supprimer ce plein carburant ?');
    if (!confirmed) return;

    try {
        await axios.delete(`/api/carburant/${id}`);
        showToast('Plein carburant supprimé');
        await loadPleins();
    } catch (err) {
        showToast('Erreur lors de la suppression', 'error');
    }
}

// ============================================================================
// Auto-calculate montant total
// ============================================================================

function autoCalculateMontant() {
    const quantite = parseFloat(carburantQuantiteInput?.value) || 0;
    const prix = parseFloat(carburantPrixInput?.value) || 0;
    const montant = (quantite * prix).toFixed(2);
    if (carburantMontantInput) carburantMontantInput.value = montant > 0 ? montant : '';
}

// ============================================================================
// Export PDF Functions
// ============================================================================

async function exportPleinsPdf() {
    try {
        const params = {};
        const filterType = document.getElementById('carburant-filter-type');
        const filterVehicule = document.getElementById('carburant-filter-vehicule');
        const filterMode = document.getElementById('carburant-filter-mode');
        const filterDateStart = document.getElementById('carburant-filter-date-start');
        const filterDateEnd = document.getElementById('carburant-filter-date-end');

        if (filterType?.value) params.type_carburant = filterType.value;
        if (filterVehicule?.value) params.vehicule_id = filterVehicule.value;
        if (filterMode?.value) params.mode_paiement = filterMode.value;
        if (filterDateStart?.value) params.date_start = filterDateStart.value;
        if (filterDateEnd?.value) params.date_end = filterDateEnd.value;

        showToast('Génération du PDF en cours...', 'info');
        const res = await axios.get('/api/carburant/export-pleins', { params, responseType: 'blob' });
        downloadBlob(res.data, 'pleins-carburant.pdf');
        showToast('PDF exporté avec succès');
    } catch (err) {
        showToast('Erreur lors de l\'export PDF', 'error');
    }
}

async function exportComparaisonPdf() {
    try {
        const params = {};
        const dateStart = document.getElementById('comparaison-date-start')?.value;
        const dateEnd = document.getElementById('comparaison-date-end')?.value;
        const typeCarburant = document.getElementById('comparaison-type-carburant')?.value;
        const categorie = document.getElementById('comparaison-categorie')?.value;

        if (dateStart) params.date_start = dateStart;
        if (dateEnd) params.date_end = dateEnd;
        if (typeCarburant) params.type_carburant = typeCarburant;
        if (categorie) params.categorie = categorie;

        showToast('Génération du PDF en cours...', 'info');
        const res = await axios.get('/api/carburant/export-comparaison', { params, responseType: 'blob' });
        downloadBlob(res.data, 'comparaison-vehicules.pdf');
        showToast('PDF exporté avec succès');
    } catch (err) {
        showToast('Erreur lors de l\'export PDF', 'error');
    }
}

async function exportStatsPdf() {
    try {
        const params = {};
        const dateStart = carburantStatsDateStart?.value;
        const dateEnd = carburantStatsDateEnd?.value;

        if (dateStart) params.date_start = dateStart;
        if (dateEnd) params.date_end = dateEnd;

        showToast('Génération du PDF en cours...', 'info');
        const res = await axios.get('/api/carburant/export-stats', { params, responseType: 'blob' });
        downloadBlob(res.data, 'statistiques-carburant.pdf');
        showToast('PDF exporté avec succès');
    } catch (err) {
        showToast('Erreur lors de l\'export PDF', 'error');
    }
}

function downloadBlob(blob, filename) {
    const url = window.URL.createObjectURL(blob);
    const a = document.createElement('a');
    a.href = url;
    a.download = filename;
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
    window.URL.revokeObjectURL(url);
}

// ============================================================================
// Panel Switching
// ============================================================================

function switchCarburantPanel(tabKey) {
    carburantPanels.forEach(panel => {
        const key = panel.dataset.carburantPanel;
        panel.classList.toggle('active', key === tabKey);
    });

    switch (tabKey) {
        case 'pleins':
            loadPleins();
            break;
        case 'consommation':
            loadConsommation();
            break;
        case 'alertes':
            loadAlertes();
            break;
        case 'comparaison':
            loadComparaison();
            break;
        case 'stats':
            loadAndRenderStats();
            break;
    }
}

// ============================================================================
// Load
// ============================================================================

export async function loadPleins() {
    await fetchPleins();
    renderPleinsTable();
}

// ============================================================================
// Event Initialization
// ============================================================================

export function initializeCarburantEvents() {
    // Listen for vehicules/chauffeurs data updates (must be registered BEFORE data loads)
    document.addEventListener('data:vehicules:updated', populateFilterSelects);
    document.addEventListener('data:chauffeurs:updated', populateFilterSelects);

    // Open modal
    openCarburantModalBtn?.addEventListener('click', () => openCarburantModal());

    // Close modal
    closeCarburantModalBtn?.addEventListener('click', closeCarburantModal);
    cancelCarburantFormBtn?.addEventListener('click', closeCarburantModal);

    // Close detail modal
    closeCarburantDetailModalBtn?.addEventListener('click', () => {
        if (carburantDetailModal) carburantDetailModal.classList.add('hidden');
    });

    // Form submit
    carburantForm?.addEventListener('submit', handleSubmitPlein);

    // Auto-calculate montant
    carburantQuantiteInput?.addEventListener('input', autoCalculateMontant);
    carburantPrixInput?.addEventListener('input', autoCalculateMontant);

    // Table row actions
    carburantTableBody?.addEventListener('click', async (e) => {
        const btn = e.target.closest('[data-action]');
        if (!btn) return;

        const action = btn.dataset.action;
        const id = parseInt(btn.dataset.id);
        const plein = pleins.find(p => p.id === id);

        switch (action) {
            case 'view':
                if (plein) showDetail(plein);
                break;
            case 'edit':
                if (plein) openCarburantModal(plein);
                break;
            case 'delete':
                await deletePlein(id);
                break;
        }
    });

    // Filter changes
    document.addEventListener('change', (e) => {
        if (e.target.id === 'carburant-filter-type' ||
            e.target.id === 'carburant-filter-vehicule' ||
            e.target.id === 'carburant-filter-mode' ||
            e.target.id === 'carburant-filter-date-start' ||
            e.target.id === 'carburant-filter-date-end') {
            loadPleins();
        }
    });

    // Export PDF buttons
    document.getElementById('export-carburant-pleins-pdf')?.addEventListener('click', exportPleinsPdf);
    document.getElementById('export-carburant-stats-pdf')?.addEventListener('click', exportStatsPdf);
    document.getElementById('export-carburant-comparaison-pdf')?.addEventListener('click', exportComparaisonPdf);
    document.getElementById('export-carburant-global-stats-pdf')?.addEventListener('click', exportStatsPdf);

    // Refresh buttons
    document.getElementById('refresh-carburant-consommation')?.addEventListener('click', loadConsommation);
    document.getElementById('refresh-carburant-alertes')?.addEventListener('click', loadAlertes);
    document.getElementById('refresh-carburant-comparaison')?.addEventListener('click', loadComparaison);
    document.getElementById('apply-comparaison-filters')?.addEventListener('click', loadComparaison);
    refreshCarburantStatsBtn?.addEventListener('click', loadAndRenderStats);
    applyCarburantStatsFiltersBtn?.addEventListener('click', loadAndRenderStats);

    // Panel navigation (submenu)
    document.querySelectorAll('[data-carburant-tab]').forEach(btn => {
        btn.addEventListener('click', () => {
            const tab = btn.dataset.carburantTab;
            if (tab) switchCarburantPanel(tab);
        });
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
// Tab Activation (called from utils.js navigation)
// ============================================================================

export function activateCarburantTab(tabKey) {
    switchCarburantPanel(tabKey);
}

// ============================================================================
// Initial Load
// ============================================================================

export async function initializeCarburant() {
    populateFilterSelects();
    await loadPleins();
}
