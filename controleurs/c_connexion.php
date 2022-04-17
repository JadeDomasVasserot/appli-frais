<?php
if(!isset($_REQUEST['action'])){
	$_REQUEST['action'] = 'demandeConnexion';
}
$action = $_REQUEST['action'];
switch($action){
	case 'demandeConnexion':{
		include("vues/v_connexion.php");
		break;
	}
	case 'valideConnexion':{
		$login = $_REQUEST['login'];
		$mdp = $_REQUEST['mdp'];
		//augmenter taille en bdd du mdp
		$mdpCrypté = md5($mdp);
		$visiteur = $pdo->getInfosVisiteur($login,$mdpCrypté);
		if(!is_array( $visiteur)){
			ajouterErreur("Login ou mot de passe incorrect");
			include("vues/v_erreurs.php");
			include("vues/v_connexion.php");
		}
		else{
			$id = $visiteur['id'];
			$nom =  $visiteur['nom'];
			$prenom = $visiteur['prenom'];
			$statut = $visiteur['statut'];
			connecter($id,$nom,$prenom);
			if($statut =='V'){
				include("vues/v_sommaireVisiteur.php");
			}
			else if ($statut == 'C'){
				$pdo->changementAutomatiseCL();
				include("vues/v_sommaireComptable.php");
			}
			else if ($statut == 'G'){
				include("vues/v_sommaireGestionnaire.php");
			}
		}
		break;
	}
	default :{
		include("vues/v_connexion.php");
		break;
	}
}
?>