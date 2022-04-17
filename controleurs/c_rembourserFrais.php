<?php
include("vues/v_sommaireComptable.php");
$action = $_REQUEST['action'];
$idVisiteur = $_SESSION['idVisiteur'];
$lesFiches=$pdo->selectFichesVisiteursVA();

$remboursementOK = false;
if(isset($_GET['mois']) &&isset($_GET['idVisiteur'])){
	$leMois = $_GET['mois']; 
	$leVisiteur = $_GET['idVisiteur'];
	$lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($leVisiteur,$leMois);
	$lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($leVisiteur,$leMois);
	$lesFraisForfait= $pdo->getLesFraisForfait($leVisiteur,$leMois);
}
switch($action){
	case 'listerFiche':{
		break;
	}	
	case 'afficherFiche':{
		$remboursementOK = true;
		break;
	}	
	case 'rembourserFiche':{
		$etat = "RB";
		$pdo->majEtatFicheFrais($leVisiteur,$leMois,$etat);
		header('Location: index.php?uc=rembourserFrais&action=afficherFiche&idVisiteur='.
		$leVisiteur.'&mois='.$leMois);
		$remboursementOK = false;
		break;
	}
}
include("vues/v_listeFicheVA.php");
include("vues/v_rembourserFrais.php");

?>