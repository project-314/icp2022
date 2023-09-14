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
    // ข้อมูลส่วนตัว
    $institute_name = "'". $request_data->institute_name ."'";

    $query = "INSERT INTO institute (institute_name) 
              VALUES  ($institute_name)
              ;";
    $statement = $connect->prepare($query);
    $statement->execute($data);
    $output = array(" message " => " Insert Institute Complete ");
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    
} 
else if ($request_data->action == "getall") {
    $query = "SELECT ins.institute_id,ins.institute_name
              FROM institute as ins
              ORDER BY ins.institute_id
              ;";
    $statement = $connect->prepare($query);
    $statement->execute();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
    }
    echo json_encode($data, JSON_UNESCAPED_UNICODE);   
}
else if ($request_data->action == "getMember") {
    $query = "SELECT mem.member_id,mem.full_name,mem.status
              FROM member as mem
              ORDER BY mem.member_id
              ;";
    $statement = $connect->prepare($query);
    $statement->execute();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
    }
    echo json_encode($data, JSON_UNESCAPED_UNICODE);   
}
else if ($request_data->action == "edit") {
    $institute_id = (int)$request_data->institute_id; 
    $query = "SELECT ins.institute_id,ins.institute_name 
                FROM institute  as  ins
                WHERE institute_id = $institute_id
             ;";
    $statement = $connect->prepare($query);
    $statement->execute();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        // ข้อมูลการศึกษา
        $data['institute_id']       = $row['institute_id'];
        $data['institute_name']     = $row['institute_name'];
    }
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    
} 
else if ($request_data->action == "update") {
    $institute_id      = (int)$request_data->institute_id;
    $institute_name    = "'". $request_data->institute_name ."'";
    $query = "UPDATE institute 
                SET 
                    institute_name = $institute_name
                WHERE institute_id = $institute_id
        ;";
    $statement = $connect->prepare($query);
    $statement->execute($data);
    $output = array("message" => "Update Complete");
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    
} 
else if ($request_data->action == "delete") {
    $institute_id = (int)$request_data->institute_id;
    $query = "DELETE FROM institute WHERE institute_id = $institute_id
    ;";
    $statement = $connect->prepare($query);
    $statement->execute();
    $output = array("message" => "Delete Institute Complete");
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    
}
?>