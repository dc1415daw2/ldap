<html>
    <head>
        <title>Modificaci&oacute; d'un usuari</title>
    </head>
    <body>
        <form action=modificacio_ldap.php method=post>
            <b><u>Identifica l'usuari les dades del qual vol modificar:</u></b><br>
            <table cellspacing=3 cellpadding=3>
                <tr>
                    <td>Identificador (login) de l'usuari:</td>
                    <td><input type=text name=idusr size=16 maxlength=15></td>
                </tr>
                <tr>
                    <td>Unitat organitzativa de l'usuari:</td>
                   <td><input type=text name=unitorg size=16 maxlength=15></td>
                </tr>
            </table>
            <tr>
            <b><u>Indica les dades de l'usuari indicar que vols modificar:</u></b><br>			
            <table cellspacing=3 cellpadding=3>
                <tr>
                    <td><input type=radio name=dada value='givenname'>Nom de l'usuari</td>
		</tr>
                <tr>
                    <td><input type=radio name=dada value='surname'>Cognom de l'usuari</td>
		</tr>
                <tr>
                    <td><input type=radio name=dada value='title'>C&agrave;rrec o t&iacute;tol de l'usuari</td>
                </tr>		
                <tr>
                    <td><input type=radio name=dada value='title'>C&agrave;rrec o t&iacute;tol de l'usuari</td>
                </tr>                                
                <tr>
                    <td><input type=radio name=dada value='telephonenumber'>Tel&egrave;fon fixe de l'usuari</td>
                </tr>
                <tr>
                    <td><input type=radio name=dada value='mobile'>Tel&egrave;fon m&ograve;bil de l'usuari</td>
                </tr>
                <tr>
                    <td><input type=radio name=dada value='postaladress'>Adre&ccedil;a de l'usuari</td>
                </tr>
                <tr>
                    <td><input type=radio name=dada value='descripction'>Descripci&oacute; de l'usuari</td>
                </tr>
                <tr>
                    <td><input type=radio name=dada value='uidnumber'>N&uacute;mero identificador de l'usuari</td>
                </tr>
                <tr>
                    <td><input type=radio name=dada value='gidnumber'>N&uacute;mero del grup per defecte de l'usuari</td>
                </tr>
                <tr>
                    <td><input type=radio name=dada value='homedirectory'>Directori personal de l'usuari</td>
                </tr>
                <tr>
                    <td><input type=radio name=dada value='loginshell'>Shell per defecte de l'usuari</td>
                </tr>
                <tr>
                    <td><input type=radio name=dada value='userpassword'>Contrasenya de l'usuari</td>
                </tr>    
                <tr>
                    <td colspan=2><input type=submit value='Envia'></td>
                </tr>				
            </table>
	</form>
	<a href="modificacio_ldap.php?logout">Retorno a l'inici de l'aplicaci&oacute;</a><br><br>
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
            header('Location: gest_mod.php');
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