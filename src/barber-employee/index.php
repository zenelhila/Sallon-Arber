<?php 
	session_start();

if(isset($_SESSION['user'])){

        if($_SESSION['user']['level']!=2){

          header("Location: ../login.php");

        }else {
            //Page Title
        $pageTitle = 'Dashboard';

            //Includes
            include 'connect.php';
            include 'includes/functions/functions.php'; 
            include 'includes/templates/header.php';
        }

      }else{
          header("Location: ../login.php");
      }
      
?>


<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
        <form action="generate_pdf.php" method="POST">
        
        <button  type="submit" name="submit" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-download fa-sm text-white-50"></i> Generate report </button>  
        </form>
    </div>

    <!-- Content Row -->
    <div class="row">

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Clients
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo countUsers("id","users")?></div>
                        </div>
                        <div class="col-auto">
                            <i class="bs bs-boy fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Services
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo countItems("service_id","services")?></div>
                        </div>
                        <div class="col-auto">
                            <i class="bs bs-scissors-1 fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Employees
                            </div>
                            <div class="row no-gutters align-items-center">
                                <div class="col-auto">
                                    <div class="h5 mb-0 mr-3 font-weight-bold text-gray-800">
                                        <?php echo countEmployee("id","users")?></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="bs bs-man fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Appointments
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo countItems("appointment_id","appointment")?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Appointment Tables -->
    <div class="card shadow mb-4">
        <div class="card-header tab" style="padding: 0px !important;background: #36b9cc!important">
            <button class="tablinks active" onclick="openTab(event, 'Upcoming')">
                Upcoming Bookings
            </button>
            <button class="tablinks" onclick="openTab(event, 'Past')">
                Past Bookings
            </button>

        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered tabcontent" id="Upcoming" style="display:table" width="100%"
                    cellspacing="0">
                    <thead>
                        <tr>
                            <th>
                                Date
                            </th>
                            <th>
                                Start Time
                            </th>
                            <th>
                                Booked Services
                            </th>
                            <th>
                                End Time Expected
                            </th>
                            <th>
                                Client
                            </th>
                            <th>
                                Employee
                            </th>

                        </tr>
                    </thead>
                    <tbody>

                        <?php
                                    $stmt = $con->prepare("SELECT * 
                                    FROM users u
                                    INNER JOIN appointment a
                                    on (a.user_id = u.id OR a.user_id_barber = u.id)
                                    where a.user_id_barber = ?
                                    AND curdate()<=date
                                    order by a.date, a.start_time;");
                                    $stmt->execute([$_SESSION['user']['id']]);
                                    $rows = $stmt->fetchAll();
                                    $count = $stmt->rowCount();

                                    if($count == 0)
                                    {

                                        echo "<tr>";
                                            echo "<td colspan='5' style='text-align:center;'>";
                                                echo "List of your upcoming bookings will be presented here";
                                            echo "</td>";
                                        echo "</tr>";
                                    }
                                    else
                                    {

                                        foreach($rows as $row)
                                        {
                                            if(date("Y-m-d") <= $row['date']){
                                            echo "<tr>";
                                            echo "<td>";
                                                echo $row['date'];
                                            echo "</td>";
                                            
                                            echo "<td>";
                                                echo timeConvert($row['start_time']);
                                            echo "</td>";
                                            
                                            echo "<td>";
                                            $booked_services = array();

                                           // echo $row['service_name'];
                                            $stmtServices = $con->prepare("SELECT service_name, date 
                                                    FROM services s, appointment a, services_for_appointment sa
                                                    where s.service_id = sa.service_id
                                                    and a.appointment_id = sa.appointment_id
                                                    and a.appointment_id = ?
                                                    ;
                                              
                                                                    ");
                                                    $stmtServices->execute([$row['appointment_id']]);
                                                    $rowsServices = $stmtServices->fetchAll();
                                                    $countServices = $stmtServices->rowCount();
                                                    foreach($rowsServices as $rowServices){
                                                        if($row['date'] == $rowServices['date'] )
                                                            array_push($booked_services, $rowServices['service_name']);
                                                    }
                                                    echo implode(', ', $booked_services);
                                                   $booked_services = array();

                                                echo "</td>";
                                                    echo "<td>";
                                                    echo timeConvert($row['end_time']);
                                                
                                                    echo "</td>";
                                                    echo "<td>";
                                
                                                            echo $row['name'];
                                                    
                                                    echo "</td>";
                                                    echo "<td>";
                                                    $stmtBarber = $con->prepare("SELECT * 
                                                    FROM users u
                                                    where u.id =  {$row['user_id_barber']};
                                                                    ");
                                                    $stmtBarber->execute();
                                                    $rowsBarber = $stmtBarber->fetchAll();
                                                    $countBarber = $stmtBarber->rowCount();
                                                    foreach($rowsBarber as $rowBarber)
                                                        echo $rowBarber['name'];

                                                    echo "</td>";
                                                    
                                                    
                                        }
                                   } }

                                ?>


                        <?php
                                            echo "</tr>";
                                        
                                    

                                ?>

                    </tbody>
                </table>
               
                <table class="table table-bordered tabcontent" id="Past" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>
                                Date
                            </th>
                            <th>
                                Start Time
                            </th>
                            <th>
                                Booked Services
                            </th>
                            <th>
                                End Time
                            </th>
                            <th>
                                Client
                            </th>
                            <th>
                                Employee
                            </th>
                            <th>
                                Check
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                   
                        <?php
                                    $stmt = $con->prepare("SELECT * 
                                    FROM users u
                                    INNER JOIN appointment a
                                    on (a.user_id = u.id OR a.user_id_barber = u.id)
                                    where a.user_id_barber = ?
                                    AND curdate()>date
                                    order by a.date, a.start_time;
                                                    ");
                                    $stmt->execute([$_SESSION['user']['id']]);
                                    $rows = $stmt->fetchAll();
                                    $count = $stmt->rowCount();

                                    if($count == 0)
                                    {

                                        echo "<tr>";
                                            echo "<td colspan='5' style='text-align:center;'>";
                                                echo "List of your past bookings will be presented here";
                                            echo "</td>";
                                        echo "</tr>";
                                    }
                                    else
                                    {

                                        foreach($rows as $row)
                                        {
                                            if(date("Y-m-d") > $row['date']){
                                            echo "<tr>";
                                            echo "<td>";
                                                echo $row['date'];
                                            echo "</td>";
                                            
                                            echo "<td>";
                                                echo timeConvert($row['start_time']);
                                            echo "</td>";
                                            
                                            echo "<td>";
                                            $booked_services = array();

                                           // echo $row['service_name'];
                                            $stmtServices = $con->prepare("SELECT service_name, date 
                                                    FROM services s, appointment a, services_for_appointment sa
                                                    where s.service_id = sa.service_id
                                                    and a.appointment_id = sa.appointment_id
                                                    and a.appointment_id  = ?
                                                    ;");
                                                    $stmtServices->execute([$row['appointment_id']]);
                                                    $rowsServices = $stmtServices->fetchAll();
                                                    $countServices = $stmtServices->rowCount();
                                                    foreach($rowsServices as $rowServices){
                                                        if($row['date'] == $rowServices['date'] )
                                                            array_push($booked_services, $rowServices['service_name']);
                                                    }
                                                    echo implode(', ', $booked_services);
                                                   $booked_services = array();

                                                echo "</td>";
                                                    echo "<td>";
                                                    echo timeConvert($row['end_time']);
                                                
                                                    echo "</td>";
                                                    echo "<td>";
                                
                                                            echo $row['name'];
                                                    
                                                    echo "</td>";
                                                    echo "<td>";
                                                    $stmtBarber = $con->prepare("SELECT * 
                                                    FROM users u
                                                    where u.id =  {$row['user_id_barber']};
                                                                    ");
                                                    $stmtBarber->execute();
                                                    $rowsBarber = $stmtBarber->fetchAll();
                                                    $countBarber = $stmtBarber->rowCount();
                                                    foreach($rowsBarber as $rowBarber)
                                                        echo $rowBarber['name'];

                                                    echo "</td>";
                                                
                                                echo "<td>";
                                                            if($row['barber_check_user'] == 0 || is_null($row['barber_check_user'])){ ?>
                            
                                                                <ul class="list-inline m-0">
                                                                    <li class="list-inline-item" data-toggle="tooltip" title="check">
                                                                        <form action = "<?php echo $_SERVER['PHP_SELF']?>" method = 'post'>
                                                                        <div class = "smallBox">
                                                                            <select name="check<?php echo $row['appointment_id']; ?>" id="check">
                                                                                <option value="-1">Nuk erdhi</option>
                                                                                <option value="0" selected>Ne pritje</option>
                                                                                <option value="1">Erdhi</option>
                                                                            </select>&nbsp;&nbsp;
                                                                            <button  name='ok<?php echo $row['appointment_id']; ?>' class="okBtn" type='submit'>OK</button>
                                                                            
                                                                            </div>
                                                                            <!-- <script>
                                                                                if (window.history.replaceState) {
                                                                                    window.history.replaceState( null, null, window.location.href );
                                                                                }
                                                                            </script> -->
                                                                        </form>
                                                                  
                                                            
                                                               <?php
            
                                                                if(isset($_POST['ok'.$row['appointment_id']])){
                                                                    if(!empty($_POST['check'.$row['appointment_id']])){
                                                                        $sel = $_POST['check'.$row['appointment_id']];

                                                                        require_once('phpFiles/databaseFunctions.php');

                                                                        $res = update_record($row['appointment_id'],$sel);
                                                                        
                                                                        echo "<script> location.reload(); </script>";
                                                                    } else {
                                                                        echo "Please select the value";
                                                                    }
                                                            } 
                                                        
                                                            }
                                                            else if($row['barber_check_user']!=0){
                                                                $choice = $row['barber_check_user']==1?"Erdhi":"Nuk erdhi";
                                                                echo "<div class=\"smallBox\"> {$choice}</div></tr>";
                                                              }
                                                            
                                    
                                                    ?>
                        <?php
                                                echo "</td>";
                                            echo "</tr>";
                                        }
                                   } }

                                ?>
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>

<?php
        
		//Include Footer
		include 'includes/templates/footer.php';


?>