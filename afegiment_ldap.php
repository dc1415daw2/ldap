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
                    <td><input type=text name=nom size=21 maxlength=20></td>
		</tr>
                <tr>
                    <td>Cognom de l'usuari: </td>
                    <td><input type=text name=cognom size=21 maxlength=20></td>
                </tr>
                <tr>
                    <td>C&agrave;rrec o t&iacute;tol de l'usuari: </td>
                    <td><input type=text name=titol size=21 maxlength=20></td>
                </tr>
                <tr>
                    <td>Tel&egrave;fon fixe de l'usuari: </td>
                    <td><input type=text name=telefon size=21 maxlength=20></td>
                </tr>
                <tr>
                    <td>Tel&egrave;fon m&ograve;bil de l'usuari: </td>
                    <td><input type=text name=mobil size=21 maxlength=20></td>
                </tr>
                <tr>
                    <td>Adre&ccedil;a de l'usuari: </td>
                    <td><input type=text name=adressa size=51 maxlength=50></td>
                </tr>
                <tr>
                    <td>Descripci&oacute; de l'usuari: </td>
                    <td><input type=text name=descripcio size=51 maxlength=50></td>
                </tr>
                <tr>
                    <td>Identificador (login) de l'usuari: </td>
                    <td><input type=text name=idusr size=21 maxlength=20></td>
                </tr>
                <tr>
                    <td>Unitat organitzativa de l'usuari: </td>
                    <td><input type=text name=unitorg size=21 maxlength=20></td>
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
                    <td><input type=text name=directori size=21 maxlength=20></td>
                </tr>
                <tr>
                    <td>Shell per defecte de l'usuari: </td>
                    <td><input type=text name=shell size=21 maxlength=20></td>
                </tr>
                <tr>
                    <td>Contrasenya de l'usuari: </td>
                    <td><input type=password name=ctrsnya size=21 maxlength=20></td>
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
include("config.php");
session_start();
//echo $_SESSION['ldaprdn']."<br>";
// Connexió amb el servidor openLDAP
$ldapconn = ldap_connect($ldaphost) or die("No s'ha pogut establir una connexi&oacute; al servidor LDAP");
ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
if ($ldapconn) {
    // Autenticació al servidor openLDAP i afegint dades d'un nou usuari
    $ldapbind = ldap_bind($ldapconn, $_SESSION['ldaprdn'], $_SESSION['ldappass']);
    if ($ldapbind) {
        $dades_usuari["objectclass"][0] = "top";
        $dades_usuari["objectclass"][1] = "person";
        $dades_usuari["objectclass"][2] = "organizationalperson";
        $dades_usuari["objectclass"][3] = "inetorgperson";
        $dades_usuari["objectclass"][4] = "posixaccount";
        $dades_usuari["objectclass"][5] = "shadowaccount";
        $dades_usuari["cn"] = $_POST['nom'].$_POST['cognom'];
        $dades_usuari["sn"] = trim($_POST['cognom']);
        $dades_usuari["givenname"] = trim($_POST['nom']);
        $dades_usuari["title"] = $_POST['titol'];
        $dades_usuari["telephonenumber"] = $_POST['telefon'];
        $dades_usuari["mobile"] = $_POST['mobil'];
        $dades_usuari["postaladdress"] = $_POST['adressa'];
        $dades_usuari["description"] = $_POST['descripcio'];
        $dades_usuari["uid"] = trim($_POST['idusr']);
        $dades_usuari["ou"] = trim($_POST['unitorg']);
        $dades_usuari["uidnumber"] = trim($_POST['num_uid']);
        $dades_usuari["gidnumber"] = trim($_POST['num_gid']);
        $dades_usuari["homedirectory"] = $_POST['directori'];
        $dades_usuari["loginshell"] = $_POST['shell'];
        $ctrsnya_codif = "{SHA}" . base64_encode(pack("H*", sha1($ctrsnya)));
        $dades_usuari["userpassword"] = $ctrsnya_codif;
        $dn = 'uid=' . $dades_usuari["uid"] . ',ou=' . $dades_usuari["ou"] . ',dc=' . $DOMINI . ',dc=' . $TLD;
        if ((dades_usuari["uid"]!="") && ($dades_usuari["ou"]!="")){
            $res_add=ldap_add($ldapconn, $dn, $dades_usuari);
            if(!$res_add){
                //echo "LDAP-Errno: " . ldap_errno($ldapconn) . "<br />\n";
                //echo "LDAP-Error: " . ldap_error($ldapconn) . "<br />\n";
                header('Location: error_usuari.php');
            }
        }
    }
    else {
        header('Location: error_admin.php');
    }
}
ldap_close($ldapconn);
// Log OUT
if(isset($_GET['logout'])) {
    session_unset();
    if (session_destroy()){
        header('Location: index.php');
    }
}
?>
