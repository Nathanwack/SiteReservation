<?php
require_once __DIR__ . '/../_partial/header.php';
require_once __DIR__ . '/../connexion/db.php';

session_start();
$success = null;
$error = [];
$nom=null;
$capacite=null;
$type=null;
$dateDebut=null;
$dateFin=null;
if (isset($_POST['submit'])) {
    if (!empty($_POST['nom']) && !empty($_POST['civilite']) &&!empty($_POST['type']) && !empty($_POST['capacite']) && !empty($_POST['dateDebut']) && !empty($_POST['dateFin']) && !empty($_POST['heureDebut']) && !empty($_POST['heureFin'])) {

        $nom          =       htmlspecialchars(trim($_POST['nom'] ?? ''));
        $civilite     =       htmlspecialchars(trim($_POST['civilite']));
        $capacite     =       htmlspecialchars(trim($_POST['capacite']));
        $type         =       htmlspecialchars(trim($_POST['type']));
        $dateDebut    =       htmlspecialchars(trim($_POST['dateDebut']));
        $dateFin      =       htmlspecialchars(trim($_POST['dateFin']));
        $heureDebut   =       htmlspecialchars(trim($_POST['heureDebut']));
        $heureFin     =       htmlspecialchars(trim($_POST['heureFin']));
       

        if (!$civilite) {
            $error[] = "Veuillez choisir le civilité ";
        }

        if (!$nom) {
            $error[] = "Libelle ne peut etre vide ";
        }
        elseif(!preg_match("/^[a-zA-ZÀ-ÖØ-öø-ÿ]+$/", $nom)){
            $error[] = "Nom ne peut contenir que des lettres";
        }
        
        if (!$capacite) {
            $error[] = "Veuillez choisir la capacité souhaité";
        }
        if (!$type) {
            $error[] = "Veuillez choisir un type pour la salle  ";
        }

        if($dateDebut>$dateFin){
            $error[]="La date de fin ne peut etre inferieur de la date de début";
        }
        
        if($dateDebut==$dateFin && $heureDebut >= $heureFin  ){
            $error[]="L'heure de fin ne peut etre inferieur / égale de l'heure de début";
        }
        if (!$dateDebut) {
            $error[] = "Veuillez choisir une date debut ";
        }

        if (!$dateFin) {
            $error[] = "Veuillez choisir une date fin  ";
        } 

        

        // var_dump($_POST);

        if (!$error) {

        $nomC=$civilite.'. '.$nom;
        
        $dateDebut = $dateDebut . ' ' . $heureDebut . ':00:00';
        $dateFin = $dateFin . ' ' . $heureFin . ':00:00';

            $sql = "SELECT s.*
                FROM salle s
                WHERE s.type = :type
                AND s.capacite >= :capacite
                AND NOT EXISTS (
                    SELECT 1
                    FROM reservation r
                    WHERE r.salle_id = s.id
                    AND r.dateHeure_debut <= :dateHeureFin
                    AND r.dateHeure_fin >= :dateHeureDebut)";

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
                    $_SESSION['nom'] = $nomC;
                    $_SESSION['type_salle']=$type;

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
    <h1 class="fs-1 text-center text-light my-5">Formulaire de réservation de salles</h1>
    <div class="form-salle col-12 center p-5">
        <form action="" method="post">
            <div class="my-3 ">
                <label for="nom" class="form-label">Nom :</label placeholder="dd"> </br>
                <input type="radio" name="civilite" value="M" id="" >
                <label>M.</label>
                <input type="radio" name="civilite" value="Mme" id="" checked>
                <label>Mme.</label> 
                <input class="form-control mt-1" type="text" name="nom" placeholder="Nom"  value="<?= $nom ?>" required>
            </div>
            
            <div class="my-3 ">
                <label for="type" class="form-label">Type de salle :</label>
                <select class="form-select" aria-label="Default select example" name="type" required>
                    <option selected>Choisir un type de salle</option>
                    <option value="open-space" <?= ($type === 'open-space')  ? 'selected' : '' ?>>Open-space</option>
                    <option value="bureau" <?= ($type === 'bureau')  ? 'selected' : '' ?>>Bureau</option>
                    <option value="salle de réunion" <?= ($type === 'salle de réunion')  ? 'selected' : '' ?>>Salle de réunion</option>
                </select>
            </div>

            <div class="my-3 ">
                <label for="capacite" class="form-label">Capacité :</label>
                <select class="form-select" aria-label="Default select example" name="capacite" required>
                    <option selected>Choisir une capacité</option>
                    <option value="5" <?= ($capacite == 5)  ? 'selected' : '' ?> >0 à 5 personnes</option>
                    <option value="10" <?= ($capacite == 10)  ? 'selected' : '' ?> >5 à 10 personnes</option>
                    <option value="50" <?= ($capacite == 50)  ? 'selected' : '' ?>>10 à 50 personnes</option>
                    <option value="100" <?= ($capacite == 100)  ? 'selected' : '' ?>>50 à 100 personnes</option>
                </select>
            </div>
            <div class="my-3">
                <label for="dateDebut" class="form-label">Date et heure de début :</label>
                <input type="date" name="dateDebut" id="date" min="2026-02-02" max="2026-04-29" value="<?= $dateDebut ?>" required>
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
                <input type="date" name="dateFin" id="date" min="2026-02-01" max="2026-04-30" required value="<?= $dateFin ?>">
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

            <p class="text-center bg-danger-subtle p-3 mx-5 fs-4 rounded mt-1">
                <?= $err;  } }?> </p>
           
    </div>



    <?php
    require_once('../_partial/footer.php');
    ?>