<?php
session_start();

if(isset($_SESSION['user'])){

  $USERID=$_SESSION['user']['id'];
}else {
  header("Location: login.php");
}
?>




<!DOCTYPE html>
<html>
<head>

<meta charset="utf-8">

        <meta name="viewport" content="width=device-width, initial-scale=1">
    
        <title>Sallon Arber</title>
        <link rel="stylesheet" type="text/css" href="styles/table.css?<?php echo time(); ?>">

        <link rel="stylesheet" type="text/css" href="styles/responsiveNav.css?<?php echo time(); ?>">

        <script type="text/javascript" src="bookJs.js" defer> </script>

        <link rel = "icon" href = "pictures/icon.png" type = "image/x-icon">

</head>
<body>

<div class="centerDiv">
      <div class="responsive">
        <a class="logoPosition" href="index.php"> <img class="logoImg" src="pictures/logo1.png"> </a>

        <span id="open" style="font-size:30px;cursor:pointer" onclick="openNav()">
                  <img id="navImg" src="pictures/hamburger.png" style="height: 30px; width: 40px;"> 
         </span>
        </div>
            <nav class="navMenu">

            <a href="index.php">Home</a>

<a href="book.php">Book</a>

<a href="about.php">About</a>

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


  <p class="tittleText"> Appointments to come </p>
</div>
 
<br><br>
<div style="overflow-x:auto;">

<form action = "<?php echo $_SERVER['PHP_SELF']?>" method = 'post'>
  <table>
    <tr>
      <th>Data</th>
      <th>Ora e Nisjes</th>
      <th>Ora e Mbarimit</th>
      <th>Sherbimi</th>
      <th>Punonjesi</th>
      <th>Fshije</th>
    </tr>
    <?php
          require_once('phpFiles/databaseFunctions.php');


          $appoints = getUpcommingAppointments($USERID);
            
          echo '<tr>';
          if($appoints) {      
            foreach($appoints as $app){
              
              $startTime = timeToString($app['start_time']);
              $endTime = timeToString($app['end_time']);
              
              echo "  <td> {$app['date']} </td> 
                      <td>{$startTime}</td> 
                      <td>{$endTime}</td>
                      <td>{$app['service_name']}</td><td>{$app['name']}</td>
                      <td>
                      <button name='delete' class=\"deleteBtn\" type='submit'>   
                      <a href=\"phpFiles/deleteAppointment.php?appointment_id={$app['appointment_id']}\"> Delete</a>
                      </button>
                      </td>
                    </tr>";
                   
            }
        }else {
        echo "<td colspan=6>No upcomming appointments!</td></tr>";
      }
      ?>
  </table>
</form>
</div>


<br><br><br>
<div class="centerDiv">
<p>Past Bookings<p>

<br><br>
</div>
<div style="overflow-x:auto;">


  <table>
    <tr>
      <th>Data</th>
      <th>Ora e Nisjes</th>
      <th>Ora e Mbarimit</th>
      <th>Sherbimi</th>
      <th>Punonjesi</th>
      <th>Review</th>
    </tr>

    <?php
          require_once('phpFiles/databaseFunctions.php');


          $appoints = getOldAppointments($USERID);
            
          //echo '<tr>';

          if($appoints) {      

          foreach($appoints as $app){
              
              $startTime = timeToString($app['start_time']);
              $endTime = timeToString($app['end_time']);
            
              echo "<tr><td> {$app['date']} </td> 
              <td>{$startTime}</td> 
              <td>{$endTime}</td><td>{$app['service_name']}</td>
              <td>{$app['name']}</td>";

              if($app['user_rating']==0 || is_null($app['user_rating'])){

              ?>

              <form action = "<?php echo $_SERVER['PHP_SELF']?>" method = 'post'>
              <td> <div class="smallBox">  
            <select name="review<?php echo $app['appointment_id']; ?>" id="review">
                      <option value='1'>1</option>
                      <option value='2'>2</option>
                      <option value='3'>3</option>
                      <option value='4'>4</option>
                      <option value='5'>5</option>
                    </select>&nbsp;&nbsp;
              <button  name='ok<?php echo $app['appointment_id']; ?>' class="okBtn" type='submit'>OK</button>
              
             </div></td></tr>


             <script>
                  if ( window.history.replaceState ) {
                      window.history.replaceState( null, null, window.location.href );
                  }
              </script>
            </form>

          <?php

              if(isset($_POST['ok'.$app['appointment_id']])){

                if(!empty($_POST['review'.$app['appointment_id']])) {

                    $sel= $_POST['review'.$app['appointment_id']];
                    update_record($app['appointment_id'],$sel);
                    echo "<script> location.reload();</script>";
                    //header("Location: appointments");
                } else {
                    echo 'Please select the value.';
                  }
                }  
              }
              else if($app['user_rating']>0){
                echo "<td> <div class=\"smallBox\"> {$app['user_rating']}</div></td></tr>";
              }
          }
        }else {
        echo "<td colspan=6>No upcomming appointments!</td></tr>";
      }
      ?>

  </table>
</div>

<br><br><br>

  <br>
  <span class="copyright">Copyright &copy; Barbershop Website by "5 Veta" Epoka Team"</span>
  <br>

</body>
</html>
