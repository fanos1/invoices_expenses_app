<?php
session_start();

try {
	 include __DIR__ . '/../../../DatabaseConnectionAccountant.php';

	$errors = array();
	$output = '';


	$q = " SELECT * FROM invoices ";	
	$result = $pdo->query($q);	

	$q2 = " SELECT * FROM customers ";	
	$customers = $pdo->query($q2);	

	$count = 0 ;
	$total = 0;
	$tr ='';
	// SHOW ONLY DATE, ignore time part 
	// date('Y-m-d', strtotime($value['expen_date']))

	foreach ($result as $key => $value) {
		// <td><a href="./delete-row.php?id='.$value['id'].'"> Delete </a> </td> 
		// echo 
		$tr .=
		'<tr> 
			<td>
				<a href="./update.php?id='.$value['id'].'">'.$value['id'].' - Update </a> 
			</td> 										
			<td>€'. number_format($value['amount'] / 100). '</td>

			<td>'. $value['invoice_ref']. '</td>
			<td>'. $value['customer_id']. '</td>
			<td>'. $value['proj_id']. '</td>
			<td>'. $value['tax_code_id']. '</td>
			<td>'. $value['issue_date']. '</td>
			<td>'. date('Y-m-d', strtotime($value['due_date']) ) . '</td>
			<td>'. $value['description']. '</td>
			<td><a href="./view.php?id='.$value['id'].'"> View </a> </td> 
		</tr>';	
		$total += $value['amount'];
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
	<style type="text/css">
		.total {			
			font-weight: bold;
		}
	</style>
</head>


<body>

<div class="container-fluid">	
<div class="row"> 
		
    <nav class="col-md-2 d-none d-md-block bg-light sidebar">
     <?php include '../navigation.php'; ?>
    </nav>

    <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4"> 
		<h2>Invoices </h2>
		
		<div class="row">
			<div class="col-md-6"> 
				<div class="alert alert-danger total">Total Invoices: €<?php echo number_format($total / 100); ?> </div>
			</div>
			<div class="col-md-6"> 
				<a href="./create-and-view.php" class="btn btn-success">Create New Invoice </a> 
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
							<th> invoice_ref</th>
							<th>cust._id </th>
							<th> proj_id</th>
							<th> tax_code_id</th>
							<th> issue</th>
							<th>due</th>
							<th>description</th>
							<th>Act.</th>
						</tr>
					</thead>
					  
					<tbody> 		  
						<?php echo $tr;	?>
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

<script type="text/javascript" src="//cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script type="text/javascript">     
    // $(function() { 
    //    $("#workdone").dataTable();
    // }); 	
	$(document).ready(function() {
		
		// $('#myTable').DataTable();
		$('#myTable').DataTable( {
		    //"lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]]
		    "lengthMenu": [26]
		});

	});
</script>


<script src="./js/dashboard.js"></script>

</body>
</html>

