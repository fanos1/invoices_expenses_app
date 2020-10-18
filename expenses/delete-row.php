<?php
session_start();


// echo $_SERVER['HTTP_REFERER'] ; // https://universeyachting.eu/admin/links/jobsapplied.php
// echo basename("/etc/passwd").PHP_EOL; //  passwd

// pathinfo() — Returns information about a file path 
$path_parts = pathinfo($_SERVER['HTTP_REFERER'] );
/* 
echo "<h3>". $path_parts['dirname']. "</h3>";
echo "<h3>". $path_parts['basename']. "</h3>";
echo "<h3>". $path_parts['extension']. "</h3>";
echo "<h3>". $path_parts['filename']. "</h3>"; 
exit();
*/

$sourceUrl = $path_parts['filename'];


try {
	 // include __DIR__ . '/../../../DatabaseConnectionManager.php';
	 include __DIR__ . '/../../../DatabaseConnectionAccountant.php';

	 $success = FALSE; // variable to store successfull deletion. Default FALSE

	 // echo '<h3>'. $_SERVER['HTTP_REFERER']. '</h3>'; // http://universeyachting.eu/time/test.php
	
	if ($_SERVER['REQUEST_METHOD'] === 'GET') 
	{
			
		if (isset($_GET['id']) ) {
			$id = $_GET['id'];				
		} else {
			// both $_GET vars should be set to continue
			exit('cik2');
		}

		if ( filter_var($id, FILTER_VALIDATE_INT) ) 
		{	
			/*
			if ($sourceUrl == 'jobsapplied') {
				$table_name = 'links_jobsapplied';
			} else if($sourceUrl == 'todo') {
				$table_name = 'links';
			} else {
				$table_name = 'links';
			}
			*/

			// $sql = "DELETE FROM ".$table_name. " WHERE id = :id";			
			$sql = "DELETE FROM expenses WHERE id = :id";
			$stmt = $pdo->prepare($sql);
			// $stmt->bindValue(':id', $GET['id']);
			$stmt->bindParam(':id', $id);
			$result = $stmt->execute();
			
			if ($result ) {
				$success = TRUE;
			} else {
				$success = FALSE;
			}
			// header('location: jokes.php');
			
		}
	}
} 
catch (PDOException $e) {
	$message = 'Unable to connect to the database server: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine();
	echo "<h3> $message </h3>";
	 echo "<h3> DatabaseConnection hatasi </h3>";
}



$_SESSION['formtoken1'] = md5(uniqid(rand(), true));
$formToken1 = $_SESSION['formtoken1'];
?>


<!doctype html>
<html class="no-js" lang="en">
<head>	
	<meta charset="utf-8">
	<meta http-equiv="x-ua-compatible" content="ie=edge" />
	<title></title>

	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

    <link rel="stylesheet" type="text/css" href="../css/styles.css">
</head>

<body>
<div class="container">
<div class="row">		
	
	<div class="col-md-12">
		<?php 
		if ($success) {
			echo '<div class="alert alert-success">Basari ile silindi!</div>';
			echo '<a href="'.$_SERVER['HTTP_REFERER'].'" class="btn btn-success">&larr; Geri Don</a>';
		} else {
			echo '<div class="alert alert-danger">error2!</div>';
		}
		?>	
	</div>
</div>
</div>



<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<!-- <script type="text/javascript" src="//cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script> -->

</body>
</html> 


