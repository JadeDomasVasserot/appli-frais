<?php
/** 
 * Classe d'accès aux données. 
 
 * Utilise les services de la classe PDO
 * pour l'application GSB
 * Les attributs sont tous statiques,
 * les 4 premiers pour la connexion
 * $monPdo de type PDO 
 * $monPdoGsb qui contiendra l'unique instance de la classe
 
 * @package default
 * @author Cheri Bibi
 * @version    1.0
 * @link       http://www.php.net/manual/fr/book.pdo.php
 */

class PdoGsb{   		
      	private static $serveur='mysql:host=localhost';
      	private static $bdd='dbname=appli-frais';   		
      	private static $user='root' ;    		
      	private static $mdp='' ;	
		private static $monPdo;
		private static $monPdoGsb=null;
/**
 * Constructeur privé, crée l'instance de PDO qui sera sollicitée
 * pour toutes les méthodes de la classe
 */				
	private function __construct(){
    	PdoGsb::$monPdo = new PDO(PdoGsb::$serveur.';'.PdoGsb::$bdd, PdoGsb::$user, PdoGsb::$mdp); 
		PdoGsb::$monPdo->query("SET CHARACTER SET utf8");
	}
	public function _destruct(){
		PdoGsb::$monPdo = null;
	}
/**
 * Fonction statique qui crée l'unique instance de la classe
 
 * Appel : $instancePdoGsb = PdoGsb::getPdoGsb();
 
 * @return l'unique objet de la classe PdoGsb
 */
	public  static function getPdoGsb(){
		if(PdoGsb::$monPdoGsb==null){
			PdoGsb::$monPdoGsb= new PdoGsb();
		}
		return PdoGsb::$monPdoGsb;  
	}
/**
 * Retourne les informations d'un visiteur
 
 * @param $login 
 * @param $mdp
 * @return l'id, le nom et le prénom sous la forme d'un tableau associatif 
*/
	public function getInfosVisiteur($login, $mdp){
		$req = "select visiteur.id as id, visiteur.statut as statut, visiteur.nom as nom, visiteur.prenom as prenom from visiteur 
		where visiteur.login='$login' and visiteur.mdp='$mdp'";
		$rs = PdoGsb::$monPdo->query($req);
		$ligne = $rs->fetch();
		return $ligne;
	}
/**
 * Retourne sous forme d'un tableau associatif toutes les lignes de frais hors forfait
 * concernées par les deux arguments
 
 * La boucle foreach ne peut être utilisée ici car on procède
 * à une modification de la structure itérée - transformation du champ date-
 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
 * @return tous les champs des lignes de frais hors forfait sous la forme d'un tableau associatif 
*/
	public function getLesFraisHorsForfait($idVisiteur,$mois){
	    $req = "select * from lignefraishorsforfait where lignefraishorsforfait.idvisiteur ='$idVisiteur' 
		and lignefraishorsforfait.mois = '$mois' ";	
		$res = PdoGsb::$monPdo->query($req);
		$lesLignes = $res->fetchAll();
		$nbLignes = count($lesLignes);
		for ($i=0; $i<$nbLignes; $i++){
			$date = $lesLignes[$i]['date'];
			$lesLignes[$i]['date'] =  dateAnglaisVersFrancais($date);
		}
		return $lesLignes; 
	}
/**
 * Retourne le nombre de justificatif d'un visiteur pour un mois donné
 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
 * @return le nombre entier de justificatifs 
*/
	public function getNbjustificatifs($idVisiteur, $mois){
		$req = "select fichefrais.nbjustificatifs as nb from  fichefrais where fichefrais.idvisiteur ='$idVisiteur' and fichefrais.mois = '$mois'";
		$res = PdoGsb::$monPdo->query($req);
		$laLigne = $res->fetch();
		return $laLigne['nb'];
	}
/**
 * Retourne sous forme d'un tableau associatif toutes les lignes de frais au forfait
 * concernées par les deux arguments
 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
 * @return l'id, le libelle et la quantité sous la forme d'un tableau associatif 
*/
	public function getLesFraisForfait($idVisiteur, $mois){
		$req = "select fraisforfait.id as idfrais, fraisforfait.libelle as libelle, 
		lignefraisforfait.quantite as quantite, fraisforfait.montant as montant from lignefraisforfait inner join fraisforfait 
		on fraisforfait.id = lignefraisforfait.idfraisforfait
		where lignefraisforfait.idvisiteur ='$idVisiteur' and lignefraisforfait.mois='$mois' 
		order by lignefraisforfait.idfraisforfait";	
		$res = PdoGsb::$monPdo->query($req);
		$lesLignes = $res->fetchAll();
		return $lesLignes; 
	}
/**
 * Retourne tous les id de la table FraisForfait
 
 * @return un tableau associatif 
*/
	public function getLesIdFrais(){
		$req = "select fraisforfait.id as idfrais from fraisforfait order by fraisforfait.id";
		$res = PdoGsb::$monPdo->query($req);
		$lesLignes = $res->fetchAll();
		return $lesLignes;
	}
/**
 * Met à jour la table ligneFraisForfait
 
 * Met à jour la table ligneFraisForfait pour un visiteur et
 * un mois donné en enregistrant les nouveaux montants
 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
 * @param $lesFrais tableau associatif de clé idFrais et de valeur la quantité pour ce frais
 * @return un tableau associatif 
*/
	public function majFraisForfait($idVisiteur, $mois, $lesFrais){
		$lesCles = array_keys($lesFrais);
		foreach($lesCles as $unIdFrais){
			$qte = $lesFrais[$unIdFrais];
			$req = "update lignefraisforfait set lignefraisforfait.quantite = '$qte'
			where lignefraisforfait.idVisiteur = '$idVisiteur' and lignefraisforfait.mois = '$mois'
			and lignefraisforfait.idFraisForfait = '$unIdFrais'";
			PdoGsb::$monPdo->exec($req);
		}
	}
/**
 * met à jour le nombre de justificatifs de la table ficheFrais
 * pour le mois et le visiteur concerné
 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
*/
	public function majNbJustificatifs($idVisiteur, $mois, $nbJustificatifs){
		$req = "update fichefrais set nbjustificatifs = $nbJustificatifs 
		where fichefrais.idvisiteur = '$idVisiteur' and fichefrais.mois = '$mois'";
		PdoGsb::$monPdo->exec($req);	
	}
/**
 * Teste si un visiteur possède une fiche de frais pour le mois passé en argument
 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
 * @return vrai ou faux 
*/	
	public function estPremierFraisMois($idVisiteur,$mois)
	{
		$ok = false;
		$req = "select count(*) as nblignesfrais from fichefrais 
		where fichefrais.mois = '$mois' and fichefrais.idvisiteur = '$idVisiteur'";
		$res = PdoGsb::$monPdo->query($req);
		$laLigne = $res->fetch();
		if($laLigne['nblignesfrais'] == 0){
			$ok = true;
		}
		return $ok;
	}
/**
 * Retourne le dernier mois en cours d'un visiteur
 
 * @param $idVisiteur 
 * @return le mois sous la forme aaaamm
*/	
	public function dernierMoisSaisi($idVisiteur){
		$req = "select max(mois) as dernierMois from fichefrais where fichefrais.idvisiteur = '$idVisiteur'";
		$res = PdoGsb::$monPdo->query($req);
		$laLigne = $res->fetch();
		$dernierMois = $laLigne['dernierMois'];
		return $dernierMois;
	}
	
/**
 * Crée une nouvelle fiche de frais et les lignes de frais au forfait pour un visiteur et un mois donnés
 
 * récupère le dernier mois en cours de traitement, met à 'CL' son champs idEtat, crée une nouvelle fiche de frais
 * avec un idEtat à 'CR' et crée les lignes de frais forfait de quantités nulles 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
*/
	public function creeNouvellesLignesFrais($idVisiteur,$mois){
		$dernierMois = $this->dernierMoisSaisi($idVisiteur);
		$laDerniereFiche = $this->getLesInfosFicheFrais($idVisiteur,$dernierMois);
		if($laDerniereFiche['idEtat']=='CR'){
				$this->majEtatFicheFrais($idVisiteur, $dernierMois,'CL');
				
		}
		$req = "insert into fichefrais(idvisiteur,mois,nbJustificatifs,montantValide,dateModif,idEtat) 
		values('$idVisiteur','$mois',0,0,now(),'CR')";
		PdoGsb::$monPdo->exec($req);
		$lesIdFrais = $this->getLesIdFrais();
		foreach($lesIdFrais as $uneLigneIdFrais){
			$unIdFrais = $uneLigneIdFrais['idfrais'];
			$req = "insert into lignefraisforfait(idvisiteur,mois,idFraisForfait,quantite) 
			values('$idVisiteur','$mois','$unIdFrais',0)";
			PdoGsb::$monPdo->exec($req);
		 }
	}
/**
 * Crée un nouveau frais hors forfait pour un visiteur un mois donné
 * à partir des informations fournies en paramètre
 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
 * @param $libelle : le libelle du frais
 * @param $date : la date du frais au format français jj//mm/aaaa
 * @param $montant : le montant
*/
	public function creeNouveauFraisHorsForfait($idVisiteur,$mois,$libelle,$date,$montant){
		$dateFr = dateFrancaisVersAnglais($date);
		$req = "insert into lignefraishorsforfait 
		values(NULL,'$idVisiteur','$mois','$libelle','$dateFr','$montant')";
		PdoGsb::$monPdo->exec($req);
	}
/**
 * Supprime le frais hors forfait dont l'id est passé en argument
 
 * @param $idFrais 
*/
	public function supprimerFraisHorsForfait($idFrais){
		$req = "delete from lignefraishorsforfait where lignefraishorsforfait.id =$idFrais ";
		PdoGsb::$monPdo->exec($req);
	}
/**
 * Retourne les mois pour lesquel un visiteur a une fiche de frais
 
 * @param $idVisiteur 
 * @return un tableau associatif de clé un mois -aaaamm- et de valeurs l'année et le mois correspondant 
*/
	public function getLesMoisDisponibles($idVisiteur){
		$req = "select fichefrais.mois as mois from  fichefrais where fichefrais.idvisiteur ='$idVisiteur' 
		order by fichefrais.mois desc ";
		$res = PdoGsb::$monPdo->query($req);
		$lesMois =array();
		$laLigne = $res->fetch();
		while($laLigne != null)	{
			$mois = $laLigne['mois'];
			$numAnnee =substr( $mois,0,4);
			$numMois =substr( $mois,4,2);
			$lesMois["$mois"]=array(
		     "mois"=>"$mois",
		    "numAnnee"  => "$numAnnee",
			"numMois"  => "$numMois"
             );
			$laLigne = $res->fetch(); 		
		}
		return $lesMois;
	}
/**
 * Retourne les mois qui ont un minimumm une fiche de frais
 
 * @return un tableau associatif de clé un mois -aaaamm- et de valeurs l'année et le mois correspondant 
*/
public function getLesMoisFicheFrais(){
	$req = "select fichefrais.mois as mois from  fichefrais
	order by fichefrais.mois desc ";
	$res = PdoGsb::$monPdo->query($req);
	$lesMois =array();
	$laLigne = $res->fetch();
	while($laLigne != null)	{
		$mois = $laLigne['mois'];
		$numAnnee =substr( $mois,0,4);
		$numMois =substr( $mois,4,2);
		$lesMois["$mois"]=array(
		 "mois"=>"$mois",
		"numAnnee"  => "$numAnnee",
		"numMois"  => "$numMois"
		 );
		$laLigne = $res->fetch(); 		
	}
	return $lesMois;
}
/**
 * Retourne les informations d'une fiche de frais d'un visiteur pour un mois donné
 
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
 * @return un tableau avec des champs de jointure entre une fiche de frais et la ligne d'état 
*/	
	public function getLesInfosFicheFrais($idVisiteur,$mois){
		$req = "select ficheFrais.idEtat as idEtat, ficheFrais.dateModif as dateModif, ficheFrais.nbJustificatifs as nbJustificatifs, 
			ficheFrais.montantValide as montantValide, etat.libelle as libEtat from  fichefrais inner join Etat on ficheFrais.idEtat = Etat.id 
			where fichefrais.idvisiteur ='$idVisiteur' and fichefrais.mois = '$mois'";
		$res = PdoGsb::$monPdo->query($req);
		$laLigne = $res->fetch();
		$date = $laLigne['dateModif'];
		$laLigne['dateModif'] =  dateAnglaisVersFrancais($date);

		
		return $laLigne;
	}
/**
 * Modifie l'état et la date de modification d'une fiche de frais
 
 * Modifie le champ idEtat et met la date de modif à aujourd'hui
 * @param $idVisiteur 
 * @param $mois sous la forme aaaamm
 */
 
	public function majEtatFicheFrais($idVisiteur,$mois,$etat){
		$req = "update ficheFrais set idEtat = '$etat', dateModif = now() 
		where fichefrais.idvisiteur ='$idVisiteur' and fichefrais.mois = '$mois'";
		PdoGsb::$monPdo->exec($req);
	}
	/**
 * Retourne la liste des visiteurs
 

 * @return l'id, le nom et le prénom sous la forme d'un tableau associatif 
*/
	public function getVisiteurs(){
		$req = "select visiteur.id as id, visiteur.nom as nom, visiteur.prenom as prenom from  visiteur
		order by visiteur.nom asc ";
		$res = PdoGsb::$monPdo->query($req);
		$lesVisiteurs =array();
		$laLigne = $res->fetch();
		while($laLigne != null)	{
			$id = $laLigne['id'];
			$nom = $laLigne['nom'];
			$prenom = $laLigne['prenom'];
			$lesVisiteurs["$id"]=array(
			"id"=>"$id",
			"nom"  => "$nom",
			"prenom"  => "$prenom"
			);
			$laLigne = $res->fetch(); 		
		}
		return $lesVisiteurs;
		
	}
/**
* Retourne la liste des visiteurs CL


* @return l'id, le nom et le prénom et l'état de la fiche sous la forme d'un tableau associatif 
*/
	public function selectVisiteurCL(){
		$req = "select fichefrais.idVisiteur as id, fichefrais.idEtat as etat,  fichefrais.mois as mois, visiteur.nom as nom, visiteur.prenom as prenom FROM fichefrais
		INNER JOIN visiteur ON visiteur.id = fichefrais.idVisiteur
		WHERE  fichefrais.idEtat ='CL'
		ORDER BY mois DESC" ;
		$res = PdoGsb::$monPdo->query($req);
		$lignes = $res->fetchAll();
		return $lignes;
	}


/**
* Retourne le visiteur choisi par le comptable dans le module suivre 

* @return l'id, le nom  sous la forme d'un tableau associatif 
*/
		public function getLeVisiteurById($leVisiteur){
		$req = "select visiteur.id as id, visiteur.nom as nom, visiteur.prenom as prenom, fichefrais.mois as mois 
		from  visiteur
		inner join fichefrais ON visiteur.id = fichefrais.idVisiteur
		where visiteur.id = '$leVisiteur' 
		order by visiteur.nom asc ";
		$res = PdoGsb::$monPdo->query($req);
		$visiteur =array();
		$laLigne = $res->fetch();
		while($laLigne != null)	{
			$id = $laLigne['id'];
			$nom = $laLigne['nom'];
			$prenom = $laLigne['prenom'];
			$mois = $laLigne['mois'];
			$visiteur=array(
			"id"=>"$id",
			"nom"  => "$nom",
			"prenom" => "$prenom",
			"mois" => "$mois",
			);
			$laLigne = $res->fetch(); 		
		}
	return $visiteur;

	}
/**
 * Modifie le libellé de la ligne du frais hors-forfait à l'id correspondant
 
 * Modifie le champ idEtat et met la date de modif à aujourd'hui
 * @param $idFrais
 * @param $mois sous la forme aaaamm
 */
 
	public function majSuppressionFraisHF($idFrais, $libelleSupprimer){
		$req = "update lignefraishorsforfait set lignefraishorsforfait.libelle = 'REFUSE :  $libelleSupprimer', lignefraishorsforfait.montant = 0
		where lignefraishorsforfait.id = '$idFrais'";
		PdoGsb::$monPdo->exec($req);
	}
/**
 * Modifie l'état, le nb de justificatifs, le montantValid et la date de modification d'une fiche de frais
 
 * Modifie le champ idEtat et met la date de modif à aujourd'hui
 * @param $idVisiteur,$mois,$etat,  $justificatifs, $montant
 * @param $mois sous la forme aaaamm
 */
 
public function majValiderFicheFrais($idVisiteur,$mois,$etat, $justificatifs, $montant){

	$req = "update fichefrais 
	set idEtat = '$etat', 
	dateModif = now(),
	nbJustificatifs = '$justificatifs',
	montantValide = '$montant'
		where fichefrais.idVisiteur ='$idVisiteur' and fichefrais.mois = '$mois'";
		PdoGsb::$monPdo->exec($req);
	}

/**
 * Modifie l'état, le nb de justificatifs, le montantValid et la date de modification d'une fiche de frais
 
 * Modifie le champ idEtat et met la date de modif à aujourd'hui
 * @param $idVisiteur,$mois,$etat,  $justificatifs, $montant
 * @param $mois sous la forme aaaamm
 */
 
	public function majFraisF($idVisiteur,$mois, $frais){

		
	}
	/**
* Retourne la liste des visiteurs CL


* @return l'id, le nom et le prénom et l'état de la fiche sous la forme d'un tableau associatif 
*/
	public function selectFichesVisiteursVA(){
		$req = "select fichefrais.idVisiteur as id, fichefrais.idEtat as etat,  fichefrais.mois as mois,  fichefrais.dateModif as date, fichefrais.montantValide as montant, visiteur.nom as nom, visiteur.prenom as prenom FROM fichefrais
		INNER JOIN visiteur ON visiteur.id = fichefrais.idVisiteur
		WHERE  fichefrais.idEtat ='VA'
		ORDER BY mois DESC" ;
		$res = PdoGsb::$monPdo->query($req);
		$lignes = $res->fetchAll();
		return $lignes;
	}
/**
* Retourne la liste des fiches de frais au mois selectionné
*
*/
	public function selectFichesByMois($mois){
		$req = "select fichefrais.idVisiteur as id, fichefrais.idEtat as etat,  fichefrais.mois as mois, fichefrais.montantValide as montant, visiteur.nom as nom, visiteur.prenom as prenom, etat.libelle as libelleEtat FROM fichefrais
		INNER JOIN visiteur ON visiteur.id = fichefrais.idVisiteur
		INNER JOIN etat ON etat.id = fichefrais.idEtat
		WHERE  fichefrais.mois ='$mois'
		ORDER BY mois DESC" ;
		$res = PdoGsb::$monPdo->query($req);
		$lignes = $res->fetchAll();
		return $lignes;
	}
/**
* Retourne la liste des fiches de frais au visiteur selectionné
*
*/
	public function selectFichesByVisiteur($visiteurId){
		$req = "select fichefrais.idVisiteur as id, fichefrais.idEtat as etat,  fichefrais.mois as mois, fichefrais.montantValide as montant, visiteur.nom as nom, visiteur.prenom as prenom, etat.libelle as libelleEtat FROM fichefrais
		INNER JOIN visiteur ON visiteur.id = fichefrais.idVisiteur
		INNER JOIN etat ON etat.id = fichefrais.idEtat
		WHERE  fichefrais.idVisiteur ='$visiteurId'
		ORDER BY mois DESC" ;
		$res = PdoGsb::$monPdo->query($req);
		$lignes = $res->fetchAll();
		return $lignes;
	}
/**
* Change l'état de toutes les fiches de frais des mois précédant en CL
*
*/
	public function changementAutomatiseCL(){
		$date = date("Ym");
		$ficheFraisCR = "SELECT * FROM fichefrais WHERE idEtat ='CR' and mois < '$date'";
		$res = PdoGsb::$monPdo->query($ficheFraisCR);
		$lignes = $res->fetchAll();
		foreach($lignes as $ligne){
			$req = "UPDATE ficheFrais
			SET idEtat = 'CL', dateModif = now()
			where fichefrais.idVisiteur ='".$ligne['idVisiteur']."' and fichefrais.mois = '".$ligne['mois']."'";
			$res = PdoGsb::$monPdo->query($req);
			$res->fetch();
		}
	}
/**
* Change l'état de toutes les fiches de frais du mois précédant en CL lors de la nouvelle saisie du visiteur
*
*/
	public function changementAutomatiseCLVisiteur($idVisiteur){
		$date = date("Ym");
		$ficheFraisCR = "SELECT * FROM fichefrais WHERE idEtat ='CR' and mois < '$date' and idVisiteur='$idVisiteur'";
		$res = PdoGsb::$monPdo->query($ficheFraisCR);
		$lignes = $res->fetchAll();
		foreach($lignes as $ligne){
			$req = "UPDATE ficheFrais
			SET idEtat = 'CL', dateModif = now()
			where fichefrais.idVisiteur ='".$ligne['idVisiteur']."' and fichefrais.mois = '".$ligne['mois']."'";
			$res = PdoGsb::$monPdo->query($req);
			$res->fetch();
		}
	}
/**
* Crypte avec md5 chaque mdp
*
*/
	public function crypterMDP(){
		$mdp = "SELECT mdp, id FROM visiteur";
		$res = PdoGsb::$monPdo->query($mdp);
		$lignes = $res->fetchAll();
		foreach($lignes as $ligne){
			$req = "UPDATE visiteur
			SET mdp = '".md5($ligne["mdp"])."'
			WHERE id ='".$ligne["id"]."';";
			$res = PdoGsb::$monPdo->query($req);
			$res->fetch();
		}
	}
}
?>