<?php
include 'db.php';

$id_produto = $_GET['id'];

// Consulta para obter detalhes do produto
$sql = "SELECT * FROM produtos WHERE id = $id_produto";
$result = $conn->query($sql);
$product = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personalizar Pack - <?= $product['nome']; ?></title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Personalize seu Pack de Café</h1>
        <nav>
            <a href="index.php">Início</a>
            <a href="cart.php">Carrinho</a>
        </nav>
    </header>
    <main>
        <h2><?= $product['nome']; ?></h2>
        <img src="images/<?= $product['imagem']; ?>" alt="<?= $product['nome']; ?>">
        <p><?= $product['descricao']; ?></p>
        <p>Preço: R$ <?= number_format($product['preco'], 2, ',', '.'); ?></p>
        
        <form action="add_to_cart.php" method="POST">
            <label for="quantidade">Quantidade:</label>
            <input type="number" name="quantidade" min="1" value="1" required>
            <input type="hidden" name="id_produto" value="<?= $product['id']; ?>">
            <button type="submit">Adicionar ao Carrinho</button>
        </form>
    </main>
</body>
</html>

<?php $conn->close(); ?>
