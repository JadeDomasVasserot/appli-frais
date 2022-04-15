<html>

<head>
    <title>Suivi des frais de visite</title>
</head>

<body>
    <?php if($remboursementOK == true){?>
    <h1>Suivi de remboursement des Frais</h1>
    <a  href="index.php?uc=validerFrais&action=rechercherFicheFrais&idVisiteur=
			<?= $leVisiteur.'&mois='.$leMois; ?>">Modifier la fiche (si besoin)</a>
    <form name="formConsultFrais" method="post" action="index.php?uc=rembourserFrais&action=rembourserFiche&idVisiteur=
			<?= $leVisiteur.'&mois='.$leMois?>" method="post">
    <?php if($lesFraisForfait != false){?>
        <div style="clear:left;">
            <h2>Frais au forfait </h2>
        </div>
        <table>
            <tr>
                <th>Repas midi</th>
                <th>Nuitée </th>
                <th>Etape</th>
                <th>Km </th>
                <th>Situation</th>
                <th>Date opération</th>
            </tr>
            <tr align="center">
                <?php	foreach ($lesFraisForfait as $unFraisForfait) {
                        $ficheSituation = $lesInfosFicheFrais["libEtat"];
                        $ficheDateOperation = $lesInfosFicheFrais["dateModif"];
						$quantite = $unFraisForfait['quantite'];
                        $idFrais = $unFraisForfait['idfrais'];
                        $montant = $unFraisForfait['montant'];
                        $montantParCategorie= $montant *$quantite;
                        echo '<td name="'.$idFrais.'">'.$quantite.'</td>';
                }?>
                        <td width="80"> <label size="3" name="situation" />
                        <?php echo $lesInfosFicheFrais["libEtat"];?>
                        </td>
                        <td width="80"> <label size="3" name="dateOper" />
                        <?php echo $lesInfosFicheFrais["dateModif"];?></td>
               
            </tr>
        </table>
        <?php
        }
        if($lesFraisHorsForfait != false){?>
        <p class="titre" />
        <div style="clear:left;">
            <h2>Hors Forfait</h2>
        </div>
        <table>
            <tr>
                <th>Date</th>
                <th>Libellé </th>
                <th>Montant</th>
                <th>Situation</th>
                <th>Date opération</th>
            </tr>
            <tr align="center">
                <?php	foreach ($lesFraisHorsForfait as $unFraisHorsForfait) {
                        $ficheSituation = $lesInfosFicheFrais["libEtat"];
                        $ficheDateOperation = $lesInfosFicheFrais["dateModif"];
						$date = $unFraisHorsForfait['date'];
                        $idFrais = $unFraisHorsForfait['id'];
                        $montant = $unFraisHorsForfait['montant'];
                        $libelle = $unFraisHorsForfait['libelle'];
                        echo '<td name="date">'.$date.'</td>
                        <td name="libelle">'.$libelle.'</td>
                        <td name="montant">'.$montant.'</td>
                        <td width="80"> <label size="3" name="situation" />'.
                        $lesInfosFicheFrais["libEtat"].'</td>
                        <td width="80"> <label size="3" name="dateOper" />
                        '.$lesInfosFicheFrais["dateModif"].'</td>';
                }?>
                       
               
            </tr>
        </table>
        <?php
        }
       ?>
        <p class="titre"></p>
     <div style="margin-bottom:10px;"> Nb Justificatifs : <?php echo $lesInfosFicheFrais["nbJustificatifs"]; ?></div> 
        <h2>Remboursement = <?php echo $lesInfosFicheFrais["montantValide"]; ?> €€
        </h2>
        <p class="titre">
            <input class="zone"type="submit" value="Remboursement"/>
        </p>
    </form>
    <?php }?>
    </div>
</body>

</html>