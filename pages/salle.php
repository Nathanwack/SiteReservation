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
$sqlSalle = "SELECT * FROM salle ORDER BY type,capacite,libelle";
$stmtSalle = $pdo->prepare($sqlSalle);
$stmtSalle->execute();

$sallesObj = $stmtSalle->fetchAll(PDO::FETCH_ASSOC);


//traitment de suppression d'une salle
// var_dump($_GET);
if (isset($_POST['delete']) && !empty($_POST['salle_id'])) {



    $salleAsupprimer = htmlspecialchars(trim($_POST['salle_id']));

    // verifier si la salle a supprimer n'es pas deja reservé
    //à regler : si method au dessus est get , le message success est quand meme affiché !!! 


    $sqlCheck = "SELECT COUNT(*) FROM reservation WHERE salle_id = :id";
    $stmtCheck = $pdo->prepare($sqlCheck);
    $stmtCheck->execute(['id' => $salleAsupprimer]);

    $nbReservations = $stmtCheck->fetchColumn();

    if ($nbReservations > 0) {
        // la salle est utilisée → suppression interdite
        $error[] = "Impossible de supprimer : la salle est déjà réservée.";

        //si la salle n'est pas reservé :
    } else {

        $sqlSalleDelete = "DELETE FROM salle WHERE id = :id";
        $stmtSalleDelete = $pdo->prepare($sqlSalleDelete);
        $resultatSupprimer = $stmtSalleDelete->execute([
            'id' => $salleAsupprimer
        ]);


        if ($resultatSupprimer) {
            $supprime = "La salle a bien été supprimée";
            header("Refresh:10; url=salle.php");
        } else {
            $error[] = "Une erreur est survenue lors de la suppression de l'article";
        }
    }
}
//fin traitment du suppression

//traitement de modification 
if (isset($_POST['edit']) && !empty($_POST['salle_id'])) {

    $salleAModifier = htmlspecialchars(trim($_POST['salle_id']));
    $_SESSION['id_modifier']=$salleAModifier;

    $sqlSalleEdit = "SELECT * FROM salle WHERE id = :id";
    $stmtSalleEdit = $pdo->prepare($sqlSalleEdit);
    $resultatSalleRecu = $stmtSalleEdit->execute([
        'id' => $salleAModifier
    ]);
    // récuperer les données de salle a modifier
    

    $resultatSalleRecu = $stmtSalleEdit->fetch(PDO::FETCH_ASSOC);
    // var_dump($resultatSalleRecu);

    $libelle  =       $resultatSalleRecu['libelle'];
    $capacite =       $resultatSalleRecu['capacite'];
    $type     =       $resultatSalleRecu['type'];

}
    if (isset($_POST['modifier'])) {
        //var_dump($_POST);
        if (!empty($_POST['libelle']) && !empty($_POST['capacite']) && !empty($_POST['type'])) {

            $libelle  =       htmlspecialchars(trim($_POST['libelle']));
            $capacite =       htmlspecialchars(trim($_POST['capacite']));
            $type     =       htmlspecialchars(trim($_POST['type']));

            $salleAModifier=$_SESSION['id_modifier'];

            if (!$libelle) {
                $error[] = "Libelle ne peut etre vide ";
            }
            if (!$capacite) {
                $error[] = "Capacité ne peut etre vide ";
            }

            if ($type == "not-valid") {
                $error[] = "Veuillez choisir un type pour la salle";
            }
             if ($type == "") {
                $error[] = "Veuillez choisir un type pour la salle";
            }

            if ($type == "Choisir le nom de la salle") {
                $error[] = "Veuillez choisir un type valide pour la salle  ";
            }
            // var_dump($_POST);

            if (!$error) {

                $sql = "UPDATE salle 
                SET libelle = :libelle, 
                    capacite = :capacite, 
                    type = :type 
                        WHERE id = :id";

                $requete = $pdo->prepare($sql);
                $resultat = $requete->execute(array(
                    'libelle'          => $libelle,
                    'capacite'         => $capacite,
                    'type'             => $type,
                    'id'               => $salleAModifier 
                ));
                
                if ($resultat) {
                    $success = "La salle a bien été mise à jour en BDD";
                    $_SESSION=[];
                    header("Refresh:10; url=salle.php");

                } else {
                    $error[] = "Erreur lors de la mise à jour en BDD";
                }
            }
        }
    }





//traitement d'insertion d'une salle
if (isset($_POST['submit'])) {
    if (!empty($_POST['libelle']) && !empty($_POST['capacite']) && !empty($_POST['type'])) {

        $libelle  =       htmlspecialchars(trim($_POST['libelle']));
        $capacite =       htmlspecialchars(trim($_POST['capacite']));
        $type     =       htmlspecialchars(trim($_POST['type']));

        if (!$libelle) {
            $error[] = "Libelle ne peut etre vide ";
        }
        if (!$capacite) {
            $error[] = "Capacité ne peut etre vide ";
        }

        if ($type == "not_valid") {
            $error[] = "Veuillez choisir un type pour la salle";
        }

        if ($type == "Choisir le nom de la salle") {
            $error[] = "Veuillez choisir un type valide pour la salle";
        }
        //var_dump($_POST);

        $sqlSalles = "SELECT libelle FROM salle";
        $stmt = $pdo->query($sqlSalles);

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if (strtolower($row['libelle']) === strtolower($libelle)) {
                $error[] = "Erreur : ce libellé existe déjà.";
            };
        }



        if (!$error) {
            $sql = "INSERT INTO salle (libelle, capacite, type) 
            VALUES (:libelle, :capacite, :type)";

            $requete = $pdo->prepare($sql);
            $resultat = $requete->execute(array(
                'libelle'          => $libelle,
                'capacite'         => $capacite,
                'type'             => $type
            ));

            if ($resultat) {
                $success = "La salle est bien ajoutée en BDD";
                header("Refresh:10; url=salle.php");
            } else {
                $error[] = "Erreur lors de l'insertion en BDD";
            }
        }
    }
}



?>


<div class="container p-5">
    <h1 class="fs-1 text-center text-light my-5">Enregistrer une salle </h1>
    <!-- debut de formulaire -->
    <div class="form-salle col-12 center p-5">
        <form action="" method="POST">


            <div class="my-3">
                <label for="libelle" class="form-label">Libellé de la salle</label>
                <input type="text" name="libelle" class="form-control" id="libelle" placeholder="libelle" required value=<?= $libelle ?>>
            </div>

            <label>Type de la salle</label>
            <select class="form-select" aria-label="Default select example" required name="type">
                <option value="not_valid" selected>Choisir le type de la salle</option>
                <option value="1">Open-space</option>
                <option value="2">Bureau</option>
                <option value="3">Salle de réunion</option>
            </select>

            <div class="my-3">
                <label for="capacite" class="form-label">Capacité</label>
                <input type="number" name="capacite" min="1" max="100" class="form-control" id="capacite" placeholder="La capacité de la salle" value=<?= $capacite ?> required>
            </div>
            <?php if (isset($_POST['edit']) && !empty($_POST['salle_id'])) {
            ?>
                <input class="btn btn-dark btn-lg px-5" type="submit" name="modifier" value="Modifier" />

            <?php } else { ?>

                <input class="btn btn-primary btn-lg px-5" type="submit" name="submit" value="Valider" /> <?php } ?>
        </form>
    </div>

    <?php if ($supprime) { ?>

        <p class="text-center bg-danger p-3 mx-5 fs-4 rounded mt-3  "><?= $supprime;
                                                                    } ?> </p>
        <?php
        if ($error) {

            foreach ($error as $err) { ?>

                <p class="text-center bg-danger-subtle p-3 mx-5 fs-4 rounded mt-3  "><?= $err;
                                                                                    } ?> </p>

            <?php
        } else if ($success) {
            ?>
                <p class="text-center bg-info-subtle p-3 mx-5 fs-4 rounded "><?= $success; ?> </p>

                <div class="salle_modif bg-dark px-5 py-2 col-6 mt-5 text-light text-center rounded mx-auto">

                <p class="text-center fs-4">Informations de la salle </p>

                    <hr class="bg-light">

                    <p class="text-center fs-5">Libellé de salle :
                        <?php if ($libelle) {
                            echo $libelle;
                        }  ?></p>
                    <p class="text-center fs-5">Capacité de salle :
                        <?php if ($capacite) {
                            echo $capacite;
                        }  ?></p>

                    <p class="text-center fs-5">Type de salle :
                        <?php if ($type) {
                            echo $type;
                        }  ?></p>



                </div>

            <?php } ?>
            <!-- fin de formulaire -->
            <!-- affichage des salles -->
</div>

<div class="container mt-5">
    <table class="table table-striped rounded text-center">
        <thead class="bg-info text-light">
            <tr>
                <th class="bg-dark text-light" scope="col"> </th>
                <th class="bg-dark text-light" scope="col">Libellé de la Salle</th>
                <th class="bg-dark text-light" scope="col">Type</th>
                <th class="bg-dark text-light" scope="col">Capacité</th>
                <th class="bg-dark text-light" scope="col">Action</th>
                <th class="bg-dark text-light" scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($sallesObj as $salle) {  ?>
                <tr>
                    <form action="" method="post">
                        <th scope="row"><?= $i ?></th>
                        <td name="salle-libelle"><?= $salle['libelle'] ?></td>
                        <td><?= ucfirst($salle['type']) ?></td>
                        <td><?= $salle['capacite'] ?></td>
                        <td><button class="btn btn-primary rounded p-1" name="edit">Modifier</button></td>
                        <td><button class="btn bg-danger rounded p-1 text-light" name="delete" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette salle ?');">Supprimer</button></td>
                        <input type="hidden" name="salle_id" value="<?= $salle['id'] ?>">

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