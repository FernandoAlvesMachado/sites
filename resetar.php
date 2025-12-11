<?php
// resetar.php
// ESTE ARQUIVO LIMPA O BANCO E RECADDSTRA OS PACOTES DA ROGETUR

try {
    $pdo = new PDO('sqlite:' . __DIR__ . '/banco.sqlite');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 1. LIMPAR DADOS ANTIGOS (Apaga tudo da tabela pacotes)
    $pdo->exec("DELETE FROM pacotes");
    
    // Zera o contador de IDs para começar do 1 (opcional, mas bom para organizar)
    $pdo->exec("DELETE FROM sqlite_sequence WHERE name='pacotes'");

    echo "<h3>1. Banco de dados limpo com sucesso.</h3>";

    // 2. LISTA DE PACOTES OFICIAIS (Dados extraídos do seu site antigo)
    $pacotes = [
        // INTERNACIONAIS
        [10180, 'GRUPO FRANÇA E ITÁLIA', 'Maio 2026 Sucesso de vendas', '/Arquivos/F  I site 2.jpg', 'internacional'],
        [2042, 'BUENOS AIRES', 'Pacote aéreo+hotel a partir R$', '/Arquivos/Buenos Aires site.jpg', 'internacional'],
        [2044, 'GRUPO LESTE EUROPEU', 'Junho 2026 Sucesso de vendas', '/Arquivos/Leste site 2.jpg', 'internacional'],
        [10156, 'SANTIAGO DO CHILE', 'Aéreo + hotel', '/Arquivos/santiago site.jpg', 'internacional'],
        [11280, 'TURQUIA', 'Saídas mensais', '/Arquivos/Turquia site 1.jpg', 'internacional'],
        [6037, 'MONTEVIDEU E PUNTA DEL ESTE', 'Pacote 5 dias', '/Arquivos/uruguai site.jpg', 'internacional'],
        [6038, 'BUENOS AIRES E SANTIAGO', 'Promoção imperdível', '/Arquivos/Buenos e SCL site.jpg', 'internacional'],
        [9, 'LAGOS ANDINOS', 'Travessia Andina', '/Arquivos/Lagos Andinos site.jpg', 'internacional'],
        [10210, 'RUSSIA, ESCANDINAVIA E FIORDES', 'Saída setembro 2026', '/Arquivos/Russia site.jpg', 'internacional'],
        [11230, 'ESPANHA E PORTUGAL', 'Grupo saída setembro 25', '/Arquivos/POR e ESP site.jpg', 'internacional'],
        [11301, 'PORTUGAL', 'Reveillon 25/26 - mega promoção', '/Arquivos/Portugal site 2.jpg', 'internacional'],
        [11319, 'TERRA SANTA 2026', 'Israel e Itália', '/Arquivos/Terra santa site 1.1.jpg', 'internacional'],
        
        // NACIONAIS
        [11318, 'NATAL LUZ GRAMADO', 'Saídas outubro/2025 a janeiro/2026', '/Arquivos/Natal luz 2.jpg', 'nacional'],
        [11317, 'APARECIDA DO NORTE', 'Grupo Rogetur - 19 a 24/03/26', '/Arquivos/Aparecida site.jpg', 'nacional'],
        [10145, 'CRUZEIROS', 'Temporada America do Sul 2025/2026', '/Arquivos/Navio site.jpg', 'nacional'],
        [11327, 'GRAMADO', 'Promoção Serras Gaúchas', '/Arquivos/Gramado site 2.jpg', 'nacional'],
        [2041, 'FOZ DO IGUAÇU', 'Mega Promoção', '/Arquivos/Foz site.jpg', 'nacional'],
        [11299, 'CIRCUITO MINEIRO', 'Saída Diárias', '/Arquivos/Minas site.jpg', 'nacional'],
        [10085, 'SANTA CATARINA E BETO CARRERO', 'Pacote 5 dias', '/Arquivos/Santa Catarina site.jpg', 'nacional'],
        [11331, 'PASSAGENS AÉREAS', 'Melhor preço é aqui.', '/Arquivos/Aereo site.jpg', 'nacional']
    ];

    // 3. INSERIR NO BANCO
    $sql = "INSERT INTO pacotes (id, titulo, subtitulo, imagem_capa, destaque_tipo) VALUES (?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);

    echo "<h3>2. Inserindo pacotes...</h3><ul>";
    
    foreach ($pacotes as $pct) {
        // Tenta inserir. Se o ID der erro, ele continua.
        try {
            $stmt->execute($pct);
            echo "<li style='color:green'>Inserido: {$pct[1]}</li>";
        } catch (Exception $e) {
            echo "<li style='color:red'>Erro ao inserir {$pct[1]}: " . $e->getMessage() . "</li>";
        }
    }
    echo "</ul>";
    echo "<h2>Tudo pronto! <a href='index.php'>Voltar para o Site</a></h2>";

} catch (PDOException $e) {
    echo "Erro fatal: " . $e->getMessage();
}
?>