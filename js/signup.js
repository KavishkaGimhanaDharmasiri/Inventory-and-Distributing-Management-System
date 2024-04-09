function check_form(){
    document.getElementById("form").addEventListener("submit", function(event) {

    var name = document.getElementById("name").value;
    var dob = document.getElementById("dob").value;
    var address = document.getElementById("address").value;
    var tnumber = document.getElementById("tnumber").value;
    var email = document.getElementById("email").value;
    var postalcode = document.getElementById("postalcode").value;
    var password1 = document.getElementById("password1").value;
    var password2 = document.getElementById("password2").value;

    var letters = /^[A-Za-z]+$/;
    var pattern =/^([0-9]{2})\/([0-9]{2})\/([0-9]{4})$/;
    var phoneno = /^\d{10}$/;
    var mailFormat =  /\S+@\S+\.\S+/;

    if(name == null || name == "" ){
        
        text='Please enter your Name';
        document.getElementById("message").innerHTML = "**Fill the password please!"; 
        event.preventDefault();
        return false;
    } else if(!name.match(letters)){
        alert("Enter characters only");
        event.preventDefault();
        return false;
    }


    if(!pattern.test(dob)){
        alert("Invalide Date of Birth.(DD-MM-YYYY)");
        event.preventDefault();
        return false;
    }


    
    if(address == null || address.trim() === ""){
        //alert("Please enter your address.");
        event.preventDefault();
        return false;
    }


    if(tnumber == null || tnumber.trim() === ""){
        alert("Please enter your number.");
        event.preventDefault();
        return false;
    }else if(!tnumber.match(phoneno)){
        alert("Enter numbers only");
        event.preventDefault();
        return false;
    }

    

    if(email == null || email.trim() === ""){
        alert("Please enter your email.");
        event.preventDefault();
        return false;
    }else if(!email.match(mailFormat)){
        alert("Invalide email format");
        event.preventDefault();
        return false;
    }


    if(password1 == null || password1.trim() === ""){
        alert("Please enter your password.");
        event.preventDefault();
        return false;
    } else if(password2 == null || password2.trim() === ""){
        alert("Please enter your password.");
        event.preventDefault();
        return false;
    }
    else if (password1 !== password2) {
        alert("Passwords do not match.");
        event.preventDefault();
        return false;
    }
}
)};