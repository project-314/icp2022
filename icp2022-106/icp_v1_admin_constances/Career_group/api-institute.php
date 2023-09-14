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
    $data = array(
        ":institute_id" => $request_data->institute_id,
        ":institute_name" => $request_data->institute_name, 
        ":institute_address" => $request_data->institute_address,

    );
    $query = "INSERT INTO institute (institute_id,
                                      institute_name,
                                      institute_address) 
                             VALUES(:institute_id,
                                    :institute_name,
                                    :institute_address)";
    $statement = $connect->prepare($query);
    $statement->execute($data);
    $output = array(" message " => " Insert Complete ");
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    
} 
else if ($request_data->action == "insertInstitutes") {
  echo " insert institutes ";
  $data = array(
      ":institute_name" => $request_data->institute_name, 
  );
  $query = "INSERT INTO institute (institute_name) 
            VALUES(:institute_name)";
  $statement = $connect->prepare($query);
  $statement->execute($data);
  $output = array(" message " => " Insert Institute Complete ");
  echo json_encode($output, JSON_UNESCAPED_UNICODE);
  
} 
else if ($request_data->action == "getall") {
    $query = "SELECT * FROM institute as ins
              ;";
    $statement = $connect->prepare($query);
    $statement->execute();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
    }
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    
}
else if ($request_data->action == "getInstitutes") {
    $query = "SELECT * FROM institute as ins
              ;";
    $statement = $connect->prepare($query);
    $statement->execute();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
    }
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    
}
else if ($request_data->action == "getFacultys") {
    $institute_id = (int)$request_data->institute_id;
    $query = "SELECT * FROM faculty as fac
              WHERE fac.institute_id = $institute_id
              ;";
    $statement = $connect->prepare($query);
    $statement->execute();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
    }
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    
}
else if ($request_data->action == "insertFacultys") {
    echo " insert facultys ";
    $data = array(
        ":institute_id" => $request_data->institute_id,
        ":faculty_name" => $request_data->faculty_name, 
    );
    $query = "INSERT INTO faculty (institute_id,faculty_name) 
              VALUES(:institute_id,:faculty_name)";
    $statement = $connect->prepare($query);
    $statement->execute($data);
    $output = array(" message " => " Insert Faculty Complete ");
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
} 
else if ($request_data->action == "getDegrees") {
    $institute_id = (int)$request_data->institute_id;
    $faculty_id = (int)$request_data->faculty_id;
    
    $query = "SELECT * FROM degree as deg
              INNER JOIN faculty   as fac  ON deg.faculty_id = fac.faculty_id
              INNER JOIN institute as ins  ON fac.institute_id = ins.institute_id
              WHERE deg.faculty_id = $faculty_id
              ;";
    $statement = $connect->prepare($query);
    $statement->execute();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
    }
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    
}
else if ($request_data->action == "insertDegrees") {
    echo " insert degrees ";
    $data = array(
        ":faculty_id" => $request_data->faculty_id,
        ":degree_name" => $request_data->degree_name, 
    );
    $query = "INSERT INTO degree (faculty_id,degree_name) 
              VALUES(:faculty_id,:degree_name)";
    $statement = $connect->prepare($query);
    $statement->execute($data);
    $output = array(" message " => " Insert Degree Complete ");
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
} 
else if ($request_data->action == "getDepartments") {
    $institute_id = (int)$request_data->institute_id;
    $faculty_id = (int)$request_data->faculty_id;
    $degree_id = (int)$request_data->degree_id;
    
    $query = "SELECT * FROM department as dep
              INNER JOIN degree    as deg  ON dep.degree_id = deg.degree_id
              INNER JOIN faculty   as fac  ON deg.faculty_id = fac.faculty_id
              INNER JOIN institute as ins  ON fac.institute_id = ins.institute_id
              WHERE ins.institute_id=$institute_id AND  deg.faculty_id = $faculty_id AND deg.degree_id =  $degree_id
              ;";
    $statement = $connect->prepare($query);
    $statement->execute();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
    }
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    
}
else if ($request_data->action == "insertDepartments") {
    echo " insert departments ";
    $data = array(
        ":degree_id" => $request_data->degree_id,
        ":department_name" => $request_data->department_name, 
    );
    $query = "INSERT INTO department (degree_id,department_name) 
              VALUES(:degree_id,:department_name)";
    $statement = $connect->prepare($query);
    $statement->execute($data);
    $output = array(" message " => " Insert Department Complete ");
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
}
else if ($request_data->action == "edit") {
    $query = "SELECT * FROM institute WHERE institute_id = $request_data->institute_id";
    $statement = $connect->prepare($query);
    $statement->execute();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $data['institute_id'] = $row['institute_id'];
        $data['institute_name'] = $row['institute_name'];
        $data['institute_address'] = $row['institute_address'];
    }
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    
} 
// Institute
else if ($request_data->action == "update") {
    $data = array(
        ":institute_id" => $request_data->institute_id,
        ":institute_name" => $request_data->institute_name, 
        ":institute_address" => $request_data->institute_address,
    );
    $query = "UPDATE institute 
              SET 
                    institute_id        =:institute_id,
                    institute_name      =:institute_name,
                    institute_address   =:institute_address,
              WHERE institute_id = :institute_id
    ;";
    $statement = $connect->prepare($query);
    $statement->execute($data);
    $output = array("message" => "Update Complete");
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
} 
else if ($request_data->action == "delete") {
    $query = "DELETE 
              FROM  institute 
              WHERE institute_id = $request_data->institute_id";
    $statement = $connect->prepare($query);
    $statement->execute();
    $output = array("message" => "Delete Institute Complete");
    echo json_encode($output, JSON_UNESCAPED_UNICODE);    
}
// Faculty
else if ($request_data->action == "update_faculty") {
    $data = array(
        ":faculty_id" => $request_data->faculty_id,
        ":faculty_name" => $request_data->faculty_name, 
    );
    $query = "UPDATE faculty 
              SET 
              faculty_id        =:faculty_id,
              faculty_name      =:faculty_name
              WHERE faculty_id = :faculty_id
    ;";
    $statement = $connect->prepare($query);
    $statement->execute($data);
    $output = array("message" => "Update Faculty Complete");
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
} 
else if ($request_data->action == "delete_faculty") {
    $query = "DELETE 
              FROM  faculty 
              WHERE faculty_id = $request_data->faculty_id";
    $statement = $connect->prepare($query);
    $statement->execute();
    $output = array("message" => "Delete Faculty Complete");
    echo json_encode($output, JSON_UNESCAPED_UNICODE);    
}
// Degree
else if ($request_data->action == "update_degree") {
    $data = array(
        ":degree_id" => $request_data->degree_id,
        ":degree_name" => $request_data->degree_name
    );
    $query = "UPDATE degree 
              SET 
              degree_id        =:degree_id,
              degree_name      =:degree_name,
              WHERE degree_id = :degree_id
    ;";
    $statement = $connect->prepare($query);
    $statement->execute($data);
    $output = array("message" => "Update Degree Complete");
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
} 
else if ($request_data->action == "delete_degree") {
    $query = "DELETE 
              FROM  degree 
              WHERE degree_id = $request_data->degree_id";
    $statement = $connect->prepare($query);
    $statement->execute();
    $output = array("message" => "Delete Degree Complete");
    echo json_encode($output, JSON_UNESCAPED_UNICODE);    
}
// Department
else if ($request_data->action == "update_department") {
    $data = array(
        ":department_id" => $request_data->department_id,
        ":department_name" => $request_data->department_name, 
    );
    $query = "UPDATE department 
              SET 
              department_id        =:department_id,
              department_name      =:department_name,
              WHERE department_id = :department_id
    ;";
    $statement = $connect->prepare($query);
    $statement->execute($data);
    $output = array("message" => "Update Department Complete");
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
} 
else if ($request_data->action == "delete_department") {
    $query = "DELETE 
              FROM  department 
              WHERE department_id = $request_data->department_id
              ;";
    $statement = $connect->prepare($query);
    $statement->execute();
    $output = array("message" => "Delete Department Complete");
    echo json_encode($output, JSON_UNESCAPED_UNICODE);    
}
// Project
else if ($request_data->action == "update_project") {
    $data = array(
        ":project_id" => $request_data->project_id,
        ":project_name" => $request_data->project_name, 
    );
    $query = "UPDATE project 
              SET 
              project_id        =:project_id,
              project_name      =:project_name,
              WHERE project_id = :project_id
    ;";
    $statement = $connect->prepare($query);
    $statement->execute($data);
    $output = array("message" => "Update Project Complete");
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
} 
else if ($request_data->action == "delete_project") {
    $query = "DELETE 
              FROM  project 
              WHERE project_id = $request_data->project_id";
    $statement = $connect->prepare($query);
    $statement->execute();
    $output = array("message" => "Delete Project Complete");
    echo json_encode($output, JSON_UNESCAPED_UNICODE);    
}
?>