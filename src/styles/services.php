<?php
session_start();

if(isset($_SESSION['user'])){

        if($_SESSION['user']['level']!=3){

          header("Location: ~eponari19/book.php");

        }

      }else{
          header("Location: ~eponari19/book.php");
      }
?>

<!DOCTYPE html>
<html>

<head>
  <title>Sallon Arber</title>
  <link rel="stylesheet" type="text/css" href="styles/homepage.css?<?php echo time(); ?>">
  <link rel="stylesheet" type="text/css" href="styles/login.css?<?php echo time(); ?>">
  <link rel="stylesheet" type="text/css" href="styles/responsiveNav.css?<?php echo time(); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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
            <a href="login.php">Logout</a>
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

<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
<div class=card>
    <label for="sname">Service name </label><br>
    <input type="text" id="sname" name="service_name" value="<?php echo isset($_POST['sname']) ? htmlentities($_POST['sname']) : ''; ?>"><br>
    <label for="sprice">Service price</label><br>
    <input type="number" id="sprice" name="service_price" value="<?php echo isset($_POST['sprice']) ? htmlentities($_POST['sprice']) : ''; ?>"><br>    
    <label for="sduration">Service duration</label><br>
    <select id="sduration" name="service_duration"><br>
    <?php
        for($i=0;$i<count($duration);$i++){
            echo '<option value="'.$i.'">'.$duration[$i].'</option>'; 
        }
    ?>
    </select>
    <input type="submit" name="submit" value="Submit data" />
    </div>
</form>

<?php
 if(isset($_POST['submit'])){
    $error_messages=array();
    $clean=array();
    
    if(isset($_POST['service_name']) && ctype_alnum($_POST['sname'])){
        $clean['service_name']=$_POST['service_name'];
    } else{
        $error_messages['service_name'] = "You should enter only letters and numbers.";
    }   
  
    if(isset($_POST['service_price']) && ctype_alnum($_POST['sprice'])){
        $clean['service_price']=$_POST['service_price'];
    } else{
        $error_messages['service_price'] = 'You should enter only numbers and currency.';
    }
    if(isset($_POST['service_duration'])){
        $clean['service_duration']=$_POST['service_duration'];
    }else{
        $error_messages['service_duration'] = "Service duration should be set."; 
    }

    if(empty($error_messages)){
        require_once("phpFiles/databaseFunctions.php");
        insertService($clean);
    }
}
 ?>