<?php 

  session_start();



  $registerEmployee=false;



  if(isset($_SESSION['user'])){

    if($_SESSION['user']['level']!=3){

      header("Location: book");

    }else{

      $registerEmployee=true;

    }

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

  <script type="text/javascript" src="bookJs.js" defer> </script>

 <link rel = "icon" href = "pictures/icon.png" type = "image/x-icon">

 <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
</head>



<body>

        <div class="responsive">
                <a class="logoPosition" href="index"> <img class="logoImg" src="pictures/logo1.png"> </a>
        
                <span id="open" style="font-size:30px;cursor:pointer">
                    <img id="navImg" src="pictures/hamburger.png" style="height: 30px; width: 40px;"> 
                 </span>
        </div>

  <nav class="navMenu">

            <a href="/">Home</a>

            <a href="book">Book</a>

            <a href="<?php echo $registerEmployee?"register":"about"?>"><?php echo $registerEmployee?"Register Employee":"About"?></a>

            <a href="academy">Academy</a>&nbsp; &nbsp;&nbsp; &nbsp;

            <a href="shop">Shop</a>

          </nav>
          
  <div id="mySidenav" class="sidenav">
              <a href="javascript:void(0)" id="close" class="closebtn">&times;</a>
              <a href="/">Home</a>
              <a href="book">Book</a>
              <a href="academy">Academy</a>
              <a href="shop">Shop</a>
              <a href="app3">Takimet</a>
              <a href="logout">Logout</a>
              </div>


  <br><br><br><br><br>

  <a>

    <div class="card" >

      <br>

      <form action="<?php echo $_SERVER['PHP_SELF']?>" method='POST'>

        <label id="tittleText">Register <?php echo $registerEmployee?"Employee":""?></label>

        <br><br>

        <label id="labelText">Name</label><br>
          
        <input name='name' class="inputRegister" id="name" type="text">

        <br><br>

        <label id="labelText">Email</label><br>

        <input name='email' class="inputRegister" id="email" type="email">

        <br><br>

        <label id="labelText">Phone Number</label><br>

        <input name='phone' class="inputRegister" id="phone" type="tel" placeholder="069 12 34 567">

        <br><br>

        <label id="labelText">Password</label><br>

        <input name='password' class="inputRegister" id="password" type="password">



        <br><br>

        <input name = 'submit' type='submit' class="fromButton" value ='Register'>

        <br><br>

      </form>

    </div>

  </a>

  <?php

  function print_err($sms){

  
    return '<br><p><div>'.implode(" ", $sms).'</p></div><br>';

  }

  $clean = array();

  $errorMessages = array();



  if(isset($_POST['submit'])){

    if(isset($_POST['name']) && !empty($_POST['name'])){

      $clean['name'] = $_POST['name'];

    } else {

      array_push($errorMessages,"Emri nuk duhet te jete bosh!");
      
    }



    if(isset($_POST['email']) && !empty($_POST['email'])){

      if(filter_var($_POST['email'],FILTER_VALIDATE_EMAIL)){

        require_once("phpFiles/databaseFunctions.php");



        $emails = getAllEmails();



        if(checkEmailUnique($_POST['email'])){

          $clean['email'] = $_POST['email'];

        }

        else{

          array_push($errorMessages,"Email eshte perdorur ne account tjeter!");  

        }

      }else {

        array_push($errorMessages,"Forma e email nuk eshte e rregullt!");  

      }

    } else {

      array_push($errorMessages,"Email nuk duhet te jete bosh!");
      

    }



    if(isset($_POST['phone']) && !empty($_POST['phone'])){

      $regex = "/^\d+$/";



      if(preg_match($regex,$_POST['phone']) && strlen($_POST['phone'])==10){

        $clean['phone'] = $_POST['phone'];

      }else {

        array_push($errorMessages,"Numri i telefonit duhet te permbaje vetem numra dhe te kete 10 numra! {$_POST['phone']}");  

      }

    } else {

      array_push($errorMessages,"Numri i telefonit nuk duhet te jete bosh!");

    }



    if(isset($_POST['password']) && !empty($_POST['password'])){

      $encode = password_hash($_POST['password'],PASSWORD_BCRYPT);



      $clean['password'] = $encode;

    }else{
      array_push($errorMessages,"Password nuk mund te jete bosh!");
  
    }



    if(count($errorMessages)==0){

      require_once("phpFiles/databaseFunctions.php");

      if($registerEmployee){

        insertEmployee($clean);

      }else{

        insertUser($clean);

      }

      

      
        echo '<script type="text/javascript">'; 
        echo 'setTimeout(function () { swal("Success!","Perdoruesi u rregjistrua me sukses","success");';
        echo '}, 0);</script>';

        echo "<script>setTimeout(function () {window.location.replace('login');},2000) </script>";

    }else{

      //echo print_err($errorMessages);
      echo "<script src=\"https://unpkg.com/sweetalert/dist/sweetalert.min.js\"></script>";
      $err=implode('\r\n', $errorMessages);
      echo '<script type="text/javascript">';
      echo 'setTimeout(function () { swal("Kujdes!","'.$err.'","warning");';
      echo '}, 500);</script>';


    }

  }

?>



</body>



</html>