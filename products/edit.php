<?php
require("../_config/connection.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
    <title>Editar produto</title>
</head>

<?php
$product = false;
$error = false;

if (!$_GET || !isset($_GET["id"])) {
    header('Location: index.php?message=Id do produto não informado!');
    die();
}

$productId = $_GET["id"];

try {
    $query = "SELECT * FROM products WHERE id=$productId";
    $result = $conn->query($query);
    $product = $result->fetch_assoc();
    $result->close();
} catch (Exception $e) {
    $error = $e->getMessage();
}

if (!$product || $error) {
    header('Location: index.php?message=Erro ao recuperar dados do produto!');
    die();
}

$upadeError = false;
$updateResult = false;
if ($_POST) {
    try {
        $image = $_POST["image"];
        $name = $_POST["name"];
        $description = $_POST["description"];
        $quantity = $_POST["quantity"];
        $category_id = $_POST["category_id"];

        $query = "UPDATE products SET 
            name='$name', 
            description='$description', 
            quantity=$quantity, 
            image='$image',
            category_id=$category_id
        WHERE 
            id=$productId";

        $updateResult = $conn->query($query);

        if ($updateResult) {
            header('Location: index.php?message=Produto alterado com sucesso!');
            die();
        }
    } catch (Exception $e) {
        $upadeError = $e->getMessage();
    }
}

try {
    $categoryQuery = "SELECT * from categories";
    $categoryResult = $conn->query($categoryQuery);
} catch (Exception $e) {
    header('Location: index.php?message=Erro ao recuperar categorias!');
    die();
}

$conn->close();

?>

<body>

    <?php
    readFile("../_partials/navbar.html");
    ?>

    <section class="container mt-5 mb-5">

        <?php if ($_POST && (!$updateResult || $upadeError)) : ?>
            <p>
                Erro ao alterar o produto.
                <?= $error ? $error : "Erro desconhecido." ?>
            </p>
        <?php endif; ?>

        <div class="row mb-3">
            <div class="col">
                <h1>Editar produto</h1>
            </div>
        </div>

        <form action="" method="post">

            <div class="mb-3">
                <label for="category_id" class="form-label">Categoria</label>
                <select 
                    class="form-control" 
                    id="category_id" 
                    name="category_id"
                    required>
                        <option value></option>

                        <?php while($category = $categoryResult->fetch_assoc()): ?>
                            <option 
                                value="<?=$category["id"]?>"
                                <?= $category["id"] == $product["category_id"] ? 'selected' : '';?>
                                >
                                <?=$category["name"]?>
                            </option>
                        <?php endwhile; ?>
                        
                        <?php $categoryResult->close(); ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="image" class="form-label">imagem</label>
                <input type="text" class="form-control" id="image" name="image" placeholder="Url da imagem do produto" value="<?= $product["image"] ?>">
            </div>

            <div class="mb-3">
                <label for="name" class="form-label">Nome</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Nome do produto" value="<?= $product["name"] ?>">
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Descrição</label>
                <textarea type="text" class="form-control" id="description" name="description"><?= $product["description"] ?></textarea>
            </div>

            <div class="mb-3">
                <label for="quantity" class="form-label">Quantidade</label>
                <input type="number" class="form-control" id="quantity" name="quantity" min="0" max="9999" placeholder="Quantidade no estoque" value="<?= $product["quantity"] ?>">
            </div>

            <a href="index.php" class="btn btn-danger">Cancelar</a>
            <button type="submit" class="btn btn-success">Salvar</button>

        </form>
    </section>

</body>

</html>