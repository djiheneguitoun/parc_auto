// ============================================================================
// Reports / Exports
// ============================================================================
// Handles vehicle report export with filters and PDF download

import { ensureAuth } from './auth.js';
import { showToast, extractErrorMessage } from './state.js';

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

function toggleModal(modalId, open) {
	const modal = document.getElementById(modalId);
	if (!modal) return;
	modal.classList[open ? 'remove' : 'add']('hidden');
}

function setupModalTriggers() {
	modalTriggers.forEach(btn => {
		const target = btn.dataset.reportModal;
		btn.addEventListener('click', () => toggleModal(target, true));
	});

	closeButtons.forEach(btn => {
		const target = btn.dataset.close;
		btn.addEventListener('click', () => toggleModal(target, false));
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

export function initializeReportsEvents() {
	setupModalTriggers();

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
}
