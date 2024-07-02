<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1,maximum-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="/style/mobile.css">
    <link rel="stylesheet" type="text/css" href="/style/style.css">
    <style>
        .slideshow-container {
            position: relative;
            height: 100vh;
        }


        /* Next & previous buttons */
        .prev,
        .next {
            cursor: pointer;
            position: absolute;
            top: 130px;
            width: auto;
            color: black;
            right: -110px;
            font-size: 50px;
            transition: 0.6s ease;
            border-radius: 0 3px 3px 0;
            user-select: none;
            padding: 10px;
        }

        /* Position the "next button" to the right */
        .next {
            right: 0;
            border-radius: 3px 0 0 3px;
        }

        /* On hover, add a black background color with a little bit see-through */

        /* Caption text */
        .text {
            color: #f2f2f2;
            font-size: 15px;
            padding: 8px 12px;
            position: absolute;
            bottom: 8px;
            text-align: center;
            animation-name: fade;
            animation-duration: 1.5s;

        }

        /* Number text (1/3 etc) */
        .numbertext {
            color: #f2f2f2;
            font-size: 12px;
            padding: 8px 12px;
            position: absolute;
            top: 0;

        }

        /* The dots/bullets/indicators */

        .active,
        .dot:hover {
            background-color: red;
            animation-name: fade;
            animation-duration: 1.5s;
        }

        /* Fading animation */
        .mySlides {
            animation-name: fade;
            animation-duration: 1.5s;
        }

        @keyframes fade {
            from {
                opacity: .4
            }

            to {
                opacity: 1
            }
        }

        /* On smaller screens, decrease text size */
        @media only screen and (max-width: 300px) {

            .prev,
            .next,
            .text {
                font-size: 11px
            }
        }

        .container {

            background: lightgreen;
            height: 300px;

        }

        .mySlides {
            height: 100vh;
        }
    </style>
</head>

<body>

    <!-- Simulate a smartphone / tablet -->
    <div class="mobile-container">

        <!-- Top Navigation Menu -->
        <div class="topnav">
            <a href="javascript:void(0)" onclick="back()" class="back-link" style="font-size: 20px;"><i class="fa fa-angle-left" style="float:left;font-size:25px;"></i><b>&nbsp;&nbsp;&nbsp;<span style="font-size: 17px;">about</span></a>

        </div>
        <div class="slideshow-container">

            <div class="mySlides">
                <div class="container">
                    <h3>About us</h3>
                    <br>
                    <label>Lotus Hardware And Electricals(PVT).LTD</label>
                    <br>
                    <label style="font-size: 14px; text-align:center;padding:10px;line-space:10px;">
                        As a leading company of Sri Lanka We Proudly Provide reliable Hardware And Electrical solution tht Most Fit for Your life.
                    </label>
                </div>
            </div>

            <div class="mySlides">
                <div class="container" style="background-color:indianred;">
                    <h3>Contact Us</h3><br>
                    <label>Lotus Hardware And Electricals(PVT).LTD</label>
                    <br>
                    <label>Lotus Hardware And Electricals(PVT).LTD<br>Karadagoda,<br>Thihagoda,<br>Mathare.
                        Main Branch</label>
                </div>
            </div>


            <a class="next" onclick="plusSlides(1)"><i class="fa fa-angle-right"></i></a>

        </div>


        <script>
            function openNav() {
                document.getElementById("mySidepanel").style.width = "150px";
            }

            function closeNav() {
                document.getElementById("mySidepanel").style.width = "0";
            }

            function back() {
                window.history.back();
            }
            let slideIndex = 1;
            showSlides(slideIndex);

            function plusSlides(n) {
                showSlides(slideIndex += n);
            }

            function currentSlide(n) {
                showSlides(slideIndex = n);
            }

            function showSlides(n) {
                let i;
                let slides = document.getElementsByClassName("mySlides");
                let dots = document.getElementsByClassName("dot");
                if (n > slides.length) {
                    slideIndex = 1
                }
                if (n < 1) {
                    slideIndex = slides.length
                }
                for (i = 0; i < slides.length; i++) {
                    slides[i].style.display = "none";
                }
                for (i = 0; i < dots.length; i++) {
                    dots[i].className = dots[i].className.replace(" active", "");
                }
                slides[slideIndex - 1].style.display = "block";
                dots[slideIndex - 1].className += " active";
            }
        </script>

</body>

</html>