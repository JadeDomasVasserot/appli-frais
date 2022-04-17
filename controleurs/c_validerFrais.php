<?php
/**Controleur qui affiche les fiches de frais Cloturées en Etat CL
 * Un comptable chosit la fiche qu'il souhaite mettre en validation/mise en paiement grâce au lien Modifier/Valider 
 * Il peut voir la liste des fiches CL dans le tableau
 */
include("vues/v_sommaireComptable.php");
$action = $_REQUEST['action'];
$idVisiteur = $_SESSION['idVisiteur'];
// récupère les variables passées en paramètre et lance les select en BDD
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
//affiche les détails de la fiche sélectionnée si le comptable clique sur Modifier/Valider
$rechercheOK = false;

switch($action){
	//Liste les fiches CL
	case 'selectionnerVisiteur':{	
		break;
	}
	//Affiche les détails de la fiche sélectionnée sous le tableau
	case 'rechercherFicheFrais':{
		$rechercheOK = true;
		break;
	}
	// lien permettant de modfier les quantités si elles ne sont pas valides
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
	// lien permettant de valider la fiche et de changer son état de CL à VA (cloturée à validée)
    // Permet aussi d'enregistrer en BDD le nombre de justificatifs (si changer) ainsi que le montant validé
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
	//Suppression du frais hors forfait sélectionné
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