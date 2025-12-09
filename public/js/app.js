// ============================================================================
// Main Application Entry Point (ES6 Modules)
// ============================================================================
// Initializes all modules and loads data on app startup

// Import state and core utilities
import { state, showToast } from './modules/state.js';
import { initializeNavigation } from './modules/utils.js';

// Import authentication
import { bootstrapAuth } from './modules/auth.js';

// Import feature modules
import { 
    initializeChauffeurEvents, 
    loadChauffeurs 
} from './modules/chauffeurs.js';

import { 
    initializeVehiculeEvents, 
    loadVehicules 
} from './modules/vehicules.js';

import { 
    initializeUserEvents, 
    loadUsers 
} from './modules/users.js';

import { 
    initializeDocumentEvents, 
    loadDocuments,
    activateDocumentTab,
    documentTypeConfig
} from './modules/documents.js';

import { 
    initializeParametresEvents,
    loadParametres 
} from './modules/parametres.js';

import { initializeReportsEvents } from './modules/reports.js';

// ============================================================================
// Application Initialization
// ============================================================================

/**
 * Load all metrics (dashboard counters)
 */
async function loadMetrics() {
    const [ch, v, d, u] = await Promise.all([
        axios.get('/api/chauffeurs'),
        axios.get('/api/vehicules'),
        axios.get('/api/vehicule-documents'),
        axios.get('/api/utilisateurs'),
    ]);
    document.getElementById('metric-chauffeurs').textContent = ch.data.total || ch.data.data.length;
    document.getElementById('metric-vehicules').textContent = v.data.total || v.data.data.length;
    document.getElementById('metric-documents').textContent = d.data.total || d.data.data.length;
    document.getElementById('metric-users').textContent = u.data.total || u.data.data.length;
}

// ============================================================================
// Dashboard Charts
// ============================================================================

let documentSoonChart = null;
let vehicleCostChart = null;
const chartPalette = ['#1e2d78', '#1e9e6d', '#f4b000', '#d9534f', '#5a6c90', '#7a3ff2'];

function daysUntil(dateString) {
    if (!dateString) return null;
    const d = new Date(dateString);
    if (Number.isNaN(d.getTime())) return null;
    const diffMs = d.getTime() - Date.now();
    return Math.floor(diffMs / (1000 * 60 * 60 * 24));
}

function getDocumentSoonStats() {
    const labels = [];
    const data = [];
    Object.entries(state.documents || {}).forEach(([type, list]) => {
        const count = (list || []).filter(doc => {
            const days = daysUntil(doc.expiration || doc.date_facture || doc.debut);
            return days !== null && days >= 0 && days <= 30;
        }).length;
        labels.push(documentTypeConfig[type]?.label || type);
        data.push(count);
    });
    if (!labels.length) {
        return { labels: ['Aucune donnée'], data: [0] };
    }
    return { labels, data };
}

function getVehicleCostStats() {
    const labelMap = { leger: 'Léger', lourd: 'Lourd', transport: 'Transport' };
    const totals = {};
    const counts = {};
    state.vehicules.forEach(v => {
        const key = v.categorie || 'inconnu';
        const val = Number(v.valeur) || 0;
        totals[key] = (totals[key] || 0) + val;
        counts[key] = (counts[key] || 0) + 1;
    });
    const labelsRaw = Object.keys(totals).length ? Object.keys(totals) : ['inconnu'];
    const labels = labelsRaw.map(k => labelMap[k] || 'Non renseigné');
    const data = labelsRaw.map(k => {
        const avg = (totals[k] || 0) / (counts[k] || 1);
        return Math.round(avg * 100) / 100;
    });
    return { labels, data };
}

function renderDashboardCharts() {
    if (typeof Chart === 'undefined') return;
    const documentsCanvas = document.getElementById('chart-documents-soon');
    const vehiculeCanvas = document.getElementById('chart-vehicules-cout');
    if (!vehiculeCanvas || !documentsCanvas) return;

    const documentStats = getDocumentSoonStats();
    const vehiculeStats = getVehicleCostStats();

    if (documentSoonChart) {
        documentSoonChart.data.labels = documentStats.labels;
        documentSoonChart.data.datasets[0].data = documentStats.data;
        documentSoonChart.update();
    } else {
        documentSoonChart = new Chart(documentsCanvas, {
            type: 'bar',
            data: {
                labels: documentStats.labels,
                datasets: [{
                    label: 'Docs < 30j',
                    data: documentStats.data,
                    backgroundColor: documentStats.labels.map((_, idx) => chartPalette[idx % chartPalette.length]),
                    borderRadius: 8,
                }],
            },
            options: {
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 1 } }
                }
            },
        });
    }

    if (vehicleCostChart) {
        vehicleCostChart.data.labels = vehiculeStats.labels;
        vehicleCostChart.data.datasets[0].data = vehiculeStats.data;
        vehicleCostChart.update();
    } else {
        vehicleCostChart = new Chart(vehiculeCanvas, {
            type: 'bar',
            data: {
                labels: vehiculeStats.labels,
                datasets: [{
                    label: 'Coût moyen (DZD)',
                    data: vehiculeStats.data,
                    backgroundColor: vehiculeStats.labels.map((_, idx) => chartPalette[idx % chartPalette.length]),
                    borderRadius: 8,
                }],
            },
            options: {
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true }
                }
            },
        });
    }
}

/**
 * Initialize the entire application
 */
function initializeApp() {
    // Initialize navigation system
    initializeNavigation();

    // Initialize all feature event listeners
    initializeChauffeurEvents();
    initializeVehiculeEvents();
    initializeUserEvents();
    initializeDocumentEvents();
    initializeParametresEvents();
    initializeReportsEvents();

    // Load all initial data
    Promise.all([
        loadChauffeurs(),
        loadVehicules(),
        loadUsers(),
        loadDocuments(),
        loadMetrics(),
        loadParametres(),
    ]).then(() => {
        renderDashboardCharts();
    }).catch(err => console.error('Error loading initial data:', err));

    // Activate the first document tab
    activateDocumentTab(state.documentCurrentType);

    document.addEventListener('data:vehicules:updated', renderDashboardCharts);
    document.addEventListener('data:documents:updated', renderDashboardCharts);
}

// ============================================================================
// Bootstrap the app
// ============================================================================

if (bootstrapAuth()) {
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initializeApp);
    } else {
        initializeApp();
    }
}

