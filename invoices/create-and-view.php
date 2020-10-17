<?php
session_start();


try 
{
	 include __DIR__ . '/../../../DatabaseConnectionAccountant.php';

	$errors = array();
	$output = '';

				
	// $q = " SELECT * FROM invoices ";	
	// $result = $pdo->query($q);	

	$q2 = " SELECT * FROM customers ";	
	$customers = $pdo->query($q2);	



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


		// if (!preg_match ('/^[0-9]{1,10}$/i', $_POST['amount'] )) {	
		if (!preg_match ('/^\d*\.?\d*$/i', $_POST['amount'] )) { // Decimal Validation i.e 100.30	
            $errors['amount'] = 'amount = hata var error! not integer';
		} else {
			$amount = strip_tags($_POST['amount']);
			$amount = $amount * 100;
		}

		/* 
		if (!preg_match ('/^[0-9-]{2,50}$/i', $_POST['issue_date'] )) {			
			$errors['issue_date'] = 'issue date error';
		} else {
			$issue_date =  strip_tags($_POST['issue_date']);
		}
		*/
		if ( !filter_var($_POST['proj_id'], FILTER_VALIDATE_INT) ) {
		    exit('cik, not valid 1');
		    $errors['proj_id'] = 'proj_id select hata var error!';
		} else {
			$proj_id = strip_tags($_POST['proj_id']);
		}


		if ( !filter_var($_POST['customer_id'], FILTER_VALIDATE_INT) ) {					    
		    $errors['customer_id'] = 'customer_id select hata var error!';
		} else {
			$customer_id = strip_tags($_POST['customer_id']);
		}


		if (!preg_match ('/^[A-Z0-9 \'.-]{2,180}$/i', $_POST['invoice_ref'] )) {		
            $errors['invoice_ref'] = 'invoice_ref hata var error!';
		} else {
			$invoice_ref = strip_tags($_POST['invoice_ref']);
		}		


		// if ( !filter_var($_POST['tax_code_id'], FILTER_VALIDATE_INT) ) {
		if (!preg_match ('/^[0-9]{1,10}$/i', $_POST['tax_code_id'] )) {	
		    $errors['tax_code_id'] = 'tax_code_id select hata var error!';
		} else {
			$tax_code_id = strip_tags($_POST['tax_code_id']);			
		}


		if (!preg_match ("/^[A-Z0-9 \'.,:\/-]{2,250}$/i", $_POST['description'] )) {		
            $errors['description'] = 'description hata var error!';
		} else {
			$description = strip_tags($_POST['description']);
		}		


	    // ------------------------
		// if No errors, INSERT 
		// -----------------------
		if (empty($errors)) 
		{			
			$q = "INSERT INTO invoices (amount, invoice_ref, customer_id, proj_id, tax_code_id,  description ) 
			VALUES(:a, :invoice_ref, :customer_id, :proj_id, :tax_code_id, :description) ";
        	    
            $stmt = $pdo->prepare($q);                               
            $stmt->bindParam(':a', $amount);
            $stmt->bindParam(':invoice_ref', $invoice_ref);
            $stmt->bindParam(':customer_id', $customer_id);
            $stmt->bindParam(':proj_id', $proj_id);
            $stmt->bindParam(':tax_code_id', $tax_code_id);
            //$stmt->bindParam(':issue_date', $issue_date);
            $stmt->bindParam(':description', $description);

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
					<legend><strong>Inovices</strong></legend>

					<div class="row">
					    <div class="col">            	  
						   	<label for="amount">Amount</label>
							<input type="text" name="amount" id="amount" class="form-control" placeholder="230.30" />
					    </div>
					    <div class="col">
					      	<label for="invoice_ref">Invoice Ref</label>
						  	<input type="text" name="invoice_ref" id="invoice_ref" class="form-control" />
					    </div>
					    <div class="col">
					      	<label for="customer_id">Customer Id</label>					
							<select name="customer_id" id="customer_id" class="form-control">	
							<?php 
							foreach ($customers as $key => $value) {
								echo '<option value="'.$value['id'].'">'.$value['name'].' </option> ';
							}
							?>			  
							</select>
					    </div>
					    <div class="col">
					    	<label for="proj_id">Proj Id</label>					
							<select name="proj_id" id="proj_id" class="form-control">					  
							  <option value="1">Project1</option>
							  <option value="2">Project2</option>					  
							</select>
					    </div>
					</div>					
					

					<div class="row">
						<div class="col">
							<label for="tax_code_id">Tax Code Id</label>
							<!-- <input type="text" name="tax_code_id" id="tax_code_id" class="form-control" /> -->
							<select name="tax_code_id" id="tax_code_id" class="form-control">					  
							  <option value="0">EU TAX 0%</option>
							  <option value="20">GBP %20</option>					  
							</select>
						</div>
						<div class="col">					
							<label for="issue_date">Issue Date</label>					
							<input type="date" class="form-control" name="issue_date" min="2020-01-01" max="2020-12-31" 
							value="2020-07-01" />		
						</div>
					</div>
										

					<label for="description">Description</label>
					<input type="text" name="description" id="description" class="form-control" />
					
				</fieldset>							
				<br/>
				<input type="submit" value="Gonder &rarr;" class="btn btn-success"> 		
			</form> 


			<!-- TABLE DATA -->
			
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


<!-- 
<script type="text/javascript" src="//cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script type="text/javascript">     
    
	$(document).ready(function() {		
		// $('#myTable').DataTable();
		$('#myTable').DataTable( {
		    //"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]]
		    "lengthMenu": [16]
		});

	});
</script>
-->

<script src="./js/dashboard.js"></script>

</body>
</html> 


