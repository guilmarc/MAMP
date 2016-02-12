
<?php
$bdd = new PDO('mysql:host=localhost;dbname=test', 'root', 'root', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
$reponse = $bdd->query('SELECT UPPER(nom) as nom_majuscule, console, prix FROM jeux_video WHERE console="NES" || console="PC" ORDER BY prix DESC');
while($donnees = $reponse->fetch()){
	echo '<p>' . $donnees['nom_majuscule']. '-' . $donnees['console'] . '-' . $donnees['prix'] . '</p>';
}
?>
