<?php
include("vues/v_sommaireComptable.php");
$action = $_REQUEST['action'];
$idVisiteur = $_SESSION['idVisiteur'];
if(isset($_GET['mois']) &&isset($_GET['idVisiteur'])){
	$leMois = $_GET['mois']; 
	$leVisiteur = $_GET['idVisiteur'];
	$visiteurSelect = $pdo->getLeVisiteurById($leVisiteur);
	$lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($leVisiteur,$leMois);
	$lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($leVisiteur,$leMois);
	$lesFraisForfait= $pdo->getLesFraisForfait($leVisiteur,$leMois);
}
$lesMois=$pdo->getLesMoisFicheFrais();
$lesVisiteursCL=$pdo->selectVisiteurCL();
$rechercheOK = false;
switch($action){
	case 'selectionnerVisiteur':{	
		break;
	}
	case 'rechercherFicheFrais':{
		
		$rechercheOK = true;
		break;
	}
	case 'modifierFraisForfait':{
		$lesFrais = $_REQUEST['lesFrais'];
		if(lesQteFraisValides($lesFrais)){
			$pdo->majFraisForfait($leVisiteur,$leMois,$lesFrais);	
			header('Location: index.php?uc=validerFrais&action=rechercherFicheFrais&idVisiteur='.$leVisiteur.'&mois='.$leMois);
	 	} else{
		 ajouterErreur("Les valeurs des frais doivent être numériques");
		 include("vues/v_erreurs.php");
	 	}
		break;
	}
    case 'validerFrais':{
		//Update Etat
		$etat = "VA";
		//Update NbJustificatifs
		$justificatifs = $_REQUEST['justificatifs'];
		//Update montant
		$total = $_REQUEST['total'];
		$modifEtat = $pdo->majValiderFicheFrais($leVisiteur,$leMois,$etat, $justificatifs, $total);
		header('Location: index.php?uc=rembourserFrais&action=afficherFiche&idVisiteur='.
		$leVisiteur.'&mois='.$leMois);
		break;
    }
	case 'supprimerFrais':{
		$rechercheOK = true;
		$idFrais = $_GET['idFrais'];
		$libelleSupprimer = $_GET['libelle'];
	    $pdo->majSuppressionFraisHF($idFrais, $libelleSupprimer);
		header('Location: '. $_SERVER['HTTP_REFERER']);		
		break;
	}
}

	include("vues/v_listeChoixCL.php");
	include("vues/v_validerFrais.php");
?>