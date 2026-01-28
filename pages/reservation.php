<?php 
require_once('../_partial/header.php');






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
            <div class="my-3 ">
                <label for="dateDebut" class="form-label">Date et heure de début :</label>
                <input type="datetime-local" name="dateDebut" required  min="2026-02-01T08:00" max="2026-04-30T18:00">
            </div>
            <div class="my-3 ">
                <label for="dateFin" class="form-label">Date et heure de fin :</label>
                <input type="datetime-local" name="dateFin" required>
            </div>
            <input class="btn btn-primary" type="submit" value="Rechercher une disponiblité" />
        </form>

<?php 
    require_once('../_partial/footer.php');
?>