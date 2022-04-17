<?php
/**
 * Page générale qui permet de définit le chemins de chaque contrôleur
 * exemple de lien index.php?uc=validerFrais&action=rechercherFicheFrais
 * On démarre la session et on définie connexion comme 1ère page
 * On démarre la connxion à la BDD
 */
require_once("include/fct.inc.php");
require_once ("include/class.pdogsb.inc.php");
include("vues/v_entete.php") ;
session_start();
$pdo = PdoGsb::getPdoGsb();
$estConnecte = estConnecte();
if(!isset($_REQUEST['uc']) || !$estConnecte){
     $_REQUEST['uc'] = 'connexion';
}	 
$uc = $_REQUEST['uc'];
switch($uc){
	case 'connexion':{
		include("controleurs/c_connexion.php");break;
	}
	case 'gererFrais' :{
		include("controleurs/c_gererFrais.php");break;
	}
	case 'etatFrais' :{
		include("controleurs/c_etatFrais.php");break; 
	}
	case 'gererFraisComptable' :{
		include("controleurs/c_gererFraisComptable.php");break;
	}
	case 'etatFraisComptable' :{
		include("controleurs/c_etatFraisComptable.php");break; 
	}
	case 'rembourserFrais' :{
		include("controleurs/c_rembourserFrais.php");break;
	}
	case 'validerFrais' :{
		include("controleurs/c_validerFrais.php");break; 
	}
	case 'gererFraisGestionnaire' :{
		include("controleurs/c_gererFraisGestionnaire.php");break;
	}
	case 'etatFraisGestionnaire' :{
		include("controleurs/c_etatFraisGestionnaire.php");break; 
	}
	case 'gestionParMois' :{
		include("controleurs/c_gestionParMois.php");break; 
	}
	case 'gestionParVisiteur' :{
		include("controleurs/c_gestionParVisiteur.php");break; 
	}
}
include("vues/v_pied.php") ;
