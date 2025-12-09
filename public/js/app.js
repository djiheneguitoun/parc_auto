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
    activateDocumentTab 
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
    ]).catch(err => console.error('Error loading initial data:', err));

    // Activate the first document tab
    activateDocumentTab(state.documentCurrentType);
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

