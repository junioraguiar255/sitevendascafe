<?php
// Conexão com o banco de dados
include('db.php');

// Verifica se o ID do pedido foi passado na URL
if (isset($_GET['pedido_id'])) {
    $pedido_id = $_GET['pedido_id'];
    
    // Consulta os detalhes do pedido
    $sql = "SELECT * FROM pedidos WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $pedido_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Verifica se o pedido foi encontrado
    if ($result->num_rows > 0) {
        $pedido = $result->fetch_assoc();
    } else {
        $pedido = null;
    }
} else {
    $pedido = null;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmação de Compra - Loja de Café Gourmet</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<header>
    <h1>Confirmação de Compra</h1>
</header>

<header>
        <nav>
            <a href="index.php">Início</a>
            <a href="cart.php">Carrinho</a>
            <a href="checkout.php">Finalizar Compra</a>
        </nav>
    </header>

<main>
    <!-- Seção de confirmação de pedido -->
    <section class="confirmacao">
        <?php if ($pedido): ?>
            <h2>Compra Realizada com Sucesso!</h2>
            <p>Seu pedido foi registrado com o número: <strong><?php echo $pedido['id']; ?></strong></p>
            <p><strong>Nome:</strong> <?php echo $pedido['nome_cliente']; ?></p>
            <p><strong>E-mail:</strong> <?php echo $pedido['email_cliente']; ?></p>
            <p><strong>Endereço de Entrega:</strong> <?php echo $pedido['endereco_cliente']; ?></p>
            <p><strong>Método de Pagamento:</strong> <?php echo ucfirst($pedido['metodo_pagamento']); ?></p>
            <p><strong>Método de Entrega:</strong> <?php echo ucfirst($pedido['metodo_entrega']); ?></p>
            <p><strong>Total da Compra:</strong> R$ <?php echo number_format($pedido['total'], 2, ',', '.'); ?></p>
        <?php else: ?>
            <p>Pedido não encontrado. Tente novamente mais tarde.</p>
        <?php endif; ?>
    </section>

    <!-- Seção de recomendações -->
    <section class="recomendacoes">
        <h3>Recomendações de Produtos</h3>
        <p>Confira nossos outros produtos e aproveite para fazer mais compras!</p>
        <!-- Aqui você pode adicionar produtos relacionados ou outras promoções -->
        <ul>
            <li><a href="#">Café Gourmet Mocha</a></li>
            <li><a href="#">Café Gourmet Expresso</a></li>
            <li><a href="#">Café Gourmet Latte</a></li>
        </ul>
    </section>
</main>

<footer>
    <p>&copy; 2024 Loja de Café Gourmet</p>
</footer>

</body>
</html>
