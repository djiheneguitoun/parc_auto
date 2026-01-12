// ============================================================================
// Reports / Exports
// ============================================================================
// Handles vehicle report export with filters and PDF download

import { ensureAuth } from './auth.js';
import { showToast, extractErrorMessage, state } from './state.js';

const modalTriggers = document.querySelectorAll('[data-report-modal]');
const closeButtons = document.querySelectorAll('[data-close]');

const vehiculesForm = document.getElementById('vehicules-report-form');
const chauffeursForm = document.getElementById('chauffeurs-report-form');
const chargesForm = document.getElementById('charges-report-form');
const facturesForm = document.getElementById('factures-report-form');

const vehiculesSubmit = document.getElementById('vehicules-report-submit');
const chauffeursSubmit = document.getElementById('chauffeurs-report-submit');
const chargesSubmit = document.getElementById('charges-report-submit');
const facturesSubmit = document.getElementById('factures-report-submit');

// Preview buttons
const vehiculesPreview = document.getElementById('vehicules-report-preview');
const chauffeursPreview = document.getElementById('chauffeurs-report-preview');
const chargesPreview = document.getElementById('charges-report-preview');
const facturesPreview = document.getElementById('factures-report-preview');

// Preview modal elements
const previewModal = document.getElementById('modal-report-preview');
const previewIframe = document.getElementById('preview-iframe');
const previewTitle = document.getElementById('preview-title');
const previewLoading = document.getElementById('preview-loading');
const previewPrintBtn = document.getElementById('preview-print-btn');
const previewDownloadBtn = document.getElementById('preview-download-btn');

// Current preview state
let currentPreviewContext = null;

// Selects de véhicules dans les rapports
const chargesVehiculeSelect = document.getElementById('charges-report-vehicule');
const facturesVehiculeSelect = document.getElementById('factures-report-vehicule');

// Populate vehicule selects with data from state
function populateReportVehiculeSelects() {
	const vehicules = state.vehicules || [];
	const defaultOption = '<option value="">Tous les véhicules</option>';
	const options = vehicules.map(v => {
		const label = v.numero || v.code || `Véhicule ${v.id}`;
		return `<option value="${v.id}">${label}</option>`;
	}).join('');
	
	if (chargesVehiculeSelect) {
		chargesVehiculeSelect.innerHTML = defaultOption + options;
	}
	if (facturesVehiculeSelect) {
		facturesVehiculeSelect.innerHTML = defaultOption + options;
	}
}

// All report modals
const reportModals = [
	document.getElementById('modal-vehicules-report'),
	document.getElementById('modal-chauffeurs-report'),
	document.getElementById('modal-charges-report'),
	document.getElementById('modal-factures-report'),
	document.getElementById('modal-report-preview')
];

function toggleModal(modalId, open) {
	const modal = document.getElementById(modalId);
	if (!modal) return;
	modal.classList[open ? 'remove' : 'add']('hidden');
}

function moveModalsToBody() {
	// Move modals to body to prevent clipping issues (same as chauffeurs/vehicules)
	reportModals.forEach(modal => {
		if (modal && modal.parentElement !== document.body) {
			document.body.appendChild(modal);
		}
	});
}

function setupModalTriggers() {
	modalTriggers.forEach(btn => {
		const target = btn.dataset.reportModal;
		btn.addEventListener('click', () => toggleModal(target, true));
	});

	// Close buttons with data-close attribute
	closeButtons.forEach(btn => {
		btn.addEventListener('click', () => {
			const target = btn.dataset.close;
			if (target) toggleModal(target, false);
		});
	});

	// Close on backdrop click
	reportModals.forEach(modal => {
		if (!modal) return;
		const backdrop = modal.querySelector('.modal-backdrop');
		if (backdrop) {
			backdrop.addEventListener('click', () => {
				toggleModal(modal.id, false);
			});
		}
	});
}

function setSubmitting(btn, isSubmitting) {
	if (!btn) return;
	btn.disabled = isSubmitting;
	btn.textContent = isSubmitting ? 'Génération...' : 'Exporter en PDF';
}

function buildParams(form) {
	const params = {};
	const fd = new FormData(form);
	fd.forEach((value, key) => {
		const trimmed = (value || '').toString().trim();
		if (trimmed) params[key] = trimmed;
	});
	return params;
}

async function downloadPdf(endpoint, form, submitBtn, filenamePrefix) {
	ensureAuth();
	setSubmitting(submitBtn, true);
	const params = buildParams(form);
	try {
		const res = await axios.get(endpoint, { params, responseType: 'blob' });
		const blob = new Blob([res.data], { type: 'application/pdf' });
		const url = window.URL.createObjectURL(blob);
		const link = document.createElement('a');
		link.href = url;
		link.download = `${filenamePrefix}-${new Date().toISOString().slice(0, 10)}.pdf`;
		document.body.appendChild(link);
		link.click();
		link.remove();
		window.URL.revokeObjectURL(url);
		showToast('Export PDF généré.');
	} catch (err) {
		console.error(`Export PDF ${filenamePrefix}`, err);
		const msg = extractErrorMessage(err);
		showToast(msg || 'Export impossible.', 'error');
	} finally {
		setSubmitting(submitBtn, false);
	}
}

function setPreviewLoading(isLoading) {
	if (previewLoading) {
		previewLoading.classList[isLoading ? 'remove' : 'add']('hidden');
	}
	if (previewIframe) {
		previewIframe.style.opacity = isLoading ? '0' : '1';
	}
}

async function showPreview(previewEndpoint, exportEndpoint, form, title, filenamePrefix, previewBtn) {
	ensureAuth();
	
	// Save context for print/download from preview modal
	currentPreviewContext = {
		exportEndpoint,
		form,
		filenamePrefix
	};
	
	// Update title
	if (previewTitle) {
		previewTitle.textContent = title;
	}
	
	// Show modal and loading state
	toggleModal('modal-report-preview', true);
	setPreviewLoading(true);
	
	// Reset iframe
	if (previewIframe) {
		previewIframe.srcdoc = '';
	}
	
	// Disable preview button
	if (previewBtn) {
		previewBtn.disabled = true;
		previewBtn.textContent = 'Chargement...';
	}
	
	const params = buildParams(form);
	try {
		const res = await axios.get(previewEndpoint, { params, responseType: 'text' });
		if (previewIframe) {
			previewIframe.srcdoc = res.data;
			previewIframe.onload = () => {
				setPreviewLoading(false);
			};
		}
	} catch (err) {
		console.error('Preview error:', err);
		const msg = extractErrorMessage(err);
		showToast(msg || 'Impossible de charger l\'aperçu.', 'error');
		toggleModal('modal-report-preview', false);
	} finally {
		if (previewBtn) {
			previewBtn.disabled = false;
			previewBtn.innerHTML = `<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg> Aperçu`;
		}
	}
}

function printPreview() {
	if (previewIframe && previewIframe.contentWindow) {
		previewIframe.contentWindow.print();
	}
}

async function downloadFromPreview() {
	if (!currentPreviewContext) return;
	
	const { exportEndpoint, form, filenamePrefix } = currentPreviewContext;
	
	ensureAuth();
	if (previewDownloadBtn) {
		previewDownloadBtn.disabled = true;
		previewDownloadBtn.textContent = 'Génération...';
	}
	
	const params = buildParams(form);
	try {
		const res = await axios.get(exportEndpoint, { params, responseType: 'blob' });
		const blob = new Blob([res.data], { type: 'application/pdf' });
		const url = window.URL.createObjectURL(blob);
		const link = document.createElement('a');
		link.href = url;
		link.download = `${filenamePrefix}-${new Date().toISOString().slice(0, 10)}.pdf`;
		document.body.appendChild(link);
		link.click();
		link.remove();
		window.URL.revokeObjectURL(url);
		showToast('Export PDF généré.');
	} catch (err) {
		console.error('Download from preview error:', err);
		const msg = extractErrorMessage(err);
		showToast(msg || 'Téléchargement impossible.', 'error');
	} finally {
		if (previewDownloadBtn) {
			previewDownloadBtn.disabled = false;
			previewDownloadBtn.innerHTML = `<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7,10 12,15 17,10"/><line x1="12" y1="15" x2="12" y2="3"/></svg> Télécharger PDF`;
		}
	}
}

export function initializeReportsEvents() {
	moveModalsToBody();
	setupModalTriggers();
	
	// Populate vehicule selects when reports module initializes
	populateReportVehiculeSelects();
	
	// Also update when vehicules data changes
	document.addEventListener('data:vehicules:updated', populateReportVehiculeSelects);

	// Export form submissions
	if (vehiculesForm) {
		vehiculesForm.addEventListener('submit', (e) => {
			e.preventDefault();
			downloadPdf('/api/reports/vehicules/export', vehiculesForm, vehiculesSubmit, 'vehicules');
		});
	}

	if (chauffeursForm) {
		chauffeursForm.addEventListener('submit', (e) => {
			e.preventDefault();
			downloadPdf('/api/reports/chauffeurs/export', chauffeursForm, chauffeursSubmit, 'chauffeurs');
		});
	}

	if (chargesForm) {
		chargesForm.addEventListener('submit', (e) => {
			e.preventDefault();
			downloadPdf('/api/reports/charges/export', chargesForm, chargesSubmit, 'charges');
		});
	}

	if (facturesForm) {
		facturesForm.addEventListener('submit', (e) => {
			e.preventDefault();
			downloadPdf('/api/reports/factures/export', facturesForm, facturesSubmit, 'factures');
		});
	}

	// Preview button handlers
	if (vehiculesPreview && vehiculesForm) {
		vehiculesPreview.addEventListener('click', () => {
			showPreview(
				'/api/reports/vehicules/preview',
				'/api/reports/vehicules/export',
				vehiculesForm,
				'Aperçu · Liste des véhicules',
				'vehicules',
				vehiculesPreview
			);
		});
	}

	if (chauffeursPreview && chauffeursForm) {
		chauffeursPreview.addEventListener('click', () => {
			showPreview(
				'/api/reports/chauffeurs/preview',
				'/api/reports/chauffeurs/export',
				chauffeursForm,
				'Aperçu · Liste des chauffeurs',
				'chauffeurs',
				chauffeursPreview
			);
		});
	}

	if (chargesPreview && chargesForm) {
		chargesPreview.addEventListener('click', () => {
			showPreview(
				'/api/reports/charges/preview',
				'/api/reports/charges/export',
				chargesForm,
				'Aperçu · Liste des charges',
				'charges',
				chargesPreview
			);
		});
	}

	if (facturesPreview && facturesForm) {
		facturesPreview.addEventListener('click', () => {
			showPreview(
				'/api/reports/factures/preview',
				'/api/reports/factures/export',
				facturesForm,
				'Aperçu · Liste des factures',
				'factures',
				facturesPreview
			);
		});
	}

	// Preview modal action buttons
	if (previewPrintBtn) {
		previewPrintBtn.addEventListener('click', printPreview);
	}

	if (previewDownloadBtn) {
		previewDownloadBtn.addEventListener('click', downloadFromPreview);
	}
}
