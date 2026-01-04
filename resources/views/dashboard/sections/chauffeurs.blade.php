<section class="panel section" id="chauffeurs">
    <div class="section-header">
        <div>
            <h2>
                <span class="icon-box">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                </span>
                Chauffeurs
            </h2>
            <p>Gérez les chauffeurs du parc automobile.</p>
        </div>
        <div class="section-actions">
            <button class="btn primary" id="open-chauffeur-modal" type="button">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" x2="19" y1="8" y2="14"/><line x1="22" x2="16" y1="11" y2="11"/></svg>
                Nouveau
            </button>
        </div>
    </div>
    
    <div class="card table-card">
        <div class="section-subheader">
            <h3>Liste des chauffeurs</h3>
            <span class="stat-badge" id="chauffeurs-count">
                <span class="count">0</span> chauffeurs
            </span>
        </div>
        <div class="table-wrapper">
            <table>
                <colgroup>
                    <col style="width: 14%">
                    <col style="width: 15%">
                    <col style="width: 14%">
                    <col style="width: 14%">
                    <col style="width: 14%">
                    <col style="width: 15%">
                    <col style="width: 14%">
                </colgroup>
                <thead>
                    <tr>
                        <th>Matricule</th>
                        <th>Nom</th>
                        <th>Téléphone</th>
                        <th>Statut</th>
                        <th>Mention</th>
                        <th>Comportement</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="chauffeur-rows"></tbody>
            </table>
        </div>
    </div>

    <div class="modal hidden" id="chauffeur-detail-modal">
        <div class="modal-backdrop" data-close="chauffeur-detail-modal"></div>
        <div class="modal-dialog detail-dialog">
            <div class="modal-header">
                <h3>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    Détails du chauffeur
                </h3>
                <button class="close-btn" id="close-chauffeur-detail-modal" type="button">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" x2="6" y1="6" y2="18"/><line x1="6" x2="18" y1="6" y2="18"/></svg>
                </button>
            </div>
            <div class="modal-body">
                <div class="detail-header">
                    <div class="detail-avatar">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    </div>
                    <div class="detail-info">
                        <h3 id="chauffeur-detail-name"></h3>
                        <div class="pill" id="chauffeur-detail-statut"></div>
                    </div>
                </div>
                <div class="detail-grid">
                    <div class="detail-item"><label>Matricule</label><span id="detail-matricule">-</span></div>
                    <div class="detail-item"><label>Téléphone</label><span id="detail-telephone">-</span></div>
                    <div class="detail-item"><label>Adresse</label><span id="detail-adresse">-</span></div>
                    <div class="detail-item"><label>Date naissance</label><span id="detail-date-naissance">-</span></div>
                    <div class="detail-item"><label>Date recrutement</label><span id="detail-date-recrutement">-</span></div>
                    <div class="detail-item"><label>Mention</label><span id="detail-mention">-</span></div>
                    <div class="detail-item"><label>Comportement</label><span id="detail-comportement">-</span></div>
                </div>
                <div class="permit-section">
                    <h4>
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect width="18" height="18" x="3" y="3" rx="2"/><path d="M3 9h18M9 21V9"/></svg>
                        Permis de conduire
                    </h4>
                    <div class="permit-grid">
                        <div class="detail-item"><label>Numéro</label><span id="detail-numero-permis">-</span></div>
                        <div class="detail-item"><label>Date</label><span id="detail-date-permis">-</span></div>
                        <div class="detail-item"><label>Lieu</label><span id="detail-lieu-permis">-</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal hidden" id="chauffeur-modal">
        <div class="modal-backdrop" data-close="chauffeur-modal"></div>
        <div class="modal-dialog">
            <div class="modal-header">
                <h3 id="chauffeur-form-title">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" x2="19" y1="8" y2="14"/><line x1="22" x2="16" y1="11" y2="11"/></svg>
                    Nouveau chauffeur
                </h3>
                <button class="close-btn" id="close-chauffeur-modal" type="button">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" x2="6" y1="6" y2="18"/><line x1="6" x2="18" y1="6" y2="18"/></svg>
                </button>
            </div>
            <form id="chauffeur-form">
                <div class="form-grid">
                    <div class="form-group">
                        <label>Matricule *</label>
                        <input name="matricule" required placeholder="MAT-001">
                    </div>
                    <div class="form-group">
                        <label>Nom *</label>
                        <input name="nom" required placeholder="Nom">
                    </div>
                    <div class="form-group">
                        <label>Prénom *</label>
                        <input name="prenom" required placeholder="Prénom">
                    </div>
                    <div class="form-group">
                        <label>Téléphone</label>
                        <input name="telephone" placeholder="+213...">
                    </div>
                    <div class="form-group">
                        <label>Adresse</label>
                        <input name="adresse" placeholder="Adresse">
                    </div>
                    <div class="form-group">
                        <label>Date naissance</label>
                        <input type="date" name="date_naissance">
                    </div>
                    <div class="form-group">
                        <label>Date recrutement</label>
                        <input type="date" name="date_recrutement">
                    </div>
                    <div class="form-group">
                        <label>N° Permis</label>
                        <input name="numero_permis" placeholder="N° permis">
                    </div>
                    <div class="form-group">
                        <label>Date permis</label>
                        <input type="date" name="date_permis">
                    </div>
                    <div class="form-group">
                        <label>Lieu permis</label>
                        <input name="lieu_permis" placeholder="Lieu">
                    </div>
                    <div class="form-group">
                        <label>Statut *</label>
                        <select name="statut" required>
                            <option value="contractuel">Contractuel</option>
                            <option value="permanent">Permanent</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Mention *</label>
                        <select name="mention" required>
                            <option value="excellent">Excellent</option>
                            <option value="tres_bon">Très bon</option>
                            <option value="bon">Bon</option>
                            <option value="moyen">Moyen</option>
                            <option value="insuffisant">Insuffisant</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Comportement *</label>
                        <select name="comportement" required>
                            <option value="excellent">Excellent</option>
                            <option value="tres_bon">Très bon</option>
                            <option value="satisfaisant">Satisfaisant</option>
                            <option value="a_ameliorer">À améliorer</option>
                            <option value="insuffisant">Insuffisant</option>
                            <option value="non_conforme">Non conforme</option>
                            <option value="a_risque">À risque</option>
                        </select>
                    </div>
                </div>
                <div class="form-actions">
                    <button class="btn secondary" type="button" id="cancel-chauffeur-form">Annuler</button>
                    <button class="btn primary" id="chauffeur-form-submit" type="submit">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</section>
