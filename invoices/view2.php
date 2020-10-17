<?php
session_start();
/* 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/

// echo $_SERVER['HTTP_REFERER'] ; // https://universeyachting.eu/admin/links/jobsapplied.php
// echo basename("/etc/passwd").PHP_EOL; //  passwd

// pathinfo() — Returns information about a file path 
 // $path_parts = pathinfo($_SERVER['HTTP_REFERER'] );
/* 
echo "<h3>". $path_parts['dirname']. "</h3>";
echo "<h3>". $path_parts['basename']. "</h3>";
echo "<h3>". $path_parts['extension']. "</h3>";
echo "<h3>". $path_parts['filename']. "</h3>"; 
exit();
*/



try {
	 // include __DIR__ . '/../../../DatabaseConnectionManager.php';
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
			
			$sql = 'SELECT * FROM `invoices` WHERE `id` = :d';
			$stmt = $pdo->prepare($sql);
			$stmt->bindValue(':d', $_GET['id'] );
			 // $stmt->bindParam(':d', $id2);
			$result = $stmt->execute();
			//$R = $stmt->fetch();
			$R = $stmt->fetchAll(PDO::FETCH_ASSOC);
			
			
			if ($result ) {
				$success = TRUE;
				$tr = '';
				
				foreach ($R  as $key => $rows) {
					// echo "<h3>". $rows['amount'] ."</h3>";
					$tr .= "<td>". $rows['id'] ."</td>";
					$tr .= "<td>". $rows['amount'] ."</td>";					
					$tr .= "<td>". $rows['invoice_ref'] ."</td>";
					$tr .= "<td>". $rows['customer_id'] ."</td>";
					$tr .= "<td>". $rows['proj_id'] ."</td>";
					$tr .= "<td>". $rows['tax_code_id'] ."</td>";
					$tr .= "<td>". $rows['issue_date'] ."</td>";
					$tr .= "<td>". $rows['due_date'] ."</td>";
					$tr .= "<td>". $rows['description'] ."</td>";

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
</head>


<body>
<div class="container">
	<div class="row">
		<table class="table" id="mytable" cellspacing="0" cellpadding="0">
			<thead>
				<tr>
					<th>id</th>													
					<th>amount</th> 
					<th> invoice_ref</th>
					<th>cust._id </th>
					<th> proj_id</th>
					<th> tax_code_id</th>
					<th> issue</th>
					<th>due</th>
					<th>description</th>
				</tr>
			</thead>
			
			<tbody> 
				<tr>  
					<?php echo $tr; ?>
				</tr>
			</tbody>
		</table>			    

	</div>
</div>	    

<p class="lead">
	<button id="pdf" class="btn btn-primary">TO PDF</button> 
</p>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>      
<script>window.jQuery || document.write('<script src="/docs/4.4/assets/js/vendor/jquery.slim.min.js"><\/script>')</script>


<!-- 
<script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.9.0/feather.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.min.js"></script>
-->


 
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.22/pdfmake.min.js"></script> 
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>
<script type="text/javascript">

    $("body").on("click", "#pdf", function () {

        html2canvas($('#mytable')[0], {
            onrendered: function (canvas) {
                var data = canvas.toDataURL();
                var docDefinition = {
                    content: [{
                        image: data,
                        width: 500
                    }]
                };
                pdfMake.createPdf(docDefinition).download("customer-details.pdf");
            }

        });
    });
</script>

</body>
</html>


