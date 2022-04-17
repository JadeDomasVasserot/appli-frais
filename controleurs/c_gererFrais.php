<?php
/**Controleur pour gérer l'Etat des frais d'un visiteur'
 * Un visiteur gère les actions de Saisir ses frais sur la fiche du mois en cours
 * Il peut modifier les quantités des frais forfaits, ajouter/supprimer des frais hors forfaits
 */
include("vues/v_sommaireVisiteur.php");
$idVisiteur = $_SESSION['idVisiteur'];
$mois = getMois(date("d/m/Y"));
$numAnnee =substr( $mois,0,4);
$numMois =substr( $mois,4,2);
$action = $_REQUEST['action'];

switch($action){
	//permet de créer un nouvelle fiche de frais si c'est la première ce mois ci, on a donc rajouté la clôturation automatisée
	case 'saisirFrais':{
		if($pdo->estPremierFraisMois($idVisiteur,$mois)){
			$pdo->changementAutomatiseCLVisiteur($idVisiteur);
			$pdo->creeNouvellesLignesFrais($idVisiteur,$mois);
		}
		break;
	}
	//Modifier les quantités en BDD
	case 'validerMajFraisForfait':{
		$lesFrais = $_REQUEST['lesFrais'];
		if(lesQteFraisValides($lesFrais)){
	  	 	$pdo->majFraisForfait($idVisiteur,$mois,$lesFrais);
		}
		//si pas des valeurs numériques
		else{
			ajouterErreur("Les valeurs des frais doivent être numériques");
			include("vues/v_erreurs.php");
		}
	  break;
	}
	//Création d'un frais hors forfait
	case 'validerCreationFrais':{
		$dateFrais = $_REQUEST['dateFrais'];
		$libelle = $_REQUEST['libelle'];
		$montant = $_REQUEST['montant'];
		valideInfosFrais($dateFrais,$libelle,$montant);
		if (nbErreurs() != 0 ){
			include("vues/v_erreurs.php");
		}
		else{
			$pdo->creeNouveauFraisHorsForfait($idVisiteur,$mois,$libelle,$dateFrais,$montant);
		}
		break;
	}
	//Suppression d'un frais hors forfait sélectionné
	case 'supprimerFrais':{
		$idFrais = $_REQUEST['idFrais'];
	    $pdo->supprimerFraisHorsForfait($idFrais);
		break;
	}
}
$lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur,$mois);
$lesFraisForfait= $pdo->getLesFraisForfait($idVisiteur,$mois);
include("vues/v_listeFraisForfaitVisiteur.php");
include("vues/v_listeFraisHorsForfaitVisiteur.php");
?>