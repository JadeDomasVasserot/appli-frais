<?php
//classe avec les fonctions créées pour la génération du PDF
    class PDF extends FPDF
		{
			// En-tête mis en place le titre, le logo
			function Header()
			{
				// Logo
				$this->Image('images/logo.jpg',80,30,50);
				// Police Arial gras 15
				$this->SetFont('Arial','B',15);
				$this->Cell('20');
				// Titre
				$this->Cell(150,20,'REMBOURSEMENT DE FRAIS ENGAGES',1,1,'C');
				// Saut de ligne
				$this->Ln(40);
			}
			// Pied de page qui permet d'avoir le n° des pages
			function Footer()
			{
				// Positionnement à 1,5 cm du bas
				$this->SetY(-15);
				// Police Arial italique 8
				$this->SetFont('Arial','I',8);
				// Numéro de page
				$this->Cell(0,10,'Page '.$this->PageNo(),0,0,'C');
			}
            // Fonction qui affiche les données de la personne passée en paramètre
			function Visiteur($lesInfosVisiteurs)
			{
				// Cell = cellule
                $this->Cell(10,10,'Visiteur',0,0,'L');
                $this->Cell(0,10,'id : '.$lesInfosVisiteurs["id"],0,0,'C');
                $this->Cell(0,10,utf8_decode($lesInfosVisiteurs["prenom"]),0,1,'R');
                $this->Cell(0,10,utf8_decode($lesInfosVisiteurs["nom"]),0,1,'R');
                $mois = $lesInfosVisiteurs["mois"];
                $this->Cell(0,10,'mois : '.$mois,0,1,'L');
                $this->Ln();
        }
			// Tableau qui affiche les frais forfaits de la personne concernée
			function TableForfait($header, $data)
			{
                $this->Cell(0,10,'FRAIS FORFAITS',1,1,'C');
				// Couleurs, épaisseur du trait et police grasse
				$this->SetFillColor(62, 186, 252);
				$this->SetTextColor(255);
				$this->SetDrawColor(128,0,0);
				$this->SetLineWidth(.3);
				$this->SetFont('','B');
				// En-tête
				$w = array(50, 50, 45, 45);
				for($i=0;$i<count($header);$i++)
					$this->Cell($w[$i],7,utf8_decode($header[$i]),1,0,'C',true);
				$this->Ln();
				// Restauration des couleurs et de la police
				$this->SetFillColor(224,235,255);
				$this->SetTextColor(0);
				$this->SetFont('');
				// Données
				$fill = false;
				foreach($data as $row)
				{
					$this->Cell($w[0],6,utf8_decode($row["libelle"]),'LR',0,'C',$fill);
					$this->Cell($w[1],6,$row["quantite"],'LR',0,'C',$fill);
					$this->Cell($w[2],6,$row["montant"],'LR',0,'C',$fill);
                    $total = $row["quantite"] * $row["montant"];
					$this->Cell($w[3],6,$total,'LR',0,'C',$fill);
					$this->Ln();
					$fill = !$fill;
				}
				// Trait de terminaison
				$this->Cell(array_sum($w),0,'','T');
                $this->Ln(10);
			}
            // Tableau qui affiche les frais hors-forfaits de la personne concernée
			function TableHorsForfait($header, $data)
			{
                $this->Cell(0,10,'FRAIS HORS FORFAITS',1,1,'C');
				// Couleurs, épaisseur du trait et police grasse
				$this->SetFillColor(62, 186, 252);
				$this->SetTextColor(255);
				$this->SetDrawColor(128,0,0);
				$this->SetLineWidth(.3);
				$this->SetFont('','B');
				// En-tête
				$w = array(35, 120,35);
				for($i=0;$i<count($header);$i++)
					$this->Cell($w[$i],7,utf8_decode($header[$i]),1,0,'C',true);
				$this->Ln();
				// Restauration des couleurs et de la police
				$this->SetFillColor(224,235,255);
				$this->SetTextColor(0);
				$this->SetFont('');
				// Données
				$fill = false;
				foreach($data as $row)
				{
					$this->Cell($w[0],6,$row["date"],'1',0,'C',$fill);
					$this->Cell($w[1],6,utf8_decode($row["libelle"]),'1',0,'C',$fill);
                    $this->Cell($w[2],6,$row["montant"],'1',0,'C',$fill);
					$this->Ln();
					$fill = !$fill;
				}
				// Trait de terminaison
				$this->Cell(array_sum($w),0,'','T');
                $this->Ln(10);
			}
			//permet de calculer le total à rembourser
            function Total($fraisForfait, $fraisHorsForfait)
			{
                $totalForfait = 0;
                $totalForfaitHorsForfait = 0;
				foreach($fraisForfait as $unFrais)
				{
                    $totalForfaitLibelle = $unFrais["quantite"] * $unFrais["montant"];
                    $totalForfait += $totalForfaitLibelle;
				}
                foreach($fraisHorsForfait as $unFraisHorsForfait)
				{
                    $totalForfaitHorsForfait += $unFraisHorsForfait["montant"];
				}
                $total = $totalForfait + $totalForfaitHorsForfait;
                $this->Cell(0,10,'TOTAL : '.$total,1,1,'C');
                $this->Ln(10);
			}
			//permet d'ajouter la date de modif et la signature'
            function Signature($infoFiche)
			{
                $this->Cell(50,10,utf8_decode('Fait à Lyon'),0,1,'L');
                $this->Cell(0,10,"Vu par l'agent comptable, le ".$infoFiche["dateModif"],0,1,'L');
                $this->Image('images/signature.png',150, $this->SetY(-45),50);
			}
		}
?>