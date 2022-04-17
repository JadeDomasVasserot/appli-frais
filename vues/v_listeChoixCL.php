<div id="contenu">
<?php

if(!empty($lesVisiteursCL)){
    ?>

<h2>Fiches cloturées</h2>
<table class="listeLegere">
<tbody>
	<tr>
		<th> ID visiteur</th>
		<th> Nom</th>
		<th> Prenom</th>
		<th> Date de la fiche</th>
		<th>Accès à la fiche</th>

		
	</tr>
<?php foreach ($lesVisiteursCL as $unVisiteur){
$annee = substr ($unVisiteur['mois'], 0, 4); // Sous-chaîne de la valeur mois, mis dans année en prenant les 4 premiers caractères
$mois = substr ($unVisiteur['mois'], 4, 2); // Sous-chaîne de la valeur mois, mis dans mois en prenant les 2 derniers caractères
$time = $mois."/".$annee; // Concaténation des deux variables
?>
	<tr>
		<td><?= $unVisiteur['id']; ?></td>
		<td><?= $unVisiteur['nom']; ?></td>
		<td><?= $unVisiteur['prenom'];?></td>
		<td><?= $time;?></td>
		<td><a style="text-decoration: none;" href="index.php?uc=validerFrais&action=rechercherFicheFrais&idVisiteur=
			<?= $unVisiteur['id'].'&mois='.$unVisiteur['mois']; ?>">Modifier/Valider</a> </td>
	</tr>
<?php	} ?>
</tbody>
	
</table>


    <?php
} else {
    echo "Pas de fiche de frais à valider";
}
