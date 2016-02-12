
<?php
setcookie('nom', 'Marco', time() + 3600*24*365);
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8"/>
		<title>Mon super titre</title> 
	</head>

	</body>

		<p><?php echo 'Bonjour M.' . $_COOKIE['nom']; ?> </p> 

	</body>
</html>