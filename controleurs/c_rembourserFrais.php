<?php
/**Controleur qui affiche les fiches de frais Validées en Etat VA
 * Un comptable chosit la fiche qu'il souhaite mettre en remboursée grâce au lien Suivre 
 * Il peut voir la liste des fiches VA dans le tableau
 */
include("vues/v_sommaireComptable.php");
$action = $_REQUEST['action'];
$idVisiteur = $_SESSION['idVisiteur'];
$lesFiches=$pdo->selectFichesVisiteursVA();
//affiche les détails de la fiche sélectionnée si le comptable clique sur Suivre
$remboursementOK = false;
// récupère les variables passées en paramètre et lance les select en BDD
if(isset($_GET['mois']) &&isset($_GET['idVisiteur'])){
	$leMois = $_GET['mois']; 
	$leVisiteur = $_GET['idVisiteur'];
	$lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($leVisiteur,$leMois);
	$lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($leVisiteur,$leMois);
	$lesFraisForfait= $pdo->getLesFraisForfait($leVisiteur,$leMois);
}
switch($action){
	//Liste les fiches VA
	case 'listerFiche':{
		break;
	}	
	//Affiche les détails de la fiche sélectionnée sous le tableau
	case 'afficherFiche':{
		$remboursementOK = true;
		break;
	}
	// Passe la fiche de VA à RB (remboursée)
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