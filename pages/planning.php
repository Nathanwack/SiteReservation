<?php
session_start();
require_once __DIR__ . '/../_partial/header.php';
require_once __DIR__ . '/../connexion/db.php';

$success = null;
$error = [];
$supprime = null;
$capacite = null;
$libelle = null;
$type = null;

//traitement de recuperation des salles 
$i = 1;
$sqlreser = "SELECT * FROM salle inner join  reservation on salle.id=reservation.salle_id ORDER BY libelle";
$stmtReser = $pdo->prepare($sqlreser);
$stmtReser->execute();
$reserObj = $stmtReser->fetchAll(PDO::FETCH_ASSOC);




$formatter = new IntlDateFormatter(
    'fr_FR',
    IntlDateFormatter::FULL,
    IntlDateFormatter::SHORT,
    null,
    null,
    'EEEE dd/MM/yyyy HH:mm'
);

foreach ($reserObj as $reser) {

    

    
}

function dateDebut($reser,){

    $timestampDebut = strtotime($reser['dateHeure_debut']);
    $heureDebut = date('H', $timestampDebut);
    $dateDebut = date('Y-m-d', $timestampDebut);
    echo $dateDebut.' à '.$heureDebut.'h';
}
function dateFin($reser,){

    $timestampFin   = strtotime($reser['dateHeure_fin']);
    $heureFin   = date('H', $timestampFin);
    $dateFin   = date('Y-m-d', $timestampFin);
    echo $dateFin.' à '.$heureFin.'h';
}





?>



<div class="container mt-5">
    <table class="table table-striped rounded text-center">
        <thead class="bg-info text-light">
            <tr>
                <th class="bg-dark text-light" scope="col"> </th>
                <th class="bg-dark text-light" scope="col">Nom </th>
                <th class="bg-dark text-light" scope="col">Date/heure debut</th>
                <th class="bg-dark text-light" scope="col">Date/heure fin</th>
                <th class="bg-dark text-light" scope="col">Salle</th>
                <th class="bg-dark text-light" scope="col">Capacité du salle</th>
                <th class="bg-dark text-light" scope="col">Action</th>
                <th class="bg-dark text-light" scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($reserObj as $reser) { ?>
            
                <tr>
                    <form action="" method="post">
                        <th scope="row"><?= $i ?></th>
                        <td name="salle-libelle"><?= $reser['nom'] ?></td>

                        <td><?=  dateDebut($reser);?></td>
                        <td><?= dateFin($reser) ?></td>
                        <td name="salle-libelle"><?= $reser['libelle'] ?></td>
                        <td><?= $reser['capacite'] ?></td>
                        <td><button class="btn btn-primary rounded p-1" name="edit">Modifier</button></td>
                        <td><button class="btn bg-danger rounded p-1 text-light" name="delete" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette salle ?');">Supprimer</button></td>
                        <input type="hidden" name="salle_id" value="<?= $reser['id'] ?>">

                    </form>
                </tr>
            <?php $i++;
            } ?>
        </tbody>
    </table>
</div>

<?php
require_once __DIR__ . '/../_partial/footer.php';
?>