<?php
session_start();

include 'db.php';

// Verifica se o carrinho está vazio
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header('Location: cart.php'); // Redireciona o usuário para o carrinho se estiver vazio
    exit;
}

$cart = $_SESSION['cart'];

// Calcula o total do carrinho
$total = 0;
foreach ($cart as $item) {
    $id_produto = $item['id_produto'];
    $quantidade = $item['quantidade'];
    
    // Verifica se o produto existe no banco de dados
    $sql = "SELECT * FROM produtos WHERE id = $id_produto";
    $result = $conn->query($sql);
    $produto = $result->fetch_assoc();

    if ($produto) { // Verifica se o produto foi encontrado
        $preco_total = $produto['preco'] * $quantidade;
        $total += $preco_total;
    } else {
        // Se o produto não for encontrado, exibe uma mensagem ou faz outro tratamento
        echo "Produto não encontrado. ID: $id_produto";
        exit; // Finaliza o script, pois não podemos continuar sem os dados do produto
    }
}

// Processa o pedido
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Coleta os dados do formulário
    $nome_cliente = $_POST['nome'];
    $email_cliente = $_POST['email'];
    $endereco_cliente = $_POST['endereco'];
    $metodo_pagamento = $_POST['metodo_pagamento'];
    $metodo_entrega = $_POST['metodo_entrega'];

    // Inicia transação (com MySQLi)
    $conn->begin_transaction();

    try {
        // Inserir os dados do pedido
        $sql = "INSERT INTO pedidos (nome_cliente, email_cliente, endereco_cliente, metodo_pagamento, metodo_entrega, total) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sssssd', $nome_cliente, $email_cliente, $endereco_cliente, $metodo_pagamento, $metodo_entrega, $total);
        $stmt->execute();

        // Pega o id do pedido recém-criado
        $pedido_id = $conn->insert_id;

        // Inserir os itens do carrinho na tabela 'itens_pedido'
        $sql_item = "INSERT INTO itens_pedido (pedido_id, produto_nome, quantidade, preco, total_item) 
                     VALUES (?, ?, ?, ?, ?)";
        $stmt_item = $conn->prepare($sql_item);
        foreach ($cart as $item) {
            // Verifica novamente se o produto existe antes de inserir no banco
            $id_produto = $item['id_produto'];
            $quantidade = $item['quantidade'];
            
            $sql_produto = "SELECT * FROM produtos WHERE id = $id_produto";
            $result_produto = $conn->query($sql_produto);
            $produto = $result_produto->fetch_assoc();

            if ($produto) {
                $produto_nome = $produto['nome'];
                $preco = $produto['preco'];
                $total_item = $preco * $quantidade;
                $stmt_item->bind_param('isdds', $pedido_id, $produto_nome, $quantidade, $preco, $total_item);
                $stmt_item->execute();
            } else {
                // Se o produto não for encontrado, mostra um erro ou tratamento
                echo "Erro ao inserir produto no pedido. Produto não encontrado.";
                exit;
            }
        }

        // Comita a transação
        $conn->commit();

        // Limpa o carrinho da sessão após a compra
        $_SESSION['cart'] = [];

        // Redireciona para a página de confirmação
        header("Location: confirmacao.php?pedido_id=$pedido_id");
        exit;
    } catch (Exception $e) {
        // Em caso de erro, faz rollback na transação
        $conn->rollback();
        echo "Erro: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Loja de Café Gourmet</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<header>
    <h1>Finalizar Compra</h1>
</header>

<header>
    <nav>
        <a href="index.php">Início</a>
        <a href="cart.php">Carrinho</a>
        <a href="checkout.php">Finalizar Compra</a>
    </nav>
</header>

<main>
    <!-- Seção de produtos no carrinho -->
    <section class="carrinho">
        <h2>Itens no Carrinho</h2>
        <table>
            <thead>
                <tr>
                    <th>Produto</th>
                    <th>Quantidade</th>
                    <th>Preço</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($cart as $item): ?>
                    <?php
                    $id_produto = $item['id_produto'];
                    $quantidade = $item['quantidade'];
                    
                    // Consulta o produto no banco de dados
                    $sql = "SELECT * FROM produtos WHERE id = $id_produto";
                    $result = $conn->query($sql);
                    $produto = $result->fetch_assoc();

                    // Verifica se o produto foi encontrado
                    if ($produto) {
                        $preco_total = $produto['preco'] * $quantidade;
                    ?>
                    <tr>
                        <td><?php echo $produto['nome']; ?></td>
                        <td><?php echo $quantidade; ?></td>
                        <td>R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></td>
                        <td>R$ <?php echo number_format($preco_total, 2, ',', '.'); ?></td>
                    </tr>
                    <?php
                    } else {
                        echo "<tr><td colspan='4'>Produto não encontrado</td></tr>";
                    }
                    ?>
                <?php endforeach; ?>
            </tbody>
        </table>
        <p>Total: R$ <?= number_format($total, 2, ',', '.'); ?></p>
    </section>

    <form method="POST" action="checkout.php">
        <h3>Dados de Entrega</h3>
        <label for="nome">Nome:</label>
        <input type="text" id="nome" name="nome" required>
        <label for="email">E-mail:</label>
        <input type="email" id="email" name="email" required>
        <label for="endereco">Endereço:</label>
        <input type="text" id="endereco" name="endereco" required>
        
        <h3>Pagamento</h3>
        <label for="metodo_pagamento">Método de Pagamento:</label>
        <select name="metodo_pagamento" id="metodo_pagamento">
            <option value="cartao">Cartão de Crédito</option>
            <option value="boleto">Boleto Bancário</option>
        </select>

        <h3>Entrega</h3>
        <label for="metodo_entrega">Método de Entrega:</label>
        <select name="metodo_entrega" id="metodo_entrega">
            <option value="normal">Entrega Normal</option>
            <option value="expresso">Entrega Expresso</option>
        </select>

        <button type="submit">Finalizar Compra</button>
    </form>
</main>

</body>
</html>
<?php $conn->close(); ?>
