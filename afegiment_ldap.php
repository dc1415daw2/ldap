<html>
	<head>
		<title>Afegiment d'un nou usuari</title>
	</head>
	<body>
		<form action=afegiment_ldap.php method=post>		
			<b>Indica les dades del nou usuari que vols afegir al domini:</b><br><br>			
			<table cellspacing=3 cellpadding=3>
		   		<tr>
			  		<td>Nom de l'usuari: </td>
			  		<td><input type=text name=nom size=16 maxlength=15></td>
		   		</tr>
				<tr>
			  		<td>Cognom de l'usuari: </td>
			  		<td><input type=text name=cognom size=16 maxlength=15></td>
		   		</tr>
				<tr>
			  		<td>C&agrave;rrec o t&iacute;tol de l'usuari: </td>
			  		<td><input type=text name=titol size=16 maxlength=15></td>
		   		</tr>
				<tr>
			  		<td>Tel&egrave;fon fixe de l'usuari: </td>
			  		<td><input type=text name=telefon size=16 maxlength=15></td>
		   		</tr>
		   		<tr>
			  		<td>Tel&egrave;fon m&ograve;bil de l'usuari: </td>
			  		<td><input type=text name=mobil size=16 maxlength=15></td>
		   		</tr>
				<tr>
			  		<td>Adre&ccedil;a de l'usuari: </td>
			  		<td><input type=text name=adressa size=16 maxlength=15></td>
		   		</tr>
				<tr>
			  		<td>Descripci&oacute; de l'usuari: </td>
			  		<td><input type=text name=descripcio size=16 maxlength=15></td>
		   		</tr>
		   		<tr>
			  		<td>Identificador (login) de l'usuari: </td>
			  		<td><input type=text name=idusr size=16 maxlength=15></td>
		   		</tr>
				<tr>
			  		<td>Unitat organitzativa de l'usuari: </td>
			  		<td><input type=text name=unitorg size=16 maxlength=15></td>
		   		</tr>
				<tr>
			  		<td>N&uacute;mero identificador de l'usuari: </td>
			  		<td><input type=number name=num_uid></td>
		   		</tr>
				<tr>
			  		<td>N&uacute;mero del grup per defecte de l'usuari: </td>
			  		<td><input type=number name=num_gid></td>
		   		</tr>
				<tr>
			  		<td>Directori personal de l'usuari: </td>
			  		<td><input type=text name=directori size=16 maxlength=15></td>
		   		</tr>
				<tr>
			  		<td>Shell per defecte de l'usuari: </td>
			  		<td><input type=text name=shell size=16 maxlength=15></td>
		   		</tr>
                                <tr>
			  		<td>Contrasenya de l'usuari: </td>
                                        <td><input type=password name=ctrsnya size=16 maxlength=15></td>
		   		</tr>    
		   		<tr>
			  		<td colspan=2><input type=submit value=Envia></td>
		   		</tr>
			</table>
		</form>
		<a href="afegiment_ldap.php?logout">Retorno a l'inici de l'aplicaci&oacute;</a><br><br>
	</body>	
</html>

<?php
session_start(); 
$TLD="net";
$DOMAIN="fjeclot";
$NOMSRV="srvapl";
$SERVER="$NOMSRV.$DOMAIN.$TLD";
// Connexió amb el servidor openLDAP
$ldaphost = "ldap://$SERVER";
$ldaplogin=trim($_SESSION['login']);
$ldaprdn  = 'cn='.$ldaplogin.',dc='.$DOMINI.',dc='.$TLD;
$ldappass = trim($_SESSION['password']);
$ldapconn = ldap_connect($ldaphost) or die("No s'ha pogut establir una connexi&oacute; al servidor LDAP");
ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
if ($ldapconn) {
	// Autenticació al servidor openLDAP
	$ldapbind = ldap_bind($ldapconn, $ldaprdn, $ldappass);
	// Accedint a les dades
	if ($ldapbind) {
		$dades_usuari["objectclass"] = "top";
		$dades_usuari["objectclass"] = "person";
		$dades_usuari["objectclass"] = "organizationalperson";
		$dades_usuari["objectclass"] = "inetorgperson";
		$dades_usuari["objectclass"] = "posixaccount";
		$dades_usuari["objectclass"] = "shadowaccount";
		$dades_usuari["cn"] = trim($_POST['nom']).trim($_POST['cognom']);	
		$dades_usuari["sn"] = trim($_POST['cognom']);
		$dades_usuari["givenname"] = trim($_POST['nom']);
		$dades_usuari["title"] = trim($_POST['titol']);
                $dades_usuari["telephonenumber"] =  trim($_POST['telefon']);
                $dades_usuari["mobile"] = trim($_POST['mobil']);
                $dades_usuari["postaladdress"] = trim($_POST['addressa']);
                $dades_usuari["description"] = trim($_POST['descripcio']);
                $dades_usuari["uid"] = trim($_POST['idusr']);
                $dades_usuari["ou"] = trim($_POST['unitorg']);
                $dades_usuari["uidnumber"] = trim($_POST['num_uid']);
                $dades_usuari["gidnumber"] = trim($_POST['num_gid']);
                $dades_usuari["homedirectory"] = trim($_POST['directori']);
                $dades_usuari["loginshell"] = trim($_POST['shell']);
                $ctrsnya_codif = "{SHA}" . base64_encode( pack( "H*", sha1( $ctrsnya ) ) );
                $dades_usuari["userpassword"] = $ctrsnya_codif; 
                $dn='uid='.$dades_usuari["uid"].',ou='.$dades_usuari["ou"].',dc='.$DOMAIN.',dc='.$TLD;
                $entrada_afegida=ldap_add($ldapconn,$dn,$dades_usuari);                      
	}
        else {
            header('Location: error_admin.php');
        }
}
ldap_close($ldapconn);
// Log OUT
if(isset($_GET['logout']))	{
    session_destroy();
    header('Location: index.php');
}
?>
