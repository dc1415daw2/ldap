<?php
    if(isset($_GET['logout'])) {
        session_destroy();
	header('Location: index.php');
    }
?>
<html>
	<head>
		<title>P&agrave;gina d'error en l'identificaci&oacute; o modificació de l'usuari del qual es volen mostrar o modificar les deades</title>
	</head>
	<body>
		Identificaci&oacute; de l'usuari del qual es volen mostrar les deades err&ograve;nia<br>
                <a href="error_usuari.php?logout">Retorn a l'inici de l'apliaci&oacute;</a>		
	</body>
</html>