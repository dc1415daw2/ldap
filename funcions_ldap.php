<?php
if (isset($_POST['func_ldap'])) {
    $funcio = $_POST['func_ldap'];
    if (isset($funcio)) {
        switch ($funcio) {
            case "cerca": header('Location: cerca_ldap.php');break;
            case "afegiment": header('Location: afegiment_ldap.php');break;
            case "esborrament": header('Location: esborrament_ldap.php');break;
            case "modificacio": header('Location: modificacio_ldap.php');break;
	}
    }
    else {
        echo("<p>No ha seleccionat cap funci&oacute; LDAP!</p>\n");
    }
}
// Log OUT
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
        <title>Selecci&oacute; de la funci&oacute; LDAP</title>
    </head>
    <body>
	<form action=funcions_ldap.php method="post">
            <b>Indica la funci&oacute; LDAP amb la qual vols treballar:</b><br><br>
            <select name="func_ldap" size="4">
		<option value="cerca">Cerca de dades d'un usuari del domini</option>
		<option value="afegiment">Afegiment d'un usuari al domini</option>
		<option value="esborrament">Esborrament d'un usuari del domini</option>
		<option value="modificacio">Modificaci&oacute; de les dades d'un usuari del domini</option>
            </select><br><br>
            <input type="submit" value="Envia opci&oacute; escollida" >
	</form>
	<a href="funcions_ldap.php?logout">Retorno a l'inici de l'aplicaci&oacute;</a><br><br>
    </body>
</html>
