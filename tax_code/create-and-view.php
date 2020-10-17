<?php
session_start();


try 
{
	 include __DIR__ . '/../../../DatabaseConnectionAccountant.php';

	$errors = array();
	$output = '';

	
	if ($_SERVER['REQUEST_METHOD'] === 'GET') {
		/* 
		if (filter_var($_GET['type'], FILTER_VALIDATE_INT)) {		    
			$type = strip_tags($_GET['type']); 
		} else {
		    exit("type Variable Not an integer/Not set, Cik");
		}
		*/
			
		$q = " SELECT * FROM tax_codes ";	
		$result = $pdo->query($q);	
	}


	if ($_SERVER['REQUEST_METHOD'] === 'POST') 
	{
		
		// ---------- Validation --------------
		//check if the form submited is our own form
	    if (!isset($_POST['formtoken1']) || $_POST['formtoken1'] !== $_SESSION['formtoken1']) {
	        //$formtoken should always be set, if it is not set, create error
	        exit('The form submited is not valid. Please reload the page');
	    }
	    if (!empty($_POST['med'] )) { //!empty means bots must have populated form submited 	        
	        exit('The form submited is not valid. Med');
	    }


		if (!preg_match ('/^[A-Z0-9 \'.-]{2,180}$/i', $_POST['name'] )) {		
            $errors['name'] = 'name hata var error!';
		} else {
			$name = strip_tags($_POST['name']);
		}		


		if (!preg_match ('/^[0-9]{1,10}$/i', $_POST['amount'] )) {		
            $errors['amount'] = 'type hata var error! not integer';
		} else {
			$amount = strip_tags($_POST['amount']);
		}		

		

	    // ------------------------
		// if No errors, INSERT 
		// -----------------------
		if (empty($errors)) 
		{			
			$q = "INSERT INTO tax_codes (name, amount) VALUES(:n, :a) ";
        	    
            $stmt = $pdo->prepare($q);                   
            $stmt->bindParam(':n', $name);  
            $stmt->bindParam(':a', $amount);
            $stmt->execute();
            
            $success = '<div class="alert alert-success">Basari ile kayit edildi!</div>';
		    
		} else {
			$errorOut = '';
			foreach ($errors as $key => $value) {
				$errorOut .= '<div class="alert alert-danger">'. $value. '</div>';
				// echo '<h3 class="alert alert-danger">'. $value. '</h3>';
			}			
		}
	}

} catch (PDOException $e) {
	$message = 'Unable to connect to the database server: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine();
	//echo "<h3> $message </h3>";
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
<div class="container-fluid">
	<div class="row">

	    <nav class="col-md-2 d-none d-md-block bg-light sidebar">
	      <?php include '../navigation.php'; ?>
	    </nav>

		<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4"> 
			<?php 
			    if (isset($success)) { 
			    	echo $success. '<a href="https://universeyachting.eu/account/"> Menu </a>';
			    } 
			    if (isset($errorOut)) {
			    	echo $errorOut;
			    }
		    ?>	
		    <form action="" method="POST">
				<input type="hidden" name="formtoken1" value="<?php echo $_SESSION['formtoken1']; ?>" />   
		        <p class="hp" style="display: none;"> <input type="text" name="med" id="med" value=""> </p>
					
				<fieldset>
					<legend><strong>Tax Codes</strong></legend>					
					<div class="form-group">
			            <label for="id">Id</label> 
			            <input type="text" name="id" class="form-control" />            
			        </div>
			        <div class="form-group">
			        	<label for="name">Name</label> 
			        	<input type="text" name="name" class="form-control" />
			        </div>

			        <div class="form-group">
			            <label for="amount">Amount</label> 
			            <input type="text" name="amount" class="form-control" />
			        </div>
				</fieldset>							
				<input type="submit" value="Gonder &rarr;" class="btn btn-success"> 		
			</form> 

			<!-- TABLE DATA -->
			<div class="containter">				
			  <div class="row">
				<div class="col-md-12" style="overflow-x:auto; font-size: smaller; margin-top: 24px;"> 
					
					<!-- make the table repsonsive, scroll Bar -->
					<table class="table" id="myTable">
						<thead>
							<tr>
								<th>id</th>					
								<th>name</th>	
								<th>amount</th>                
							</tr>
						</thead>
						  
						<tbody> 		  
							<?php 			
								$count = 0 ;
								foreach ($result as $key => $value) {
									echo 
									'<tr> 
										<td> 
											<a href="'.$value['id'].'">'. $value['id']. '</a>
										</td> 
										<td>'. $value['name']. '</td>
										<td>'. $value['amount']. '</td>
										<td>
											<a href="./delete-row.php?id='.$value['id'].'"> Delete </a> 
										</td> 
									</tr>';	
								}
							?>
						</tbody>
					</table>
					
				</div>
			  </div>
			</div>

		</main>

	</div>
</div>






<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script> -->
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>

<script>window.jQuery || document.write('<script src="/docs/4.4/assets/js/vendor/jquery.slim.min.js"><\/script>')</script>
<script src="/docs/4.4/dist/js/bootstrap.bundle.min.js" integrity="sha384-6khuMg9gaYr5AxOqhkVIODVIvm9ynTT5J4V1cfthmT+emCG6yVmEZsRHdxlotUnm" crossorigin="anonymous"></script>
<!-- 
<script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.9.0/feather.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.min.js"></script>
-->



<script type="text/javascript" src="//cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script type="text/javascript">     
    // $(function() { 
    //    $("#workdone").dataTable();
    // }); 	
	$(document).ready(function() {
		
		// $('#myTable').DataTable();
		$('#myTable').DataTable( {
		    //"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]]
		    "lengthMenu": [16]
		});

	});
</script>

<script src="./js/dashboard.js"></script>

</body>
</html> 


