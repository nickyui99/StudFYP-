<?php
require_once 'Database.php';
require_once '../ClassModel/AssignedEvaluationModel.php';
require_once '../ClassModel/ProjectlogbookModel.php';
require_once '../ClassModel/EvaluationRubricModel.php';
require_once '../ClassModel/EvaluationReportModel.php';
require_once '../ClassModel/EvaluationResultModel.php';

class LecturerDataService
{

    function getAssignedEvaluation($query, $id)
    {

        $db = new Database();

        //Create connection
        $connection = $db->getConnection();

        $sql_query = "SELECT * FROM assigned_lecturer_evaluator " .
            "INNER JOIN fyp_project " .
            "ON assigned_lecturer_evaluator.stud_id = fyp_project.stud_id " .
            "INNER JOIN lecturer ON lecturer.lect_id = assigned_lecturer_evaluator.lect_id " .
            "WHERE assigned_lecturer_evaluator.lect_id = '" . $id . "' AND " .
            "fyp_project.stud_id LIKE '%" . $query . "%'";

        //Run SQL Query
        $result = $connection->query($sql_query);

        if ($result->num_rows == 0) {
            return null;
        } else {
            $i = 0;
            $assigned_ev_array = array();
            while ($row = $result->fetch_assoc()) {
                //Retrieve data
                $assigned_ev = new AssignedEvaluation();
                $assigned_ev->setProjectID($row['fyp_proj_id']);
                $assigned_ev->setStudentID($row['stud_id']);
                $assigned_ev->setStudentName($row['lect_name']);
                $assigned_ev->setFypLevel($row['proj_fyp_stage']);
                $assigned_ev->setFypProgress($row['fyp_proj_progress']);
                $assigned_ev->setEvaluation1($row['document_submission_1']);
                $assigned_ev->setEvaluation2($row['document_submission_2']);
                $assigned_ev->setEvaluation3($row['document_submission_3']);

                //Add to array
                $assigned_ev_array[$i] = $assigned_ev;
                $i++;
            }

            //Close connection
            $connection->close();

            //Set output
            $output = "";
            foreach ($assigned_ev_array as $assigned_ev) {
                $output = $output . '<tr>' .
                    '<td>' . $assigned_ev->getProjectID() . '</td>' .
                    '<td>' . $assigned_ev->getStudentID() . '</td>' .
                    '<td>' . $assigned_ev->getStudentName() . '</td>' .
                    '<td>' . $assigned_ev->getFypLevel() . '</td>' .
                    '<td>' . $assigned_ev->getFypProgress() . '</td>' .
                    '<td><a href="evaluate_fyp.php?projID=' . $assigned_ev->getProjectID() . '&studID=' . $assigned_ev->getStudentID() . '&submission=1"><button type="button" class="btn btn-light btn-outline-dark btn-sm">1</button></a> ' .
                    '<a href="evaluate_fyp.php?projID=' . $assigned_ev->getProjectID() . '&studID=' . $assigned_ev->getStudentID() . '&submission=2"><button type="button" class="btn btn-light btn-outline-dark btn-sm">2</button></a> ' .
                    '<a href="evaluate_fyp.php?projID=' . $assigned_ev->getProjectID() . '&studID=' . $assigned_ev->getStudentID() . '&submission=3"><button type="button" class="btn btn-light btn-outline-dark btn-sm">3</button></a>' .
                    '</td>' .
                    '</tr>';
            }
            return $output;
        }
    }

    function getEvaluationDetails($proj_id, $stud_id, $submission)
    {

        $db = new Database();

        //Create connection
        $connection = $db->getConnection();

        $sql_query = "SELECT * FROM fyp_project " .
            "WHERE fyp_proj_id = '" . $proj_id . "' AND " .
            "stud_id = '" . $stud_id . "'";

        //Run SQL Query
        $result = $connection->query($sql_query);

        $evaluateFypModel = new EvaluateFyp();
        if ($result->num_rows == 0) {
            //Do nothing
        } else {
            $row = $result->fetch_assoc();

            $evaluateFypModel->setFypLevel($row['proj_fyp_stage']);
            $evaluateFypModel->setProjTitle($row['proj_title']);
            $evaluateFypModel->setProjMark('');
            $evaluateFypModel->setProjFeedback('');
            $evaluateFypModel->setProjQR(base64_encode($row['fyp_qrcode']));
        }

        //Close connection
        $connection->close();

        return $evaluateFypModel;
    }

    function getProjectQr($project_id)
    {
        echo $project_id;
        $db = new Database();

        //Create connection
        $connection = $db->getConnection();

        $sql_query = "SELECT fyp_proj_id, fyp_qrcode FROM fyp_project WHERE fyp_proj_id = '" . $project_id . "'";

        //Run SQL Query
        $result = $connection->query($sql_query);

        if ($result->num_rows == 0) {
            //Do nothing
            echo "Download error";
        } else {
            while ($row = $result->fetch_assoc()) {
                $image = $row['fyp_qrcode'];
            }

            header('Expires: 0');
            header('Pragma: no-cache');
            header('Content-Disposition: attachment; filename=ProjectQR_' . $project_id . '.png');
            header('Content-length: ' . strlen($image));
            header('Content-type: image/png');
            ob_clean();
            flush();
            echo $image;
        }

        //Close connection
        $connection->close();
    }

    function getProjectDoc($project_id, $submission)
    {
        $db = new Database();

        //Create connection
        $connection = $db->getConnection();


        $sql_query = "SELECT fyp_proj_id, document_submission_" . $submission . " FROM fyp_project WHERE fyp_proj_id = '" . $project_id . "'";

        //Run SQL Query
        $result = $connection->query($sql_query);

        if ($result->num_rows == 0) {
            //Do nothing
            echo "Download error";
        } else {
            while ($row = $result->fetch_assoc()) {
                $stud_id = $row['stud_id'];
                $document = $row['document_submission_' . $submission];
            }

            header('Expires: 0');
            header('Pragma: no-cache');
            header('Content-Disposition: attachment; filename=' . $stud_id . '_Submission' . $submission . '.pdf');
            header('Content-length: ' . strlen($document));
            header('Content-type: application/pdf');
            ob_clean();
            flush();
            echo $document;
        }

        //Close connection
        $connection->close();
    }

    function getProjectLog($proj_id, $submission)
    {
        $db = new Database();

        //Create connection
        $connection = $db->getConnection();

        $sql_query = "SELECT * FROM project_logbook " .
            "WHERE fyp_proj_id ='" . $proj_id . "' AND " .
            "submission = '" . $submission . "'";

        //Run SQL Query
        $result = $connection->query($sql_query);

        $project_log_array = array();

        if ($result->num_rows == 0) {
            //Do nothing
        } else {
            $i = 0;
            while ($row = $result->fetch_assoc()) {
                $project_log_model = new ProjectLogbook();
                $project_log_model->setDate($row['logbook_date']);
                $project_log_model->setActivity($row['logbook_details']);

                //Add to array
                $project_log_array[$i] = $project_log_model;
                $i++;
            }
        }

        //Close connection
        $connection->close();

        return $project_log_array;
    }

    function getEvaluationRubric($submission, $fyp_level)
    {
        $db = new Database();

        //Create connection
        $connection = $db->getConnection();

        $sql_query = "SELECT * FROM evaluation_rubric " .
            "WHERE rubric_submission ='" . $submission . "' AND " .
            "rubric_fyp = '" . $fyp_level . "' " .
            "ORDER BY `evaluation_rubric`.`rubric_num` ASC";

        //Run SQL Query
        $result = $connection->query($sql_query);

        $evaluation_rubric_array = array();

        if ($result->num_rows == 0) {
            //Do nothing
        } else {
            $i = 0;
            while ($row = $result->fetch_assoc()) {
                $ev_rubric_model = new EvaluationRubric();
                $ev_rubric_model->setRubricId($row['evaluation_rubric_id']);
                $ev_rubric_model->setRubricNum($row['rubric_num']);
                $ev_rubric_model->setRubricTitle($row['rubric_title']);
                $ev_rubric_model->setRubricDetails($row['rubric_details']);
                $ev_rubric_model->setRubricMark($row['rubric_mark']);
                $ev_rubric_model->setRubricWeightage($row['rubric_weightage']);

                //Add to array
                $evaluation_rubric_array[$i] = $ev_rubric_model;
                $i++;
            }
        }
        //Close connection
        $connection->close();

        return $evaluation_rubric_array;
    }

    function getEvaluationReport($query, $id)
    {
        $db = new Database();

        //Create connection
        $connection = $db->getConnection();

        $sql_query = "SELECT evaluation_result.result_id, evaluation_result.fyp_proj_id, evaluation_result.assigned_lect_id, fyp_project.proj_title, evaluation_result.submission_level, 
            evaluation_result.evaluation_feedback, evaluation_result.evaluation_mark, assigned_lecturer_evaluator.lect_id, fyp_project.proj_fyp_stage, fyp_project.stud_id, evaluation_result.evaluation_date
            FROM evaluation_result INNER JOIN assigned_lecturer_evaluator 
            ON assigned_lecturer_evaluator.assigned_lect_id = evaluation_result.assigned_lect_id 
            INNER JOIN fyp_project 
            ON evaluation_result.fyp_proj_id = fyp_project.fyp_proj_id
            WHERE assigned_lecturer_evaluator.lect_id = '$id' AND 
            (evaluation_result.fyp_proj_id LIKE '%$query%' OR 
            fyp_project.proj_title LIKE '%$query%' OR 
            assigned_lecturer_evaluator.stud_id LIKE '%$query%')
            ORDER BY evaluation_result.fyp_proj_id ASC";

        //Run SQL Query
        $result = $connection->query($sql_query);

        $evaluation_report_array = array();

        if ($result->num_rows == 0) {
            //Do nothing
        } else {
            $i = 0;
            while ($row = $result->fetch_assoc()) {
                $ev_report = new EvaluationReport();

                $ev_report->setResultId($row['result_id']);
                $ev_report->setProjID($row['fyp_proj_id']);
                $ev_report->setFypStage($row['proj_fyp_stage']);
                $ev_report->setSubmission($row['submission_level']);
                $ev_report->setMark($row['evaluation_mark']);
                $ev_report->setProjTitle($row['proj_title']);
                $ev_report->setEvaluationDate($row['evaluation_date']);
                $ev_report->setStudID($row['stud_id']);

                //Add to array
                $evaluation_report_array[$i] = $ev_report;
                $i++;
            }
        }
        //Close connection
        $connection->close();

        return $evaluation_report_array;
    }

    function getAssignedLectId($lect_id)
    {

        $db = new Database();

        //Create connection
        $connection = $db->getConnection();

        $sql_query = "SELECT * FROM assigned_lecturer_evaluator WHERE lect_id = '$lect_id'";

        //Run SQL Query
        $result = $connection->query($sql_query);

        if ($result->num_rows == 0) {
            return null;
        } else {
            $row = $result->fetch_assoc();
            return $row['assigned_lect_id'];
        }
    }

    function insertEvaluationResult($ev_result, $ev_id, $stud_id)
    {
        $db = new Database();

        //Create connection
        $connection = $db->getConnection();

        $sql_query = "SELECT * FROM evaluation_result " .
            "ORDER BY `result_id` DESC";

        //Run SQL Query
        $result = $connection->query($sql_query);

        if ($result->num_rows == 0) {
            //Do nothing
        } else {
            $row = $result->fetch_assoc();
            $last_id = $row['result_id'];

            //check last id number
            $rubric_num = preg_replace('/[^0-9]/', '', $last_id);

            $new_rubric_num = (int)($rubric_num) + 1;
            $new_rubric_id = "ER" . sprintf("%03d", $new_rubric_num);
            $sql_query = "INSERT INTO evaluation_result 
            VALUES ('" . $new_rubric_id . "', '" . $ev_result->getProjID() . "', '" . $ev_id . "', 
            'NULL', '" . $ev_result->getSubmission() . "', '" . $ev_result->getProjectFeedback() . "', 
            '" . $ev_result->getEvaluationMark() . "', '" . $ev_result->getEvaluationDate() . "')";

            if ($connection->query($sql_query) === TRUE) {
                echo "New record created successfully";
            } else {
                echo "Error: " . $sql_query . "<br>" . $connection->error;
            }

            $connection->close();
        }
    }


    function deleteEvaluationReport($er_id)
    {
        $db = new Database();

        //Create connection
        $connection = $db->getConnection();

        $sql_query = "DELETE FROM evaluation_result WHERE result_id = '$er_id'";

        if ($connection->query($sql_query) === TRUE) {
            echo "Evaluation report deleted successfully";
        } else {
            echo "Error deleting evaluation report: " . $connection->error;
        }

        $connection->close();
    }
}
