// ============================================================================
// Utility Functions & Navigation Helpers
// ============================================================================

import { state } from './state.js';

const ETAT_LABELS = {
    disponible: 'Disponible',
    utilisation: 'Utilisation',
    technique: 'Technique',
    reglementaire: 'Réglementaire',
    incident: 'Incident',
    fin_de_vie: 'Fin de vie',
};

const STATUT_LABELS = {
    disponible: 'Disponible',
    en_service: 'En service',
    reserve: 'Réservé',
    en_maintenance: 'En maintenance',
    en_panne: 'En panne',
    en_reparation: 'En réparation',
    non_conforme: 'Non conforme',
    interdit: 'Interdit',
    sinistre: 'Sinistré',
    en_expertise: 'En expertise',
    reforme: 'Réformé',
    sorti_du_parc: 'Sorti du parc (Cédé)',
};

const MENTION_CONFIG = {
    excellent: { label: 'Excellent', score: 5 },
    tres_bon: { label: 'Très bon', score: 4 },
    bon: { label: 'Bon', score: 3 },
    moyen: { label: 'Moyen', score: 2 },
    insuffisant: { label: 'Insuffisant', score: 1 },
};

const COMPORTEMENT_LABELS = {
    excellent: 'Excellent',
    tres_bon: 'Très bon',
    satisfaisant: 'Satisfaisant',
    a_ameliorer: 'À améliorer',
    insuffisant: 'Insuffisant',
    non_conforme: 'Non conforme',
    a_risque: 'À risque',
};

const SINISTRE_STATUT_LABELS = {
    declare: 'Déclaré',
    en_cours: 'En cours',
    en_reparation: 'En réparation',
    clos: 'Clos',
};

const SINISTRE_TYPE_LABELS = {
    accident: 'Accident',
    panne: 'Panne',
    vol: 'Vol',
    incendie: 'Incendie',
};

const SINISTRE_GRAVITE_LABELS = {
    mineur: 'Mineur',
    moyen: 'Moyen',
    grave: 'Grave',
};

const SINISTRE_RESPONSABLE_LABELS = {
    chauffeur: 'Chauffeur',
    tiers: 'Tiers',
    inconnu: 'Inconnu',
};

export function formatDate(date) {
    if (!date) return '-';
    const parsed = new Date(date);
    return Number.isNaN(parsed.getTime()) ? '-' : parsed.toLocaleDateString('fr-FR');
}

export function formatTime(value) {
    if (!value) return '-';
    if (/^\d{2}:\d{2}$/.test(value)) return value;
    if (/^\d{2}:\d{2}:\d{2}$/.test(value)) return value.slice(0, 5);
    return value;
}

export function formatMention(value) {
    const config = MENTION_CONFIG[value];
    if (!config) return value || '-';
    return config.label;
}

export function formatMentionStars(value) {
    const config = MENTION_CONFIG[value];
    if (!config) return value || '-';
    return starString(config.score);
}

export function formatVehiculeStatut(value) {
    return STATUT_LABELS[value] || '-';
}

export function formatEtatFonctionnel(value) {
    return ETAT_LABELS[value] || '-';
}

export function formatCategorie(value) {
    const map = { leger: 'Léger', lourd: 'Lourd', transport: 'Transport', tracteur: 'Tracteur', engins: 'Engins' };
    return map[value] || '-';
}

export function formatOptionVehicule(value) {
    const map = { base: 'Base', base_clim: 'Base clim', toutes_options: 'Toutes options' };
    return map[value] || '-';
}

export function formatEnergie(value) {
    const map = { essence: 'Essence', diesel: 'Diesel', gpl: 'GPL', electrique: 'Électrique' };
    return map[value] || '-';
}

export function formatBoite(value) {
    const map = { semiauto: 'Semi-auto', auto: 'Auto', manuel: 'Manuel' };
    return map[value] || '-';
}

export function formatLeasing(value) {
    const map = { location: 'Location', acquisition: 'Acquisition', autre: 'Autre' };
    return map[value] || '-';
}

export function formatUtilisation(value) {
    const map = { personnel: 'Personnel', professionnel: 'Professionnel' };
    return map[value] || '-';
}

export function formatUserStatus(value) {
    return Number(value) === 1 ? 'Actif' : 'Inactif';
}

export function formatComportement(value) {
    return COMPORTEMENT_LABELS[value] || '-';
}

// Comportement badge avec couleur
export function formatComportementBadge(value) {
    const label = COMPORTEMENT_LABELS[value] || '-';
    const colorMap = {
        excellent: 'success',
        tres_bon: 'primary',
        satisfaisant: 'info',
        a_ameliorer: 'warning',
        insuffisant: 'danger',
        non_conforme: 'danger',
        a_risque: 'danger'
    };
    const color = colorMap[value] || 'gray';
    return `<span class="badge ${color}">${label}</span>`;
}

export function formatStatut(value) {
    const map = { contractuel: 'Contractuel', permanent: 'Permanent' };
    return map[value] || '-';
}

export function formatSinistreStatut(value) {
    return SINISTRE_STATUT_LABELS[value] || '-';
}

export function formatSinistreType(value) {
    return SINISTRE_TYPE_LABELS[value] || '-';
}

export function formatSinistreGravite(value) {
    return SINISTRE_GRAVITE_LABELS[value] || '-';
}

export function formatSinistreResponsable(value) {
    return SINISTRE_RESPONSABLE_LABELS[value] || '-';
}

export function formatCurrency(value) {
    if (value === null || value === undefined || value === '') return '-';
    const num = Number(value);
    if (Number.isNaN(num)) return value;
    return new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'DZD', minimumFractionDigits: 0 }).format(num);
}

export function resolveVehiculeImageSrc(path) {
    if (!path) return '';
    if (/^https?:\/\//i.test(path)) return path;
    if (path.startsWith('/')) return path;
    if (path.startsWith('storage/')) return `/${path}`;
    if (path.startsWith('images/')) return `/${path}`;
    return `/storage/${path}`;
}

export function toInputDate(value) {
    if (!value) return '';
    const date = new Date(value);
    if (Number.isNaN(date.getTime())) {
        return String(value).slice(0, 10);
    }
    return date.toISOString().slice(0, 10);
}

// ============================================================================
// Navigation & Sections Management
// ============================================================================

export function initializeNavigation() {
    const sections = document.querySelectorAll('.section');
    const navButtons = document.querySelectorAll('.nav-btn');
    const documentSubmenuButtons = document.querySelectorAll('.nav-submenu-btn[data-doc-type]');
    const sinistreSubmenuButtons = document.querySelectorAll('.nav-submenu-btn[data-sinistre-tab]');
    const documentsDropdownBtn = document.getElementById('documents-dropdown-btn');
    const documentsDropdown = documentsDropdownBtn?.parentElement;
    const sinistresDropdownBtn = document.getElementById('sinistres-dropdown-btn');
    const sinistresDropdown = sinistresDropdownBtn?.parentElement;

    const storageKeys = {
        section: 'nav:lastSection',
        docType: 'nav:lastDocType',
        sinistreTab: 'nav:lastSinistreTab',
    };

    const saveNavState = (sectionId, tab = null) => {
        if (sectionId) localStorage.setItem(storageKeys.section, sectionId);
        if (tab && sectionId === 'documents') localStorage.setItem(storageKeys.docType, tab);
        if (tab && sectionId === 'sinistres') localStorage.setItem(storageKeys.sinistreTab, tab);
    };

    const resetNav = () => {
        navButtons.forEach(b => b.classList.remove('active'));
        documentSubmenuButtons.forEach(b => b.classList.remove('active'));
        sinistreSubmenuButtons.forEach(b => b.classList.remove('active'));
        sections.forEach(s => s.classList.remove('active'));
        if (documentsDropdown) documentsDropdown.classList.remove('open');
        if (sinistresDropdown) sinistresDropdown.classList.remove('open');
    };

    const activateDocuments = (docType) => {
        const typeToUse = docType || localStorage.getItem(storageKeys.docType) || state.documentCurrentType || 'assurance';
        const targetSection = document.getElementById('documents');

        resetNav();
        if (documentsDropdownBtn) documentsDropdownBtn.classList.add('active');
        if (documentsDropdown) documentsDropdown.classList.add('open');
        if (targetSection) targetSection.classList.add('active');

        const matchingSubBtn = Array.from(documentSubmenuButtons).find(btn => btn.dataset.docType === typeToUse);
        if (matchingSubBtn) matchingSubBtn.classList.add('active');

        saveNavState('documents', typeToUse);

        import('./documents.js')
            .then(module => module.activateDocumentTab(typeToUse))
            .catch(err => console.error('Error activating documents tab:', err));
    };

    const activateSinistres = (tabKey) => {
        const tabToUse = tabKey || localStorage.getItem(storageKeys.sinistreTab) || state.sinistreCurrentTab || 'tableau';
        const targetSection = document.getElementById('sinistres');

        resetNav();
        if (sinistresDropdownBtn) sinistresDropdownBtn.classList.add('active');
        if (sinistresDropdown) sinistresDropdown.classList.add('open');
        if (targetSection) targetSection.classList.add('active');

        const matchingSubBtn = Array.from(sinistreSubmenuButtons).find(btn => btn.dataset.sinistreTab === tabToUse);
        if (matchingSubBtn) matchingSubBtn.classList.add('active');

        saveNavState('sinistres', tabToUse);

        import('./sinistres.js')
            .then(module => module.activateSinistreTab(tabToUse))
            .catch(err => console.error('Error activating sinistres tab:', err));
    };

    const activateMainSection = (sectionId) => {
        resetNav();
        const targetSection = document.getElementById(sectionId);
        const targetBtn = Array.from(navButtons).find(b => b.dataset.target === sectionId);
        if (targetBtn) targetBtn.classList.add('active');
        if (targetSection) targetSection.classList.add('active');
        saveNavState(sectionId);
    };

    navButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            if (btn.id === 'documents-dropdown-btn') {
                // Toggle: fermer si déjà ouvert
                if (documentsDropdown?.classList.contains('open')) {
                    documentsDropdown.classList.remove('open');
                } else {
                    activateDocuments();
                }
                return;
            }
            if (btn.id === 'sinistres-dropdown-btn') {
                // Toggle: fermer si déjà ouvert
                if (sinistresDropdown?.classList.contains('open')) {
                    sinistresDropdown.classList.remove('open');
                } else {
                    activateSinistres();
                }
                return;
            }
            activateMainSection(btn.dataset.target);
        });
    });

    documentSubmenuButtons.forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            activateDocuments(btn.dataset.docType);
        });
    });

    sinistreSubmenuButtons.forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            activateSinistres(btn.dataset.sinistreTab);
        });
    });

    const lastSection = localStorage.getItem(storageKeys.section) || 'overview';
    const lastDocType = localStorage.getItem(storageKeys.docType) || state.documentCurrentType || 'assurance';
    const lastSinistreTab = localStorage.getItem(storageKeys.sinistreTab) || state.sinistreCurrentTab || 'tableau';

    if (lastSection === 'documents') {
        activateDocuments(lastDocType);
    } else if (lastSection === 'sinistres') {
        activateSinistres(lastSinistreTab);
    } else {
        activateMainSection(lastSection);
    }
}

function starString(score) {
    const safeScore = Math.min(5, Math.max(0, Number(score) || 0));
    const filled = '★'.repeat(safeScore);
    const empty = '☆'.repeat(5 - safeScore);
    return `<span class="stars" title="${safeScore}/5"><span class="filled">${filled}</span><span class="empty">${empty}</span></span>`;
}
