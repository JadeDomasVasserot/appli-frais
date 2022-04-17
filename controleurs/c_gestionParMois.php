<?php
include("vues/v_sommaireGestionnaire.php");
$action = $_REQUEST['action'];
$idVisiteur = $_SESSION['idVisiteur'];
$lesMois=$pdo->getLesMoisFicheFrais();
$ficheSelect = false;
switch($action){
	case 'selectionnerMois':{
		// Afin de sélectionner par défaut le dernier mois dans la zone de liste
		// on demande toutes les clés, et on prend la première,
		// les mois étant triés décroissants
		$lesCles = array_keys( $lesMois );
		$moisASelectionner = $lesCles[0];
		break;
	}
	case 'voirFicheMois':{
		$ficheSelect = true;
		$leMois = $_REQUEST['lstMois']; 
		$lesInfosFicheFrais = $pdo->selectFichesByMois($leMois);
		$numAnnee =substr( $leMois,0,4);
		$numMois =substr( $leMois,4,2);
		break;
	}
}
include("vues/v_listeMoisGestionnaire.php");
include("vues/v_afficherFicheMois.php");
?>