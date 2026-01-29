<?php
require_once('_partial/header.php');

require_once __DIR__ . '/connexion/db.php';

$sql = "SELECT * FROM reservation INNER Join salle on salle.id=reservation.salle_id ORDER BY dateHeure_debut,dateHeure_fin";
$stmt = $pdo->prepare($sql);
$stmt->execute();

$resultats = $stmt->fetchAll(PDO::FETCH_ASSOC);
$i=1;

//to do : modifier l'affichage de l'heure et date
// to do : planning par semaine 
?>

<div class="container p-5">
    <h1 class="fs-1 text-center text-light my-5">Planning de toutes les reservations </h1>
    <table class="table table-striped rounded">
        <thead  class="bg-info text-light">
            <tr>
                <th  class="bg-dark text-light" scope="col">  </th>
                <th  class="bg-dark text-light" scope="col">Libellé de Salle</th>
                <th class="bg-dark text-light" scope="col">Date et heure de début</th>
                <th class="bg-dark text-light" scope="col">Date et heure de fin</th>
                <th class="bg-dark text-light" scope="col">Réservé par</th>
            </tr>
        </thead>
        <tbody>
         <?php   foreach ($resultats as $ligne) {  ?>
            <tr>
                <th scope="row"><?= $i ?></th>
                <td><?= $ligne['libelle'] ?></td>
                <td><?= $ligne['dateHeure_debut'] ?></td>
                <td><?= $ligne['dateHeure_fin'] ?></td>
                <td><?= $ligne['nom'] ?></td>
            </tr>
           <?php $i++ ; } ?>
        </tbody>
    </table>

</div>

<?php
require_once('_partial/footer.php');
?>