
<?php
/**Controleur pour gérer la connexion des utilisateurs.
 * Distinguer si c'est un gestionnaire, un comptable ou bien un visiteur --> Affiche le menu correspondant
 * Si comptable : Clôturation des fiches du mois précédents
 * Ajout du cryptage en BDD avec la méthode MD5 --> connexion avec login et mdp crypté
 */
if(!isset($_REQUEST['action'])){
	$_REQUEST['action'] = 'demandeConnexion';
}
$action = $_REQUEST['action'];

switch($action){
	/**Affiche la page de connexion
 	*/
	case 'demandeConnexion':{
		include("vues/v_connexion.php");
		break;
	}
	/**Connecte ou affiche une erreur
 	*/
	case 'valideConnexion':{
		$login = $_REQUEST['login'];
		$mdp = $_REQUEST['mdp'];
		//augmenter taille en bdd du mdp + cryptage en md5
		$mdpCrypté = md5($mdp);
		/**Méthode de connexion
 		*/
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
			// Gère le statut de la personne connectée et affiche menu correspondant
			if($statut =='V'){
				include("vues/v_sommaireVisiteur.php");
			}
			else if ($statut == 'C'){
				// Automatisation des fiches de frais passage de CR à CL de toutes les fiches des mois précédent celui en cours
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