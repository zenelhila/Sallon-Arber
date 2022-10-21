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
    
    function getTimeInMinutes($time){
        $start = 0;
        
        $hours = substr($time,0,2);
        $minutes = substr($time,3,2);
        
        $start = 60*intval($hours) + intval($minutes);
        
        return $start;
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

    function getStartFromSchedule($id,$date){
        global $conn;
        
        $day = date('w', strtotime($date));
        
        $query = "select start_time from schedule where barber_id=? and day_id=?";
        
        $stmt = $conn->prepare($query);

        
        $stmt->execute([$id,$day]);
            
        $result = $stmt->fetch();
        
        return isset($result['start_time'])?$result['start_time']:"09:00:00";
    }
    
    function getEndFromSchedule($id,$date){
        global $conn;
        
        $day = date('w', strtotime($date));
        
        if($day==0){$day=7;}
        
        $query = "select end_time from schedule where barber_id=? and day_id=?";
        
        $stmt = $conn->prepare($query);

        
        $stmt->execute([$id,$day]);
            
        $result = $stmt->fetch();
        
        return isset($result['end_time'])?$result['end_time']:"21:00:00";
    }

    function getAllFreeTimestamps($duration,$date,$barberId){
        global $conn;
        
        $startTime = getTimeInMinutes(getStartFromSchedule($barberId,$date));
        $endTime = getTimeInMinutes(getEndFromSchedule($barberId,$date));

        $end = $startTime + $duration;

        $available = array();
        
        for($start = $startTime;$end<=$endTime;$start+=15){
            $end = $start+$duration;
            if($end>$endTime){
                break;
            }
            
            if(($start<getTimeInMinutes(date("h:i"))+60) && $date == date("Y-m-d")){
                continue;
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
    function insertServiceForAppointment($appointmentId,$serviceId){
        global $conn;
        
        $query = "INSERT INTO services_for_appointment VALUES (?,?)";
        
        $conn->prepare($query)->execute([$appointmentId,$serviceId]);
        
        
    }

    function loginUser($email,$password){
        $users = getAllUsers($email);
        $logged = false;

        if(password_verify($password,$users['password'])){
            $_SESSION['user'] = $users;   
            $logged = true;
            
            echo "<script>window.location.href='login.php'</script>";
            
        } else{
            echo "<script src=\"https://unpkg.com/sweetalert/dist/sweetalert.min.js\"></script>";
      echo '<script type="text/javascript">';
      echo 'setTimeout(function () { swal("Kujdes!","Email ose password sjane te sakta!","warning");';
      echo '}, 500);</script>';
        }
    }
    
    
    
    
    
    
    function getUpcommingAppointments($userId){
        global $conn;

        $query = "SELECT  a.date, a.start_time, a.end_time, GROUP_CONCAT(s.service_name) as service_name, u.name , a.appointment_id  FROM services s, users u, `appointment` a join services_for_appointment sa on sa.appointment_id=a.appointment_id WHERE user_id=".$userId." and s.service_id=sa.service_id  and u.id=a.user_id_barber  and a.barber_check_user=0  GROUP BY(sa.appointment_id)";

        $stmt = $conn->prepare($query);

        $stmt->execute();

        $products = $stmt->fetchAll();

        return $products;
    }

    function getOldAppointments($userId){
        global $conn;

        $query = "SELECT  a.date, a.start_time, a.end_time, GROUP_CONCAT(s.service_name) as service_name, u.name, a.appointment_id, a.user_rating FROM services s, users u, `appointment` a join services_for_appointment sa on sa.appointment_id=a.appointment_id WHERE user_id=".$userId." and s.service_id=sa.service_id  and u.id=a.user_id_barber  and a.barber_check_user=1  GROUP BY(sa.appointment_id)";

        $stmt = $conn->prepare($query);

        $stmt->execute();

        $products = $stmt->fetchAll();

        return $products;
    }

  

    function deleteApp($id) {
        try {
            $sql = "DELETE FROM `appointment` WHERE appointment_id=:id";
            global $conn;
            $stmt = $conn -> prepare($sql);
            $stmt -> bindParam(":id", $id);
            $stmt -> execute();
            return true;
        } catch (PDOException $ex) {
            return false;
        }
    }

    function update_record($id,$review) {
        try {
            global $conn;
            
            $sql = "UPDATE appointment SET user_rating=? WHERE appointment_id=?";
            $stmt= $conn->prepare($sql);
            $stmt->execute([$review, $id]);
           // return true;
        } catch (PDOException $ex) {
            echo $ex -> getMessage() . '<br>';
           // return false;
        }
    }
    
    function timeToString($time){
        $hour_padded = sprintf("%02d", floor($time/60));
        $minute_padded = sprintf("%02d", $time%60);
        $stringTime = $hour_padded.':'.$minute_padded;
        
        return $stringTime;
    }
    
    
    
    
    
?>