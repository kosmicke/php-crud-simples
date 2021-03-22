<?php  
	require("../_config/connection.php");
	$message = false;
	if($_GET && $_GET["message"]){
		$message = $_GET["message"];
	}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-BmbxuPwQa2lc/FVzBcNJ7UAyJxM6wuqIj61tLrc4wSX0szH/Ev+nYRRuWlolflfl" crossorigin="anonymous">
	<title>Categorias</title>
</head>
<body>

	<?php  
        readFile("../_partials/navbar.html");
    ?>

	<?php 
		$query = "SELECT * FROM categories";
		$result = $conn->query($query);
		$rows = $result->fetch_all(MYSQLI_ASSOC);
		$result->close();
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
				<h1>Categorias</h1>
			</div>
			<div class="col d-flex justify-content-end align-items-center">
				<a class="btn btn-primary" href="add.php">Adicionar</a>
			</div>
		</div>

		<table class="table table-striped table-bordered">
			<thead class="table-dark">
				<tr>
					<th>ID</th>
					<th>Nome</th>
					<th>Descrição</th>
					<th>Ações</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($rows as $category): ?>
					<tr>
						<td>
							<?=$category["id"]?>
						</td>
						<td>
							<?=$category["name"]?>
						</td>
						<td>
							<?=$category["description"]?>
						</td>
						<td>
							<div class="btn-group" role="group">
								<button 
									type="button" 
									class="btn btn-outline-primary"
									onclick="confirmDelete(<?=$category['id']?>)">
									Excluir
								</button>
								<a 
									href="edit.php?id=<?=$category["id"]?>" 
									class="btn btn-outline-primary">
									Editar
								</a>
								<a 
									href="view.php?id=<?=$category["id"]?>" 
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
	const confirmDelete = (categoryId) => {
		const response = confirm("Deseja realmente excluir esta categoria?")
		if(response){
			window.location.href = "delete.php?id=" + categoryId
		}
	}
</script>
</html>


