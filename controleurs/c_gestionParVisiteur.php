<?php
include("vues/v_sommaireGestionnaire.php");
$action = $_REQUEST['action'];
$idVisiteur = $_SESSION['idVisiteur'];
$lesVisiteurs=$pdo->getVisiteurs();
$ficheSelect = false;
switch($action){
	case 'selectionnerVisiteur':{
		$lesCles = array_keys( $lesVisiteurs );
		$visiteurASelectionner = $lesCles[0];
		break;
	}
	case 'voirFicheVisiteur':{
		$ficheSelect = true;
		$leVisiteur = $_REQUEST['lstVisiteur']; 
		$string = explode("-" ,$leVisiteur );
		$visiteurIdSelect = $string[0];
		$lesInfosFicheFrais = $pdo->selectFichesByVisiteur($visiteurIdSelect);
		break;
	}
}

include("vues/v_listeVisiteurGestionnaire.php");
include("vues/v_afficherFicheVisiteur.php");
?>