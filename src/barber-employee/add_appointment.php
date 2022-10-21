<?php

    session_start();

    

    $registerEmployee = false;

    

    if(isset($_SESSION['user'])){

        if($_SESSION['user']['level']==3){

          $registerEmployee=true;

        }

      }else{

          header("Location: ../login.php");

      }





      $selectedPreferences = isset($_POST['next']);

      if(isset($_POST['next'])){

        if(isset($_POST['services']) AND isset($_POST['bookingDate'])){

            if(empty($_POST['services']) or empty($_POST['bookingDate'])){

                echo "Empty fields";

                $selectedPreferences = false;

            }else{

                $clean = array();



                $clean['services'] = $_POST['services'];

                $clean['chair'] = $_SESSION['user']['id'];

                $clean['bookingDate'] = htmlentities($_POST['bookingDate']);

                $_SESSION['clean'] = $clean;

            }

            //$timeStamps = getAvailableTimestamps($clean);

        } else{
            echo "Bad request sent!";
        }

      }

      if(isset($_POST['submit'])){
          if(isset($_POST['time'])){
              require_once('phpFiles/databaseFunctions.php');
              //$date,$startTime,$duration,$userId,$barberId
              $appointmentId = insertAppointment($_SESSION['clean']['bookingDate'],$_POST['time'],getTotalTime($_SESSION['clean']['services'])['total'],$_SESSION['user']['id'],$_SESSION['clean']['chair']);
            

              foreach($_SESSION['clean']['services'] as $service){
                  insertServiceForAppointment($appointmentId,$service);
              }
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



    <link rel="stylesheet" type="text/css" href="styles/homepage.css?<?php echo time(); ?>">

    <link rel="stylesheet" type="text/css" href="styles/booking.css?<?php echo time(); ?>">

    <script type="text/javascript" src="bookJs.js" defer> </script>

    <link rel = "icon" href = "pictures/logo1.png" type = "image/x-icon">

</head>


<body>

    <a class="logoPosition" href="index.php"> <img class="logoImg" src="pictures/logo1.png"> </a>

    <div id="hamburgerNav">

        <img id="navImg" src="pictures/hamburger.png" style="height: 30px; width: 40px;">

    </div>

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

                    if($selectedPreferences){

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

                <input name='bookingDate' id="date" type="date" <?php if($selectedPreferences) {echo $selectedPreferences?" readonly value='{$clean['bookingDate']}'":"";}?>>

                <br><br>

            </div>

            <br>
            
            <?php $clean['chair'] = $_SESSION['user']['id']; ?>
        </div>



        <button  name='next' id="NextBtn" type='submit'>Next</button>

        <br><br><br>

        </form>

    </div>

    



    <br><br><br><br> <br><br><br><br>
    
<?php if($selectedPreferences){  ?>
    
    <div class="card"  id="place">
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
                    $hour_padded = sprintf("%02d", floor($time/60));
                    $minute_padded = sprintf("%02d", $time%60);
                    $stringTime = $hour_padded.':'.$minute_padded;
                    echo "<option value='$time'> $stringTime </option>";
                }
            ?>
        </select>

        <br>

        <button  name='submit' id="NextBtn" type='submit'>Next</button>

        <br>
        </form>
    </div>
            


    <br><br><br><br> <br><br><br><br>



    </div>

    <br>

<?php } ?>

</body>



</html>