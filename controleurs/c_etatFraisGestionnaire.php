<?php
/**Controleur pour suivre l'Etat des frais pour ce mois d'un gestionnaire'
 * Un gestionnaire gère les actions de choisir sa fiche du mois (amène sur l'action selectionnerMois)
 * Peut voir ses fiches de frais et générer les PDF de sa fiche
 */
include("vues/v_sommaireGestionnaire.php");
$action = $_REQUEST['action'];
$idVisiteur = $_SESSION['idVisiteur'];
require ("include/fpdf184/fpdf.php");
require ("include/fpdf184/pdf.php");

switch($action){
	//Affiche le form avec le select avec tous les mois ont qui au minimum  wune fiche de frais
	case 'selectionnerMois':{
		$lesMois=$pdo->getLesMoisDisponibles($idVisiteur);
		$lesCles = array_keys( $lesMois );
		$moisASelectionner = $lesCles[0];
		include("vues/v_listeMoisGestionnaire.php");
		break;
	}
	//Après avoir sélectionné un mois j'affiche les informations qui correspondent
	case 'voirEtatFrais':{
		//$_REQUEST permet de récupérer les données du form
		$leMois = $_REQUEST['lstMois']; 
		$lesMois=$pdo->getLesMoisDisponibles($idVisiteur);
		$moisASelectionner = $leMois;
		include("vues/v_listeMoisGestionnaire.php");
		/**On va faire une requête en BDD pour récupérer les informations sur les frais forfaits et hors forfait ainsi que les informations de la fiche
		* getLesFraisHorsForfait --> récupère les frais hors-forfait pour un visiteur et une fiche donnée
		* getLesFraisForfait --> récupère les fraisforfait pour un visiteur et une fiche donnée
		* getLesInfosFicheFrais --> récupère les infos de la fiche de frais pour un visiteur et une fiche donnée
		*/
		$lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur,$leMois);
		$lesFraisForfait= $pdo->getLesFraisForfait($idVisiteur,$leMois);
		$lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($idVisiteur,$leMois);
		// Permet de différencier l'année et le mois
		$numAnnee =substr( $leMois,0,4);
		$numMois =substr( $leMois,4,2);
		$libEtat = $lesInfosFicheFrais['libEtat'];
		$montantValide = $lesInfosFicheFrais['montantValide'];
		$nbJustificatifs = $lesInfosFicheFrais['nbJustificatifs'];
		$dateModif =  $lesInfosFicheFrais['dateModif'];
		// Fonction pour passer la date venant de la BDD en anglais et l'afficher en FR
		$dateModif =  dateAnglaisVersFrancais($dateModif);
		include("vues/v_etatFraisGestionnaire.php");
		break;
	}
	/**Utilisation de FPDF pour générer les PDF en brut
	 * La classe est dans include/fpdf184/
	 * on récupère le mois passé en paramètre pour savoir qu'elle est la fiche et effectué les requêtes en BDD
	 * getLeVisiteurById -> récupère les infos sur le visiteur selon l'ID 
	 * On a codé des fonctions pour afficher le PDF : exemple doc/ficheFrais_202203_a17_EXEMPLE
	 * Possible uniquement si fiche etat = RB (remboursée) ou VA (validée et mise en paiement)
	*/
	case 'genererPDF':{
		$leMois = $_GET['mois']; 
		$lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur,$leMois);
		$lesFraisForfait= $pdo->getLesFraisForfait($idVisiteur,$leMois);
		$lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($idVisiteur,$leMois);
		$lesInfosVisiteurs = $pdo->getLeVisiteurById($idVisiteur);
		ob_get_clean();
		// Création du PDF avec le constructeur de base
		$pdf = new PDF();
		// Ajout d'une page
		$pdf->AddPage();
		// On définit la font
		$pdf->SetFont('Arial','',14);
		//On passe le headerForfait du tableau en parm
		$headerForfait = array('Frais Forfaitaires', 'Quantités', 'Montant unitaire', 'Total');
		// Affichage des infos du visiteur
		$pdf->Visiteur($lesInfosVisiteurs);
		// Affichage et génération du tableau des infos des frais forfaitaires
		$pdf->TableForfait($headerForfait,$lesFraisForfait);
		// Saut de ligne
		$pdf->Ln();
		$headerHorsForfait = array('Date', 'Libellée', 'Montant');
		// Affichage et génération du tableau des infos des frais hors forfaits
		$pdf->TableHorsForfait($headerHorsForfait,$lesFraisHorsForfait);
		// Affichage du total
		$pdf->Total($lesFraisForfait, $lesFraisHorsForfait);
		// Affichage de la signature et de la date de modif
		$pdf->Signature($lesInfosFicheFrais);
		// Output avec ses options permet de dire qu'on veut le faire télécharge 'D' et qu'il portera le nom ficheFrais+Mois+nom du visteur.pdf
		$pdf->Output('D','ficheFrais_'.$leMois.'_'.$lesInfosVisiteurs["nom"].'.pdf', true);
		break;
	}
}
?>