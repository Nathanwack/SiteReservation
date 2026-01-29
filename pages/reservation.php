<?php
require_once __DIR__ . '/../_partial/header.php';
require_once __DIR__ . '/../connexion/db.php';

session_start();
$success = null;
$error = [];

//to do : ajouter champe civilité 

if (isset($_POST['submit'])) {
    if (!empty($_POST['nom']) && !empty($_POST['type']) && !empty($_POST['capacite']) && !empty($_POST['dateDebut']) && !empty($_POST['dateFin']) && !empty($_POST['heureDebut']) && !empty($_POST['heureFin'])) {

        $nom          =       htmlspecialchars(trim($_POST['nom']));
        $capacite     =       htmlspecialchars(trim($_POST['capacite']));
        $type         =       htmlspecialchars(trim($_POST['type']));
        $dateDebut    =       htmlspecialchars(trim($_POST['dateDebut']));
        $dateFin      =       htmlspecialchars(trim($_POST['dateFin']));
        $heureDebut   =       htmlspecialchars(trim($_POST['heureDebut']));
        $heureFin     =       htmlspecialchars(trim($_POST['heureFin']));
       




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
            $dateDebut = $dateDebut . ' ' . $heureDebut . ':00:00';
        }

        if (!$dateFin) {
            $error[] = "Veuillez choisir un type valide pour la salle  ";
        } else {
            $dateFin = $dateFin . ' ' . $heureFin . ':00:00';
        }




        var_dump($_POST);

        if (!$error) {
            $sql = "SELECT s.*
                FROM salle s
                WHERE s.type = :type
                AND s.capacite >= :capacite
                AND NOT EXISTS (
                    SELECT 1
                    FROM reservation r
                    WHERE r.salle_id = s.id
                    AND r.dateHeure_debut < :dateHeureFin
                    AND r.dateHeure_fin > :dateHeureDebut)";

            $requete = $pdo->prepare($sql);

            // Exécution avec les paramètres
            $resultat = $requete->execute(array(
                'type'            => $type,
                'capacite'        => $capacite,
                'dateHeureDebut'  => $dateDebut,
                'dateHeureFin'    => $dateFin
            ));

            // Récupération des résultats
            if ($resultat) {
                $sallesDisponibles = $requete->fetchAll(PDO::FETCH_ASSOC);

                if (!empty($sallesDisponibles)) {
                    // Stocker les résultats en session
                    $_SESSION['dateDebut']=$dateDebut;
                    $_SESSION['dateFin']=$dateFin;
                    $_SESSION['sallesDisponibles'] = $sallesDisponibles;
                    $_SESSION['nom'] = $nom;

                    // Redirection vers la page résultat
                    header('Location: resultatRecherche.php');
                    exit;
                } else {
                    $error[] = "Aucune salle disponible pour cette période.";
                }
            }
            else {
                $error[] = "il y a une erreur lors de la recherche ; veuillez reessayer plus tard.";
            }
        }
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
                    <option value="salle de réunion">Salle de réunion</option>
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
                <input type="date" name="dateDebut" id="date" min="2026-02-01" max="2026-04-29" required>
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
                
            </div>

            <div class="my-3">
                <label for="dateFin" class="form-label">Date et heure de fin :</label>
                <input type="date" name="dateFin" id="date" min="2026-02-01" max="2026-04-30" required>
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
                
            </div>

            <input class="btn btn-primary" type="submit" name="submit" value="Rechercher une disponiblité" />
        </form>    
    </div>
    

      <?php  if ($error) {
        foreach ($error as $err) { ?>

            <p class="text-center bg-danger-subtle p-3 mx-5 fs-4 rounded ">
                <?= $err;  } }?> </p>
           
    </div>



    <?php
    require_once('../_partial/footer.php');
    ?>