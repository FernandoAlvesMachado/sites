<?php
require '../config.php';

$erro = '';

// Se já estiver logado, manda direto pro adicionar
if (isset($_SESSION['admin_logado'])) {
    header("Location: adicionar.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Busca o usuário no banco
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verifica se usuário existe E se a senha bate com a criptografia
    if ($usuario && password_verify($senha, $usuario['senha'])) {
        $_SESSION['admin_logado'] = true;
        $_SESSION['admin_nome'] = $usuario['email'];
        header("Location: adicionar.php");
        exit;
    } else {
        $erro = "E-mail ou senha incorretos!";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin | Rogetur</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Open Sans', sans-serif;
            background-color: #f4f6f8;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }
        .login-card {
            background: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 350px;
            text-align: center;
        }
        h2 { font-family: 'Montserrat', sans-serif; color: #2c3e50; margin-bottom: 20px; }
        input {
            width: 100%;
            padding: 12px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            width: 100%;
            padding: 12px;
            background-color: #008fa1;
            color: white;
            border: none;
            border-radius: 4px;
            font-weight: bold;
            cursor: pointer;
        }
        button:hover { background-color: #2c3e50; }
        .error { color: #e74c3c; margin-bottom: 15px; font-size: 0.9rem; }
        .back { display: block; margin-top: 15px; font-size: 0.8rem; color: #666; text-decoration: none; }
    </style>
</head>
<body>
    <div class="login-card">
        <h2>Área Restrita</h2>
        <?php if($erro): ?>
            <div class="error"><?= $erro ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <input type="email" name="email" placeholder="E-mail" required>
            <input type="password" name="senha" placeholder="Senha" required>
            <button type="submit">Entrar</button>
        </form>
        <a href="../index.php" class="back">← Voltar para o site</a>
    </div>
</body>
</html>