<?php
require_once('_partial/header.php');

require_once __DIR__ . '/connexion/db.php';

$sql = "SELECT * FROM reservation INNER Join salle on salle.id=reservation.salle_id ORDER BY dateHeure_debut,dateHeure_fin";
$stmt = $pdo->prepare($sql);
$stmt->execute();

$resultats = $stmt->fetchAll(PDO::FETCH_ASSOC);
$i = 1;

//to do : modifier l'affichage de l'heure et date
// to do : planning par semaine 
?>

<div class="container p-5">
    <h1 class="fs-1 text-center text-light my-5">Planning de toutes les reservations </h1>
   <!-- debut de la table  -->
    <table class="table table-striped rounded text-center">
        <thead  class="bg-info text-light">
            <tr>
                <th class="bg-dark text-light" scope="col"> </th>
                <th class="bg-dark text-light" scope="col">Lundi</th>
                <th class="bg-dark text-light" scope="col">Mardi</th>
                <th class="bg-dark text-light" scope="col">Mercredi</th>
                <th class="bg-dark text-light" scope="col">Jeudi</th>
                <th class="bg-dark text-light" scope="col">Vendredi</th>
            </tr>
        </thead>
        <tbody>
            <?php for ($i = 9; $i < 17; $i++) { ?>
                <tr>

                    <th scope="row"><?= $i . 'h-' . ($i + 1) . 'h';
                                } ?></th>
                </tr>

        </tbody>
    </table>
    <!-- buttons  -->


    <div class="d-flex flex-column flex-md-row justify-content-md-between gap-2">
        <button class="btn btn-success">La semaine précédente</button>
        <button class="btn btn-success">La semaine suivante</button>
    </div>



</div>

<?php
require_once('_partial/footer.php');
?>