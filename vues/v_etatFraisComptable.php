<!-- affiche l'état de la fiche de frais sélectionnée par l'utilisateur connecté selon le mois selectionné dans v_listeMoisComptable
Pour le module mes fiches de frais -->
<h3>Fiche de frais du mois <?php echo $numMois . "-" . $numAnnee ?> :
</h3>
<!-- Affiche la génération de PDG si la fiche est VA ou RB -->
<?php if($libEtat == "Remboursée" || $libEtat == "Validée et mise en paiement"){ ?>
<a href="index.php?uc=etatFraisComptable&action=genererPDF&idVisiteur=
			<?= $idVisiteur . '&mois=' . $leMois ?>" method="post""><h1>Télécharger le PDF</h1></a>
<?php } ?>    
      <div class=" encadre">
  <p>
    Etat : <?php echo $libEtat ?> depuis le <?php echo $dateModif ?> <br> Montant validé : <?php echo $montantValide ?>
  </p>
   <!-- Tableau pour les frais forfaits : faire une boucle sur la requête en BDD qui récupère les frais selon le mois et l'idVIsiteur -->
  <table class="listeLegere">
    <caption>Eléments forfaitisés </caption>
    <tr>
      <?php
      foreach ($lesFraisForfait as $unFraisForfait) {
        $libelle = $unFraisForfait['libelle'];
      ?>
        <th> <?php echo $libelle ?></th>
      <?php
      }
      ?>
      <th>Total</th>
    </tr>
    <tr>
      <?php
      foreach ($lesFraisForfait as $unFraisForfait) {
        $quantite = $unFraisForfait['quantite'];
      ?>
        <td class="qteForfait"><?php echo $quantite ?> </td>
      <?php
      }
      ?>
    </tr>
    <tr>
      <?php
      $montantTot = 0;
      foreach ($lesFraisForfait as $unFraisForfait) {
        $montantUnit = $unFraisForfait['montant'];
      ?>
        <td class="montant"><?php echo $montantUnit ?> €</td>
      <?php
      }
      ?>
    </tr>
    <tr>
      <!-- Calcul du montant des frais forfaits -->
      <?php
      $montantTot = 0;
      foreach ($lesFraisForfait as $unFraisForfait) {
        $quantite = $unFraisForfait['quantite'];
        $montantUnit = $unFraisForfait['montant'];
        $montantLibelle = $quantite * $montantUnit;
        $montantTot += $montantLibelle;
      ?>
        <td class="montant"><?php echo $montantLibelle ?>€ </td>
      <?php
      }
      ?>
      <td class="montantTotal">
        <h3><?php echo $montantTot ?> €</h3>
      </td>
    </tr>
  </table>
  <!-- Tableau pour les frais hors-forfaits : faire une boucle sur la requête en BDD qui récupère les frais selon le mois et l'idVIsiteur -->
  <table class="listeLegere">
    <caption>Descriptif des éléments hors forfait -<?php echo $nbJustificatifs ?> justificatifs reçus -
    </caption>
    <tr>
      <th class="date">Date</th>
      <th class="libelle">Libellé</th>
      <th class='montant'>Montant</th>
    </tr>
    <?php
    $montantHF = 0;
    foreach ($lesFraisHorsForfait as $unFraisHorsForfait) {
      $date = $unFraisHorsForfait['date'];
      $libelle = $unFraisHorsForfait['libelle'];
      $montant = $unFraisHorsForfait['montant'];
    ?>
      <tr>
        <td><?php echo $date ?></td>
        <td><?php echo $libelle ?></td>
        <td><?php echo $montant ?></td>
         <!-- Calcul du montant total hors-forfait  -->
        <?php $montantHF += $montant; ?>
      </tr>
    <?php
    }
    ?>
    <tr>
      <td colspan="3"><?php echo '<h3>TOTAL = ' . $montantHF . ' €</h3>'; ?></td>
    </tr>
  </table>
      </div>
  </div>