<?php
session_start();

require_once 'FPDF/fpdf.php';
require_once 'phpFiles/connection.php';
require_once 'phpFiles/databaseFunctions.php';

$data = $conn->prepare("SELECT date,start_time,end_time,user_id,user_id_barber,barber_check_user FROM appointment where curdate() > date");
$data-> bindParam(":user", $_SESSION['user']['id']);
$data->execute();
$rows = $data->fetchAll();
$count = $data->rowCount();

  if(isset($_POST['submit'])){

    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial','B',16);
    $pdf-> cell(33,10,'Date',1,0,'C');
    $pdf-> cell(20,10,'Start',1,0,'C');
    $pdf-> cell(20,10,'End',1,0,'C');
    $pdf-> cell(40,10,'Client',1,0,'C');
    $pdf-> cell(40,10,'Barber',1,0,'C');
    $pdf-> cell(40,10,'Check',1,1,'C');


    foreach($rows as $row){
        $query = "SELECT name FROM users where id=?";
        $data = $conn->prepare($query);
        $data->execute([$row['user_id']]);
        $info = $data->fetch();
        $name = $info['name'];
        
        $query = "SELECT name FROM users where id=?";
        $data = $conn->prepare($query);
        $data->execute([$row['user_id_barber']]);
        $info = $data->fetch();
        $barber = $info['name'];

        $pdf-> cell(33,10,$row['date'],1,0,'C');
        $pdf-> cell(20,10,timeToString($row['start_time']),1,0,'C');
        $pdf-> cell(20,10,timeToString($row['end_time']),1,0,'C');
        $pdf-> cell(40,10,$name,1,0,'C');
        $pdf-> cell(40,10,$barber,1,0,'C');
        if($row['barber_check_user']==1){
          $pdf-> cell(40,10,"Ka ardhur",1,1,'C');
        }
        else if($row['barber_check_user']==0){
          $pdf-> cell(40,10,"Ne pritje",1,1,'C');
        }
        else{
          $pdf-> cell(40,10,"Nuk ka ardhur",1,1,'C');
        }
        
    }
    $pdf->output();
    
  }
?>