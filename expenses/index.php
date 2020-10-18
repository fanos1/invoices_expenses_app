<?php
session_start();

try {
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
			
		$q = " SELECT * FROM expenses ";	
		$result = $pdo->query($q);	
		
		$tr = '';
		$totals = 0;
		// SHOW ONLY DATE, IGNORE TIME 2020-10-09 16:00:52
		// date('Y-m-d', strtotime($value['expen_date'])) 
		foreach ($result as $key => $value) {			
			// <td><a href="./delete-row.php?id='.$value['id'].'"> Delete </a> </td> 
			$tr .= 
			'<tr> 
				<td>
					<a href="./update.php?id='.$value['id'].'">'.$value['id'].' </a> 
				</td> 										
				<td>'. $value['amount'] / 100 . '</td>
				<td>'. $value['expen_type']. '</td>
				<td>'. $value['proj_id']. '</td>
				
				<td>'. $value['description']. '</td>
				<td>'. date('Y-m-d', strtotime($value['expen_date']))  . '</td>

				<td><a href="./view.php?id='.$value['id'].'"> View </a> </td> 
			</tr>';										
			$totals += $value['amount'];
		}			
	}

} catch (Exception $e) {
	exit("we apologise, exception occured");		
} catch (PDOException $e) {
	$message = 'Unable to connect to the database server: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine();
	//echo "<h3> $message </h3>";
	echo "<h3> DatabaseConnection hatasi </h3>";
}

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
		<h2>Expenses</h2>
		
		<div class="row">
			<div class="col-md-6"><a href="./create-and-view.php" class="btn btn-success">Create Or View </a></div>	
			<div class="col-md-6">
				<div class="alert alert-danger">
					Total Expenses:<strong>€ <?php echo number_format($totals / 100); ?> </strong>
				</div>
			</div>
		</div>
		

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
								echo $tr;
							?>
						</tbody>
					</table>
					
				</div>
			  </div>
			</div>
      
    </main>

</div>
</div>


<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
      
<script>window.jQuery || document.write('<script src="/docs/4.4/assets/js/vendor/jquery.slim.min.js"><\/script>')</script><script src="/docs/4.4/dist/js/bootstrap.bundle.min.js" integrity="sha384-6khuMg9gaYr5AxOqhkVIODVIvm9ynTT5J4V1cfthmT+emCG6yVmEZsRHdxlotUnm" crossorigin="anonymous"></script>
<!-- 
<script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.9.0/feather.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.min.js"></script>
-->
<script src="./js/dashboard.js"></script>

</body>
</html>

