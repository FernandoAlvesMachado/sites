<?php require 'config.php'; ?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rogetur Viagens e Turismo</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700;800&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="top-bar">
        <div class="container">
            <div><i class="fa fa-phone"></i> (83) 9305-6906 | <i class="fa fa-envelope"></i> rogetur@rogetur.com.br</div>
            <div class="social-icons">
                <a href="#" target="_blank"><i class="fab fa-instagram"></i></a>
                <a href="#" target="_blank"><i class="fab fa-facebook-f"></i></a>
            </div>
        </div>
    </div>

    <header>
        <div class="container navbar">
            <a href="index.php" class="logo">
                <h1>Rogetur<span>.</span></h1>
            </a>
            <div class="mobile-toggle" onclick="toggleMenu()"><i class="fas fa-bars"></i></div>
            <nav>
                <ul class="nav-links" id="navLinks">
                    <li><a href="#internacionais">Internacionais</a></li>
                    <li><a href="#nacionais">Nacionais</a></li>
                    <li><a href="#">Cruzeiros</a></li>
                    <li><a href="#contato">Contato</a></li>
                    <li><a href="admin/adicionar.php" style="color:#690707; font-weight:bold;">Área Admin</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <section class="hero">
        <div class="container">
            <h2>Qual seu próximo destino?</h2>
            <form action="#" class="search-box">
                <input type="text" placeholder="Procure por um destino...">
                <button type="submit"><i class="fas fa-search"></i> Buscar</button>
            </form>
        </div>
    </section>

    <main class="container">
        
        <div id="internacionais" class="section-title" style="margin-top: 50px;">
            <h3>Destaques <span>Internacionais</span></h3>
        </div>

        <div class="packages-grid">
            <?php
            // Busca pacotes do banco de dados (os antigos e os novos)
            $stmt = $pdo->prepare("SELECT * FROM pacotes WHERE destaque_tipo = :tipo ORDER BY id DESC");
            $stmt->execute([':tipo' => 'internacional']);
            
            while ($pacote = $stmt->fetch(PDO::FETCH_ASSOC)):
                // LÓGICA INTELIGENTE DE IMAGEM
                $img = $pacote['imagem_capa'];
                
                if (strpos($img, 'http') === 0) {
                    // É um link externo
                    $src = $img;
                } elseif (strpos($img, '/Arquivos/') === 0) {
                    // É uma imagem do site antigo (remove a primeira barra se necessário ou mantem)
                    // Como estamos na raiz, o caminho relativo deve funcionar se a pasta existir
                    $src = "." . $img; 
                } else {
                    // É um upload novo feito pelo Admin
                    $src = "uploads/" . $img;
                }
            ?>
                <article class="card">
                    <div class="card-img-wrapper">
                        <img src="<?= $src ?>" alt="<?= htmlspecialchars($pacote['titulo']) ?>" class="card-img" onerror="this.src='https://via.placeholder.com/400x300?text=Sem+Imagem'">
                    </div>
                    
                    <div class="card-body">
                        <h4 class="card-title"><?= htmlspecialchars($pacote['titulo']) ?></h4>
                        <p class="card-text" style="color: #690707; font-weight: bold; font-size: 0.9rem;">
                            <?= htmlspecialchars($pacote['subtitulo']) ?>
                        </p>
                        
                        <a href="detalhe.php?id=<?= $pacote['id'] ?>" class="btn-card">
                            Ver detalhes <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </article>
            <?php endwhile; ?>
        </div>

        <div id="nacionais" class="section-title" style="margin-top: 50px;">
            <h3>Destaques <span>Nacionais</span></h3>
        </div>

        <div class="packages-grid">
            <?php
            $stmt = $pdo->prepare("SELECT * FROM pacotes WHERE destaque_tipo = :tipo ORDER BY id DESC");
            $stmt->execute([':tipo' => 'nacional']);
            
            while ($pacote = $stmt->fetch(PDO::FETCH_ASSOC)):
                // REPETE A LÓGICA DE IMAGEM
                $img = $pacote['imagem_capa'];
                if (strpos($img, 'http') === 0) {
                    $src = $img;
                } elseif (strpos($img, '/Arquivos/') === 0) {
                    $src = "." . $img; 
                } else {
                    $src = "uploads/" . $img;
                }
            ?>
                <article class="card">
                    <div class="card-img-wrapper">
                        <img src="<?= $src ?>" alt="<?= htmlspecialchars($pacote['titulo']) ?>" class="card-img" onerror="this.src='https://via.placeholder.com/400x300?text=Sem+Imagem'">
                    </div>
                    <div class="card-body">
                        <h4 class="card-title"><?= htmlspecialchars($pacote['titulo']) ?></h4>
                        <p class="card-text" style="color: #690707; font-weight: bold; font-size: 0.9rem;">
                            <?= htmlspecialchars($pacote['subtitulo']) ?>
                        </p>
                        <a href="detalhe.php?id=<?= $pacote['id'] ?>" class="btn-card">Ver detalhes <i class="fas fa-arrow-right"></i></a>
                    </div>
                </article>
            <?php endwhile; ?>
        </div>

    </main>

    <footer id="contato">
        <div class="container">
            <div class="footer-grid">
                <div class="footer-col">
                    <h3>Nosso Endereço</h3>
                    <p><i class="fas fa-map-marker-alt"></i> Av. Manoel Morais, 435<br>Manaíra - João Pessoa - PB</p>
                    <p><i class="fas fa-phone-alt"></i> (83) 9305-6906</p>
                </div>
                <div class="footer-col">
                    <h3>Links Rápidos</h3>
                    <a href="#">Sobre a Rogetur</a>
                    <a href="#internacionais">Internacionais</a>
                    <a href="#nacionais">Nacionais</a>
                </div>
                <div class="footer-col">
                    <h3>Newsletter</h3>
                    <form class="newsletter-form">
                        <input type="email" placeholder="Seu email">
                        <button type="button">OK</button>
                    </form>
                </div>
            </div>
            <div class="copyright">
                <p>&copy; 2025 Rogetur. Todos os direitos reservados.</p>
            </div>
        </div>
    </footer>

    <script>
        function toggleMenu() {
            document.getElementById('navLinks').classList.toggle('active');
        }
    </script>
</body>
</html>