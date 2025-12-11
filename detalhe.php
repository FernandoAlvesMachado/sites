<?php
require 'config.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM pacotes WHERE id = ?");
$stmt->execute([$id]);
$pacote = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$pacote) {
    echo "Pacote não encontrado.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pacote['titulo'] ?> - Rogetur</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700;800&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="top-bar">
        <div class="container">
            <div><i class="fa fa-phone"></i> (83) 9305-6906</div>
            <div class="social-icons">
                <a href="#"><i class="fab fa-instagram"></i></a>
                <a href="#"><i class="fab fa-facebook-f"></i></a>
            </div>
        </div>
    </div>

    <header>
        <div class="container navbar">
            <a href="index.php" class="logo"><h1>Rogetur<span>.</span></h1></a>
            <div class="mobile-toggle" onclick="toggleMenu()"><i class="fas fa-bars"></i></div>
            <nav>
                <ul class="nav-links" id="navLinks">
                    <li><a href="index.php">Home</a></li>
                    <li><a href="#">Pacotes</a></li>
                    <li><a href="#">Contato</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <div class="page-header">
        <div class="container">
            <div class="page-title">
                <h2><?= $pacote['titulo'] ?></h2>
                <div class="page-subtitle">
                    <span><i class="far fa-calendar-alt"></i> <?= $pacote['subtitulo'] ?></span>
                    <span style="color: var(--primary-color); font-weight:bold;">
                        <i class="fas fa-tag"></i> <?= $pacote['preco'] ?>
                    </span>
                    
                    <button class="btn-share" onclick="alert('Compartilhar no Face')">
                        <i class="fab fa-facebook-f"></i> Compartilhar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <main class="container content-wrapper">
        <section class="main-content">
            <?php if($pacote['pdf_arquivo']): ?>
                <a href="uploads/<?= $pacote['pdf_arquivo'] ?>" target="_blank" class="btn-download">
                    <i class="fas fa-file-download"></i> BAIXAR ROTEIRO COMPLETO (PDF)
                </a>

                <iframe src="uploads/<?= $pacote['pdf_arquivo'] ?>" class="pdf-viewer" title="Roteiro PDF">
                    <p>Seu navegador não suporta PDF. <a href="uploads/<?= $pacote['pdf_arquivo'] ?>">Baixe aqui.</a></p>
                </iframe>
            <?php else: ?>
                <p>Roteiro em PDF não disponível no momento.</p>
            <?php endif; ?>

           
        </section>

        <aside class="sidebar">
            <h3>Capa do Pacote</h3>
            <div class="sidebar-gallery">
                <?php $img = $pacote['imagem_capa'] ? "uploads/".$pacote['imagem_capa'] : "https://via.placeholder.com/400x300"; ?>
                <img src="<?= $img ?>" class="sidebar-img" style="height: auto; width: 100%; grid-column: span 2;">
            </div>

            <div style="margin-top: 30px; background: #e0f7fa; padding: 20px; border-radius: 8px; text-align: center;">
                <h4 style="color: var(--primary-color);">Dúvidas?</h4>
                <p style="font-size: 0.9rem; margin: 10px 0;">Fale com nossos consultores agora mesmo.</p>
                <a href="#" style="font-weight: bold; color: var(--secondary-color); text-decoration: underline;">Ir para Contato</a>
            </div>
        </aside>
    </main>

    <footer>
        <div class="container">
            <div class="copyright">
                <p>&copy; 2025 Rogetur. Todos os direitos reservados.</p>
            </div>
        </div>
    </footer>

    <script>
        function toggleMenu() {
            document.getElementById('navLinks').classList.toggle('active');
        }

        function verificarSenha() {
            const input = document.getElementById('inputSenha');
            const erro = document.getElementById('msgErro');
            const conteudo = document.getElementById('conteudoRestrito');
            const senhaCorreta = "<?= $pacote['senha_grupo'] ?>"; 

            if (input.value === senhaCorreta && senhaCorreta !== "") {
                conteudo.style.display = 'block';
                erro.style.display = 'none';
                input.style.border = "1px solid var(--primary-color)";
                input.disabled = true;
            } else {
                conteudo.style.display = 'none';
                erro.style.display = 'block';
                input.style.border = "1px solid var(--accent-color)";
                input.value = '';
            }
        }
    </script>
</body>
</html>