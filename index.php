
<!doctype html>
<html class="no-js" lang="en">
<head>	
	<meta charset="utf-8">
	<meta http-equiv="x-ua-compatible" content="ie=edge" />
	<title></title>

	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">

<style type="text/css">
  body {
	  font-size: .875rem;
	}

	.feather {
	  width: 16px;
	  height: 16px;
	  vertical-align: text-bottom;
	}

	/*
	 * Sidebar
	 */

	.sidebar {
	  position: fixed;
	  top: 0;
	  bottom: 0;
	  left: 0;
	  z-index: 100; /* Behind the navbar */
	  padding: 48px 0 0; /* Height of navbar */
	  box-shadow: inset -1px 0 0 rgba(0, 0, 0, .1);
	}

	.sidebar-sticky {
	  position: relative;
	  top: 0;
	  height: calc(100vh - 48px);
	  padding-top: .5rem;
	  overflow-x: hidden;
	  overflow-y: auto; /* Scrollable contents if viewport is shorter than content. */
	}

	@supports ((position: -webkit-sticky) or (position: sticky)) {
	  .sidebar-sticky {
	    position: -webkit-sticky;
	    position: sticky;
	  }
	}

	.sidebar .nav-link {
	  font-weight: 500;
	  color: #333;
	}

	.sidebar .nav-link .feather {
	  margin-right: 4px;
	  color: #999;
	}

	.sidebar .nav-link.active {
	  color: #007bff;
	}

	.sidebar .nav-link:hover .feather,
	.sidebar .nav-link.active .feather {
	  color: inherit;
	}

	.sidebar-heading {
	  font-size: .75rem;
	  text-transform: uppercase;
	}

	/*
	 * Content
	 */
	[role="main"] {
	  padding-top: 133px; /* Space for fixed navbar */
	}

	@media (min-width: 768px) {
	  [role="main"] {
	    padding-top: 48px; /* Space for fixed navbar */
	  }
	}

	/*
	 * Navbar
	 */
	.navbar-brand {
	  padding-top: .75rem;
	  padding-bottom: .75rem;
	  font-size: 1rem;
	  background-color: rgba(0, 0, 0, .25);
	  box-shadow: inset -1px 0 0 rgba(0, 0, 0, .25);
	}

	.navbar .form-control {
	  padding: .75rem 1rem;
	  border-width: 0;
	  border-radius: 0;
	}

	.form-control-dark {
	  color: #fff;
	  background-color: rgba(255, 255, 255, .1);
	  border-color: rgba(255, 255, 255, .1);
	}

	.form-control-dark:focus {
	  border-color: transparent;
	  box-shadow: 0 0 0 3px rgba(255, 255, 255, .25);
	}


</style>	
</head>


<body>

<div class="container-fluid">	
<div class="row"> 
		
    <nav class="col-md-2 d-none d-md-block bg-light sidebar">
      <?php include './navigation.php'; ?>
    </nav>

    <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4"> 
      <h2>Section title</h2>
      <div class="table-responsive">
        <table class="table table-striped table-sm">
          <thead>
            <tr>
              <th>#</th>
              <th>Header</th>
              <th>Header</th>
              <th>Header</th>
              <th>Header</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>1,001</td>
              <td>Lorem</td>
              <td>ipsum</td>
              <td>dolor</td>
              <td>sit</td>
            </tr>
            <tr>
              <td>1,002</td>
              <td>amet</td>
              <td>consectetur</td>
              <td>adipiscing</td>
              <td>elit</td>
            </tr>
            <tr>
              <td>1,003</td>
              <td>Integer</td>
              <td>nec</td>
              <td>odio</td>
              <td>Praesent</td>
            </tr>
           
          </tbody>
        </table>
      </div>   
      
      <div id="form">
        <!-- Form Generator from DB Table -->
        <!-- https://www.fpmgonline.com/  -->
       
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

