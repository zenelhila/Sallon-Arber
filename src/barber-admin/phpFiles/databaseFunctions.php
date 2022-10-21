<?php
    require_once("connection.php");

    function getAllEmails(){
        global $conn;

        $query = "SELECT email FROM users";

        $stmt = $conn->prepare($query);

        $stmt->execute();

        $emails = $stmt->fetchAll();

        return $emails;
    }

    function getAllServices(){
        global $conn;

        $query = "SELECT * FROM services";

        $stmt = $conn->prepare($query);

        $stmt->execute();

        $services = $stmt->fetchAll();

        return $services;
    }

    function getAllEmployees(){
        global $conn;

        $query = "SELECT * FROM users where level=2";

        $stmt = $conn->prepare($query);

        $stmt->execute();

        $employees = $stmt->fetchAll();

        return $employees;
    }
    
    function getAllProducts(){
        global $conn;

        $query = "SELECT * FROM products";

        $stmt = $conn->prepare($query);

        $stmt->execute();

        $products = $stmt->fetchAll();

        return $products;
    }

    function getAllUsers($email){
        global $conn;

        $query = "SELECT * FROM users where email= :email";

        $stmt = $conn->prepare($query);

        $stmt->bindParam(":email",$email);

        $stmt->execute();

        $users = $stmt->fetch();

        return $users;
    }

    function getTotalTime($services){
        global $conn;

        $in  = str_repeat('?,', count($services) - 1) . '?';
        $sql = "SELECT sum(service_duration) total FROM services where service_id in ($in)";
        $stm = $conn->prepare($sql);
        $stm->execute($services);
        $total = $stm->fetch();

        return $total;
    }

    function getDescription($services){
        global $conn;
        $in  = str_repeat('?,', count($services) - 1) . '?';
        $sql = "SELECT service_name FROM services where service_id in ($in)";
        $stm = $conn->prepare($sql);
        $stm->execute($services);
        $names = $stm->fetchAll(PDO::FETCH_ASSOC);
        $results = array();

        print_r($names);

        foreach($names as $name){
            array_push($results,$name['service_name']);
        }

        $out = implode(',',$results);

        return $out;
    }

    function getAllFreeTimestamps($duration,$date,$barberId){
        global $conn;
        
        $end = 9*60 + $duration;

        $available = array();

        for($start = 9*60;$end<=21*60;$start+=15){
            $end = $start+$duration;
            if($end>21*60){
                break;
            }

            $query = "select count(*) c from appointment where date=:date and user_id_barber=:barberid AND ((:start BETWEEN start_time+1 and end_time-1) or (:end BETWEEN start_time+1 and end_time-1) OR (start_time = :start) or (end_time = :end) or (start_time BETWEEN :start +1 and :end -1) or (end_time BETWEEN :start +1 and :end -1))";
            $stmt = $conn->prepare($query);

            $stmt->bindParam(":start",$start);
            $stmt->bindParam(":end",$end);
            $stmt->bindParam(":barberid",$barberId);
            $stmt->bindParam(":date",$date);

            $stmt->execute();

            $result = $stmt->fetch();
            
            if($result['c']==0){
                array_push($available,$start);
            }
        }

        return $available;
    }

    function checkEmailUnique($userEmail){
        global $conn;
        $emails = getAllEmails();

        foreach($emails as $email){
            if($userEmail == $email['email']){
                return false;
            }
        }

        return true;
    }

    function insertUser($clean){
        global $conn;

        $query = "INSERT INTO users (name,email,phone,password,level) VALUES (?,?,?,?,?)";    
        $conn->prepare($query)->execute([$clean['name'],$clean['email'],$clean['phone'],$clean['password'],1]);
    }

    function insertEmployee($clean){
        global $conn;

        $query = "INSERT INTO users(name,email,phone,password,level) VALUES (?,?,?,?,?)";
        $conn->prepare($query)->execute([$clean['name'],$clean['email'],$clean['phone'],$clean['password'],2]);
    }

    function insertService($clean){
        global $conn;

        $query = "INSERT INTO services(service_name,service_price,service_duration) VALUES (?,?,?)";
        $conn->prepare($query)->execute([$clean['service_name'],$clean['service_price'],$clean['service_duration']]);
    }

    function insertProduct($clean){
        global $conn;

        $query = "INSERT INTO products(product_name,product_price,product_image_path) VALUES (?,?,?)";
        $conn->prepare($query)->execute([$clean['product_name'],$clean['product_price'],$clean['fileToUpload']]);
    }
    
    function insertAppointment($date,$startTime,$duration,$userId,$barberId){
        global $conn;

        $query = "INSERT INTO appointment(date,start_time,end_time,user_id,user_id_barber) VALUES (?,?,?,?,?)";
        $conn->prepare($query)->execute([$date,intval($startTime),intval($startTime)+$duration,$userId,$barberId]);

        return $conn->lastInsertId();
    }

    function insertServiceForAppointment($appointment,$service){
        global $conn;

        $query = "INSERT INTO services_for_appointment VALUES (?,?)";
        $conn->prepare($query)->execute([$appointment,$service]);
    }

    function loginUser($email,$password){
        $users = getAllUsers($email);
        $logged = false;

        if(password_verify($password,$users['password'])){
            $_SESSION['user'] = $users;   
            $logged = true;
            
            echo "<script>window.location.href='../book.php'</script>";
            
        } else{
            echo "Email ose password sjane te sakta!";
        }
    }
    
    function timeToString($time){
        $hour_padded = sprintf("%02d", floor($time/60));
        $minute_padded = sprintf("%02d", $time%60);
        $stringTime = $hour_padded.':'.$minute_padded;
        
        return $stringTime;
    }
?>