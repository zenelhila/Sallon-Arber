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
        <h1 class="h3 mb-0 text-gray-800">Calendar</h1>
    </div>

    <html>

    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>daypilot-project</title>

        <style type="text/css">
        p,
        body,
        td,
        input,
        select,
        button {
            font-family: -apple-system, system-ui, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            font-size: 14px;
        }

        body {
            padding: 0px;
            margin: 0px;
            background-color: #ffffff;
        }

        a {
            color: #1155a3;
        }

        .space {
            margin: 10px 0px 10px 0px;
        }

        .header {
            background: #003267;
            background: linear-gradient(to right, #011329 0%, #00639e 44%, #011329 100%);
            padding: 20px 10px;
            color: white;
            box-shadow: 0px 0px 10px 5px rgba(0, 0, 0, 0.75);
        }

        .header a {
            color: white;
        }

        .header h1 a {
            text-decoration: none;
        }

        .header h1 {
            padding: 0px;
            margin: 0px;
        }

        .main {
            padding: 10px;
            margin-top: 10px;
        }

        .generated {
            color: #999;
            margin-top: 10px;
        }

        .generated a {
            color: #999;
        }
        </style>

        <!-- DayPilot library -->
        <script src="Design/js/daypilot-all.min.js"></script>

    </head>

    <body>


        <div class="main">
            <div id="dp"></div>
        </div>

        <script>
        var dp = new DayPilot.Calendar("dp", {
            viewType: "Days",
            days: 14,
            startDate: DayPilot.Date.today().firstDayOfWeek(),
            cellHeight: 30,
            allowEventOverlap: false,
            timeRangeSelectedHandling: "Disabled",
            eventDeleteHandling: "Disabled",
            eventMoveHandling: "Disabled",
            eventResizeHandling: "Disabled",
            eventClickHandling: "Disabled",
            eventHoverHandling: "Disabled",
        });

        // const getAppointments = () => {
        //     const url = 'http://localhost/Release%2010.0/public_html/barber-admin/get_appointments.php';
        //     fetch(url)
        //         .then(response => response.json())
        //         .then(console.log)
        //         .catch(error => console.log("Error!"))
        // }

        const getAppointments = () => {
            const url = 'http://stud-proj.epoka.edu.al/~eponari19/barber-employee/get_appointments.php';
            const request = new XMLHttpRequest();
            request.open('POST', url);
            request.addEventListener('load', function() {
                const jsonResponse = JSON.parse(this.responseText);
                const data = jsonResponse.data;
                allAppointments = data;
                allAppointments.forEach(appointment => {
                    //appendLeague(appointment);
                    console.log(appointment);
                    dp.events.add(new DayPilot.Event(appointment));
                });
            });
            request.send("id="+<?php echo $_SESSION['user']['id'] ?>);
            
        }

        getAppointments();

        dp.init();
        </script>
    </body>


    </html>

    <?php
        
		//Include Footer
		include 'includes/templates/footer.php';


?>