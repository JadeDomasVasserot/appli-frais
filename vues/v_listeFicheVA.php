<!-- Affiche la liste des fiches validées 
S'il y a au moins une fiche sinon affiche une message d'erreur
Pour le module remoursement par le comptable
affichera v_remboursementFrais.php
-->
<div id="contenu">
	<?php
	if (!empty($lesFiches)) {
	?>
		<h2>Fiches validées</h2>
		<table class="listeLegere">
			<tbody>
				<tr>
					<th> ID visiteur</th>
					<th> Nom</th>
					<th> Prenom</th>
					<th> Date de la fiche</th>
					<th> Montant</th>
					<th> Accès à la fiche</th>
				</tr>
				<?php foreach ($lesFiches as $uneFiche) {
					$annee = substr($uneFiche['mois'], 0, 4); // Sous-chaîne de la valeur mois, mis dans année en prenant les 4 premiers caractères
					$mois = substr($uneFiche['mois'], 4, 2); // Sous-chaîne de la valeur mois, mis dans mois en prenant les 2 derniers caractères
					$time = $mois . "/" . $annee; // Concaténation des deux variables
				?>
					<tr>
						<td><?= $uneFiche['id']; ?></td>
						<td><?= $uneFiche['nom']; ?></td>
						<td><?= $uneFiche['prenom']; ?></td>
						<td><?= $time; ?></td>
						<td><?= $uneFiche['montant']; ?></td>
						<td><a style="text-decoration: none;" href="index.php?uc=rembourserFrais&action=afficherFiche&idVisiteur=
			<?= $uneFiche['id'] . '&mois=' . $uneFiche['mois']; ?>">Suivre</a> </td>
					</tr>
				<?php	} ?>
			</tbody>
		</table>
	<?php
	} else {
		echo "Pas de fiche de frais à mettre en paiement";
	}
