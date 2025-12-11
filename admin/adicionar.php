<?php
require '../config.php';

// --- SEGURANÇA: Se não estiver logado, redireciona para o login ---
if (!isset($_SESSION['admin_logado'])) {
    header("Location: login.php");
    exit;
}

// Lógica de Logout (Sair)
if (isset($_GET['sair'])) {
    session_destroy();
    header("Location: login.php");
    exit;
}
// ------------------------------------------------------------------

$mensagem = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'];
    $subtitulo = $_POST['subtitulo'];
    $senha = $_POST['senha'];
    $tipo = $_POST['tipo'];
    
    // Preço agora é opcional (se vier vazio, salva vazio)
    $preco = isset($_POST['preco']) ? $_POST['preco'] : '';

    // Tratamento de upload de imagem (Continua OBRIGATÓRIO pela regra de negócio visual)
    if(isset($_FILES['imagem']) && $_FILES['imagem']['error'] == 0){
        $imagemNome = time() . '_' . $_FILES['imagem']['name'];
        if (!is_dir('../uploads')) { mkdir('../uploads', 0755, true); }
        move_uploaded_file($_FILES['imagem']['tmp_name'], '../uploads/' . $imagemNome);
    } else {
        $imagemNome = ''; 
    }

    // Tratamento de upload de PDF (AGORA OPCIONAL)
    if(isset($_FILES['pdf']) && $_FILES['pdf']['error'] == 0){
        $pdfNome = time() . '_' . $_FILES['pdf']['name'];
        if (!is_dir('../uploads')) { mkdir('../uploads', 0755, true); }
        move_uploaded_file($_FILES['pdf']['tmp_name'], '../uploads/' . $pdfNome);
    } else {
        $pdfNome = ''; // Salva vazio se não tiver PDF
    }

    // Salvar no Banco
    $sql = "INSERT INTO pacotes (titulo, subtitulo, imagem_capa, pdf_arquivo, senha_grupo, preco, destaque_tipo) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    
    if($stmt->execute([$titulo, $subtitulo, $imagemNome, $pdfNome, $senha, $preco, $tipo])) {
        $mensagem = "<div class='alert success'><i class='fas fa-check-circle'></i> Pacote cadastrado com sucesso!</div>";
    } else {
        $mensagem = "<div class='alert error'>Erro ao cadastrar pacote.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Cadastrar Pacote | Rogetur</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700;800&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary-color: #008fa1;
            --secondary-color: #2c3e50;
            --bg-light: #f4f6f8;
            --white: #ffffff;
            --shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            --border-radius: 8px;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Open Sans', sans-serif;
            background-color: var(--bg-light);
            color: var(--secondary-color);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .admin-container {
            background-color: var(--white);
            width: 100%;
            max-width: 600px;
            padding: 40px;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            margin-top: 20px;
            margin-bottom: 20px;
        }

        .header-form {
            text-align: center;
            margin-bottom: 30px;
        }

        .header-form h1 {
            font-family: 'Montserrat', sans-serif;
            color: var(--secondary-color);
            font-size: 1.8rem;
            margin-bottom: 5px;
        }

        .header-form span {
            color: var(--primary-color);
            font-weight: bold;
        }

        .current-user {
            text-align: center;
            font-size: 0.8rem;
            color: #666;
            margin-bottom: 20px;
            background: #eee;
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        label {
            font-weight: 600;
            font-size: 0.9rem;
            color: #555;
        }

        input[type="text"],
        select {
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 1rem;
            transition: border 0.3s ease;
            outline: none;
        }

        input[type="text"]:focus,
        select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(0, 143, 161, 0.1);
        }

        input[type="file"] {
            padding: 10px;
            border: 1px dashed #ccc;
            border-radius: 6px;
            background: #fafafa;
            width: 100%;
            cursor: pointer;
        }

        .btn-submit {
            background-color: var(--primary-color);
            color: white;
            padding: 15px;
            border: none;
            border-radius: 6px;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s ease;
            margin-top: 10px;
            font-family: 'Montserrat', sans-serif;
            text-transform: uppercase;
        }

        .btn-submit:hover {
            background-color: var(--secondary-color);
        }

        .alert {
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-weight: 600;
        }

        .alert.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .footer-links {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 30px;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }

        .back-link {
            color: #666;
            text-decoration: none;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .back-link:hover {
            color: var(--primary-color);
        }

        .logout-link {
            color: #e74c3c;
            text-decoration: none;
            font-weight: bold;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .logout-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="admin-container">
        <div class="header-form">
            <h1>Rogetur<span>.</span> Admin</h1>
            <p>Painel de Controle</p>
            <div class="current-user">
                <i class="fas fa-user-circle"></i> Logado como: <strong><?= $_SESSION['admin_nome'] ?? 'Admin' ?></strong>
            </div>
        </div>

        <?= $mensagem ?>

        <form method="POST" enctype="multipart/form-data">
            
            <div class="form-group">
                <label>Título do Pacote</label>
                <input type="text" name="titulo" placeholder="Ex: Grupo França e Itália" required>
            </div>

            <div class="form-group">
                <label>Subtítulo / Data</label>
                <input type="text" name="subtitulo" placeholder="Ex: Maio 2026 - Sucesso de Vendas" required>
            </div>

            <div class="form-group">
                <label>Preço / Chamada <small>(Opcional)</small></label>
                <input type="text" name="preco" placeholder="Ex: A partir de R$ 5.000 ou 10x Sem Juros">
            </div>

            <div class="form-group">
                <label>Categoria do Destaque</label>
                <select name="tipo">
                    <option value="internacional">Destaque Internacional</option>
                    <option value="nacional">Destaque Nacional</option>
                    <option value="cruzeiro">Cruzeiro</option>
                    <option value="jovem">Roteiro Jovem</option>
                </select>
            </div>

            <div class="form-group">
                <label>Senha da Área Restrita <small>(Opcional)</small></label>
                <input type="text" name="senha" placeholder="Crie uma senha para os passageiros">
            </div>

            <div class="form-group">
                <label><i class="fas fa-image"></i> Imagem de Capa</label>
                <input type="file" name="imagem" accept="image/*" required>
            </div>

            <div class="form-group">
                <label><i class="fas fa-file-pdf"></i> Roteiro PDF <small>(Opcional)</small></label>
                <input type="file" name="pdf" accept="application/pdf">
            </div>

            <button type="submit" class="btn-submit">Cadastrar Pacote</button>
        </form>

        <div class="footer-links">
            <a href="../index.php" class="back-link"><i class="fas fa-arrow-left"></i> Voltar para o Site</a>
            <a href="adicionar.php?sair=true" class="logout-link"><i class="fas fa-sign-out-alt"></i> Sair</a>
        </div>
    </div>

</body>
</html>