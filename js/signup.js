function check_form(){
    document.getElementById("form").addEventListener("submit", function(event) {

    var fname = document.getElementById("fname").value;
    var lname = document.getElementById("lname").value;
    var dob = document.getElementById("dob").value;
    var address = document.getElementById("address").value;
    var tnumber = document.getElementById("tnumber").value;
    var email = document.getElementById("email").value;
    var password1 = document.getElementById("password1").value;
    var password2 = document.getElementById("password2").value;

    var letters = /^[A-Za-z]+$/;
    var pattern =/^([0-9]{2})\/([0-9]{2})\/([0-9]{4})$/;
    var phoneno = /^\d{10}$/;
    var mailFormat =  /\S+@\S+\.\S+/;

    if(fname == null || fname == "" ){
        text='**Please enter your Name**';
        document.getElementById("name_msg").innerHTML = text;
        event.preventDefault();
        return false;
    } else if(!fname.match(letters)){
        text='**Enter Characters Only**';
        document.getElementById("name_msg").innerHTML = text;
        event.preventDefault();
        return false;
    }



    else if(lname == null || lname == "" ){
        text='**Please enter your Name**';
        document.getElementById("name_msg").innerHTML = text;
        event.preventDefault();
        return false;
    } else if(!lname.match(letters)){
        text='**Enter Characters Only**';
        document.getElementById("name_msg").innerHTML = text;
        event.preventDefault();
        return false;
    }



    else if(!pattern.test(dob)){
        text='**Invalide Date of Birth (DD-MM-YYYY)**';
        document.getElementById("dob_msg").innerHTML = text;
        event.preventDefault();
        return false;
    }


    
    else if(address == null || address.trim() === ""){
        text='**Enter Your Address**';
        document.getElementById("address_msg").innerHTML = text;
        event.preventDefault();
        return false;
    }



    else if(tnumber == null || tnumber.trim() === ""){
        text='**Enter Your Number**';
        document.getElementById("number_msg").innerHTML = text;
        event.preventDefault();
        return false;
    }else if(!tnumber.match(phoneno)){
        text='**Enter Numbers Only**';
        document.getElementById("number_msg").innerHTML = text;
        event.preventDefault();
        return false;
    }

    

    else if(email == null || email.trim() === ""){
        text='**Enter Your Email**';
        document.getElementById("email_msg").innerHTML = text;
        event.preventDefault();
        return false;
    }else if(!email.match(mailFormat)){
        text='**Invalide Email Format**';
        document.getElementById("email_msg").innerHTML = text;
        event.preventDefault();
        return false;
    }



    else if(password1 == null || password1.trim() === ""){
        text='**Enter Your Password**';
        document.getElementById("pwd1_msg").innerHTML = text;
        event.preventDefault();
        return false;
    } else if(password2 == null || password2.trim() === ""){
        text='**Enter Your Confirm Password**';
        document.getElementById("pwd2_msg").innerHTML = text;
        event.preventDefault();
        return false;
    }
    else if (password1 !== password2) {
        text='**Password do not match**';
        document.getElementById("pwd1_msg").innerHTML = text;
        event.preventDefault();
        return false;
    }
}
)};