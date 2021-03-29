<?php  
	require("../../_config/connection.php");
	header('Content-type: application/json');

	
	function add($conn) {

		$result = false;
		$error = false;

		try {
			// $requestBody = file_get_contents('php://input');
			// $body = json_decode($requestBody, true);
			
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
		
		} catch (Exception $e) {
			$error = $e;
		}
		
		$message = "";
		
		if ($result && !$error) {
			$message = "Produto Inserido com sucesso";
		}else{
			$message = "Erro ao adicionar o produto";
		}
		
		$data = ["message" => $message];
		echo json_encode($data);
	}

	function edit($conn, $id){
		$rowCount = 0;
		$error = false;

		if (empty($id)) {
			$data = [ "message" => "Id do produto não informado"];
			$dataEncoded = json_encode($data);
			echo $dataEncoded;
			exit();
		}

		try {
			$query = "SELECT * FROM products WHERE id=$id";
			$result = $conn->query($query);
			$rowCount = $result->num_rows;
			$result->close();
		} catch (Exception $e) {
			$error = $e;
		}

		if ($rowCount == 0 || !empty($error)) {
			$data = [ "message" => "Produto não encontrado"];
			$dataEncoded = json_encode($data);
			echo $dataEncoded;
			exit();
		}

		$upadeError = false;
		$updateResult = false;

		try {

			// $requestBody = file_get_contents('php://input');
			// $body = json_decode($requestBody, true);

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
				id=$id";

			$updateResult = $conn->query($query);

		} catch (Exception $e) {
			$upadeError = $e;
		}

		$message = "";

		if ($updateResult && !$upadeError) {
			$message = "Produto alterado com sucesso";
		}else{
			$message = "Erro ao alterar o produto";
		}

		$data = ["message" => $message];
		echo json_encode($data);

	}

	function remove($conn, $id){
		$rowCount = 0;
		$error = false;
		$result = false;

		try {
			$query = "SELECT * FROM products WHERE id=$id";
			$result = $conn->query($query);
			$rowCount = $result->num_rows;
			$result->close();
		} catch (Exception $e) {
			$error = $e;
		}
		
		if ($rowCount == 0 || !empty($error)) {
			$data = [ "message" => "Produto não encontrado"];
			$dataEncoded = json_encode($data);
			echo $dataEncoded;
			exit();
		}

		try {
			$query = "DELETE FROM products WHERE id=$id";
			$result = $conn->query($query);
		} catch (Exception $e) {
			$error = $e;
		}
		
		$message = ($result && empty($error)) ? "Produto excluido com sucesso." : "Erro ao excluir o produto.";
	
		$data = ["message" => $message];
		echo json_encode($data);
	}

	function getOne($conn, $id) {

		$error = false;
		$product = [];

		try {
			$query = "SELECT * FROM products WHERE id=$id";
			$result = $conn->query($query);
			$product = $result->fetch_assoc();
			$result->close();
		} catch (Exception $e) {
			$error = $e;
		}

		$data = [];

		if (empty($product) || !empty($error)) {
			$data = [ "message" => "Produto não encontrado"];
		} else{
			$data = $product;
		}

		echo json_encode($data);
	}

	function getAll($conn, $category_id) {
		
		// Building select query
		$query = "SELECT p.*, c.name as category 
			FROM products p
			INNER JOIN categories c on p.category_id = c.id";
	
		if(!empty($category_id)){
			$query .= " WHERE p.category_id = $category_id";
		}

		// Getting registers
		$result = $conn->query($query);
		$data = $result->fetch_all(MYSQLI_ASSOC);
		$result->close();

		// Returning data
		$dataEncoded = json_encode($data);
		echo $dataEncoded;
	}

	// Getting HTTP Method
	$method = $_SERVER['REQUEST_METHOD'];

	// Getting URL params
	$category_id = "";
	$id = "";
	if(isset($_GET["category_id"])){
		$category_id = $_GET["category_id"];
	}
	if(isset($_GET["id"])){
		$id = $_GET["id"];
	}

	switch ($method) {
		case 'GET':
			empty($id) ? getAll($conn, $category_id) : getOne($conn, $id);
		break;

		case 'DELETE':
			remove($conn, $id);
		break;

		case 'POST':
			empty($id) ? add($conn) : edit($conn, $id);
		break;

		// case 'PUT':
		// 	edit($conn, $id);
		// break;
		
		default:
			echo "Default";
		break;
	}

	// Closing connection
	$conn->close();
