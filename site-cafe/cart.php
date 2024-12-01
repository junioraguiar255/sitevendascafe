<?php
session_start();

include 'db.php';

// Verifica se o carrinho está vazio
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Se o usuário adicionou um item ao carrinho
if (isset($_GET['add'])) {
    $id_produto = $_GET['add'];
    $quantidade = 1; // Quantidade padrão ao adicionar um item

    // Verifica se o item já existe no carrinho
    $item_found = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id_produto'] == $id_produto) {
            $item['quantidade'] += $quantidade; // Incrementa a quantidade
            $item_found = true;
            break;
        }
    }

    // Se o item não foi encontrado, adiciona um novo item ao carrinho
    if (!$item_found) {
        $_SESSION['cart'][] = ['id_produto' => $id_produto, 'quantidade' => $quantidade];
    }
}

$cart = $_SESSION['cart'];
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrinho de Compras</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Carrinho de Compras</h1>
        <nav>
            <a href="index.php">Início</a>
            <a href="checkout.php">Finalizar Compra</a>
        </nav>
    </header>
    <main>
        <h2>Itens no Carrinho</h2>
        <table>
            <tr>
                <th>Produto</th>
                <th>Quantidade</th>
                <th>Preço</th>
            </tr>
            <?php
            $total = 0;
            foreach ($cart as $item) {
                $id_produto = $item['id_produto'];
                $quantidade = $item['quantidade'];
                
                $sql = "SELECT * FROM produtos WHERE id = $id_produto";
                $result = $conn->query($sql);
                $produto = $result->fetch_assoc();
                
                $preco_total = $produto['preco'] * $quantidade;
                $total += $preco_total;
                
                echo "<tr>
                        <td>{$produto['nome']}</td>
                        <td>{$quantidade}</td>
                        <td>R$ " . number_format($preco_total, 2, ',', '.') . "</td>
                    </tr>";
            }
            ?>
        </table>
        <p>Total: R$ <?= number_format($total, 2, ',', '.'); ?></p>
    </main>
</body>
</html>

<?php $conn->close(); ?>
