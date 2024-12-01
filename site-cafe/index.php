<?php
include 'db.php';

// Consulta para listar os produtos
$sql = "SELECT * FROM produtos";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loja Virtual de Café Gourmet</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Loja Virtual de Café Gourmet</h1>
        <nav>
            <a href="index.php">Início</a>
            <a href="cart.php">Carrinho</a>
            <a href="checkout.php">Finalizar Compra</a>
        </nav>
    </header>
    <main>
        <h2>Escolha seu Café Gourmet</h2>
        <div class="products">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="product">
                    <img src="images/cafe.webp">
                    <h3><?= $row['nome']; ?></h3>
                    <p><?= $row['descricao']; ?></p>
                    <p>R$ <?= number_format($row['preco'], 2, ',', '.'); ?></p>
                    <a href="product.php?id=<?= $row['id']; ?>">Personalizar Pack</a>
                </div>
            <?php endwhile; ?>
        </div>
    </main>
</body>
</html>

<?php $conn->close(); ?>
