<?php
session_start();

// Verifica se o carrinho já existe na sessão
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if (isset($_POST['id_produto']) && isset($_POST['quantidade'])) {
    $id_produto = $_POST['id_produto'];
    $quantidade = $_POST['quantidade'];

    // Verifica se o produto já está no carrinho
    $produto_ja_no_carrinho = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id_produto'] == $id_produto) {
            $item['quantidade'] += $quantidade;
            $produto_ja_no_carrinho = true;
            break;
        }
    }

    // Se o produto não está no carrinho, adiciona ele
    if (!$produto_ja_no_carrinho) {
        $_SESSION['cart'][] = [
            'id_produto' => $id_produto,
            'quantidade' => $quantidade
        ];
    }
}

header('Location: cart.php');
exit();
?>
