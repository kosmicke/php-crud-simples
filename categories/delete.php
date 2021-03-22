<?php  
	require("../_config/connection.php");

    $error = false;

    if(!$_GET || !$_GET["id"]){
        header('Location: index.php?message=Id da categoria não informado!');
        die();
    }

    $categoryId = $_GET["id"];

    try {
        $query = "DELETE FROM categoria WHERE id=$categoryId";
		$result = $conn->query($query);
        $conn->close();
    } catch (Exception $e) {
        $error = $e->getMessage();
    }

    $message = ($result && !$error) ? "Categoria excluida com sucesso." : "Erro ao excluir a categoria.";
    header("Location: index.php?message=$message");
    die();

