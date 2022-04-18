			<!-- Pour le module Validation de fiche de frais par le comptable
			affiche les informations de la fiche sélectionnée dans v_listeChoixCL.php
			permet de modifier les quantitées, de supprimer (refuser un frais), de modifier le nombre de justificatifs
			et valider et inserer le montant validé en BDD
			-->
			<?php
			if ($rechercheOK === true) {
			?>
				<div>
					<h3>Visiteur : <?php echo $visiteurSelect['id'] . '       ' . $visiteurSelect['nom'] . '  ' . $visiteurSelect['prenom'] ?>
					</h3>
				</div>
				<?php if ($lesFraisForfait != false) { ?>
					<div style="clear:left;">
						<h2>Frais au forfait </h2>
					</div>
					<table>
						<tr>
							<th>Libellé</th>
							<?php
							foreach ($lesFraisForfait as $unFraisForfait) {
								$libelle = $unFraisForfait['libelle'];
								echo '<th style="border:1Px">' . $libelle . '</th>';
							}
							?>
						</tr>
						<form action="index.php?uc=validerFrais&action=modifierFraisForfait&idVisiteur=
			<?= $leVisiteur . '&mois=' . $leMois ?>" method="post">
							<tr>
								<td>Quantité</td>
								<?php
								$i = 0;
								foreach ($lesFraisForfait as $unFraisForfait) {
									$idFrais = $unFraisForfait['idfrais'];
									$quantite = $unFraisForfait['quantite'];
									echo '<td><input name="lesFrais[' . $idFrais . ']" type="number" value="' . $quantite . '"></td>';
								} ?>
							</tr>
							<tr>
								<td>Montant unitaire</td>
								<?php
								foreach ($lesFraisForfait as $unFraisForfait) {
									$montantValide = $unFraisForfait['montant'];
									echo '<td>' . $montantValide . ' € </td>';
								}
								?>
							</tr>
							<tr>
								<td>Total</td>
								<?php
								$totalForfait = 0;
								foreach ($lesFraisForfait as $unFraisForfait) {
									$quantite = $unFraisForfait['quantite'];
									$montantValide = $unFraisForfait['montant'];
									$totalParLibelle = $quantite * $montantValide;
									echo '<td>' . $totalParLibelle . ' €</td>';
									$totalForfait += $totalParLibelle;
								}
								?>
							</tr>
					</table>
					<input type="submit" value="modifier les quantités" style="margin:5px;">
					</form>
					<h4>TOTAL : <?php echo $totalForfait ?> €</h4>
				<?php }
				if ($lesFraisHorsForfait != false) { ?>
					<p class="titre" />
					<div style="clear:left;">
						<h2>Hors Forfait</h2>
					</div>
					<table>
						<tr>
							<th>Date</th>
							<th>Libellé </th>
							<th>Montant</th>
							<th>Action</th>
						</tr>
						<?php
						$totalHF = 0;
						foreach ($lesFraisHorsForfait as $unFraisHorsForfait) {
							$id = $unFraisHorsForfait['id'];
							$date = $unFraisHorsForfait['date'];
							$libelleHorsForfait = $unFraisHorsForfait['libelle'];
							$montantHorsForfait = $unFraisHorsForfait['montant'];
							echo '<tr><td>' . $date . '</td>';
							echo '<td>' . $libelleHorsForfait . '</td>';
							echo '<td>' . $montantHorsForfait . ' €</td>';
							echo '<td><a href="index.php?uc=validerFrais&action=supprimerFrais&idFrais=' . $id . '&libelle=' . $libelleHorsForfait . '">Supprimer</a></tr>';
							$totalHF += $montantHorsForfait;
						}
						?>
					</table>
					<h4>TOTAL : <?php echo $totalHF ?> €</h4>
				<?php
				} ?>
				<form action="index.php?uc=validerFrais&action=validerFrais&idVisiteur=
					<?= $leVisiteur . '&mois=' . $leMois ?>" method="post">
					<p class="titre"></p>
					<div class="titre">Nb Justificatifs</div>
					<input type="number" class="zone" name="justificatifs" size="4" value="<?php echo $lesInfosFicheFrais["nbJustificatifs"];
																							?>" />
					<p class="titre" />
					<h4>TOTAL Forfait et Hors-forfait: 
						<?php
							if ($lesFraisHorsForfait != false && $lesFraisForfait != false) {
								$totalHFetF = $totalForfait + $totalHF;
							} else if ($lesFraisHorsForfait == false && $lesFraisForfait != false) {
								$totalHFetF = $totalForfait;
							} else if ($lesFraisForfait == false && $lesFraisHorsForfait != false) {
								$totalHFetF = $totalHF;
							} else {
								$totalHFetF = 0;
							}
							echo $totalHFetF . '<input name="total" type="hidden"value="' . $totalHFetF . '">' ?>
					</h4>
					</h4>
					<input class="zone" type="reset" />
					<input class="zone" type="submit" value="Valider la fiche" />
				</form>

			<?php
			}
			?>
			</div>

			</body>

			</html>
