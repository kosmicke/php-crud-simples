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
    <title>Adicionar produto</title>
</head>

<?php
$result = false;
$error = false;


if ($_POST) {
    try {

        $image = $_POST["image"];
        $name = $_POST["name"];
        $description = $_POST["description"];
        $quantity = $_POST["quantity"];
        $category_id = $_POST["category_id"];

        $query = "INSERT INTO products (
            name, 
            description, 
            quantity, 
            image,
            category_id
        ) VALUES (
            '$name',
            '$description', 
            $quantity, 
            '$image',
            $category_id
        )";

        $result = $conn->query($query);
        $conn->close();

        if ($result) {
            header('Location: index.php?message=Produto inserido com sucesso!');
            die();
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
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

        <?php if ($_POST && (!$result || $error)) : ?>
            <p>
                Erro salvar o novo produto.
                <?= $error ? $error : "Erro desconhecido." ?>
            </p>
        <?php endif; ?>

        <div class="row mb-3">
            <div class="col">
                <h1>Adicionar produto</h1>
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
                            <option value="<?=$category["id"]?>">
                                <?=$category["name"]?>
                            </option>
                        <?php endwhile; ?>
                        
                        <?php $categoryResult->close(); ?>
                </select>
            </div>

            <div class="mb-3">
                <label for="image" class="form-label">Imagem</label>
                <input type="text" 
                    class="form-control" 
                    id="image" 
                    name="image" 
                    placeholder="Url da imagem do produto"
                    required>
            </div>

            <div class="mb-3">
                <label for="name" class="form-label">Nome</label>
                <input type="text" 
                    class="form-control" 
                    id="name" 
                    name="name" 
                    placeholder="Nome do produto"
                    required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Descrição</label>
                <textarea 
                    type="text" 
                    class="form-control" 
                    id="description" 
                    name="description"
                    required>
                </textarea>
            </div>

            <div class="mb-3">
                <label for="quantity" class="form-label">Quantidade</label>
                <input 
                    type="number" 
                    class="form-control" 
                    id="quantity" 
                    name="quantity" 
                    min="0" 
                    max="9999" 
                    placeholder="Quantidade no estoque"
                    required>
            </div>

            <a href="index.php" class="btn btn-danger">Cancelar</a>
            <button type="submit" class="btn btn-success">Salvar</button>

        </form>
    </section>

</body>

</html>