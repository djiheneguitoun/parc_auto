// ============================================================================
// Documents Management (Assurance, Vignette, Contrôle, Entretien, etc.)
// ============================================================================
// Handles document CRUD operations, modal interactions, and rendering

import { state, showToast } from './state.js';
import { ensureAuth } from './auth.js';
import { formatDate, formatCurrency, toInputDate } from './utils.js';

// ============================================================================
// Document Type Configuration
// ============================================================================

export const documentTypeConfig = {
    assurance: {
        label: 'Assurance',
        description: 'Numéro, partenaire, période et facturation.',
        colspan: 8,
        fields: [
            { name: 'vehicule_id', label: 'Véhicule', type: 'vehicule', required: true },
            { name: 'numero', label: 'Numéro', type: 'text' },
            { name: 'libele', label: 'Libellé', type: 'text' },
            { name: 'partenaire', label: 'Partenaire', type: 'text' },
            { name: 'debut', label: 'Début', type: 'date' },
            { name: 'expiration', label: 'Expiration', type: 'date' },
            { name: 'valeur', label: 'Valeur', type: 'number', min: '0', step: '0.01' },
            { name: 'num_facture', label: 'Numéro facture', type: 'text' },
            { name: 'date_facture', label: 'Date facture', type: 'date' },
        ],
    },
    vignette: {
        label: 'Vignette',
        description: 'Numéro, partenaire, période et facturation.',
        colspan: 8,
        fields: [
            { name: 'vehicule_id', label: 'Véhicule', type: 'vehicule', required: true },
            { name: 'numero', label: 'Numéro', type: 'text' },
            { name: 'libele', label: 'Libellé', type: 'text' },
            { name: 'partenaire', label: 'Partenaire', type: 'text' },
            { name: 'debut', label: 'Début', type: 'date' },
            { name: 'expiration', label: 'Expiration', type: 'date' },
            { name: 'valeur', label: 'Valeur', type: 'number', min: '0', step: '0.01' },
            { name: 'num_facture', label: 'Numéro facture', type: 'text' },
            { name: 'date_facture', label: 'Date facture', type: 'date' },
        ],
    },
    controle: {
        label: 'Contrôle',
        description: 'Numéro, partenaire, période et facturation.',
        colspan: 8,
        fields: [
            { name: 'vehicule_id', label: 'Véhicule', type: 'vehicule', required: true },
            { name: 'numero', label: 'Numéro', type: 'text' },
            { name: 'libele', label: 'Libellé', type: 'text' },
            { name: 'partenaire', label: 'Partenaire', type: 'text' },
            { name: 'debut', label: 'Début', type: 'date' },
            { name: 'expiration', label: 'Expiration', type: 'date' },
            { name: 'valeur', label: 'Valeur', type: 'number', min: '0', step: '0.01' },
            { name: 'num_facture', label: 'Numéro facture', type: 'text' },
            { name: 'date_facture', label: 'Date facture', type: 'date' },
        ],
    },
    entretien: {
        label: 'Entretien',
        description: 'Inclut vidange (complet/partiel) et kilométrage.',
        colspan: 10,
        fields: [
            { name: 'vehicule_id', label: 'Véhicule', type: 'vehicule', required: true },
            { name: 'numero', label: 'Numéro', type: 'text' },
            { name: 'libele', label: 'Libellé', type: 'text' },
            { name: 'partenaire', label: 'Partenaire', type: 'text' },
            { name: 'debut', label: 'Début', type: 'date' },
            { name: 'expiration', label: 'Expiration', type: 'date' },
            { name: 'vidange', label: 'Vidange', type: 'select', options: [
                { value: 'complet', label: 'Complet' },
                { value: 'partiel', label: 'Partiel' },
            ] },
            { name: 'kilometrage', label: 'Kilométrage', type: 'number', min: '0', step: '1' },
            { name: 'valeur', label: 'Valeur', type: 'number', min: '0', step: '0.01' },
            { name: 'num_facture', label: 'Numéro facture', type: 'text' },
            { name: 'date_facture', label: 'Date facture', type: 'date' },
        ],
    },
    reparation: {
        label: 'Réparation',
        description: 'Pièce, réparateur, type (carrosserie/mécanique) et facture.',
        colspan: 9,
        fields: [
            { name: 'vehicule_id', label: 'Véhicule', type: 'vehicule', required: true },
            { name: 'numero', label: 'Numéro', type: 'text' },
            { name: 'libele', label: 'Libellé', type: 'text' },
            { name: 'piece', label: 'Pièce', type: 'text' },
            { name: 'reparateur', label: 'Réparateur', type: 'text' },
            { name: 'type_reparation', label: 'Type', type: 'select', options: [
                { value: 'carosserie', label: 'Carrosserie' },
                { value: 'mecanique', label: 'Mécanique' },
            ] },
            { name: 'date_reparation', label: 'Date', type: 'date' },
            { name: 'valeur', label: 'Valeur', type: 'number', min: '0', step: '0.01' },
            { name: 'num_facture', label: 'Numéro facture', type: 'text' },
            { name: 'date_facture', label: 'Date facture', type: 'date' },
        ],
    },
    bon_essence: {
        label: "Bon d'essence",
        description: 'Type de carburant, kilométrage et utilisation.',
        colspan: 9,
        fields: [
            { name: 'vehicule_id', label: 'Véhicule', type: 'vehicule', required: true },
            { name: 'numero', label: 'Numéro', type: 'text' },
            { name: 'debut', label: 'Date', type: 'date' },
            { name: 'typecarburant', label: 'Type carburant', type: 'select', options: [
                { value: 'essence', label: 'Essence' },
                { value: 'gasoil', label: 'Gasoil' },
                { value: 'gpl', label: 'GPL' },
            ] },
            { name: 'kilometrage', label: 'Kilométrage', type: 'number', min: '0', step: '1' },
            { name: 'utilisation', label: 'Utilisation', type: 'select', options: [
                { value: 'trajet', label: 'Trajet' },
                { value: 'interne', label: 'Interne' },
            ] },
            { name: 'valeur', label: 'Valeur', type: 'number', min: '0', step: '0.01' },
            { name: 'num_facture', label: 'Numéro facture', type: 'text' },
            { name: 'date_facture', label: 'Date facture', type: 'date' },
        ],
    },
};

// ============================================================================
// Formatting Functions for Documents
// ============================================================================

function formatDocVidange(value) {
    const map = { complet: 'Complet', partiel: 'Partiel' };
    return map[value] || '-';
}

function formatDocTypeReparation(value) {
    const map = { carosserie: 'Carrosserie', mecanique: 'Mécanique' };
    return map[value] || '-';
}

function formatDocCarburant(value) {
    const map = { essence: 'Essence', gasoil: 'Gasoil', gpl: 'GPL' };
    return map[value] || '-';
}

function formatDocUtilisation(value) {
    const map = { trajet: 'Trajet', interne: 'Interne' };
    return map[value] || '-';
}

function formatDocFactureNum(doc) {
    return doc.num_facture ? `#${doc.num_facture}` : '-';
}

function formatDocFactureDate(doc) {
    return formatDate(doc.date_facture);
}

function vehiculeLabel(doc) {
    const vehicle = doc?.vehicule;
    if (vehicle) {
        const labelParts = [vehicle.code, vehicle.numero, vehicle.marque, vehicle.modele].filter(Boolean);
        if (labelParts.length) return labelParts.join(' · ');
    }
    return doc?.vehicule_id ? `ID ${doc.vehicule_id}` : '-';
}

// ============================================================================
// DOM Elements
// ============================================================================

export const documentTabs = document.querySelectorAll('[data-doc-tab]');
export const documentPanels = document.querySelectorAll('[data-doc-panel]');
export const documentModal = document.getElementById('document-modal');
export const documentForm = document.getElementById('document-form');
export const documentFormFields = document.getElementById('document-form-fields');
export const documentFormTitle = document.getElementById('document-form-title');
export const documentFormDescription = document.getElementById('document-form-description');
export const documentFormSubmit = document.getElementById('document-form-submit');
export const closeDocumentModalBtn = document.getElementById('close-document-modal');
export const documentSection = document.getElementById('documents');
export const documentTypeInput = document.getElementById('document-type-input');
export const documentTableBodies = {
    assurance: document.getElementById('document-rows-assurance'),
    vignette: document.getElementById('document-rows-vignette'),
    controle: document.getElementById('document-rows-controle'),
    entretien: document.getElementById('document-rows-entretien'),
    reparation: document.getElementById('document-rows-reparation'),
    bon_essence: document.getElementById('document-rows-bon_essence'),
};
export const documentVehiculeFilter = document.getElementById('document-vehicule-filter');

// ============================================================================
// Form & Rendering Functions
// ============================================================================

function renderVehiculeOptions(selectedId = '') {
    const options = state.vehicules.map(v => {
        const label = [v.code, v.numero, v.marque, v.modele].filter(Boolean).join(' · ') || `Véhicule ${v.id}`;
        const selected = Number(selectedId) === Number(v.id) ? 'selected' : '';
        return `<option value="${v.id}" ${selected}>${label}</option>`;
    }).join('');
    return `<option value="">Choisir un véhicule</option>${options}`;
}

function renderDocumentField(field, doc = {}) {
    const valueRaw = doc[field.name];
    const value = field.type === 'date' ? toInputDate(valueRaw) : (valueRaw ?? '');
    const required = field.required ? 'required' : '';
    if (field.type === 'vehicule') {
        return `
            <div><label>${field.label}</label>
                <select name="${field.name}" ${required}>${renderVehiculeOptions(value)}</select>
            </div>`;
    }
    if (field.type === 'select') {
        const options = (field.options || []).map(opt => {
            const sel = String(opt.value) === String(value) ? 'selected' : '';
            return `<option value="${opt.value}" ${sel}>${opt.label}</option>`;
        }).join('');
        return `
            <div><label>${field.label}</label>
                <select name="${field.name}" ${required}>
                    <option value=""></option>
                    ${options}
                </select>
            </div>`;
    }
    const minAttr = field.min ? `min="${field.min}"` : '';
    const stepAttr = field.step ? `step="${field.step}"` : '';
    const typeAttr = field.type || 'text';
    return `
        <div><label>${field.label}</label>
            <input name="${field.name}" type="${typeAttr}" value="${value}" ${required} ${minAttr} ${stepAttr}>
        </div>`;
}

function renderDocumentFormFields(type, doc = {}) {
    const config = documentTypeConfig[type];
    if (!config) return;
    documentFormFields.innerHTML = config.fields.map(field => renderDocumentField(field, doc)).join('');
}

function renderDocumentRow(type, doc) {
    if (['assurance', 'vignette', 'controle'].includes(type)) {
        return `
            <tr data-doc-id="${doc.id}" data-doc-type="${type}">
                <td>${doc.numero || '-'}</td>
                <td>${doc.libele || '-'}</td>
                <td>${doc.partenaire || '-'}</td>
                <td>${formatDate(doc.debut)}</td>
                <td>${formatDate(doc.expiration)}</td>
                <td>${formatCurrency(doc.valeur)}</td>
                <td>${formatDocFactureNum(doc)}</td>
                <td>${formatDocFactureDate(doc)}</td>
                <td class="row-actions">
                    <button class="btn secondary xs" data-doc-action="edit" type="button">Modifier</button>
                    <button class="btn danger xs" data-doc-action="delete" type="button">Supprimer</button>
                </td>
            </tr>`;
    }
    if (type === 'entretien') {
        return `
            <tr data-doc-id="${doc.id}" data-doc-type="${type}">
                <td>${doc.numero || '-'}</td>
                <td>${doc.libele || '-'}</td>
                <td>${doc.partenaire || '-'}</td>
                <td>${formatDate(doc.debut)}</td>
                <td>${formatDate(doc.expiration)}</td>
                <td>${formatDocVidange(doc.vidange)}</td>
                <td>${doc.kilometrage ?? '-'}</td>
                <td>${formatCurrency(doc.valeur)}</td>
                <td>${formatDocFactureNum(doc)}</td>
                <td>${formatDocFactureDate(doc)}</td>
                <td class="row-actions">
                    <button class="btn secondary xs" data-doc-action="edit" type="button">Modifier</button>
                    <button class="btn danger xs" data-doc-action="delete" type="button">Supprimer</button>
                </td>
            </tr>`;
    }
    if (type === 'reparation') {
        return `
            <tr data-doc-id="${doc.id}" data-doc-type="${type}">
                <td>${doc.numero || '-'}</td>
                <td>${doc.piece || '-'}</td>
                <td>${doc.reparateur || '-'}</td>
                <td>${formatDocTypeReparation(doc.type_reparation)}</td>
                <td>${formatDate(doc.date_reparation)}</td>
                <td>${formatCurrency(doc.valeur)}</td>
                <td>${formatDocFactureNum(doc)}</td>
                <td>${formatDocFactureDate(doc)}</td>
                <td class="row-actions">
                    <button class="btn secondary xs" data-doc-action="edit" type="button">Modifier</button>
                    <button class="btn danger xs" data-doc-action="delete" type="button">Supprimer</button>
                </td>
            </tr>`;
    }
    if (type === 'bon_essence') {
        return `
            <tr data-doc-id="${doc.id}" data-doc-type="${type}">
                <td>${doc.numero || '-'}</td>
                <td>${formatDate(doc.debut)}</td>
                <td>${formatDocCarburant(doc.typecarburant)}</td>
                <td>${doc.kilometrage ?? '-'}</td>
                <td>${formatDocUtilisation(doc.utilisation)}</td>
                <td>${formatCurrency(doc.valeur)}</td>
                <td>${formatDocFactureNum(doc)}</td>
                <td>${formatDocFactureDate(doc)}</td>
                <td class="row-actions">
                    <button class="btn secondary xs" data-doc-action="edit" type="button">Modifier</button>
                    <button class="btn danger xs" data-doc-action="delete" type="button">Supprimer</button>
                </td>
            </tr>`;
    }
    return '';
}

function renderDocumentTables() {
    const selectedVehicleId = documentVehiculeFilter.value;
    Object.entries(documentTableBodies).forEach(([type, tbody]) => {
        let docs = state.documents[type] || [];
        if (selectedVehicleId) {
            docs = docs.filter(doc => Number(doc.vehicule_id) === Number(selectedVehicleId));
        }
        const rows = docs.map(doc => renderDocumentRow(type, doc)).join('');
        const colspans = {
            assurance: 9,
            vignette: 9,
            controle: 9,
            entretien: 11,
            reparation: 9,
            bon_essence: 9,
        };
        const colspan = colspans[type] || 6;
        tbody.innerHTML = rows || `<tr><td colspan="${colspan}" class="muted">Aucun document ${documentTypeConfig[type]?.label?.toLowerCase() || ''}.</td></tr>`;
    });
}

export function activateDocumentTab(type) {
    state.documentCurrentType = type;
    documentTabs.forEach(tab => tab.classList.toggle('active', tab.dataset.docTab === type));
    documentPanels.forEach(panel => panel.classList.toggle('active', panel.dataset.docPanel === type));
}

export function openDocumentModal(type, doc = null) {
    const config = documentTypeConfig[type];
    if (!config) return;
    state.documentCurrentType = type;
    state.documentEditingId = doc?.id || null;
    documentTypeInput.value = type;
    documentForm.reset();
    documentFormTitle.textContent = `${doc ? 'Modifier' : 'Ajouter'} ${config.label.toLowerCase()}`;
    documentFormDescription.textContent = config.description || '';
    documentFormSubmit.textContent = doc ? 'Mettre à jour' : 'Enregistrer';
    renderDocumentFormFields(type, doc || {});
    documentModal.classList.remove('hidden');
}

export function closeDocumentModal() {
    state.documentEditingId = null;
    documentModal.classList.add('hidden');
    documentForm.reset();
}

export async function loadDocuments() {
    ensureAuth();
    const types = Object.keys(documentTypeConfig);
    const requests = types.map(type => axios.get('/api/vehicule-documents', { params: { type, per_page: 200 } }));
    const responses = await Promise.all(requests);
    responses.forEach((res, idx) => {
        const type = types[idx];
        const list = res?.data?.data || res?.data || [];
        state.documents[type] = list;
    });
    populateVehiculeFilter();
    renderDocumentTables();
}

// ============================================================================
// Event Listeners
// ============================================================================

function populateVehiculeFilter() {
    const options = state.vehicules.map(v => {
        const label = [v.code, v.numero, v.marque, v.modele].filter(Boolean).join(' · ') || `Véhicule ${v.id}`;
        return `<option value="${v.id}">${label}</option>`;
    }).join('');
    documentVehiculeFilter.innerHTML = `<option value="">-- Tous les véhicules --</option>${options}`;
}

export function initializeDocumentEvents() {
    documentVehiculeFilter.addEventListener('change', () => {
        renderDocumentTables();
    });
    
    documentTabs.forEach(tab => {
        tab.addEventListener('click', () => activateDocumentTab(tab.dataset.docTab));
    });

    documentSection.querySelectorAll('[data-doc-add]').forEach(btn => {
        btn.addEventListener('click', () => openDocumentModal(btn.dataset.docAdd));
    });

    closeDocumentModalBtn.addEventListener('click', closeDocumentModal);
    documentModal.addEventListener('click', (e) => {
        if (e.target.dataset.close === 'document-modal') {
            closeDocumentModal();
        }
    });

    documentForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        ensureAuth();
        const payload = Object.fromEntries(new FormData(documentForm).entries());
        const type = state.documentCurrentType;
        payload.type = type;
        const isEdit = Boolean(state.documentEditingId);
        if (isEdit) {
            await axios.put(`/api/vehicule-documents/${state.documentEditingId}`, payload);
        } else {
            await axios.post('/api/vehicule-documents', payload);
        }
        closeDocumentModal();
        await loadDocuments();
        showToast(isEdit ? 'Document mis à jour.' : 'Document ajouté.');
    });

    documentSection.addEventListener('click', async (e) => {
        const actionBtn = e.target.closest('[data-doc-action]');
        if (!actionBtn) return;
        const row = actionBtn.closest('tr[data-doc-id]');
        if (!row) return;
        const id = row.dataset.docId;
        const type = row.dataset.docType;
        if (actionBtn.dataset.docAction === 'edit') {
            const doc = (state.documents[type] || []).find(d => Number(d.id) === Number(id));
            openDocumentModal(type, doc || { id });
            return;
        }
        if (actionBtn.dataset.docAction === 'delete') {
            const confirmed = window.confirm('Supprimer ce document ?');
            if (!confirmed) return;
            await axios.delete(`/api/vehicule-documents/${id}`);
            await loadDocuments();
            showToast('Document supprimé.');
        }
    });
}
