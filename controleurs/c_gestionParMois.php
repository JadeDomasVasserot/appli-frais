<?php
/**Controleur pour affiche les fiches selon le mois choisit
 * Un gestionnaire chosit le mois 
 * Il peut voir la liste des fiches dans le tableau
 */
include("vues/v_sommaireGestionnaire.php");
$action = $_REQUEST['action'];
$idVisiteur = $_SESSION['idVisiteur'];
$lesMois=$pdo->getLesMoisFicheFrais();
$ficheSelect = false;

switch($action){
	case 'selectionnerMois':{
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
include("vues/v_listeFicheParMois.php");
include("vues/v_afficherFicheMois.php");
?>