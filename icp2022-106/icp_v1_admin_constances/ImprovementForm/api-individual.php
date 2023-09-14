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
    $perform_name   = "'". $request_data->perform_name ."'";
    $perform_value  = "'". $request_data->perform_value ."'";

    $query = "INSERT INTO perform (perform_name,perform_value) 
              VALUES  ($perform_name,$perform_value)
             ;";
    $statement = $connect->prepare($query);
    $statement->execute($data);
    $output = array(" message " => " Insert Complete ");
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    
} 
else if ($request_data->action == "getall") {
    $query = "SELECT per.perform_id,per.perform_name,per.perform_value 
              FROM perform AS per
              ;";
    $statement = $connect->prepare($query);
    $statement->execute();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
    }
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
}
else if ($request_data->action == "edit") {
    $perform_id = (int)$request_data->perform_id;
    $query = "SELECT per.perform_id,per.perform_name,per.perform_value 
                FROM perform  AS  per
                WHERE per.perform_id = $perform_id
    ;";
    $statement = $connect->prepare($query);
    $statement->execute();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $data['perform_id']     = $row['perform_id'];
        $data['perform_name']   = $row['perform_name'];
        $data['perform_value']  = $row['perform_value'];
    }
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
} 
else if ($request_data->action == "update") {
    $perform_id     = (int)$request_data->perform_id;
    $perform_name   = "'". $request_data->perform_name ."'";
    $perform_value  = "'". $request_data->perform_value ."'";
    $query = "UPDATE perform 
        SET 
            perform_name  = $perform_name,
            perform_value = $perform_value
        WHERE perform_id  = $perform_id
        ;";
    $statement = $connect->prepare($query);
    $statement->execute($data);
    $output = array("message" => "Update Complete");
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    
} 
else if ($request_data->action == "delete") {
    $perform_id = (int)$request_data->perform_id;
    $query = "DELETE FROM perform 
              WHERE perform_id = $perform_id
             ;";
    $statement = $connect->prepare($query);
    $statement->execute();
    $output = array("message" => "Delete Complete");
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
}
?>