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
    $member_id          = (int)$request_data->member_id;
    $birthday           = "'". $request_data->birthday ."'";
    $telephone          = "'". $request_data->telephone ."'";
    // ข้อมูลการศึกษา
    $department_id      = (int)$request_data->department_id;
    $is_graduate        = (int)$request_data->is_graduate;
    $date               = "'". $request_data->date ."'";
    $year               = "'". $request_data->year ."'";
    // ข้อมูลความพิการ
    $is_disability      = (int)$request_data->is_disability;
    $disability_id      = (int)$request_data->disability_id;
    $dis_description    = "'". $request_data->dis_description ."'";
    // ข้อมูลโครงการ
    $project_id         = (int)$request_data->project_id;
    // ข้อมูลผู้ดูแลกลุ่ม
    $advisor_id         = (int)$request_data->advisor_id;

    $query = "INSERT INTO individual (member_id, birthday, telephone,
                                      department_id, is_graduate, date, year, 
                                      is_disability, disability_id, dis_description,
                                      project_id, advisor_id
                                    ) 
                             VALUES  ($member_id, $birthday, $telephone,
                                      $department_id, $is_graduate, $date, $year, 
                                      $is_disability, $disability_id, $dis_description,
                                      $project_id, $advisor_id
                                    )";
    $statement = $connect->prepare($query);
    $statement->execute($data);
    $output = array(" message " => " Insert Complete ");
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    
} 
else if ($request_data->action == "getall") {
    $member_id          = (int)$request_data->member_id;
    $query = "SELECT ind.individual_id,ind.birthday,ind.telephone,
                     ind.is_graduate,ind.date,ind.year, 
                     mem.member_id,mem.full_name,mem.status,
                     ins.institute_id,ins.institute_name,
                     fac.faculty_name,
                     deg.degree_name,
                     dep.department_id,dep.department_name,
                     dis.disability_id,dis.disability_name,ind.dis_description,ind.is_disability,
                     pro.project_id,pro.project_name,
                     ind.advisor_id,mem1.full_name as advisor_name 
              FROM individual as ind
              LEFT OUTER JOIN member     as mem  ON ind.member_id = mem.member_id
              LEFT OUTER JOIN project    as pro  ON ind.project_id = pro.project_id
              LEFT OUTER JOIN disability as dis  ON ind.disability_id = dis.disability_id
              LEFT OUTER JOIN department as dep  ON ind.department_id = dep.department_id
              LEFT OUTER JOIN degree     as deg  ON dep.degree_id = deg.degree_id
              LEFT OUTER JOIN faculty    as fac  ON deg.faculty_id = fac.faculty_id
              LEFT OUTER JOIN institute  as ins  ON fac.institute_id = ins.institute_id
              LEFT OUTER JOIN member     as mem1 ON ind.advisor_id = mem1.member_id
              WHERE ind.advisor_id = $member_id
              ORDER BY ind.individual_id
              ;";
    $statement = $connect->prepare($query);
    $statement->execute();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
    }
    echo json_encode($data, JSON_UNESCAPED_UNICODE);   
}
// else if ($request_data->action == "getMember") {
//     $member_id          = (int)$request_data->member_id;
//     $query = "SELECT mem.member_id,mem.full_name,mem.status
//               FROM member as mem
//               WHERE mem.created_by = $member_id  
//               ORDER BY mem.member_id
//              ;";
//     $statement = $connect->prepare($query);
//     $statement->execute();
//     while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
//         $data[] = $row;
//     }
//     echo json_encode($data, JSON_UNESCAPED_UNICODE);   
// }
// else if ($request_data->action == "getMember") {
//     $member_id          = (int)$request_data->member_id;
//     $query = "SELECT mem.member_id,mem.full_name,mem.status
//               FROM member AS mem
//               WHERE mem.member_id IN
//                 (SELECT mem.member_id
//                 FROM member AS mem
//                 INNER JOIN individual AS ind ON mem.member_id  = ind.member_id
//                 INNER JOIN member     AS adv ON ind.advisor_id = adv.member_id
//                 WHERE (ind.advisor_id = $member_id ))
//               OR mem.member_id IN
//                 (SELECT mem.member_id
//                 FROM member AS mem
//                 WHERE (mem.created_by = $member_id ))
//              ;";
//     $statement = $connect->prepare($query);
//     $statement->execute();
//     while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
//         $data[] = $row;
//     }
//     echo json_encode($data, JSON_UNESCAPED_UNICODE);   
// }
else if ($request_data->action == "getMember") {
    $member_id          = (int)$request_data->member_id;
    $query = "SELECT DISTINCT mem.member_id,mem.full_name,mem.status
              FROM member AS mem
              LEFT OUTER JOIN individual AS ind ON mem.member_id  = ind.member_id
              LEFT OUTER JOIN member     AS adv ON ind.advisor_id = adv.member_id
              WHERE (ind.advisor_id = $member_id ) OR (mem.created_by = $member_id )
             ;";
    $statement = $connect->prepare($query);
    $statement->execute();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
    }
    echo json_encode($data, JSON_UNESCAPED_UNICODE);   
}
else if ($request_data->action == "edit") {
    $individual_id = (int)$request_data->individual_id;
    
    $query = "SELECT 
                     ind.individual_id,ind.birthday,ind.telephone,
                     ind.is_graduate,ind.date,ind.year,ind.member_id, 
                     mem.member_id, mem.full_name, mem.status, 
                     ins.institute_id,ins.institute_name,
                     fac.faculty_id,fac.faculty_name,
                     deg.degree_id,deg.degree_name,
                     dep.department_id,dep.department_name,
                     dis.disability_id,dis.disability_name,ind.dis_description,ind.is_disability,
                     pro.project_id,pro.project_name,
                     ind.advisor_id,mem1.full_name as advisor_name 
                FROM individual  as  ind
                LEFT OUTER JOIN project      as  pro  ON ind.project_id      = pro.project_id
                LEFT OUTER JOIN disability   as  dis  ON ind.disability_id   = dis.disability_id
                LEFT OUTER JOIN department   as  dep  ON ind.department_id   = dep.department_id
                LEFT OUTER JOIN degree       as  deg  ON dep.degree_id       = deg.degree_id
                LEFT OUTER JOIN faculty      as  fac  ON deg.faculty_id      = fac.faculty_id
                LEFT OUTER JOIN institute    as  ins  ON fac.institute_id    = ins.institute_id
                LEFT OUTER JOIN member       as  mem  ON ind.member_id       = mem.member_id
                LEFT OUTER JOIN member       as  mem1 ON ind.advisor_id      = mem1.member_id
                WHERE individual_id = $individual_id
    ;";
    $statement = $connect->prepare($query);
    $statement->execute();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $data['individual_id']      = $row['individual_id'];
        // ข้อมูลส่วนตัว
        $data['member_id']          = $row['member_id'];
        $data['full_name']          = $row['full_name'];
        $data['status']             = $row['status'];
        
        $data['birthday']           = $row['birthday'];
        $data['telephone']          = $row['telephone'];
        // ข้อมูลการศึกษา
        $data['institute_id']       = $row['institute_id'];
        $data['institute_name']     = $row['institute_name'];
        $data['faculty_id']         = $row['faculty_id'];
        $data['faculty_name']       = $row['faculty_name'];
        $data['degree_id']          = $row['degree_id'];
        $data['degree_name']        = $row['degree_name'];
        $data['department_id']      = $row['department_id'];
        $data['department_name']    = $row['department_name'];

        $data['is_graduate']        = $row['is_graduate'];
        $data['date']               = $row['date'];
        $data['year']               = $row['year'];
        // ข้อมูลความพิการ
        $data['is_disability']      = $row['is_disability'];
        $data['disability_id']      = $row['disability_id'];
        $data['disability_name']    = $row['disability_name'];
        $data['dis_description']    = $row['dis_description'];
        // ข้อมูลโครงการ
        $data['project_id']         = $row['project_id'];
        $data['project_name']       = $row['project_name'];
        // ข้อมูลผู้ดูแลกลุ่ม
        $data['advisor_id']         = $row['advisor_id'];
        $data['advisor_name']       = $row['advisor_name'];
    }
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    
} 
else if ($request_data->action == "update") {
    $individual_id      = (int)$request_data->individual_id;
    // ข้อมูลส่วนตัว
    $member_id          = (int)$request_data->member_id;
    $birthday           = "'". $request_data->birthday ."'";
    $telephone          = "'". $request_data->telephone ."'";
    // ข้อมูลการศึกษา
    $department_id      = (int)$request_data->department_id;
    $is_graduate        = (int)$request_data->is_graduate;
    $date               = "'". $request_data->date ."'";
    $year               = "'". $request_data->year ."'";
    // ข้อมูลความพิการ
    $is_disability      = (int)$request_data->is_disability;
    $disability_id      = (int)$request_data->disability_id;
    $dis_description    = "'". $request_data->dis_description ."'";
    // ข้อมูลโครงการ
    $project_id         = (int)$request_data->project_id;
    $query = "UPDATE individual 
        SET 
            individual_id=$individual_id,
            -- ข้อมูลส่วนตัว
            member_id=$member_id,
            birthday=$birthday,
            telephone=$telephone,
            -- ข้อมูลการศึกษา
            department_id=$department_id,
            is_graduate=$is_graduate,
            date=$date ,
            year=$year,
            -- ข้อมูลความพิการ
            is_disability=$is_disability,
            disability_id=$disability_id,
            dis_description=$dis_description ,
            -- ข้อมูลโครงการ
            project_id=$project_id
        WHERE individual_id=$individual_id
        ;";
    $statement = $connect->prepare($query);
    $statement->execute($data);
    $output = array("message" => "Update Complete");
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    
} 
else if ($request_data->action == "delete") {
    $individual_id = (int)$request_data->individual_id;
    $query = "DELETE FROM individual WHERE individual_id = $individual_id
    ;";
    $statement = $connect->prepare($query);
    $statement->execute();
    $output = array("message" => "Delete Complete");
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    
}
?>