<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Attendance System</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

    <!-- Data Table -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.css" />

    <style>
        /* Importing Google Fonts */
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@500&display=swap');

         /* CSS styles */
        * {
            margin: 0;
            padding: 0;
            font-family: 'Times New Roman', sans-serif;
        }

        body {
            background: linear-gradient(to bottom, rgba(255,255,255,0.15) 0%, rgba(0,0,0,0.15) 100%), radial-gradient(at top center, rgba(255,255,255,0.40) 0%, rgba(0,0,0,0.40) 120%) #989898;
            background-blend-mode: multiply,multiply;
            background-attachment: fixed;
            background-repeat: no-repeat;
            background-size: cover;
        }

        .main {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 91.5vh;
        }

        .employee-container {
            height: 90%;
            width: 90%;
            border-radius: 20px;
            padding: 40px;
            background-color: rgba(255, 255, 255, 0.8);
        }

        .employee-container > div {
            box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
            border-radius: 10px;
            padding: 30px;
            height: 100%;
        }

        .title {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        table.dataTable thead > tr > th.sorting, table.dataTable thead > tr > th.sorting_asc, table.dataTable thead > tr > th.sorting_desc, table.dataTable thead > tr > th.sorting_asc_disabled, table.dataTable thead > tr > th.sorting_desc_disabled, table.dataTable thead > tr > td.sorting, table.dataTable thead > tr > td.sorting_asc, table.dataTable thead > tr > td.sorting_desc, table.dataTable thead > tr > td.sorting_asc_disabled, table.dataTable thead > tr > td.sorting_desc_disabled {
            text-align: center;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <!-- Navbar content -->
    <a class="navbar-brand ml-4" href="#"><img src = "lotus.png">Lotus Electrical</a>
        <a class="navbar-brand ml-4" href="#">QR Code Attendance System</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="./index.php">Home <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="./masterlist.php">List of Employees</a>
                </li>
                <li class="nav-item ">
                    <a class="nav-link" href="./masterlist.php">Time In</a>
                </li> 
                <li class="nav-item ">
                    <a class="nav-link" href="./masterlist.php">Time Out</a>
                </li>
                <li class="nav-item ">
                    <a class="nav-link" href="./masterlist.php">No of Hrs</a>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item mr-3">
                    <a class="nav-link" href="#">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="main">
        <!-- Main content -->
        <div class="employee-container">
            <!-- Employee list container -->
            <div class="employee-list">
                <!-- Title and add employee button -->
                <div class="title">
                    <!-- Title -->
                    <h4>List of Employees</h4>
                    <!-- Button to add employee -->
                    <button class="btn btn-dark" data-toggle="modal" data-target="#addEmployeeModal">Add Employee</button>
                </div>
                <hr>
                <!-- Table for displaying employee information -->
                <div class="table-container table-responsive">
                    <table class="table text-center table-sm" id="employeeTable">
                        <!-- Table headers -->
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Name</th>
                                <th scope="col">Department</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php 
                                // Include the database connection file
                                include ('./conn/conn.php');

                                // Prepare SQL statement to select all records from tbl_employee table
                                $stmt = $conn->prepare("SELECT * FROM tbl_employee");
                                // Execute the prepared statement
                                $stmt->execute();
                     
                                // Fetch all rows from the result set into an associative array
                                $result = $stmt->fetchAll();
                
                                // Loop through each row in the result set
                                foreach ($result as $row) {
                                    // Extract data from the current row
                                    $employeeID = $row["tbl_employee_id"];
                                    $employeeName = $row["employee_name"];
                                    $employeeDepartment = $row["employee_department"];
                                    $qrCode = $row["generated_code"];
                                ?>

                                <tr>
                                    <!-- Employee ID -->
                                    <th scope="row" id="employeeID-<?= $employeeID ?>"><?= $employeeID ?></th>
                                    <!-- Employee Name -->
                                    <td id="employeeName-<?= $employeeID ?>"><?= $employeeName ?></td>
                                    <!-- Employee Department -->
                                    <td id="employeeDepartment-<?= $employeeID ?>"><?= $employeeDepartment ?></td>
                                    <td>
                                        <div class="action-button">
                                            <!-- Button to view QR code modal -->
                                            <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#qrCodeModal<?= $employeeID ?>"><img src="https://cdn-icons-png.flaticon.com/512/1341/1341632.png" alt="" width="16"></button>

                                            <!-- QR Modal -->
                                            <div class="modal fade" id="qrCodeModal<?= $employeeID ?>" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                    <div class="modal-header">
                                                        <!-- Modal title with employee's name -->
                                                        <h5 class="modal-title"><?= $employeeName ?>'s QR Code</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body text-center">
                                                        <!-- QR code image with employee's generated QR code -->
                                                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=<?= $qrCode ?>" alt="" width="300">
                                                    </div>
                                                    <div class="modal-footer">
                                                        <!-- Button to close modal -->
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                    </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Button to update employee information -->
                                            <button class="btn btn-secondary btn-sm" onclick="updateEmployee(<?= $employeeID ?>)">&#128393;</button>
                                            <!-- Button to delete employee -->
                                            <button class="btn btn-danger btn-sm" onclick="deleteEmployee(<?= $employeeID ?>)">&#10006;</button>
                                        </div>
                                    </td>
                                </tr>

                                <?php
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    <!-- Add Modal -->
    <div class="modal fade" id="addEmployeeModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="addEmployee" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <!-- Modal title -->
                    <h5 class="modal-title" id="addEmployee">Add Employee</h5>
                    <!-- Button to close modal -->
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Form for adding a new employee -->
                    <form action="./endpoint/add-employee.php" method="POST">
                        <div class="form-group">
                            <label for="employeeName">Full Name:</label>
                            <!-- Input field for entering the employee's name -->
                            <input type="text" class="form-control" id="employeeName" name="employee_name" required>
                        </div>
                        <div class="form-group">
                            <label for="employeeDepartment">Department:</label>
                            <!-- Input field for entering the employee's department -->
                            <input type="text" class="form-control" id="employeeDepartment" name="employee_department" required>
                        </div>
                        <!-- Button to generate QR code -->
                        <button type="button" class="btn btn-secondary form-control qr-generator" onclick="generateQrCode()">Generate QR Code</button>

                        <!-- Container for QR code display -->
                        <div class="qr-con text-center" style="display: none;">
                            <!-- Hidden input field to store generated QR code -->
                            <input type="hidden" class="form-control" id="generatedCode" name="generated_code">
                            <p>Take a pic with your qr code.</p>
                            <!-- Image tag to display the QR code -->
                            <img class="mb-4" src="" id="qrImg" alt="">
                        </div>
                        <!-- Modal footer -->
                        <div class="modal-footer modal-close" style="display: none;">
                            <!-- Button to close modal --> 
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <!-- Button to submit form and add employee to the list -->
                            <button type="submit" class="btn btn-dark">Add List</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Update Modal -->
    <div class="modal fade" id="updateEmployeeModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="updateEmployee" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <!-- Modal title -->
                    <h5 class="modal-title" id="updateEmployee">Update Employee</h5>
                    <!-- Button to close modal -->
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    
                    <!-- Form for updating employee's name -->  
                    <form action="./endpoint/update-employee.php" method="POST">
                    <!-- Hidden input field to store the employee ID -->   
                    <input type="hidden" class="form-control" id="updateEmployeeId" name="tbl_employee_id">
                        <div class="form-group">
                            <label for="updateEmployeeName">Full Name:</label>
                            <!-- Input field for updating the employee's name -->
                            <input type="text" class="form-control" id="updateEmployeeName" name="employee_name">
                        </div>
                        <div class="form-group">
                            <label for="updateEmployeeDepartment">Department:</label>
                            <!-- Input field for updating the employee's department -->
                            <input type="text" class="form-control" id="updateEmployeeDepartment" name="employee_department">
                        </div>
                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <!-- Button to close modal -->
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <!-- Button to submit form and update employee information -->
                            <button type="submit" class="btn btn-dark">Add</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>

    <!-- Data Table -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.js"></script>

    <script>
        // Function to initialize DataTable for employeeTable
        $(document).ready( function () {
            $('#employeeTable').DataTable();
        });

        // Function to handle updating employee information
        function updateEmployee(id) {
            // Show the update employee modal
            $("#updateEmployeeModal").modal("show");

            // Fetch the current employee's ID, name, and department
            let updateEmployeeId = $("#employeeID-" + id).text();
            let updateEmployeeName = $("#employeeName-" + id).text();
            let updateEmployeeDepartment = $("#employeeDepartment-" + id).text();

            // Set the values in the update employee modal form
            $("#updateEmployeeId").val(updateEmployeeId);
            $("#updateEmployeeName").val(updateEmployeeName);
            $("#updateEmployeeDepartment").val(updateEmployeeDepartment);
        }

        // Function to handle deleting an employee
        function deleteEmployee(id) {
            // Ask for confirmation before deleting the employee
            if (confirm("Do you want to delete this employee?")) {
                // Redirect to the delete employee endpoint with the employee ID
                window.location = "./endpoint/delete-employee.php?employee=" + id;
            }
        }

         // Function to generate a random code of a specified length
        function generateRandomCode(length) {
            // Define the characters to be used for generating the random code
            const characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
            // Initialize an empty string to store the generated random code
            let randomString = '';

            // Loop through the specified length to generate each character of the code
            for (let i = 0; i < length; i++) {
                // Generate a random index within the range of characters
                const randomIndex = Math.floor(Math.random() * characters.length);
                // Append the character at the random index to the randomString
                randomString += characters.charAt(randomIndex);
            }

            // Return the generated random code
            return randomString;
        }

        // Function to generate a QR code
        function generateQrCode() {
            // Get the QR code image element
            const qrImg = document.getElementById('qrImg');

            // Generate a random code
            let text = generateRandomCode(10);
            // Set the generated code in the hidden input field
            $("#generatedCode").val(text);

            // If the generated code is empty, display an alert
            if (text === "") {
                alert("Please enter text to generate a QR code.");
                return;
            } else {
                // Construct the API URL for generating the QR code
                const apiUrl = `https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=${encodeURIComponent(text)}`;

                // Set the QR code image source
                qrImg.src = apiUrl;

                // Disable pointer events for input fields and update display styles
                document.getElementById('employeeName').style.pointerEvents = 'none';
                document.getElementById('employeeDepartment').style.pointerEvents = 'none';
                document.querySelector('.modal-close').style.display = '';
                document.querySelector('.qr-con').style.display = '';
                document.querySelector('.qr-generator').style.display = 'none';
            }
        }
    </script>
    
</body>
</html>
