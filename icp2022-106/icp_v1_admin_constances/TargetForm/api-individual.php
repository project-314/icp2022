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
    $target_name           = "'". $request_data->target_name ."'";
    $target_value          = "'". $request_data->target_value ."'";

    $query = "INSERT INTO target(target_name,target_value) 
              VALUES  ($target_name,$target_value)";
    $statement = $connect->prepare($query);
    $statement->execute($data);
    $output = array(" message " => " Insert Complete ");
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    
} 
else if ($request_data->action == "edit") {
    $target_id = (int)$request_data->target_id;
    
    $query = "SELECT  tar.target_id,tar.target_name,tar.target_value,tar.target_description 
              FROM  target AS tar
              WHERE tar.target_id = $target_id
    ;";
    $statement = $connect->prepare($query);
    $statement->execute();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $data['target_id']          = $row['target_id'];
        $data['target_name']        = $row['target_name'];
        $data['target_value']       = $row['target_value'];
        $data['target_description'] = $row['target_description'];
    }
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    
}
else if ($request_data->action == "getall") {
    $query = "SELECT tar.target_id,tar.target_name,tar.target_value,tar.target_description  
              FROM target AS tar
              ;";
    $statement = $connect->prepare($query);
    $statement->execute();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
    }
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    
} 
else if ($request_data->action == "update") {
    $target_id             = (int)$request_data->target_id;
    // ข้อมูลส่วนตัว
    $target_name           = "'". $request_data->target_name ."'";
    $target_value          = "'". $request_data->target_value ."'";

    $query = "UPDATE target 
        SET 
            target_name=$target_name,
            target_value=$target_value 
            
        WHERE target_id=$target_id
        ;";
    $statement = $connect->prepare($query);
    $statement->execute($data);
    $output = array("message" => "Update Complete");
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    
} 
else if ($request_data->action == "delete") {
    $target_id = (int)$request_data->target_id;
    $query = "DELETE FROM target WHERE target_id = $target_id
    ;";
    $statement = $connect->prepare($query);
    $statement->execute();
    $output = array("message" => "Delete Complete");
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    
}
?>