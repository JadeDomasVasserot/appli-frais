Programme d'actualisation des lignes des tables,  
cette mise à jour peut prendre plusieurs minutes...
<?php
require_once ("include/class.pdogsb.inc.php");
$pdo = PdoGsb::getPdoGsb();
set_time_limit(0);
$mdp = $pdo->crypterMDP();
?>