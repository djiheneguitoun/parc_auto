<section class="panel section" id="rapports">
	<div class="section-header">
		<div>
			<h2>Rapports</h2>
			<p>Choisissez un rapport, définissez les filtres et exportez en PDF.</p>
		</div>
	</div>

	<div class="reports-grid">
		<div class="card report-card">
			<h3>Liste des véhicules</h3>
			<div class="muted-small">Filtrer par catégorie, option, énergie, boîte, leasing, utilisation, affectation, date acquisition.</div>
			<div class="section-actions">
				<button class="btn secondary" data-report-modal="modal-vehicules-report" type="button">Filtres & export</button>
			</div>
		</div>
		<div class="card report-card">
			<h3>Liste des chauffeurs</h3>
			<div class="muted-small">Filtrer par statut et mention.</div>
			<div class="section-actions">
				<button class="btn secondary" data-report-modal="modal-chauffeurs-report" type="button">Filtres & export</button>
			</div>
		</div>
		<div class="card report-card">
			<h3>Charges véhicules</h3>
			<div class="muted-small">Filtrer par véhicule et type de charge (assurance, vignettes, contrôles, entretiens, réparations, bons d'essence).</div>
			<div class="section-actions">
				<button class="btn secondary" data-report-modal="modal-charges-report" type="button">Filtres & export</button>
			</div>
		</div>
		<div class="card report-card">
			<h3>Factures véhicule</h3>
			<div class="muted-small">Filtrer par véhicule et période (date facture).</div>
			<div class="section-actions">
				<button class="btn secondary" data-report-modal="modal-factures-report" type="button">Filtres & export</button>
			</div>
		</div>
	</div>

	<div class="modal hidden" id="modal-vehicules-report">
		<div class="modal-backdrop" data-close="modal-vehicules-report"></div>
		<div class="modal-dialog">
			<div class="section-subheader">
				<div>
					<h3>Filtres · Liste des véhicules</h3>
					<div class="muted-small">Seuls les champs renseignés seront appliqués.</div>
				</div>
				<div class="section-actions">
					<button class="btn secondary xs" data-close="modal-vehicules-report" type="button">Fermer</button>
				</div>
			</div>
			<form id="vehicules-report-form" class="stack" autocomplete="off">
				<div class="filters-grid">
					<div class="stack">
						<label for="filter-categorie">Catégorie</label>
						<select id="filter-categorie" name="categorie">
							<option value="">Toutes</option>
							<option value="leger">Léger</option>
							<option value="lourd">Lourd</option>
							<option value="transport">Transport</option>
							<option value="tracteur">Tracteur</option>
							<option value="engins">Engins</option>
						</select>
					</div>
					<div class="stack">
						<label for="filter-option">Option</label>
						<select id="filter-option" name="option_vehicule">
							<option value="">Toutes</option>
							<option value="base">La base</option>
							<option value="base_clim">Base clim</option>
							<option value="toutes_options">Toutes option</option>
						</select>
					</div>
					<div class="stack">
						<label for="filter-energie">Énergie</label>
						<select id="filter-energie" name="energie">
							<option value="">Tous</option>
							<option value="essence">Essence</option>
							<option value="diesel">Diesel</option>
							<option value="gpl">GPL</option>
							<option value="electrique">Électrique</option>
						</select>
					</div>
					<div class="stack">
						<label for="filter-boite">Boîte</label>
						<select id="filter-boite" name="boite">
							<option value="">Toutes</option>
							<option value="semiauto">Semi-auto.</option>
							<option value="auto">Automatique</option>
							<option value="manuel">Manuel</option>
						</select>
					</div>
					<div class="stack">
						<label for="filter-leasing">Leasing</label>
						<select id="filter-leasing" name="leasing">
							<option value="">Tous</option>
							<option value="location">Location</option>
							<option value="acquisition">Acquisition</option>
							<option value="autre">Autre</option>
						</select>
					</div>
					<div class="stack">
						<label for="filter-utilisation">Utilisation</label>
						<select id="filter-utilisation" name="utilisation">
							<option value="">Toutes</option>
							<option value="personnel">Personnel</option>
							<option value="professionnel">Professionnel</option>
						</select>
					</div>
					<div class="stack">
						<label for="filter-affectation">Affectation</label>
						<input id="filter-affectation" name="affectation" type="text" placeholder="Ex. Siège, Agence ...">
					</div>
				</div>

				<div class="filters-inline">
					<div class="stack">
						<label for="filter-date-start">Date acquisition (début)</label>
						<input id="filter-date-start" name="date_acquisition_start" type="date">
					</div>
					<div class="stack">
						<label for="filter-date-end">Date acquisition (fin)</label>
						<input id="filter-date-end" name="date_acquisition_end" type="date">
					</div>
					<div class="stack">
						<label>&nbsp;</label>
						<div class="section-actions" style="gap: 8px;">
							<button class="btn secondary" type="reset">Réinitialiser</button>
							<button class="btn primary" id="vehicules-report-submit" type="submit">Exporter en PDF</button>
						</div>
					</div>
				</div>

				<div class="hint">Seuls les champs renseignés seront utilisés comme filtres. La génération ouvre un PDF prêt à télécharger.</div>
			</form>
		</div>
	</div>

	<div class="modal hidden" id="modal-chauffeurs-report">
		<div class="modal-backdrop" data-close="modal-chauffeurs-report"></div>
		<div class="modal-dialog">
			<div class="section-subheader">
				<div>
					<h3>Filtres · Liste des chauffeurs</h3>
					<div class="muted-small">Statut et mention.</div>
				</div>
				<div class="section-actions">
					<button class="btn secondary xs" data-close="modal-chauffeurs-report" type="button">Fermer</button>
				</div>
			</div>
			<form id="chauffeurs-report-form" class="stack" autocomplete="off">
				<div class="filters-grid">
					<div class="stack">
						<label for="filter-chauffeur-statut">Statut</label>
						<select id="filter-chauffeur-statut" name="statut">
							<option value="">Tous</option>
							<option value="contractuel">Contractuel</option>
							<option value="permanent">Permanent</option>
						</select>
					</div>
					<div class="stack">
						<label for="filter-chauffeur-mention">Mention</label>
						<select id="filter-chauffeur-mention" name="mention">
							<option value="">Toutes</option>
							<option value="tres_bien">Très bien</option>
							<option value="bien">Bien</option>
							<option value="mauvais">Mauvais</option>
							<option value="blame">Blâme</option>
						</select>
					</div>
				</div>
				<div class="section-actions" style="gap: 8px;">
					<button class="btn secondary" type="reset">Réinitialiser</button>
					<button class="btn primary" id="chauffeurs-report-submit" type="submit">Exporter en PDF</button>
				</div>
			</form>
		</div>
	</div>

	<div class="modal hidden" id="modal-charges-report">
		<div class="modal-backdrop" data-close="modal-charges-report"></div>
		<div class="modal-dialog">
			<div class="section-subheader">
				<div>
					<h3>Filtres · Charges véhicules</h3>
					<div class="muted-small">Véhicule et type de charge.</div>
				</div>
				<div class="section-actions">
					<button class="btn secondary xs" data-close="modal-charges-report" type="button">Fermer</button>
				</div>
			</div>
			<form id="charges-report-form" class="stack" autocomplete="off">
				<div class="filters-grid">
					<div class="stack">
						<label for="filter-charge-vehicule">Véhicule (ID ou code)</label>
						<input id="filter-charge-vehicule" name="vehicule" placeholder="ID ou code du véhicule">
					</div>
					<div class="stack">
						<label for="filter-charge-type">Type de charge</label>
						<select id="filter-charge-type" name="type">
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
				<div class="section-actions" style="gap: 8px;">
					<button class="btn secondary" type="reset">Réinitialiser</button>
					<button class="btn primary" id="charges-report-submit" type="submit">Exporter en PDF</button>
				</div>
			</form>
		</div>
	</div>

	<div class="modal hidden" id="modal-factures-report">
		<div class="modal-backdrop" data-close="modal-factures-report"></div>
		<div class="modal-dialog">
			<div class="section-subheader">
				<div>
					<h3>Filtres · Factures véhicule</h3>
					<div class="muted-small">Véhicule et période (date facture).</div>
				</div>
				<div class="section-actions">
					<button class="btn secondary xs" data-close="modal-factures-report" type="button">Fermer</button>
				</div>
			</div>
			<form id="factures-report-form" class="stack" autocomplete="off">
				<div class="filters-grid">
					<div class="stack">
						<label for="filter-facture-vehicule">Véhicule (ID ou code)</label>
						<input id="filter-facture-vehicule" name="vehicule" placeholder="ID ou code du véhicule">
					</div>
				</div>
				<div class="filters-inline">
					<div class="stack">
						<label for="filter-facture-start">Période début</label>
						<input id="filter-facture-start" name="start" type="date">
					</div>
					<div class="stack">
						<label for="filter-facture-end">Période fin</label>
						<input id="filter-facture-end" name="end" type="date">
					</div>
					<div class="stack">
						<label>&nbsp;</label>
						<div class="section-actions" style="gap: 8px;">
							<button class="btn secondary" type="reset">Réinitialiser</button>
							<button class="btn primary" id="factures-report-submit" type="submit">Exporter en PDF</button>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</section>
