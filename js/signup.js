function check_form(){
    var name = document.getElementById("name").value;
    var dob = document.getElementById("dob").value;
    var address = document.getElementById("address").value;
    var tnumber = document.getElementById("tnumber").value;
    var email = document.getElementById("email").value;
    var postalcode = document.getElementById("postalcode").value;
    var password1 = document.getElementById("password1").value;
    var password2 = document.getElementById("password2").value;

    if(name == null || name.trim() === ""){
        alert("Please enter your name.");
        return false;
    } else if(address == null || address.trim() === ""){
        alert("Please enter your address.");
        return false;
    } else if(tnumber == null || tnumber.trim() === ""){
        alert("Please enter your number.");
        return false;
    } else if(email == null || email.trim() === ""){
        alert("Please enter your email.");
        return false;
    } else if(postalcode == null || postalcode.trim() === ""){
        alert("Please enter your postalcode.");
        return false;
    } else if(password1 == null || password1.trim() === ""){
        alert("Please enter your password.");
        return false;
    } else if(password2 == null || password2.trim() === ""){
        alert("Please enter your password.");
        return false;
    }
  
    if (password1 !== password2) {
        alert("Passwords do not match.");
        return false;
    }

    window.location.href = "Cart.php";
    document.getElementById("form").submit();
};

document.getElementById("button").addEventListener("click", check_form);