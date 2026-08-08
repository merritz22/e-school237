<?php

/*
|--------------------------------------------------------------------------
| Statistiques scolaires nationales (Cameroun)
|--------------------------------------------------------------------------
|
| Utilisées sur la page d'accueil pour ancrer le discours commercial dans
| des chiffres réels et sourcés. À mettre à jour chaque année dès la
| publication des résultats officiels de la session en cours — c'est la
| seule chose à changer, tout le reste du contenu (page d'accueil, cartes
| de statistiques) se met à jour automatiquement.
|
*/

return [

    'year' => 2026,

    'exam' => [
        'label' => 'Baccalauréat',
        'pass_rate' => 42.14,
        'previous_pass_rate' => 47.45,
        'previous_year' => 2025,
        'candidates_present' => 130772,
        'candidates_registered' => 131628,
        'admitted' => 55108,
        'admitted_girls' => 30750,
        'admitted_boys' => 24358,
        'top_regions' => ['Centre', 'Littoral', 'Ouest'],
    ],

    'source_label' => 'Office du Baccalauréat du Cameroun (OBC) — résultats officiels, session 2026',
    'source_url' => 'https://fr.journalducameroun.com/cameroun-le-bac-esg-2026-affiche-un-taux-de-reussite-de-4214/',

];
