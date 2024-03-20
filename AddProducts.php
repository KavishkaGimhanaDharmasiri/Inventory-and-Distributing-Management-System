<?php include('header.php'); ?>

          <div class="container"><h1>Add Products</h1></div>

                            <!--center container-->
                            <div class="container min-vh-100 d-flex justify-content-center align-items-center">

                                <form id="myForm" action="addproduct.php" method="post" class="row g-3">
                                    <div class="col-md-6">
                                      <label for="inputEmail4" class="form-label">Product ID</label>
                                      <input type="text" name="productId" class="form-control" id="inputcustomername">
                                    </div>
                                

                                    <div class="col-md-6">
                                      <label for="inputPassword4" class="form-label">Product Name</label>
                                      <input type="text" name="productName" class="form-control" id="inputshopname">
                                    </div>
                                    <div class="col-md-6">
                                      <label for="inputMainCategory" class="form-label">Main Category</label>
                                      <input type="text" name="MainCategory" class="form-control" id="inputMainCategory">
                                    </div>
                                    <div class="col-md-6">
                                      <label for="inputSubCategory" class="form-label">Sub Category</label>
                                      <input type="text" name="SubCategory" class="form-control" id="inputSubCategory">
                                    </div>
              
                                    <div class="col-md-6">
                                      <label for="inputCity" class="form-label">Cost Price</label>
                                      <input type="text" name="costPrice" class="form-control" id="costPrice">
                                    </div>
            
                                    <div class="col-md-6">
                                        <label for="inputCashPrice" class="form-label">Cash Price</label>
                                        <input type="text" name="CashPrice" class="form-control" id="CashPrice">
                                      </div>
                                      <!--margin-->
                                      <p id="result">result</p>

                                      <script>
                                        function addNumbers() {
                                          // Get values from input fields
                                          var num1 = parseFloat(document.getElementById('costPrice').value);
                                          var num2 = parseFloat(document.getElementById('CashPrice').value);
                                    
                                          // Check if input is valid
                                          if (isNaN(num1) || isNaN(num2)) {
                                            alert("Please enter valid numbers");
                                            return;
                                          }
                                    
                                          // Perform addition
                                          var sum = num1 + num2;
                                    
                                          // Display the result
                                          document.getElementById('result').innerText = "Result: " + sum;
                                        }
                                      </script>
                                      <!--
                                      <script>

                                        var costPrice = costPrice;
                                        var CashPrice = CashPrice;
                                        var sum = (((CashPrice-costPrice)/costPrice)*100);
                                        document.write("Cash Margin = " + sum);
                                      </script>

                                      -->

                                      <div class="col-md-6">
                                        <label for="inputCreditPrice" class="form-label">Credit Price</label>
                                        <input type="text" name="CreditPrice" class="form-control" id="inputCreditPrice">
                                      </div>

                                      <div class="col-md-6">
                                        <label for="inputChequePrice" class="form-label">Cheque Price</label>
                                        <input type="text" name="ChequePrice" class="form-control" id="inputChequePrice">
                                      </div>
                                      <!---Calculation-->



            
                                    <div class="col-2">
                                      <button type="submit" class="btn btn-primary">Add Product</button>
                                    </div>

                                    <div class="col-1">
                                        <button type="reset" class="btn btn-danger">Clear</button>
                                    </div>

                                    <div class="col-1">
                                      <button type="edit" class="btn btn-Warning">Edit</button>
                                  </div>

                                  <div class="col-1">
                                    <button type="delect" class="btn btn-dark">Delete</button>
                                </div>

                                  </form>

                                  <script>
                                    document.getElementById("myForm").addEventListener("submit", function() {
                                        // Clear form fields after submission
                                        document.getElementById("myForm").reset();
                                    });
                                    </script>
            
                                
            
            
            
                              </div>






<?php include('footer.php');?>