<!DOCTYPE html>

<html>

    <head>

        <title>Sallon Arber</title>

        <link rel="stylesheet"  type="text/css" href="styles/homepage.css?<?php echo time(); ?>">
        <link rel="stylesheet"  type="text/css" href="styles/shop.css?<?php echo time(); ?>">
        <link rel="stylesheet" type="text/css" href="styles/responsiveNav.css?<?php echo time(); ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <script type="text/javascript" src="bookJs.js" defer> </script>

        <link rel = "icon" href = "pictures/icon.png" type = "image/x-icon">

    </head>

    <body>

        <div class="responsive">
                <a class="logoPosition" href="index"> <img class="logoImg" src="pictures/logo1.png"> </a>
        
                <span id="open" style="font-size:30px;cursor:pointer" >
                    <img id="navImg" src="pictures/hamburger.png" style="height: 30px; width: 40px;"> 
                 </span>
        </div>



        <nav class="navMenu">

            <a href="index.php">Home</a>

            <a href="book.php">Book</a>

            <a href="about.php">About</a>

            <a href="academy.php">Academy</a>&nbsp; &nbsp;&nbsp; &nbsp;

            <a href="shop.php">Shop</a>


          </nav>
          
            <div id="mySidenav" class="sidenav">
            <a href="index.php">Home</a>

            <a href="book.php">Book</a>

            <a href="about.php">About</a>

            <a href="academy.php">Academy</a>&nbsp; &nbsp;&nbsp; &nbsp;

            <a href="shop.php">Shop</a>
              </div>


          <br><br><br><br><br><br><br><br>
    

        <?php
            require_once('phpFiles/databaseFunctions.php');
            
            $products = getAllProducts();
            
            echo '<div class="rowProduct">';
            
            $cnt = 0;
                        
            foreach($products as $product){
                echo "<div class='product'> <img class='productImg' src='{$product['product_image_path']}'><div class='info'><label class='tittleProd'> {$product['product_name']} </label><br><label class='priceProd'> {$product['product_price']} LEK </label></div></div> ";
                
                $cnt++;
                
                if($cnt%3==0){
                    echo '</div><br/><div class="rowProduct">';
                }
            }
        ?>
    






    </body>

</html>