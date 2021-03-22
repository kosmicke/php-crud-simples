<?php  
	require("../_config/connection.php");

    $error = false;

    if(!$_GET || !$_GET["id"]){
        header('Location: index.php?message=Id do produto não informado!');
        die();
    }

    $productId = $_GET["id"];

    try {
        $query = "DELETE FROM products WHERE id=$productId";
		$result = $conn->query($query);
        $conn->close();
    } catch (Exception $e) {
        $error = $e->getMessage();
    }

    $message = ($result && !$error) ? "Produto excluido com sucesso." : "Erro ao excluir o produto.";
    header("Location: index.php?message=$message");
    die();

