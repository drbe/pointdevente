		<tr>

		  <th scope="col">#</th>

		  <th scope="col">Clients</th>

		  <th scope="col">Chiffres d'affaires</th>

		  <th scope="col">nbr achats</th>

		  <th scope="col">nbr articles</th>

		</tr>



	  </thead>



	  <tbody>

	  <?php 

	  $sql = "SELECT SUM(".$tab."ticket.total) as prix,".$tab."client.id ,".$tab."client.nom, ".$tab."client.prenom FROM ".$tab."ticket, ".$tab."client WHERE (".$tab."ticket.type=1 or ".$tab."ticket.type=4 ) AND ".$tab."ticket.id_client=".$tab."client.id ".$where_date." GROUP BY ".$tab."client.id,".$tab."client.nom,".$tab."client.prenom ORDER BY prix DESC";

	  		$reponse = $pdo->query($sql." LIMIT ".$page.", ".$bloc);

			$i=0;

			$achat=0;

			$article=0;

			$prix=0;

		while ($donnees = $reponse->fetch())

		{

				$i++;

			echo '<tr class="ligne_liste"><td>'.$i.'</td>'; 

			echo '<td>'.$donnees['nom'].' '.$donnees['prenom'].'</td>'; 

			echo '<td>'.number_format($donnees['prix'], 3).'</td>';

			$prix+=$donnees['prix'];

			$req="SELECT count(*) as nbr FROM ".$tab."ticket WHERE (".$tab."ticket.type=1 or ".$tab."ticket.type=4 ) and ".$tab."ticket.id_client=".$donnees['id'];

				$rep = $pdo->query($req);

				$data = $rep->fetch();

				echo '<td>'.$data['nbr'].'</td>';

			$achat+=$data['nbr'];

			$req="SELECT SUM(".$tab."ticket.nbr_produits) as nbr FROM ".$tab."ticket WHERE (".$tab."ticket.type=1 or ".$tab."ticket.type=4 ) and  ".$tab."ticket.id_client=".$donnees['id'];

				$rep = $pdo->query($req);

				$data = $rep->fetch();

				echo '<td>'.$data['nbr'].'</td></tr>'; 

				$article+=$data['nbr'];

		}

		if($i==0)

		{

			echo "<tr><td  colspan='4'>Vous n'avez pas d'enregistrements</td></tr>";

		}else{

		?>

			<tr class="total_tab"><td></td>

			<td></td>

			<td><?php echo number_format($prix, 3);?></td>

			<td><?php echo $achat;?></td>

			<td><?php echo $article;?></td></tr>

			<?php }?>