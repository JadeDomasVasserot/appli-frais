    <!-- Division pour le sommaire -->
    <div id="menuGauche">
     <div id="infosUtil">
      </div>  
        <ul id="menuList">
			<li >
				  Gestionnaire :<br>
				<h2><?php echo $_SESSION['prenom']."  ".$_SESSION['nom']  ?></h2>
			</li>
         <li class="smenu">
              <a href="index.php?uc=gestionParMois&action=selectionnerMois" title="Gestion des fiches de frais par mois">Gestion des fiches de frais par mois</a>
           </li>
           <li class="smenu">
              <a href="index.php?uc=gestionParVisiteur&action=selectionnerVisiteur" title="Gestion des fiches de frais par visiteur">Gestion des fiches de frais par visiteur</a>
           </li>
 	   <li class="smenu">
              <a href="index.php?uc=connexion&action=deconnexion" title="Se déconnecter">Déconnexion</a>
           </li>
         </ul>
    </div>
       
    