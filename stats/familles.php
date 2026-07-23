		<tr>

		  <th scope="col">#</th>

		  <th scope="col">Familles</th>

		  <th scope="col">Nombre de vente</th>

		  <th scope="col">Total</th>

		</tr>

	  </thead>

	  <tbody>

	  <?php 

	  $sql = "SELECT sum(".$tab."produits.prix) as prix ,".$tab."familles.id,".$tab."familles.nom as fam, sum(".$tab."details_ticket.qte) as nbr FROM ".$tab."details_ticket,".$tab."ticket,".$tab."produits,".$tab."familles WHERE ".$tab."ticket.id=".$tab."details_ticket.id_tickets && (".$tab."ticket.type=1 or ".$tab."ticket.type=4 ) && ".$tab."details_ticket.id_produit=".$tab."produits.id and ".$tab."familles.id=".$tab."produits.famille ".$where_date." GROUP BY ".$tab."familles.id,".$tab."familles.nom ORDER BY nbr DESC";

	  		$reponse = $pdo->query($sql." LIMIT ".$page.", ".$bloc);

			$i=0;

			$nbr=0;

			$total=0;

		while ($donnees = $reponse->fetch())

		{

				$i++;

			echo '<tr class="ligne_liste"><td>'.$i.'</td>';  

			echo '<td>'.$donnees['fam'].'</td>'; 

			echo '<td>'.$donnees['nbr'].'</td>';

				$nbr+=$donnees['nbr'];

			echo '<td>'.number_format($donnees['prix'],3).'</td></tr>';

				$total+=$donnees['prix'];

		}

		if($i==0)

		{

			echo "<tr><td  colspan='4'>Vous n'avez pas d'enregistrements</td></tr>";

		}else{

		?>

		<tr class="total_tab">

			<td></td>  

			<td></td>

			<td><?php echo $nbr;?></td>

			<td><?php echo number_format($total,3);?></td>

		</tr>

		<?php }?>