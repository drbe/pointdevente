<!DOCTYPE html>
<html lang="fr">
<head>
	<title>Point de vente</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================	-->
	<link rel="icon" type="image/png" href="img/logo.png"/>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="bibliotheque/Login/vendor/bootstrap/css/bootstrap.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="bibliotheque/Login/fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="bibliotheque/Login/vendor/animate/animate.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="bibliotheque/Login/vendor/css-hamburgers/hamburgers.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="bibliotheque/Login/vendor/select2/select2.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="bibliotheque/Login/css/util.css">
	<link rel="stylesheet" type="text/css" href="bibliotheque/Login/css/main.css">
<!--===============================================================================================-->
</head>
<body>
	
	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">
				<div class="login100-pic js-tilt" data-tilt>
					<img src="bibliotheque/Login/images/img-01.png" alt="IMG">
				</div>

				<form class="login100-form validate-form" method="post">
					<span class="login100-form-title">
						Connexion mombre
					</span>

					<div class="wrap-input100 validate-input" data-validate = "Un Email valide est requis : ex@abc.xyz">
						<input class="input100" type="text" name="email" placeholder="Email">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-envelope" aria-hidden="true"></i>
						</span>
					</div>

					<div class="wrap-input100 validate-input" data-validate = "Mot de passe necessaire">
						<input class="input100" type="password" name="pass" placeholder="Mot de passe">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-lock" aria-hidden="true"></i>
						</span>
					</div>
					<div id="messages">
					</div>
					<div class="container-login100-form-btn">
						<button class="login100-form-btn">
							Connexion
						</button>
					</div>

					<div class="text-center p-t-12">
						<span class="txt1">
							Oublié ?
						</span>
						<a class="txt2" href="#">
							nom utilisateur / mot de passe?
						</a>
					</div>

					<div class="text-center p-t-136">
						<a class="txt2" href="#">
							Cree votre compte
							<i class="fa fa-long-arrow-right m-l-5" aria-hidden="true"></i>
						</a>
					</div>
				</form>
			</div>
		</div>
	</div>
	
	

	
<!--===============================================================================================-->	
	<script src="bibliotheque/Login/vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
	<script src="bibliotheque/Login/vendor/bootstrap/js/popper.js"></script>
	<script src="bibliotheque/Login/vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
	<script src="bibliotheque/Login/vendor/select2/select2.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/tilt/tilt.jquery.min.js"></script>
	<script >
		$('.js-tilt').tilt({
			scale: 1.1
		})
	</script>
<!--===============================================================================================-->
	<script src="bibliotheque/Login/js/main.js"></script>

</body>
</html>