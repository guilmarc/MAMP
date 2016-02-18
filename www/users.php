
<?php
$bdd = new PDO('mysql:host=localhost;dbname=citizen', 'root', 'root', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
$reponse = $bdd->query('SELECT * FROM users');
$rows = array();
while($donnees = $reponse->fetch()){
	$rows[] = $donnees;
}
print json_encode($rows);
?>