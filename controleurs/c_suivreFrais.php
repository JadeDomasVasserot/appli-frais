<?php
include("vues/v_comptable.php");
$action = $_REQUEST['action'];
$idVisiteur = $_SESSION['idVisiteur'];
switch($action){
	case 'selectionnerFiche':{
		$lesMois=$pdo->getLesMoisFicheFrais($idVisiteur);
		$lesVisiteurs=$pdo->getVisiteurs();
		// Afin de sélectionner par défaut le dernier mois dans la zone de liste
		// on demande toutes les clés, et on prend la première,
		// les mois étant triés décroissants
		$lesCles = array_keys( $lesMois);
		$lesClesVisiteur = array_keys( $lesVisiteurs);
		$moisASelectionner = $lesCles[0];
		$visiteurASelectionner = $lesClesVisiteur[0];
		include("vues/v_suivreFrais.php");
		break;
	}
	case 'afficherFicheFrais':{
		$leMois = $_REQUEST['lstMois']; 
		$leVisiteur = $_REQUEST['lstVisiteur'];
		$lesMois=$pdo->getLesMoisFicheFrais();
		$lesVisiteurs=$pdo->getVisiteurs();
		$moisASelectionner = $leMois;
		$visiteurASelectionner = $leVisiteur;
		include("vues/v_suivreFrais.php");
		$rechercheVisiteurId = $pdo->getLeVisiteur($leVisiteur);
		$visiteurId= $rechercheVisiteurId["id"];
		$lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($visiteurId,$leMois);
		$lesFraisForfait= $pdo->getLesFraisForfait($visiteurId,$leMois);
		$lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($visiteurId,$leMois);
		if($lesInfosFicheFrais != false){
		$numAnnee =substr( $leMois,0,4);
		$numMois =substr( $leMois,4,2);
		$libEtat = $lesInfosFicheFrais['libEtat'];
		$montantValide = $lesInfosFicheFrais['montantValide'];
		$nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
		$dateModif =  $lesInfosFicheFrais['dateModif'];
		$dateModif =  dateAnglaisVersFrancais($dateModif);
		}
		else{
		$libEtat = 'Pas de fiche de frais pour ce visiteur ce mois';
		$montantValide = 0;
		$nbJustificatifs = 0;
		$dateModif =  '';
		}
		include("vues/v_afficherFicheFrais.php");

	}
	
}
?>