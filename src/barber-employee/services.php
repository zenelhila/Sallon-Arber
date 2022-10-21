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
        <h1 class="h3 mb-0 text-gray-800">Services</h1>
        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-download fa-sm text-white-50"></i>
            Generate Report
        </a>
    </div>

    <?php
                $do = '';

                if(isset($_GET['do']) && in_array($_GET['do'], array('Add','Edit')))
                {
                    $do = htmlspecialchars($_GET['do']);
                }
                else
                {
                    $do = 'Manage';
                }

                if($do == 'Manage')
                {
                    $stmt = $con->prepare("SELECT * FROM services s");
                    $stmt->execute();
                    $rows_services = $stmt->fetchAll();
                ?>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Services</h6>
        </div>
        <div class="card-body">



            <!-- SERVICES TABLE -->

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th scope="col">Service Name</th>
                        <th scope="col">Description</th>
                        <th scope="col">Price</th>
                        <th scope="col">Duration</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                                        foreach($rows_services as $service)
                                        {
                                            echo "<tr>";
                                                echo "<td>";
                                                    echo $service['service_name'];
                                                echo "</td>";
                                    
                                                echo "<td style = 'width:30%'>";
                                                    echo $service['service_description'];
                                                echo "</td>";
                                                echo "<td>";
                                                    echo $service['service_price'];
                                                echo "</td>";
                                                echo "<td>";
                                                    echo $service['service_duration'];
                                                echo "</td>";
                                               
                                            echo "</tr>";
                                        }
                                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php
                }
                elseif($do == 'Add')
                {
                    ?>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Add New Service</h6>
        </div>
        <div class="card-body">
            <form method="POST" action="services.php?do=Add">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="service_name">Service Name</label>
                            <input type="text" class="form-control"
                                value="<?php echo (isset($_POST['service_name']))?htmlspecialchars($_POST['service_name']):'' ?>"
                                placeholder="Service Name" name="service_name">
                            <?php
                                                $flag_add_service_form = 0;
                                                if(isset($_POST['add_new_service']))
                                                {
                                                    if(empty(test_input($_POST['service_name'])))
                                                    {
                                                        ?>
                            <div class="invalid-feedback" style="display: block;">
                                Service name is required.
                            </div>
                            <?php

                                                        $flag_add_service_form = 1;
                                                    }
                                                }
                                            ?>
                        </div>
                    </div>

                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="service_duration">Service Duration(min)</label>
                            <input type="text" class="form-control"
                                value="<?php echo (isset($_POST['service_duration']))?htmlspecialchars($_POST['service_duration']):'' ?>"
                                placeholder="Service Duration" name="service_duration">
                            <?php

                                                if(isset($_POST['add_new_service']))
                                                {
                                                    if(empty(test_input($_POST['service_duration'])))
                                                    {
                                                        ?>
                            <div class="invalid-feedback" style="display: block;">
                                Service duration is required.
                            </div>
                            <?php

                                                        $flag_add_service_form = 1;
                                                    }
                                                    elseif(!ctype_digit(test_input($_POST['service_duration'])))
                                                    {
                                                        ?>
                            <div class="invalid-feedback" style="display: block;">
                                Invalid duration.
                            </div>
                            <?php

                                                        $flag_add_service_form = 1;
                                                    }
                                                }
                                            ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="service_price">Service Price($)</label>
                            <input type="text" class="form-control"
                                value="<?php echo (isset($_POST['service_price']))?htmlspecialchars($_POST['service_price']):'' ?>"
                                placeholder="Service Price" name="service_price">
                            <?php

                                                if(isset($_POST['add_new_service']))
                                                {
                                                    if(empty(test_input($_POST['service_price'])))
                                                    {
                                                        ?>
                            <div class="invalid-feedback" style="display: block;">
                                Service price is required.
                            </div>
                            <?php

                                                        $flag_add_service_form = 1;
                                                    }
                                                    elseif(!is_numeric(test_input($_POST['service_price'])))
                                                    {
                                                        ?>
                            <div class="invalid-feedback" style="display: block;">
                                Invalid price.
                            </div>
                            <?php

                                                        $flag_add_service_form = 1;
                                                    }
                                                }
                                            ?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="service_description">Service Description</label>
                            <textarea class="form-control" name="service_description"
                                style="resize: none;"><?php echo (isset($_POST['service_description']))?htmlspecialchars($_POST['service_description']):''; ?></textarea>
                            <?php

                                                if(isset($_POST['add_new_service']))
                                                {
                                                    if(empty(test_input($_POST['service_description'])))
                                                    {
                                                        ?>
                            <div class="invalid-feedback" style="display: block;">
                                Service description is required.
                            </div>
                            <?php

                                                        $flag_add_service_form = 1;
                                                    }
                                                    elseif(strlen(test_input($_POST['service_description'])) > 250)
                                                    {
                                                        ?>
                            <div class="invalid-feedback" style="display: block;">
                                The length of the description should be less than 250 letters.
                            </div>
                            <?php

                                                        $flag_add_service_form = 1;
                                                    }
                                                }
                                            ?>
                        </div>
                    </div>
                </div>

                <!-- SUBMIT BUTTON -->

                <button type="submit" name="add_new_service" class="btn btn-primary">Add service</button>

            </form>

            <?php

                                /*** ADD NEW SERVICE ***/
                                if(isset($_POST['add_new_service']) && $_SERVER['REQUEST_METHOD'] == 'POST' && $flag_add_service_form == 0)
                                {
                                    $service_name = test_input($_POST['service_name']);
                                    $service_duration = test_input($_POST['service_duration']);
                                    $service_price = test_input($_POST['service_price']);
                                    $service_description = test_input($_POST['service_description']);

                                    try
                                    {
                                        $stmt = $con->prepare("insert into services(service_name,service_description,service_price,service_duration) values(?,?,?,?) ");
                                        $stmt->execute(array($service_name,$service_description,$service_price,$service_duration));
                                        
                                        ?>
            <!-- SUCCESS MESSAGE -->

            <script type="text/javascript">
            swal("New Service", "The new service has been created successfully", "success").then((value) => {
                window.location.replace("services.php");
            });
            </script>

            <?php

                                    }
                                    catch(Exception $e)
                                    {
                                        echo "<div class = 'alert alert-danger' style='margin:10px 0px;'>";
                                            echo 'Error occurred: ' .$e->getMessage();
                                        echo "</div>";
                                    }
                                    
                                }
                            ?>
        </div>
    </div>


    <?php   
                }
                elseif($do == "Edit")
                {
                    $service_id = (isset($_GET['service_id']) && is_numeric($_GET['service_id']))?intval($_GET['service_id']):0;

                    if($service_id)
                    {
                        $stmt = $con->prepare("Select * from services where service_id = ?");
                        $stmt->execute(array($service_id));
                        $service = $stmt->fetch();
                        $count = $stmt->rowCount();

                        if($count > 0)
                        {
                            ?>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Edit Service</h6>
        </div>
        <div class="card-body">
            <form method="POST" action="services.php?do=Edit&service_id=<?php echo $service_id; ?>">
                <!-- SERVICE ID -->
                <input type="hidden" name="service_id" value="<?php echo $service['service_id'];?>">

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="service_name">Service Name</label>
                            <input type="text" class="form-control" value="<?php echo $service['service_name'] ?>"
                                placeholder="Service Name" name="service_name">
                            <?php
                                                        $flag_edit_service_form = 0;

                                                        if(isset($_POST['edit_service_sbmt']))
                                                        {
                                                            if(empty(test_input($_POST['service_name'])))
                                                            {
                                                                ?>
                            <div class="invalid-feedback" style="display: block;">
                                Service name is required.
                            </div>
                            <?php

                                                                $flag_edit_service_form = 1;
                                                            }
                                                            
                                                        }
                                                    ?>
                        </div>
                    </div>

                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="service_duration">Service Duration(min)</label>
                            <input type="text" class="form-control" value="<?php echo $service['service_duration'] ?>"
                                placeholder="Service Duration" name="service_duration">
                            <?php

                                                        if(isset($_POST['edit_service_sbmt']))
                                                        {
                                                            if(empty(test_input($_POST['service_duration'])))
                                                            {
                                                                ?>
                            <div class="invalid-feedback" style="display: block;">
                                Service duration is required.
                            </div>
                            <?php

                                                                $flag_edit_service_form = 1;
                                                            }
                                                            elseif(!ctype_digit(test_input($_POST['service_duration'])))
                                                            {
                                                                ?>
                            <div class="invalid-feedback" style="display: block;">
                                Invalid duration.
                            </div>
                            <?php

                                                                $flag_edit_service_form = 1;
                                                            }
                                                        }
                                                    ?>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="service_price">Service Price($)</label>
                            <input type="text" class="form-control" value="<?php echo $service['service_price'] ?>"
                                placeholder="Service Price" name="service_price">
                            <?php

                                                        if(isset($_POST['edit_service_sbmt']))
                                                        {
                                                            if(empty(test_input($_POST['service_price'])))
                                                            {
                                                                ?>
                            <div class="invalid-feedback" style="display: block;">
                                Service price is required.
                            </div>
                            <?php

                                                                $flag_edit_service_form = 1;
                                                            }
                                                            elseif(!is_numeric(test_input($_POST['service_price'])))
                                                            {
                                                                ?>
                            <div class="invalid-feedback" style="display: block;">
                                Invalid price.
                            </div>
                            <?php

                                                                $flag_edit_service_form = 1;
                                                            }
                                                        }
                                                    ?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="service_description">Service Description</label>
                            <textarea class="form-control" name="service_description"
                                style="resize: none;"><?php echo $service['service_description']; ?></textarea>
                            <?php

                                                        if(isset($_POST['edit_service_sbmt']))
                                                        {
                                                            if(empty(test_input($_POST['service_description'])))
                                                            {
                                                                ?>
                            <div class="invalid-feedback" style="display: block;">
                                Service description is required.
                            </div>
                            <?php

                                                                $flag_edit_service_form = 1;
                                                            }
                                                            elseif(strlen(test_input($_POST['service_description'])) > 250)
                                                            {
                                                                ?>
                            <div class="invalid-feedback" style="display: block;">
                                The length of the description should be less than 250 letters.
                            </div>
                            <?php

                                                                $flag_edit_service_form = 1;
                                                            }
                                                        }
                                                    ?>
                        </div>
                    </div>
                </div>

                <!-- SUBMIT BUTTON -->
                <button type="submit" name="edit_service_sbmt" class="btn btn-primary">Save Edits</button>
            </form>

            <?php
                                        /*** EDIT SERVICE ***/
                                        if(isset($_POST['edit_service_sbmt']) && $_SERVER['REQUEST_METHOD'] == 'POST' && $flag_edit_service_form == 0)
                                        {
                                            $service_id = $_POST['service_id'];
                                            $service_name = test_input($_POST['service_name']);
                                            $service_duration = test_input($_POST['service_duration']);
                                            $service_price = test_input($_POST['service_price']);
                                            $service_description = test_input($_POST['service_description']);

                                            try
                                            {
                                                $stmt = $con->prepare("update services set service_name = ?, service_description = ?, service_price = ?, service_duration = ? where service_id = ? ");
                                                $stmt->execute(array($service_name,$service_description,$service_price,$service_duration,$service_id));
                                                
                                                ?>
            <!-- SUCCESS MESSAGE -->

            <script type="text/javascript">
            swal("Service Updated", "The service has been updated successfully", "success").then((value) => {
                window.location.replace("services.php");
            });
            </script>

            <?php

                                            }
                                            catch(Exception $e)
                                            {
                                                echo "<div class = 'alert alert-danger' style='margin:10px 0px;'>";
                                                    echo 'Error occurred: ' .$e->getMessage();
                                                echo "</div>";
                                            }
                                            
                                        }
                                    ?>
        </div>
    </div>
    <?php
                        }
                        else
                        {
                            header('Location: services.php');
                            exit();
                        }
                    }
                    else
                    {
                        header('Location: services.php');
                        exit();
                    }
                }
            ?>
</div>

<?php 
        
        //Include Footer
        include 'includes/templates/footer.php';


?>