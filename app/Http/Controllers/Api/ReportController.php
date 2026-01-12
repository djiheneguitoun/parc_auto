<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Chauffeur;
use App\Models\Vehicule;
use App\Models\VehiculeDocument;
use Illuminate\Http\Request;
use Mpdf\Mpdf;

class ReportController extends Controller
{
    private const MENTION_LABELS = [
        'excellent' => ['label' => 'Excellent', 'score' => 5],
        'tres_bon' => ['label' => 'Très bon', 'score' => 4],
        'bon' => ['label' => 'Bon', 'score' => 3],
        'moyen' => ['label' => 'Moyen', 'score' => 2],
        'insuffisant' => ['label' => 'Insuffisant', 'score' => 1],
    ];

    private const COMPORTEMENT_LABELS = [
        'excellent' => 'Excellent',
        'tres_bon' => 'Très bon',
        'satisfaisant' => 'Satisfaisant',
        'a_ameliorer' => 'À améliorer',
        'insuffisant' => 'Insuffisant',
        'non_conforme' => 'Non conforme',
        'a_risque' => 'À risque',
    ];

    private function getVehiculesQuery(Request $request)
    {
        $query = Vehicule::with('chauffeur');

        if ($request->filled('categorie')) {
            $query->where('categorie', $request->input('categorie'));
        }
        if ($request->filled('option_vehicule')) {
            $query->where('option_vehicule', $request->input('option_vehicule'));
        }
        if ($request->filled('energie')) {
            $query->where('energie', $request->input('energie'));
        }
        if ($request->filled('boite')) {
            $query->where('boite', $request->input('boite'));
        }
        if ($request->filled('leasing')) {
            $query->where('leasing', $request->input('leasing'));
        }
        if ($request->filled('utilisation')) {
            $query->where('utilisation', $request->input('utilisation'));
        }
        if ($request->filled('affectation')) {
            $query->where('affectation', 'like', '%' . $request->input('affectation') . '%');
        }

        $start = $request->input('date_acquisition_start');
        $end = $request->input('date_acquisition_end');
        if ($start) {
            $query->whereDate('date_acquisition', '>=', $start);
        }
        if ($end) {
            $query->whereDate('date_acquisition', '<=', $end);
        }

        return $query->orderBy('marque')->orderBy('modele')->get();
    }

    public function exportVehicules(Request $request)
    {
        $vehicules = $this->getVehiculesQuery($request);

        $mpdf = new Mpdf(['format' => 'A4-L']);
        $mpdf->SetTitle('Rapport des véhicules');

        $html = $this->buildHtml($vehicules, $request);
        $mpdf->WriteHTML($html);

        return response($mpdf->Output('', 'S'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="vehicules.pdf"',
        ]);
    }

    public function previewVehicules(Request $request)
    {
        $vehicules = $this->getVehiculesQuery($request);
        $html = $this->buildHtml($vehicules, $request, true);
        return response($html, 200, ['Content-Type' => 'text/html; charset=UTF-8']);
    }

    private function getChauffeursQuery(Request $request)
    {
        $query = Chauffeur::query();
        if ($request->filled('statut')) {
            $query->where('statut', $request->input('statut'));
        }
        if ($request->filled('mention')) {
            $query->where('mention', $request->input('mention'));
        }

        return $query->orderBy('nom')->orderBy('prenom')->get();
    }

    public function exportChauffeurs(Request $request)
    {
        $chauffeurs = $this->getChauffeursQuery($request);

        $mpdf = new Mpdf(['format' => 'A4']);
        $mpdf->SetTitle('Rapport des chauffeurs');
        $html = $this->buildChauffeursHtml($chauffeurs, $request);
        $mpdf->WriteHTML($html);

        return response($mpdf->Output('', 'S'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="chauffeurs.pdf"',
        ]);
    }

    public function previewChauffeurs(Request $request)
    {
        $chauffeurs = $this->getChauffeursQuery($request);
        $html = $this->buildChauffeursHtml($chauffeurs, $request, true);
        return response($html, 200, ['Content-Type' => 'text/html; charset=UTF-8']);
    }

    private function getChargesQuery(Request $request)
    {
        $query = VehiculeDocument::with('vehicule');

        if ($request->filled('vehicule')) {
            $vehicule = $request->input('vehicule');
            $query->where(function ($q) use ($vehicule) {
                $q->where('vehicule_id', $vehicule)
                  ->orWhereHas('vehicule', function ($sub) use ($vehicule) {
                      $sub->where('code', 'like', "%{$vehicule}%");
                  });
            });
        }
        if ($request->filled('type')) {
            $query->where('type', $request->input('type'));
        }

        return $query->orderBy('type')->orderByDesc('created_at')->get();
    }

    public function exportCharges(Request $request)
    {
        $charges = $this->getChargesQuery($request);

        $mpdf = new Mpdf(['format' => 'A4-L']);
        $mpdf->SetTitle('Rapport des charges véhicules');
        $html = $this->buildChargesHtml($charges, $request);
        $mpdf->WriteHTML($html);

        return response($mpdf->Output('', 'S'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="charges-vehicules.pdf"',
        ]);
    }

    public function previewCharges(Request $request)
    {
        $charges = $this->getChargesQuery($request);
        $html = $this->buildChargesHtml($charges, $request, true);
        return response($html, 200, ['Content-Type' => 'text/html; charset=UTF-8']);
    }

    private function getFacturesQuery(Request $request)
    {
        $query = VehiculeDocument::with('vehicule');

        if ($request->filled('vehicule')) {
            $vehicule = $request->input('vehicule');
            $query->where(function ($q) use ($vehicule) {
                $q->where('vehicule_id', $vehicule)
                  ->orWhereHas('vehicule', function ($sub) use ($vehicule) {
                      $sub->where('code', 'like', "%{$vehicule}%");
                  });
            });
        }
        if ($request->filled('start')) {
            $query->whereDate('date_facture', '>=', $request->input('start'));
        }
        if ($request->filled('end')) {
            $query->whereDate('date_facture', '<=', $request->input('end'));
        }

        return $query->whereNotNull('date_facture')->orderBy('date_facture', 'desc')->get();
    }

    public function exportFactures(Request $request)
    {
        $factures = $this->getFacturesQuery($request);

        $mpdf = new Mpdf(['format' => 'A4']);
        $mpdf->SetTitle('Rapport des factures véhicule');
        $html = $this->buildFacturesHtml($factures, $request);
        $mpdf->WriteHTML($html);

        return response($mpdf->Output('', 'S'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="factures-vehicule.pdf"',
        ]);
    }

    public function previewFactures(Request $request)
    {
        $factures = $this->getFacturesQuery($request);
        $html = $this->buildFacturesHtml($factures, $request, true);
        return response($html, 200, ['Content-Type' => 'text/html; charset=UTF-8']);
    }

    private function buildHtml($vehicules, Request $request, bool $isPreview = false)
    {
        $date = now()->format('d/m/Y H:i');
        $filters = $this->formatFilters($request);

        $rows = $vehicules->map(function ($v) {
            return sprintf(
                '<tr>
                    <td>%s</td>
                    <td>%s</td>
                    <td>%s</td>
                    <td>%s</td>
                    <td>%s</td>
                    <td>%s</td>
                    <td>%s</td>
                    <td>%s</td>
                    <td>%s</td>
                    <td>%s</td>
                </tr>',
                e($v->code),
                e($v->numero),
                e(trim(($v->marque ?? '') . ' ' . ($v->modele ?? ''))),
                e($v->categorie),
                e($v->option_vehicule),
                e($v->energie),
                e($v->boite),
                e($v->utilisation),
                e($v->affectation),
                optional($v->date_acquisition)->format('d/m/Y')
            );
        })->implode('');

        if ($rows === '') {
            $rows = '<tr><td colspan="10" style="text-align:center; padding:12px;">Aucune donnée trouvée pour ces filtres.</td></tr>';
        }

        $filtersHtml = $filters ? '<div class="filters">Filtres : ' . e($filters) . '</div>' : '';

        $previewStyles = $isPreview ? '
        body { padding: 20px; max-width: 100%; margin: 0 auto; }
        @media print {
            body { padding: 0; }
        }
        ' : '';

        return <<<HTML
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; color: #111; }
        h1 { font-size: 20px; margin: 0 0 6px; }
        .meta { color: #555; margin-bottom: 10px; }
        .filters { font-size: 11px; margin-bottom: 8px; color: #333; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 6px 8px; }
        th { background: #f2f4f8; text-align: left; }
        tr:nth-child(even) { background: #fafbfc; }
        {$previewStyles}
    </style>
</head>
<body>
    <h1>Rapport des véhicules</h1>
    <div class="meta">Généré le {$date}</div>
    {$filtersHtml}
    <table>
        <thead>
            <tr>
                <th>Code</th>
                <th>Numéro</th>
                <th>Marque / Modèle</th>
                <th>Catégorie</th>
                <th>Option</th>
                <th>Énergie</th>
                <th>Boîte</th>
                <th>Utilisation</th>
                <th>Affectation</th>
                <th>Date acquisition</th>
            </tr>
        </thead>
        <tbody>
            {$rows}
        </tbody>
    </table>
</body>
</html>
HTML;
    }

    private function buildChauffeursHtml($chauffeurs, Request $request, bool $isPreview = false)
    {
        $date = now()->format('d/m/Y H:i');
        $filters = $this->formatFilters($request, [
            'statut' => 'Statut',
            'mention' => 'Mention',
        ], [
            'mention' => fn($value) => $this->formatMentionLabel($value),
        ]);

        $rows = $chauffeurs->map(function ($c) {
            return sprintf(
                '<tr>
                    <td>%s</td>
                    <td>%s</td>
                    <td>%s</td>
                    <td>%s</td>
                    <td>%s</td>
                    <td>%s</td>
                </tr>',
                e($c->matricule),
                e($c->nom),
                e($c->prenom),
                e($c->statut),
                e($this->formatMentionLabel($c->mention)),
                e($this->formatComportementLabel($c->comportement))
            );
        })->implode('');

        if ($rows === '') {
            $rows = '<tr><td colspan="6" style="text-align:center; padding:12px;">Aucune donnée trouvée pour ces filtres.</td></tr>';
        }

        $previewStyles = $isPreview ? '
        body { padding: 20px; max-width: 100%; margin: 0 auto; }
        @media print {
            body { padding: 0; }
        }
        ' : '';

        return <<<HTML
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; color: #111; }
        h1 { font-size: 20px; margin: 0 0 6px; }
        .meta { color: #555; margin-bottom: 10px; }
        .filters { font-size: 11px; margin-bottom: 8px; color: #333; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 6px 8px; }
        th { background: #f2f4f8; text-align: left; }
        tr:nth-child(even) { background: #fafbfc; }
        {$previewStyles}
    </style>
</head>
<body>
    <h1>Rapport des chauffeurs</h1>
    <div class="meta">Généré le {$date}</div>
    {$filters}
    <table>
        <thead>
            <tr>
                <th>Matricule</th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Statut</th>
                <th>Mention</th>
                <th>Comportement</th>
            </tr>
        </thead>
        <tbody>
            {$rows}
        </tbody>
    </table>
</body>
</html>
HTML;
    }

    private function buildChargesHtml($charges, Request $request, bool $isPreview = false)
    {
        $date = now()->format('d/m/Y H:i');
        $filters = $this->formatFilters($request, [
            'vehicule' => 'Véhicule',
            'type' => 'Type de charge',
        ]);

        $rows = $charges->map(function ($c) {
            return sprintf(
                '<tr>
                    <td>%s</td>
                    <td>%s</td>
                    <td>%s</td>
                    <td>%s</td>
                    <td>%s</td>
                    <td>%s</td>
                </tr>',
                e(optional($c->vehicule)->code ?? $c->vehicule_id),
                e($c->type),
                e($c->libele),
                e($c->partenaire),
                e(optional($c->debut)->format('d/m/Y')),
                e(optional($c->expiration)->format('d/m/Y'))
            );
        })->implode('');

        if ($rows === '') {
            $rows = '<tr><td colspan="6" style="text-align:center; padding:12px;">Aucune donnée trouvée pour ces filtres.</td></tr>';
        }

        $previewStyles = $isPreview ? '
        body { padding: 20px; max-width: 100%; margin: 0 auto; }
        @media print {
            body { padding: 0; }
        }
        ' : '';

        return <<<HTML
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; color: #111; }
        h1 { font-size: 20px; margin: 0 0 6px; }
        .meta { color: #555; margin-bottom: 10px; }
        .filters { font-size: 11px; margin-bottom: 8px; color: #333; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 6px 8px; }
        th { background: #f2f4f8; text-align: left; }
        tr:nth-child(even) { background: #fafbfc; }
        {$previewStyles}
    </style>
</head>
<body>
    <h1>Rapport des charges véhicules</h1>
    <div class="meta">Généré le {$date}</div>
    {$filters}
    <table>
        <thead>
            <tr>
                <th>Véhicule</th>
                <th>Type</th>
                <th>Libellé</th>
                <th>Partenaire</th>
                <th>Début</th>
                <th>Expiration</th>
            </tr>
        </thead>
        <tbody>
            {$rows}
        </tbody>
    </table>
</body>
</html>
HTML;
    }

    private function buildFacturesHtml($factures, Request $request, bool $isPreview = false)
    {
        $date = now()->format('d/m/Y H:i');
        $filters = $this->formatFilters($request, [
            'vehicule' => 'Véhicule',
            'start' => 'Période début',
            'end' => 'Période fin',
        ]);

        $rows = $factures->map(function ($f) {
            return sprintf(
                '<tr>
                    <td>%s</td>
                    <td>%s</td>
                    <td>%s</td>
                    <td>%s</td>
                    <td>%s</td>
                </tr>',
                e(optional($f->vehicule)->code ?? $f->vehicule_id),
                e($f->num_facture),
                e($f->libele),
                e(optional($f->date_facture)->format('d/m/Y')),
                e($f->valeur)
            );
        })->implode('');

        if ($rows === '') {
            $rows = '<tr><td colspan="5" style="text-align:center; padding:12px;">Aucune donnée trouvée pour ces filtres.</td></tr>';
        }

        $previewStyles = $isPreview ? '
        body { padding: 20px; max-width: 100%; margin: 0 auto; }
        @media print {
            body { padding: 0; }
        }
        ' : '';

        return <<<HTML
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; color: #111; }
        h1 { font-size: 20px; margin: 0 0 6px; }
        .meta { color: #555; margin-bottom: 10px; }
        .filters { font-size: 11px; margin-bottom: 8px; color: #333; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 6px 8px; }
        th { background: #f2f4f8; text-align: left; }
        tr:nth-child(even) { background: #fafbfc; }
        {$previewStyles}
    </style>
</head>
<body>
    <h1>Rapport des factures véhicule</h1>
    <div class="meta">Généré le {$date}</div>
    {$filters}
    <table>
        <thead>
            <tr>
                <th>Véhicule</th>
                <th>N° facture</th>
                <th>Libellé</th>
                <th>Date facture</th>
                <th>Montant</th>
            </tr>
        </thead>
        <tbody>
            {$rows}
        </tbody>
    </table>
</body>
</html>
HTML;
    }

    private function formatMentionLabel(?string $value): string
    {
        if (!$value || !isset(self::MENTION_LABELS[$value])) {
            return $value ?: '-';
        }

        $config = self::MENTION_LABELS[$value];
        return $config['label'] . ' ' . $this->starString($config['score']);
    }

    private function formatComportementLabel(?string $value): string
    {
        if (!$value) {
            return '-';
        }

        return self::COMPORTEMENT_LABELS[$value] ?? $value;
    }

    private function starString(int $score): string
    {
        $score = max(0, min(5, $score));
        return str_repeat('★', $score) . str_repeat('☆', 5 - $score);
    }

    private function formatFilters(Request $request, array $labelMap = [], array $transformMap = [])
    {
        $parts = [];
        $map = array_merge([
            'categorie' => 'Catégorie',
            'option_vehicule' => 'Option',
            'energie' => 'Énergie',
            'boite' => 'Boîte',
            'leasing' => 'Leasing',
            'utilisation' => 'Utilisation',
            'affectation' => 'Affectation',
        ], $labelMap);

        foreach ($map as $key => $label) {
            if ($request->filled($key)) {
                $value = $request->input($key);
                if (isset($transformMap[$key]) && is_callable($transformMap[$key])) {
                    $value = $transformMap[$key]($value);
                }
                $parts[] = $label . ': ' . $value;
            }
        }

        if ($request->filled('date_acquisition_start') || $request->filled('date_acquisition_end')) {
            $start = $request->input('date_acquisition_start') ?: '...';
            $end = $request->input('date_acquisition_end') ?: '...';
            $parts[] = 'Date acquisition: ' . $start . ' → ' . $end;
        }

        if ($request->filled('start') || $request->filled('end')) {
            $start = $request->input('start') ?: '...';
            $end = $request->input('end') ?: '...';
            $parts[] = 'Période: ' . $start . ' → ' . $end;
        }

        return $parts ? '<div class="filters">Filtres : ' . e(implode(' | ', $parts)) . '</div>' : '';
    }
}