<?php 
require_once('../_partial/header.php');
?>

<div class="resultat-recherche">
        <table class="table table-dark table-striped-columns">

            <thead class="text-center">
                <tr>
                    <th scope="col">Salle</th>
                    <th scope="col">Date</th>
                    <th scope="col" >Choisir</th>
                </tr>
            </thead>
            <tbody class="text-center" >
                <tr >
                    <th scope="row">1</th>
                    <td>Mark</td>
                    <td ><input type="radio" name="resultat" id=""></td>
                </tr>
                <tr>
                    <th scope="row">2</th>
                    <td>Jacob</td>
                    <td ><input type="radio" name="resultat" id=""></td>
                </tr>
                <tr>
                    <th scope="row">3</th>
                    <td>John</td>
                    <td ><input type="radio" name="resultat" id=""></td>
                </tr>
            </tbody>

        </table>

    </div>

<?php 
    require_once('../_partial/footer.php');
?>