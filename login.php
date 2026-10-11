<?php
/**
 * Page de connexion - Point de Vente
 * Système de gestion et d'encaissement
 */
include('fonctions.php');

// Redirection si l'utilisateur est déjà connecté
if (isset($_SESSION['etat'])) {
    if ((int)$_SESSION['etat'] === 3) {
        header('Location: admin.php');
        exit();
    } elseif ((int)$_SESSION['etat'] >= 2) {
        header('Location: index.php');
        exit();
    }
}

$error_message = '';
$email_val = '';

// Traitement du formulaire de connexion
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email_val = trim($_POST['email'] ?? '');
    $mot_pass = $_POST['pass'] ?? '';

    if (empty($email_val) || empty($mot_pass)) {
        $error_message = "Veuillez renseigner votre identifiant et votre mot de passe.";
    } else {
        try {
            // Recherche par email ou par nom d'utilisateur
            $stmt = $pdo->prepare("SELECT * FROM " . $tab . "utilisateurs WHERE (email = ? OR Nom_utilisateur = ?) AND mot_pass = ? LIMIT 1");
            $stmt->execute([$email_val, $email_val, $mot_pass]);
            $user = $stmt->fetch();

            if ($user && !empty($user['id'])) {
                $etat = (int)$user['etat'];

                // Vérification de l'état du compte : 1 = bloqué / inactif
                if ($etat === 1) {
                    $error_message = "Ce compte est désactivé. Veuillez contacter l'administrateur.";
                    session_destroy();
                } else {
                    // Connexion réussie : initialisation de la session
                    $_SESSION['nom'] = $user['nom'];
                    $_SESSION['prenom'] = $user['prenom'];
                    $_SESSION['id'] = $user['id'];
                    $_SESSION['Nom_utilisateur'] = $user['Nom_utilisateur'];
                    $_SESSION['tel'] = $user['tel'];
                    $_SESSION['etat'] = $etat;

                    if ($etat === 3) {
                        header('Location: admin.php');
                        exit();
                    } else {
                        header('Location: index.php');
                        exit();
                    }
                }
            } else {
                $error_message = "Identifiant ou mot de passe incorrect.";
            }
        } catch (Exception $e) {
            $error_message = "Erreur technique lors de la tentative de connexion.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Connexion — Point de Vente</title>
    <link rel="icon" type="image/png" href="img/logo.png">

    <!-- CSS Bootstrap & Font Awesome -->
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="bibliotheque/Login/fonts/font-awesome-4.7.0/css/font-awesome.min.css">

    <!-- Typographie Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #2563eb;
            --primary-hover: #1d4ed8;
            --dark-bg: #0f172a;
            --surface: #ffffff;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --border-color: #e2e8f0;
            --border-focus: #3b82f6;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: radial-gradient(circle at 20% 20%, #1e293b 0%, #0f172a 100%);
            padding: 20px;
            color: var(--text-main);
        }

        .login-wrapper {
            width: 100%;
            max-width: 440px;
        }

        .login-card {
            background: var(--surface);
            border-radius: 20px;
            padding: 40px 36px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.35);
            border: 1px solid rgba(255, 255, 255, 0.1);
            position: relative;
            overflow: hidden;
        }

        .login-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, #2563eb, #38bdf8);
        }

        .brand-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .brand-logo {
            max-height: 65px;
            width: auto;
            margin-bottom: 16px;
            transition: transform 0.3s ease;
        }

        .brand-logo:hover {
            transform: scale(1.03);
        }

        .brand-title {
            font-size: 1.55rem;
            font-weight: 700;
            color: #0f172a;
            letter-spacing: -0.02em;
            margin-bottom: 6px;
        }

        .brand-subtitle {
            font-size: 0.92rem;
            color: var(--text-muted);
            font-weight: 500;
        }

        .form-group {
            margin-bottom: 22px;
        }

        .form-label {
            display: block;
            font-size: 0.86rem;
            font-weight: 600;
            color: #334155;
            margin-bottom: 8px;
        }

        .input-group-custom {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-group-custom .input-icon {
            position: absolute;
            left: 16px;
            color: #94a3b8;
            font-size: 1rem;
            pointer-events: none;
            transition: color 0.2s ease;
            z-index: 2;
        }

        .form-control-custom {
            width: 100%;
            height: 48px;
            padding: 10px 16px 10px 46px;
            font-size: 0.95rem;
            font-weight: 500;
            border-radius: 12px;
            border: 1.5px solid var(--border-color);
            background-color: #f8fafc;
            color: var(--text-main);
            transition: all 0.2s ease;
        }

        .form-control-custom:focus {
            background-color: #ffffff;
            border-color: var(--border-focus);
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.12);
            outline: none;
        }

        .form-control-custom:focus + .input-icon,
        .input-group-custom:focus-within .input-icon {
            color: var(--primary);
        }

        .btn-toggle-password {
            position: absolute;
            right: 14px;
            background: none;
            border: none;
            color: #94a3b8;
            cursor: pointer;
            padding: 4px 6px;
            font-size: 0.95rem;
            transition: color 0.2s ease;
            z-index: 2;
        }

        .btn-toggle-password:hover {
            color: var(--text-main);
        }

        .btn-toggle-password:focus {
            outline: none;
        }

        .btn-submit {
            width: 100%;
            height: 48px;
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: #ffffff;
            border: none;
            border-radius: 12px;
            font-size: 0.98rem;
            font-weight: 600;
            letter-spacing: -0.01em;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.3);
            transition: all 0.2s ease;
            margin-top: 10px;
        }

        .btn-submit:hover {
            background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%);
            box-shadow: 0 12px 20px -3px rgba(37, 99, 235, 0.4);
            transform: translateY(-1px);
            color: #ffffff;
            text-decoration: none;
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        .alert-custom {
            background-color: #fef2f2;
            border: 1px solid #fecaca;
            color: #b91c1c;
            padding: 12px 16px;
            border-radius: 12px;
            font-size: 0.88rem;
            font-weight: 500;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 10px;
            animation: fadeIn 0.3s ease;
        }

        .alert-custom i {
            font-size: 1.1rem;
            flex-shrink: 0;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-6px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .login-footer {
            margin-top: 26px;
            text-align: center;
            font-size: 0.84rem;
            color: var(--text-muted);
            border-top: 1px solid #f1f5f9;
            padding-top: 18px;
        }

        .login-footer p {
            margin-bottom: 4px;
        }

        .login-footer a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
        }

        .login-footer a:hover {
            text-decoration: underline;
        }

        .security-badge {
            margin-top: 20px;
            text-align: center;
            font-size: 0.8rem;
            color: #64748b;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .security-badge i {
            color: #10b981;
        }

        /* Optimisations Responsive pour smartphones et petits écrans */
        @media (max-width: 480px) {
            body {
                padding: 16px 12px;
                align-items: flex-start;
                padding-top: 24px;
                padding-bottom: 24px;
            }

            .login-card {
                padding: 28px 18px;
                border-radius: 16px;
            }

            .brand-logo {
                max-height: 52px;
                margin-bottom: 12px;
            }

            .brand-title {
                font-size: 1.35rem;
            }

            .brand-subtitle {
                font-size: 0.85rem;
            }

            .form-control-custom {
                height: 46px;
                font-size: 0.92rem;
            }

            .btn-submit {
                height: 46px;
                font-size: 0.95rem;
            }
        }
    </style>
</head>
<body>

<div class="login-wrapper">
    <div class="login-card">
        
        <div class="brand-header">
            <img src="img/logo.png" alt="Point de Vente" class="brand-logo" onerror="this.style.display='none'">
            <h1 class="brand-title">Point de Vente</h1>
            <p class="brand-subtitle">Veuillez vous identifier pour accéder à votre espace</p>
        </div>

        <?php if (!empty($error_message)): ?>
            <div class="alert-custom" role="alert">
                <i class="fa fa-exclamation-circle" aria-hidden="true"></i>
                <div><?= htmlspecialchars($error_message, ENT_QUOTES, 'UTF-8'); ?></div>
            </div>
        <?php endif; ?>

        <form method="POST" action="login.php" id="loginForm" autocomplete="on">
            <div class="form-group">
                <label for="email" class="form-label">Identifiant ou Email</label>
                <div class="input-group-custom">
                    <i class="fa fa-envelope-o input-icon" aria-hidden="true"></i>
                    <input 
                        type="text" 
                        class="form-control-custom" 
                        id="email" 
                        name="email" 
                        placeholder="exemple@domaine.com ou identifiant" 
                        value="<?= htmlspecialchars($email_val, ENT_QUOTES, 'UTF-8'); ?>" 
                        required 
                        autofocus
                    >
                </div>
            </div>

            <div class="form-group">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <label for="pass" class="form-label mb-0">Mot de passe</label>
                </div>
                <div class="input-group-custom">
                    <i class="fa fa-lock input-icon" aria-hidden="true"></i>
                    <input 
                        type="password" 
                        class="form-control-custom" 
                        id="pass" 
                        name="pass" 
                        placeholder="••••••••" 
                        required
                    >
                    <button type="button" class="btn-toggle-password" id="togglePassword" title="Afficher ou masquer le mot de passe" aria-label="Afficher ou masquer le mot de passe">
                        <i class="fa fa-eye" id="togglePasswordIcon" aria-hidden="true"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn-submit" id="btnSubmit">
                <span>Se connecter</span>
                <i class="fa fa-arrow-right" aria-hidden="true"></i>
            </button>
        </form>

        <div class="login-footer">
            <p>Besoin d'un accès ou mot de passe oublié ?</p>
            <p>Veuillez contacter votre administrateur système.</p>
        </div>
    </div>

    <div class="security-badge">
        <i class="fa fa-shield" aria-hidden="true"></i>
        <span>Connexion sécurisée — © <?= date('Y'); ?> Point de Vente</span>
    </div>
</div>

<!-- Scripts -->
<script src="bibliotheque/Login/vendor/jquery/jquery-3.2.1.min.js"></script>
<script>
    // Basculer l'affichage du mot de passe
    const togglePasswordBtn = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('pass');
    const toggleIcon = document.getElementById('togglePasswordIcon');

    if (togglePasswordBtn && passwordInput && toggleIcon) {
        togglePasswordBtn.addEventListener('click', function () {
            const isPassword = passwordInput.getAttribute('type') === 'password';
            passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
            toggleIcon.classList.toggle('fa-eye', !isPassword);
            toggleIcon.classList.toggle('fa-eye-slash', isPassword);
        });
    }

    // Animation du bouton lors de la soumission
    const form = document.getElementById('loginForm');
    const btn = document.getElementById('btnSubmit');

    if (form && btn) {
        form.addEventListener('submit', function () {
            btn.innerHTML = '<i class="fa fa-spinner fa-spin" aria-hidden="true"></i> <span>Connexion en cours...</span>';
            btn.style.opacity = '0.85';
            btn.style.pointerEvents = 'none';
        });
    }
</script>

</body>
</html>
