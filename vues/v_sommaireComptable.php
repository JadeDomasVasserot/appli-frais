﻿    <!-- Division pour le sommaire du comptable -->
    <div id="menuGauche">
       <div id="infosUtil">
       </div>
       <ul id="menuList">
          <li>
             <h1>Comptable :</h1><br>
             <h2><?php echo $_SESSION['prenom'] . "  " . $_SESSION['nom']  ?></h2>
          </li>
          <li class="smenu">
             <a href="index.php?uc=gererFraisComptable&action=saisirFrais" title="Saisie fiche de frais ">Saisie fiche de frais</a>
          </li>
          <li class="smenu">
             <a href="index.php?uc=etatFraisComptable&action=selectionnerMois" title="Consultation de mes fiches de frais">Mes fiches de frais</a>
          </li>
          <li class="smenu">
             <a href="index.php?uc=validerFrais&action=selectionnerVisiteur" title="Valider fiche de frais">Valider fiche de frais</a>
          </li>
          <li class="smenu">
             <a href="index.php?uc=rembourserFrais&action=listerFiche" title="Rembourser le paiement fiche de frais">Rembourser le paiement fiche de frais</a>
          </li>
          <li class="smenu">
             <a href="index.php?uc=connexion&action=deconnexion" title="Se déconnecter">Déconnexion</a>
          </li>
       </ul>
    </div>
