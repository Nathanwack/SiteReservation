<?php
require_once('../_partial/header.php');
require_once __DIR__ . '/../connexion/db.php';

session_start();

if (!isset($_SESSION['sallesDisponibles'])) {
    header('Location: reservation.php');
    exit;
}
$message = null;
$success=null;
$error=null;
//recuperation des elements de la session 

$sallesDisponibles = $_SESSION['sallesDisponibles'];
$dateDebut = $_SESSION['dateDebut'];
$dateFin = $_SESSION['dateFin'];
$nom = $_SESSION['nom'];
$type=$_SESSION['type_salle'];

//traitement de conversion date et heure en date locale 
$dateDebutDT = new DateTime($dateDebut);
$dateFinDT   = new DateTime($dateFin);

$dateD  = $dateDebutDT->format('d/m/Y');
$heureD = $dateDebutDT->format('H:i');

$dateF  = $dateFinDT->format('d/m/Y');
$heureF = $dateFinDT->format('H:i');


if ($dateD == $dateF) {
    $message = $dateD . " de  " . $heureD . " à " . $heureF;
} else {
    $message = $dateD . " à " . $heureD . " jusqu'à " . $dateF . " à " . $heureF;
}

if (isset($_POST['submit'])) {
    if (!empty($_POST['choix_reservation'])) {
        var_dump($_POST);
        $id_salle = htmlspecialchars(trim($_POST['choix_reservation']));

        $sql = "INSERT INTO reservation (dateHeure_debut, dateHeure_fin,nom,salle_id) 
            VALUES (:dateHeure_debut, :dateHeure_fin, :nom, :salle_id)";

        $requete = $pdo->prepare($sql);
        $resultat = $requete->execute(array(
            'dateHeure_debut'          => $dateDebut,
            'dateHeure_fin'            => $dateFin,
            'nom'                      => $nom,
            'salle_id'                 => $id_salle
        ));

        if ($resultat) {
             $success = "La reservation est bien enregistrée";
            
        } else {
            $error[] = "Erreur lors d'insertion en BDD";
        }
    }
}

?>

<div class="resultat-recherche container my-5">

<?php if(!$success){ ?>
    <div class="bg-secondary p-5 mb-5 rounded">
        <p class="fs-5 text-light text-center ">Voici le resultat de votre recherche pour la periode  </br></br>
            <?= $message ?><br>
            et de'une salle du type  :

        
        </p>


    </div>
    <form action="" method="post">
        <table class="table table-dark table-striped-columns ">

            <thead class="text-center">
                <tr>
                    <th scope="col">Salle</th>
                    <th scope="col">Capacité</th>
                    <th scope="col">Choisir</th>
                </tr>
            </thead>
            <tbody class="text-center">
                <?php foreach ($sallesDisponibles as $salle): ?>
                    <tr>
                        <th scope="row"><?= htmlspecialchars($salle['libelle']) ?></th>
                        <td><?= htmlspecialchars($salle['capacite']) ?></td>
                        <td><input type="radio" name="choix_reservation" value="<?= htmlspecialchars($salle['id']) ?>" id="<?= htmlspecialchars($salle['id']) . "choix" ?>"></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>

        </table>
        <div class="d-grid gap-2 col-4 float-end mt-3">
            <input class="btn btn-info btn-lg py-3 text-light" type="submit" name="submit" value="Valider" />
        </div>
    </form>

    <?php } else { ?>

    <div class="bg-secondary p-5 mb-5 rounded">
        <p class="fs-5 text-light text-center "> <?= $success." </br> pour ".$message;  ?></br>

        <a href="reservation.php"class="btn btn-info text-light mx-5 my-5">Faire une autre réservation </a> 
        <a href="../index.php"class="btn btn-dark text-light">voir le planning des réservations</a> 

        <?php }?>
           
    </div>


</div>


<?php
require_once('../_partial/footer.php');
?>