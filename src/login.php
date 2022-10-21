<?php 

  session_start();



  if(isset($_SESSION['user'])){
    
    if($_SESSION['user']['level']==3){
        header("Location: barber-admin/index.php");
    }
    else if($_SESSION['user']['level']==2){
        header("Location: barber-employee/index.php");
    }
    else{
        header("Location: book.php");    
    }
    }

?>

<!DOCTYPE html>

<html>



<head>

  <title>Sallon Arber</title>

  <link rel="stylesheet" type="text/css" href="styles/homepage.css?<?php echo time(); ?>">

  <link rel="stylesheet" type="text/css" href="styles/login.css?<?php echo time(); ?>" >
  
  <link rel="stylesheet" type="text/css" href="styles/responsiveNav.css?<?php echo time(); ?>">

  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

  <script async  type="text/javascript" src="bookJs.js" defer> </script>

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

            <a href="about.php">About</a>

            <a href="academy.php">Academy</a>&nbsp; &nbsp;&nbsp; &nbsp;

            <a href="shop.php">Shop</a>

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








  <br><br><br><br><br><br><br>



  <form action="<?php echo $_SERVER['PHP_SELF']?>" method="POST">

    <div class="card" >



      <br>

      <label id="tittleText">Login</label>

      <br>

      <br>



      <label id="labelText">Email</label>

      <input name='email' class="inputRegisterLogin" id="email" type="email">

      <br>

      <label id="labelText">Password</label>

      <input name='password' class="inputRegisterLogin" id="password" type="password">



      <br>

      <input name='submit' type='submit' class="fromButton" value='Login'>

      <br>



      <a href='register'><label id="labelText">Register</label> </a>

      <br>



    </div>

  </form>

<?php

  if(isset($_POST['submit'])){

    $clean = array();



    if(isset($_POST['email']) && !empty($_POST['email']) && isset($_POST['password']) && !empty($_POST['password'])){

      require_once('phpFiles/databaseFunctions.php');



      $clean['email']  = $_POST['email'];

      $clean['password'] = $_POST['password'];



      loginUser($clean['email'],$clean['password']);

    }else{

      echo "<script src=\"https://unpkg.com/sweetalert/dist/sweetalert.min.js\"></script>";
      echo '<script type="text/javascript">';
      echo 'setTimeout(function () { swal("Kujdes!","Vendos email dhe password!","warning");';
      echo '}, 500);</script>';

    }

  }

?>
<br><br><br>
  <span class="copyright">Copyright &copy; Barbershop Website by "5 Veta" Epoka Team"</span>
  <br>

</body>



</html>