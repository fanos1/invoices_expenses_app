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
			/* 
			$sql = 'INSERT INTO `joke` SET
				`joketext` = :joketext,
				`jokedate` = CURDATE()';
			*/
			
			// $sql = 'SELECT * FROM `invoices` WHERE `id` = :d';
			$sql = "SELECT inv.id, inv.amount, inv.invoice_ref, inv.customer_id, inv.proj_id, 
			inv.tax_code_id, inv.issue_date,  inv.due_date, inv.description, 
			c.id as cust_id, c.name, c.str_address, c.city, c.post_code, c.country, c.VAT_no
			FROM invoices AS inv
			INNER JOIN customers AS c ON c.id = inv.customer_id
			WHERE inv.id = :d";
			$stmt = $pdo->prepare($sql);
			$stmt->bindValue(':d', $_GET['id'] );
			 // $stmt->bindParam(':d', $id2);
			$result = $stmt->execute();
			//$R = $stmt->fetch();
			$R = $stmt->fetchAll(PDO::FETCH_ASSOC);
			
			
			if ($result ) {
				$success = TRUE;
				$tr = '';
				$inv_no = '';
				$due_d = '';
				$issue_d = '';
				$custom_name = '';
				$custom_city = '';
				$custom_country = '';
				$custom_postcode = '';
				$custom_vat = '';
				$tot = 0;
				
				foreach ($R  as $key => $rows) {
					// echo "<h3>". $rows['amount'] ."</h3>";
					$tr .= "<td>". $rows['id'] ."</td>";
					$tr .= "<td>". number_format($rows['amount'] / 100)  ."</td>";					
					$tr .= "<td>". $rows['invoice_ref'] ."</td>";
					$tr .= "<td>". $rows['customer_id'] ."</td>";
					$tr .= "<td>". $rows['proj_id'] ."</td>";
					$tr .= "<td>". $rows['tax_code_id'] ."</td>";
					$tr .= "<td>". $rows['issue_date'] ."</td>";
					$tr .= "<td>". $rows['due_date'] ."</td>";
					$tr .= "<td>". $rows['description'] ."</td>";

					$inv_no = $rows['id'];
					$custom_city = $rows['city'];
					$custom_name = $rows['name'];
					$custom_str_addr = $rows['str_address'];
					$custom_postcode = $rows['post_code'];
					$custom_country = $rows['country'];
					$custom_vat = $rows['VAT_no'];
					// $due_d = date('Y-m-d', strtotime($rows['due_date'] ));
					$due_d = date('Y-m-d', strtotime($rows['due_date']. ' + 14 days') ); // add 14 days
					$issue_d = date('Y-m-d', strtotime( $rows['issue_date'] ));

					// $tot = number_format($rows['amount'] / 100);
					$tot = $rows['amount'] ;
					$vat = $rows['tax_code_id'];
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
		    background: grey;
		    margin-top: 120px;
		    margin-bottom: 120px;
		}
    </style>
</head>


<body>
			
<div class="container">
    <div class="row">
        <div class="col-12" id="mytable">
			
            <div class="card">
                <div class="card-body p-0">
                    <div class="row p-5">
                        <div class="col-md-6">                            
                            <strong> Universe Yachting Ltd </strong> <br/>
                            Rushmore Rd,  <br/>
                            London, E5 0HA <br/>
                            VAT Number: GB123456 <br/>
                            Registered Company No. 10898609
                        </div>

                        <div class="col-md-6 text-right">
                            <p class="font-weight-bold mb-1">Invoice #<?php echo $inv_no; ?> </p>
                            <p class="text-muted">Due Date:  <?php echo $due_d; ?> </p>
                        </div>
                    </div>

                    <hr class="my-5">

                    <div class="row pb-5 p-5">
                        <div class="col-md-6">
                            <p class="font-weight-bold mb-4">Client Information</p>
                            <p class="mb-1"> <?php echo $custom_name; ?></p>
                            <p> <?php echo $custom_str_addr; ?></p>
                            <p class="mb-1"><?php echo $custom_city.', '. $custom_country; ?>  </p>
                            <p class="mb-1"><?php echo $custom_postcode; ?></p>
                        </div>

                        <div class="col-md-6 text-right">
                            <p class="font-weight-bold mb-4">Payment Details</p>
                            <p class="mb-1"><span class="text-muted">VAT: </span> <?php echo $custom_vat; ?></p>
                            <p class="mb-1"><span class="text-muted">VAT ID: </span> 10253642</p>                            
                            <p class="mb-1"><span class="text-muted">Name: </span> John Doe</p>
                        </div>
                    </div>

                    <div class="row p-5">
                        <div class="col-md-12">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th class="border-0 text-uppercase small font-weight-bold">id</th>
                                        <th class="border-0 text-uppercase small font-weight-bold">amount</th>
                                        <th class="border-0 text-uppercase small font-weight-bold">invoice_ref</th>
                                        <th class="border-0 text-uppercase small font-weight-bold">cust_id</th>

                                        <th class="border-0 text-uppercase small font-weight-bold">proj_id</th>
                                        <th class="border-0 text-uppercase small font-weight-bold">tax_code_id</th>
                                        <th class="border-0 text-uppercase small font-weight-bold">issue Date </th>
                                        <th class="border-0 text-uppercase small font-weight-bold">due Date </th>
                                        <th class="border-0 text-uppercase small font-weight-bold">description </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>  <?php echo $tr; ?> </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="d-flex flex-row-reverse bg-dark text-white p-4">
                        <div class="py-3 px-5 text-right">
                            <div class="mb-2">Grand Total</div>
                            <div class="h2 font-weight-light">
                            	<?php 
                            		$Grand = $tot+$vat;
                            		echo '€'. number_format($Grand / 100); 
                            	?>
                            </div>
                        </div>
                     
                        <div class="py-3 px-5 text-right">
                            <div class="mb-2">VAT</div>
                            <div class="h2 font-weight-light">€0.00</div>
                        </div>
                    

                        <div class="py-3 px-5 text-right">
                            <div class="mb-2">Sub - Total amount</div>
                            <div class="h2 font-weight-light"> <?php echo '€'.number_format($tot /100); ?>  </div>
                        </div>
                    </div>
                </div>
            </div>

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


