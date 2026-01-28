<h1>Formulaire de réservation de salles</h1>

<form action="" method="post">
    <div>
        <label for="nom">Nom :</label placeholder="dd">
        <input type="text" name="nom" require>
    </div>
    <div>
        <label for="type">Type de salle :</label>
        <select name="type" require>
            <option value="open-space">Open-space</option>
            <option value="bureau">Bureau</option>
            <option value="salle de réunion">Salle de réunion</option>
        </select>
    </div>
    <div>
        <label for="capacite">Capacité :</label>
        <select name="capacite" require>
            <option value="5">0 à 5 personnes</option>
            <option value="10">5 à 10 personnes</option>
            <option value="50">10 à 50 personnes</option>
            <option value="100">50 à 100 personnes</option>
        </select>
    </div>
    <div>
        <label for="dateDebut">Date et heure de début :</label>
        <input type="datetime-local" name="dateDebut" require>
    </div>
    <div>
        <label for="dateFin">Date et heure de fin :</label>
        <input type="datetime-local" name="dateFin" require>
    </div>
    <input type="submit" value="Rechercher une disponiblité" />


</form>