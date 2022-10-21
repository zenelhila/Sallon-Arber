<?php 
// who can read this file, * means all
// the value tells browsers to allow requesting code from any origin to access the resource
header("Access-Control-Allow-Origin: *");
// it will return json
header("Content-Type: application/json");

require_once __DIR__."/connect.php";

include 'connect.php';
include 'includes/functions/functions.php'; 

$stmt =  $con->prepare("SELECT  a.appointment_id, a.date, a.start_time, a.end_time, GROUP_CONCAT(s.service_name) as service_name
FROM services s, users u, `appointment` a join services_for_appointment sa 
on sa.appointment_id=a.appointment_id WHERE  s.service_id=sa.service_id  
and u.id=a.user_id_barber  and a.barber_check_user=0 and u.id=?  GROUP BY(sa.appointment_id)
order by a.date, a.start_time; ");

$stmt->execute([$_POST['id']]);
$rows = $stmt->fetchAll();
$num = $stmt->rowCount();

if ($num > 0) {
    $return_data = array();
    $return_data['data'] = array();
    //$return_data['count'] = $num;
    foreach($rows as $row) {
        $start = $row['date']. " " .appointmentTimeConverter($row['start_time']);
        $end = $row['date']. " " .appointmentTimeConverter($row['end_time']);
        $appointments = array(
            "id" => $row['appointment_id'],
            "start" => $start,
            "end" => $end,
            "text" => $row['service_name']
        );
        array_push($return_data['data'], $appointments);
    }
    http_response_code(200);
    //$appointments =  json_encode($return_data);
    echo json_encode($return_data);
} else {
    http_response_code(404);
    echo  json_encode(array("msg" => "There are no appointments to select!"));
}

?>