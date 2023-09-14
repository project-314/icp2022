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
    $self_assessment_date   = "'". $request_data->self_assessment_date ."'";
    $qa_plan_career_id      = (int) $request_data->qa_plan_career_id;
    $perform_id             = (int) $request_data->perform_id;

    $query = "INSERT INTO self_assessment (self_assessment_date,
                          qa_plan_career_id, perform_id) 
              VALUES($self_assessment_date,
                     $qa_plan_career_id, $perform_id)
             ;";

    $statement = $connect->prepare($query);
    $statement->execute($data);
    $output = array(" message " => " Insert Complete ");
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    
} 
// else if ($request_data->action == "insert_reference") {
//     $self_assessment_id      = (int)$request_data->self_assessment_id;
//     $references              = $request_data->references;
//     $query = "INSERT INTO reference (self_assessment_id, reference_description) VALUES ";
//     $query_parts = array();
//     for($i=0; $i<count($references); $i++){
//         $query_parts[] = "(" . $self_assessment_id . ", '" . $references[$i] . "')";
//     }
//     $query .= implode(',', $query_parts);
//     $statement = $connect->prepare($query);
//     $statement->execute();
//     $output = array(" message " => " Insert Complete ");
//     echo json_encode($output, JSON_UNESCAPED_UNICODE);
    
// }
else if ($request_data->action == "insert_reference") {
    $self_assessment_id      = (int)$request_data->self_assessment_id;
    $references              = $request_data->references;
    $query = "INSERT INTO reference (self_assessment_id, reference_description) VALUES ";
    $query_parts = array();
    for($i=0; $i<count($references); $i++){
        $query_parts[] = "(" . $self_assessment_id . ", '" . $references[$i] . "')";
    }
    $query .= implode(',', $query_parts);
    $statement = $connect->prepare($query);
    $statement->execute();
    $output = array(" message " => " Insert Complete ");
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    
}
else if ($request_data->action == "get_all_references") {
    $self_assessment_id = (int) $request_data->self_assessment_id;
    $query = "SELECT * FROM reference as ref
              LEFT OUTER JOIN self_assessment as sel ON ref.self_assessment_id = sel.self_assessment_id
              WHERE ref.self_assessment_id = $self_assessment_id
              ORDER BY ref.reference_id ASC
             ;";
    $statement = $connect->prepare($query);
    $statement->execute();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
    }
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
}
else if ($request_data->action == "get_reference_by_self_assessment_id") {
    $self_assessment_id = (int) $request_data->self_assessment_id;
    $query = "SELECT  pla.member_id, ref.reference_id, sel.self_assessment_id, ref.reference_description
              FROM reference as ref
              LEFT OUTER JOIN self_assessment as sel ON ref.self_assessment_id = sel.self_assessment_id
              LEFT OUTER JOIN qa_plan_career  as qpc ON sel.qa_plan_career_id = qpc.qa_plan_career_id
              LEFT OUTER JOIN plan_career     as pla ON qpc.plan_career_id = pla.plan_career_id
              WHERE ref.self_assessment_id = $self_assessment_id
              ORDER BY ref.reference_id ASC
             ;";
    $statement = $connect->prepare($query);
    $statement->execute();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
    }
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
}
else if ($request_data->action == "delete_reference_by_reference_id") {
    $query = "DELETE FROM reference 
              WHERE reference_id = $request_data->reference_id
              ;";
    $statement = $connect->prepare($query);
    $statement->execute();
    $output = array("message" => "Delete Complete");
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
}
else if ($request_data->action == "add_reference_by_self_assessment_id") {
    $self_assessment_id      = (int)$request_data->self_assessment_id;
    $references              = $request_data->references;
    $query = "INSERT INTO reference (self_assessment_id, reference_description) 
              VALUES ($self_assessment_id,$references)
              ;";
    $statement = $connect->prepare($query);
    $statement->execute();
    $output = array(" message " => " Insert Complete ");
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
}
else if ($request_data->action == "add_reference") {
    $self_assessment_id      = (int)$request_data->self_assessment_id;
    $reference_description   = "'".$request_data->reference_description."'";
    $query = "INSERT INTO reference (self_assessment_id, reference_description) 
              VALUES ($self_assessment_id,$reference_description)
              ;";
    $statement = $connect->prepare($query);
    $statement->execute();
    $output = array(" message " => " Insert Complete ");
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
}
else if ($request_data->action == "edit_reference_by_reference_id") {
    $reference_id            = (int)$request_data->reference_id;
    $reference_description   = "'".$request_data->reference_description."'";

    $query = "UPDATE reference
              SET 
                     reference_description   = $reference_description
              WHERE  reference_id            = $reference_id
             ;";

    $statement = $connect->prepare($query);
    $statement->execute();
    $output = array(" message " => " Update Complete ");
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
}
else if ($request_data->action == "getall") {
    $query = "SELECT * FROM self_assessment
             ;";
    $statement = $connect->prepare($query);
    $statement->execute();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
    }
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    
}
else if ($request_data->action == "getAll") {
  $query = "SELECT sef.self_assessment_id, sef.self_assessment_date,
                   car.career_id, car.career_name,
                   qpc.qa_plan_career_id, 
                   qua.qualification_name,
                   tar.target_id,tar.target_name, tar.target_value,
                   per.perform_id, per.perform_name, per.perform_value,
                   pla.member_id,mem.full_name,
                   ref.reference_id,ref.reference_description 

                 FROM self_assessment AS sef
                 LEFT OUTER JOIN qa_plan_career AS qpc ON sef.qa_plan_career_id = qpc.qa_plan_career_id
                 LEFT OUTER JOIN qualification  AS qua ON qpc.qualification_id = qua.qualification_id
                 LEFT OUTER JOIN plan_career    AS pla ON qpc.plan_career_id = pla.plan_career_id

                 LEFT OUTER JOIN member         AS mem ON pla.member_id = mem.member_id
                 LEFT OUTER JOIN career         AS car ON pla.career_id = car.career_id
                 LEFT OUTER JOIN target         AS tar ON qpc.target_id = tar.target_id
                 LEFT OUTER JOIN perform        AS per ON sef.perform_id = per.perform_id

                 LEFT OUTER JOIN reference      AS ref ON sef.self_assessment_id = ref.self_assessment_id
                 GROUP BY sef.self_assessment_id
                 ORDER BY sef.self_assessment_id ASC
           ;";
  $statement = $connect->prepare($query);
  $statement->execute();
  while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
      $data[] = $row;
  }
  echo json_encode($data, JSON_UNESCAPED_UNICODE);
  
}
else if ($request_data->action == "lastRecord") {
  $query = "SELECT sef.self_assessment_id
            FROM self_assessment AS sef
            ORDER BY sef.self_assessment_id DESC LIMIT 1
           ;";
  $statement = $connect->prepare($query);
  $statement->execute();
  while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
      $data[] = $row;
  }
  echo json_encode($data, JSON_UNESCAPED_UNICODE);
}
else if ($request_data->action == "getAllReference") {
    $query = "SELECT * 
              FROM  reference as ref
              ORDER BY ref.reference_id ASC
             ;";
    $statement = $connect->prepare($query);
    $statement->execute();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
    }
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    
}
else if ($request_data->action == "getAllReference_") {
    $self_assessment_id = (int)$request_data->self_assessment_id;

    $query = "SELECT pla.member_id, ref.reference_id, sel.self_assessment_id, ref.reference_description
              FROM  reference as ref
              LEFT OUTER JOIN self_assessment as sel ON ref.self_assessment_id = sel.self_assessment_id
              LEFT OUTER JOIN qa_plan_career  as qpc ON sel.qa_plan_career_id = qpc.qa_plan_career_id
              LEFT OUTER JOIN plan_career     as pla ON qpc.plan_career_id = pla.plan_career_id
              WHERE ref.self_assessment_id = $self_assessment_id 
              ORDER BY ref.reference_id ASC
             ;";
             
    $statement = $connect->prepare($query);
    $statement->execute();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
    }
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    
}
else if ($request_data->action == "get_self_aassessment_by_qa_plan_career_id") {
    $qa_plan_career_id      = (int) $request_data->qa_plan_career_id;
    $query = "SELECT * 
              FROM self_assessment as sef
              LEFT OUTER JOIN qa_plan_career as qpc ON sef.qa_plan_career_id = qpc.qa_plan_career_id
              LEFT OUTER JOIN qualification  as qua ON qpc.qualification_id = qua.qualification_id
              LEFT OUTER JOIN plan_career    as pla ON qpc.plan_career_id = pla.plan_career_id
              LEFT OUTER JOIN career         as car ON pla.career_id = car.career_id
              LEFT OUTER JOIN target         as tar ON qpc.target_id = tar.target_id
              LEFT OUTER JOIN perform        as per ON sef.perform_id = per.perform_id
              WHERE sef.qa_plan_career_id = $qa_plan_career_id
    ;";
    $statement = $connect->prepare($query);
    $statement->execute();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
    }
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    
}
else if ($request_data->action == "edit") {
    $self_assessment_id     = (int) $request_data->self_assessment_id;
    $query = "SELECT  sef.self_assessment_id,sef.self_assessment_date,
                      mem.member_id,mem.full_name,mem.status, 
                      plc.plan_career_id,plc.start_date,car.career_id,car.career_name,
                      qap.qa_plan_career_id,qua.qualification_id,qua.qualification_name,
                      tar.target_name,tar.target_value,tar.target_description,
                      lev.level_description,lev.level_name,lev.weigth,
                      sef.perform_id

              FROM self_assessment as sef
              LEFT OUTER JOIN qa_plan_career AS qap ON sef.qa_plan_career_id = qap.qa_plan_career_id
              LEFT OUTER JOIN qualification  AS qua ON qap.qualification_id = qua.qualification_id  
              LEFT OUTER JOIN level          AS lev ON qap.level_id          = lev.level_id
              LEFT OUTER JOIN target         AS tar ON qap.target_id         = tar.target_id

              LEFT OUTER JOIN plan_career    AS plc ON qap.plan_career_id = plc.plan_career_id
              LEFT OUTER JOIN member         AS mem ON plc.member_id = mem.member_id
              LEFT OUTER JOIN career         AS car ON plc.career_id = car.career_id
                
              WHERE sef.self_assessment_id = $self_assessment_id
              ;";
    $statement = $connect->prepare($query);
    $statement->execute();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $data['member_id']              = $row['member_id'];
        $data['full_name']              = $row['full_name'];
        $data['status']                 = $row['status'];

        $data['plan_career_id']         = $row['plan_career_id'];
        $data['career_name']            = $row['career_name'];
        $data['start_date']             = $row['start_date'];

        $data['qa_plan_career_id']      = $row['qa_plan_career_id'];
        $data['qualification_name']     = $row['qualification_name'];
        $data['target_name']            = $row['target_name'];
        $data['level_description']      = $row['level_description'];

        $data['self_assessment_id']     = $row['self_assessment_id'];
        $data['self_assessment_date']   = $row['self_assessment_date'];
        
        $data['perform_id']             = $row['perform_id'];
    }
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    
} 
else if ($request_data->action == "update") {
    $self_assessment_id     = (int) $request_data->self_assessment_id;
    $self_assessment_date   = "'". $request_data->self_assessment_date ."'";
    $qa_plan_career_id      = (int) $request_data->qa_plan_career_id;
    $perform_id             = (int) $request_data->perform_id;
    $query = "UPDATE self_assessment 
              SET 
                    qa_plan_career_id       = $qa_plan_career_id,
                    self_assessment_date    = $self_assessment_date,
                    perform_id              = $perform_id 
              WHERE self_assessment_id = $self_assessment_id
             ;";
    $statement = $connect->prepare($query);
    $statement->execute($data);
    $output = array("message" => "Update Complete");
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    
} 
else if ($request_data->action == "delete") {
    $query = "DELETE FROM self_assessment 
              WHERE self_assessment_id = $request_data->self_assessment_id
              ;";
    $statement = $connect->prepare($query);
    $statement->execute();
    $output = array("message" => "Delete Complete");
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    
}
else if ($request_data->action == "getPerform") {
    $query = "SELECT * 
              FROM perform 
             ;";
    $statement = $connect->prepare($query);
    $statement->execute();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
    }
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
}
else if ($request_data->action == "get_plan_career_by_member_id") {
    $query = "SELECT * FROM plan_career as pla
                       LEFT OUTER JOIN career as car ON pla.career_id=car.career_id
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
  
              LEFT OUTER JOIN plan_career    AS plc ON mem.member_id         = plc.member_id
              LEFT OUTER JOIN career         AS car ON plc.career_id         = car.career_id
  
              LEFT OUTER JOIN qa_plan_career AS qap ON plc.plan_career_id    = qap.plan_career_id
              LEFT OUTER JOIN qualification  AS qua ON qap.qualification_id  = qua.qualification_id
              GROUP BY mem.member_id
              ORDER BY mem.member_id ASC
              ;";
    $statement = $connect->prepare($query);
    $statement->execute();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
    }
    echo json_encode($data, JSON_UNESCAPED_UNICODE);   
  }
?>