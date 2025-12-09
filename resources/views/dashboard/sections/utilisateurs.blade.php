<section class="panel section" id="utilisateurs">
    <div class="section-subheader">
        <div>
            <h2>Utilisateurs</h2>
            <p>Gestion des rôles et activation.</p>
        </div>
        <div class="section-actions" style="flex-wrap: nowrap; gap: 10px; align-items: center;">
            <input id="user-search" type="search" placeholder="Rechercher (nom, email, rôle)" style="max-width: 260px;">
            <button class="btn primary" id="open-user-modal" type="button">Nouvel utilisateur</button>
        </div>
    </div>

    <div class="table-wrapper">
        <table class="table-clickable">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Clé</th>
                    <th>Rôle</th>
                    <th>Statut</th>
                    <th style="width: 160px;">Actions</th>
                </tr>
            </thead>
            <tbody id="user-rows"></tbody>
        </table>
    </div>
</section>

<div class="modal hidden" id="user-modal">
    <div class="modal-backdrop" data-close="user-modal"></div>
    <div class="modal-dialog">
        <div class="section-subheader" style="margin-bottom: 10px;">
            <h3 id="user-form-title" style="margin: 0;">Ajouter un utilisateur</h3>
            <button class="btn secondary" id="close-user-modal" type="button">Fermer</button>
        </div>
        <form id="user-form">
            <div class="grid">
                <div>
                    <label>Nom</label>
                    <input name="nom" required>
                </div>
                <div>
                    <label>Clé</label>
                    <input name="cle" required>
                </div>
                <div>
                    <label>Email</label>
                    <input name="email" type="email" placeholder="optionnel">
                </div>
                <div>
                    <label>Mot de passe</label>
                    <input name="password" type="password" placeholder="Laisser vide pour conserver">
                </div>
                <div>
                    <label>Rôle</label>
                    <select name="role" required>
                        <option value="administratif">Administratif</option>
                        <option value="responsable">Responsable</option>
                        <option value="agent">Agent</option>
                    </select>
                </div>
                <div>
                    <label>Actif</label>
                    <select name="actif">
                        <option value="1">Oui</option>
                        <option value="0">Non</option>
                    </select>
                </div>
            </div>
            <div style="display: flex; gap: 8px; justify-content: flex-end;">
                <button class="btn secondary" type="button" id="cancel-user-form">Annuler</button>
                <button class="btn primary" type="submit" id="user-form-submit">Enregistrer</button>
            </div>
        </form>
    </div>
</div>
