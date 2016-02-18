
<?php
if(isset($_GET['email']) && isset($_GET['password'])) {

	$bdd = new PDO('mysql:host=localhost;dbname=citizen', 'root', 'root', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
	$requete = $bdd->prepare('SELECT * FROM users WHERE email = ? AND password = ?');
	$requete->execute(array($_GET['email'], $_GET['password']));
	$donnees = $requete->fetch();

	if ($donnees){
		echo TRUE;
	} else {
		echo FALSE;
	}

	//while($donnees = $requete->fetch()){
	//	echo '<p>' . $donnees['email']. '-' . $donnees['pawword'] . '</p>';
	//}


}

?>
