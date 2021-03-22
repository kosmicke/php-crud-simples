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
    <title>Visualizar produto</title>
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
    $query = "SELECT p.*, c.name as category 
        FROM products p
        INNER JOIN categories c on p.category_id = c.id
        WHERE p.id=$productId";

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

$conn->close();

?>

<body>

    <?php
    readFile("../_partials/navbar.html");
    ?>

    <section class="container mt-5 mb-5">
        <div class="row mb-3">
            <div class="col">
                <h1>Visualizar produto</h1>
            </div>
        </div>

        <div class="mb-3">
            <h3>Imagem</h3>
            <img src="<?= $product["image"] ?>" alt="Imagem do produto" />
        </div>

        <div class="mb-3">
            <h3>Nome</h3>
            <p><?= $product["name"] ?></p>
        </div>

        <div class="mb-3">
            <h3>Categoria</h3>
            <p><?= $product["category"] ?></p>
        </div>

        <div class="mb-3">
            <h3>Descrição</h3>
            <p><?= $product["description"] ?></p>
        </div>

        <div class="mb-3">
            <h3>Quantidade</h3>
            <p><?= $product["quantity"] ?></p>
        </div>
    </section>
</body>

</html>