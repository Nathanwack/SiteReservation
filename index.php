<?php
require_once('_partial/header.php');

require_once __DIR__ . '/connexion/db.php';


$week = $_GET['week'] ?? '2026-02-01';
$debutSemaine = date('Y-m-d 09:00:00', strtotime($week));
$finSemaine   = date('Y-m-d 17:00:00', strtotime($week . ' +6 days'));

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

$formatter = new IntlDateFormatter(
    'fr_FR',
    IntlDateFormatter::NONE,
    IntlDateFormatter::NONE,
    null,
    null,
    'EEEE'
);

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

    $difJour = date('d', strtotime($reser['dateHeure_fin'])) - date('d', strtotime($reser['dateHeure_debut']));

    $reser['jourDebut'] = $jourDebut;
    $reser['jourFin'] = $jourFin;
    $reser['difJour'] = $difJour;
    $reser['heureDebut'] = $heureDebut;
    $reser['heureFin'] = $heureFin;

    return $reser;
}

foreach ($reservations as $key => $reser) {
    $reservations[$key] = convert($reser);
}


function span($reser)
{

    switch ($reser['difJour']) {
        case 0:
            echo 'grid-span-1';
            break;
        case 1:
            echo 'grid-span-2';
            break;
        case 2:
            echo 'grid-span-3';
            break;
        case 3:
            echo 'grid-span-4';
            break;
        case 4:
            echo 'grid-span-5';
            break;
        case 5:
            echo 'grid-span-6';
            break;
        case 6:
            echo 'grid-span-7';
            break;

        default:
            // Code to execute if expression doesn't match any case
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
    <h1 class="fs-1 text-center text-light my-5">Planning de réservations par semaine</h1>
    <!-- debut de la table  -->

    <div class="grid-7 mb-5 text-start">
        <div class="box">Lundi</div>
        <div class="box">Mardi</div>
        <div class="box">Mercredi</div>
        <div class="box">Jeudi</div>
        <div class="box">Vendredi</div>
        <div class="box">Samedi</div>
        <div class="box">Dimanche</div>
    </div>
    <div class="grid-7 mb-5">

        <?php foreach ($reservations as $reser) {
        ?>

            <div class="box <?php span($reser); startGrid($reser); ?>" >
                <p class="text-danger text-capitalize">Réservé par <?= $reser['nom'] ?></p>
                <p class="text-danger text-capitalize">De <?= $reser['heureDebut']  . 'h  à' . $reser['heureFin']  . 'h'  ?></p>
            </div>

        <?php } ?>
    </div>




















    <!-- buttons  -->


    <div class="d-flex flex-column flex-md-row justify-content-md-between gap-2 mt-5">
        <button class="btn btn-primary"><img src="/SiteReservation/assets/fleche-gauche.png" alt="Logo" height="20">Semaine précédente</button>
        <button class="btn btn-primary">Semaine suivante<img src="/SiteReservation/assets/fleche-droite.png" alt="Logo" height="20"></button>
    </div>



</div>

<?php
require_once('_partial/footer.php');
?>