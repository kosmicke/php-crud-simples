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
    <title>Editar categoria</title>
</head>

<?php
$category = false;
$error = false;

if (!$_GET || !isset($_GET["id"])) {
    header('Location: index.php?message=Id da categoria não informado!');
    die();
}

$categoryId = $_GET["id"];

try {
    $query = "SELECT * FROM categories WHERE id=$categoryId";
    $result = $conn->query($query);
    $category = $result->fetch_assoc();
    $result->close();
} catch (Exception $e) {
    $error = $e->getMessage();
}

if (!$category || $error) {
    header('Location: index.php?message=Erro ao recuperar dados da categoria!');
    die();
}

$upadeError = false;
$updateResult = false;
if ($_POST) {
    try {
        $name = $_POST["name"];
        $description = $_POST["description"];

        $query = "UPDATE categories SET 
            name='$name', 
            description='$description'
        WHERE 
            id=$categoryId
        ";

        $updateResult = $conn->query($query);

        if ($updateResult) {
            header('Location: index.php?message=Categoria alterada com sucesso!');
            die();
        }
    } catch (Exception $e) {
        $upadeError = $e->getMessage();
    }
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
                Erro ao alterar a categoria.
                <?= $error ? $error : "Erro desconhecido." ?>
            </p>
        <?php endif; ?>

        <div class="row mb-3">
            <div class="col">
                <h1>Editar Categoria</h1>
            </div>
        </div>

        <form action="" method="post">

            <div class="mb-3">
                <label for="name" class="form-label">Nome</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Nome do produto" value="<?= $category["name"] ?>">
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Descrição</label>
                <textarea type="text" class="form-control" id="description" name="description"><?= $category["description"] ?></textarea>
            </div>

            <a href="index.php" class="btn btn-danger">Cancelar</a>
            <button type="submit" class="btn btn-success">Salvar</button>

        </form>
    </section>

</body>

</html>