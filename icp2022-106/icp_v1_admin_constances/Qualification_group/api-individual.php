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
    $qualification_group_name   = "'". $request_data->qualification_group_name ."'";
 
    $query = "INSERT INTO qualification_group (qualification_group_name) 
              VALUES  ($qualification_group_name)
             ;";
    $statement = $connect->prepare($query);
    $statement->execute($data);
    $output = array(" message " => " Insert Complete ");
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
} 
else if ($request_data->action == "getall") {
    $query = "SELECT qug.qualification_group_id, qug.qualification_group_name  
              FROM qualification_group AS qug
              ;";
    $statement = $connect->prepare($query);
    $statement->execute();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
    }
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
}
else if ($request_data->action == "edit") {
    $qualification_group_id = (int)$request_data->qualification_group_id;
    $query = "SELECT qug.qualification_group_id, qug.qualification_group_name 
                FROM qualification_group  AS  qug
                WHERE qug.qualification_group_id = $qualification_group_id
    ;";
    $statement = $connect->prepare($query);
    $statement->execute();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $data['qualification_group_id']     = $row['qualification_group_id'];
        $data['qualification_group_name']   = $row['qualification_group_name'];
    }
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
} 
else if ($request_data->action == "update") {
    $qualification_group_id     = (int)$request_data->qualification_group_id;
    $qualification_group_name   = "'". $request_data->qualification_group_name ."'";

    $query = "UPDATE qualification_group 
        SET 
        qualification_group_name  = $qualification_group_name
        WHERE qualification_group_id  = $qualification_group_id
        ;";
    $statement = $connect->prepare($query);
    $statement->execute($data);
    $output = array("message" => "Update Complete");
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    
} 
else if ($request_data->action == "delete") {
    $qualification_group_id = (int)$request_data->qualification_group_id;
    $query = "DELETE FROM qualification_group 
              WHERE qualification_group_id = $qualification_group_id
             ;";
    $statement = $connect->prepare($query);
    $statement->execute();
    $output = array("message" => "Delete Complete");
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
}
?>