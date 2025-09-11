<?php

function convertirDateEnFrancais($date) {
    $mois = [
        1 => 'janvier',
        2 => 'février',
        3 => 'mars',
        4 => 'avril',
        5 => 'mai',
        6 => 'juin',
        7 => 'juillet',
        8 => 'août',
        9 => 'septembre',
        10 => 'octobre',
        11 => 'novembre',
        12 => 'décembre'
    ];

    $date = trim($date, '()');
    
    $parties = explode(' ', $date);
    $datePart = $parties[0];
    $heurePart = $parties[1] ?? null;

    list($annee, $moisNum, $jour) = explode('-', $datePart);
    $moisLettre = $mois[(int)$moisNum];
    
    $resultat = "$jour $moisLettre $annee";
    
    if ($heurePart) {
        list($heures, $minutes, $secondes) = explode(':', $heurePart);
        $resultat .= " à $heures h $minutes";
    }
    
    return $resultat;
}

?>