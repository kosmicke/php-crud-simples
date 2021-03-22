<?php  
	require("../_config/connection.php");

	$message = false;
	$category_id = false;

	if($_GET){
		if(isset($_GET["message"])){
			$message = $_GET["message"];
		}
		if(isset($_GET["category_id"])){
			$category_id = $_GET["category_id"];
		}
	}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
	<title>My php script</title>
</head>
<body>

	<?php  
        readFile("../_partials/navbar.html");
    ?>

	<?php 
		$query = "SELECT p.*, c.name as category 
			FROM products p
			INNER JOIN categories c on p.category_id = c.id";

		if($category_id){
			$query .= " WHERE p.category_id = $category_id";
		}

		$result = $conn->query($query);
		$rows = $result->fetch_all(MYSQLI_ASSOC);
		$result->close();

		try {
			$categoryQuery = "SELECT * from categories";
			$categoryResult = $conn->query($categoryQuery);
		} catch (Exception $e) {
			header('Location: index.php?message=Erro ao recuperar categorias!');
			die();
		}
		
		$conn->close();
	?>
	<section class="container mt-5 mb-5">

		<?php if($message):?>
			<div class="alert alert-primary alert-dismissible fade show" role="alert">
				<?=$message?>
				<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
			</div>
		<?php endif;?>

		<div class="row mb-3">
			<div class="col">
				<h1>Produtos</h1>
			</div>
			<div class="col d-flex justify-content-end align-items-center">
				<a class="btn btn-primary" href="add.php">Adicionar</a>
			</div>
		</div>

		<form action="" method="get">
			<div class="input-group mb-3">
				<select 
					class="form-control" 
					id="category_id" 
					name="category_id">
						<option value></option>

						<?php while($category = $categoryResult->fetch_assoc()): ?>
							<option 
								value="<?=$category["id"]?>"
								<?= $category["id"] == $category_id ? 'selected' : '';?>
							>
								<?=$category["name"]?>
							</option>
						<?php endwhile; ?>
						
						<?php $categoryResult->close(); ?>
				</select>
				<button class="btn btn-outline-secondary" type="submit">
					Pesquisar
				</button>
			</div>
		</form>

		<table class="table table-striped table-bordered">
			<thead class="table-dark">
				<tr>
					<th>ID</th>
					<th>Nome</th>
					<th>Descrição</th>
					<th>Quantidade</th>
					<th>Categoria</th>
					<th>Ações</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($rows as $product): ?>
					<tr>
						<td>
							<?=$product["id"]?>
						</td>
						<td>
							<?=$product["name"]?>
						</td>
						<td>
							<?=$product["description"]?>
						</td>
						<td>
							<?=$product["quantity"]?>
						</td>
						<td>
							<?=$product["category"]?>
						</td>
						<td>
							<div class="btn-group" role="group">
								<button 
									type="button" 
									class="btn btn-outline-primary"
									onclick="confirmDelete(<?=$product['id']?>)">
									Excluir
								</button>
								<a 
									href="edit.php?id=<?=$product["id"]?>" 
									class="btn btn-outline-primary">
									Editar
								</a>
								<a 
									href="view.php?id=<?=$product["id"]?>" 
									class="btn btn-outline-primary">
									Ver
								</a>
							</div>
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</section>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
<script>
	const confirmDelete = (productId) => {
		const response = confirm("Deseja realmente excluir este produto?")
		if(response){
			window.location.href = "delete.php?id=" + productId
		}
	}
</script>
</html>


