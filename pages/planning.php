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



function dateDebut($reser){

    $timestampDebut = strtotime($reser['dateHeure_debut']);
    $heureDebut = date('H', $timestampDebut);
    $dateDebut = date('Y-m-d', $timestampDebut);
    echo $dateDebut.' à '.$heureDebut.'h';
}
function dateFin($reser){

    $timestampFin   = strtotime($reser['dateHeure_fin']);
    $heureFin   = date('H', $timestampFin);
    $dateFin   = date('Y-m-d', $timestampFin);
    echo $dateFin.' à '.$heureFin.'h';
}
var_dump($_POST) ;
if (isset($_POST['delete']) && !empty($_POST['id'])) {

    
    var_dump($_POST) ;

    $reservAsupprimer = htmlspecialchars(trim($_POST['id']));

    // verifier si la salle a supprimer n'es pas deja reservé
    //à regler : si method au dessus est get , le message success est quand meme affiché !!! 

        $sqlReservDelete = "DELETE FROM reservation WHERE id = :id";
        $stmtReservDelete = $pdo->prepare($sqlReservDelete);
        $resultatSupprimer = $stmtReservDelete->execute([
            'id' => $reservAsupprimer
        ]);


        if ($resultatSupprimer) {
            $supprime = "La reservation a bien été supprimée";
            header("Refresh:10; url=planning.php");
        } else {
            $error[] = "Une erreur est survenue lors de la suppression ";
        }
    }

//fin traitment du suppression



?>



<div class="container mt-5">
    <?php if ($supprime) { ?>

        <p class="text-center bg-danger p-3 mx-5 fs-4 rounded mt-3  "><?= $supprime;
                                                                    } ?> </p>
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
                        <input type="hidden" name="id" value="<?= $reser['id'] ?>">

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