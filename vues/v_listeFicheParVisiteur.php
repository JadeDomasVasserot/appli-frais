  <!-- Affiche la liste des fiches du visiteur sélectionné 
Pour le module gestion fiche de frais par visiteur par le gestionnaire
affichera v_afficherFicheVisiteur
-->
 <div id="contenu">
   <h2>Toutes les fiches de frais par visiteur</h2>
   <h3>Visiteur à sélectionner : </h3>
   <form action="index.php?uc=gestionParVisiteur&action=voirFicheVisiteur" method="post">
     <div class="corpsForm">
       <p>
         <label for="lstVisiteur" accesskey="n">Visiteur : </label>
         <select id="lstVisiteur" name="lstVisiteur">
           <?php
            foreach ($lesVisiteurs as $unVisiteur) {
              $visiteur = $unVisiteur['id'] . '-' . $unVisiteur['prenom'] . '-' . $unVisiteur['nom'];
              if ($visiteur == $visiteurASelectionner) {
            ?>
               <option selected value="<?php echo $visiteur ?>"><?php echo $visiteur ?> </option>
             <?php
              } else { ?>
               <option value="<?php echo $visiteur ?>"><?php echo  $visiteur ?> </option>
           <?php
              }
            }
            ?>
         </select>
       </p>
     </div>
     <div class="piedForm">
       <p>
         <input id="ok" type="submit" value="Valider" size="20" />
         <input id="annuler" type="reset" value="Effacer" size="20" />
       </p>
     </div>
   </form>