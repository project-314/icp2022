<?php
function cors()
{
    // Allow from any origin
    if (isset($_SERVER['HTTP_ORIGIN'])) {
        // Decide if the origin in $_SERVER['HTTP_ORIGIN'] is one
        // you want to allow, and if so:
        header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 86400');    // cache for 1 day
    }

    // Access-Control headers are received during OPTIONS requests
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
            // may also be using PUT, PATCH, HEAD etc
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS");

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
            header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

        exit(0);
    }
    // echo "You have CORS!";
}

cors();

$request_data = json_decode(file_get_contents("php://input"));
$data = array();
$servername = "localhost";
$username = "u486700931_root";
$password = "B^&AVvb7g";
$database = "u486700931_icp";

try {
        $connect = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
        $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // echo "Connected successfully"; 
    } catch(PDOException $e) {    
        echo "Connection failed: " . $e->getMessage();
    }
                    
if ($request_data->action == "insert") {
    echo " insert ";
    $notification_id      = (int)$request_data->notification_id;
    $member_id            = (int)$request_data->member_id;
    $is_notification      = (int)$request_data->is_notification;
    $notification_date    = "'". $request_data->notification_date ."'";
    $notification_type    = (int)$request_data->notification_type;
    $message              = "'". $request_data->message ."'";
    $query = "INSERT INTO notification (notification_id, member_id, is_notification,
                                      notification_date,notification_type,message
                                    ) 
                             VALUES  ($notification_id, $member_id, $is_notification,
                                      $notification_date,$notification_type,$message
                                    )";
    $statement = $connect->prepare($query);
    $statement->execute($data);
    $output = array(" message " => " Insert Complete ");
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    
} 
else if ($request_data->action == "getall") {
    $member_id = (int)$request_data->member_id;
    $query = "SELECT noti.notification_id,
                     noti.member_id,
                     noti.is_notification,
                     noti.notification_date,
                     noti.message,
                     fre.frequency_id,
                     fre.frequency_name,
                     mem.full_name
              FROM notification as noti
              INNER JOIN member     as mem  ON noti.member_id           = mem.member_id
              INNER JOIN frequency  as fre  ON noti.notification_type   = fre.frequency_id
            --   WHERE noti.member_id = $member_id
              ORDER BY noti.notification_id
              ;";
    $statement = $connect->prepare($query);
    $statement->execute();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
    }
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    
}
else if ($request_data->action == "getallNotify") {
    $member_id = (int)$request_data->member_id;
    $query = "SELECT noti.notification_id,
                     noti.member_id,
                     noti.is_notification,
                     noti.notification_date,
                     noti.message,
                     fre.frequency_id,
                     fre.frequency_name
              FROM notification as noti
              INNER JOIN member     as mem  ON noti.member_id           = mem.member_id
              INNER JOIN frequency  as fre  ON noti.notification_type   = fre.frequency_id
              WHERE noti.member_id = $member_id
              ORDER BY noti.notification_id DESC
              LIMIT 1
              ;";
    $statement = $connect->prepare($query);
    $statement->execute();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $data['notification_id']    = $row['notification_id'];
        $data['member_id']          = $row['member_id'];
        $data['is_notification']    = $row['is_notification'];
        $data['notification_date']  = $row['notification_date'];
        $data['message']            = $row['message'];
        $data['frequency_id']       = $row['frequency_id'];
        $data['frequency_name']     = $row['frequency_name'];
    }
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    
}
else if ($request_data->action == "edit") {
    $notification_id = (int)$request_data->notification_id;
    $query = "SELECT noti.notification_id,
                     noti.member_id, mem.full_name,mem.status,
                     noti.is_notification,
                     noti.notification_date,
                     noti.message,
                     fre.frequency_id,
                     fre.frequency_name

                FROM notification as noti
                INNER JOIN member     as mem  ON noti.member_id           = mem.member_id
                INNER JOIN frequency  as fre  ON noti.notification_type   = fre.frequency_id
                WHERE noti.notification_id = $notification_id 
                ORDER BY noti.notification_id
             ;";
    $statement = $connect->prepare($query);
    $statement->execute();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $data['notification_id']    = $row['notification_id'];
        $data['member_id']          = $row['member_id'];
        $data['full_name']          = $row['full_name'];
        $data['status']             = $row['status'];
        $data['is_notification']    = $row['is_notification'];
        $data['notification_date']  = $row['notification_date'];
        $data['message']            = $row['message'];
        $data['frequency_id']       = $row['frequency_id'];
        $data['frequency_name']     = $row['frequency_name'];
    }
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    
} 
else if ($request_data->action == "update") {
    $notification_id      = (int)$request_data->notification_id;
    $member_id            = (int)$request_data->member_id;
    $is_notification      = (int)$request_data->is_notification;
    $notification_type    = (int)$request_data->notification_type ;
    $notification_date    = "'". $request_data->notification_date ."'";
    $message              = "'". $request_data->message ."'";

    $query = "UPDATE notification 
        SET 
            notification_id     = $notification_id,
            member_id           = $member_id,
            is_notification     = $is_notification,
            notification_type   = $notification_type,
            notification_date   = $notification_date,
            message             = $message
        WHERE notification_id   = $notification_id
        ;";
    $statement = $connect->prepare($query);
    $statement->execute($data);
    $output = array("message" => "Update Complete");
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    
} 
else if ($request_data->action == "delete") {
    
    $notification_id = (int)$request_data->notification_id;
    $query = "DELETE FROM notification 
              WHERE notification_id = $notification_id
    ;";
    $statement = $connect->prepare($query);
    $statement->execute();
    $output = array("message" => "Delete Complete");
    echo json_encode($output, JSON_UNESCAPED_UNICODE);   
}
else if ($request_data->action == "getMember") {
    $query = "SELECT mem.member_id,mem.full_name,mem.status
              FROM member as mem
  
              JOIN plan_career    AS plc ON mem.member_id         = plc.member_id
              JOIN career         AS car ON plc.career_id         = car.career_id
  
              JOIN qa_plan_career AS qap ON plc.plan_career_id    = qap.plan_career_id
              JOIN qualification  AS qua ON qap.qualification_id  = qua.qualification_id
              GROUP BY mem.member_id
              ORDER BY mem.member_id ASC
              ;";
    $statement = $connect->prepare($query);
    $statement->execute();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
    }
    echo json_encode($data, JSON_UNESCAPED_UNICODE);   
  }
?>