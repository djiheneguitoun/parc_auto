// ============================================================================
// Users Management
// ============================================================================
// Handles user CRUD operations, modal interactions, and rendering

import { state, showToast, showConfirm, extractErrorMessage } from './state.js';
import { ensureAuth } from './auth.js';
import { formatUserStatus } from './utils.js';

// DOM Elements
export const userTableBody = document.getElementById('user-rows');
export const userSearchInput = document.getElementById('user-search');
export const userModal = document.getElementById('user-modal');
export const openUserModalBtn = document.getElementById('open-user-modal');
export const closeUserModalBtn = document.getElementById('close-user-modal');
export const userForm = document.getElementById('user-form');
export const userFormTitle = document.getElementById('user-form-title');
export const userFormSubmit = document.getElementById('user-form-submit');
export const cancelUserFormBtn = document.getElementById('cancel-user-form');

export function setUserFormMode(mode, user = null) {
    if (mode === 'edit' && user) {
        state.userEditingId = user.id;
        userFormTitle.textContent = 'Modifier un utilisateur';
        userFormSubmit.textContent = 'Mettre à jour';
        userForm.nom.value = user.nom || '';
        userForm.cle.value = user.cle || '';
        userForm.email.value = user.email || '';
        userForm.password.value = '';
        userForm.role.value = user.role || 'agent';
        userForm.actif.value = Number(user.actif) === 1 ? '1' : '0';
    } else {
        state.userEditingId = null;
        userForm.reset();
        userFormTitle.textContent = 'Ajouter un utilisateur';
        userFormSubmit.textContent = 'Enregistrer';
        if (userForm.actif) userForm.actif.value = '1';
    }
}

export function openUserModal(mode, user = null) {
    setUserFormMode(mode, user);
    userModal.classList.remove('hidden');
}

export function closeUserModal() {
    userModal.classList.add('hidden');
    setUserFormMode('create');
}

export function filteredUsers() {
    const search = state.userSearch.trim().toLowerCase();
    if (!search) return state.users;
    return state.users.filter(u => {
        return [u.nom, u.email, u.role, u.cle]
            .filter(Boolean)
            .some(value => String(value).toLowerCase().includes(search));
    });
}

export function renderUserRows() {
    if (!userTableBody) return;
    const rows = filteredUsers().map(u => `
        <tr data-id="${u.id}">
            <td>${u.nom || ''}</td>
            <td>${u.email || '-'}</td>
            <td>${u.cle || '-'}</td>
            <td>${u.role || '-'}</td>
            <td><span class="badge">${formatUserStatus(u.actif)}</span></td>
            <td class="row-actions">
                <button class="btn secondary xs" data-action="edit" type="button">Modifier</button>
                <button class="btn danger xs" data-action="delete" type="button">Supprimer</button>
            </td>
        </tr>
    `).join('');
    userTableBody.innerHTML = rows || '<tr><td colspan="6" class="muted">Aucun utilisateur</td></tr>';
}

async function fetchUser(id) {
    ensureAuth();
    const res = await axios.get(`/api/utilisateurs/${id}`);
    return res.data;
}

export async function handleDeleteUser(id) {
    ensureAuth();
    const user = state.users.find(u => u.id === Number(id));
    const confirmed = await showConfirm({
        title: 'Supprimer l\'utilisateur ?',
        message: `Êtes-vous sûr de vouloir supprimer l'utilisateur <strong>${user?.nom || user?.email || ''}</strong> ? Cette action est irréversible.`,
        confirmText: 'Supprimer',
        cancelText: 'Annuler',
        type: 'danger'
    });
    if (!confirmed) return;
    try {
        await axios.delete(`/api/utilisateurs/${id}`);
        await loadUsers();
        showToast('Utilisateur supprimé avec succès.', 'success', { title: 'Suppression effectuée' });
    } catch (err) {
        showToast(extractErrorMessage(err), 'error', { title: 'Échec de la suppression' });
    }
}

export async function loadUsers() {
    ensureAuth();
    const res = await axios.get('/api/utilisateurs');
    const list = res.data.data || res.data || [];
    state.users = list;
    renderUserRows();
    // Notify other modules (e.g., dashboard) that users data changed
    document.dispatchEvent(new CustomEvent('data:users:updated'));
}

// ============================================================================
// Event Listeners
// ============================================================================

export function initializeUserEvents() {
    userForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        ensureAuth();
        const payload = Object.fromEntries(new FormData(e.target).entries());
        if (!payload.password) delete payload.password;
        const isEdit = Boolean(state.userEditingId);
        try {
            if (isEdit) {
                await axios.put(`/api/utilisateurs/${state.userEditingId}`, payload);
            } else {
                await axios.post('/api/utilisateurs', payload);
            }
            closeUserModal();
            await loadUsers();
            showToast(isEdit ? 'Utilisateur mis à jour avec succès.' : 'Utilisateur créé avec succès.', 'success', { title: isEdit ? 'Modification effectuée' : 'Création effectuée' });
        } catch (err) {
            showToast(extractErrorMessage(err), 'error', { title: 'Échec de l\'enregistrement' });
        }
    });

    userTableBody.addEventListener('click', async (e) => {
        const row = e.target.closest('tr[data-id]');
        if (!row) return;
        const id = row.dataset.id;
        const action = e.target.dataset.action;
        if (action === 'edit') {
            e.stopPropagation();
            const user = await fetchUser(id);
            setUserFormMode('edit', user);
            openUserModal('edit', user);
            return;
        }
        if (action === 'delete') {
            e.stopPropagation();
            await handleDeleteUser(id);
        }
    });

    userSearchInput.addEventListener('input', (e) => {
        state.userSearch = e.target.value;
        renderUserRows();
    });

    openUserModalBtn.addEventListener('click', () => openUserModal('create'));
    closeUserModalBtn.addEventListener('click', closeUserModal);
    cancelUserFormBtn.addEventListener('click', closeUserModal);
    userModal.addEventListener('click', (e) => {
        if (e.target.dataset.close === 'user-modal') {
            closeUserModal();
        }
    });
}
