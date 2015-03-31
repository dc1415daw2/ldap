<html>
    <head>
        <title>Modificaci&oacute; d'un usuari</title>
    </head>
    <body>
        <form action=gest_mod method=post>
            <b><u>Canvi del valor de les dades de l'usuari:</u></b><br>
            <table cellspacing=3 cellpadding=3>
                <tr>
                    <td>Indica el nou valor:</td>
                    <td><input type=text name=novadada></td>
                </tr>
            </table>
        </form>
        <a href="gest_mod.php?logout">Retorno a l'inici de l'aplicaci&oacute;</a><br><br>
    </body>
</html>

<?php
session_start();
$con=$_SESSION['$ldapcon'];
$dn_usuari=$_SESSION['dn'];
if (isset($_SESSION['dada'])) {
    $dada_form=trim($_SESSION['dada']);
    if (($dada_form=="uidnumber") || ($dada_form=="gidnumber")) {
        $int_novadada=intval($novadada);
        $entrada[$dada_form]=$int_novadada;
        if (ldap_modify($con,$dn_usuari,$entrada)==FALSE){
            header('Location: error_usuari.php');
        }
    }
    else {
        $entrada[$dada_form]=$int_novadada;
        if (ldap_modify($con,$dn_usuari,$entrada)==FALSE){
            header('Location: error_usuari.php');
        }
    }
}
// Log OUT
if(isset($_GET['logout'])) {
    session_destroy();
    header('Location: index.php');
}
?>