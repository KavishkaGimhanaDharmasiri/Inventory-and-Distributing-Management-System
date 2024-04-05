<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<style type="text/css">
		body {
            margin: 0;
            padding: 0;
            height: 100vh;
            position: relative;
            margin: 8px;
        }

        .options-container {
            background-color: #fff;
            border-radius: 15px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 100%;
            max-width: 600px;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
            border: 1px solid #45a049;
           
        }

        .option {
            width: 45%;
            margin-bottom: 20px;
            background-color: #4caf50;
            color: #fff;
            text-align: center;
           padding: 15px;
            border-radius: 15px;
            cursor: pointer;
            padding-right:5px;
            
    
        }
        a{
            text-decoration: none;
            color:white;
            text-align: center;
    

        }

        .option:hover {
            background-color: #45a049;
        }

        @media (max-width: 600px) {
            .option {
                width: 100%;
            }
        }
	</style>
</head>
<body>
	 <div class="options-container">
	 	<a href="add_customer.php" class="option" id="option1"><div>Add Seller</div></a>
    	<a href="add_Route.php" class="option" id="option2"><div>Add Route</div></a>
    	<a href="manage_employee.php" class="option" id="option3"><div>Manage Employee</div></a>
    
	 </div>
</body>
</html>