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
    <title>Adicionar categoria</title>
</head>

<?php
$result = false;
$error = false;
if ($_POST) {
    try {
        $name = $_POST["name"];
        $description = $_POST["description"];

        $query = "INSERT INTO categories (
            name, 
            description
        ) VALUES (
            '$name',
            '$description'
        )";
        
        $result = $conn->query($query);
        $conn->close();

        if ($result) {
            header('Location: index.php?message=Categoria inserida com sucesso!');
            die();
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}
?>

<body>

    <?php
        readFile("../_partials/navbar.html");
    ?>

    <section class="container mt-5 mb-5">

        <?php if ($_POST && (!$result || $error)) : ?>
            <p>
                Erro ao salvar a nova categoria.
                <?= $error ? $error : "Erro desconhecido." ?>
            </p>
        <?php endif; ?>

        <div class="row mb-3">
            <div class="col">
                <h1>Adicionar categoria</h1>
            </div>
        </div>

        <form action="" method="post">

            <div class="mb-3">
                <label for="name" class="form-label">Nome</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Nome da categoria">
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Descrição</label>
                <textarea type="text" class="form-control" id="description" name="description"></textarea>
            </div>

            <a href="index.php" class="btn btn-danger">Cancelar</a>
            <button type="submit" class="btn btn-success">Salvar</button>

        </form>
    </section>

</body>

</html>