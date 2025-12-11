<?php
// config.php
session_start();

try {
    $pdo = new PDO('sqlite:' . __DIR__ . '/banco.sqlite');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 1. Cria tabela Pacotes
    $pdo->exec("CREATE TABLE IF NOT EXISTS pacotes (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        titulo TEXT,
        subtitulo TEXT,
        imagem_capa TEXT,
        pdf_arquivo TEXT,
        senha_grupo TEXT,
        preco TEXT,
        destaque_tipo TEXT,
        descricao TEXT
    )");

    // 2. Cria tabela Usuários
    $pdo->exec("CREATE TABLE IF NOT EXISTS usuarios (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        email TEXT UNIQUE,
        senha TEXT
    )");

    // 3. Cria usuário Admin padrão se não existir
    $checkUser = $pdo->query("SELECT COUNT(*) FROM usuarios")->fetchColumn();
    if ($checkUser == 0) {
        $senha = password_hash('admin123', PASSWORD_DEFAULT);
        $pdo->exec("INSERT INTO usuarios (email, senha) VALUES ('admin@rogetur.com', '$senha')");
    }

    // 4. POPULA O BANCO COM OS DADOS ANTIGOS (Só roda se a tabela estiver vazia)
    $checkPacotes = $pdo->query("SELECT COUNT(*) FROM pacotes")->fetchColumn();
    
    if ($checkPacotes == 0) {
        // Lista extraída do seu HTML antigo
        $pacotesAntigos = [
            // INTERNACIONAIS
            [10180, 'GRUPO FRANÇA E ITÁLIA', 'Maio 2026 Sucesso de vendas', '/Arquivos/F  I site 2.jpg', 'internacional'],
            [2042, 'BUENOS AIRES', 'Pacote aéreo+hotel a partir R$', '/Arquivos/Buenos Aires site.jpg', 'internacional'],
            [2044, 'GRUPO LESTE EUROPEU', 'Junho 2026 Sucesso de vendas', '/Arquivos/Leste site 2.jpg', 'internacional'],
            [10156, 'SANTIAGO DO CHILE', 'Aéreo + hotel', '/Arquivos/santiago site.jpg', 'internacional'],
            [11280, 'TURQUIA', 'Saídas mensais', '/Arquivos/Turquia site 1.jpg', 'internacional'],
            [6037, 'MONTEVIDEU E PUNTA DEL ESTE', 'Pacote 5 dias', '/Arquivos/uruguai site.jpg', 'internacional'],
            [6038, 'BUENOS AIRES E SANTIAGO', 'Promoção imperdível', '/Arquivos/Buenos e SCL site.jpg', 'internacional'],
            [9, 'LAGOS ANDINOS', 'Santiago - Puerto Mont - Bariloche - Buenos Aires', '/Arquivos/Lagos Andinos site.jpg', 'internacional'],
            [10210, 'RUSSIA, ESCANDINAVIA E FIORDES', 'Saída setembro 2026', '/Arquivos/Russia site.jpg', 'internacional'],
            [11230, 'ESPANHA E PORTUGAL', 'Grupo saída setembro 25', '/Arquivos/POR e ESP site.jpg', 'internacional'],
            [11301, 'PORTUGAL', 'Reveillon 25/26 - mega promoção', '/Arquivos/Portugal site 2.jpg', 'internacional'],
            [11319, 'TERRA SANTA 2026', 'Israel e Itália', '/Arquivos/Terra santa site 1.1.jpg', 'internacional'],
            
            // NACIONAIS
            [11318, 'NATAL LUZ GRAMADO - Mega promoção', 'Saídas outubro/2025 a janeiro/2026', '/Arquivos/Natal luz 2.jpg', 'nacional'],
            [11317, 'APARECIDA DO NORTE', 'Grupo Rogetur - 19 a 24/03/26', '/Arquivos/Aparecida site.jpg', 'nacional'],
            [10145, 'CRUZEIROS', 'Temporada America do Sul 2025/2026', '/Arquivos/Navio site.jpg', 'nacional'],
            [11327, 'GRAMADO', 'Promoção', '/Arquivos/Gramado site 2.jpg', 'nacional'],
            [2041, 'FOZ DO IGUAÇU', 'Mega Promoção', '/Arquivos/Foz site.jpg', 'nacional'],
            [11299, 'CIRCUITO MINEIRO', 'Saída Diárias', '/Arquivos/Minas site.jpg', 'nacional'],
            [10085, 'SANTA CATARINA COM BETO CARRERO', 'Pacote 5 dias', '/Arquivos/Santa Catarina site.jpg', 'nacional'],
            [11331, 'Passagens Aérea', 'Melhor preço é aqui.', '/Arquivos/Aereo site.jpg', 'nacional']
        ];

        $insert = $pdo->prepare("INSERT INTO pacotes (id, titulo, subtitulo, imagem_capa, destaque_tipo) VALUES (?, ?, ?, ?, ?)");
        
        foreach ($pacotesAntigos as $pct) {
            // Tenta inserir mantendo o ID original
            try {
                $insert->execute($pct);
            } catch (Exception $ex) {
                // Se der erro de ID duplicado, ignora e continua
                continue;
            }
        }
    }

} catch (PDOException $e) {
    echo "Erro no banco: " . $e->getMessage();
    exit;
}
?>