<?php
require_once __DIR__ . '/../_partial/header.php';
require_once __DIR__ . '/../connexion/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// var_dump($_SESSION);
$success = null;
$error = [];
$civilite=null;
$dateDebut=null;
$dateFin=null;
$capacite=null;
$heureDebut=null;
$heureFin=null;
if(isset($_SESSION['reservation']))
{

$nom=$_SESSION['reservation']['nom'];
$parties = explode(" ", $nom);

$civilite = $parties[0]; 
$nom  = $parties[1]; 
echo $civilite ; 
echo $nom;

$capacite=$_SESSION['reservation']['capacite'];
$type=$_SESSION['reservation']['type'];;
$dateDebut=$_SESSION['reservation']['dateHeure_debut'];
$dateFin=$_SESSION['reservation']['dateHeure_fin'];

$heureDebut =(int) date("H", strtotime($dateDebut));
$heureFin = (int)(date("H", strtotime($dateFin)));

 //traitement de date jour et fin en format complet sans heure

$dateDebut = date('Y-m-d', strtotime($dateDebut));
$dateFin = date('Y-m-d', strtotime($dateFin));

}
else{

$nom=null;
$capacite=null;
$type=null;
$dateDebut=null;
$dateFin=null;

}
$sqlSalles="SELECT DISTINCT type FROM salle";

$stmt = $pdo->query($sqlSalles);
$salles = $stmt->fetchAll(PDO::FETCH_ASSOC);

// var_dump($salles);
// foreach($salles as $salle){
//     echo $salle['type'];
// }

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
       
    var_dump($_POST);
        if (!$civilite) {
            $error[] = "Veuillez choisir une civilité";
        }

        if (!$nom) {
            $error[] = "Le libellé ne peut être vide ";
        }
        elseif(!preg_match("/^[a-zA-ZÀ-ÖØ-öø-ÿ]+$/", $nom)){
            $error[] = "Le nom ne peut contenir que des lettres";
        }
        
        if ($capacite=="not-valid") {
            $error[] = "Veuillez choisir une capacité";
        }
        if ($type=="not-valid") {
            $error[] = "Veuillez choisir un type pour la salle";
        }
        if ($type=null) {
            $error[] = "Veuillez choisir un type pour la salle";
        }

        if($dateDebut>$dateFin){
            $error[]="La date de fin ne peut être inferieure à la date de début";
        }
        
        if($dateDebut==$dateFin && $heureDebut >= $heureFin  ){
            $error[]="L'heure de fin ne peut etre inferieure ou égale à l'heure de début";
        }
        if (!$dateDebut) {
            $error[] = "Veuillez choisir une date debut";
        }

        if (!$dateFin) {
            $error[] = "Veuillez choisir une date fin";
        } 


        // var_dump($_POST);

        if (!$error) {

        $nomC=$civilite.' '.$nom;

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
                    $error[] = "Aucune salle disponible pour cette période";                   
                    header("Refresh:3; url=reservation.php");

                }
            }
            else {
                $error[] = "Une erreur s'est produite lors de la recherche ; veuillez reessayer plus tard.";
            }
        }
    }
}


?>

<div class="container p-5">
    <h1 class="fs-1 text-center text-light my-5">Réserver une salle</h1>
    <div class="form-salle col-12 center p-5">
        <form action="" method="post">
            <div class="my-3 ">
                <label for="nom" class="form-label">Nom :</label placeholder="dd"> </br>
                <input type="radio" name="civilite" value=<?= ($civilite === 'M.')  ? 'selected' : 'M.' ?> id="" >
                <label>M.</label>
                <input type="radio" name="civilite" value=<?= ($civilite === 'Mm.')  ? 'selected' : 'Mme.' ?> id="" checked>
                <label>Mme.</label> 
                <input class="form-control mt-1" type="text" name="nom" placeholder="Nom"  value="<?= $nom ?>" required>
            </div>
            
            <div class="my-3 ">
                <label for="type" class="form-label">Type de la salle :</label>
                <select class="form-select" aria-label="Default select example" name="type" required>
                    <option value="not-valid" selected>Choisir un type de salle</option>
                    <?php foreach($salles as $salle){?>
                    <option value=<?= $salle['type'] ?> <?= ($type === $salle['type'])  ? 'selected' : '' ?> ><?= ucfirst($salle['type']) ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="my-3 ">
                <label for="capacite" class="form-label">Capacité :</label>
                <select class="form-select" aria-label="Default select example" name="capacite" required>
                    <option value="not-valid" selected>Choisir une capacité</option>
                    <option value="5" <?= ($capacite == 5     || ($capacite <=5 && 0<= $capacite ))  ? 'selected' : '5' ?> >0 à 5 personnes</option>
                    <option value="10" <?= ($capacite == 10   || ($capacite <=10 && 5<= $capacite ))  ? 'selected' : '' ?> >5 à 10 personnes</option>
                    <option value="50" <?= ($capacite == 50   || ($capacite <=50 && 10<= $capacite ))  ? 'selected' : '' ?>>10 à 50 personnes</option>
                    <option value="100" <?= ($capacite == 100 || ($capacite <=100 && 50<= $capacite ))  ? 'selected' : '' ?>>50 à 100 personnes</option>
                </select>
            </div>
            <div class="my-3">
                <label for="dateDebut" class="form-label">Date et heure de début :</label>
                <input type="date" name="dateDebut" id="date" min="2026-02-02" max="2026-04-29" value="<?= $dateDebut ?>" required>
                <select name="heureDebut">
                    <option value="9" <?= $heureDebut  == 9 ? 'selected' : ''  ?>>9</option>
                    <option value="10" <?= $heureDebut == 10 ? 'selected' : ''  ?>>10</option>
                    <option value="11" <?= $heureDebut == 11 ? 'selected' : ''  ?>>11</option>
                    <option value="12" <?= $heureDebut == 12 ? 'selected' : ''  ?>>12</option>
                    <option value="13" <?= $heureDebut == 13 ? 'selected' : ''  ?>>13</option>
                    <option value="14" <?= $heureDebut == 14 ? 'selected' : ''  ?>>14</option>
                    <option value="15" <?= $heureDebut == 15 ? 'selected' : ''  ?>>15</option>
                    <option value="16" <?= $heureDebut == 16 ? 'selected' : ''  ?>>16</option>
                </select> 
            </div>

            <div class="my-3">
                <label for="dateFin" class="form-label">Date et heure de fin :</label>
                <input type="date" name="dateFin" id="date" min="2026-02-01" max="2026-04-30" required value="<?= $dateFin ?>">
                <select name="heureFin">
                    <option value="9" <?= $heureFin == 9 ? 'selected' : ''  ?>>9</option>
                    <option value="10" <?= $heureFin == 10 ? 'selected' : ''  ?>>10</option>
                    <option value="11" <?= $heureFin == 11 ? 'selected' : ''  ?>>11</option>
                    <option value="12" <?= $heureFin == 12 ? 'selected' : ''  ?>>12</option>
                    <option value="13" <?= $heureFin == 13 ? 'selected' : ''  ?>>13</option>
                    <option value="14" <?= $heureFin == 14 ? 'selected' : ''  ?>>14</option>
                    <option value="15" <?= $heureFin == 15 ? 'selected' : ''  ?>>15</option>
                    <option value="16" <?= $heureFin == 16 ? 'selected' : ''  ?>>16</option>
                </select>
                
            </div>

           <?php if(isset($_SESSION['reservation'])) { ?>
            <input class="btn btn-dark text-light" type="submit" name="submit" value="Mettre à jour la reservation " />

            <?php } else { ?>
            <input class="btn btn-primary" type="submit" name="submit" value="Rechercher une disponiblité" />
            <?php } ?>

        </form>   
        
    </div>
    

      <?php  if ($error) {
        foreach ($error as $err) { ?>

            <p class="text-center bg-danger-subtle p-3 mx-5 fs-4 rounded mt-3">
                <?= $err;  } }?> </p>

    <div class="row ">
        <div class="col-5"></div>
        <div class="col-6"><a href="planning.php" class="p-2 rounded text-center bg-warning">Voir toutes les reservations </a></div>
        
        <div class="col-1">
            
        </div>
        
    </div>
    
    </div>

    

    <?php
    require_once('../_partial/footer.php');
    ?>