<html>
	<head>
		<title>Esborrant un nou usuari</title>
	</head>
	<body>
            <form action=esborrament_ldap.php method=post>		
			<b>Indica les dades de l'usuari que vols esborrar del domini:</b><br><br>			
			<table cellspacing=3 cellpadding=3>
		   		<tr>
			  		<td>Identificador (login) de l'usuari: </td>
			  		<td><input type=text name=idusr size=16 maxlength=15></td>
		   		</tr>
				<tr>
			  		<td>Unitat organitzativa de l'usuari: </td>
			  		<td><input type=text name=unitorg size=16 maxlength=15></td>
		   		</tr>
				<tr>
			  		<td colspan=2><input type=submit value=Envia></td>
		   		</tr>
			</table>
		</form>
		<a href="esborrament_ldap.php?logout">Retorno a l'inici de l'aplicaci&oacute;</a><br><br>
	</body>	
</html>

<?php
session_start(); 
$TLD="net";
$DOMAIN="fjeclot";
$NOMSRV="srvapl";
$SERVER="$NOMSRV.$DOMAIN.$TLD";
if (isset($_POST['idusr']) && isset($_POST['unitorg'])){
    // Connexió amb el servidor openLDAP
    $ldaphost = "ldap://$SERVER";
    $ldaplogin=trim($_SESSION['login']);
    $ldaprdn  = 'cn='.$ldaplogin.',dc='.$DOMINI.',dc='.$TLD;
    $ldappass = trim($_SESSION['password']);
    $ldapconn = ldap_connect($ldaphost) or die("No s'ha pogut establir una connexi&oacute; al servidor LDAP");
    ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
    if($ldapconn){
       // Autenticant-se de nou com administrador al servidor openLDAP i accedint a les dades d'un usuari
	$ldapbind = ldap_bind($ldapconn, $ldaprdn, $ldappass);
        if ($ldapbind) {
            $dn='uid='.$dades_usuari["uid"].',ou='.$dades_usuari["ou"].',dc='.$DOMAIN.',dc='.$TLD;
            if (ldap_delete($ldapconn,$dn)){
                echo "L'usuari $idusr de la unitat organitzativa $unitorg ha estat esborrat!<br>";
            }
            else {
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
if(isset($_GET['logout']))	{
    session_destroy();
    header('Location: index.php');
}
?>
