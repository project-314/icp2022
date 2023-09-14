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
    $level_name   = "'". $request_data->level_name ."'";
    $level_description  = "'". $request_data->level_description ."'";
    $weigth  = "'". $request_data->weigth ."'";

    $query = "INSERT INTO level (level_name,level_description ,weigth ) 
              VALUES  ($level_name,$level_description ,$weigth)
             ;";
    $statement = $connect->prepare($query);
    $statement->execute($data);
    $output = array(" message " => " Insert Complete ");
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    
} 
else if ($request_data->action == "getall") {
    $query = "SELECT lev.level_id,lev.level_name,lev.level_description,lev.weigth 
              FROM level AS lev
              ;";
    $statement = $connect->prepare($query);
    $statement->execute();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
    }
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
}
else if ($request_data->action == "edit") {
    $level_id = (int)$request_data->level_id;
    $query = "SELECT lev.level_id,lev.level_name,lev.level_description,lev.weigth 
                FROM level AS lev
                WHERE lev.level_id = $level_id
    ;";
    $statement = $connect->prepare($query);
    $statement->execute();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $data['level_id']           = $row['level_id'];
        $data['level_name']         = $row['level_name'];
        $data['level_description']  = $row['level_description'];
        $data['weigth']             = $row['weigth'];
    }
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
} 
else if ($request_data->action == "update") {
    $level_id           = (int)$request_data->level_id;
    $level_name         = "'". $request_data->level_name ."'";
    $level_description  = "'". $request_data->level_description ."'";
    $weigth             = (int)$request_data->weigth;

    $query = "UPDATE  level 
        SET 
            level_name          = $level_name,
            level_description   = $level_description,
            weigth              = $weigth
        WHERE level_id          = $level_id
        ;";
    $statement = $connect->prepare($query);
    $statement->execute($data);
    $output = array("message" => "Update Complete");
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    
} 
else if ($request_data->action == "delete") {
    $level_id = (int)$request_data->level_id;
    $query = "DELETE FROM level 
              WHERE level_id = $level_id
             ;";
    $statement = $connect->prepare($query);
    $statement->execute();
    $output = array("message" => "Delete Complete");
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
}
?>