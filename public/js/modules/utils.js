// ============================================================================
// Utility Functions & Formatting
// ============================================================================
// Shared utilities for formatting, DOM manipulation, and helpers

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
    return Number(value) === 0 ? 'Inactif' : 'Actif';
}

export function formatCategorie(value) {
    const map = { leger: 'Léger', lourd: 'Lourd', transport: 'Transport' };
    return map[value] || '-';
}

export function formatOptionVehicule(value) {
    const map = { base: 'Base', base_clim: 'Base clim', toutes_options: 'Toutes options' };
    return map[value] || '-';
}

export function formatEnergie(value) {
    const map = { essence: 'Essence', diesel: 'Diesel', gpl: 'GPL' };
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

    console.log('Navigation initialized:', {
        navButtons: navButtons.length,
        navSubmenuButtons: navSubmenuButtons.length,
        hasDropdownBtn: !!documentsDropdownBtn
    });

    // Gérer les boutons de navigation principaux
    navButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            // Ignorer si c'est le bouton dropdown
            if (btn.id === 'documents-dropdown-btn') {
                console.log('Toggle dropdown');
                documentsDropdown.classList.toggle('open');
                return;
            }

            navButtons.forEach(b => b.classList.remove('active'));
            navSubmenuButtons.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            sections.forEach(s => s.classList.remove('active'));
            const targetSection = document.getElementById(btn.dataset.target);
            if (targetSection) {
                targetSection.classList.add('active');
            }

            // Fermer le dropdown si on clique ailleurs
            if (documentsDropdown) {
                documentsDropdown.classList.remove('open');
            }
        });
    });

    // Gérer les boutons du sous-menu documents
    navSubmenuButtons.forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            
            const docType = btn.dataset.docType;
            console.log('Submenu clicked:', docType);
            
            // Désactiver tous les boutons principaux et sous-menu
            navButtons.forEach(b => b.classList.remove('active'));
            navSubmenuButtons.forEach(b => b.classList.remove('active'));
            
            // Activer le bouton cliqué et le bouton dropdown parent
            btn.classList.add('active');
            if (documentsDropdownBtn) {
                documentsDropdownBtn.classList.add('active');
            }

            // Afficher la section documents
            sections.forEach(s => s.classList.remove('active'));
            const documentsSection = document.getElementById(btn.dataset.target);
            if (documentsSection) {
                documentsSection.classList.add('active');
            }

            // Activer le bon type de document
            if (docType) {
                // Importer dynamiquement activateDocumentTab
                import('./documents.js').then(module => {
                    console.log('Activating document tab:', docType);
                    module.activateDocumentTab(docType);
                }).catch(err => {
                    console.error('Error loading documents module:', err);
                });
            }
        });
    });
}
