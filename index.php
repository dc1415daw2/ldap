<?php
include("config.php");
session_start(); 
if( isset($_POST['login']) && isset($_POST['password']))
{
    $ldaplogin=trim($_POST['login']);
    $_SESSION['ldaprdn']  = 'cn='.$ldaplogin.',dc='.$DOMINI.',dc='.$TLD;
    $_SESSION['ldappass'] = trim($_POST['password']);   
    // Connectant-se al servidor openLDAP
    $ldapconn = ldap_connect($ldaphost) or die("No s'ha pogut establir una connexió amb el servidor openLDAP.");
    ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
    if ($ldapconn) {
	//Autenticant-se en el servidor openLDAP i accedint a la resta de l'aplicació un cop autenticat
	$ldapbind = ldap_bind($ldapconn, $_SESSION['ldaprdn'], $_SESSION['ldappass']);
	if ($ldapbind) {
            if ($ldaplogin==$ADMIN){
		header('Location: funcions_ldap.php');
            }
            else {
		header('Location: error_admin.php');					
            }
	}
	else {
            header('Location: error_admin.php');
	}
    }
    ldap_close($ldapconn);
}
?>
<html>
    <head>
        <title>P&agrave;gina d'autenticaci&oacute; de l'administrador del domini</title>
    </head>
    <body>	
        <form action=index.php method=post>
            <b>Autenticaci&oacute; com administrador del domini fjeclot.net</b><br><br>	
            <table>
                <tr>
                    <td>Nom de l'usuari administrador: </td>
                    <td><input type=text name=login size=16 maxlength=15></td>
		</tr>
		<tr>
                    <td>Contrasenya: </td>
                    <td><input type=password name=password size=16 maxlength=15></td>
		</tr>
		<tr>
                    <td><input type=submit value=Autenticaci&oacute;></td>
                </tr>
            </table>
	</form>
    </body>
</html>
