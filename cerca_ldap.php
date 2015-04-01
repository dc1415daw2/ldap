<?php
include("config.php");
session_start(); 
if (isset($_POST['usuari']))
{
    $ldapuser = trim($_POST['usuari']);
    $ldapou = trim($_POST['ou']);
    $base_dn = "ou=".$ldapou.",dc=".$DOMINI.",dc=".$TLD;
    $filtre = "(uid=$ldapuser)";
    // Connexió amb el servidor openLDAP	
    $ldapconn = ldap_connect($ldaphost) or die("Could not connect to LDAP server.");
    ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
    if ($ldapconn) {
	// Autenticant-se de nou com administrador al servidor openLDAP i accedint a les dades d'un usuari
        $ldapbind = ldap_bind($ldapconn, $_SESSION['ldaprdn'], $_SESSION['ldappass']);
	if ($ldapbind) {
            $search = ldap_search($ldapconn, $base_dn, $filtre);
            if ($search!=""){
                $info = ldap_get_entries($ldapconn, $search);
                //Ara, visualitzarem les dades de l'usuari:
                echo "<b><u>Dades de l'usuari dn: uid=".$ldapuser.",ou=".$ldapou.",dc=".$DOMINI.",dc=".$TLD.":</u></b><br>";
                for ($i=0; $i<$info["count"]; $i++)
                {
                    echo "<b>Nom:</b> ".$info[$i]["cn"][0]. "<br />";
                    echo "<b>Títol:</b> ".$info[$i]["title"][0]. "<br />";
                    echo "<b>Telèfon fixe:</b> ".$info[$i]["telephonenumber"][0]. "<br />";
                    echo "<b>Adreça postal:</b> ".$info[$i]["postaladdress"][0]. "<br />";
                    echo "<b>Telèfon mòbil:</b> ".$info[$i]["mobile"][0]. "<br />";
                    echo "<b>Descripció:</b> ".$info[$i]["description"][0]. "<br />";
                    echo "<b>Identificador de l'usuari:</b> ".$info[$i]["uid"][0]. "<br />";
                    echo "<b>Número identificador d'usuari:</b> ".$info[$i]["uidnumber"][0]. "<br />";
                    echo "<b>Grup de l'usuari per defecte:</b> ".$info[$i]["gidnumber"][0]. "<br />";
                    echo "<b>Directori personal:</b> ".$info[$i]["homedirectory"][0]. "<br />";
                    echo "<b>Shell de l'usuari:</b> ".$info[$i]["loginshell"][0]. "<br />";
                    echo "<br />";
                }
            }
            else{
                header('Location: error_usuari.php');	
            }	 
	} 
	else {
            header('Location: error_admin.php');
	}
    }
    ldap_close($ldapconn);
}
// Log OUT
if(isset($_GET['logout'])) {
    session_unset();
    if (session_destroy()){
        header('Location: index.php');
    }
}
?>

<html>
    <head>
        <title> Cerca de les dades d'un usuari</title>
    </head>
    <body>
        <form action=cerca_ldap.php method=post>		
            <b><u>Indica l'usuari del domini sobre el qual vols trobar informaci&oacute;:</u></b><br>			
            <table cellspacing=3 cellpadding=3>
		<tr>
                    <td>Nom de l'usuari: </td>
                    <td><input type=text name=usuari size=16 maxlength=15></td>
		</tr>
		<tr>
                    <td>Unitat organitzativa: </td>
                    <td><input type=text name=ou size=16 maxlength=15></td>
		</tr>
		<tr>
                    <td colspan=2><input type=submit value=Envia></td>
		</tr>
            </table>
	</form>
	<a href="cerca_ldap.php?logout">Retorno a l'inici de l'aplicaci&oacute;</a><br><br>
    </body>	
</html>
