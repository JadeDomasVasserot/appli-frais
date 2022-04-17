 <!-- Affiche la liste des fiches du mois sélectionné 
Pour le module gestion fiche de frais par mois par le gestionnaire
affichera v_afficherFicheMois
-->
 <div id="contenu">
   <h2>Toutes les fiches de frais par mois</h2>
   <h3>Mois à sélectionner : </h3>
   <form action="index.php?uc=gestionParMois&action=voirFicheMois" method="post">
     <div class="corpsForm">
       <p>
         <label for="lstMois" accesskey="n">Mois : </label>
         <select id="lstMois" name="lstMois">
           <?php
            foreach ($lesMois as $unMois) {
              $mois = $unMois['mois'];
              $numAnnee =  $unMois['numAnnee'];
              $numMois =  $unMois['numMois'];
              if ($mois == $moisASelectionner) {
            ?>
               <option selected value="<?php echo $mois ?>"><?php echo  $numMois . "/" . $numAnnee ?> </option>
             <?php
              } else { ?>
               <option value="<?php echo $mois ?>"><?php echo  $numMois . "/" . $numAnnee ?> </option>
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