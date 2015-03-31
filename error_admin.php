<?php
    if(isset($_GET['logout'])) {
	session_destroy();
	header('Location: index.php');
    }
?>
<html>
	<head>
		<title>P&agrave;gina d'error en l'autenticaci&oacute; de l'administrador del domini fjeclot.net;</title>
	</head>
	<body>
		Autenticaci&oacute; com administrador del domini err&ograve;nia<br>
		<a href="error_admin.php?logout">Retorn a l'inici de l'apliaci&oacute;</a>		
	</body>
</html>
