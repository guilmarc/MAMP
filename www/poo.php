<?php

include_once('Membre.class.php');

$robert = new Membre('Robert');
$robert->setPseudo('Autre Robert');

echo $robert->getPseudo();

?>