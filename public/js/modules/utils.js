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
    navButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            navButtons.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            sections.forEach(s => s.classList.remove('active'));
            document.getElementById(btn.dataset.target).classList.add('active');
        });
    });
}
