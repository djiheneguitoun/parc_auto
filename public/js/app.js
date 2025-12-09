    const state = {
        token: localStorage.getItem('token') || null,
        chauffeurs: [],
        selectedChauffeurId: null,
        chauffeurEditingId: null,
        vehicules: [],
        selectedVehiculeId: null,
        vehiculeEditingId: null,
        users: [],
        userEditingId: null,
        userSearch: '',
        documents: {
            assurance: [],
            vignette: [],
            controle: [],
            entretien: [],
            reparation: [],
            bon_essence: [],
        },
        documentCurrentType: 'assurance',
        documentEditingId: null,
    };

    const toastContainer = document.createElement('div');
    toastContainer.id = 'toast-container';
    document.body.appendChild(toastContainer);

    function showToast(message, type = 'success') {
        const toast = document.createElement('div');
        toast.className = `toast ${type}`;
        toast.textContent = message;
        toastContainer.appendChild(toast);
        requestAnimationFrame(() => toast.classList.add('visible'));
        setTimeout(() => {
            toast.classList.remove('visible');
            setTimeout(() => toast.remove(), 200);
        }, 2600);
    }

    function extractErrorMessage(err) {
        if (err?.response?.data?.message) return err.response.data.message;
        const errors = err?.response?.data?.errors;
        if (errors && typeof errors === 'object') {
            const first = Object.values(errors).flat()[0];
            if (first) return first;
        }
        return 'Une erreur est survenue.';
    }

    const sections = document.querySelectorAll('.section');
    const navButtons = document.querySelectorAll('.nav-btn');
    navButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            navButtons.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            sections.forEach(s => s.classList.remove('active'));
            document.getElementById(btn.dataset.target).classList.add('active');
        });
    });

    const chauffeurFormTitle = document.getElementById('chauffeur-form-title');
    const chauffeurFormSubmit = document.getElementById('chauffeur-form-submit');
    const chauffeurForm = document.getElementById('chauffeur-form');
    const chauffeurTableBody = document.getElementById('chauffeur-rows');
    const chauffeurDetailEmpty = document.getElementById('chauffeur-detail-empty');
    const chauffeurDetail = document.getElementById('chauffeur-detail');
    const chauffeurDetailName = document.getElementById('chauffeur-detail-name');
    const chauffeurDetailStatut = document.getElementById('chauffeur-detail-statut');
    const detailMatricule = document.getElementById('detail-matricule');
    const detailTelephone = document.getElementById('detail-telephone');
    const detailAdresse = document.getElementById('detail-adresse');
    const detailDateNaissance = document.getElementById('detail-date-naissance');
    const detailDateRecrutement = document.getElementById('detail-date-recrutement');
    const detailNumeroPermis = document.getElementById('detail-numero-permis');
    const detailDatePermis = document.getElementById('detail-date-permis');
    const detailLieuPermis = document.getElementById('detail-lieu-permis');
    const detailMention = document.getElementById('detail-mention');
    const chauffeurModal = document.getElementById('chauffeur-modal');
    const openChauffeurModalBtn = document.getElementById('open-chauffeur-modal');
    const closeChauffeurModalBtn = document.getElementById('close-chauffeur-modal');

    const vehiculeTableBody = document.getElementById('vehicule-rows');
    const vehiculeDetailEmpty = document.getElementById('vehicule-detail-empty');
    const vehiculeDetail = document.getElementById('vehicule-detail');
    const vehiculeDetailTitle = document.getElementById('vehicule-detail-title');
    const vehiculeDetailStatut = document.getElementById('vehicule-detail-statut');
    const vehiculeDetailCategory = document.getElementById('vehicule-detail-category');
    const detailCode = document.getElementById('detail-code');
    const detailNumero = document.getElementById('detail-numero');
    const detailModele = document.getElementById('detail-modele');
    const detailAnnee = document.getElementById('detail-annee');
    const detailCouleur = document.getElementById('detail-couleur');
    const detailChassis = document.getElementById('detail-chassis');
    const detailEnergie = document.getElementById('detail-energie');
    const detailBoite = document.getElementById('detail-boite');
    const detailOption = document.getElementById('detail-option');
    const detailUtilisation = document.getElementById('detail-utilisation');
    const detailLeasing = document.getElementById('detail-leasing');
    const detailAffectation = document.getElementById('detail-affectation');
    const detailValeur = document.getElementById('detail-valeur');
    const detailDateAcquisition = document.getElementById('detail-date-acquisition');
    const detailDateCreation = document.getElementById('detail-date-creation');
    const detailDescription = document.getElementById('detail-description');
    const detailChauffeurNom = document.getElementById('detail-chauffeur-nom');
    const detailChauffeurTelephone = document.getElementById('detail-chauffeur-telephone');
    const detailChauffeurStatut = document.getElementById('detail-chauffeur-statut');
    const vehiculeDocuments = document.getElementById('vehicule-documents');
    const vehiculeImages = document.getElementById('vehicule-images');
    const vehiculeModal = document.getElementById('vehicule-modal');
    const vehiculeForm = document.getElementById('vehicule-form');
    const vehiculeFormTitle = document.getElementById('vehicule-form-title');
    const vehiculeFormSubmit = document.getElementById('vehicule-form-submit');
    const openVehiculeModalBtn = document.getElementById('open-vehicule-modal');
    const closeVehiculeModalBtn = document.getElementById('close-vehicule-modal');
    const vehiculeImagesInput = document.getElementById('vehicule-images-input');
    const imageViewerModal = document.getElementById('image-viewer-modal');
    const imageViewerImg = document.getElementById('image-viewer-img');
    const imageViewerCaption = document.getElementById('image-viewer-caption');
    const closeImageViewerBtn = document.getElementById('close-image-viewer');
    const userTableBody = document.getElementById('user-rows');
    const userSearchInput = document.getElementById('user-search');
    const userModal = document.getElementById('user-modal');
    const openUserModalBtn = document.getElementById('open-user-modal');
    const closeUserModalBtn = document.getElementById('close-user-modal');
    const userForm = document.getElementById('user-form');
    const userFormTitle = document.getElementById('user-form-title');
    const userFormSubmit = document.getElementById('user-form-submit');
    const cancelUserFormBtn = document.getElementById('cancel-user-form');
    const documentTabs = document.querySelectorAll('[data-doc-tab]');
    const documentPanels = document.querySelectorAll('[data-doc-panel]');
    const documentModal = document.getElementById('document-modal');
    const documentForm = document.getElementById('document-form');
    const documentFormFields = document.getElementById('document-form-fields');
    const documentFormTitle = document.getElementById('document-form-title');
    const documentFormDescription = document.getElementById('document-form-description');
    const documentFormSubmit = document.getElementById('document-form-submit');
    const closeDocumentModalBtn = document.getElementById('close-document-modal');
    const documentSection = document.getElementById('documents');
    const documentTypeInput = document.getElementById('document-type-input');
    const documentTableBodies = {
        assurance: document.getElementById('document-rows-assurance'),
        vignette: document.getElementById('document-rows-vignette'),
        controle: document.getElementById('document-rows-controle'),
        entretien: document.getElementById('document-rows-entretien'),
        reparation: document.getElementById('document-rows-reparation'),
        bon_essence: document.getElementById('document-rows-bon_essence'),
    };

    function applyToken(token) {
        state.token = token;
        axios.defaults.headers.common['Authorization'] = 'Bearer ' + token;
    }

    function ensureAuth() {
        if (!state.token) {
            window.location.href = '/login';
            throw new Error('Non authentifié');
        }
    }

    function bootstrapAuth() {
        if (!state.token) {
            window.location.href = '/login';
            return false;
        }
        applyToken(state.token);
        return true;
    }

    async function logout() {
        if (state.token) {
            try { await axios.post('/api/auth/logout'); } catch (e) { console.warn('logout', e); }
        }
        state.token = null;
        localStorage.removeItem('token');
        delete axios.defaults.headers.common['Authorization'];
        window.location.href = '/login';
    }

    function formatDate(date) {
        if (!date) return '-';
        const parsed = new Date(date);
        return isNaN(parsed) ? '-' : parsed.toLocaleDateString('fr-FR');
    }

    function formatMention(value) {
        const map = { tres_bien: 'Très bien', bien: 'Bien', mauvais: 'Mauvais', blame: 'Blâme' };
        return map[value] || value || '-';
    }

    function formatStatut(value) {
        const map = { contractuel: 'Contractuel', permanent: 'Permanent' };
        return map[value] || value || '-';
    }

    function formatVehiculeStatut(value) {
        return Number(value) === 0 ? 'Inactif' : 'Actif';
    }

    function formatCategorie(value) {
        const map = { leger: 'Léger', lourd: 'Lourd', transport: 'Transport' };
        return map[value] || '-';
    }

    function formatOptionVehicule(value) {
        const map = { base: 'Base', base_clim: 'Base clim', toutes_options: 'Toutes options' };
        return map[value] || '-';
    }

    function formatEnergie(value) {
        const map = { essence: 'Essence', diesel: 'Diesel', gpl: 'GPL' };
        return map[value] || '-';
    }

    function formatBoite(value) {
        const map = { semiauto: 'Semi-auto', auto: 'Auto', manuel: 'Manuel' };
        return map[value] || '-';
    }

    function formatLeasing(value) {
        const map = { location: 'Location', acquisition: 'Acquisition', autre: 'Autre' };
        return map[value] || '-';
    }

    function formatUtilisation(value) {
        const map = { personnel: 'Personnel', professionnel: 'Professionnel' };
        return map[value] || '-';
    }

    function formatUserStatus(value) {
        return Number(value) === 1 ? 'Actif' : 'Inactif';
    }

    function formatCurrency(value) {
        if (value === null || value === undefined || value === '') return '-';
        const num = Number(value);
        if (Number.isNaN(num)) return value;
        return new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'DZD', minimumFractionDigits: 0 }).format(num);
    }

    // Normalise les chemins d'images (public/images ou storage public)
    function resolveVehiculeImageSrc(path) {
        if (!path) return '';
        if (/^https?:\/\//i.test(path)) return path;
        if (path.startsWith('/')) return path;
        if (path.startsWith('storage/')) return `/${path}`;
        if (path.startsWith('images/')) return `/${path}`;
        return `/storage/${path}`;
    }

    const documentTypeConfig = {
        assurance: {
            label: 'Assurance',
            description: 'Numéro, partenaire, période et facturation.',
            colspan: 8,
            fields: [
                { name: 'vehicule_id', label: 'Véhicule', type: 'vehicule', required: true },
                { name: 'numero', label: 'Numéro', type: 'text' },
                { name: 'libele', label: 'Libellé', type: 'text' },
                { name: 'partenaire', label: 'Partenaire', type: 'text' },
                { name: 'debut', label: 'Début', type: 'date' },
                { name: 'expiration', label: 'Expiration', type: 'date' },
                { name: 'valeur', label: 'Valeur', type: 'number', min: '0', step: '0.01' },
                { name: 'num_facture', label: 'Numéro facture', type: 'text' },
                { name: 'date_facture', label: 'Date facture', type: 'date' },
            ],
        },
        vignette: {
            label: 'Vignette',
            description: 'Numéro, partenaire, période et facturation.',
            colspan: 8,
            fields: [
                { name: 'vehicule_id', label: 'Véhicule', type: 'vehicule', required: true },
                { name: 'numero', label: 'Numéro', type: 'text' },
                { name: 'libele', label: 'Libellé', type: 'text' },
                { name: 'partenaire', label: 'Partenaire', type: 'text' },
                { name: 'debut', label: 'Début', type: 'date' },
                { name: 'expiration', label: 'Expiration', type: 'date' },
                { name: 'valeur', label: 'Valeur', type: 'number', min: '0', step: '0.01' },
                { name: 'num_facture', label: 'Numéro facture', type: 'text' },
                { name: 'date_facture', label: 'Date facture', type: 'date' },
            ],
        },
        controle: {
            label: 'Contrôle',
            description: 'Numéro, partenaire, période et facturation.',
            colspan: 8,
            fields: [
                { name: 'vehicule_id', label: 'Véhicule', type: 'vehicule', required: true },
                { name: 'numero', label: 'Numéro', type: 'text' },
                { name: 'libele', label: 'Libellé', type: 'text' },
                { name: 'partenaire', label: 'Partenaire', type: 'text' },
                { name: 'debut', label: 'Début', type: 'date' },
                { name: 'expiration', label: 'Expiration', type: 'date' },
                { name: 'valeur', label: 'Valeur', type: 'number', min: '0', step: '0.01' },
                { name: 'num_facture', label: 'Numéro facture', type: 'text' },
                { name: 'date_facture', label: 'Date facture', type: 'date' },
            ],
        },
        entretien: {
            label: 'Entretien',
            description: 'Inclut vidange (complet/partiel) et kilométrage.',
            colspan: 10,
            fields: [
                { name: 'vehicule_id', label: 'Véhicule', type: 'vehicule', required: true },
                { name: 'numero', label: 'Numéro', type: 'text' },
                { name: 'libele', label: 'Libellé', type: 'text' },
                { name: 'partenaire', label: 'Partenaire', type: 'text' },
                { name: 'debut', label: 'Début', type: 'date' },
                { name: 'expiration', label: 'Expiration', type: 'date' },
                { name: 'vidange', label: 'Vidange', type: 'select', options: [
                    { value: 'complet', label: 'Complet' },
                    { value: 'partiel', label: 'Partiel' },
                ] },
                { name: 'kilometrage', label: 'Kilométrage', type: 'number', min: '0', step: '1' },
                { name: 'valeur', label: 'Valeur', type: 'number', min: '0', step: '0.01' },
                { name: 'num_facture', label: 'Numéro facture', type: 'text' },
                { name: 'date_facture', label: 'Date facture', type: 'date' },
            ],
        },
        reparation: {
            label: 'Réparation',
            description: 'Pièce, réparateur, type (carrosserie/mécanique) et facture.',
            colspan: 9,
            fields: [
                { name: 'vehicule_id', label: 'Véhicule', type: 'vehicule', required: true },
                { name: 'numero', label: 'Numéro', type: 'text' },
                { name: 'libele', label: 'Libellé', type: 'text' },
                { name: 'piece', label: 'Pièce', type: 'text' },
                { name: 'reparateur', label: 'Réparateur', type: 'text' },
                { name: 'type_reparation', label: 'Type', type: 'select', options: [
                    { value: 'carosserie', label: 'Carrosserie' },
                    { value: 'mecanique', label: 'Mécanique' },
                ] },
                { name: 'date_reparation', label: 'Date', type: 'date' },
                { name: 'valeur', label: 'Valeur', type: 'number', min: '0', step: '0.01' },
                { name: 'num_facture', label: 'Numéro facture', type: 'text' },
                { name: 'date_facture', label: 'Date facture', type: 'date' },
            ],
        },
        bon_essence: {
            label: "Bon d'essence",
            description: 'Type de carburant, kilométrage et utilisation.',
            colspan: 9,
            fields: [
                { name: 'vehicule_id', label: 'Véhicule', type: 'vehicule', required: true },
                { name: 'numero', label: 'Numéro', type: 'text' },
                { name: 'debut', label: 'Date', type: 'date' },
                { name: 'typecarburant', label: 'Type carburant', type: 'select', options: [
                    { value: 'essence', label: 'Essence' },
                    { value: 'gasoil', label: 'Gasoil' },
                    { value: 'gpl', label: 'GPL' },
                ] },
                { name: 'kilometrage', label: 'Kilométrage', type: 'number', min: '0', step: '1' },
                { name: 'utilisation', label: 'Utilisation', type: 'select', options: [
                    { value: 'trajet', label: 'Trajet' },
                    { value: 'interne', label: 'Interne' },
                ] },
                { name: 'valeur', label: 'Valeur', type: 'number', min: '0', step: '0.01' },
                { name: 'num_facture', label: 'Numéro facture', type: 'text' },
                { name: 'date_facture', label: 'Date facture', type: 'date' },
            ],
        },
    };

    function formatDocVidange(value) {
        const map = { complet: 'Complet', partiel: 'Partiel' };
        return map[value] || '-';
    }

    function formatDocTypeReparation(value) {
        const map = { carosserie: 'Carrosserie', mecanique: 'Mécanique' };
        return map[value] || '-';
    }

    function formatDocCarburant(value) {
        const map = { essence: 'Essence', gasoil: 'Gasoil', gpl: 'GPL' };
        return map[value] || '-';
    }

    function formatDocUtilisation(value) {
        const map = { trajet: 'Trajet', interne: 'Interne' };
        return map[value] || '-';
    }

    function formatDocFacture(doc) {
        const num = doc.num_facture ? `#${doc.num_facture}` : '-';
        const date = formatDate(doc.date_facture);
        return `${num} • ${date}`;
    }

    function vehiculeLabel(doc) {
        const vehicle = doc?.vehicule;
        if (vehicle) {
            const labelParts = [vehicle.code, vehicle.numero, vehicle.marque, vehicle.modele].filter(Boolean);
            if (labelParts.length) return labelParts.join(' · ');
        }
        return doc?.vehicule_id ? `ID ${doc.vehicule_id}` : '-';
    }

    function toInputDate(value) {
        if (!value) return '';
        const date = new Date(value);
        if (Number.isNaN(date.getTime())) {
            return String(value).slice(0, 10);
        }
        return date.toISOString().slice(0, 10);
    }

    function renderVehiculeOptions(selectedId = '') {
        const options = state.vehicules.map(v => {
            const label = [v.code, v.numero, v.marque, v.modele].filter(Boolean).join(' · ') || `Véhicule ${v.id}`;
            const selected = Number(selectedId) === Number(v.id) ? 'selected' : '';
            return `<option value="${v.id}" ${selected}>${label}</option>`;
        }).join('');
        return `<option value="">Choisir un véhicule</option>${options}`;
    }

    function renderDocumentField(field, doc = {}) {
        const valueRaw = doc[field.name];
        const value = field.type === 'date' ? toInputDate(valueRaw) : (valueRaw ?? '');
        const required = field.required ? 'required' : '';
        if (field.type === 'vehicule') {
            return `
                <div><label>${field.label}</label>
                    <select name="${field.name}" ${required}>${renderVehiculeOptions(value)}</select>
                </div>`;
        }
        if (field.type === 'select') {
            const options = (field.options || []).map(opt => {
                const sel = String(opt.value) === String(value) ? 'selected' : '';
                return `<option value="${opt.value}" ${sel}>${opt.label}</option>`;
            }).join('');
            return `
                <div><label>${field.label}</label>
                    <select name="${field.name}" ${required}>
                        <option value=""></option>
                        ${options}
                    </select>
                </div>`;
        }
        const minAttr = field.min ? `min="${field.min}"` : '';
        const stepAttr = field.step ? `step="${field.step}"` : '';
        const typeAttr = field.type || 'text';
        return `
            <div><label>${field.label}</label>
                <input name="${field.name}" type="${typeAttr}" value="${value}" ${required} ${minAttr} ${stepAttr}>
            </div>`;
    }

    function renderDocumentFormFields(type, doc = {}) {
        const config = documentTypeConfig[type];
        if (!config) return;
        documentFormFields.innerHTML = config.fields.map(field => renderDocumentField(field, doc)).join('');
    }

    let imageViewerZoom = 1;

    function openImageViewer(src, caption = '') {
        imageViewerZoom = 1;
        imageViewerImg.style.transform = 'scale(1)';
        imageViewerImg.src = src;
        imageViewerCaption.textContent = caption;
        imageViewerModal.classList.remove('hidden');
    }

    function closeImageViewer() {
        imageViewerModal.classList.add('hidden');
    }

    function adjustImageViewerZoom(delta) {
        imageViewerZoom = Math.min(3, Math.max(1, imageViewerZoom + delta));
        imageViewerImg.style.transform = `scale(${imageViewerZoom})`;
    }

    function setUserFormMode(mode, user = null) {
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

    function openUserModal(mode, user = null) {
        setUserFormMode(mode, user);
        userModal.classList.remove('hidden');
    }

    function closeUserModal() {
        userModal.classList.add('hidden');
        setUserFormMode('create');
    }

    function filteredUsers() {
        const search = state.userSearch.trim().toLowerCase();
        if (!search) return state.users;
        return state.users.filter(u => {
            return [u.nom, u.email, u.role, u.cle]
                .filter(Boolean)
                .some(value => String(value).toLowerCase().includes(search));
        });
    }

    function renderUserRows() {
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

    async function handleDeleteUser(id) {
        ensureAuth();
        const confirmed = window.confirm('Supprimer cet utilisateur ?');
        if (!confirmed) return;
        await axios.delete(`/api/utilisateurs/${id}`);
        await loadUsers();
        showToast('Utilisateur supprimé.');
    }

    function openChauffeurModal(mode, chauffeur = null) {
        setChauffeurFormMode(mode, chauffeur);
        chauffeurModal.classList.remove('hidden');
    }

    function closeChauffeurModal() {
        chauffeurModal.classList.add('hidden');
        setChauffeurFormMode('create');
    }

    function setChauffeurFormMode(mode, chauffeur = null) {
        if (mode === 'edit' && chauffeur) {
            state.chauffeurEditingId = chauffeur.id;
            chauffeurFormTitle.textContent = 'Modifier le chauffeur';
            chauffeurFormSubmit.textContent = 'Mettre à jour';
            chauffeurForm.matricule.value = chauffeur.matricule || '';
            chauffeurForm.nom.value = chauffeur.nom || '';
            chauffeurForm.prenom.value = chauffeur.prenom || '';
            chauffeurForm.telephone.value = chauffeur.telephone || '';
            chauffeurForm.adresse.value = chauffeur.adresse || '';
            chauffeurForm.date_naissance.value = chauffeur.date_naissance || '';
            chauffeurForm.date_recrutement.value = chauffeur.date_recrutement || '';
            chauffeurForm.numero_permis.value = chauffeur.numero_permis || '';
            chauffeurForm.date_permis.value = chauffeur.date_permis || '';
            chauffeurForm.lieu_permis.value = chauffeur.lieu_permis || '';
            chauffeurForm.statut.value = chauffeur.statut || 'contractuel';
            chauffeurForm.mention.value = chauffeur.mention || 'bien';
        } else {
            state.chauffeurEditingId = null;
            chauffeurForm.reset();
            chauffeurFormTitle.textContent = 'Ajouter un chauffeur';
            chauffeurFormSubmit.textContent = 'Enregistrer';
        }
    }

    function renderChauffeurRows() {
        const rows = state.chauffeurs.map(ch => `
            <tr data-id="${ch.id}">
                <td>${ch.matricule}</td>
                <td>${ch.nom} ${ch.prenom}</td>
                <td>${ch.telephone || ''}</td>
                <td><span class="badge">${formatStatut(ch.statut)}</span></td>
                <td>${formatMention(ch.mention)}</td>
                <td class="row-actions">
                    <button class="btn secondary xs" data-action="edit" type="button">Modifier</button>
                    <button class="btn danger xs" data-action="delete" type="button">Supprimer</button>
                </td>
            </tr>
        `).join('');
        chauffeurTableBody.innerHTML = rows;
    }

    function setVehiculeFormMode(mode, vehicule = null) {
        if (vehiculeImagesInput) vehiculeImagesInput.value = '';
        if (mode === 'edit' && vehicule) {
            state.vehiculeEditingId = vehicule.id;
            vehiculeFormTitle.textContent = 'Modifier le véhicule';
            vehiculeFormSubmit.textContent = 'Mettre à jour';
            vehiculeForm.numero.value = vehicule.numero || '';
            vehiculeForm.code.value = vehicule.code || '';
            vehiculeForm.marque.value = vehicule.marque || '';
            vehiculeForm.modele.value = vehicule.modele || '';
            vehiculeForm.annee.value = vehicule.annee || '';
            vehiculeForm.couleur.value = vehicule.couleur || '';
            vehiculeForm.chassis.value = vehicule.chassis || '';
            vehiculeForm.chauffeur_id.value = vehicule.chauffeur_id || '';
            vehiculeForm.date_acquisition.value = vehicule.date_acquisition || '';
            vehiculeForm.valeur.value = vehicule.valeur || '';
            vehiculeForm.statut.value = Number(vehicule.statut) === 0 ? '0' : '1';
            vehiculeForm.date_creation.value = vehicule.date_creation ? vehicule.date_creation.slice(0, 10) : '';
            vehiculeForm.categorie.value = vehicule.categorie || '';
            vehiculeForm.option_vehicule.value = vehicule.option_vehicule || '';
            vehiculeForm.energie.value = vehicule.energie || '';
            vehiculeForm.boite.value = vehicule.boite || '';
            vehiculeForm.leasing.value = vehicule.leasing || '';
            vehiculeForm.utilisation.value = vehicule.utilisation || '';
            vehiculeForm.affectation.value = vehicule.affectation || '';
            vehiculeForm.description.value = vehicule.description || '';
        } else {
            state.vehiculeEditingId = null;
            vehiculeForm.reset();
            if (vehiculeImagesInput) vehiculeImagesInput.value = '';
            vehiculeFormTitle.textContent = 'Ajouter un véhicule';
            vehiculeFormSubmit.textContent = 'Enregistrer';
        }
    }

    function openVehiculeModal(mode, vehicule = null) {
        setVehiculeFormMode(mode, vehicule);
        vehiculeModal.classList.remove('hidden');
    }

    function closeVehiculeModal() {
        vehiculeModal.classList.add('hidden');
        setVehiculeFormMode('create');
    }

    function renderVehiculeRows() {
        const rows = state.vehicules.map(v => `
            <tr data-id="${v.id}">
                <td>${v.code || ''}</td>
                <td>${v.numero || ''}</td>
                <td>${[v.marque, v.modele].filter(Boolean).join(' ')}</td>
                <td>${v.chauffeur ? `${v.chauffeur.nom || ''} ${v.chauffeur.prenom || ''}`.trim() : '-'}</td>
                <td><span class="badge">${formatVehiculeStatut(v.statut)}</span></td>
                <td>${formatCategorie(v.categorie)}</td>
                <td class="row-actions">
                    <button class="btn secondary xs" data-action="edit" type="button">Modifier</button>
                    <button class="btn danger xs" data-action="delete" type="button">Supprimer</button>
                </td>
            </tr>
        `).join('');
        vehiculeTableBody.innerHTML = rows;
    }

    function clearVehiculeDetail() {
        vehiculeDetail.style.display = 'none';
        vehiculeDetailEmpty.style.display = 'block';
        state.selectedVehiculeId = null;
    }

    function renderVehiculeDetail(v) {
        vehiculeDetailTitle.textContent = `${v.marque || ''} ${v.modele || ''}`.trim() || v.code || 'Véhicule';
        vehiculeDetailStatut.textContent = formatVehiculeStatut(v.statut);
        vehiculeDetailCategory.textContent = `${formatCategorie(v.categorie)} • ${formatOptionVehicule(v.option_vehicule)}`;
        detailCode.textContent = v.code || '-';
        detailNumero.textContent = v.numero || '-';
        detailModele.textContent = `${v.marque || '-'} ${v.modele || ''}`.trim();
        detailAnnee.textContent = v.annee || '-';
        detailCouleur.textContent = v.couleur || '-';
        detailChassis.textContent = v.chassis || '-';
        detailEnergie.textContent = formatEnergie(v.energie);
        detailBoite.textContent = formatBoite(v.boite);
        detailOption.textContent = formatOptionVehicule(v.option_vehicule);
        detailUtilisation.textContent = formatUtilisation(v.utilisation);
        detailLeasing.textContent = formatLeasing(v.leasing);
        detailAffectation.textContent = v.affectation || '-';
        detailValeur.textContent = formatCurrency(v.valeur);
        detailDateAcquisition.textContent = formatDate(v.date_acquisition);
        detailDateCreation.textContent = formatDate(v.date_creation);
        detailDescription.textContent = v.description || 'Aucune description.';

        if (v.chauffeur) {
            detailChauffeurNom.textContent = `${v.chauffeur.nom || ''} ${v.chauffeur.prenom || ''}`.trim();
            detailChauffeurTelephone.textContent = v.chauffeur.telephone || '-';
            detailChauffeurStatut.textContent = formatStatut(v.chauffeur.statut);
        } else {
            detailChauffeurNom.textContent = '-';
            detailChauffeurTelephone.textContent = '-';
            detailChauffeurStatut.textContent = '-';
        }

        // Documents section removed from détail véhicule; documents live in their own section.

        if (v.images && v.images.length) {
            vehiculeImages.innerHTML = v.images.map(img => `
                <div class="card vehicule-thumb" data-image-src="${img.image_path}" data-caption="${img.legende || 'Photo'}">
                    <div class="muted-small">${img.legende || 'Photo'}</div>
                    <img src="${resolveVehiculeImageSrc(img.image_path)}" alt="vehicule" />
                </div>
            `).join('');
        } else {
            vehiculeImages.innerHTML = '<div class="muted-small">Aucune image disponible.</div>';
        }

        vehiculeDetailEmpty.style.display = 'none';
        vehiculeDetail.style.display = 'block';
    }

    async function fetchVehicule(id) {
        ensureAuth();
        const res = await axios.get(`/api/vehicules/${id}`);
        return res.data;
    }

    async function showVehiculeDetail(id, focusImages = false) {
        const vehicule = await fetchVehicule(id);
        state.selectedVehiculeId = vehicule.id;
        renderVehiculeDetail(vehicule);
        if (focusImages) {
            setTimeout(() => {
                vehiculeImages.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }, 150);
        }
    }

    async function handleDeleteVehicule(id) {
        ensureAuth();
        const confirmed = window.confirm('Supprimer ce véhicule ?');
        if (!confirmed) return;
        await axios.delete(`/api/vehicules/${id}`);
        if (state.selectedVehiculeId === Number(id)) {
            clearVehiculeDetail();
        }
        await loadVehicules();
    }

    function clearChauffeurDetail() {
        chauffeurDetail.style.display = 'none';
        chauffeurDetailEmpty.style.display = 'block';
        state.selectedChauffeurId = null;
    }

    function renderChauffeurDetail(ch) {
        chauffeurDetailName.textContent = `${ch.nom} ${ch.prenom}`;
        chauffeurDetailStatut.textContent = formatStatut(ch.statut);
        detailMatricule.textContent = ch.matricule || '-';
        detailTelephone.textContent = ch.telephone || '-';
        detailAdresse.textContent = ch.adresse || '-';
        detailDateNaissance.textContent = formatDate(ch.date_naissance);
        detailDateRecrutement.textContent = formatDate(ch.date_recrutement);
        detailNumeroPermis.textContent = ch.numero_permis || '-';
        detailDatePermis.textContent = formatDate(ch.date_permis);
        detailLieuPermis.textContent = ch.lieu_permis || '-';
        detailMention.textContent = formatMention(ch.mention);
        chauffeurDetailEmpty.style.display = 'none';
        chauffeurDetail.style.display = 'block';
    }

    async function fetchChauffeur(id) {
        ensureAuth();
        const res = await axios.get(`/api/chauffeurs/${id}`);
        return res.data;
    }

    async function showChauffeurDetail(id) {
        const chauffeur = await fetchChauffeur(id);
        state.selectedChauffeurId = chauffeur.id;
        renderChauffeurDetail(chauffeur);
    }

    async function handleDeleteChauffeur(id) {
        ensureAuth();
        const confirmed = window.confirm('Supprimer ce chauffeur ?');
        if (!confirmed) return;
        await axios.delete(`/api/chauffeurs/${id}`);
        clearChauffeurDetail();
        await loadChauffeurs();
    }

    async function loadChauffeurs() {
        ensureAuth();
        const res = await axios.get('/api/chauffeurs');
        state.chauffeurs = res.data.data || [];
        renderChauffeurRows();
        if (state.selectedChauffeurId) {
            const stillExists = state.chauffeurs.find(ch => ch.id === state.selectedChauffeurId);
            if (stillExists) {
                showChauffeurDetail(state.selectedChauffeurId);
            } else {
                clearChauffeurDetail();
            }
        }
    }

    async function loadVehicules() {
        ensureAuth();
        const res = await axios.get('/api/vehicules');
        const list = res.data.data || res.data;
        state.vehicules = list;
        renderVehiculeRows();
        if (state.selectedVehiculeId) {
            const exists = state.vehicules.find(v => v.id === state.selectedVehiculeId);
            if (exists) {
                showVehiculeDetail(state.selectedVehiculeId).catch(console.error);
            } else {
                clearVehiculeDetail();
            }
        }
    }

    async function loadUsers() {
        ensureAuth();
        const res = await axios.get('/api/utilisateurs');
        const list = res.data.data || res.data || [];
        state.users = list;
        renderUserRows();
    }

    function renderDocumentRow(type, doc) {
        const veh = vehiculeLabel(doc);
        const facture = formatDocFacture(doc);
        if (['assurance', 'vignette', 'controle'].includes(type)) {
            return `
                <tr data-doc-id="${doc.id}" data-doc-type="${type}">
                    <td>${veh}</td>
                    <td>${doc.numero || '-'}</td>
                    <td>${doc.partenaire || '-'}</td>
                    <td>${formatDate(doc.debut)}</td>
                    <td>${formatDate(doc.expiration)}</td>
                    <td>${formatCurrency(doc.valeur)}</td>
                    <td>${facture}</td>
                    <td class="row-actions">
                        <button class="btn secondary xs" data-doc-action="edit" type="button">Modifier</button>
                        <button class="btn danger xs" data-doc-action="delete" type="button">Supprimer</button>
                    </td>
                </tr>`;
        }
        if (type === 'entretien') {
            return `
                <tr data-doc-id="${doc.id}" data-doc-type="${type}">
                    <td>${veh}</td>
                    <td>${doc.numero || '-'}</td>
                    <td>${doc.partenaire || '-'}</td>
                    <td>${formatDate(doc.debut)}</td>
                    <td>${formatDate(doc.expiration)}</td>
                    <td>${formatDocVidange(doc.vidange)}</td>
                    <td>${doc.kilometrage ?? '-'}</td>
                    <td>${formatCurrency(doc.valeur)}</td>
                    <td>${facture}</td>
                    <td class="row-actions">
                        <button class="btn secondary xs" data-doc-action="edit" type="button">Modifier</button>
                        <button class="btn danger xs" data-doc-action="delete" type="button">Supprimer</button>
                    </td>
                </tr>`;
        }
        if (type === 'reparation') {
            return `
                <tr data-doc-id="${doc.id}" data-doc-type="${type}">
                    <td>${veh}</td>
                    <td>${doc.numero || '-'}</td>
                    <td>${doc.piece || '-'}</td>
                    <td>${doc.reparateur || '-'}</td>
                    <td>${formatDocTypeReparation(doc.type_reparation)}</td>
                    <td>${formatDate(doc.date_reparation)}</td>
                    <td>${formatCurrency(doc.valeur)}</td>
                    <td>${facture}</td>
                    <td class="row-actions">
                        <button class="btn secondary xs" data-doc-action="edit" type="button">Modifier</button>
                        <button class="btn danger xs" data-doc-action="delete" type="button">Supprimer</button>
                    </td>
                </tr>`;
        }
        if (type === 'bon_essence') {
            return `
                <tr data-doc-id="${doc.id}" data-doc-type="${type}">
                    <td>${veh}</td>
                    <td>${doc.numero || '-'}</td>
                    <td>${formatDate(doc.debut)}</td>
                    <td>${formatDocCarburant(doc.typecarburant)}</td>
                    <td>${doc.kilometrage ?? '-'}</td>
                    <td>${formatDocUtilisation(doc.utilisation)}</td>
                    <td>${formatCurrency(doc.valeur)}</td>
                    <td>${facture}</td>
                    <td class="row-actions">
                        <button class="btn secondary xs" data-doc-action="edit" type="button">Modifier</button>
                        <button class="btn danger xs" data-doc-action="delete" type="button">Supprimer</button>
                    </td>
                </tr>`;
        }
        return '';
    }

    function renderDocumentTables() {
        Object.entries(documentTableBodies).forEach(([type, tbody]) => {
            const rows = (state.documents[type] || []).map(doc => renderDocumentRow(type, doc)).join('');
            const colspan = documentTypeConfig[type]?.colspan || 6;
            tbody.innerHTML = rows || `<tr><td colspan="${colspan}" class="muted">Aucun document ${documentTypeConfig[type]?.label?.toLowerCase() || ''}.</td></tr>`;
        });
    }

    function activateDocumentTab(type) {
        state.documentCurrentType = type;
        documentTabs.forEach(tab => tab.classList.toggle('active', tab.dataset.docTab === type));
        documentPanels.forEach(panel => panel.classList.toggle('active', panel.dataset.docPanel === type));
    }

    function openDocumentModal(type, doc = null) {
        const config = documentTypeConfig[type];
        if (!config) return;
        state.documentCurrentType = type;
        state.documentEditingId = doc?.id || null;
        documentTypeInput.value = type;
        documentForm.reset();
        documentFormTitle.textContent = `${doc ? 'Modifier' : 'Ajouter'} ${config.label.toLowerCase()}`;
        documentFormDescription.textContent = config.description || '';
        documentFormSubmit.textContent = doc ? 'Mettre à jour' : 'Enregistrer';
        renderDocumentFormFields(type, doc || {});
        documentModal.classList.remove('hidden');
    }

    function closeDocumentModal() {
        state.documentEditingId = null;
        documentModal.classList.add('hidden');
        documentForm.reset();
    }

    async function loadDocuments() {
        ensureAuth();
        const types = Object.keys(documentTypeConfig);
        const requests = types.map(type => axios.get('/api/vehicule-documents', { params: { type, per_page: 200 } }));
        const responses = await Promise.all(requests);
        responses.forEach((res, idx) => {
            const type = types[idx];
            const list = res?.data?.data || res?.data || [];
            state.documents[type] = list;
        });
        renderDocumentTables();
    }

    async function loadParametres() {
        ensureAuth();
        const res = await axios.get('/api/parametres');
        if (res.data) {
            const form = document.getElementById('param-form');
            form.nom_entreprise.value = res.data.nom_entreprise || '';
            form.lien_archive_facture.value = res.data.lien_archive_facture || '';
        }
    }

    async function loadMetrics() {
        ensureAuth();
        const [ch, v, d, u] = await Promise.all([
            axios.get('/api/chauffeurs'),
            axios.get('/api/vehicules'),
            axios.get('/api/vehicule-documents'),
            axios.get('/api/utilisateurs'),
        ]);
        document.getElementById('metric-chauffeurs').textContent = ch.data.total || ch.data.data.length;
        document.getElementById('metric-vehicules').textContent = v.data.total || v.data.data.length;
        document.getElementById('metric-documents').textContent = d.data.total || d.data.data.length;
        document.getElementById('metric-users').textContent = u.data.total || u.data.data.length;
    }

    document.getElementById('logout-btn').addEventListener('click', logout);

    chauffeurForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        ensureAuth();
        const payload = Object.fromEntries(new FormData(e.target).entries());
        if (state.chauffeurEditingId) {
            await axios.put(`/api/chauffeurs/${state.chauffeurEditingId}`, payload);
        } else {
            await axios.post('/api/chauffeurs', payload);
        }
        closeChauffeurModal();
        await loadChauffeurs();
    });

    chauffeurTableBody.addEventListener('click', async (e) => {
        const row = e.target.closest('tr[data-id]');
        if (!row) return;
        const id = row.dataset.id;
        if (e.target.matches('[data-action="edit"]')) {
            e.stopPropagation();
            const chauffeur = await fetchChauffeur(id);
            setChauffeurFormMode('edit', chauffeur);
            openChauffeurModal('edit', chauffeur);
            return;
        }
        if (e.target.matches('[data-action="delete"]')) {
            e.stopPropagation();
            await handleDeleteChauffeur(id);
            return;
        }
        showChauffeurDetail(id);
    });

    openChauffeurModalBtn.addEventListener('click', () => openChauffeurModal('create'));
    closeChauffeurModalBtn.addEventListener('click', () => closeChauffeurModal());
    chauffeurModal.addEventListener('click', (e) => {
        if (e.target.dataset.close === 'chauffeur-modal') {
            closeChauffeurModal();
        }
    });

    vehiculeForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        ensureAuth();
        const formData = new FormData(e.target);
        const isEdit = Boolean(state.vehiculeEditingId);
        const url = isEdit ? `/api/vehicules/${state.vehiculeEditingId}` : '/api/vehicules';

        // For PUT with files, we rely on Laravel's method override to keep multipart intact.
        if (isEdit) {
            formData.append('_method', 'PUT');
        }

        const res = await axios.post(url, formData, { headers: { 'Content-Type': 'multipart/form-data' } });

        // Upload attached images (if any) via the dedicated endpoint.
        const vehiculeId = res.data?.id;
        const files = vehiculeImagesInput?.files || [];
        if (vehiculeId && files.length) {
            const uploads = Array.from(files).map(file => {
                const fd = new FormData();
                fd.append('vehicule_id', vehiculeId);
                fd.append('image', file);
                return axios.post('/api/vehicule-images', fd, { headers: { 'Content-Type': 'multipart/form-data' } });
            });
            await Promise.all(uploads);
        }
        closeVehiculeModal();
        await loadVehicules();
    });

    vehiculeTableBody.addEventListener('click', async (e) => {
        const row = e.target.closest('tr[data-id]');
        if (!row) return;
        const id = row.dataset.id;
        const action = e.target.dataset.action;
        if (action === 'edit') {
            e.stopPropagation();
            const vehicule = await fetchVehicule(id);
            setVehiculeFormMode('edit', vehicule);
            openVehiculeModal('edit', vehicule);
            return;
        }
        if (action === 'delete') {
            e.stopPropagation();
            await handleDeleteVehicule(id);
            return;
        }
        await showVehiculeDetail(id);
    });

    openVehiculeModalBtn.addEventListener('click', () => openVehiculeModal('create'));
    closeVehiculeModalBtn.addEventListener('click', () => closeVehiculeModal());
    vehiculeModal.addEventListener('click', (e) => {
        if (e.target.dataset.close === 'vehicule-modal') {
            closeVehiculeModal();
        }
    });

    vehiculeImages.addEventListener('click', (e) => {
        const card = e.target.closest('[data-image-src]');
        if (!card) return;
        const src = card.dataset.imageSrc;
        const caption = card.dataset.caption || '';
        openImageViewer(resolveVehiculeImageSrc(src), caption);
    });

    closeImageViewerBtn.addEventListener('click', closeImageViewer);
    imageViewerModal.addEventListener('click', (e) => {
        if (e.target.dataset.close === 'image-viewer-modal') {
            closeImageViewer();
        }
    });
    imageViewerImg.addEventListener('wheel', (e) => {
        e.preventDefault();
        adjustImageViewerZoom(e.deltaY < 0 ? 0.12 : -0.12);
    });
    imageViewerImg.addEventListener('dblclick', () => {
        imageViewerZoom = 1;
        imageViewerImg.style.transform = 'scale(1)';
    });

    userForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        ensureAuth();
        const payload = Object.fromEntries(new FormData(e.target).entries());
        if (!payload.password) delete payload.password;
        const isEdit = Boolean(state.userEditingId);
        if (isEdit) {
            await axios.put(`/api/utilisateurs/${state.userEditingId}`, payload);
        } else {
            await axios.post('/api/utilisateurs', payload);
        }
        closeUserModal();
        await loadUsers();
        showToast(isEdit ? 'Utilisateur mis à jour.' : 'Utilisateur créé.');
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

    documentTabs.forEach(tab => {
        tab.addEventListener('click', () => activateDocumentTab(tab.dataset.docTab));
    });

    documentSection.querySelectorAll('[data-doc-add]').forEach(btn => {
        btn.addEventListener('click', () => openDocumentModal(btn.dataset.docAdd));
    });

    closeDocumentModalBtn.addEventListener('click', closeDocumentModal);
    documentModal.addEventListener('click', (e) => {
        if (e.target.dataset.close === 'document-modal') {
            closeDocumentModal();
        }
    });

    documentForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        ensureAuth();
        const payload = Object.fromEntries(new FormData(documentForm).entries());
        const type = state.documentCurrentType;
        payload.type = type;
        const isEdit = Boolean(state.documentEditingId);
        if (isEdit) {
            await axios.put(`/api/vehicule-documents/${state.documentEditingId}`, payload);
        } else {
            await axios.post('/api/vehicule-documents', payload);
        }
        closeDocumentModal();
        await loadDocuments();
        showToast(isEdit ? 'Document mis à jour.' : 'Document ajouté.');
    });

    documentSection.addEventListener('click', async (e) => {
        const actionBtn = e.target.closest('[data-doc-action]');
        if (!actionBtn) return;
        const row = actionBtn.closest('tr[data-doc-id]');
        if (!row) return;
        const id = row.dataset.docId;
        const type = row.dataset.docType;
        if (actionBtn.dataset.docAction === 'edit') {
            const doc = (state.documents[type] || []).find(d => Number(d.id) === Number(id));
            openDocumentModal(type, doc || { id });
            return;
        }
        if (actionBtn.dataset.docAction === 'delete') {
            const confirmed = window.confirm('Supprimer ce document ?');
            if (!confirmed) return;
            await axios.delete(`/api/vehicule-documents/${id}`);
            await loadDocuments();
            showToast('Document supprimé.');
        }
    });

    activateDocumentTab(state.documentCurrentType);

    document.getElementById('param-form').addEventListener('submit', async (e) => {
        e.preventDefault();
        ensureAuth();
        const payload = Object.fromEntries(new FormData(e.target).entries());
        try {
            await axios.put('/api/parametres', payload);
            await loadParametres();
            showToast('Paramètres enregistrés.');
        } catch (err) {
            showToast(extractErrorMessage(err), 'error');
            console.error(err);
        }
    });

    if (bootstrapAuth()) {
        Promise.all([loadChauffeurs(), loadVehicules(), loadUsers(), loadDocuments(), loadMetrics(), loadParametres()]).catch(err => console.error(err));
    }
