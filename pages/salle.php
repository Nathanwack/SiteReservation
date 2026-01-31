<?php

require_once __DIR__ . '/../_partial/header.php';
require_once __DIR__ . '/../connexion/db.php';

$success = null;
$error = [];

//traitement de recuperation des salles 
$i=1;
            $sqlSalle = "SELECT * FROM salle ORDER BY type,capacite,libelle";
            $stmtSalle = $pdo->prepare($sqlSalle);
            $stmtSalle->execute();

            $sallesObj = $stmtSalle->fetchAll(PDO::FETCH_ASSOC);

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

        if (!$type) {
            $error[] = "Veuillez choisir un type pour la salle  ";
        }

        if ($type == "Choisir le nom du salle") {
            $error[] = "Veuillez choisir un type valide pour la salle  ";
        }
        // var_dump($_POST);

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
                $success = "La salle es bien ajouté en BDD";
            } else {
                $error[] = "Erreur lors d'insertion en BDD";
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


            <div class="my-3 ">
                <label for="libelle" class="form-label">Libellé de la salle</label>
                <input type="text" name="libelle" class="form-control" id="libelle" placeholder="libelle" required>
            </div>

            <label>Type du salle</label>
            <select class="form-select" aria-label="Default select example" required name="type">
                <option selected>Choisir le type de salle</option>
                <option value="1">Open-space</option>
                <option value="2">Bureau</option>
                <option value="3">Salle de reunion</option>
            </select>

            <div class="my-3">
                <label for="capacite" class="form-label">Capacité</label>
                <input type="number" name="capacite" min="1" max="100" class="form-control" id="capacite" placeholder="La capacité du salle" required>
            </div>
            <input class="btn btn-primary btn-lg px-5" type="submit" name="submit" value="Valider"/>
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
                <th class="bg-dark text-light" scope="col">Libellé de Salle</th>
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
                    <th scope="row"><?= $i ?></th>
                    <td><?= $salle['libelle'] ?></td>
                    <td><?= ucfirst($salle['type']) ?></td>
                    <td><?= $salle['capacite'] ?></td>
                    <td><button class="btn btn-primary rounded p-1">Modifier</button></td>
                    <td><button class="btn bg-danger rounded p-1 text-light">Supprimer</button></td>
                </tr>
            <?php $i++;
            } ?>
        </tbody>
    </table>
</div>

<?php
require_once __DIR__ . '/../_partial/footer.php';
?>