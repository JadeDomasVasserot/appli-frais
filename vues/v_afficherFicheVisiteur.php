<!-- Affiche les informations des fiches de frais dont le visiteur a été selectionné dans v_listeFicheParMois
S'il y a au moins une fiche sinon affiche une message d'erreur
Pour le module gestion fiches de frais par visiteur -->
<?php
if (!empty($lesInfosFicheFrais)) {
	if ($ficheSelect = true) { ?>
		<table class="listeLegere">
			<tbody>
				<tr>
					<th> ID visiteur</th>
					<th> Nom</th>
					<th> Prenom</th>
					<th> Date de la fiche</th>
					<th> Montant (si validée ou remboursée)</th>
					<th> Etat de la fiche</th>
				</tr>
				<?php foreach ($lesInfosFicheFrais as $uneFiche) {
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
						<td><?= $uneFiche['libelleEtat']; ?></td>
					</tr>
				<?php	} ?>
			</tbody>
		</table>
<?php
	} else {
		echo "Pas de fiche de frais pour ce mois";
	}
}
