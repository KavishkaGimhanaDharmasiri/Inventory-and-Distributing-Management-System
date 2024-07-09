<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lotus Inventory</title>
    <!--CDN link-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>
    <!--css-->
    <style>
      .col-3{
        width: 200px;
      }
      
      body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            background: radial-gradient(circle, rgba(76,175,80,1) 0%, rgba(247,252,248,1) 0%, rgba(250,253,251,1) 23%, rgba(252,254,253,1) 36%, rgba(255,255,255,1) 47%, rgba(246,251,246,1) 59%, rgba(228,243,229,1) 68%, rgba(171,218,173,1) 100%, rgba(76,175,80,1) 100%);
            padding: 0;
           
            align-items: center;
            margin: 8px;
        }
        #navbarNavDropdown{
          background-color: #45a049;
        }
        .container {
            background-color: #fff;
            border-radius: 15px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 100%;
            max-width: 1300px;
            border: 1px solid #45a049;
        }
        input {
            width: 100%;
            padding: 8px;
            box-sizing: border-box;
            border: 1px solid #ccc;
            border-radius: 15px;
             width: calc(100% - 5px);
        }
    </style>
</head>
<body>
      
    <!--nav bar  -->
    <!-- <nav class="navbar navbar-dark bg-dark0> -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light" style="background-color: #45a049;" > 
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavDropdown">
          <ul class="navbar-nav">
            <li class="nav-item active">
              <a class="nav-link" href="Addsuppilers.php"><b>Add Suppliers</b></a>
            </li>
            <li class="nav-item active">
              <a class="nav-link" href="viewSuppliers.php"><b>View Suppliers</b></a>
            </li>
            <li class="nav-item ">
                <a class="nav-link" href="AddRawMaterials.php"><b>Add Raw Materials</b></a>
              </li>
              <li class="nav-item">
              <a class="nav-link" href="ViewRaw.php"><b>View Raw Materials</b></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="addproduct.php"><b>Add Products</b></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="viewProducts.php"><b>View Products</b></a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link" href="rawgrn.php"><b>Raw Good Received Note</b></a>
              </li>
              <li class="nav-item">
              <a class="nav-link" href="ViewRawStocks.php"><b>View Raw Stocks</b></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="outsourcer.php"><b>Outsourcer</b></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="viewOutsourcer.php"><b>View Outsourcer</b></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="productGrn.php"><b>Product Grn</b></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="productGrnView.php"><b>View Pro Grn</b></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="deductRawQty.php"><b>deduct Raw Qty</b></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="viewOutsourcerStock.php"><b>view Out Stock</b></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="productDeduct.php"><b>Pro Deduct</b></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="rawInHouseCal.php"><b>Raw INh Cal</b></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="rawOutCal.php"><b>Raw Out Cal</b></a>
            </li>
            
          </ul>
        </div>
      </nav>

          <!--close nav bar  -->