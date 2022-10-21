<?php 
session_start();
if(isset($_SESSION['user'])){

        if($_SESSION['user']['level']!=3){

          header("Location: ../login");

        }else {
            //Page Title
        $pageTitle = 'Clients';

            //Includes
            include 'connect.php';
            include 'includes/functions/functions.php'; 
            include 'includes/templates/header.php';
        }

      }else{
          header("Location: ../login");
      }
?>
<!-- Begin Page Content -->
<div class="container-fluid">

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Clients</h1>
        <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
            <i class="fas fa-download fa-sm text-white-50"></i>
            Generate Report
        </a>
    </div>


    <!-- Clients Table -->
    <?php
                $stmt = $con->prepare("SELECT u.name, u.phone, u.email, sum(a.barber_check_user) as rating, 
                count(a.barber_check_user) as countRatings  FROM users u, appointment a where u.level=1 
                and u.id = a.user_id GROUP BY u.id;");
                $stmt->execute();
                $rows_clients = $stmt->fetchAll(); 
            ?>
    <div class="card shadow mb-4">
        <div class="card-header tab" style="padding: 0px !important;background: #36b9cc!important">
            <button class="tablinks active" onclick="openTab(event, 'Clients')">
                Clients
            </button>
            <button class="tablinks" onclick="openTab(event, 'Past')">
                All Users
            </button>
        </div>
        <div class="card-body">

            <!-- Clients Table -->
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered tabcontent" id="Clients" style="display:table" width="100%"
                        cellspacing="0">
                        <thead>
                            <tr>
                                <th scope="col">Name</th>
                                <th scope="col">Phone Number</th>
                                <th scope="col">E-mail</th>
                                <th scope="col">Rating</th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                    foreach($rows_clients as $client)
                                    {
                                        echo "<tr>";
                                            echo "<td>";
                                                echo $client['name'];
                                            echo "</td>";
                                            echo "<td>";
                                                echo $client['phone'];
                                            echo "</td>";
                                            echo "<td>";
                                                echo $client['email'];
                                            echo "</td>";
                                            echo "<td>";
                                            echo $client['rating'];
                                            echo " / ";
                                            echo $client['countRatings'];


                                            //echo $client['email'];
                                        echo "</td>";
                                        echo "</tr>";
                                    }
                                ?>
                        </tbody>
                    </table>
                    <table class="table table-bordered tabcontent" id="Past" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th scope="col">Name</th>
                                <th scope="col">Phone Number</th>
                                <th scope="col">E-mail</th>


                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $stmtUsers = $con->prepare("SELECT * FROM users u where level=1 group by u.id");
                                $stmtUsers->execute();
                                $rows_users = $stmtUsers->fetchAll(); 

                                    foreach($rows_users as $user)
                                    {
                                        echo "<tr>";
                                            echo "<td>";
                                                echo $user['name'];
                                            echo "</td>";
                                            echo "<td>";
                                                echo $user['phone'];
                                            echo "</td>";
                                            echo "<td>";
                                                echo $user['email'];
                                            echo "</td>";
                                           
                                        echo "</tr>";
                                    }
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