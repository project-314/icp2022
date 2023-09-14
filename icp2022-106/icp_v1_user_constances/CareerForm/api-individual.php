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
$username = "u486700931_icp3";
$password = "Dc48f$qz";
$database = "u486700931_icp3";

try {
        $connect = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
        $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // echo "Connected successfully";
    } catch(PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    }

if ($request_data->action == "insert") {
    echo " insert ";
    $career_name   = "'". $request_data->career_name ."'";
    $career_group_id   =  (int)$request_data->career_group_id ;

    $query = "INSERT INTO career(career_name, career_group_id)
              VALUES ($career_name,$career_group_id)
             ;";
    $statement = $connect->prepare($query);
    $statement->execute($data);
    $output = array(" message " => " Insert Complete ");
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
}
else if ($request_data->action == "getall") {
    $query = "SELECT car.career_id, car.career_name, car.career_group_id, cag.ca_group_name
              FROM career AS car
              INNER JOIN career_group AS cag ON car.career_group_id = cag.career_group_id
              ;";
    $statement = $connect->prepare($query);
    $statement->execute();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
    }
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
}
else if ($request_data->action == "get_career") {
    $query = "SELECT car.career_id, car.career_name, car.career_group_id, cag.ca_group_name
              FROM career AS car
              INNER JOIN career_group AS cag ON car.career_group_id = cag.career_group_id
              ;";
    $statement = $connect->prepare($query);
    $statement->execute();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
    }
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
}
else if ($request_data->action == "getall") {
    $query = "SELECT car.career_id, car.career_name, car.career_group_id, cag.ca_group_name
              FROM career AS car
              INNER JOIN career_group AS cag ON car.career_group_id = cag.career_group_id
              ;";
    $statement = $connect->prepare($query);
    $statement->execute();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
    }
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
}
else if ($request_data->action == "get_career_group") {
    $query = "SELECT car.career_group_id, car.ca_group_name, car.ca_group_description
              FROM career_group AS car
              ;";
    $statement = $connect->prepare($query);
    $statement->execute();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
    }
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
}
else if ($request_data->action == "edit") {
    $career_id = (int)$request_data->career_id;
    $query = "SELECT car.career_id, car.career_name, car.career_group_id, cag.ca_group_name
              FROM career  AS  car
              INNER JOIN career_group AS cag ON car.career_group_id = cag.career_group_id
              WHERE car.career_id = $career_id
    ;";
    $statement = $connect->prepare($query);
    $statement->execute();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $data['career_id']      = $row['career_id'];
        $data['career_name']    = $row['career_name'];
        $data['career_group_id']= $row['career_group_id'];
        $data['ca_group_name']  = $row['ca_group_name'];
    }
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
}
else if ($request_data->action == "update") {
    $career_id          = (int)$request_data->career_id;
    $career_name        = "'". $request_data->career_name ."'";
    $career_group_id    = (int)$request_data->career_group_id;

    $query = "UPDATE career
        SET
              career_name       = $career_name,
              career_group_id   = $career_group_id
        WHERE career_id         = $career_id
        ;";
    $statement = $connect->prepare($query);
    $statement->execute($data);
    $output = array("message" => "Update Complete");
    echo json_encode($output, JSON_UNESCAPED_UNICODE);

}
else if ($request_data->action == "delete") {
    $career_id = (int)$request_data->career_id;
    $query = "DELETE FROM career
              WHERE career_id = $career_id
             ;";
    $statement = $connect->prepare($query);
    $statement->execute();
    $output = array("message" => "Delete Complete");
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
}
?>
