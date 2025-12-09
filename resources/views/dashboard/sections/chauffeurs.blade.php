<section class="panel section" id="chauffeurs">
    <div class="section-header">
        <div>
            <h2>Chauffeurs</h2>
            <p>Ajoutez, modifiez ou supprimez les chauffeurs du parc.</p>
        </div>
        <div class="section-actions">
            <button class="btn secondary" id="open-chauffeur-modal" type="button">Nouveau chauffeur</button>
        </div>
    </div>
    <div class="card table-card">
        <div class="section-subheader">
            <div>
                <h3>Liste des chauffeurs</h3>
                <div class="muted-small">Cliquez sur un chauffeur pour voir tous ses détails.</div>
            </div>
        </div>
        <div class="table-wrapper">
            <table class="table-clickable">
                <thead>
                    <tr><th>Matricule</th><th>Nom</th><th>Contact</th><th>Statut</th><th>Mention</th><th>Actions</th></tr>
                </thead>
                <tbody id="chauffeur-rows"></tbody>
            </table>
        </div>
    </div>

    <div class="card detail-card" id="chauffeur-detail-card">
        <div id="chauffeur-detail-empty" class="muted-small">Sélectionnez un chauffeur pour afficher tous les détails.</div>
        <div id="chauffeur-detail" style="display:none;">
            <div class="section-subheader">
                <div class="detail-heading">
                    <h3 id="chauffeur-detail-name"></h3>
                    <div class="pill" id="chauffeur-detail-statut"></div>
                </div>
            </div>
            <div class="grid">
                <div class="stack">
                    <div class="muted-small">Matricule</div>
                    <div id="detail-matricule"></div>
                </div>
                <div class="stack">
                    <div class="muted-small">Téléphone</div>
                    <div id="detail-telephone"></div>
                </div>
                <div class="stack">
                    <div class="muted-small">Adresse</div>
                    <div id="detail-adresse"></div>
                </div>
                <div class="stack">
                    <div class="muted-small">Date de naissance</div>
                    <div id="detail-date-naissance"></div>
                </div>
                <div class="stack">
                    <div class="muted-small">Date de recrutement</div>
                    <div id="detail-date-recrutement"></div>
                </div>
                <div class="stack">
                    <div class="muted-small">Mention</div>
                    <div id="detail-mention"></div>
                </div>
            </div>

            <div class="permit-card">
                <div class="section-subheader">
                    <div>
                        <h4 class="permit-title">Info permis</h4>
                        <div class="muted-small">Détails du permis de conduire</div>
                    </div>
                </div>
                <div class="permit-info">
                    <div><span class="muted-small">Numéro</span><span id="detail-numero-permis"></span></div>
                    <div><span class="muted-small">Date</span><span id="detail-date-permis"></span></div>
                    <div><span class="muted-small">Lieu</span><span id="detail-lieu-permis"></span></div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal hidden" id="chauffeur-modal">
        <div class="modal-backdrop" data-close="chauffeur-modal"></div>
        <div class="modal-dialog">
            <div class="section-subheader">
                <div>
                    <h3 id="chauffeur-form-title">Ajouter un chauffeur</h3>
                    <div class="muted-small">Renseignez toutes les informations du chauffeur.</div>
                </div>
                <div class="section-actions">
                    <button class="btn secondary xs" id="close-chauffeur-modal" type="button">Fermer</button>
                </div>
            </div>
            <form id="chauffeur-form">
                <div class="grid">
                    <div><label>Matricule</label><input name="matricule" required></div>
                    <div><label>Nom</label><input name="nom" required></div>
                    <div><label>Prénom</label><input name="prenom" required></div>
                    <div><label>Téléphone</label><input name="telephone" placeholder="Ex : +213..."></div>
                    <div><label>Adresse</label><input name="adresse" placeholder="Adresse complète"></div>
                    <div><label>Date de naissance</label><input type="date" name="date_naissance"></div>
                    <div><label>Date de recrutement</label><input type="date" name="date_recrutement"></div>
                    <div><label>Numéro de permis</label><input name="numero_permis"></div>
                    <div><label>Date du permis</label><input type="date" name="date_permis"></div>
                    <div><label>Lieu du permis</label><input name="lieu_permis"></div>
                    <div><label>Statut</label>
                        <select name="statut" required>
                            <option value="contractuel">Contractuel</option>
                            <option value="permanent">Permanent</option>
                        </select>
                    </div>
                    <div><label>Mention</label>
                        <select name="mention" required>
                            <option value="tres_bien">Très bien</option>
                            <option value="bien">Bien</option>
                            <option value="mauvais">Mauvais</option>
                            <option value="blame">Blâme</option>
                        </select>
                    </div>
                </div>
                <div class="section-actions">
                    <button class="btn primary" id="chauffeur-form-submit" type="submit">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</section>
