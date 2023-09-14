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
    $plan_career_id = (int) $request_data->plan_career_id;
    $qualification_id = (int) $request_data->qualification_id;
    $level_id = (int) $request_data->level_id;
    $target_id = (int) $request_data->target_id;

    $query = "INSERT INTO qa_plan_career (plan_career_id, qualification_id, level_id, target_id)
              VALUES($plan_career_id,$qualification_id,$level_id,$target_id)
              ;";
    $statement = $connect->prepare($query);
    $statement->execute($data);
    $output = array(" message " => " Insert Complete ");
    echo json_encode($output, JSON_UNESCAPED_UNICODE);

}
else if ($request_data->action == "getall") {
    // echo " getAllUser ";
    $query = "SELECT * FROM career_qualification";
    $statement = $connect->prepare($query);
    $statement->execute();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
    }
    echo json_encode($data, JSON_UNESCAPED_UNICODE);

}
else if ($request_data->action == "getAll") {
    // echo " getAllUser ";
    $query = "SELECT * FROM
              qa_plan_career           as qpc
              INNER JOIN plan_career   as pca ON qpc.plan_career_id = pca.plan_career_id
              INNER JOIN career        as car ON pca.career_id = car.career_id
              INNER JOIN qualification as qua ON qpc.qualification_id = qua.qualification_id
              INNER JOIN level         as lev ON qpc.level_id = lev.level_id
              INNER JOIN target        as tar ON qpc.target_id = tar.target_id
              ORDER BY qpc.qa_plan_career_id asc
    ;";
    $statement = $connect->prepare($query);
    $statement->execute();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
    }
    echo json_encode($data, JSON_UNESCAPED_UNICODE);

}
else if ($request_data->action == "getEmpCareer1") {
    $query = "SELECT pc.Plan_Career_id, emp.id, pc.Name_Plan_Career
    FROM employee as emp
    INNER JOIN plan_career as pc ON pc.Employee_id = emp.id
    WHERE emp.id = $request_data->employee_id
    ORDER BY pc.Plan_Career_id asc";
    $statement = $connect->prepare($query);
    $statement->execute();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
    }
    echo json_encode($data, JSON_UNESCAPED_UNICODE);

}
else if ($request_data->action == "getEmpCareer") {
    $query = "SELECT * FROM employee as em
    INNER JOIN plan_career as pc ON em.id = pc.employee_id
    INNER JOIN career as ca ON pc.Career_id = ca.career_id

    -- WHERE pc.Employee_id = $request_data->employee_id
    ORDER BY pc.Career_id asc";
    $statement = $connect->prepare($query);
    $statement->execute();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
    }
    echo json_encode($data, JSON_UNESCAPED_UNICODE);

}
else if ($request_data->action == "getCareerQualifiation") {
    $query = "SELECT *
              FROM qualification as qua
              ORDER BY qua.qualification_id asc
              ;";
    $statement = $connect->prepare($query);
    $statement->execute();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
    }
    echo json_encode($data, JSON_UNESCAPED_UNICODE);

}
else if ($request_data->action == "getCareer_Qualifiation") {
    $query = "SELECT *
    FROM qualification as q
    WHERE q.planCareerId = $request_data->career_id
    ORDER BY q.qualificationId asc";
    $statement = $connect->prepare($query);
    $statement->execute();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
    }
    echo json_encode($data, JSON_UNESCAPED_UNICODE);

}
else if ($request_data->action == "getPlanCareer") {
    $query = "SELECT emp.id,emp.name,emp.study_faculty,emp.university,emp.disability_type,
    pc.Plan_Career_id, pc.Name_Plan_Career
    FROM employee as emp
    INNER JOIN plan_career as pc ON pc.Employee_id = emp.id
    WHERE emp.id = $request_data->employeeId
    ORDER BY emp.id asc";
    $statement = $connect->prepare($query);
    $statement->execute();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
    }
    echo json_encode($data, JSON_UNESCAPED_UNICODE);

}
else if ($request_data->action == "edit") {
    $qa_plan_career_id = (int)$request_data->qa_plan_career_id;
    $query = "SELECT * FROM qa_plan_career AS qap
              INNER JOIN plan_career       AS pla ON qap.plan_career_id = pla.plan_career_id
              INNER JOIN career            AS car ON pla.career_id = car.career_id
              INNER JOIN qualification     AS qua ON qap.qualification_id = qua.qualification_id
              INNER JOIN level             AS lev ON qap.level_id = lev.level_id
              INNER JOIN target            AS tar ON qap.target_id = tar.target_id
              WHERE qa_plan_career_id = $qa_plan_career_id
              ;";
    $statement = $connect->prepare($query);
    $statement->execute();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $data['qa_plan_career_id'] = $row['qa_plan_career_id'];
        $data['plan_career_id'] = $row['plan_career_id'];
        $data['career_id'] = $row['career_id'];
        $data['career_name'] = $row['career_name'];
        $data['qualification_id'] = $row['qualification_id'];
        $data['qualification_name'] = $row['qualification_name'];
        $data['level_id'] = $row['level_id'];
        $data['level_name'] = $row['level_name'];
        $data['target_id'] = $row['target_id'];
        $data['target_name'] = $row['target_name'];
    }
    echo json_encode($data, JSON_UNESCAPED_UNICODE);

}
else if ($request_data->action == "update") {
    $qa_plan_career_id  = (int) $request_data->qa_plan_career_id;
    $plan_career_id     = (int) $request_data->plan_career_id;
    $qualification_id   = (int) $request_data->qualification_id;
    $level_id           = (int) $request_data->level_id;
    $target_id          = (int) $request_data->target_id;

    $query = "UPDATE qa_plan_career
              SET
                    plan_career_id=$plan_career_id,
                    qualification_id=$qualification_id,
                    level_id=$level_id,
                    target_id=$target_id
              WHERE qa_plan_career_id = $qa_plan_career_id
              ;";
    $statement = $connect->prepare($query);
    $statement->execute($data);
    $output = array("message" => "Update Complete");
    echo json_encode($output, JSON_UNESCAPED_UNICODE);

}
else if ($request_data->action == "delete") {
    $qa_plan_career_id = (int) $request_data->qa_plan_career_id;
    $query = "DELETE FROM qa_plan_career
              WHERE qa_plan_career_id = $qa_plan_career_id
              ";
    $statement = $connect->prepare($query);
    $statement->execute();
    $output = array("message" => "Delete Complete");
    echo json_encode($output, JSON_UNESCAPED_UNICODE);

}
else if ($request_data->action == "getTarget") {
    $query = "SELECT * FROM target";
    $statement = $connect->prepare($query);
    $statement->execute();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
    }
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
}
else if ($request_data->action == "getLevel") {
    $query = "SELECT * FROM level";
    $statement = $connect->prepare($query);
    $statement->execute();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
    }
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
}
else if ($request_data->action == "get_year_table") {
    $member_id = (int) $request_data->member_id;
    $query = "SELECT  qpc.qualification_id,
                      mem.full_name,
                      DATE_FORMAT(sel.self_assessment_date,'%Y') AS Year,
                      car.career_name,
                      qua.qualification_name,
                      lev.level_name,
                      tar.target_name, tar.target_value,
                      ROUND(AVG(per.perform_value),2) AS avg_perform_value

                FROM    self_assessment     AS sel
                INNER JOIN perform 	        AS per ON sel.perform_id = per.perform_id
                INNER JOIN qa_plan_career   AS qpc ON sel.qa_plan_career_id = qpc.qa_plan_career_id
                INNER JOIN qualification    AS qua ON qpc.qualification_id = qua.qualification_id

                INNER JOIN plan_career      AS pla ON qpc.plan_career_id =  pla.plan_career_id
                INNER JOIN member          AS mem ON pla.member_id =  mem.member_id

                INNER JOIN career           AS car ON pla.career_id = car.career_id
                INNER JOIN target 	        AS tar ON qpc.target_id = tar.target_id
                INNER JOIN level 	        AS lev ON qpc.level_id = lev.level_id

                WHERE mem.member_id = $member_id
                GROUP BY Year,qpc.qualification_id, qpc.target_id
                ORDER BY Year,qpc.qualification_id, qpc.target_id
    ;";
    $statement = $connect->prepare($query);
    $statement->execute();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
    }
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
}
else if ($request_data->action == "get_month_table") {
        $member_id = (int) $request_data->member_id;
        $query = "SELECT  sel.self_assessment_id,
                          mem.full_name,
                          DATE_FORMAT(sel.self_assessment_date,'%m') AS M,
                          DATE_FORMAT(sel.self_assessment_date,'%M') AS Month,
                          DATE_FORMAT(sel.self_assessment_date,'%Y') AS Year,
                          car.career_name,
                          qua.qualification_name,
                          lev.level_name,
                          tar.target_name, tar.target_value,
                          ROUND(AVG(per.perform_value),2) AS avg_perform_value

                FROM    self_assessment    AS sel
                INNER JOIN perform 	       AS per ON sel.perform_id = per.perform_id
                INNER JOIN qa_plan_career  AS qpc ON sel.qa_plan_career_id = qpc.qa_plan_career_id
                INNER JOIN qualification   AS qua ON qpc.qualification_id = qua.qualification_id

                INNER JOIN plan_career     AS pla ON qpc.plan_career_id =  pla.plan_career_id
                INNER JOIN member          AS mem ON pla.member_id =  mem.member_id

                INNER JOIN career          AS car ON pla.career_id = car.career_id
                INNER JOIN target 	       AS tar ON qpc.target_id = tar.target_id
                INNER JOIN level 	       AS lev ON qpc.level_id = lev.level_id

                WHERE mem.member_id = $member_id
                GROUP BY Year, M, qpc.qualification_id, qpc.target_id
                ORDER BY Year, M, qpc.qualification_id, qpc.target_id
    ;";
    $statement = $connect->prepare($query);
    $statement->execute();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
    }
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
}
else if ($request_data->action == "get_qa_plan_career") {
    $member_id = (int) $request_data->member_id;
    $query = "SELECT mem.member_id, mem.full_name,
                    ind.individual_id,ind.is_graduate,ind.year,ind.date,ind.birthday,ind.telephone,
                    ind.is_disability,ind.disability_id,dis.disability_name,ind.dis_description,
                    ind.project_id,pro.project_name,
                    ind.advisor_id,adv.full_name AS advisor_name,

                    ind.department_id,dep.department_name,
                    deg.degree_id,deg.degree_name,
                    fac.faculty_id,fac.faculty_name,
                    ins.institute_id,ins.institute_name,

                    plc.plan_career_id,plc.start_date,plc.end_date,
                    plc.career_id,car.career_name,
                    car.career_group_id,cag.ca_group_name,

                    qpc.qa_plan_career_id,
                    qpc.qualification_id, qua.qualification_name,  qua.qualification_group_id, qug.qualification_group_name,
                    qpc.level_id,lev.level_name,lev.weigth,
                    qpc.target_id, tar.target_name,tar.target_value,

                    pla.plan_id, pla.plan_title, pla.plan_channel, pla.plan_start_date, pla.plan_end_date,
                    pla.importance_id,imp.importance_name,imp.importance_value,

                    pla.development_id, dev.development_name,
                    sel.self_assessment_id, sel.self_assessment_date,
                    sel.perform_id,per.perform_name,per.perform_value
                FROM member 		  			AS mem
                INNER JOIN individual 			AS ind ON mem.member_id 				= ind.member_id
                INNER JOIN disability 			AS dis ON ind.disability_id				= dis.disability_id
                INNER JOIN project 				AS pro ON ind.project_id				= pro.project_id
                INNER JOIN member 				AS adv ON ind.advisor_id				= adv.member_id

                INNER JOIN department 			AS dep ON ind.department_id 			= dep.department_id
                INNER JOIN degree 	  			AS deg ON dep.degree_id 				= deg.degree_id
                INNER JOIN faculty 	  			AS fac ON deg.faculty_id 				= fac.faculty_id
                INNER JOIN institute  			AS ins ON fac.institute_id 				= ins.institute_id

                INNER JOIN plan_career  		AS plc ON mem.member_id 				= plc.member_id
                INNER JOIN career  				AS car ON plc.career_id 				= car.career_id
                INNER JOIN career_group			AS cag ON car.career_group_id 			= cag.career_group_id

                INNER JOIN qa_plan_career		AS qpc ON plc.plan_career_id 			= qpc.plan_career_id
                INNER JOIN qualification		AS qua ON qpc.qualification_id 			= qua.qualification_id
                INNER JOIN qualification_group	AS qug ON qua.qualification_group_id 	= qug.qualification_group_id

                INNER JOIN level				AS lev ON qpc.level_id 					= lev.level_id
                INNER JOIN target				AS tar ON qpc.target_id 				= tar.target_id

                INNER JOIN plan					AS pla ON qpc.qa_plan_career_id			= pla.qa_plan_career_id
                INNER JOIN importance			AS imp ON pla.importance_id				= imp.importance_id
                INNER JOIN development  		AS dev ON pla.development_id			= dev.development_id

                INNER JOIN self_assessment  	AS sel ON qpc.qa_plan_career_id			= sel.qa_plan_career_id
                INNER JOIN perform  			AS per ON sel.perform_id 				= per.perform_id
                WHERE mem.member_id = $member_id
    ;";
    $statement = $connect->prepare($query);
    $statement->execute();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
    }
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
}
else if ($request_data->action == "get_plan_career") {
    $member_id = (int) $request_data->member_id;
    $query = "SELECT plc.plan_career_id,car.career_id, car.career_name
                FROM member 		  			AS mem
                INNER JOIN individual 			AS ind ON mem.member_id 				= ind.member_id
                INNER JOIN disability 			AS dis ON ind.disability_id				= dis.disability_id
                INNER JOIN project 				AS pro ON ind.project_id				= pro.project_id
                INNER JOIN member 				AS adv ON ind.advisor_id				= adv.member_id

                INNER JOIN department 			AS dep ON ind.department_id 			= dep.department_id
                INNER JOIN degree 	  			AS deg ON dep.degree_id 				= deg.degree_id
                INNER JOIN faculty 	  			AS fac ON deg.faculty_id 				= fac.faculty_id
                INNER JOIN institute  			AS ins ON fac.institute_id 				= ins.institute_id

                INNER JOIN plan_career  		AS plc ON mem.member_id 				= plc.member_id
                INNER JOIN career  				AS car ON plc.career_id 				= car.career_id
                INNER JOIN career_group			AS cag ON car.career_group_id 			= cag.career_group_id

                INNER JOIN qa_plan_career		AS qpc ON plc.plan_career_id 			= qpc.plan_career_id
                INNER JOIN qualification		AS qua ON qpc.qualification_id 			= qua.qualification_id
                INNER JOIN qualification_group	AS qug ON qua.qualification_group_id 	= qug.qualification_group_id

                INNER JOIN level				AS lev ON qpc.level_id 					= lev.level_id
                INNER JOIN target				AS tar ON qpc.target_id 				= tar.target_id

                INNER JOIN plan					AS pla ON qpc.qa_plan_career_id			= pla.qa_plan_career_id
                INNER JOIN importance			AS imp ON pla.importance_id				= imp.importance_id
                INNER JOIN development  		AS dev ON pla.development_id			= dev.development_id

                INNER JOIN self_assessment  	AS sel ON qpc.qa_plan_career_id			= sel.qa_plan_career_id
                INNER JOIN perform  			AS per ON sel.perform_id 				= per.perform_id
                GROUP BY car.career_id
                ORDER BY car.career_id DESC

    ;";
    $statement = $connect->prepare($query);
    $statement->execute();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
    }
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
}

else if ($request_data->action == "get_full_name") {
    $member_id = (int) $request_data->member_id;
    $query = "SELECT  mem.member_id, mem.full_name

                FROM    self_assessment     AS sel
                INNER JOIN perform 	        AS per ON sel.perform_id = per.perform_id
                INNER JOIN qa_plan_career   AS qpc ON sel.qa_plan_career_id = qpc.qa_plan_career_id
                INNER JOIN qualification    AS qua ON qpc.qualification_id = qua.qualification_id

                INNER JOIN plan_career      AS pla ON qpc.plan_career_id =  pla.plan_career_id
                INNER JOIN member           AS mem ON pla.member_id =  mem.member_id

                INNER JOIN career           AS car ON pla.career_id = car.career_id
                INNER JOIN target 	        AS tar ON qpc.target_id = tar.target_id
                INNER JOIN level 	        AS lev ON qpc.level_id = lev.level_id

                WHERE mem.member_id = $member_id
                GROUP BY mem.member_id
                ORDER BY mem.member_id
    ;";
    $statement = $connect->prepare($query);
    $statement->execute();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
    }
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
}
else if ($request_data->action == "get_birthday") {
    $member_id = (int) $request_data->member_id;
    $query = "SELECT ind.birthday
                FROM member 		  			AS mem
                INNER JOIN individual 			AS ind ON mem.member_id 				= ind.member_id
                INNER JOIN disability 			AS dis ON ind.disability_id				= dis.disability_id
                INNER JOIN project 				AS pro ON ind.project_id				= pro.project_id
                INNER JOIN member 				AS adv ON ind.advisor_id				= adv.member_id

                INNER JOIN department 			AS dep ON ind.department_id 			= dep.department_id
                INNER JOIN degree 	  			AS deg ON dep.degree_id 				= deg.degree_id
                INNER JOIN faculty 	  			AS fac ON deg.faculty_id 				= fac.faculty_id
                INNER JOIN institute  			AS ins ON fac.institute_id 				= ins.institute_id

                INNER JOIN plan_career  		AS plc ON mem.member_id 				= plc.member_id
                INNER JOIN career  				AS car ON plc.career_id 				= car.career_id
                INNER JOIN career_group			AS cag ON car.career_group_id 			= cag.career_group_id

                INNER JOIN qa_plan_career		AS qpc ON plc.plan_career_id 			= qpc.plan_career_id
                INNER JOIN qualification		AS qua ON qpc.qualification_id 			= qua.qualification_id
                INNER JOIN qualification_group	AS qug ON qua.qualification_group_id 	= qug.qualification_group_id

                INNER JOIN level				AS lev ON qpc.level_id 					= lev.level_id
                INNER JOIN target				AS tar ON qpc.target_id 				= tar.target_id

                INNER JOIN plan					AS pla ON qpc.qa_plan_career_id			= pla.qa_plan_career_id
                INNER JOIN importance			AS imp ON pla.importance_id				= imp.importance_id
                INNER JOIN development  		AS dev ON pla.development_id			= dev.development_id

                INNER JOIN self_assessment  	AS sel ON qpc.qa_plan_career_id			= sel.qa_plan_career_id
                INNER JOIN perform  			AS per ON sel.perform_id 				= per.perform_id
                WHERE mem.member_id = $member_id
                GROUP BY ind.birthday
                ORDER BY ind.birthday DESC

    ;";
    $statement = $connect->prepare($query);
    $statement->execute();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
    }
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
}
else if ($request_data->action == "get_telephone") {
    $member_id = (int) $request_data->member_id;
    $query = "SELECT ind.telephone
                FROM member 		  			AS mem
                INNER JOIN individual 			AS ind ON mem.member_id 				= ind.member_id
                INNER JOIN disability 			AS dis ON ind.disability_id				= dis.disability_id
                INNER JOIN project 				AS pro ON ind.project_id				= pro.project_id
                INNER JOIN member 				AS adv ON ind.advisor_id				= adv.member_id

                INNER JOIN department 			AS dep ON ind.department_id 			= dep.department_id
                INNER JOIN degree 	  			AS deg ON dep.degree_id 				= deg.degree_id
                INNER JOIN faculty 	  			AS fac ON deg.faculty_id 				= fac.faculty_id
                INNER JOIN institute  			AS ins ON fac.institute_id 				= ins.institute_id

                INNER JOIN plan_career  		AS plc ON mem.member_id 				= plc.member_id
                INNER JOIN career  				AS car ON plc.career_id 				= car.career_id
                INNER JOIN career_group			AS cag ON car.career_group_id 			= cag.career_group_id

                INNER JOIN qa_plan_career		AS qpc ON plc.plan_career_id 			= qpc.plan_career_id
                INNER JOIN qualification		AS qua ON qpc.qualification_id 			= qua.qualification_id
                INNER JOIN qualification_group	AS qug ON qua.qualification_group_id 	= qug.qualification_group_id

                INNER JOIN level				AS lev ON qpc.level_id 					= lev.level_id
                INNER JOIN target				AS tar ON qpc.target_id 				= tar.target_id

                INNER JOIN plan					AS pla ON qpc.qa_plan_career_id			= pla.qa_plan_career_id
                INNER JOIN importance			AS imp ON pla.importance_id				= imp.importance_id
                INNER JOIN development  		AS dev ON pla.development_id			= dev.development_id

                INNER JOIN self_assessment  	AS sel ON qpc.qa_plan_career_id			= sel.qa_plan_career_id
                INNER JOIN perform  			AS per ON sel.perform_id 				= per.perform_id
                WHERE mem.member_id = $member_id
                GROUP BY ind.telephone
                ORDER BY ind.telephone DESC

    ;";
    $statement = $connect->prepare($query);
    $statement->execute();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
    }
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
}
else if ($request_data->action == "get_institute_name") {
    $member_id = (int) $request_data->member_id;
    $query = "SELECT ins.institute_name
                FROM member 		  			AS mem
                INNER JOIN individual 			AS ind ON mem.member_id 				= ind.member_id
                INNER JOIN disability 			AS dis ON ind.disability_id				= dis.disability_id
                INNER JOIN project 				AS pro ON ind.project_id				= pro.project_id
                INNER JOIN member 				AS adv ON ind.advisor_id				= adv.member_id

                INNER JOIN department 			AS dep ON ind.department_id 			= dep.department_id
                INNER JOIN degree 	  			AS deg ON dep.degree_id 				= deg.degree_id
                INNER JOIN faculty 	  			AS fac ON deg.faculty_id 				= fac.faculty_id
                INNER JOIN institute  			AS ins ON fac.institute_id 				= ins.institute_id

                INNER JOIN plan_career  		AS plc ON mem.member_id 				= plc.member_id
                INNER JOIN career  				AS car ON plc.career_id 				= car.career_id
                INNER JOIN career_group			AS cag ON car.career_group_id 			= cag.career_group_id

                INNER JOIN qa_plan_career		AS qpc ON plc.plan_career_id 			= qpc.plan_career_id
                INNER JOIN qualification		AS qua ON qpc.qualification_id 			= qua.qualification_id
                INNER JOIN qualification_group	AS qug ON qua.qualification_group_id 	= qug.qualification_group_id

                INNER JOIN level				AS lev ON qpc.level_id 					= lev.level_id
                INNER JOIN target				AS tar ON qpc.target_id 				= tar.target_id

                INNER JOIN plan					AS pla ON qpc.qa_plan_career_id			= pla.qa_plan_career_id
                INNER JOIN importance			AS imp ON pla.importance_id				= imp.importance_id
                INNER JOIN development  		AS dev ON pla.development_id			= dev.development_id

                INNER JOIN self_assessment  	AS sel ON qpc.qa_plan_career_id			= sel.qa_plan_career_id
                INNER JOIN perform  			AS per ON sel.perform_id 				= per.perform_id
                WHERE mem.member_id = $member_id
                GROUP BY ins.institute_name
                ORDER BY ins.institute_name DESC

    ;";
    $statement = $connect->prepare($query);
    $statement->execute();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
    }
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
}
else if ($request_data->action == "get_faculty_name") {
    $member_id = (int) $request_data->member_id;
    $query = "SELECT fac.faculty_name
                FROM member 		  			AS mem
                INNER JOIN individual 			AS ind ON mem.member_id 				= ind.member_id
                INNER JOIN disability 			AS dis ON ind.disability_id				= dis.disability_id
                INNER JOIN project 				AS pro ON ind.project_id				= pro.project_id
                INNER JOIN member 				AS adv ON ind.advisor_id				= adv.member_id

                INNER JOIN department 			AS dep ON ind.department_id 			= dep.department_id
                INNER JOIN degree 	  			AS deg ON dep.degree_id 				= deg.degree_id
                INNER JOIN faculty 	  			AS fac ON deg.faculty_id 				= fac.faculty_id
                INNER JOIN institute  			AS ins ON fac.institute_id 				= ins.institute_id

                INNER JOIN plan_career  		AS plc ON mem.member_id 				= plc.member_id
                INNER JOIN career  				AS car ON plc.career_id 				= car.career_id
                INNER JOIN career_group			AS cag ON car.career_group_id 			= cag.career_group_id

                INNER JOIN qa_plan_career		AS qpc ON plc.plan_career_id 			= qpc.plan_career_id
                INNER JOIN qualification		AS qua ON qpc.qualification_id 			= qua.qualification_id
                INNER JOIN qualification_group	AS qug ON qua.qualification_group_id 	= qug.qualification_group_id

                INNER JOIN level				AS lev ON qpc.level_id 					= lev.level_id
                INNER JOIN target				AS tar ON qpc.target_id 				= tar.target_id

                INNER JOIN plan					AS pla ON qpc.qa_plan_career_id			= pla.qa_plan_career_id
                INNER JOIN importance			AS imp ON pla.importance_id				= imp.importance_id
                INNER JOIN development  		AS dev ON pla.development_id			= dev.development_id

                INNER JOIN self_assessment  	AS sel ON qpc.qa_plan_career_id			= sel.qa_plan_career_id
                INNER JOIN perform  			AS per ON sel.perform_id 				= per.perform_id
                WHERE mem.member_id = $member_id
                GROUP BY fac.faculty_name
                ORDER BY fac.faculty_name DESC

    ;";
    $statement = $connect->prepare($query);
    $statement->execute();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
    }
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
}
else if ($request_data->action == "get_degree_name") {
    $member_id = (int) $request_data->member_id;
    $query = "SELECT deg.degree_name
                FROM member 		  			AS mem
                INNER JOIN individual 			AS ind ON mem.member_id 				= ind.member_id
                INNER JOIN disability 			AS dis ON ind.disability_id				= dis.disability_id
                INNER JOIN project 				AS pro ON ind.project_id				= pro.project_id
                INNER JOIN member 				AS adv ON ind.advisor_id				= adv.member_id

                INNER JOIN department 			AS dep ON ind.department_id 			= dep.department_id
                INNER JOIN degree 	  			AS deg ON dep.degree_id 				= deg.degree_id
                INNER JOIN faculty 	  			AS fac ON deg.faculty_id 				= fac.faculty_id
                INNER JOIN institute  			AS ins ON fac.institute_id 				= ins.institute_id

                INNER JOIN plan_career  		AS plc ON mem.member_id 				= plc.member_id
                INNER JOIN career  				AS car ON plc.career_id 				= car.career_id
                INNER JOIN career_group			AS cag ON car.career_group_id 			= cag.career_group_id

                INNER JOIN qa_plan_career		AS qpc ON plc.plan_career_id 			= qpc.plan_career_id
                INNER JOIN qualification		AS qua ON qpc.qualification_id 			= qua.qualification_id
                INNER JOIN qualification_group	AS qug ON qua.qualification_group_id 	= qug.qualification_group_id

                INNER JOIN level				AS lev ON qpc.level_id 					= lev.level_id
                INNER JOIN target				AS tar ON qpc.target_id 				= tar.target_id

                INNER JOIN plan					AS pla ON qpc.qa_plan_career_id			= pla.qa_plan_career_id
                INNER JOIN importance			AS imp ON pla.importance_id				= imp.importance_id
                INNER JOIN development  		AS dev ON pla.development_id			= dev.development_id

                INNER JOIN self_assessment  	AS sel ON qpc.qa_plan_career_id			= sel.qa_plan_career_id
                INNER JOIN perform  			AS per ON sel.perform_id 				= per.perform_id
                WHERE mem.member_id = $member_id
                GROUP BY deg.degree_name
                ORDER BY deg.degree_name DESC

    ;";
    $statement = $connect->prepare($query);
    $statement->execute();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
    }
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
}
else if ($request_data->action == "get_department_name") {
    $member_id = (int) $request_data->member_id;
    $query = "SELECT dep.department_name
                FROM member 		  			AS mem
                INNER JOIN individual 			AS ind ON mem.member_id 				= ind.member_id
                INNER JOIN disability 			AS dis ON ind.disability_id				= dis.disability_id
                INNER JOIN project 				AS pro ON ind.project_id				= pro.project_id
                INNER JOIN member 				AS adv ON ind.advisor_id				= adv.member_id

                INNER JOIN department 			AS dep ON ind.department_id 			= dep.department_id
                INNER JOIN degree 	  			AS deg ON dep.degree_id 				= deg.degree_id
                INNER JOIN faculty 	  			AS fac ON deg.faculty_id 				= fac.faculty_id
                INNER JOIN institute  			AS ins ON fac.institute_id 				= ins.institute_id

                INNER JOIN plan_career  		AS plc ON mem.member_id 				= plc.member_id
                INNER JOIN career  				AS car ON plc.career_id 				= car.career_id
                INNER JOIN career_group			AS cag ON car.career_group_id 			= cag.career_group_id

                INNER JOIN qa_plan_career		AS qpc ON plc.plan_career_id 			= qpc.plan_career_id
                INNER JOIN qualification		AS qua ON qpc.qualification_id 			= qua.qualification_id
                INNER JOIN qualification_group	AS qug ON qua.qualification_group_id 	= qug.qualification_group_id

                INNER JOIN level				AS lev ON qpc.level_id 					= lev.level_id
                INNER JOIN target				AS tar ON qpc.target_id 				= tar.target_id

                INNER JOIN plan					AS pla ON qpc.qa_plan_career_id			= pla.qa_plan_career_id
                INNER JOIN importance			AS imp ON pla.importance_id				= imp.importance_id
                INNER JOIN development  		AS dev ON pla.development_id			= dev.development_id

                INNER JOIN self_assessment  	AS sel ON qpc.qa_plan_career_id			= sel.qa_plan_career_id
                INNER JOIN perform  			AS per ON sel.perform_id 				= per.perform_id
                WHERE mem.member_id = $member_id
                GROUP BY dep.department_name
                ORDER BY dep.department_name DESC

    ;";
    $statement = $connect->prepare($query);
    $statement->execute();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
    }
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
}
else if ($request_data->action == "get_graduate_year") {
    $member_id = (int) $request_data->member_id;
    $query = "SELECT ind.date AS graduate_year
                FROM member 		  			AS mem
                INNER JOIN individual 			AS ind ON mem.member_id 				= ind.member_id
                INNER JOIN disability 			AS dis ON ind.disability_id				= dis.disability_id
                INNER JOIN project 				AS pro ON ind.project_id				= pro.project_id
                INNER JOIN member 				AS adv ON ind.advisor_id				= adv.member_id

                INNER JOIN department 			AS dep ON ind.department_id 			= dep.department_id
                INNER JOIN degree 	  			AS deg ON dep.degree_id 				= deg.degree_id
                INNER JOIN faculty 	  			AS fac ON deg.faculty_id 				= fac.faculty_id
                INNER JOIN institute  			AS ins ON fac.institute_id 				= ins.institute_id

                INNER JOIN plan_career  		AS plc ON mem.member_id 				= plc.member_id
                INNER JOIN career  				AS car ON plc.career_id 				= car.career_id
                INNER JOIN career_group			AS cag ON car.career_group_id 			= cag.career_group_id

                INNER JOIN qa_plan_career		AS qpc ON plc.plan_career_id 			= qpc.plan_career_id
                INNER JOIN qualification		AS qua ON qpc.qualification_id 			= qua.qualification_id
                INNER JOIN qualification_group	AS qug ON qua.qualification_group_id 	= qug.qualification_group_id

                INNER JOIN level				AS lev ON qpc.level_id 					= lev.level_id
                INNER JOIN target				AS tar ON qpc.target_id 				= tar.target_id

                INNER JOIN plan					AS pla ON qpc.qa_plan_career_id			= pla.qa_plan_career_id
                INNER JOIN importance			AS imp ON pla.importance_id				= imp.importance_id
                INNER JOIN development  		AS dev ON pla.development_id			= dev.development_id

                INNER JOIN self_assessment  	AS sel ON qpc.qa_plan_career_id			= sel.qa_plan_career_id
                INNER JOIN perform  			AS per ON sel.perform_id 				= per.perform_id
                WHERE mem.member_id = $member_id
                GROUP BY graduate_year
                ORDER BY graduate_year DESC

    ;";
    $statement = $connect->prepare($query);
    $statement->execute();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
    }
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
}
else if ($request_data->action == "get_study_year") {
    $member_id = (int) $request_data->member_id;
    $query = "SELECT ind.year AS study_year
                FROM member 		  			AS mem
                INNER JOIN individual 			AS ind ON mem.member_id 				= ind.member_id
                INNER JOIN disability 			AS dis ON ind.disability_id				= dis.disability_id
                INNER JOIN project 				AS pro ON ind.project_id				= pro.project_id
                INNER JOIN member 				AS adv ON ind.advisor_id				= adv.member_id

                INNER JOIN department 			AS dep ON ind.department_id 			= dep.department_id
                INNER JOIN degree 	  			AS deg ON dep.degree_id 				= deg.degree_id
                INNER JOIN faculty 	  			AS fac ON deg.faculty_id 				= fac.faculty_id
                INNER JOIN institute  			AS ins ON fac.institute_id 				= ins.institute_id

                INNER JOIN plan_career  		AS plc ON mem.member_id 				= plc.member_id
                INNER JOIN career  				AS car ON plc.career_id 				= car.career_id
                INNER JOIN career_group			AS cag ON car.career_group_id 			= cag.career_group_id

                INNER JOIN qa_plan_career		AS qpc ON plc.plan_career_id 			= qpc.plan_career_id
                INNER JOIN qualification		AS qua ON qpc.qualification_id 			= qua.qualification_id
                INNER JOIN qualification_group	AS qug ON qua.qualification_group_id 	= qug.qualification_group_id

                INNER JOIN level				AS lev ON qpc.level_id 					= lev.level_id
                INNER JOIN target				AS tar ON qpc.target_id 				= tar.target_id

                INNER JOIN plan					AS pla ON qpc.qa_plan_career_id			= pla.qa_plan_career_id
                INNER JOIN importance			AS imp ON pla.importance_id				= imp.importance_id
                INNER JOIN development  		AS dev ON pla.development_id			= dev.development_id

                INNER JOIN self_assessment  	AS sel ON qpc.qa_plan_career_id			= sel.qa_plan_career_id
                INNER JOIN perform  			AS per ON sel.perform_id 				= per.perform_id
                WHERE mem.member_id = $member_id
                GROUP BY study_year
                ORDER BY study_year DESC

    ;";
    $statement = $connect->prepare($query);
    $statement->execute();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
    }
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
}
else if ($request_data->action == "get_disability") {
    $member_id = (int) $request_data->member_id;
    $query = "SELECT dis.disability_name
                FROM member 		  			AS mem
                INNER JOIN individual 			AS ind ON mem.member_id 				= ind.member_id
                INNER JOIN disability 			AS dis ON ind.disability_id				= dis.disability_id
                INNER JOIN project 				AS pro ON ind.project_id				= pro.project_id
                INNER JOIN member 				AS adv ON ind.advisor_id				= adv.member_id

                INNER JOIN department 			AS dep ON ind.department_id 			= dep.department_id
                INNER JOIN degree 	  			AS deg ON dep.degree_id 				= deg.degree_id
                INNER JOIN faculty 	  			AS fac ON deg.faculty_id 				= fac.faculty_id
                INNER JOIN institute  			AS ins ON fac.institute_id 				= ins.institute_id

                INNER JOIN plan_career  		AS plc ON mem.member_id 				= plc.member_id
                INNER JOIN career  				AS car ON plc.career_id 				= car.career_id
                INNER JOIN career_group			AS cag ON car.career_group_id 			= cag.career_group_id

                INNER JOIN qa_plan_career		AS qpc ON plc.plan_career_id 			= qpc.plan_career_id
                INNER JOIN qualification		AS qua ON qpc.qualification_id 			= qua.qualification_id
                INNER JOIN qualification_group	AS qug ON qua.qualification_group_id 	= qug.qualification_group_id

                INNER JOIN level				AS lev ON qpc.level_id 					= lev.level_id
                INNER JOIN target				AS tar ON qpc.target_id 				= tar.target_id

                INNER JOIN plan					AS pla ON qpc.qa_plan_career_id			= pla.qa_plan_career_id
                INNER JOIN importance			AS imp ON pla.importance_id				= imp.importance_id
                INNER JOIN development  		AS dev ON pla.development_id			= dev.development_id

                INNER JOIN self_assessment  	AS sel ON qpc.qa_plan_career_id			= sel.qa_plan_career_id
                INNER JOIN perform  			AS per ON sel.perform_id 				= per.perform_id
                WHERE mem.member_id = $member_id
                GROUP BY dis.disability_name
                ORDER BY dis.disability_name DESC

    ;";
    $statement = $connect->prepare($query);
    $statement->execute();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
    }
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
}
else if ($request_data->action == "get_project_name") {
    $member_id = (int) $request_data->member_id;
    $query = "SELECT pro.project_name
                FROM member 		  			AS mem
                INNER JOIN individual 			AS ind ON mem.member_id 				= ind.member_id
                INNER JOIN disability 			AS dis ON ind.disability_id				= dis.disability_id
                INNER JOIN project 				AS pro ON ind.project_id				= pro.project_id
                INNER JOIN member 				AS adv ON ind.advisor_id				= adv.member_id

                INNER JOIN department 			AS dep ON ind.department_id 			= dep.department_id
                INNER JOIN degree 	  			AS deg ON dep.degree_id 				= deg.degree_id
                INNER JOIN faculty 	  			AS fac ON deg.faculty_id 				= fac.faculty_id
                INNER JOIN institute  			AS ins ON fac.institute_id 				= ins.institute_id

                INNER JOIN plan_career  		AS plc ON mem.member_id 				= plc.member_id
                INNER JOIN career  				AS car ON plc.career_id 				= car.career_id
                INNER JOIN career_group			AS cag ON car.career_group_id 			= cag.career_group_id

                INNER JOIN qa_plan_career		AS qpc ON plc.plan_career_id 			= qpc.plan_career_id
                INNER JOIN qualification		AS qua ON qpc.qualification_id 			= qua.qualification_id
                INNER JOIN qualification_group	AS qug ON qua.qualification_group_id 	= qug.qualification_group_id

                INNER JOIN level				AS lev ON qpc.level_id 					= lev.level_id
                INNER JOIN target				AS tar ON qpc.target_id 				= tar.target_id

                INNER JOIN plan					AS pla ON qpc.qa_plan_career_id			= pla.qa_plan_career_id
                INNER JOIN importance			AS imp ON pla.importance_id				= imp.importance_id
                INNER JOIN development  		AS dev ON pla.development_id			= dev.development_id

                INNER JOIN self_assessment  	AS sel ON qpc.qa_plan_career_id			= sel.qa_plan_career_id
                INNER JOIN perform  			AS per ON sel.perform_id 				= per.perform_id
                WHERE mem.member_id = $member_id
                GROUP BY dis.disability_name
                ORDER BY dis.disability_name DESC

    ;";
    $statement = $connect->prepare($query);
    $statement->execute();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
    }
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
}
else if ($request_data->action == "get_advisor_name") {
    $member_id = (int) $request_data->member_id;
    $query = "SELECT adv.full_name AS advisor_name
                FROM member 		  			AS mem
                INNER JOIN individual 			AS ind ON mem.member_id 				= ind.member_id
                INNER JOIN disability 			AS dis ON ind.disability_id				= dis.disability_id
                INNER JOIN project 				AS pro ON ind.project_id				= pro.project_id
                INNER JOIN member 				AS adv ON ind.advisor_id				= adv.member_id

                INNER JOIN department 			AS dep ON ind.department_id 			= dep.department_id
                INNER JOIN degree 	  			AS deg ON dep.degree_id 				= deg.degree_id
                INNER JOIN faculty 	  			AS fac ON deg.faculty_id 				= fac.faculty_id
                INNER JOIN institute  			AS ins ON fac.institute_id 				= ins.institute_id

                INNER JOIN plan_career  		AS plc ON mem.member_id 				= plc.member_id
                INNER JOIN career  				AS car ON plc.career_id 				= car.career_id
                INNER JOIN career_group			AS cag ON car.career_group_id 			= cag.career_group_id

                INNER JOIN qa_plan_career		AS qpc ON plc.plan_career_id 			= qpc.plan_career_id
                INNER JOIN qualification		AS qua ON qpc.qualification_id 			= qua.qualification_id
                INNER JOIN qualification_group	AS qug ON qua.qualification_group_id 	= qug.qualification_group_id

                INNER JOIN level				AS lev ON qpc.level_id 					= lev.level_id
                INNER JOIN target				AS tar ON qpc.target_id 				= tar.target_id

                INNER JOIN plan					AS pla ON qpc.qa_plan_career_id			= pla.qa_plan_career_id
                INNER JOIN importance			AS imp ON pla.importance_id				= imp.importance_id
                INNER JOIN development  		AS dev ON pla.development_id			= dev.development_id

                INNER JOIN self_assessment  	AS sel ON qpc.qa_plan_career_id			= sel.qa_plan_career_id
                INNER JOIN perform  			AS per ON sel.perform_id 				= per.perform_id
                WHERE mem.member_id = $member_id
                GROUP BY advisor_name
                ORDER BY advisor_name DESC

    ;";
    $statement = $connect->prepare($query);
    $statement->execute();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
    }
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
}
else if ($request_data->action == "get_career_group_name") {
    $member_id = (int) $request_data->member_id;
    $query = "SELECT cag.ca_group_name
                FROM member 		  			AS mem
                INNER JOIN individual 			AS ind ON mem.member_id 				= ind.member_id
                INNER JOIN disability 			AS dis ON ind.disability_id				= dis.disability_id
                INNER JOIN project 				AS pro ON ind.project_id				= pro.project_id
                INNER JOIN member 				AS adv ON ind.advisor_id				= adv.member_id

                INNER JOIN department 			AS dep ON ind.department_id 			= dep.department_id
                INNER JOIN degree 	  			AS deg ON dep.degree_id 				= deg.degree_id
                INNER JOIN faculty 	  			AS fac ON deg.faculty_id 				= fac.faculty_id
                INNER JOIN institute  			AS ins ON fac.institute_id 				= ins.institute_id

                INNER JOIN plan_career  		AS plc ON mem.member_id 				= plc.member_id
                INNER JOIN career  				AS car ON plc.career_id 				= car.career_id
                INNER JOIN career_group			AS cag ON car.career_group_id 			= cag.career_group_id

                INNER JOIN qa_plan_career		AS qpc ON plc.plan_career_id 			= qpc.plan_career_id
                INNER JOIN qualification		AS qua ON qpc.qualification_id 			= qua.qualification_id
                INNER JOIN qualification_group	AS qug ON qua.qualification_group_id 	= qug.qualification_group_id

                INNER JOIN level				AS lev ON qpc.level_id 					= lev.level_id
                INNER JOIN target				AS tar ON qpc.target_id 				= tar.target_id

                INNER JOIN plan					AS pla ON qpc.qa_plan_career_id			= pla.qa_plan_career_id
                INNER JOIN importance			AS imp ON pla.importance_id				= imp.importance_id
                INNER JOIN development  		AS dev ON pla.development_id			= dev.development_id

                INNER JOIN self_assessment  	AS sel ON qpc.qa_plan_career_id			= sel.qa_plan_career_id
                INNER JOIN perform  			AS per ON sel.perform_id 				= per.perform_id
                WHERE mem.member_id = $member_id
                GROUP BY ca_group_name
                ORDER BY ca_group_name DESC

    ;";
    $statement = $connect->prepare($query);
    $statement->execute();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
    }
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
}
else if ($request_data->action == "get_start_date") {
    $member_id = (int) $request_data->member_id;
    $query = "SELECT plc.start_date
                FROM member 		  			AS mem
                INNER JOIN individual 			AS ind ON mem.member_id 				= ind.member_id
                INNER JOIN disability 			AS dis ON ind.disability_id				= dis.disability_id
                INNER JOIN project 				AS pro ON ind.project_id				= pro.project_id
                INNER JOIN member 				AS adv ON ind.advisor_id				= adv.member_id

                INNER JOIN department 			AS dep ON ind.department_id 			= dep.department_id
                INNER JOIN degree 	  			AS deg ON dep.degree_id 				= deg.degree_id
                INNER JOIN faculty 	  			AS fac ON deg.faculty_id 				= fac.faculty_id
                INNER JOIN institute  			AS ins ON fac.institute_id 				= ins.institute_id

                INNER JOIN plan_career  		AS plc ON mem.member_id 				= plc.member_id
                INNER JOIN career  				AS car ON plc.career_id 				= car.career_id
                INNER JOIN career_group			AS cag ON car.career_group_id 			= cag.career_group_id

                INNER JOIN qa_plan_career		AS qpc ON plc.plan_career_id 			= qpc.plan_career_id
                INNER JOIN qualification		AS qua ON qpc.qualification_id 			= qua.qualification_id
                INNER JOIN qualification_group	AS qug ON qua.qualification_group_id 	= qug.qualification_group_id

                INNER JOIN level				AS lev ON qpc.level_id 					= lev.level_id
                INNER JOIN target				AS tar ON qpc.target_id 				= tar.target_id

                INNER JOIN plan					AS pla ON qpc.qa_plan_career_id			= pla.qa_plan_career_id
                INNER JOIN importance			AS imp ON pla.importance_id				= imp.importance_id
                INNER JOIN development  		AS dev ON pla.development_id			= dev.development_id

                INNER JOIN self_assessment  	AS sel ON qpc.qa_plan_career_id			= sel.qa_plan_career_id
                INNER JOIN perform  			AS per ON sel.perform_id 				= per.perform_id
                WHERE mem.member_id = $member_id
                GROUP BY plc.start_date
                ORDER BY plc.start_date DESC

    ;";
    $statement = $connect->prepare($query);
    $statement->execute();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
    }
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
}
else if ($request_data->action == "get_qualification_group_name") {
    $member_id = (int) $request_data->member_id;
    $query = "SELECT qug.qualification_group_name
                FROM member 		  			AS mem
                INNER JOIN individual 			AS ind ON mem.member_id 				= ind.member_id
                INNER JOIN disability 			AS dis ON ind.disability_id				= dis.disability_id
                INNER JOIN project 				AS pro ON ind.project_id				= pro.project_id
                INNER JOIN member 				AS adv ON ind.advisor_id				= adv.member_id

                INNER JOIN department 			AS dep ON ind.department_id 			= dep.department_id
                INNER JOIN degree 	  			AS deg ON dep.degree_id 				= deg.degree_id
                INNER JOIN faculty 	  			AS fac ON deg.faculty_id 				= fac.faculty_id
                INNER JOIN institute  			AS ins ON fac.institute_id 				= ins.institute_id

                INNER JOIN plan_career  		AS plc ON mem.member_id 				= plc.member_id
                INNER JOIN career  				AS car ON plc.career_id 				= car.career_id
                INNER JOIN career_group			AS cag ON car.career_group_id 			= cag.career_group_id

                INNER JOIN qa_plan_career		AS qpc ON plc.plan_career_id 			= qpc.plan_career_id
                INNER JOIN qualification		AS qua ON qpc.qualification_id 			= qua.qualification_id
                INNER JOIN qualification_group	AS qug ON qua.qualification_group_id 	= qug.qualification_group_id

                INNER JOIN level				AS lev ON qpc.level_id 					= lev.level_id
                INNER JOIN target				AS tar ON qpc.target_id 				= tar.target_id

                INNER JOIN plan					AS pla ON qpc.qa_plan_career_id			= pla.qa_plan_career_id
                INNER JOIN importance			AS imp ON pla.importance_id				= imp.importance_id
                INNER JOIN development  		AS dev ON pla.development_id			= dev.development_id

                INNER JOIN self_assessment  	AS sel ON qpc.qa_plan_career_id			= sel.qa_plan_career_id
                INNER JOIN perform  			AS per ON sel.perform_id 				= per.perform_id
                WHERE mem.member_id = $member_id
                GROUP BY qug.qualification_group_name
                ORDER BY qug.qualification_group_name DESC

    ;";
    $statement = $connect->prepare($query);
    $statement->execute();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
    }
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
}
else if ($request_data->action == "get_qualification_name") {
    $member_id = (int) $request_data->member_id;
    $query = "SELECT  qua.qualification_id,
                      qua.qualification_name

                FROM    self_assessment     AS sel
                INNER JOIN perform 	        AS per ON sel.perform_id = per.perform_id
                INNER JOIN qa_plan_career   AS qpc ON sel.qa_plan_career_id = qpc.qa_plan_career_id
                INNER JOIN qualification    AS qua ON qpc.qualification_id = qua.qualification_id

                INNER JOIN plan_career      AS pla ON qpc.plan_career_id =  pla.plan_career_id
                INNER JOIN member           AS mem ON pla.member_id =  mem.member_id

                INNER JOIN career           AS car ON pla.career_id = car.career_id
                INNER JOIN target 	        AS tar ON qpc.target_id = tar.target_id
                INNER JOIN level 	        AS lev ON qpc.level_id = lev.level_id

                WHERE mem.member_id = $member_id
                GROUP BY qua.qualification_id
                ORDER BY qua.qualification_id
    ;";
    $statement = $connect->prepare($query);
    $statement->execute();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
    }
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
}
else if ($request_data->action == "get_target_name") {
    $member_id = (int) $request_data->member_id;
    $query = "SELECT tar.target_name,tar.target_value
                FROM member 		  			AS mem
                INNER JOIN individual 			AS ind ON mem.member_id 				= ind.member_id
                INNER JOIN disability 			AS dis ON ind.disability_id				= dis.disability_id
                INNER JOIN project 				AS pro ON ind.project_id				= pro.project_id
                INNER JOIN member 				AS adv ON ind.advisor_id				= adv.member_id

                INNER JOIN department 			AS dep ON ind.department_id 			= dep.department_id
                INNER JOIN degree 	  			AS deg ON dep.degree_id 				= deg.degree_id
                INNER JOIN faculty 	  			AS fac ON deg.faculty_id 				= fac.faculty_id
                INNER JOIN institute  			AS ins ON fac.institute_id 				= ins.institute_id

                INNER JOIN plan_career  		AS plc ON mem.member_id 				= plc.member_id
                INNER JOIN career  				AS car ON plc.career_id 				= car.career_id
                INNER JOIN career_group			AS cag ON car.career_group_id 			= cag.career_group_id

                INNER JOIN qa_plan_career		AS qpc ON plc.plan_career_id 			= qpc.plan_career_id
                INNER JOIN qualification		AS qua ON qpc.qualification_id 			= qua.qualification_id
                INNER JOIN qualification_group	AS qug ON qua.qualification_group_id 	= qug.qualification_group_id

                INNER JOIN level				AS lev ON qpc.level_id 					= lev.level_id
                INNER JOIN target				AS tar ON qpc.target_id 				= tar.target_id

                INNER JOIN plan					AS pla ON qpc.qa_plan_career_id			= pla.qa_plan_career_id
                INNER JOIN importance			AS imp ON pla.importance_id				= imp.importance_id
                INNER JOIN development  		AS dev ON pla.development_id			= dev.development_id

                INNER JOIN self_assessment  	AS sel ON qpc.qa_plan_career_id			= sel.qa_plan_career_id
                INNER JOIN perform  			AS per ON sel.perform_id 				= per.perform_id
                WHERE mem.member_id = $member_id
                GROUP BY tar.target_value
                ORDER BY tar.target_value DESC

    ;";
    $statement = $connect->prepare($query);
    $statement->execute();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
    }
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
}
else if ($request_data->action == "get_level_name") {
    $member_id = (int) $request_data->member_id;
    $query = "SELECT lev.level_name
                FROM member 		  			AS mem
                INNER JOIN individual 			AS ind ON mem.member_id 				= ind.member_id
                INNER JOIN disability 			AS dis ON ind.disability_id				= dis.disability_id
                INNER JOIN project 				AS pro ON ind.project_id				= pro.project_id
                INNER JOIN member 				AS adv ON ind.advisor_id				= adv.member_id

                INNER JOIN department 			AS dep ON ind.department_id 			= dep.department_id
                INNER JOIN degree 	  			AS deg ON dep.degree_id 				= deg.degree_id
                INNER JOIN faculty 	  			AS fac ON deg.faculty_id 				= fac.faculty_id
                INNER JOIN institute  			AS ins ON fac.institute_id 				= ins.institute_id

                INNER JOIN plan_career  		AS plc ON mem.member_id 				= plc.member_id
                INNER JOIN career  				AS car ON plc.career_id 				= car.career_id
                INNER JOIN career_group			AS cag ON car.career_group_id 			= cag.career_group_id

                INNER JOIN qa_plan_career		AS qpc ON plc.plan_career_id 			= qpc.plan_career_id
                INNER JOIN qualification		AS qua ON qpc.qualification_id 			= qua.qualification_id
                INNER JOIN qualification_group	AS qug ON qua.qualification_group_id 	= qug.qualification_group_id

                INNER JOIN level				AS lev ON qpc.level_id 					= lev.level_id
                INNER JOIN target				AS tar ON qpc.target_id 				= tar.target_id

                INNER JOIN plan					AS pla ON qpc.qa_plan_career_id			= pla.qa_plan_career_id
                INNER JOIN importance			AS imp ON pla.importance_id				= imp.importance_id
                INNER JOIN development  		AS dev ON pla.development_id			= dev.development_id

                INNER JOIN self_assessment  	AS sel ON qpc.qa_plan_career_id			= sel.qa_plan_career_id
                INNER JOIN perform  			AS per ON sel.perform_id 				= per.perform_id
                WHERE mem.member_id = $member_id
                GROUP BY lev.level_name
                ORDER BY lev.level_name DESC

    ;";
    $statement = $connect->prepare($query);
    $statement->execute();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
    }
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
}
else if ($request_data->action == "get_development_name") {
    $member_id = (int) $request_data->member_id;
    $query = "SELECT dev.development_name
                FROM member 		  			AS mem
                INNER JOIN individual 			AS ind ON mem.member_id 				= ind.member_id
                INNER JOIN disability 			AS dis ON ind.disability_id				= dis.disability_id
                INNER JOIN project 				AS pro ON ind.project_id				= pro.project_id
                INNER JOIN member 				AS adv ON ind.advisor_id				= adv.member_id

                INNER JOIN department 			AS dep ON ind.department_id 			= dep.department_id
                INNER JOIN degree 	  			AS deg ON dep.degree_id 				= deg.degree_id
                INNER JOIN faculty 	  			AS fac ON deg.faculty_id 				= fac.faculty_id
                INNER JOIN institute  			AS ins ON fac.institute_id 				= ins.institute_id

                INNER JOIN plan_career  		AS plc ON mem.member_id 				= plc.member_id
                INNER JOIN career  				AS car ON plc.career_id 				= car.career_id
                INNER JOIN career_group			AS cag ON car.career_group_id 			= cag.career_group_id

                INNER JOIN qa_plan_career		AS qpc ON plc.plan_career_id 			= qpc.plan_career_id
                INNER JOIN qualification		AS qua ON qpc.qualification_id 			= qua.qualification_id
                INNER JOIN qualification_group	AS qug ON qua.qualification_group_id 	= qug.qualification_group_id

                INNER JOIN level				AS lev ON qpc.level_id 					= lev.level_id
                INNER JOIN target				AS tar ON qpc.target_id 				= tar.target_id

                INNER JOIN plan					AS pla ON qpc.qa_plan_career_id			= pla.qa_plan_career_id
                INNER JOIN importance			AS imp ON pla.importance_id				= imp.importance_id
                INNER JOIN development  		AS dev ON pla.development_id			= dev.development_id

                INNER JOIN self_assessment  	AS sel ON qpc.qa_plan_career_id			= sel.qa_plan_career_id
                INNER JOIN perform  			AS per ON sel.perform_id 				= per.perform_id
                WHERE mem.member_id = $member_id
                GROUP BY dev.development_name
                ORDER BY dev.development_name DESC

    ;";
    $statement = $connect->prepare($query);
    $statement->execute();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
    }
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
}
else if ($request_data->action == "get_plan_title") {
    $member_id = (int) $request_data->member_id;
    $query = "SELECT pla.plan_title
                FROM member 		  			AS mem
                INNER JOIN individual 			AS ind ON mem.member_id 				= ind.member_id
                INNER JOIN disability 			AS dis ON ind.disability_id				= dis.disability_id
                INNER JOIN project 				AS pro ON ind.project_id				= pro.project_id
                INNER JOIN member 				AS adv ON ind.advisor_id				= adv.member_id

                INNER JOIN department 			AS dep ON ind.department_id 			= dep.department_id
                INNER JOIN degree 	  			AS deg ON dep.degree_id 				= deg.degree_id
                INNER JOIN faculty 	  			AS fac ON deg.faculty_id 				= fac.faculty_id
                INNER JOIN institute  			AS ins ON fac.institute_id 				= ins.institute_id

                INNER JOIN plan_career  		AS plc ON mem.member_id 				= plc.member_id
                INNER JOIN career  				AS car ON plc.career_id 				= car.career_id
                INNER JOIN career_group			AS cag ON car.career_group_id 			= cag.career_group_id

                INNER JOIN qa_plan_career		AS qpc ON plc.plan_career_id 			= qpc.plan_career_id
                INNER JOIN qualification		AS qua ON qpc.qualification_id 			= qua.qualification_id
                INNER JOIN qualification_group	AS qug ON qua.qualification_group_id 	= qug.qualification_group_id

                INNER JOIN level				AS lev ON qpc.level_id 					= lev.level_id
                INNER JOIN target				AS tar ON qpc.target_id 				= tar.target_id

                INNER JOIN plan					AS pla ON qpc.qa_plan_career_id			= pla.qa_plan_career_id
                INNER JOIN importance			AS imp ON pla.importance_id				= imp.importance_id
                INNER JOIN development  		AS dev ON pla.development_id			= dev.development_id

                INNER JOIN self_assessment  	AS sel ON qpc.qa_plan_career_id			= sel.qa_plan_career_id
                INNER JOIN perform  			AS per ON sel.perform_id 				= per.perform_id
                WHERE mem.member_id = $member_id
                GROUP BY pla.plan_title
                ORDER BY pla.plan_title DESC

    ;";
    $statement = $connect->prepare($query);
    $statement->execute();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
    }
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
}
else if ($request_data->action == "get_importance_name") {
    $member_id = (int) $request_data->member_id;
    $query = "SELECT imp.importance_name
                FROM member 		  			AS mem
                INNER JOIN individual 			AS ind ON mem.member_id 				= ind.member_id
                INNER JOIN disability 			AS dis ON ind.disability_id				= dis.disability_id
                INNER JOIN project 				AS pro ON ind.project_id				= pro.project_id
                INNER JOIN member 				AS adv ON ind.advisor_id				= adv.member_id

                INNER JOIN department 			AS dep ON ind.department_id 			= dep.department_id
                INNER JOIN degree 	  			AS deg ON dep.degree_id 				= deg.degree_id
                INNER JOIN faculty 	  			AS fac ON deg.faculty_id 				= fac.faculty_id
                INNER JOIN institute  			AS ins ON fac.institute_id 				= ins.institute_id

                INNER JOIN plan_career  		AS plc ON mem.member_id 				= plc.member_id
                INNER JOIN career  				AS car ON plc.career_id 				= car.career_id
                INNER JOIN career_group			AS cag ON car.career_group_id 			= cag.career_group_id

                INNER JOIN qa_plan_career		AS qpc ON plc.plan_career_id 			= qpc.plan_career_id
                INNER JOIN qualification		AS qua ON qpc.qualification_id 			= qua.qualification_id
                INNER JOIN qualification_group	AS qug ON qua.qualification_group_id 	= qug.qualification_group_id

                INNER JOIN level				AS lev ON qpc.level_id 					= lev.level_id
                INNER JOIN target				AS tar ON qpc.target_id 				= tar.target_id

                INNER JOIN plan					AS pla ON qpc.qa_plan_career_id			= pla.qa_plan_career_id
                INNER JOIN importance			AS imp ON pla.importance_id				= imp.importance_id
                INNER JOIN development  		AS dev ON pla.development_id			= dev.development_id

                INNER JOIN self_assessment  	AS sel ON qpc.qa_plan_career_id			= sel.qa_plan_career_id
                INNER JOIN perform  			AS per ON sel.perform_id 				= per.perform_id
                WHERE mem.member_id = $member_id
                GROUP BY imp.importance_name
                ORDER BY imp.importance_name DESC

    ;";
    $statement = $connect->prepare($query);
    $statement->execute();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
    }
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
}
else if ($request_data->action == "get_plan_channel") {
    $member_id = (int) $request_data->member_id;
    $query = "SELECT pla.plan_channel
                FROM member 		  			AS mem
                INNER JOIN individual 			AS ind ON mem.member_id 				= ind.member_id
                INNER JOIN disability 			AS dis ON ind.disability_id				= dis.disability_id
                INNER JOIN project 				AS pro ON ind.project_id				= pro.project_id
                INNER JOIN member 				AS adv ON ind.advisor_id				= adv.member_id

                INNER JOIN department 			AS dep ON ind.department_id 			= dep.department_id
                INNER JOIN degree 	  			AS deg ON dep.degree_id 				= deg.degree_id
                INNER JOIN faculty 	  			AS fac ON deg.faculty_id 				= fac.faculty_id
                INNER JOIN institute  			AS ins ON fac.institute_id 				= ins.institute_id

                INNER JOIN plan_career  		AS plc ON mem.member_id 				= plc.member_id
                INNER JOIN career  				AS car ON plc.career_id 				= car.career_id
                INNER JOIN career_group			AS cag ON car.career_group_id 			= cag.career_group_id

                INNER JOIN qa_plan_career		AS qpc ON plc.plan_career_id 			= qpc.plan_career_id
                INNER JOIN qualification		AS qua ON qpc.qualification_id 			= qua.qualification_id
                INNER JOIN qualification_group	AS qug ON qua.qualification_group_id 	= qug.qualification_group_id

                INNER JOIN level				AS lev ON qpc.level_id 					= lev.level_id
                INNER JOIN target				AS tar ON qpc.target_id 				= tar.target_id

                INNER JOIN plan					AS pla ON qpc.qa_plan_career_id			= pla.qa_plan_career_id
                INNER JOIN importance			AS imp ON pla.importance_id				= imp.importance_id
                INNER JOIN development  		AS dev ON pla.development_id			= dev.development_id

                INNER JOIN self_assessment  	AS sel ON qpc.qa_plan_career_id			= sel.qa_plan_career_id
                INNER JOIN perform  			AS per ON sel.perform_id 				= per.perform_id
                WHERE mem.member_id = $member_id
                GROUP BY pla.plan_channel
                ORDER BY pla.plan_channel DESC

    ;";
    $statement = $connect->prepare($query);
    $statement->execute();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
    }
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
}
else if ($request_data->action == "get_plan_start_date") {
    $member_id = (int) $request_data->member_id;
    $query = "SELECT pla.plan_start_date
                FROM member 		  			AS mem
                INNER JOIN individual 			AS ind ON mem.member_id 				= ind.member_id
                INNER JOIN disability 			AS dis ON ind.disability_id				= dis.disability_id
                INNER JOIN project 				AS pro ON ind.project_id				= pro.project_id
                INNER JOIN member 				AS adv ON ind.advisor_id				= adv.member_id

                INNER JOIN department 			AS dep ON ind.department_id 			= dep.department_id
                INNER JOIN degree 	  			AS deg ON dep.degree_id 				= deg.degree_id
                INNER JOIN faculty 	  			AS fac ON deg.faculty_id 				= fac.faculty_id
                INNER JOIN institute  			AS ins ON fac.institute_id 				= ins.institute_id

                INNER JOIN plan_career  		AS plc ON mem.member_id 				= plc.member_id
                INNER JOIN career  				AS car ON plc.career_id 				= car.career_id
                INNER JOIN career_group			AS cag ON car.career_group_id 			= cag.career_group_id

                INNER JOIN qa_plan_career		AS qpc ON plc.plan_career_id 			= qpc.plan_career_id
                INNER JOIN qualification		AS qua ON qpc.qualification_id 			= qua.qualification_id
                INNER JOIN qualification_group	AS qug ON qua.qualification_group_id 	= qug.qualification_group_id

                INNER JOIN level				AS lev ON qpc.level_id 					= lev.level_id
                INNER JOIN target				AS tar ON qpc.target_id 				= tar.target_id

                INNER JOIN plan					AS pla ON qpc.qa_plan_career_id			= pla.qa_plan_career_id
                INNER JOIN importance			AS imp ON pla.importance_id				= imp.importance_id
                INNER JOIN development  		AS dev ON pla.development_id			= dev.development_id

                INNER JOIN self_assessment  	AS sel ON qpc.qa_plan_career_id			= sel.qa_plan_career_id
                INNER JOIN perform  			AS per ON sel.perform_id 				= per.perform_id
                WHERE mem.member_id = $member_id
                GROUP BY pla.plan_start_date
                ORDER BY pla.plan_start_date DESC

    ;";
    $statement = $connect->prepare($query);
    $statement->execute();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
    }
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
}
else if ($request_data->action == "get_plan_end_date") {
    $member_id = (int) $request_data->member_id;
    $query = "SELECT pla.plan_end_date
                FROM member 		  			AS mem
                INNER JOIN individual 			AS ind ON mem.member_id 				= ind.member_id
                INNER JOIN disability 			AS dis ON ind.disability_id				= dis.disability_id
                INNER JOIN project 				AS pro ON ind.project_id				= pro.project_id
                INNER JOIN member 				AS adv ON ind.advisor_id				= adv.member_id

                INNER JOIN department 			AS dep ON ind.department_id 			= dep.department_id
                INNER JOIN degree 	  			AS deg ON dep.degree_id 				= deg.degree_id
                INNER JOIN faculty 	  			AS fac ON deg.faculty_id 				= fac.faculty_id
                INNER JOIN institute  			AS ins ON fac.institute_id 				= ins.institute_id

                INNER JOIN plan_career  		AS plc ON mem.member_id 				= plc.member_id
                INNER JOIN career  				AS car ON plc.career_id 				= car.career_id
                INNER JOIN career_group			AS cag ON car.career_group_id 			= cag.career_group_id

                INNER JOIN qa_plan_career		AS qpc ON plc.plan_career_id 			= qpc.plan_career_id
                INNER JOIN qualification		AS qua ON qpc.qualification_id 			= qua.qualification_id
                INNER JOIN qualification_group	AS qug ON qua.qualification_group_id 	= qug.qualification_group_id

                INNER JOIN level				AS lev ON qpc.level_id 					= lev.level_id
                INNER JOIN target				AS tar ON qpc.target_id 				= tar.target_id

                INNER JOIN plan					AS pla ON qpc.qa_plan_career_id			= pla.qa_plan_career_id
                INNER JOIN importance			AS imp ON pla.importance_id				= imp.importance_id
                INNER JOIN development  		AS dev ON pla.development_id			= dev.development_id

                INNER JOIN self_assessment  	AS sel ON qpc.qa_plan_career_id			= sel.qa_plan_career_id
                INNER JOIN perform  			AS per ON sel.perform_id 				= per.perform_id
                WHERE mem.member_id = $member_id
                GROUP BY pla.plan_end_date
                ORDER BY pla.plan_end_date DESC

    ;";
    $statement = $connect->prepare($query);
    $statement->execute();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
    }
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
}
else if ($request_data->action == "get_perform_name") {
    $member_id = (int) $request_data->member_id;
    $query = "SELECT per.perform_name
                FROM member 		  			AS mem
                INNER JOIN individual 			AS ind ON mem.member_id 				= ind.member_id
                INNER JOIN disability 			AS dis ON ind.disability_id				= dis.disability_id
                INNER JOIN project 				AS pro ON ind.project_id				= pro.project_id
                INNER JOIN member 				AS adv ON ind.advisor_id				= adv.member_id

                INNER JOIN department 			AS dep ON ind.department_id 			= dep.department_id
                INNER JOIN degree 	  			AS deg ON dep.degree_id 				= deg.degree_id
                INNER JOIN faculty 	  			AS fac ON deg.faculty_id 				= fac.faculty_id
                INNER JOIN institute  			AS ins ON fac.institute_id 				= ins.institute_id

                INNER JOIN plan_career  		AS plc ON mem.member_id 				= plc.member_id
                INNER JOIN career  				AS car ON plc.career_id 				= car.career_id
                INNER JOIN career_group			AS cag ON car.career_group_id 			= cag.career_group_id

                INNER JOIN qa_plan_career		AS qpc ON plc.plan_career_id 			= qpc.plan_career_id
                INNER JOIN qualification		AS qua ON qpc.qualification_id 			= qua.qualification_id
                INNER JOIN qualification_group	AS qug ON qua.qualification_group_id 	= qug.qualification_group_id

                INNER JOIN level				AS lev ON qpc.level_id 					= lev.level_id
                INNER JOIN target				AS tar ON qpc.target_id 				= tar.target_id

                INNER JOIN plan					AS pla ON qpc.qa_plan_career_id			= pla.qa_plan_career_id
                INNER JOIN importance			AS imp ON pla.importance_id				= imp.importance_id
                INNER JOIN development  		AS dev ON pla.development_id			= dev.development_id

                INNER JOIN self_assessment  	AS sel ON qpc.qa_plan_career_id			= sel.qa_plan_career_id
                INNER JOIN perform  			AS per ON sel.perform_id 				= per.perform_id
                WHERE mem.member_id = $member_id
                GROUP BY per.perform_name
                ORDER BY per.perform_name DESC

    ;";
    $statement = $connect->prepare($query);
    $statement->execute();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
    }
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
}
else if ($request_data->action == "get_self_assessment_date") {
    $member_id = (int) $request_data->member_id;
    $query = "SELECT sel.self_assessment_date
                FROM member 		  			AS mem
                INNER JOIN individual 			AS ind ON mem.member_id 				= ind.member_id
                INNER JOIN disability 			AS dis ON ind.disability_id				= dis.disability_id
                INNER JOIN project 				AS pro ON ind.project_id				= pro.project_id
                INNER JOIN member 				AS adv ON ind.advisor_id				= adv.member_id

                INNER JOIN department 			AS dep ON ind.department_id 			= dep.department_id
                INNER JOIN degree 	  			AS deg ON dep.degree_id 				= deg.degree_id
                INNER JOIN faculty 	  			AS fac ON deg.faculty_id 				= fac.faculty_id
                INNER JOIN institute  			AS ins ON fac.institute_id 				= ins.institute_id

                INNER JOIN plan_career  		AS plc ON mem.member_id 				= plc.member_id
                INNER JOIN career  				AS car ON plc.career_id 				= car.career_id
                INNER JOIN career_group			AS cag ON car.career_group_id 			= cag.career_group_id

                INNER JOIN qa_plan_career		AS qpc ON plc.plan_career_id 			= qpc.plan_career_id
                INNER JOIN qualification		AS qua ON qpc.qualification_id 			= qua.qualification_id
                INNER JOIN qualification_group	AS qug ON qua.qualification_group_id 	= qug.qualification_group_id

                INNER JOIN level				AS lev ON qpc.level_id 					= lev.level_id
                INNER JOIN target				AS tar ON qpc.target_id 				= tar.target_id

                INNER JOIN plan					AS pla ON qpc.qa_plan_career_id			= pla.qa_plan_career_id
                INNER JOIN importance			AS imp ON pla.importance_id				= imp.importance_id
                INNER JOIN development  		AS dev ON pla.development_id			= dev.development_id

                INNER JOIN self_assessment  	AS sel ON qpc.qa_plan_career_id			= sel.qa_plan_career_id
                INNER JOIN perform  			AS per ON sel.perform_id 				= per.perform_id
                WHERE mem.member_id = $member_id
                GROUP BY sel.self_assessment_date
                ORDER BY sel.self_assessment_date DESC

    ;";
    $statement = $connect->prepare($query);
    $statement->execute();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
    }
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
}
else if ($request_data->action == "get_career") {
    $member_id = (int) $request_data->member_id;
    $query = "SELECT  car.career_id, car.career_name

                FROM    self_assessment     AS sel
                INNER JOIN perform 	        AS per ON sel.perform_id = per.perform_id
                INNER JOIN qa_plan_career   AS qpc ON sel.qa_plan_career_id = qpc.qa_plan_career_id
                INNER JOIN qualification    AS qua ON qpc.qualification_id = qua.qualification_id

                INNER JOIN plan_career      AS pla ON qpc.plan_career_id =  pla.plan_career_id
                INNER JOIN member           AS mem ON pla.member_id =  mem.member_id

                INNER JOIN career           AS car ON pla.career_id = car.career_id
                INNER JOIN target 	        AS tar ON qpc.target_id = tar.target_id
                INNER JOIN level 	        AS lev ON qpc.level_id = lev.level_id

                WHERE mem.member_id = $member_id
                GROUP BY car.career_id
                ORDER BY car.career_id
    ;";
    $statement = $connect->prepare($query);
    $statement->execute();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
    }
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
}
else if ($request_data->action == "get_filter_individual") {
    $full_name = "'".$request_data->full_name."'";
    $birthday  =  "'".$request_data->birthday."'";
    $telephone = "'".$request_data->telephone."'";

    $institute_name = "'".$request_data->institute_name."'";
    $faculty_name = "'".$request_data->faculty_name."'";
    $degree_name = "'".$request_data->degree_name."'";
    $department_name = "'".$request_data->department_name."'";
    $graduate_year = "'".$request_data->graduate_year."'";
    $study_year = "'".$request_data->study_year."'";

    $disability_name = "'".$request_data->disability_name."'";
    $project_name = "'".$request_data->project_name."'";
    $advisor_name = "'".$request_data->advisor_name."'";

    $career_group_name = "'".$request_data->career_group_name."'";
    $career = "'".$request_data->career."'";
    $start_date = "'".$request_data->start_date."'";

    $qualification_group_name = "'".$request_data->qualification_group_name."'";
    $qualification_name = "'".$request_data->qualification_name."'";
    $qa_plan_career = "'".$request_data->qa_plan_career."'";
    $target_name = "'".$request_data->target_name."'";
    $level_name = "'".$request_data->level_name."'";

    $development_name = "'".$request_data->development_name."'";
    $plan_title = "'".$request_data->plan_title."'";
    $importance_name = "'".$request_data->importance_name."'";
    $plan_channel = "'".$request_data->plan_channel."'";
    $plan_start_date = "'".$request_data->plan_start_date."'";
    $plan_end_date = "'".$request_data->plan_end_date."'";

    $perform_name = "'".$request_data->perform_name."'";
    $self_assessment_date = "'".$request_data->self_assessment_date."'";

    $query = "SELECT mem.member_id, mem.full_name,
                     ind.individual_id,ind.is_graduate,ind.year ,ind.date,ind.birthday,ind.telephone,
                     ind.is_disability,ind.disability_id,dis.disability_name,ind.dis_description,
                     ind.project_id,pro.project_name,
                     ind.advisor_id,adv.full_name AS advisor_name,

                     ind.department_id,dep.department_name,
                     deg.degree_id,deg.degree_name,
                     fac.faculty_id,fac.faculty_name,
                     ins.institute_id,ins.institute_name,

                     plc.plan_career_id,plc.start_date,plc.end_date,
                     plc.career_id,car.career_name,
                     car.career_group_id,cag.ca_group_name,

                     qpc.qa_plan_career_id,
                     qpc.qualification_id, qua.qualification_name,  qua.qualification_group_id, qug.qualification_group_name,
                     qpc.level_id,lev.level_name,lev.weigth,
                     qpc.target_id, tar.target_name,tar.target_value,

                     pla.plan_id, pla.plan_title, pla.plan_channel, pla.plan_start_date, pla.plan_end_date,
                     pla.importance_id,imp.importance_name,imp.importance_value,

                     pla.development_id, dev.development_name,
                     sel.self_assessment_id, sel.self_assessment_date,
                     sel.perform_id,per.perform_name,per.perform_value
                FROM member 		  			AS mem
                INNER JOIN individual 			AS ind ON mem.member_id 				= ind.member_id
                INNER JOIN disability 			AS dis ON ind.disability_id				= dis.disability_id
                INNER JOIN project 				AS pro ON ind.project_id				= pro.project_id
                INNER JOIN member 				AS adv ON ind.advisor_id				= adv.member_id

                INNER JOIN department 			AS dep ON ind.department_id 			= dep.department_id
                INNER JOIN degree 	  			AS deg ON dep.degree_id 				= deg.degree_id
                INNER JOIN faculty 	  			AS fac ON deg.faculty_id 				= fac.faculty_id
                INNER JOIN institute  			AS ins ON fac.institute_id 				= ins.institute_id

                INNER JOIN plan_career  		AS plc ON mem.member_id 				= plc.member_id
                INNER JOIN career  				AS car ON plc.career_id 				= car.career_id
                INNER JOIN career_group			AS cag ON car.career_group_id 			= cag.career_group_id

                INNER JOIN qa_plan_career		AS qpc ON plc.plan_career_id 			= qpc.plan_career_id
                INNER JOIN qualification		AS qua ON qpc.qualification_id 			= qua.qualification_id
                INNER JOIN qualification_group	AS qug ON qua.qualification_group_id 	= qug.qualification_group_id

                INNER JOIN level				AS lev ON qpc.level_id 					= lev.level_id
                INNER JOIN target				AS tar ON qpc.target_id 				= tar.target_id

                INNER JOIN plan					AS pla ON qpc.qa_plan_career_id			= pla.qa_plan_career_id
                INNER JOIN importance			AS imp ON pla.importance_id				= imp.importance_id
                INNER JOIN development  		AS dev ON pla.development_id			= dev.development_id

                INNER JOIN self_assessment  	AS sel ON qpc.qa_plan_career_id			= sel.qa_plan_career_id
                INNER JOIN perform  			AS per ON sel.perform_id 				= per.perform_id

                WHERE    mem.full_name  LIKE $full_name   AND ind.birthday   LIKE $birthday   AND ind.telephone  LIKE $telephone
                    AND ins.institute_name           LIKE $institute_name           AND fac.faculty_name         LIKE $faculty_name         AND deg.degree_name      LIKE $degree_name
                    AND dep.department_name          LIKE $department_name          AND ind.date                 LIKE $graduate_year        AND ind.year             LIKE $study_year
                    AND dis.disability_name          LIKE $disability_name          AND pro.project_name         LIKE $project_name         AND adv.full_name        LIKE $advisor_name
                    AND cag.ca_group_name            LIKE $career_group_name        AND car.career_name          LIKE $career               AND plc.start_date       LIKE $start_date
                    AND qug.qualification_group_name LIKE $qualification_group_name AND qua.qualification_name   LIKE $qualification_name   AND tar.target_name      LIKE $target_name
                    AND lev.level_name               LIKE $level_name
                    AND dev.development_name         LIKE $development_name         AND pla.plan_title           LIKE $plan_title           AND imp.importance_name  LIKE $importance_name
                    AND pla.plan_channel             LIKE $plan_channel             AND pla.plan_start_date      LIKE $plan_start_date      AND pla.plan_end_date    LIKE $plan_end_date
                    AND per.perform_name             LIKE $perform_name             AND sel.self_assessment_date LIKE $self_assessment_date

     ;";
    $statement = $connect->prepare($query);
    $statement->execute();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
    }
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
}
else if ($request_data->action == "get_qa_plan_career_by_plan_career_id") {
    $plan_career_id = (int) $request_data->plan_career_id;
    $query = "SELECT * FROM qa_plan_career as qpc
                INNER JOIN plan_career   as pla ON qpc.plan_career_id = pla.plan_career_id
                INNER JOIN career        as car ON pla.career_id = car.career_id
                INNER JOIN qualification as qua ON qpc.qualification_id = qua.qualification_id
                INNER JOIN level         as lev ON qpc.level_id = lev.level_id
                INNER JOIN target        as tar ON qpc.target_id = tar.target_id
                WHERE  qpc.plan_career_id = $plan_career_id
                GROUP BY qua.qualification_id
    ;";
    $statement = $connect->prepare($query);
    $statement->execute();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
    }
    echo json_encode($data, JSON_UNESCAPED_UNICODE);

}
else if ($request_data->action == "get_qa_plan_career_by_qualification_id") {
    $plan_career_id = (int) $request_data->plan_career_id;
    $qualification_id = (int) $request_data->qualification_id;
    $query = "SELECT * FROM qa_plan_career as qpc
                INNER JOIN plan_career   as pla ON qpc.plan_career_id = pla.plan_career_id
                INNER JOIN career        as car ON pla.career_id = car.career_id
                INNER JOIN qualification as qua ON qpc.qualification_id = qua.qualification_id
                INNER JOIN level         as lev ON qpc.level_id = lev.level_id
                INNER JOIN target        as tar ON qpc.target_id = tar.target_id
                WHERE  (qpc.plan_career_id = $plan_career_id) AND (qpc.qualification_id = $qualification_id)
                GROUP BY qua.qualification_id
    ;";
    $statement = $connect->prepare($query);
    $statement->execute();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
    }
    echo json_encode($data, JSON_UNESCAPED_UNICODE);

}
else if ($request_data->action == "get_year") {
    $member_id = (int) $request_data->member_id;
    $query = "SELECT  DATE_FORMAT(sel.self_assessment_date,'%Y') AS Year
                FROM    self_assessment     AS sel
                INNER JOIN perform 	        AS per ON sel.perform_id = per.perform_id
                INNER JOIN qa_plan_career   AS qpc ON sel.qa_plan_career_id = qpc.qa_plan_career_id
                INNER JOIN qualification    AS qua ON qpc.qualification_id = qua.qualification_id

                INNER JOIN plan_career      AS pla ON qpc.plan_career_id =  pla.plan_career_id
                INNER JOIN member           AS mem ON pla.member_id =  mem.member_id

                INNER JOIN career           AS car ON pla.career_id = car.career_id
                INNER JOIN target 	        AS tar ON qpc.target_id = tar.target_id
                INNER JOIN level 	        AS lev ON qpc.level_id = lev.level_id

                WHERE mem.member_id = $member_id
                GROUP BY Year
                ORDER BY Year
    ;";
    $statement = $connect->prepare($query);
    $statement->execute();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
    }
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
}
else if ($request_data->action == "get_month") {
    $member_id = (int) $request_data->member_id;
    $query = "SELECT DATE_FORMAT(sel.self_assessment_date,'%m') AS M,
                     DATE_FORMAT(sel.self_assessment_date,'%M') AS Month
                     FROM    self_assessment     AS sel
                     INNER JOIN perform 	     AS per ON sel.perform_id = per.perform_id
                     INNER JOIN qa_plan_career   AS qpc ON sel.qa_plan_career_id = qpc.qa_plan_career_id
                     INNER JOIN qualification    AS qua ON qpc.qualification_id = qua.qualification_id

                     INNER JOIN plan_career      AS pla ON qpc.plan_career_id =  pla.plan_career_id
                     INNER JOIN member           AS mem ON pla.member_id =  mem.member_id

                     INNER JOIN career           AS car ON pla.career_id = car.career_id
                     INNER JOIN target 	         AS tar ON qpc.target_id = tar.target_id
                     INNER JOIN level 	         AS lev ON qpc.level_id = lev.level_id

                     WHERE mem.member_id = $member_id
                     GROUP BY M
                     ORDER BY M
    ;";
    $statement = $connect->prepare($query);
    $statement->execute();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
    }
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
}

else if ($request_data->action == "get_filter_year") {
    $member_id          = (int) $request_data->member_id;
    $full_name          = "'".$request_data->full_name."'";
    $career_name        = "'".$request_data->career_name."'";
    $qualification_name = "'".$request_data->qualification_name."'";
    $year               = "'".$request_data->year."'";

    $query = "
    SELECT sub.member_id, sub.Year,sub.M,sub.Month,sub.full_name,
    car.career_id, car.career_name,sub.qualification_name,
    lev.level_name,
    tar.target_name, tar.target_value,
    sel_1.self_assessment_date AS min_day,
    per_1.perform_value AS min_perform_value,
    sel_2.self_assessment_date AS max_day,
    per_2.perform_value AS max_perform_value
    FROM ( SELECT                mem.member_id, mem.full_name, pla.career_id, qua.qualification_id, 		              qua.qualification_name,
                DATE_FORMAT(sel.self_assessment_date,'%Y') 	   AS Year,
                DATE_FORMAT(sel.self_assessment_date,'%m') 	   AS M,
                DATE_FORMAT(sel.self_assessment_date,'%M') 	   AS Month,
                min(sel.self_assessment_date)  AS minDay,
                max(sel.self_assessment_date)  AS maxDay,
                sel.perform_id,
                qpc.target_id, qpc.level_id

                FROM    self_assessment    	AS sel
                INNER JOIN qa_plan_career  	AS qpc ON sel.qa_plan_career_id = qpc.qa_plan_career_id
                INNER JOIN qualification   	AS qua ON qpc.qualification_id = qua.qualification_id

                INNER JOIN plan_career     	AS pla ON qpc.plan_career_id =  pla.plan_career_id
                INNER JOIN member          	AS mem ON pla.member_id =  mem.member_id
                                        WHERE    mem.member_id              =  $member_id              AND
                                                 DATE_FORMAT(sel.self_assessment_date,'%Y')  LIKE $year AND
                                                 mem.full_name             LIKE $full_name     AND
                                                 qua.qualification_name    LIKE $qualification_name

                GROUP BY mem.member_id, pla.career_id, qpc.qualification_id, Year
                ORDER BY `maxDay` DESC) AS sub

            INNER JOIN self_assessment AS sel_1 ON sel_1.self_assessment_date = sub.minDay
            INNER JOIN self_assessment AS sel_2 ON sel_2.self_assessment_date = sub.maxDay

            INNER JOIN career      AS car ON sub.career_id 	= car.career_id
            INNER JOIN target 	   AS tar ON sub.target_id 	= tar.target_id
            INNER JOIN level 	   AS lev ON sub.level_id 	= lev.level_id
            INNER JOIN perform 	   AS per_1 ON sel_1.perform_id = per_1.perform_id
            INNER JOIN perform 	   AS per_2 ON sel_2.perform_id = per_2.perform_id
                                WHERE car.career_name           LIKE $career_name
            GROUP BY sub.member_id, sub.Year , sub.career_id, sub.qualification_id
            ORDER BY sub.member_id, sub.career_id, sub.qualification_id, sub.Year
    ;";
    $statement = $connect->prepare($query);
    $statement->execute();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
    }
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
}
else if ($request_data->action == "get_filter_month") {
    $full_name          = "'".$request_data->full_name."'";
    $career_name        = "'".$request_data->career_name."'";
    $qualification_name = "'".$request_data->qualification_name."'";
    $year               = "'".$request_data->year."'" ;
    $month              = "'".$request_data->month."'";

    $query = "
                SELECT sub.member_id, sub.full_name,
                car.career_name,sub.qualification_name,
                lev.level_name,
                sub.maxDay,sub.Year,sub.M,sub.Month,
                tar.target_value, per_2.perform_value AS max_perform_value
                    FROM ( SELECT
                                    mem.member_id, mem.full_name, pla.career_id, qua.qualification_id, qua.qualification_name,
                                    max(sel.self_assessment_date) 	AS maxDay,
                                    max(DATE_FORMAT(sel.self_assessment_date,'%e')) 	AS max1Day,
                                    DATE_FORMAT(sel.self_assessment_date,'%m') 		AS M,
                                    DATE_FORMAT(sel.self_assessment_date,'%M') 		AS Month,
                                    DATE_FORMAT(sel.self_assessment_date,'%Y') 		AS Year,
                                    sel.perform_id,
                                    qpc.target_id, qpc.level_id

                                FROM    self_assessment    	AS sel
                                INNER JOIN qa_plan_career  	AS qpc ON sel.qa_plan_career_id = qpc.qa_plan_career_id
                                INNER JOIN qualification   	AS qua ON qpc.qualification_id = qua.qualification_id

                                INNER JOIN plan_career     	AS pla ON qpc.plan_career_id =  pla.plan_career_id
                                INNER JOIN member          	AS mem ON pla.member_id =  mem.member_id

                                WHERE
                                    DATE_FORMAT(sel.self_assessment_date,'%Y') LIKE   $year               AND
                                    DATE_FORMAT(sel.self_assessment_date,'%M') LIKE   $month              AND
                                    mem.full_name                              LIKE   $full_name          AND
                                    qua.qualification_name                     LIKE   $qualification_name

                                GROUP BY mem.member_id, Year, M, pla.career_id, qpc.qualification_id
                                ORDER BY mem.member_id, pla.career_id, qpc.qualification_id, Year, M ) AS sub

                    INNER JOIN self_assessment AS sel_2 ON sel_2.self_assessment_date = sub.maxDay

                    INNER JOIN career      AS car ON sub.career_id 	= car.career_id
                    INNER JOIN target 	   AS tar ON sub.target_id 	= tar.target_id
                    INNER JOIN level 	   AS lev ON sub.level_id 	= lev.level_id
                    INNER JOIN perform 	   AS per_2 ON sel_2.perform_id = per_2.perform_id

                    WHERE car.career_name  LIKE   $career_name

                    GROUP BY sub.member_id, sub.Year, sub.M, car.career_id, sub.qualification_id
                    ORDER BY sub.member_id, car.career_id, sub.qualification_id, sub.Year, sub.M, sub.qualification_id
        ;";

    $statement = $connect->prepare($query);
    $statement->execute();
    while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
        $data[] = $row;
    }
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
}
?>
