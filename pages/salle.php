<?php

require_once __DIR__ . '/../_partial/header.php';
require_once __DIR__ . '/../connexion/db.php';

$success = null;
$error = [];




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

        if (!$type) {
            $error[] = "Veuillez choisir un type pour la salle  ";
        }

        if ($type == "Choisir le nom du salle") {
            $error[] = "Veuillez choisir un type valide pour la salle  ";
        }
        var_dump($_POST);
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
                $success = "La salle es bien ajouté en BDD";
            } else {
                $error[] = "Erreur lors d'insertion en BDD";
            }
        }
    }
}



?>


<div class="container p-5">
    <p class="text-start fs-2">Enregistrer une salle </p>
    <div class="form-salle col-12 center p-5">
        <form action="" method="POST">


            <div class="my-3 ">
                <label for="libelle" class="form-label">Libellé de la salle</label>
                <input type="text" name="libelle" class="form-control" id="libelle" placeholder="libelle" required>
            </div>

            <label>Type du salle</label>
            <select class="form-select" aria-label="Default select example" required name="type">
                <option selected>Choisir le nom du salle</option>
                <option value="1">Open-sapce</option>
                <option value="2">Bureau</option>
                <option value="3">Salle de reunion</option>
            </select>

            <div class="my-3">
                <label for="capacite" class="form-label">Capacité</label>
                <input type="number" name="capacite" min="1" max="100" class="form-control" id="capacite" placeholder="La capacité du salle" required>
            </div>

            
                <button class="btn btn-primary btn-lg px-5" type="submit" name="submit">
                    <p class="text-center">Valider</p>
                </button>
            

        </form>

    </div>
    <?php
    if ($error) {

        foreach ($error as $err) { ?>

            <p class="text-center bg-danger-subtle p-3 mx-5 fs-4 rounded "><?= $err;
                                                                        } ?> </p>

        <?php
    } else if ($success) {
        ?>
            <p class="text-center bg-info-subtle p-3 mx-5 fs-4 rounded "><?= $success; ?> </p>

            <div class="salle_modif bg-dark px-5 py-2 col-6 mt-5 text-light text-center rounded mx-auto">

                <p class="text-center fs-4">Information de la salle </p>

                <hr class="bg-light">

                <p class="text-center fs-5">Libellé de salle :
                <?php if ($libelle) {
                                                echo $libelle;
                                            }  ?></p>
                <p class="text-center fs-5">Capcité de salle :
                <?php if ($capacite) {
                                            echo $capacite;
                                        }  ?></p>

                <p class="text-center fs-5">Type de salle :
                    <?php if ($type) {
                        echo $type;
                    }  ?></p>



            </div>

        <?php } ?>

</div>