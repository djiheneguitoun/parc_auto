<section class="panel section" id="rapports">
    <div class="section-header">
        <div>
            <h2>
                <span class="icon-box">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14,2 14,8 20,8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10,9 9,9 8,9"/></svg>
                </span>
                Rapports
            </h2>
            <p>Choisissez un rapport, définissez les filtres et exportez en PDF.</p>
        </div>
    </div>

    <div class="reports-grid">
        <div class="report-card" data-report="vehicules">
            <div class="report-icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.4 10.6 16 8 16 8h-5s-2.4 2.6-4.5 3.1C5.7 11.3 5 12.1 5 13v3c0 .6.4 1 1 1h2"/><circle cx="7.5" cy="17" r="2.5"/><circle cx="16.5" cy="17" r="2.5"/></svg>
            </div>
            <div class="report-content">
                <h3>Liste des véhicules</h3>
                <p>Filtrer par catégorie, option, énergie, boîte, leasing, utilisation, affectation, date acquisition.</p>
            </div>
            <button class="btn primary" data-report-modal="modal-vehicules-report" type="button">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7,10 12,15 17,10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                Exporter
            </button>
        </div>

        <div class="report-card" data-report="chauffeurs">
            <div class="report-icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            </div>
            <div class="report-content">
                <h3>Liste des chauffeurs</h3>
                <p>Filtrer par statut et mention.</p>
            </div>
            <button class="btn primary" data-report-modal="modal-chauffeurs-report" type="button">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7,10 12,15 17,10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                Exporter
            </button>
        </div>

        <div class="report-card" data-report="charges">
            <div class="report-icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
            </div>
            <div class="report-content">
                <h3>Charges véhicules</h3>
                <p>Filtrer par véhicule et type de charge (assurance, vignettes, contrôles, entretiens, réparations, bons d'essence).</p>
            </div>
            <button class="btn primary" data-report-modal="modal-charges-report" type="button">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7,10 12,15 17,10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                Exporter
            </button>
        </div>

        <div class="report-card" data-report="factures">
            <div class="report-icon">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14,2 14,8 20,8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
            </div>
            <div class="report-content">
                <h3>Factures véhicule</h3>
                <p>Filtrer par véhicule et période (date facture).</p>
            </div>
            <button class="btn primary" data-report-modal="modal-factures-report" type="button">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7,10 12,15 17,10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                Exporter
            </button>
        </div>
    </div>

    <!-- Modal: Véhicules Report -->
    <div class="modal hidden" id="modal-vehicules-report">
        <div class="modal-backdrop" data-close="modal-vehicules-report"></div>
        <div class="modal-dialog">
            <div class="modal-header">
                <h3>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M19 17h2c.6 0 1-.4 1-1v-3c0-.9-.7-1.7-1.5-1.9C18.4 10.6 16 8 16 8h-5s-2.4 2.6-4.5 3.1C5.7 11.3 5 12.1 5 13v3c0 .6.4 1 1 1h2"/><circle cx="7.5" cy="17" r="2.5"/><circle cx="16.5" cy="17" r="2.5"/></svg>
                    Rapport · Liste des véhicules
                </h3>
                <button class="close-btn" data-close="modal-vehicules-report" type="button">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" x2="6" y1="6" y2="18"/><line x1="6" x2="18" y1="6" y2="18"/></svg>
                </button>
            </div>
            <form id="vehicules-report-form" autocomplete="off">
                <div class="form-grid">
                    <div class="form-group">
                        <label>Catégorie</label>
                        <select name="categorie">
                            <option value="">Toutes</option>
                            <option value="leger">Léger</option>
                            <option value="lourd">Lourd</option>
                            <option value="transport">Transport</option>
                            <option value="tracteur">Tracteur</option>
                            <option value="engins">Engins</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Option</label>
                        <select name="option_vehicule">
                            <option value="">Toutes</option>
                            <option value="base">La base</option>
                            <option value="base_clim">Base clim</option>
                            <option value="toutes_options">Toutes options</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Énergie</label>
                        <select name="energie">
                            <option value="">Tous</option>
                            <option value="essence">Essence</option>
                            <option value="diesel">Diesel</option>
                            <option value="gpl">GPL</option>
                            <option value="electrique">Électrique</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Boîte</label>
                        <select name="boite">
                            <option value="">Toutes</option>
                            <option value="semiauto">Semi-auto.</option>
                            <option value="auto">Automatique</option>
                            <option value="manuel">Manuel</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Leasing</label>
                        <select name="leasing">
                            <option value="">Tous</option>
                            <option value="location">Location</option>
                            <option value="acquisition">Acquisition</option>
                            <option value="autre">Autre</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Utilisation</label>
                        <select name="utilisation">
                            <option value="">Toutes</option>
                            <option value="personnel">Personnel</option>
                            <option value="professionnel">Professionnel</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Affectation</label>
                        <input name="affectation" type="text" placeholder="Ex. Siège, Agence...">
                    </div>
                    <div class="form-group">
                        <label>Date acquisition (début)</label>
                        <input name="date_acquisition_start" type="date">
                    </div>
                    <div class="form-group">
                        <label>Date acquisition (fin)</label>
                        <input name="date_acquisition_end" type="date">
                    </div>
                </div>
                <div class="form-hint">Seuls les champs renseignés seront utilisés comme filtres.</div>
                <div class="form-actions">
                    <button class="btn secondary" type="reset">Réinitialiser</button>
                    <button class="btn primary" id="vehicules-report-submit" type="submit">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14,2 14,8 20,8"/></svg>
                        Exporter en PDF
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal: Chauffeurs Report -->
    <div class="modal hidden" id="modal-chauffeurs-report">
        <div class="modal-backdrop" data-close="modal-chauffeurs-report"></div>
        <div class="modal-dialog">
            <div class="modal-header">
                <h3>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    Rapport · Liste des chauffeurs
                </h3>
                <button class="close-btn" data-close="modal-chauffeurs-report" type="button">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" x2="6" y1="6" y2="18"/><line x1="6" x2="18" y1="6" y2="18"/></svg>
                </button>
            </div>
            <form id="chauffeurs-report-form" autocomplete="off">
                <div class="form-grid cols-2">
                    <div class="form-group">
                        <label>Statut</label>
                        <select name="statut">
                            <option value="">Tous</option>
                            <option value="contractuel">Contractuel</option>
                            <option value="permanent">Permanent</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Mention</label>
                        <select name="mention">
                            <option value="">Toutes</option>
                            <option value="excellent">Excellent</option>
                            <option value="tres_bon">Très bon</option>
                            <option value="bon">Bon</option>
                            <option value="moyen">Moyen</option>
                            <option value="insuffisant">Insuffisant</option>
                        </select>
                    </div>
                </div>
                <div class="form-actions">
                    <button class="btn secondary" type="reset">Réinitialiser</button>
                    <button class="btn primary" id="chauffeurs-report-submit" type="submit">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14,2 14,8 20,8"/></svg>
                        Exporter en PDF
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal: Charges Report -->
    <div class="modal hidden" id="modal-charges-report">
        <div class="modal-backdrop" data-close="modal-charges-report"></div>
        <div class="modal-dialog">
            <div class="modal-header">
                <h3>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                    Rapport · Charges véhicules
                </h3>
                <button class="close-btn" data-close="modal-charges-report" type="button">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" x2="6" y1="6" y2="18"/><line x1="6" x2="18" y1="6" y2="18"/></svg>
                </button>
            </div>
            <form id="charges-report-form" autocomplete="off">
                <div class="form-grid cols-2">
                    <div class="form-group">
                        <label>Véhicule (ID ou code)</label>
                        <input name="vehicule" placeholder="ID ou code du véhicule">
                    </div>
                    <div class="form-group">
                        <label>Type de charge</label>
                        <select name="type">
                            <option value="">Tous</option>
                            <option value="assurance">Assurance</option>
                            <option value="vignette">Vignettes</option>
                            <option value="controle">Contrôles</option>
                            <option value="entretien">Entretiens</option>
                            <option value="reparation">Réparations</option>
                            <option value="bon_essence">Bons d'essence</option>
                        </select>
                    </div>
                </div>
                <div class="form-actions">
                    <button class="btn secondary" type="reset">Réinitialiser</button>
                    <button class="btn primary" id="charges-report-submit" type="submit">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14,2 14,8 20,8"/></svg>
                        Exporter en PDF
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal: Factures Report -->
    <div class="modal hidden" id="modal-factures-report">
        <div class="modal-backdrop" data-close="modal-factures-report"></div>
        <div class="modal-dialog">
            <div class="modal-header">
                <h3>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14,2 14,8 20,8"/></svg>
                    Rapport · Factures véhicule
                </h3>
                <button class="close-btn" data-close="modal-factures-report" type="button">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" x2="6" y1="6" y2="18"/><line x1="6" x2="18" y1="6" y2="18"/></svg>
                </button>
            </div>
            <form id="factures-report-form" autocomplete="off">
                <div class="form-grid cols-3">
                    <div class="form-group">
                        <label>Véhicule (ID ou code)</label>
                        <input name="vehicule" placeholder="ID ou code du véhicule">
                    </div>
                    <div class="form-group">
                        <label>Période début</label>
                        <input name="start" type="date">
                    </div>
                    <div class="form-group">
                        <label>Période fin</label>
                        <input name="end" type="date">
                    </div>
                </div>
                <div class="form-actions">
                    <button class="btn secondary" type="reset">Réinitialiser</button>
                    <button class="btn primary" id="factures-report-submit" type="submit">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14,2 14,8 20,8"/></svg>
                        Exporter en PDF
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>
