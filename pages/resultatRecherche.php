<?php 
require_once('../_partial/header.php');


session_start();

if (!isset($_SESSION['sallesDisponibles'])) {
    header('Location: reservation.php');
    exit;
}

$sallesDisponibles = $_SESSION['sallesDisponibles'];


?>

<div class="resultat-recherche container my-5">


        <div class="bg-secondary p-5 mb-5 rounded">
            <p class="fs-5 text-light text-center ">Voici le resultat de votre recherche pour la periode de :  </br>  </p>
        </div>

        <table class="table table-dark table-striped-columns ">

            <thead class="text-center">
                <tr>
                    <th scope="col">Salle</th>
                    <th scope="col">Capacité</th>
                    <th scope="col" >Choisir</th>
                </tr>
            </thead>
            <tbody class="text-center" >
                <?php foreach ($sallesDisponibles as $salle): ?>
                <tr>
                    <th scope="row"><?= htmlspecialchars($salle['libelle']) ?></th>
                    <td><?= htmlspecialchars($salle['capacite']) ?></td>
                    <td ><input type="radio" name="resultat" id="<?= htmlspecialchars($salle['id']) ?>"></td>
                </tr>
                <?php endforeach; ?>
            </tbody>

        </table>
            <div class="d-grid gap-2 col-4 float-end mt-3">
                <input class="btn btn-info btn-lg py-3 text-light" type="submit" name="submit" value="Valider"/>
            </div>

            
        </div>
        

    </div>

<?php 
    require_once('../_partial/footer.php');
?>