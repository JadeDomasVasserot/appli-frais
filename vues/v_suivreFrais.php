
 <div id="contenu">
      <h2>Suivre les fiches de frais</h2>
      <h3>Recherche </h3>
      <form action="index.php?uc=suivreFrais&action=afficherFicheFrais" method="post">
      <div class="corpsForm">
         
      <p>
	 
        <label for="lstMois" accesskey="n">Mois : </label>
        <select id="lstMois" name="lstMois">
            <?php
			foreach ($lesMois as $unMois)
			{
			  $mois = $unMois['mois'];
				$numAnnee =  $unMois['numAnnee'];
				$numMois =  $unMois['numMois'];
				if($mois == $moisASelectionner){
				?>
				<option selected value="<?php echo $mois ?>"><?php echo  $numMois."/".$numAnnee ?> </option>
				<?php 
				}
				else{ ?>
				<option value="<?php echo $mois ?>"><?php echo  $numMois."/".$numAnnee ?> </option>
				<?php 
				}
			
			}       
		   ?>    
        </select>
        <select id="lstVisiteur" name="lstVisiteur">
            <?php
			foreach ($lesVisiteurs as $unVisiteur)
			{
				$visiteur = $unVisiteur['nom'];
				?>
				<option selected value="<?php echo $visiteur ?>"><?php echo  $visiteur?> </option>
				<?php 
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
      