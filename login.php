<?php
include('fonctions.php');
include('bibliotheque/Login/index.php');
	if(isset($_SESSION['etat'])&&$_SESSION['etat']==2){?><script>window.location.href = "index.php";</script><?php }
	if(isset($_SESSION['etat'])&&$_SESSION['etat']==3){?><script>window.location.href = "admin.php";</script><?php }
?>
<script>
function msg(msg)
{
	$('#messages').html($('#messages').html()+"<br>\n"+msg);
}
</script>
<?php
if(isset($_POST['email'])&&isset($_POST['pass']))
{
	$email=$_POST['email'];
	$mot_pass=$_POST['pass'];
		//$stmt = $pdo->prepare("SELECT * FROM ".$tab."utilisateurs ");
		$stmt = $pdo->prepare("SELECT * FROM ".$tab."utilisateurs WHERE email= ? AND  mot_pass= ? ");
		$stmt->execute([$email, $mot_pass]);
		$row = $stmt->fetch();
		//var_dump($row);
		//1.bloqué 2.vendeur 3.admin
			if($row['nom']<>""){
				echo "<script>msg('Connexion en cours');</script>";
				$_SESSION['nom']=$row['nom'];
				$_SESSION['prenom']=$row['prenom'];
				$_SESSION['id']=$row['id'];
				$_SESSION['Nom_utilisateur']=$row['Nom_utilisateur'];
				$_SESSION['tel']=$row['tel'];
				$_SESSION['etat']=$row['etat'];
				if($row['etat']==3){
					?><script>window.location.href = "admin.php";</script><?php
					header('Location: admin.php');
				}else{?><script>window.location.href = "index.php";</script><?php
				}
			}else{
				echo "<script>msg('Erreur de connexion');</script>";
				session_destroy();
			}
		if($row['etat']==1){//droit de connexion
			echo "<script>msg('Vous n\'etes pas autoriser a ce connecter ! ');</script>";
		}
}
?>
