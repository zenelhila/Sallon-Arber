<?php 
session_start();
if(isset($_SESSION['user'])){

        if($_SESSION['user']['level']!=3){

          header("Location: ../login");

        }

      }else{
          header("Location: ../login");
      }
if(isset($_POST['submit'])){
    $errorMessages = array();
    $clean = array();

    if(isset($_POST['product_name']) AND ctype_alnum($_POST['product_name'])){
            $clean['product_name'] = $_POST['product_name'];
    } else {
            $errorMessages['product_name'] = 'The product name should contain only characters and digits';
    }
    
    if(isset($_POST['product_price']) AND is_numeric($_POST['product_price'])){ 
        $clean['product_price']=$_POST['product_price'];
    } else {
        $errorMessages['product_price'] = "The product price should be a number";
    }
    
    if(isset($_FILES['fileToUpload'])){//Picture Upload
        $imgfolder="img/";
        $type = pathinfo($_FILES['fileToUpload']['name'], PATHINFO_EXTENSION);
            
        $file = $imgfolder.$clean['product_name'].date("d:m:y").".".$type;

        if(move_uploaded_file($_FILES['fileToUpload']['tmp_name'],$file)){
            $clean['fileToUpload'] = $file;
        }else{
            $errorMessages['fileToUpload'] = "Can't upload file.";
        }
    }

    if(empty($errorMessages)){
        require_once("phpFiles/databaseFunctions.php");

        insertProduct($clean);
        
        header("Location: shop");
    }
}
?>

<!DOCTYPE html>
<html>

<head>
  <title>Sallon Arber</title>
  <link rel="stylesheet" type="text/css" href="styles/homepage.css?<?php echo time(); ?>">
  <link rel="stylesheet" type="text/css" href="styles/login.css?<?php echo time(); ?>">
  <link rel="stylesheet"  type="text/css" href="styles/shop.css?<?php echo time(); ?>">
  <link rel="stylesheet" type="text/css" href="styles/responsiveNav.css?<?php echo time(); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <script type="text/javascript" src="bookJs.js" defer> </script>
  <link rel = "icon" href = "pictures/icon.png" type = "image/x-icon">
</head>

<body>

        <div class="responsive">
                <a class="logoPosition" href="index.php"> <img class="logoImg" src="pictures/logo1.png"> </a>
        
                <span id="open" style="font-size:30px;cursor:pointer" >
                    <img id="navImg" src="pictures/hamburger.png" style="height: 30px; width: 40px;"> 
                 </span>
        </div>

  <nav class="navMenu">
  <a href="index.php">Home</a>

<a href="book.php">Book</a>

<a href="<?php echo $registerEmployee?"register.php":"about.php"?>"><?php echo $registerEmployee?"Register Employee":"About"?></a>

<a href="academy.php">Academy</a>&nbsp; &nbsp;&nbsp; &nbsp;

<a href="shop.php">Shop</a>

<a href="appointments.php">Takimet</a>&nbsp;&nbsp;&nbsp;&nbsp;

<a href="logout.php">Logout</a>

</nav>

<div id="mySidenav" class="sidenav">
  <a href="javascript:void(0)" id="close" class="closebtn">&times;</a>
  <a href="index.php">Home</a>
  <a href="book.php">Book</a>
  <a href="academy.php">Academy</a>
  <a href="shop.php">Shop</a>
  <a href="appointments.php">Takimet</a>
  <a href="logout.php">Logout</a>
              </div>


    <br><br><br><br><br>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method = "post" enctype="multipart/form-data">
    <div class=card>

    <br><br>
    <label  class="productsLabel" for="product_name">Product Name:</label><br>
    <input type="text" name = "product_name" value = "<?php echo isset($_POST['product_name']) ? htmlentities($_POST['product_name']) : ''; ?>"><br>
    
    <label  class="productsLabel" for="product_price">Product Price:</label><br>
    <input type="text" name = "product_price" value = "<?php echo isset($_POST['product_price']) ? htmlentities($_POST['product_price']) : ''; ?>"><br>

    <label  class="productsLabel" for="product_picture">Upload a picture: </label><br>

    <div class="upload-btn-wrapper">
        <button class="btn">Upload a photo</button>
    <input type="file" name = "fileToUpload" id = "fileToUpload"><br>
    </div>

    <br><br>
    <div class="upload-btn-wrapper">
        <button class="btn">Submit</button>
    <input type="submit" value = "Submit" name = "submit">
    </div>

    <br><br>

    </div>
</form>

</body>
</html>