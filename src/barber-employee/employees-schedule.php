<?php 
	session_start();

	//Check If user is already logged in
// 	if(isset($_SESSION['username_barbershop_Xw211qAAsq4']) && isset($_SESSION['password_barbershop_Xw211qAAsq4']))

if(isset($_SESSION['user'])){

        if($_SESSION['user']['level']!=2){

          header("Location: ../login.php");

        }else {
            //Page Title
            $pageTitle = 'Employees Schedule';

            //Includes
            include 'connect.php';
            include 'includes/functions/functions.php'; 
            include 'includes/templates/header.php';
            echo "<script src='https://unpkg.com/sweetalert/dist/sweetalert.min.js'></script>";

        }

      }else{
          header("Location: ../login.php");
      }
?>


<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Employees Schedule</h1>
        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-download fa-sm text-white-50"></i>
            Generate Report
        </a>
    </div>


    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Employees Schedule</h6>
        </div>
        <div class="card-body">
            <div class="sb-entity-selector" style="max-width:300px;">
                <form action="employees-schedule.php" method="POST">
                    <div class="form-group">
                        <label class="control-label" for="emloyee_schedule_select">
                            Select employee to see the schedule:
                        </label>
                        <div style="display:inline-block;margin-bottom: 10px;">
                            <?php 
                                        $stmt = $con->prepare('select * from users where level=2');
                                        $stmt->execute();
                                        $employees = $stmt->fetchAll();
                                    
                                        echo "<select class='form-control' name='employee_selected'>";
                                            foreach ($employees as $employee) 
                                            {
                                                echo "<option value=".$employee['id']." ".((isset($_POST['employee_selected']) && $_POST['employee_selected'] == $employee['id'])?'selected':'').">".$employee['name']."</option>";
                                            }
                                        echo "</select>";                                    
                                    ?>
                        </div>
                        <button type="submit" name="show_schedule_sbmt" class="btn btn-primary">Show schedule</button>
                    </div>
                </form>
            </div>

            <div class="alert alert-info">
                This is the current week shift arranged by manager!
            </div>


            <!-- SECHEDULE PART -->

            <div class="sb-content" style="min-height: 500px;">
                <?php

                            /** WHEN SHOW SCHEDULE BUTTON CLICKED **/

                            if(isset($_POST['show_schedule_sbmt']))
                            {
                        ?>
                <form method="POST" action="employees-schedule.php">
                    <input type="hidden" name="employee_id" value="<?php echo $_POST['employee_selected'];?>" hidden>
                    <div class="worktime-days">
                        <?php
                                            $employee_id = $_POST['employee_selected'];
                                            $stmt = $con->prepare('select * from users u, schedule s 
                                            where u.id = s.barber_id 
                                            and u.level = 2
                                            and u.id = ?');
                                            $stmt->execute(array($employee_id));
                                            $employees = $stmt->fetchAll();
            
                                            $days = array("1"=>"Monday",
                                                "2"=>"Tuesday",
                                                "3"=>"Wednsday",
                                                "4"=>"Thursday",
                                                "5"=>"Friday",
                                                "6"=>"Saturday",
                                                "7"=>"Sunday") ;
                                        
                                            //Available days
                                            $av_days = array();
                                            foreach($employees as $employee)
                                            {
                                                $av_days[] = $employee['day_id'];
                                            }
                                        
                                            foreach($days as $key => $value)
                                            {
                                                echo "<div class='worktime-day row'>";
                                                
                                                if(in_array($key,$av_days))
                                                {
                                                    echo "<div class='form-group  col-md-4'>";
                                                        echo "<input name='".$value."' id='".$key."' class='sb-worktime-day-switch ' type='checkbox' onclick='return false'  >";
                                                        echo "<span class='day-name'>";                
                                                            echo $value;
                                                        echo "</span>";
                                                    echo "</div>";
                                                    
                                                    foreach($employees as $employee)
                                                    {
                                                        if(in_array($key,$av_days) && $employee['day_id'] == $key)
                                                        {
                                                            echo "<div class='time_ col-md-8 row'>";
                                                            echo "<div class='form-group col-md-6'>";
                                                            echo "<input type='time' name='".$value."-from' value='".$employee['start_time']."' class='form-control' readonly>";
                                                            echo "</div>";
                                                            echo "<div class='form-group col-md-6'>";
                                                            echo "<input type='time' name='".$value."-to' value='".$employee['end_time']."'  class='form-control' readonly>";
                                                            echo "</div>";
                                                            echo "</div>";
                                                        
                                                        }
                                                    
                                                    }
                                                }
                                                else
                                                {
                                                    echo "<div class='form-group  col-md-4'>";
                                                    echo "<input name='".$value."' id='".$key."' class='sb-worktime-day-switch' type='checkbox' onclick='return false'>";
                                                    echo "<span class='day-name'>";                
                                                    echo $value;
                                                    echo "</span>";
                                                    echo "</div>";
                                                    
                                             
                                                    
                                                }
                                                
                                                echo "</div>";
                                            }
                                        ?>
                    </div>


                </form>
                <?php
                            }
                        ?>
            </div>




        </div>
    </div>
</div>

<?php 
        
        //Include Footer
        include 'includes/templates/footer.php';
?>