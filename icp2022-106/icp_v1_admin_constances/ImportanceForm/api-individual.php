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
    $importance_name   = "'". $request_data->importance_name ."'";
    $importance_value  = "'". $request_data->importance_value ."'";

    $query = "INSERT INTO importance (importance_name,importance_value) 
              VALUES  ($importance_name,$importance_value)
             ;";
    $statement = $connect->prepare($query);
    $statement->execute($data);
    $output = array(" message " => " Insert Complete ");
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    
} 
else if ($request_data->action == "getall") {
    $query = "SELECT per.importance_id,per.importance_name,per.importance_value 
              FROM importance AS per
              ;";
    $statement = $connect->prepare($query);
    $statement->execute();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
    }
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
}
else if ($request_data->action == "edit") {
    $importance_id = (int)$request_data->importance_id;
    $query = "SELECT per.importance_id,per.importance_name,per.importance_value 
                FROM importance  AS  per
                WHERE per.importance_id = $importance_id
    ;";
    $statement = $connect->prepare($query);
    $statement->execute();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $data['importance_id']     = $row['importance_id'];
        $data['importance_name']   = $row['importance_name'];
        $data['importance_value']  = $row['importance_value'];
    }
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
} 
else if ($request_data->action == "update") {
    $importance_id     = (int)$request_data->importance_id;
    $importance_name   = "'". $request_data->importance_name ."'";
    $importance_value  = "'". $request_data->importance_value ."'";
    $query = "UPDATE importance 
        SET 
            importance_name  = $importance_name,
            importance_value = $importance_value
        WHERE importance_id  = $importance_id
        ;";
    $statement = $connect->prepare($query);
    $statement->execute($data);
    $output = array("message" => "Update Complete");
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    
} 
else if ($request_data->action == "delete") {
    $importance_id = (int)$request_data->importance_id;
    $query = "DELETE FROM importance 
              WHERE importance_id = $importance_id
             ;";
    $statement = $connect->prepare($query);
    $statement->execute();
    $output = array("message" => "Delete Complete");
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
}
?>