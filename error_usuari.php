<?php
    if(isset($_GET['logout'])) {
        session_start();
        session_unset();
        if (session_destroy()){
            header('Location: index.php');
        }
    }
?>
<html>
	<head>
		<title>P&agrave;gina d'error en l'identificaci&oacute;, afegiment, esborrament o modificació de l'usuari</title>
	</head>
	<body>
            El proc&eacute;s d'identificaci&oacute;, afegiment, esborrament o modificació de l'usuari amb el qual es vol treballar ha tingut un error.<br>
            Si us plau, intenteu realitzar el proc&eacute;s novament.<br>
            <a href="error_usuari.php?logout">Retorn a l'inici de l'apliaci&oacute;</a>		
	</body>
</html>