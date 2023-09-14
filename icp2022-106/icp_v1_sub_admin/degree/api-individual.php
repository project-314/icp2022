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
    $faculty_id        = (int)$request_data->faculty_id;
    $degree_name       = "'". $request_data->degree_name ."'";


    $query = "INSERT INTO degree (faculty_id,degree_name) 
              VALUES  ($faculty_id,$degree_name)";
    $statement = $connect->prepare($query);
    $statement->execute($data);
    $output = array(" message " => " Insert Complete ");
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    
} 
else if ($request_data->action == "getall") {
    $query = "SELECT 
                     ins.institute_id,ins.institute_name,
                     fac.faculty_id,fac.faculty_name,
                     deg.degree_id,deg.degree_name

              FROM degree           AS deg 
              INNER JOIN faculty    AS fac  ON deg.faculty_id = fac.faculty_id
              INNER JOIN institute  AS ins  ON fac.institute_id = ins.institute_id
              ORDER BY deg.degree_id
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
    $degree_id = (int)$request_data->degree_id;
    
    $query = "SELECT 
                     ins.institute_id,ins.institute_name,
                     fac.faculty_id,fac.faculty_name,
                     deg.degree_id,deg.degree_name

                FROM degree           AS deg 
                INNER JOIN faculty    AS fac  ON deg.faculty_id = fac.faculty_id
                INNER JOIN institute  AS ins  ON fac.institute_id = ins.institute_id
                WHERE deg.degree_id = $degree_id
                ORDER BY deg.degree_id               
             ;";
    $statement = $connect->prepare($query);
    $statement->execute();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        // ข้อมูลการศึกษา
        $data['institute_id']       = $row['institute_id'];
        $data['institute_name']     = $row['institute_name'];
        $data['faculty_id']         = $row['faculty_id'];
        $data['faculty_name']       = $row['faculty_name'];
        $data['degree_id']          = $row['degree_id'];
        $data['degree_name']        = $row['degree_name'];
    }
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    
} 
else if ($request_data->action == "update") {
    $faculty_id        = (int)$request_data->faculty_id;
    $degree_id        = (int)$request_data->degree_id;
    $degree_name       = "'". $request_data->degree_name ."'";

    $query = "UPDATE degree 
        SET 
            faculty_id=$faculty_id,
            degree_name=$degree_name
        WHERE degree_id=$degree_id
        ;";
    $statement = $connect->prepare($query);
    $statement->execute($data);
    $output = array("message" => "Update Complete");
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    
} 
else if ($request_data->action == "delete") {
    $degree_id = (int)$request_data->degree_id;
    $query = "DELETE FROM degree WHERE degree_id = $degree_id
    ;";
    $statement = $connect->prepare($query);
    $statement->execute();
    $output = array("message" => "Delete Complete");
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    
}
?>