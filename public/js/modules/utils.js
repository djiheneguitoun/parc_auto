// ============================================================================
// Utility Functions & Formatting
// ============================================================================
// Shared utilities for formatting, DOM manipulation, and helpers

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

export function formatDate(date) {
    if (!date) return '-';
    const parsed = new Date(date);
    return isNaN(parsed) ? '-' : parsed.toLocaleDateString('fr-FR');
}

export function formatMention(value) {
    const map = { tres_bien: 'Très bien', bien: 'Bien', mauvais: 'Mauvais', blame: 'Blâme' };
    return map[value] || value || '-';
}

export function formatStatut(value) {
    const map = { contractuel: 'Contractuel', permanent: 'Permanent' };
    return map[value] || value || '-';
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
    const navSubmenuButtons = document.querySelectorAll('.nav-submenu-btn');
    const documentsDropdownBtn = document.getElementById('documents-dropdown-btn');
    const documentsDropdown = documentsDropdownBtn?.parentElement;

    const storageKeys = {
        section: 'nav:lastSection',
        docType: 'nav:lastDocType',
    };

    const saveNavState = (sectionId, docType = null) => {
        if (sectionId) localStorage.setItem(storageKeys.section, sectionId);
        if (docType) localStorage.setItem(storageKeys.docType, docType);
    };

    const activateDocuments = (docType) => {
        const typeToUse = docType || localStorage.getItem(storageKeys.docType) || state.documentCurrentType;
        const targetSection = document.getElementById('documents');

        navButtons.forEach(b => b.classList.remove('active'));
        navSubmenuButtons.forEach(b => b.classList.remove('active'));
        sections.forEach(s => s.classList.remove('active'));

        if (documentsDropdownBtn) {
            documentsDropdownBtn.classList.add('active');
        }
        if (documentsDropdown) {
            documentsDropdown.classList.add('open');
        }
        if (targetSection) {
            targetSection.classList.add('active');
        }

        const matchingSubBtn = Array.from(navSubmenuButtons).find(btn => btn.dataset.docType === typeToUse);
        if (matchingSubBtn) {
            matchingSubBtn.classList.add('active');
        }

        // Persist immediately so refresh restores Documents even if module import fails
        saveNavState('documents', typeToUse);

        import('./documents.js').then(module => {
            module.activateDocumentTab(typeToUse);
        }).catch(err => console.error('Error activating documents tab:', err));
    };

    const activateMainSection = (sectionId) => {
        navButtons.forEach(b => b.classList.remove('active'));
        navSubmenuButtons.forEach(b => b.classList.remove('active'));
        sections.forEach(s => s.classList.remove('active'));

        const targetSection = document.getElementById(sectionId);
        const targetBtn = Array.from(navButtons).find(b => b.dataset.target === sectionId);
        if (targetBtn) targetBtn.classList.add('active');
        if (targetSection) targetSection.classList.add('active');

        if (documentsDropdown) {
            documentsDropdown.classList.remove('open');
        }

        saveNavState(sectionId);
    };

    console.log('Navigation initialized:', {
        navButtons: navButtons.length,
        navSubmenuButtons: navSubmenuButtons.length,
        hasDropdownBtn: !!documentsDropdownBtn
    });

    // Gérer les boutons de navigation principaux
    navButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            if (btn.id === 'documents-dropdown-btn') {
                activateDocuments();
                return;
            }
            activateMainSection(btn.dataset.target);
        });
    });

    // Gérer les boutons du sous-menu documents
    navSubmenuButtons.forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            
            const docType = btn.dataset.docType;
            console.log('Submenu clicked:', docType);
            
            activateDocuments(docType);
        });
    });

    // Restaurer la dernière section visitée
    const lastSection = localStorage.getItem(storageKeys.section) || 'overview';
    const lastDocType = localStorage.getItem(storageKeys.docType) || state.documentCurrentType;
    if (lastSection === 'documents') {
        activateDocuments(lastDocType);
    } else {
        activateMainSection(lastSection);
    }
}
