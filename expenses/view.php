<?php
session_start();
/* 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/


try {
	 include __DIR__ . '/../../../DatabaseConnectionAccountant.php';

	 $success = FALSE; // variable to store successfull deletion. Default FALSE

	 // echo '<h3>'. $_SERVER['HTTP_REFERER']. '</h3>'; // http://universeyachting.eu/time/test.php
	
	if ($_SERVER['REQUEST_METHOD'] === 'GET') 
	{
			
		if (isset($_GET['id']) ) {
			$id2 = $_GET['id'];				
		} else {
			// both $_GET vars should be set to continue
			exit('cik2');
		}

		if ( filter_var($id2, FILTER_VALIDATE_INT) ) 
		{	
			// $sql = "SELECT * FROM invoices WHERE id = 1";
			// $result = $pdo->query($sql);

			/* 
			$sql = 'INSERT INTO `joke` SET
				`joketext` = :joketext,
				`jokedate` = CURDATE()';
			*/
			
			// $sql = 'SELECT * FROM `invoices` WHERE `id` = :d';
			$sql = "SELECT exp.id, exp.amount, exp.attachment, 
			exp.expen_type, exp.proj_id, exp.attachment, exp.description, exp.expen_date, 
			etype.name AS expenseType, p.name as projName
			FROM expenses AS exp
			INNER JOIN expen_types AS etype ON etype.id = exp.expen_type
			INNER JOIN projects AS p ON p.id = exp.proj_id
			WHERE exp.id = :d";
			$stmt = $pdo->prepare($sql);
			$stmt->bindValue(':d', $_GET['id'] );
			 // $stmt->bindParam(':d', $id2);
			$result = $stmt->execute();
			//$R = $stmt->fetch();
			$R = $stmt->fetchAll(PDO::FETCH_ASSOC);
			
			
			if ($result ) {
				$success = TRUE;
				$tr = '';
				$exp_no = '';
				
				$issue_d = '';
				$custom_name = '';
				$custom_city = '';
				$custom_country = '';
				$custom_postcode = '';
				$custom_vat = '';
				$tot = 0;
				
				// $tr .= "<td>". $rows['attachment'] ."</td>";
				foreach ($R  as $key => $rows) {
					// echo "<h3>". $rows['amount'] ."</h3>";
					$tr .= "<td>". $rows['id'] ."</td>";
					$tr .= "<td>". number_format($rows['amount'] / 100)  ."</td>";	
					$tr .= "<td>". $rows['expen_type'] ."</td>";
					$tr .= "<td>". $rows['expenseType'] ."</td>";
					$tr .= "<td>". $rows['proj_id'] ."</td>";														
					$tr .= "<td>". $rows['description'] ."</td>";
					$tr .= "<td>". $rows['expen_date'] ."</td>";
					$tr .= "<td>". $rows['projName'] ."</td>";
					
					$tr .= '<td><a href="'.$rows['attachment'].'">'. $rows['attachment'] .'</a>
					</td>';

					$exp_no = $rows['id'];
					//$custom_city = $rows['city'];
					//$custom_name = $rows['name'];
					//$custom_str_addr = $rows['str_address'];
					//$custom_postcode = $rows['post_code'];
					//$custom_country = $rows['country'];
					//$custom_vat = $rows['VAT_no'];

					

					// $tot = number_format($rows['amount'] / 100);
					$tot = $rows['amount'] ;
				}

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
    <style type="text/css">
    	body {
		    margin-top: 120px;
		    margin-bottom: 120px;
		}
    </style>
</head>


<body>
			
<div class="container">
    <div class="row">
        <div class="col-12" id="mytable">
		
			<table class="table">
				<thead>
					<th>id </th>
					<th> Amount</th>
					<th> exp type</th>
					<th>exp name</th>
					<th> proj id</th>
					<th>Descrip</th>
					<th>exp date</th>
					<th>Proj name</th>
					<th>pdf</th>
				</thead>
				<tbody>
					<?php echo $tr; ?>
				</tbody>
			</table>

        </div>        
    </div>


	<p class="lead">
		<button id="pdf" class="btn btn-primary">TO PDF</button> 
	</p>

</div>	    

   


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> 
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>

<!-- 
<script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.9.0/feather.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.min.js"></script>
-->



</body>
</html>


