Programme d'actualisation des lignes des tables,  
cette mise à jour peut prendre plusieurs minutes...
<?php
/**
 * Page Script pour coder en MD5 les mots de passe de la BDD
 */
    require_once ("include/class.pdogsb.inc.php");
    $pdo = PdoGsb::getPdoGsb();
    set_time_limit(0);
    $mdp = $pdo->crypterMDP();
?>