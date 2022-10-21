<?php

    session_start();

    $clean = array();

    $registerEmployee = false;

    

    if(isset($_SESSION['user'])){

        if(isset($_SESSION['user'])){
    
            if($_SESSION['user']['level']==3){
                header("Location: barber-admin/index.php");
            }
            else if($_SESSION['user']['level']==2){
                header("Location: barber-employee/index.php");
            }
            }

        if($_SESSION['user']['level']==3){

          $registerEmployee=true;

        }

      }else{
          header("Location: login.php");
      }

      



      $selectedPreferences = isset($_POST['next']);

      if(isset($_POST['next'])){

        if(isset($_POST['services']) AND isset($_POST['chair']) AND isset($_POST['bookingDate'])){

            if(empty($_POST['services']) OR empty($_POST['chair']) or empty($_POST['bookingDate'])){

                echo "Empty fields";

                $selectedPreferences = false;

            }
            else if(!isset($_POST['services']) OR !isset($_POST['chair']) or !isset($_POST['bookingDate'])){
                echo "Empty fields";

                $selectedPreferences = false;
            }
            else{
                $clean['services'] = $_POST['services'];

                $clean['chair'] = htmlentities($_POST['chair']);

                $clean['bookingDate'] = htmlentities($_POST['bookingDate']);

                $_SESSION['clean'] = $clean;

            }

            //$timeStamps = getAvailableTimestamps($clean);

        } else{

            echo '<script type="text/javascript">';
            echo 'setTimeout(function () { swal("Kujdes!","Ploteso te gjitha pikat e formularit!","warning");';
            echo '}, 500);</script>';
           
        }

      }

      if(isset($_POST['submit'])){
          if(isset($_POST['time'])){
              require_once('phpFiles/databaseFunctions.php');
              
              $appointmentid = insertAppointment($_SESSION['clean']['bookingDate'],$_POST['time'],getTotalTime($_SESSION['clean']['services'])['total'],$_SESSION['user']['id'],$_SESSION['clean']['chair']);
              
              foreach($_SESSION['clean']['services'] as $serviceId){
                insertServiceForAppointment($appointmentid,$serviceId);
              }

                echo '<script type="text/javascript">'; 
                echo 'setTimeout(function () { swal("Success!","Takimi juaj u ruaj me sukses","success");';
                echo '}, 1000);</script>';


               echo "<script>window.location.replace('appointments');</script>";
               header("Location: appointments.php");
              
          }else{
              echo "Time is missing";
          }
      }
?>


<!DOCTYPE html>

<html>



<head>

    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Sallon Arber</title>

      <script>window.alert("<?php $_SESSION['user']['level']?>")</script>

    <link rel="stylesheet" type="text/css" href="styles/homepage.css?<?php echo time(); ?>">

    <link rel="stylesheet" type="text/css" href="styles/booking.css?<?php echo time(); ?>">
    
    <link rel="stylesheet" type="text/css" href="styles/responsiveNav.css?<?php echo time(); ?>">

    <script type="text/javascript" src="bookJs.js" defer> </script>

    <link rel = "icon" href = "pictures/icon.png" type = "image/x-icon">

    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

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



    <br><br><br><br>



    <div class="card" id="place">

        <br><br>

        <label id="tittleText">Welcome <?php echo $_SESSION['user']['name']?></label>

        <br><br>

    </div>

    <br><br><br><br>

    <!--Customer will firstly choose what they need to do-->

    

    <div class="card" id="place">

    <form action='<?php echo $_SERVER['PHP_SELF'] ?>' method='post'>  

        <br>

        <label id="tittleText">Service</label>

        <br>

        <br>

        <div class="box">

            <?php

                require_once('phpFiles/databaseFunctions.php');

                $services = getAllServices();



                foreach($services as $service){

                    if(isset($clean['services']) && isset($service['service_id'])){
                        $inPreference = in_array($service['service_id'],$clean['services'])?"checked":"";

                        echo "<div><input name='services[]' type='checkbox' class='checkbox-round' value='{$service['service_id']}' onclick='return false;' $inPreference>"

                        ."<label id='labelText'>{$service['service_name']}</label><br><br></div>";

                    }else{

                        echo "<div><input name='services[]' type='checkbox' class='checkbox-round' value='{$service['service_id']}'>"

                        ."<label id='labelText'>{$service['service_name']}</label><br><br></div>";

                    }

                }

            ?>



            <div>

                <input name='bookingDate' id="date" type="date" min = <?php echo date("Y-m-d");?> <?php if(isset($clean['bookingDate'])) {echo $selectedPreferences?" readonly value='{$clean['bookingDate']}'":"";}?>>

                <br><br>

            </div>

            <br>

            <label id="tittleText">Zgjidh berberin</label>

            <br>

            <?php

                require_once('phpFiles/databaseFunctions.php');

                $employees = getAllEmployees();

                foreach($employees as $employee){

                    if(isset($clean['chair'])){

                        $inPreference = $employee['id']==$clean['chair']?"checked":"";

                        $selected = $selectedPreferences?'readonly="readonly"':'';

                        

                        echo "<div><input value='{$employee['id']}' type='radio' name='chair' class='checkbox-round' $inPreference $selected>"

                        ."<label id='labelText'>{$employee['name']}</label><br><br></div>";

                    }else {

                        echo "<div><input value='{$employee['id']}' type='radio' name='chair' class='checkbox-round'>"

                        ."<label id='labelText'>{$employee['name']}</label><br><br></div>";

                    }

                    

                }

            ?>

        </div>



        <button  name='next' id="NextBtn" type='submit'>Next</button>

        <br><br><br>

        </form>

        <?php if(isset($clean['chair'])){  ?>
    

    <form action = "<?php echo $_SERVER['PHP_SELF']?>" method = 'post'>
        <br>

        <label id="tittleText">Time</label>

        <br>

        <label for="timetable" id="labelText"> Choose a time</label>

        <select name="time" id="time">
            
            <?php
                require_once('phpFiles/databaseFunctions.php');

                echo getTotalTime($clean['services'])['total'];

                echo $clean['bookingDate'];

                echo $clean['chair'];
                $times = getAllFreeTimestamps(getTotalTime($clean['services'])['total'],$clean['bookingDate'],$clean['chair']);
            
                foreach($times as $time){
                    $stringTime = timeToString($time);
                    echo "<option value='$time'> $stringTime </option>";
                }
            ?>
        </select>

        <br><br>

        <button  name='submit' id="NextBtn" type='submit'>Submit</button>

        <br><br>
        </form>
        <?php } ?>

    </div>

    </div>

    <br>
    
  <br>
  <span class="copyright">Copyright &copy; Barbershop Website by "5 Veta" Epoka Team"</span>
  <br>
    

</body>



</html>