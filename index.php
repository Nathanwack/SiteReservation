<?php
require_once('_partial/header.php');

require_once __DIR__ . '/connexion/db.php';

$entree='2026-03-16';

$week = $_GET['week'] ?? $entree;
$debutSemaine = date('Y-m-d 09:00:00', strtotime($week));
$finSemaine   = date('Y-m-d 17:00:00', strtotime($week . ' +6 days'));

function nextWeek($week){
    echo date('Y-m-d', strtotime($week . ' +7 days'));
}

function lastWeek($week){
    echo date('Y-m-d', strtotime($week . ' -7 days'));
}

$sql = "SELECT r.*, s.*
    FROM reservation r
    JOIN salle s ON s.id = r.salle_id
    WHERE r.dateHeure_debut <= :fin
      AND r.dateHeure_fin >= :debut
    ORDER BY r.dateHeure_debut";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    'debut' => $debutSemaine,
    'fin'   => $finSemaine
]);
$reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);




function convert($reser)
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

    $jourDebut = $formatter->format(new DateTime($reser['dateHeure_debut']));
    $jourFin = $formatter->format(new DateTime($reser['dateHeure_fin']));

    $dateFin=date('d', strtotime($reser['dateHeure_fin']));
    $dateDebut=date('d', strtotime($reser['dateHeure_debut']));

    $difJour = date('d', strtotime($reser['dateHeure_fin'])) - date('d', strtotime($reser['dateHeure_debut']));

    $reser['jourDebut'] = $jourDebut;
    $reser['jourFin'] = $jourFin;
    $reser['difJour'] = $difJour;
    $reser['heureDebut'] = $heureDebut;
    $reser['heureFin'] = $heureFin;
    $reser['dateDebut'] = $dateDebut;
    $reser['dateFin'] = $dateFin;

    return $reser;
}

foreach ($reservations as $key => $reser) {
    $reservations[$key] = convert($reser);
}


function span($reser)
{

    switch (true) {
        case $reser['difJour'] == 0:
            echo 'grid-span-1 bg-dark text-light';
            break;
        case $reser['difJour'] == 1:
            echo 'grid-span-2 bg-info text-dark';
            break;
        case $reser['difJour'] == 2:
            echo 'grid-span-3 bg-danger text-light';
            break;
        case $reser['difJour'] == 3:
            echo 'grid-span-4 bg-success text-light';
            break;
        case $reser['difJour'] == 4:
            echo 'grid-span-5 bg-primary text-light';
            break;
        case $reser['difJour'] == 5:
            echo 'grid-span-6 bg-light text-dark';
            break;
        case $reser['difJour'] >= 6:   // 6 or more
            echo 'grid-span-7';
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
        case 'mercred':
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
    <h1 class="fs-1 text-center text-light mb-5">Planning de réservations par semaine</h1>
    <!-- debut de la table  -->

    <div class="grid-7 mb-5 text-start ">
        <div class="box">Lundi</div>
        <div class="box">Mardi</div>
        <div class="box">Mercredi</div>
        <div class="box">Jeudi</div>
        <div class="box">Vendredi</div>
        <div class="box">Samedi</div>
        <div class="box">Dimanche</div>
    </div>
    <div class="grid-7 mb-5 bg-bleu">
        
        <?php
        if(!$reservations){
        echo '<h5 class="grid-start-3 grid-span-3 py-2">Pas de reservation pour cette semaine</h5>';
        } else {
        foreach ($reservations as $reser) {
        ?>

            <div class="box <?php span($reser);
                            startGrid($reser); ?>">
                <p class="text-capitalize">Réservé par <?= $reser['nom'] ?></p>
                <p class="text-capitalize">De <?= $reser['jourDebut'] . $reser['dateDebut']. ' ' . $reser['heureDebut'] . 'h  à ' .$reser['dateFin']. $reser['jourFin'] . ' ' . $reser['heureFin']  . 'h'  ?>
                </p>
                <p class="text-capitalize">Salle : <?= $reser['libelle'] ?></p>
            </div>

        <?php }} ?>
    </div>






    <!-- buttons  -->


    <div class="d-flex flex-column flex-md-row justify-content-md-between gap-2 mt-5">
        <a href="?week=<?php lastWeek($week) ?>" class="btn btn-primary"><img src="/SiteReservation/assets/fleche-gauche.png" alt="Logo" height="20"> Semaine précédente</button>
        <a href="?week=<?php nextWeek($week) ?>" class="btn btn-primary">Semaine suivante <img src="/SiteReservation/assets/fleche-droite.png" alt="Logo" height="20"></a>
    </div>



</div>

<?php
require_once('_partial/footer.php');
?>