// ============================================================================
// Authentication & Authorization
// ============================================================================
// Handles login/logout and token management

import { state, showToast } from './state.js';

export function applyToken(token) {
    state.token = token;
    axios.defaults.headers.common['Authorization'] = 'Bearer ' + token;
}

export function ensureAuth() {
    if (!state.token) {
        window.location.href = '/login';
        throw new Error('Non authentifié');
    }
}

export function bootstrapAuth() {
    if (!state.token) {
        window.location.href = '/login';
        return false;
    }
    applyToken(state.token);
    return true;
}

export async function logout() {
    if (state.token) {
        try { await axios.post('/api/auth/logout'); } catch (e) { console.warn('logout', e); }
    }
    state.token = null;
    localStorage.removeItem('token');
    delete axios.defaults.headers.common['Authorization'];
    window.location.href = '/login';
}

// Initialize logout button
document.getElementById('logout-btn').addEventListener('click', logout);
