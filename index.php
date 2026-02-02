<?php
require_once('_partial/header.php');

require_once __DIR__ . '/connexion/db.php';

$entree = '2026-02-02';
$week = $_GET['week'] ?? $entree;

//pour la BDD
$debutSemaine = date('Y-m-d 09:00:00', strtotime($week));
$finSemaine   = date('Y-m-d 17:00:00', strtotime($week . ' +6 days'));

function nextWeek($week)
{
    return date('Y-m-d', strtotime($week . ' +7 days'));
}

function lastWeek($week)
{
    return date('Y-m-d', strtotime($week . ' -7 days'));
}

$sql = "SELECT r.*, s.*
    FROM reservation r
    JOIN salle s ON s.id = r.salle_id
    WHERE r.dateHeure_debut <= :fin
      AND r.dateHeure_fin >= :debut
    ORDER BY r.dateHeure_debut,r.dateHeure_fin";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    'debut' => $debutSemaine,
    'fin'   => $finSemaine
]);
$reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);




function convert($reser, $debutSemaine, $finSemaine)
{
    $formatter = new IntlDateFormatter(
        'fr_FR',
        IntlDateFormatter::NONE,
        IntlDateFormatter::NONE,
        null,
        null,
        'EEEE'
    );

    $heureDebut = date("H", strtotime($reser['dateHeure_debut']));
    $heureFin = date("H", strtotime($reser['dateHeure_fin']));




    //traitement de date jour et fin en format complet sans heure
    $dateFin = date('Y-m-d', strtotime($reser['dateHeure_fin']));
    $dateDebut = date('Y-m-d', strtotime($reser['dateHeure_debut']));

    //traitement de la semaine presente
    $debutSemaine = date('Y-m-d', strtotime($debutSemaine));
    $finSemaine = date('Y-m-d', strtotime($finSemaine));

    // var_dump($reser['nom'],$dateDebut,' et',$debutSemaine);
    $result = "not yet";

    if ($dateFin >= $debutSemaine && $dateFin <= $finSemaine && $dateDebut < $debutSemaine) {

        $dateDebut = $debutSemaine;
        $result = "this is the case : " . $dateDebut;
    }
    if ($dateFin >= $debutSemaine && $dateDebut <= $debutSemaine) {

        $dateDebut = $debutSemaine;
        $result = "this is the case : " . $dateDebut;
    }



    $dateFinN = date('d', strtotime($dateFin));
    $dateDebutN = date('d', strtotime($dateDebut));


    $jourDebut = $formatter->format(new DateTime($dateDebut));
    $jourFin = $formatter->format(new DateTime($dateFin));
    $difJour = $dateFinN - $dateDebutN;

    $reser['jourDebut'] = $jourDebut;
    $reser['jourFin'] = $jourFin;
    $reser['difJour'] = $difJour;
    $reser['heureDebut'] = $heureDebut;
    $reser['heureFin'] = $heureFin;
    $reser['dateDebut'] = $dateDebutN;
    $reser['dateFin'] = $dateFinN;
    $reser['result'] = $result;
    $reser['debutSemaine'] = $debutSemaine;
    //  var_dump($reser);
    return $reser;
}

foreach ($reservations as $key => $reser) {
    $reservations[$key] = convert($reser, $debutSemaine, $finSemaine);
}


function span($reser)
{

    switch (true) {
        case $reser['difJour'] == 0:
            echo 'grid-span-1 bg-danger text-light';
            break;
        case $reser['difJour'] == 1:
            echo 'grid-span-2 bg-info text-dark';
            break;
        case $reser['difJour'] == 2:
            echo 'grid-span-3 bg-success text-light';
            break;
        case $reser['difJour'] == 3:
            echo 'grid-span-4 bg-success text-light';
            break;
        case $reser['difJour'] == 4:
            echo 'grid-span-5 bg-darkblue text-light';
            break;
        case $reser['difJour'] == 5:
            echo 'grid-span-6 bg-dark text-light';
            break;
        case $reser['difJour'] >= 6:   // 6 or more
            echo 'grid-span-7 bg-dark text-light';
            break;
    }
}

function startGrid($reser)
{

    switch ($reser['jourDebut']) {
        case 'lundi':
            echo ' grid-start-1';
            break;
        case 'mardi':
            echo ' grid-start-2';
            break;
        case 'mercredi':
            echo ' grid-start-3';
            break;
        case 'jeudi':
            echo ' grid-start-4';
            break;
        case 'vendredi':
            echo ' grid-start-5';
            break;
        case 'samedi':
            echo ' grid-start-6';
            break;
        case 'dimanche':
            echo ' grid-start-7';
            break;

        default:
            // Code to execute if expression doesn't match any case
    }
}


?>

<div class="container p-5">
    <h1 class="fs-1 text-center text-light mb-5">Planning des réservations</h1>
    <!-- debut de la table  -->

    <!-- buttons  -->


    <div class="d-flex flex-column flex-md-row justify-content-md-between gap-2 mb-5">
        <a href="?week=<?= lastWeek($week) ?>" class="btn btn-primary"><img src="/SiteReservation/assets/fleche-gauche.png" alt="Logo" height="20"> Semaine précédente</button>
            <a href="?week=<?= nextWeek($week) ?>" class="btn btn-primary">Semaine suivante <img src="/SiteReservation/assets/fleche-droite.png" alt="Logo" height="20"></a>
    </div>

    <!-- semaine  -->
    <div class="grid-7 mb-2 text-start ">
        <div class="box">Lundi <p><?= date('d-m', strtotime($week)); ?></p>
        </div>
        <div class="box">Mardi <p><?= date('d-m', strtotime($week . ' +1 days')); ?></p>
        </div>
        <div class="box">Mercredi <p><?= date('d-m', strtotime($week . ' +2 days')); ?></p>
        </div>
        <div class="box">Jeudi <p><?= date('d-m', strtotime($week . ' +3 days')); ?></p>
        </div>
        <div class="box">Vendredi <p><?= date('d-m', strtotime($week . ' +4 days')); ?></p>
        </div>
        <div class="box">Samedi <p><?= date('d-m', strtotime($week . ' +5 days')); ?></p>
        </div>
        <div class="box">Dimanche <p><?= date('d-m', strtotime($week . ' +6 days')); ?></p>
        </div>
    </div>
    <div class="grid-7 mb-5 bg-bleu">

        <?php
        if (!$reservations) {
            echo '<h5 class="grid-start-3 grid-span-3 py-2">Pas de reservation pour cette semaine</h5>';
        } else {
            foreach ($reservations as $reser) {
        ?>

                <div class="box <?php span($reser);
                                startGrid($reser); ?>">

                    <p class="text-capitalize">Réservé par <?= $reser['nom'] ?></p>
                    <p class="text-capitalize">De <?= $reser['jourDebut'] . ' ' . $reser['dateDebut'] . ' ' . $reser['heureDebut'] . 'h - à ' . $reser['dateFin'] . ' ' . $reser['jourFin'] . ' ' . $reser['heureFin']  . 'h'  ?>
                    </p>
                    <p class="text-capitalize">Salle : <?= $reser['libelle'] ?></p>
                </div>

        <?php }
        } ?>
    </div>










</div>

<?php
require_once('_partial/footer.php');
?>