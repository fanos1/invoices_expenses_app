<?php
session_start();

$errors = array();
$output = '';
$PDFS_DIR = './pdf/';


try 
{
	include __DIR__ . '/../../../DatabaseConnectionAccountant.php';	
	
	if ($_SERVER['REQUEST_METHOD'] === 'GET') {
		
		$q = " SELECT * FROM expenses ";	
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


		// if (!preg_match ('/^[0-9]{1,10}$/i', $_POST['amount'] )) {		
	     if (!preg_match ('/^\d*\.?\d*$/i', $_POST['amount'] )) { // Decimal Validation i.e 100.30
	     	/* https://stackoverflow.com/questions/12117024/decimal-number-regular-expression-where-digit-after-decimal-is-optional

	     	* ^ - Beginning of the line;
	     	* \d* - 0 or more digits;
	     	* \.? - An optional dot (escaped, because in regex, . is a special character);
	     	* \d* - 0 or more digits (the decimal part);	     	
	     	*/

            $errors['amount'] = 'amount hata var error! not integer';
		} else {
			$amount = strip_tags($_POST['amount']);
			// we store amount in pence in the Database
			// Amount Submited by user is in Decmal format, convert it to int 
			$amount = $amount * 100; 
		}

	
		if (!preg_match ('/^[0-9-]{2,50}$/i', $_POST['expen_date'] )) {			
			$errors['expen_date'] = 'issue date error';
		} else {
			$expen_date =  strip_tags($_POST['expen_date']);
		}
	

		if ( !filter_var($_POST['proj_id'], FILTER_VALIDATE_INT) ) {
		    exit('cik, not valid 1');
		    $errors['proj_id'] = 'proj_id select hata var error!';
		} else {
			$proj_id = strip_tags($_POST['proj_id']);
		}

		// if ( !filter_var($_POST['tax_code_id'], FILTER_VALIDATE_INT) ) {
		if (!preg_match ('/^[0-9]{1,10}$/i', $_POST['expen_type'] )) {	
		    $errors['expen_type'] = 'expen_type select hata var error!';
		} else {
			$expen_type = strip_tags($_POST['expen_type']);			
		}


		if (!preg_match ('/^[A-Z0-9 \'.-]{2,180}$/i', $_POST['description'] )) {		
            $errors['description'] = 'description hata var error!';
		} else {
			$description = strip_tags($_POST['description']);
		}		

		//Validate PDF, Check for a PDF:
		if (is_uploaded_file($_FILES['pdf']['tmp_name']) && ($_FILES['pdf']['error'] === UPLOAD_ERR_OK)) 
		{		
			// Get a reference:
			$file = $_FILES['pdf'];
			
			// Find the size:
			$size = ROUND($file['size']/1024);

			// Validate the file size (5MB max):
			if ($size > 5120) {
				$errors['pdf'] = 'The uploaded file was too large.';
			} 

			// Allowed types:
			$allowed_mime = array ('image/gif', 'image/jpeg', 'application/pdf');
			$allowed_extensions = array ('.pdf', '.jpg', '.gif', 'jpeg');

			// Check the file: Create the resource:
			$fileinfo = finfo_open(FILEINFO_MIME_TYPE);
			$file_type = finfo_file($fileinfo, $file['tmp_name']);
			finfo_close($fileinfo);

			$file_ext = substr($file['name'], -4);
			
			if ( !in_array($file_type, $allowed_mime) || !in_array($file_ext, $allowed_extensions) ) {
				$errors['image'] = 'The uploaded file was not of the proper type.';
			} 			


			// Move the file over, if no problems:
			if (!array_key_exists('image', $errors)) {								

				$new_name = sha1($file['name'] . uniqid('',true));

				// Add the extension:
				$new_name .= ((substr($file_ext, 0, 1) != '.') ? ".{$file_ext}" : $file_ext);

				// Move the file to its proper folder but add _tmp, just in case:
				$dest =  $PDFS_DIR .$new_name;
				$attach = date("Y-m-d") .'_'. $new_name;

				if (move_uploaded_file($file['tmp_name'], $dest)) {
		
					// Store the data in the session for later use:
					$_SESSION['pdf']['tmp_name'] = $tmp_name;
					$_SESSION['pdf']['size'] = $size;
					$_SESSION['pdf']['file_name'] = $file['name'];
		
					// Print a message:
					$pdfUpload = '<div class="alert alert-success"><h3>The file has been uploaded!</h3></div>';
		
				} else {
					// trigger_error('The file could not be moved.');
					unlink ($file['tmp_name']);				
					exit('The file could not be moved.');
				}

			} 
		
		} 
		elseif (!isset($_SESSION['pdf'])) // No current or previous uploaded file.
		{ 
			switch ($_FILES['pdf']['error']) {
				case 1:
				case 2:
					$errors['pdf'] = 'The uploaded file was too large.';
					break;
				case 3:
					$errors['pdf'] = 'The file was only partially uploaded.';
					break;
				case 6:
				case 7:
				case 8:
					$errors['pdf'] = 'The file could not be uploaded due to a system error.';
					break;
				case 4:
				default: 
					$errors['pdf'] = 'No file was uploaded.';
					break;
			} // End of SWITCH.

		} // End of $_FILES IF-ELSEIF-ELSE.



	    // ------------------------
		// if No errors, INSERT 
		// -----------------------
		if (empty($errors)) 
		{			
			//INSERT 
 			$q = " INSERT INTO expenses (amount, expen_type, proj_id, attachment, description, expen_date )  
 			VALUES (:amount, :expen_type, :proj_id, :attach, :description, :expen_date ) "; 
   
            $stmt = $pdo->prepare($q);                               
            $stmt->bindParam(':amount', $amount);
            $stmt->bindParam(':expen_type', $expen_type);
            $stmt->bindParam(':proj_id', $proj_id);
            $stmt->bindParam(':attach', $attach);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':expen_date', $expen_date);            
            $stmt->execute();
            
            $success = '<div class="alert alert-success">Basari ile kayit edildi!</div>';
		    
		} else {
			$errorOut = '';
			foreach ($errors as $key => $value) {
				$errorOut .= '<div class="alert alert-danger">'. $value. '</div>';				
			}			
		}
	}

} catch (PDOException $e) {
	$message = 'Unable to connect to the database server: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine();
	//echo "<h3> $message </h3>";
	echo "<h3> DatabaseConnection error </h3>";
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
			    if (isset($pdfUpload)) {
			    	echo $pdfUpload;
			    }
		    ?>	
		    <!-- <form action="" method="POST"> -->
		    <form enctype="multipart/form-data" action="" method="POST" accept-charset="utf-8">
		    	
		    	<input type="hidden" name="MAX_FILE_SIZE" value="5242880">

				<input type="hidden" name="formtoken1" value="<?php echo $_SESSION['formtoken1']; ?>" />   
		        <p class="hp" style="display: none;"> <input type="text" name="med" id="med" value=""> </p>

				<fieldset>
					<legend><strong>expense</strong></legend>
					<div class="row">
					    <div class="col"> 
					    	<label for="amount">Amount</label>
							<input type="text" name="amount" id="amount" class="form-control" placeholder="100.50" />
					    </div>
					    <div class="col"> 
					    	<label for="expen_type">Expen Type</label>			
							<select name="expen_type" id="expen_type" class="form-control">					  
							  <option value="1">Flight </option>
							  <option value="2">Accommodation </option>
							  <option value="3">Subcontractor </option>	
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
						<!-- 
					    <div class="col"> 
					    	<label for="attachment">Attachment</label>
							<input type="text" name="attachment" id="attachment" class="form-control" />
					    </div> 
						-->
					    <div class="col">
					    	<label for="expen_date">Expen Date</label>
							<input type="date" class="form-control" name="expen_date" min="2020-01-01" max="2020-12-31" value="2020-07-01" />
					    </div>
					</div>
						
					<label for="description">Description</label>
					<textarea name="description" id="description" class="form-control" cols="45" rows="5"></textarea>

					<input type="file" name="pdf" size="50" />

				</fieldset>
				<br/> 
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
								<th>amount</th> 
								<th> expen type</th>								
								<th> proj_id</th>
								<!-- <th> attachment</th> -->
								<th> description</th>
								<th>expen Date</th>
								<th>action</th>
							</tr>
						</thead>
						  
						<tbody> 		  
							<?php 			
							
								foreach ($result as $key => $value) {
									// <td><a href="./delete-row.php?id='.$value['id'].'"> Delete </a> </td> 
									echo 
									'<tr> 
										<td>
											<a href="./update.php?id='.$value['id'].'">'.$value['id'].' - Update </a> 
										</td> 										
										<td>'. $value['amount']. '</td>
										<td>'. $value['expen_type']. '</td>
										<td>'. $value['proj_id']. '</td>
										
										<td>'. $value['description']. '</td>
										<td>'. $value['expen_date']. '</td>
										<td><a href="./view.php?id='.$value['id'].'"> View </a> </td> 
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


