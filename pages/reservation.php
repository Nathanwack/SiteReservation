<?php
require_once __DIR__ . '/../_partial/header.php';
require_once __DIR__ . '/../connexion/db.php';

$success = null;
$error = [];

if (isset($_POST['submit'])) {
    if (!empty($_POST['nom']) && !empty($_POST['type']) && !empty($_POST['capacite']) && !empty($_POST['dateDebut']) && !empty($_POST['dateFin']) && !empty($_POST['heureDebut']) && !empty($_POST['heureFin'])) {

        $nom          =       htmlspecialchars(trim($_POST['nom']));
        $capacite     =       htmlspecialchars(trim($_POST['capacite']));
        $type         =       htmlspecialchars(trim($_POST['type']));
        $dateDebut    =       htmlspecialchars(trim($_POST['dateDebut']));
        $dateFin      =       htmlspecialchars(trim($_POST['dateFin']));
        $heureDebut   =       htmlspecialchars(trim($_POST['heureDebut']));
        $heureFin     =       htmlspecialchars(trim($_POST['heureFin']));
        $minDebut     =       htmlspecialchars(trim($_POST['minDebut']));
        $minFin       =       htmlspecialchars(trim($_POST['minFin']));

        $dateDebut = date('d/m/Y', strtotime($dateDebut));
        echo $dateDebut;

        if (!$nom) {
            $error[] = "Libelle ne peut etre vide ";
        }
        if (!$capacite) {
            $error[] = "Capacité ne peut etre vide ";
        }

        if (!$type) {
            $error[] = "Veuillez choisir un type pour la salle  ";
        }

        if (!$dateDebut) {
            $error[] = "Veuillez choisir un type valide pour la salle  ";
        } else {
            $dateDebut = $dateDebut . '-' . $heureDebut . 'h' . $minDebut;
        }
        if (!$dateFin) {
            $error[] = "Veuillez choisir un type valide pour la salle  ";
        } else {
            $dateFin = $dateFin . '-' . $heureFin . 'h' . $minFin;
        }

        var_dump($_POST);
        echo $dateDebut;
        echo $dateFin;

        // if (!$error) {
        //     $sql = "INSERT INTO salle (libelle, capacite, type) 
        //     VALUES (:libelle, :capacite, :type)";

        //     $requete = $pdo->prepare($sql);
        //     $resultat = $requete->execute(array(
        //         'libelle'          => $libelle,
        //         'capacite'         => $capacite,
        //         'type'             => $type
        //     ));

        //     if ($resultat) {
        //         $success = "La salle es bien ajouté en BDD";
        //     } else {
        //         $error[] = "Erreur lors d'insertion en BDD";
        //     }
        // }
    }
}






?>

<div class="container p-5">
    <h1 class="text-start fs-2">Formulaire de réservation de salles</h1>
    <div class="form-salle col-12 center p-5">
        <form action="" method="post">
            <div class="my-3 ">
                <label for="nom" class="form-label">Nom :</label placeholder="dd">
                <input class="form-control" type="text" name="nom" placeholder="Nom" required>
            </div>
            <div class="my-3 ">
                <label for="type" class="form-label">Type de salle :</label>
                <select class="form-select" aria-label="Default select example" name="type" required>
                    <option selected>Choisir un type de salle</option>
                    <option value="open-space">Open-space</option>
                    <option value="bureau">Bureau</option>
                    <option value="salle-de-réunion">Salle de réunion</option>
                </select>
            </div>
            <div class="my-3 ">
                <label for="capacite" class="form-label">Capacité :</label>
                <select class="form-select" aria-label="Default select example" name="capacite" required>
                    <option selected>Choisir une capacité</option>
                    <option value="5">0 à 5 personnes</option>
                    <option value="10">5 à 10 personnes</option>
                    <option value="50">10 à 50 personnes</option>
                    <option value="100">50 à 100 personnes</option>
                </select>
            </div>
            <div class="my-3">
                <label for="dateDebut" class="form-label">Date et heure de début :</label>
                <input type="date" name="dateDebut" id="date" min="2026-02-01" max="2026-04-29">
                <select name="heureDebut">
                    <option value="9">9</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>
                    <option value="13">13</option>
                    <option value="14">14</option>
                    <option value="15">15</option>
                    <option value="16">16</option>
                </select>
                <select name="minDebut">
                    <option value="0">00</option>
                    <option value="30">30</option>
                </select>
            </div>

            <div class="my-3">
                <label for="dateFin" class="form-label">Date et heure de fin :</label>
                <input type="date" name="dateFin" id="date" min="2026-02-01" max="2026-04-30">
                <select name="heureFin">
                    <option value="9">9</option>
                    <option value="10">10</option>
                    <option value="11">11</option>
                    <option value="12">12</option>
                    <option value="13">13</option>
                    <option value="14">14</option>
                    <option value="15">15</option>
                    <option value="16">16</option>
                </select>
                <select name="minFin">
                    <option value="0">00</option>
                    <option value="30">30</option>
                </select>
            </div>

            <input class="btn btn-primary" type="submit" name="submit" value="Rechercher une disponiblité" />
        </form>

        <?php
        require_once('../_partial/footer.php');
        ?>