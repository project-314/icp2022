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
    $degree_id          = (int)$request_data->degree_id;
    $department_name    = "'". $request_data->department_name ."'";

    $query = "INSERT INTO department (degree_id,department_name) 
                             VALUES  ($degree_id,$department_name)";
    $statement = $connect->prepare($query);
    $statement->execute($data);
    $output = array(" message " => " Insert Complete ");
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    
} 
else if ($request_data->action == "getall") {
    $query = "SELECT 
                     ins.institute_id,ins.institute_name,
                     fac.faculty_id,fac.faculty_name,
                     deg.degree_id,deg.degree_name,
                     dep.department_id,dep.department_name 

              FROM department as dep  
              INNER JOIN degree     as deg  ON dep.degree_id = deg.degree_id
              INNER JOIN faculty    as fac  ON deg.faculty_id = fac.faculty_id
              INNER JOIN institute  as ins  ON fac.institute_id = ins.institute_id
              ORDER BY dep.department_id
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
    $department_id = (int)$request_data->department_id;
    
    $query = "SELECT 
                ins.institute_id,ins.institute_name,
                fac.faculty_id,fac.faculty_name,
                deg.degree_id,deg.degree_name,
                dep.department_id,dep.department_name 

            FROM department as dep  
            INNER JOIN degree     as deg  ON dep.degree_id = deg.degree_id
            INNER JOIN faculty    as fac  ON deg.faculty_id = fac.faculty_id
            INNER JOIN institute  as ins  ON fac.institute_id = ins.institute_id
            WHERE dep.department_id = $department_id
            ORDER BY dep.department_id 
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
        $data['department_id']      = $row['department_id'];
        $data['department_name']    = $row['department_name'];
    }
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
} 
else if ($request_data->action == "update") {
    $degree_id          = (int)$request_data->degree_id;
    $department_id      = (int)$request_data->department_id;
    $department_name    = "'". $request_data->department_name ."'";

    $query = "UPDATE department 
        SET 
            degree_id       = $degree_id,
            department_name = $department_name
        WHERE department_id=$department_id
        ;";
    $statement = $connect->prepare($query);
    $statement->execute($data);
    $output = array("message" => "Update Complete");
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    
} 
else if ($request_data->action == "delete") {
    $department_id  = (int)$request_data->department_id;
    $query = "DELETE FROM department 
              WHERE department_id = $department_id 
             ;";
    $statement = $connect->prepare($query);
    $statement->execute();
    $output = array("message" => "Delete Complete");
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
}
?>