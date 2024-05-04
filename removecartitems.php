<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style type="text/css">
        /*.container {
            position: relative;
            width: 100%;
            min-height: 100vh;
            justify-content: center;
            align-items: center;
            background: lightblue;
            transition: 0.5s;
            padding: 20px;
        }*/
        .container#blur.active {
            filter: blur(20px);
            pointer-events: none;
            user-select: none;
        }
    </style>
</head>
<body>
    <div class="container" id="blur">
        <div class="content">
            <h2>ruew teiwuo ghskl gfdsh gfdshl bvxm bvcm rtewui gfsj gfsj gfsj gfsj gfdsh ghsdj trwey7 cbxn 46372tryw gfs vcxbm 43782 treyiw ghjs xm gfs trewyi 453268 vcxbn gfajtr3y gfhwgjfyu gdyhsft7 ygf t78</h2>
            <img src="Images/Decoration/bulb.jpg" alt="Bulb">
            <a href="#" onclick="toggle()">Read more</a>
        </div>
    </div>
    <script type="text/javascript">
        function toggle() {
            var blur = document.getElementById('blur');
            blur.classList.toggle('active');
        }
    </script>
</body>
</html>
