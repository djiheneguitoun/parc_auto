<section class="panel section" id="utilisateurs">
    <div class="section-header">
        <div>
            <h2>
                <span class="icon-box">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                </span>
                Utilisateurs
            </h2>
            <p>Gestion des utilisateurs, rôles et permissions d'accès.</p>
        </div>
        <div class="section-actions">
            <button class="btn primary" id="open-user-modal" type="button">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" x2="19" y1="8" y2="14"/><line x1="22" x2="16" y1="11" y2="11"/></svg>
                Nouvel utilisateur
            </button>
        </div>
    </div>
    
    <div class="card table-card">
        <div class="section-subheader">
            <h3>Liste des utilisateurs</h3>
            <div class="subheader-actions">
                <div class="search-box">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" x2="16.65" y1="21" y2="16.65"/></svg>
                    <input id="user-search" type="search" placeholder="Rechercher (nom, email, rôle)">
                </div>
                <span class="stat-badge" id="users-count">
                    <span class="count">0</span> utilisateurs
                </span>
            </div>
        </div>
        <div class="table-wrapper">
            <table>
                <colgroup>
                    <col style="width: 18%">
                    <col style="width: 22%">
                    <col style="width: 14%">
                    <col style="width: 14%">
                    <col style="width: 14%">
                    <col style="width: 18%">
                </colgroup>
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Clé</th>
                        <th>Rôle</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="user-rows"></tbody>
            </table>
        </div>
    </div>
</section>

<div class="modal hidden" id="user-modal">
    <div class="modal-backdrop" data-close="user-modal"></div>
    <div class="modal-dialog">
        <div class="modal-header">
            <h3 id="user-form-title">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" x2="19" y1="8" y2="14"/><line x1="22" x2="16" y1="11" y2="11"/></svg>
                Ajouter un utilisateur
            </h3>
            <button class="close-btn" id="close-user-modal" type="button">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" x2="6" y1="6" y2="18"/><line x1="6" x2="18" y1="6" y2="18"/></svg>
            </button>
        </div>
        <form id="user-form">
            <div class="form-grid">
                <div class="form-group">
                    <label>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        Nom *
                    </label>
                    <input name="nom" required placeholder="Nom complet">
                </div>
                <div class="form-group">
                    <label>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4"/></svg>
                        Clé d'accès *
                    </label>
                    <input name="cle" required placeholder="Clé unique">
                </div>
                <div class="form-group">
                    <label>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                        Email
                    </label>
                    <input name="email" type="email" placeholder="email@exemple.com (optionnel)">
                </div>
                <div class="form-group">
                    <label>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        Mot de passe
                    </label>
                    <input name="password" type="password" placeholder="Laisser vide pour conserver">
                </div>
                <div class="form-group">
                    <label>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                        Rôle *
                    </label>
                    <select name="role" required>
                        <option value="">- Sélectionner un rôle -</option>
                        <option value="administratif">Administratif</option>
                        <option value="responsable">Responsable</option>
                        <option value="agent">Agent</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                        Statut
                    </label>
                    <select name="actif">
                        <option value="1">Actif</option>
                        <option value="0">Inactif</option>
                    </select>
                </div>
            </div>
            <div class="form-actions">
                <button class="btn secondary" type="button" id="cancel-user-form">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" x2="6" y1="6" y2="18"/><line x1="6" x2="18" y1="6" y2="18"/></svg>
                    Annuler
                </button>
                <button class="btn primary" type="submit" id="user-form-submit">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                    Enregistrer
                </button>
            </div>
        </form>
    </div>
</div>
