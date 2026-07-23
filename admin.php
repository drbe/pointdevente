<?php
include('fonctions.php');
if (!isset($_SESSION['etat']) || $_SESSION['etat'] <= 2) {
  header('Location: login.php');
}
if (isset($_GET['deconnexion'])) {
  session_destroy();
  header('Location: login.php');
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
  <title>Point de vente</title>
  <link rel="icon" type="image/png" href="img/logo.png" />
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script>
    // Script CRITIQUE - doit s'exécuter avant le CSS pour éviter le flash
    (function() {
      const theme = localStorage.getItem('theme') || 'light';
      document.documentElement.setAttribute('data-theme', theme);
      document.documentElement.classList.add(theme === 'dark' ? 'dark-theme' : 'light-theme');
    })();
  </script>
  <style>
    /* Spinner de chargement */
    .loading-spinner {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: #ffffff;
      display: flex;
      justify-content: center;
      align-items: center;
      z-index: 9999;
      transition: opacity 0.3s ease, visibility 0.3s ease;
    }
    
    .loading-spinner.dark {
      background-color: #1a1a1a;
    }
    
    .loading-spinner.hidden {
      opacity: 0;
      visibility: hidden;
    }
    
    .spinner {
      width: 50px;
      height: 50px;
      border: 4px solid #f3f3f3;
      border-top: 4px solid #007bff;
      border-radius: 50%;
      animation: spin 1s linear infinite;
    }
    
    .loading-spinner.dark .spinner {
      border-color: #495057;
      border-top-color: #66b3ff;
    }
    
    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }
    
    .loading-text {
      margin-top: 20px;
      font-size: 16px;
      color: #6c757d;
    }
    
    .loading-spinner.dark .loading-text {
      color: #adb5bd;
    }
  </style>
  <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="css/css.css" rel="stylesheet">
  <link href="css/bootstrap-colorpicker.min.css" rel="stylesheet">
  <link href="bibliotheque/fontawesome/css/all.css" rel="stylesheet">
  <script src="js/jquery.min.js"></script>
  <script src="bibliotheque/fontawesome/js/all.js"></script>
  <script src="bibliotheque/html2pdf.js"></script>
  <script src="bootstrap/js/bootstrap.min.js"></script>
  <script src="js/bootstrap-colorpicker.js"></script>
  <script src="js/fonction_admin.js"></script>
  <script src="js/fonctions.js"></script>
  <script src="js/theme-manager.js"></script>
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  <!--poup-->
  <link href="popup/style.css" rel="stylesheet">
  <!--popup-->
</head>

<body>
  <!-- Spinner de chargement -->
  <div id="loading-spinner" class="loading-spinner">
    <div class="text-center">
      <div class="spinner"></div>
      <div class="loading-text">Chargement en cours...</div>
    </div>
  </div>

  <div class="piste no_print">
    <div class="row">
      <nav class="navbar navbar-expand-xl navbar navbar-dark bg-dark col">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav mr-auto">
            <li class="nav-item active">
              <a class="nav-link" href="admin.php">Accueil <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Fichier
              </a>
              <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a href="?menu=familles" class="dropdown-item" href="#">Familles</a>
                <a href="?menu=produits" class="dropdown-item" href="#">Produits</a>
                <div class="dropdown-divider"></div>
                <a href="?menu=ventes&type=1" class="dropdown-item" href="#">Facture</a>
                <a href="?menu=ventes&type=2" class="dropdown-item" href="#">Devis</a>
                <a href="?menu=ventes&type=3" class="dropdown-item" href="#">Bon de commande</a>
                <a href="?menu=ventes&type=4" class="dropdown-item" href="#">Bon de livraison</a>
                <a href="?menu=ventes" class="dropdown-item" href="#">Tout</a>
                <div class="dropdown-divider"></div>
                <a href="?menu=clients" class="dropdown-item" href="#">Clients</a>
                <div class="dropdown-divider"></div>
                <a href="?menu=utilisateurs" class="dropdown-item" href="#">Utilisateurs</a>
              </div>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="index.php">Point de vente</a>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Statistique
              </a>
              <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="?menu=stats&type=produits">Meilleur vente par produit</a>
                <a class="dropdown-item" href="?menu=stats&type=familles">Meilleur vente par famille</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="?menu=stats&type=clients">Meilleur vente par client</a>
                <a class="dropdown-item" href="?menu=stats&type=utilisateurs">Meilleur vente par utilisateur</a>
              </div>
            </li>

            <li class="nav-item">
              <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">A propos</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="admin.php?deconnexion=true">Déconnexion</a>
            </li>
          </ul>
          <ul class="navbar-nav ml-auto">
            <li class="nav-item mr-3">
              <span class="nav-link"><i class="fas fa-user"></i> <?php echo $_SESSION['Nom_utilisateur']; ?></span>
            </li>
            <li class="nav-item">
              <button id="theme-toggle" class="btn btn-outline-secondary btn-sm nav-link" title="Basculer vers le thème sombre">
                <i class="fas fa-moon"></i>
              </button>
            </li>
          </ul>
        </div>
      </nav>
    </div>
    <div class="row">
      <div class="col">
        <div id="html"></div>
        <?php
        if (isset($_REQUEST['menu'])) {
          include($_REQUEST['menu'] . ".php");
        } else {
        ?>
          <div class="container-fluid mt-5">
            <h2 class="text-center mb-5">Point de vente</h2>
            <div class="row g-4">
              <!-- Familles -->
              <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-4">
                <a href="?menu=familles" class="text-decoration-none">
                  <div class="card h-100 text-center icon-card">
                    <div class="card-body">
                      <i class="fas fa-boxes fa-3x mb-3"></i>
                      <h5 class="card-title">Familles</h5>
                    </div>
                  </div>
                </a>
              </div>

              <!-- Produits -->
              <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-4">
                <a href="?menu=produits" class="text-decoration-none">
                  <div class="card h-100 text-center icon-card">
                    <div class="card-body">
                      <i class="fas fa-cube fa-3x mb-3"></i>
                      <h5 class="card-title">Produits</h5>
                    </div>
                  </div>
                </a>
              </div>

              <!-- Clients -->
              <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-4">
                <a href="?menu=clients" class="text-decoration-none">
                  <div class="card h-100 text-center icon-card">
                    <div class="card-body">
                      <i class="fas fa-users fa-3x mb-3"></i>
                      <h5 class="card-title">Clients</h5>
                    </div>
                  </div>
                </a>
              </div>

              <!-- Utilisateurs -->
              <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-4">
                <a href="?menu=utilisateurs" class="text-decoration-none">
                  <div class="card h-100 text-center icon-card">
                    <div class="card-body">
                      <i class="fas fa-user-tie fa-3x mb-3"></i>
                      <h5 class="card-title">Utilisateurs</h5>
                    </div>
                  </div>
                </a>
              </div>

              <!-- Factures -->
              <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-4">
                <a href="?menu=ventes&type=1" class="text-decoration-none">
                  <div class="card h-100 text-center icon-card">
                    <div class="card-body">
                      <i class="fas fa-file-invoice fa-3x mb-3"></i>
                      <h5 class="card-title">Factures</h5>
                    </div>
                  </div>
                </a>
              </div>

              <!-- Devis -->
              <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-4">
                <a href="?menu=ventes&type=2" class="text-decoration-none">
                  <div class="card h-100 text-center icon-card">
                    <div class="card-body">
                      <i class="fas fa-file-alt fa-3x mb-3"></i>
                      <h5 class="card-title">Devis</h5>
                    </div>
                  </div>
                </a>
              </div>

              <!-- Bon de commande -->
              <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-4">
                <a href="?menu=ventes&type=3" class="text-decoration-none">
                  <div class="card h-100 text-center icon-card">
                    <div class="card-body">
                      <i class="fas fa-shopping-cart fa-3x mb-3"></i>
                      <h5 class="card-title">Bon de commande</h5>
                    </div>
                  </div>
                </a>
              </div>

              <!-- Bon de livraison -->
              <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-4">
                <a href="?menu=ventes&type=4" class="text-decoration-none">
                  <div class="card h-100 text-center icon-card">
                    <div class="card-body">
                      <i class="fas fa-truck fa-3x mb-3"></i>
                      <h5 class="card-title">Bon de livraison</h5>
                    </div>
                  </div>
                </a>
              </div>

              <!-- Toutes les ventes -->
              <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-4">
                <a href="?menu=ventes" class="text-decoration-none">
                  <div class="card h-100 text-center icon-card">
                    <div class="card-body">
                      <i class="fas fa-list fa-3x mb-3"></i>
                      <h5 class="card-title">Toutes les ventes</h5>
                    </div>
                  </div>
                </a>
              </div>

              <!-- Stats Produits -->
              <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-4">
                <a href="?menu=stats&type=produits" class="text-decoration-none">
                  <div class="card h-100 text-center icon-card">
                    <div class="card-body">
                      <i class="fas fa-chart-bar fa-3x mb-3"></i>
                      <h5 class="card-title">Stats Produits</h5>
                    </div>
                  </div>
                </a>
              </div>

              <!-- Stats Familles -->
              <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-4">
                <a href="?menu=stats&type=familles" class="text-decoration-none">
                  <div class="card h-100 text-center icon-card">
                    <div class="card-body">
                      <i class="fas fa-chart-pie fa-3x mb-3"></i>
                      <h5 class="card-title">Stats Familles</h5>
                    </div>
                  </div>
                </a>
              </div>

              <!-- Stats Clients -->
              <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-4">
                <a href="?menu=stats&type=clients" class="text-decoration-none">
                  <div class="card h-100 text-center icon-card">
                    <div class="card-body">
                      <i class="fas fa-chart-line fa-3x mb-3"></i>
                      <h5 class="card-title">Stats Clients</h5>
                    </div>
                  </div>
                </a>
              </div>

              <!-- Stats Utilisateurs -->
              <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-4">
                <a href="?menu=stats&type=utilisateurs" class="text-decoration-none">
                  <div class="card h-100 text-center icon-card">
                    <div class="card-body">
                      <i class="fas fa-chart-area fa-3x mb-3"></i>
                      <h5 class="card-title">Stats Utilisateurs</h5>
                    </div>
                  </div>
                </a>
              </div>

              <!-- Point de vente -->
              <div class="col-lg-3 col-md-4 col-sm-6 col-12 mb-4">
                <a href="index.php" class="text-decoration-none">
                  <div class="card h-100 text-center icon-card">
                    <div class="card-body">
                      <i class="fas fa-cash-register fa-3x mb-3"></i>
                      <h5 class="card-title">Point de vente</h5>
                    </div>
                  </div>
                </a>
              </div>
            </div>
          </div>
        <?php } ?>
      </div>

    </div><!--piste-->

    <!--Fenetre popup-->
    <div class="pop_up">
      <div class="topHeader">
        <div class="row no_print" id="x" onclick="$('.pop_up').hide();$('body').css('background-color','#000');">✖</div>
      </div>
      <div id="pop_up">
        <div class="pop_upContainer"></div>
      </div>
    </div>
    <!--Fenetre popup-->
    <div class="m-3 text-center">
      <center>
        >Expert informatique 2019 - <?= date('Y') ?> - Tous droits réservés.<
      </center>
    </div>
    <?php include('popup.php'); ?>
    
    <script>
      // Masquer le spinner de chargement une fois que tout est chargé
      window.addEventListener('load', function() {
        const spinner = document.getElementById('loading-spinner');
        const theme = localStorage.getItem('theme') || 'light';
        
        // Appliquer le thème au spinner avant de le masquer
        if (theme === 'dark') {
          spinner.classList.add('dark');
        }
        
        // Petit délai pour s'assurer que tout est bien rendu
        setTimeout(function() {
          spinner.classList.add('hidden');
          // Supprimer complètement après la transition
          setTimeout(function() {
            spinner.style.display = 'none';
          }, 300);
        }, 100);
      });
    </script>
</body>

</html>