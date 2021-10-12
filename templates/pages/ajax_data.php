<?php

$subsystem = $_SESSION['subsystem'];
$user_permissions = $_SESSION['user_permissions'];

if (Input::exists() && Input::get("user_email_action") == "send_user_email") {
    $subject = trim(Input::get('subject'));
    $cc = trim(Input::get("cc"), '"');
    $bcc = trim(Input::get("bcc"), '"');
    $receiver_email = trim(Input::get("receiver_email"), '"');
    $message = trim(Input::get("message"), '"');
    $result = sendEmail($receiver_email, '', $subject, $message, array($cc), true);
    if ($result == 'success') {
        $response = json_encode(array('title' => "Message Sent", 'body' => 'Email Sent to ' . $receiver_email, 'type' => 'success'));
    } else {
        $response = json_encode(array('title' => "Error in Sending Message", 'body' => 'Email could not be sent to your address', 'type' => 'danger'));
    }
    echo $response;
}

if (isset($_POST["chatAction"]) && Input::get("chatAction") == "loadOthersUsers") {
    $user_id = Input::get("user_id");
    $users = DB::getInstance()->querySample("SELECT CONCAT(fname,' ',lname) name,user_id id,(CASE WHEN photo IS NOT NULL THEN photo ELSE '$default_avator' END) photo,'' last_message,'' last_sent FROM user u  WHERE u.status=1 AND u.user_id!='$user_id'");
    echo json_encode($users);
}
if (isset($_POST['chatAction']) && Input::get("chatAction") == "loadSingleUserChat") {
    $user_id = Input::get("user_id");
    $other_user = Input::get("other_user");
    $chats = DB::getInstance()->querySample("SELECT * FROM chat WHERE status=1 AND ((message_from='$user_id' AND message_to='$other_user') OR (message_to='$user_id' AND message_from='$other_user'))");
    echo json_encode($chats);
}
if (isset($_POST['chatAction']) && Input::get("chatAction") == "sendUserChatMessage") {
    $message_from = Input::get("message_from");
    $message_to = Input::get("message_to");
    $message = Input::get("message");
    $id = DB::getInstance()->insert("chat", ["message_from" => $message_from, "message_to" => $message_to, "message" => $message]);

    if ($id) {
        $title = COMPANY_NAME;
        $sender = DB::getInstance()->getRow("user", $message_from, "*", "user_id");
        $receiver = DB::getInstance()->getRow("user", $message_to, "*", "user_id");
        $sender_photo = ($sender->photo && file_exists($sender->photo)) ? $sender->photo : $default_avator;
        if ($receiver->messaging_token != "" && $receiver->messaging_token != $sender->messaging_token) {
            $receiverToken = $receiver->messaging_token;
            $headers = array(
                'Authorization: key=' . MESSAGING_API_KEY,
                'Content-Type: application/json'
            );
            $fields = array(
                'to' => $receiverToken,
                'notification' => array('id' => $id, 'sender_id' => $message_from, 'title' => $title, 'body' => $message, 'icon' => COMPANY_LOGO, 'image' => $sender_photo, 'time' => date('Y-m-d h:i:s a')),
                'data' => array('id' => $id, 'sender_id' => $message_from, 'title' => $title, 'body' => $message, 'icon' => COMPANY_LOGO, 'image' => $sender_photo, 'time' => date('Y-m-d h:i:s a')),
                'priority' => 'high'
            );
            $payload = json_encode($fields);
            $curl_session = curl_init();
            curl_setopt($curl_session, CURLOPT_URL, FCM_PATH);
            curl_setopt($curl_session, CURLOPT_POST, true);
            curl_setopt($curl_session, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($curl_session, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl_session, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl_session, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
            curl_setopt($curl_session, CURLOPT_POSTFIELDS, $payload);

            $result = curl_exec($curl_session);
            $err = curl_error($curl_session);
            if ($err) {
                //var_dump($err);
            }
            if ($result) {
                //var_dump($result);
            }
            curl_close($curl_session);
        }
    }

    echo json_encode(array('message' => 'Sent', 'id' => $id));
}
if (isset($_POST['chatAction']) && Input::get("chatAction") == "saveUserChatToken") {
    $token = Input::get("token");
    $user_id = Input::get("user_id");
    DB::getInstance()->update("user", $user_id, ["messaging_token" => $token], 'user_id');
    echo json_encode(array('message' => 'Success'));
}
if (isset($_POST['addEmployeeMeetingSchedule'])) {
    $ids = Input::get("ids");
    $month = Input::get("month");
    $date = Input::get("date");
    $submitted = 0;
    if ($ids) {
        foreach ($ids as $reporting_id) {
            if (!DB::getInstance()->checkRows("SELECT * FROM employee_schedule WHERE reporting_id='$reporting_id' AND schedule_date='$date'")) {
                $id = DB::getInstance()->insert("employee_schedule", array(
                    "reporting_id" => $reporting_id,
                    "month" => $month,
                    "schedule_date" => $date
                ));
                if ($id) {
                    $submitted++;
                }
            }
        }
    }
    if ($submitted > 0) {
        echo $response = json_encode(array('title' => "Success", 'body' => 'Meeting successfully scheduled for ' . $submitted . ' employees', 'type' => 'success'));
    } else {
        echo json_encode(array('title' => "Error", 'body' => 'Could not submit an empty form', 'type' => 'error'));
    }
}
if (isset($_GET["loadCalendarEvents"])) {
    $start = $_GET['start'];
    $end = $_GET['end'];
    $events = array();

    $tasks = DB::getInstance()->querySample("SELECT a.*,t.*,p.name FROM tasks t LEFT JOIN user_project_assignment a ON(t.project_assignment_id=a.id AND a.status=1) LEFT JOIN projects p ON(a.project_id=p.id AND p.status=1) WHERE t.user_id='$user_id' AND t.is_completed!=1 AND t.expected_start_time>='$start' AND t.expected_start_time<='$end' AND t.status=1");
    foreach ($tasks as $task) {
        $form = '<br/><form action="" id="taskResponseForm" method="POST" onsubmit="event.preventDefault();toggleTaskStatus(\'' . $task->id . '\', \'user_response\');">
        <div class="form-group"><textarea class="form-control" id="updates" name="" placeholder="Updates"></textarea></div>
        <div class="form-group"><textarea class="form-control" id="challenges" name="" placeholder="Challenges"></textarea></div>
        <div class="form-group"><textarea class="form-control" id="remarks" name="" placeholder="Remarks"></textarea></div>
            <label><input type="checkbox" name="mark_complete" value="1" id="mark_complete"> Mark as Complete?</label>
            <button class="btn btn-success btn-xs">Submit</button>
        </form>';
        $events[] = array(
            'id' => $task->id,
            'title' => $task->content,
            'description' => 'Task: ' . $task->content . ', Project: ' . $task->name,
            'start' => ($task->expected_start_time) ? $task->expected_start_time : $task->assignment_start_date,
            'end' => ($task->expected_end_time) ? $task->expected_end_time : $task->assignment_end_date,
            'editable' => ($task->expected_start_time) ? TRUE : FALSE,
            'is_task' => TRUE,
            'response_form' => $form
        );
    }
    $allBirthdays = DB::getInstance()->querySample("SELECT CONCAT(fname,' ',lname) name,dob FROM user WHERE status=1 AND dob IS NOT NULL");
    foreach ($allBirthdays as $list) {
        $events[] = array(
            'title' => 'Birthday: ' . $list->name,
            'description' => $list->name,
            'start' => date('Y') . '-' . substr($list->dob, 5),
            'allDay' => TRUE,
            'editable' => FALSE,
            'is_task' => FALSE,
            "response_form" => NULL
        );
    }
    $leaveFormsQuery = "SELECT CONCAT(fname,' ',lname)name,l.* FROM leave_form l,user u WHERE l.submitted_by=u.user_id AND l.is_approved=1 AND l.status=1 AND ((l.leave_start_date>='$start' AND l.leave_start_date<='$end')OR (l.leave_end_date>='$start' AND l.leave_end_date<='$end')) ORDER BY l.form_date DESC";
    $approvedLeaves = DB::getInstance()->querySample($leaveFormsQuery);
    foreach ($approvedLeaves as $list) {
        $events[] = array(
            'id' => $list->id,
            'title' => 'Leave: ' . $list->name,
            'description' => $list->reason_for_leave,
            'start' => $list->leave_start_date,
            'end' => $list->leave_end_date,
            'editable' => FALSE,
            'is_task' => FALSE,
            "response_form" => NULL
        );
    }
    $schedulesQuery = "SELECT er.reports_to,s.id,(CASE WHEN er.user_id='$user_id' THEN (SELECT CONCAT(fname,' ',lname) FROM user u WHERE u.user_id=er.reports_to) ELSE (SELECT CONCAT(fname,' ',lname) FROM user u WHERE u.user_id=er.user_id) END) name,s.schedule_date FROM employee_schedule s, employee_reporting er WHERE s.reporting_id=er.id  AND s.schedule_status='Pending' AND er.status=1 AND s.status=1 AND s.schedule_date>='$start' AND s.schedule_date<='$end' AND (er.user_id='$user_id' OR er.reports_to='$user_id')";
    $schedules = DB::getInstance()->querySample($schedulesQuery);
    foreach ($schedules as $list) {
        $form = $list->reports_to == $user_id ? '<br/><button onclick="toggleMeetingStatus(this,\'' . $list->id . '\', \'Done\');" class="btn btn-success btn-block">Mark as done</button>' : NULL;
        $events[] = array(
            'id' => $list->id,
            'title' => 'Meeting',
            'description' => "One on One meeting with $list->name",
            'start' => $list->schedule_date,
            'allDay' => TRUE,
            'editable' => FALSE,
            'is_task' => $list->reports_to == $user_id,
            "response_form" => $form
        );
    }

    echo json_encode($events);
}
if (Input::exists() && Input::get("uploadUserTask") == "uploadUserTask") {
    $user_id = Input::get("user_id");
    $content = Input::get("content");
    $assignment_id = Input::get("assignment_id");
    $start = Input::get("start_time");
    $end = Input::get("end_time");
    $id = DB::getInstance()->insert('tasks', array(
        'user_id' => $user_id,
        'project_assignment_id' => ($assignment_id) ? $assignment_id : NULL,
        'content' => $content,
        'expected_start_time' => ($start) ? $start : NULL,
        'expected_end_time' => ($end) ? $end : NULL
    ));
    echo $id;
}
if (isset($_POST['toggleMeetingStatus'])) {
    $id = Input::get("id");
    $status = Input::get("status");
    DB::getInstance()->update("employee_schedule", $id, array("schedule_status" => $status), 'id');
    echo $response = json_encode(array('title' => "Success", 'body' => "Schedule marked $status", 'type' => 'success'));
}

if (Input::exists() && Input::get("updateUserTask") == "updateUserTask") {
    $id = Input::get("id");
    $start = Input::get("start_time");
    $end = Input::get("end_time");
    DB::getInstance()->update('tasks', $id, array(
        'content' => $content,
        'expected_start_time' => ($start) ? $start : NULL,
        'expected_end_time' => ($end) ? $end : NULL
    ), 'id');
}
if (Input::exists() && Input::get("updateUserTaskStatus") == "updateUserTaskStatus") {
    $id = Input::get("id");
    $is_started = Input::get("is_started");
    $is_complete = Input::get("is_completed");
    $update_type = Input::get("update_type");
    if ($update_type == 'start') {
        $array_data = array('is_started' => $is_started);
        $array_data['time_started'] = ($is_started == 1) ? date("Y-m-d H:i:s") : NULL;
    } else if ($update_type == 'complete') {
        $array_data['completed_on'] = $date_today;
        $array_data['is_completed'] = 1;
        $array_data['time_completed'] = date("Y-m-d H:i:s");
    } else if ($update_type == 'delete') {
        $array_data['status'] = 0;
    } else if ($update_type == "user_response") {
        $user_response = Input::get("user_response");
        $task = DB::getInstance()->getRow("tasks", $id, "*", 'id');
        $resp = $task->user_response != NULL ? json_decode($task->user_response) : array();
        array_push($resp, $user_response);
        $array_data['user_response'] = json_encode($resp);
        if ($is_complete == 1) {
            $array_data['completed_on'] = $date_today;
            $array_data['is_completed'] = 1;
            $array_data['time_completed'] = date("Y-m-d H:i:s");
        }
    }
    DB::getInstance()->update('tasks', $id, $array_data, 'id');
}

if (Input::exists() && Input::get("save_form_draft") == "save_form_draft") {
    $form_type = trim(Input::get("form_type"), '"');
    $form_id = trim(Input::get("form_id"), '"');
    if ($form_type == "interim_form") {
        $international_line = Input::get("international_line");
        $sector = Input::get("sector");
        $office = Input::get("office");

        $professional_background = Input::get("professional_background");
        $professional_qualification = Input::get("professional_qualification");
        $date_started_in_current_grade = Input::get("date_started_in_current_grade");
        $appraisal_year = Input::get("appraisal_year");
        $appraiser = Input::get("appraiser");
        $interim_date = Input::get("interim_date");

        $competency_block_names = Input::get("competency_block_names");
        $competency_block_appraisee_comment = Input::get("competency_block_appraisee_comment");
        $competency_block_appraiser_comment = Input::get("competency_block_appraiser_comment");
        $interim_performance_review = serialize(array('names' => $competency_block_names, 'appraisee_comments' => $competency_block_appraisee_comment, 'appraiser_comments' => $competency_block_appraiser_comment));

        $development_objectives = Input::get("development_objectives");
        $development_actions = Input::get("development_actions");
        $develepment_time_frame = Input::get("develepment_time_frame");
        $develepment_kpis = Input::get("develepment_kpis");
        $develepment_appraisee_comments = Input::get("develepment_appraisee_comments");
        $develepment_appraiser_comments = Input::get("develepment_appraiser_comments");
        $interim_development_review = serialize(array('objectives' => $development_objectives, 'actions' => $development_actions, 'time_frame' => $develepment_time_frame, 'kpis' => $develepment_kpis, 'appraisee_comments' => $develepment_appraisee_comments, 'appraiser_comments' => $develepment_appraiser_comments));

        $career_aspirations_name = Input::get("career_aspirations_name");
        $aspiration_development_actions = Input::get("aspiration_development_actions");
        $aspiration_time_frame = Input::get("aspiration_time_frame");
        $aspiration_help_needed = Input::get("aspiration_help_needed");
        $aspiration_appraiser_comments = Input::get("aspiration_appraiser_comments");
        $career_aspirations = serialize(array('names' => $career_aspirations_name, 'actions' => $aspiration_development_actions, 'time_frame' => $aspiration_time_frame, 'help_needed' => $aspiration_help_needed, 'appraiser_comments' => $aspiration_appraiser_comments));

        $dev_plan_development_requirements = Input::get("dev_plan_development_requirements");
        $dev_plan_development_actions = Input::get("dev_plan_development_actions");
        $dev_plan_time_frame = Input::get("dev_plan_time_frame");
        $dev_plan_help_needed = Input::get("dev_plan_help_needed");
        $dev_plan_appraiser_feedback = Input::get("dev_plan_appraiser_feedback");
        $development_plan = serialize(array('requirements' => $dev_plan_development_requirements, 'actions' => $dev_plan_development_actions, 'time_frame' => $dev_plan_time_frame, 'help_needed' => $dev_plan_help_needed, 'appraiser_feedback' => $dev_plan_appraiser_feedback));

        $appraisee_final_comment = Input::get("appraisee_final_comment");
        $appraiser_final_comment = Input::get("appraiser_final_comment");
        $appraiser_overall_summary = Input::get("appraiser_overall_summary");
        $appraiser_agreement_comments = Input::get("appraiser_agreement_comments");
        $appraisee_signature = Input::get("appraisee_signature");
        $appraisee_date = Input::get("appraisee_date");
        $appraiser_signature = Input::get("appraiser_signature");
        $appraiser_date = Input::get("appraiser_date");
        $hr_signature = Input::get("hr_signature");
        $hr_comment = Input::get("hr_comment");
        $hr_date = Input::get("hr_date");

        $array = array(
            "form_date" => $date_today,
            "submitted_by" => $_SESSION['system_user_id'],
            "international_line" => $international_line,
            "sector" => $sector,
            "office" => $office,
            "professional_background" => $professional_background,
            "professional_qualification" => $professional_qualification,
            "date_started_in_current_grade" => ($date_started_in_current_grade) ? $date_started_in_current_grade : NULL,
            "appraisal_year" => $appraisal_year,
            "appraiser" => ($appraiser) ? $appraiser : NULL,
            "interim_performance_date" => ($interim_date) ? $interim_date : NULL,
            "interim_performance_review" => $interim_performance_review,
            "interim_development_review" => $interim_development_review,
            "career_aspirations" => $career_aspirations,
            "development_plan" => $development_plan,
            "appraisee_final_comments" => $appraisee_final_comment,
            "appraiser_final_comments" => $appraiser_final_comment,
            "interim_appraisal_summary" => $appraiser_overall_summary,
            "appraisee_agreement_comments" => $appraiser_agreement_comments,
            "appraisee_signature" => $appraisee_signature,
            "appraisee_date" => ($appraisee_date) ? $appraisee_date : NULL,
            "appraiser_signature" => $appraiser_signature,
            "appraiser_date" => ($appraiser_date) ? $appraiser_date : NULL,
            "hr_signature" => $hr_signature,
            "hr_comment" => $hr_comment,
            "hr_date" => ($hr_date) ? $hr_date : NULL,
            "status" => 2
        );
    }
    if ($form_type == "assignment_performance_form") {
        $date_started = Input::get("date_started");
        $appraisal_year = Input::get("appraisal_year");
        $appraiser = Input::get("appraiser");
        $project_name = Input::get("project_name");
        $assignment_date = Input::get("assignment_date");
        $assignment_type = Input::get("assignment_type");

        $assignment_date_range = Input::get("assignment_date_range");
        $assignment_start_date = ($assignment_date_range['from']) ? date("Y-m-d", strtotime($assignment_date_range['from'])) : NULL;
        $assignment_end_date = ($assignment_date_range['to']) ? date("Y-m-d", strtotime($assignment_date_range['to'])) : NULL;


        $competency_block_names = Input::get("competency_block_names");
        $competency_block_review = Input::get("competency_block_review");
        $overall_percentage_score = Input::get("overall_percentage_score");
        $assignment_performance_planning_review = serialize(array('name' => $competency_block_names, 'review' => $competency_block_review, 'overall_percentage' => $overall_percentage_score));
        $overall_recommendations = Input::get("overall_recommendations");
        $appraisee_comment = Input::get("appraisee_comment");
        $appraisee_signature = Input::get("appraisee_signature");
        $appraisee_date = Input::get("appraisee_date");
        $appraiser_signature = Input::get("appraiser_signature");
        $appraiser_date = Input::get("appraiser_date");
        $hr_signature = Input::get("hr_signature");
        $hr_comment = Input::get("hr_comment");
        $hr_date = Input::get("hr_date");
        $partner_signature = Input::get("partner_signature");
        $partner_date = Input::get("partner_date");

        $array = array(
            "form_date" => $date_today,
            "date_started_in_current_grade" => ($date_started) ? $date_started : NULL,
            "appraisal_year" => $appraisal_year,
            "appraiser" => $appraiser,
            "project_name" => $project_name,
            "assignment_date" => ($assignment_date) ? $assignment_date : NULL,
            "assignment_type" => $assignment_type,
            "assignment_start_date" => ($assignment_start_date) ? $assignment_start_date : NULL,
            "assignment_end_date" => ($assignment_end_date) ? $assignment_end_date : NULL,
            "assignment_performance_planning_review" => $assignment_performance_planning_review,
            "overall_recommendations" => $overall_recommendations,
            "appraisee_comment" => $appraisee_comment,
            "appraisee_signature" => $appraisee_signature,
            "appraisee_date" => ($appraisee_date) ? $appraisee_date : NULL,
            "appraiser_signature" => $appraiser_signature,
            "appraiser_date" => ($appraiser_date) ? $appraiser_date : NULL,
            "partner_signature" => $partner_signature,
            "appraiser_date" => ($appraiser_date) ? $appraiser_date : NULL,
            "hr_signature" => $hr_signature,
            "hr_comment" => $hr_comment,
            "hr_date" => ($hr_date) ? $hr_date : NULL,
            "partner_date" => ($partner_date) ? $partner_date : NULL,
            "submitted_by" => $_SESSION['system_user_id'],
            "status" => 2
        );
    }
    if ($form_type == "performance_appraisal_form") {
        $assignment_id = Input::get("assignment_id");
        $department = Input::get("department");
        $location = Input::get("location");
        $position = Input::get("position");
        $education_level = Input::get("education_level");
        $years_of_experience = Input::get("years_of_experience");
        $period_covered = Input::get("period_covered");
        $time_in_present_position = Input::get("time_in_present_position");
        $length_of_service = Input::get("length_of_service");
        $appraisal_time = Input::get("appraisal_time");
        $appraisal_revenue = Input::get("appraisal_revenue");
        $appraiser = Input::get("appraiser");
        $appraisal_completion_date = Input::get("appraisal_completion_date");
        $main_duties_and_responsibilities = Input::get("main_duties_and_responsibilities");
        $discussion_points_qn = Input::get("discussion_points_qn");
        $discussion_points_response = Input::get("discussion_points_response");
        $discussion_points = serialize(array('qn' => $discussion_points_qn, 'response' => $discussion_points_response));
        $own_capability_qn = Input::get("own_capability_qn");
        $own_capability_or_knowledge_response = Input::get("own_capability_or_knowledge_response");
        $own_capability_comment = Input::get("own_capability_comment");
        $own_capability_or_knowledge = serialize(array('qn' => $own_capability_qn, 'response' => $own_capability_or_knowledge_response, 'comment' => $own_capability_comment));
        $tasks_to_focus_on = Input::get("tasks_to_focus_on");

        $project_undertaken = Input::get("project_undertaken");
        $supervisor_score = Input::get("supervisor_score");
        $self_score = Input::get("self_score");
        $appraiser_comment = Input::get("appraiser_comment");
        $supervisor_score_average = Input::get("supervisor_score_average");
        $self_score_average = Input::get("self_score_average");
        $specific_job_appraisals = serialize(array('project_undertaken' => $project_undertaken, 'supervisor_score' => $supervisor_score, 'self_score' => $self_score, 'appraiser_comment' => $appraiser_comment, 'supervisor_score_average' => $supervisor_score_average, 'self_score_average' => $self_score_average));

        $appraisee_job_purpose = Input::get("appraisee_job_purpose");
        $completed_discussion_points = Input::get("completed_discussion_points");
        $general_objectives = Input::get("general_objectives");
        $appraisee_capability_qn = Input::get("appraisee_capability_qn");
        $appraisee_capability_or_knowledge_response = Input::get("appraisee_capability_or_knowledge_response");
        $appraisee_capability_or_knowledge_comment = Input::get("appraisee_capability_or_knowledge_comment");
        $appraisee_capability_or_knowledge = serialize(array('qn' => $appraisee_capability_qn, 'response' => $appraisee_capability_or_knowledge_response, 'comment' => $appraisee_capability_or_knowledge_comment));
        $appraisee_career_direction = Input::get("appraisee_career_direction");
        $skills_required_for_competence = Input::get("skills_required_for_competence");
        $specific_objectives = Input::get("specific_objectives");
        $training_support_to_be_given = Input::get("training_support_to_be_given");
        $recommendations_as_applicable = Input::get("recommendations_as_applicable");
        $appraisee_signature = Input::get("appraisee_signature");
        $appraisee_date = Input::get("appraisee_date");
        $appraiser_signature = Input::get("appraiser_signature");
        $appraiser_date = Input::get("appraiser_date");
        $hr_signature = Input::get("hr_signature");
        $hr_comment = Input::get("hr_comment");
        $hr_date = Input::get("hr_date");
        $partner_signature = Input::get("partner_signature");
        $partner_date = Input::get("partner_date");



        $array = array(
            "submitted_by" => $_SESSION['system_user_id'],
            "assignment_id" => $assignment_id,
            "form_date" => $date_today,
            "department" => $department,
            "location" => $location,
            "position" => $position,
            "education_level" => $education_level,
            "years_of_experience" => $years_of_experience,
            "period_covered" => $period_covered,
            "time_in_present_position" => $time_in_present_position,
            "length_of_service" => $length_of_service,
            "appraisal_time" => ($appraisal_time) ? $appraisal_time : NULL,
            "appraisal_venue" => $appraisal_revenue,
            "appraiser" => ($appraiser) ? $appraiser : NULL,
            "appraisee_completion_date" => ($appraisal_completion_date) ? $appraisal_completion_date : NULL,
            "main_duties_and_responsibilities" => $main_duties_and_responsibilities,
            "discussion_points" => $discussion_points,
            "own_capability_or_knowledge" => $own_capability_or_knowledge,
            "tasks_to_focus_on" => $tasks_to_focus_on,
            "specific_job_appraisals" => $specific_job_appraisals,
            "appraisee_job_purpose" => $appraisee_job_purpose,
            "completed_discussion_points" => $completed_discussion_points,
            "general_objectives" => $general_objectives,
            "appraisee_capability_or_knowledge" => $appraisee_capability_or_knowledge,
            "appraisee_career_direction" => $appraisee_career_direction,
            "skills_required_for_competence" => $skills_required_for_competence,
            "specific_objectives" => $specific_objectives,
            "training_support_to_be_given" => $training_support_to_be_given,
            "recommendations_as_applicable" => $recommendations_as_applicable,
            "appraisee_signature" => $appraisee_signature,
            "appraisee_date" => ($appraisee_date) ? $appraisee_date : NULL,
            "appraiser_signature" => $appraiser_signature,
            "appraiser_date" => ($appraiser_date) ? $appraiser_date : NULL,
            "hr_signature" => $hr_signature,
            "hr_comment" => $hr_comment,
            "hr_date" => ($hr_date) ? $hr_date : NULL,
            "partner_signature" => $partner_signature,
            "partner_date" => ($partner_date) ? $partner_date : NULL,
            "status" => 2
        );
        DB::getInstance()->update("performance_appraisal_assignment", $assignment_id, array("assignment_status" => "Completed"), "assignment_id");
    }
    if ($form_id) {
        DB::getInstance()->update($form_type, $form_id, $array, "form_id");
    } else {
        $form_id = DB::getInstance()->insert($form_type, $array);
    }
    echo json_encode(array(
        "form_id" => $form_id,
        "notif_title" => "Success", "notif_body" => "Draft saved successfully", "notif_type" => "success"
    ));
}
if (Input::exists() && Input::get("userSurveyAction") != "") {
    $action_made = Input::get("userSurveyAction");
    $survey_title = Input::get("survey_title");
    $survey_id = Input::get("survey_id");
    $user_id = Input::get("user_id");
    $section_id = Input::get("section_id");
    $valid_from = Input::get("valid_from");
    $valid_to = Input::get("valid_to");
    $old_banner = Input::get("old_banner");

    $json = Input::get("Json");
    if ($action_made == "UpdateSurveyHeaders") {
        $array = array("title" => $survey_title, "valid_from" => ($valid_from) ? $valid_from : NULL, "valid_to" => ($valid_to) ? $valid_to : NULL, "user_id" => $user_id, "section_id" => $section_id, "subsystem" => $subsystem);
        $file_name = $_FILES["surveyBanner"]["name"];
        if ($file_name) {

            $extension = end(explode(".", $file_name));
            $original_file_name = ($survey_id) ? $survey_id : date("YmdHis");
            $file_url = ($survey_id && $old_banner) ? $old_banner : "Banner-" . $original_file_name . "." . $extension;
            move_uploaded_file($_FILES["surveyBanner"]["tmp_name"], "uploads/banners/" . $file_url);

            $array["survey_banner"] = $file_url;
        } else {
            $array["survey_banner"] = $old_banner;
        }
        if ($survey_id != "") {
            DB::getInstance()->update("aml_tools", $survey_id, $array, 'tool_id');
        } else {
            $survey_id = DB::getInstance()->insert("aml_tools", $array);
        }

        $array['survey_id'] = $survey_id;
        $array['IsSuccess'] = 1; //If not successful send 0
        echo json_encode($array);
    }
    if ($action_made == "saveSurvey") {
        $array = array("title" => $survey_title, "valid_from" => ($valid_from) ? $valid_from : NULL, "valid_to" => ($valid_to) ? $valid_to : NULL, "tool_questions" => $json, "user_id" => $user_id, "section_id" => $section_id, "subsystem" => $subsystem);
        if ($survey_banner) {
            $array["survey_banner"] = $survey_banner;
        }
        if ($survey_id != "") {
            DB::getInstance()->update("aml_tools", $survey_id, $array, 'tool_id');
        } else {
            $survey_id = DB::getInstance()->insert("aml_tools", $array);
        }

        $array['survey_id'] = $survey_id;
        $array['json'] = $json;
        $array['IsSuccess'] = 1; //If not successful send 0
        echo json_encode($array);
    } else if ($action_made == "saveSurveyAnswer") {
        $survey_id = Input::get("survey_id");
        $client_id = Input::get('client_id');
        $array = array("tool_id" => $survey_id, "tool_results" => $json, 'client_id' => ($client_id != '') ? $client_id : NULL, 'user_id' => ($user_id != '') ? $user_id : NULL);
        DB::getInstance()->insert("tool_answer", $array);
    }
}
if (Input::exists() && Input::get("displaySectionPolicies") == "displaySectionPolicies") {
    $section_id = Input::get("section_id");
    $policiesList = DB::getInstance()->querySample("SELECT policy_id,title FROM aml_policy WHERE section_id='$section_id' AND status=1 ORDER BY title");
    foreach ($policiesList as $policies) {
        echo '<option value="' . $policies->policy_id . '">' . $policies->title . '</option>';
    }
}
if (Input::exists() && Input::get("displayNotifications") == "displayNotifications") {
    $array = array();
    $rows = array();
    $cu_time = date('Y-m-d H:i');
    $cu_time = increaseDateToDate(-1, 'minute', $cu_time);
    $notificationsQuery = "SELECT n.*,u.photo FROM notifications n,user u WHERE n.uploaded_by=u.user_id AND n.expiry_date>='$date_today' AND n.time_submitted>='$cu_time' AND n.status=1 ORDER BY n.time_submitted DESC";
    $notificationsCount = DB::getInstance()->countElements($notificationsQuery);
    if (DB::getInstance()->checkRows($notificationsQuery)) {
        $notificationsList = DB::getInstance()->querySample($notificationsQuery);
        foreach ($notificationsList as $list) {
            $photo = ($list->photo != '') ? $list->photo : 'default.jpg';
            $n_data['title'] = $list->notification_title;
            $n_data['time'] = english_date_time($list->time_submitted);
            $n_data['msg'] = $list->message;
            $n_data['icon'] = 'uploads/user_profiles/' . $photo;
            $n_data['url'] = '';
            $rows[] = $n_data;
        }
        $array['result'] = true;
    } else {
        $array['result'] = false;
    }
    $array['notif'] = $rows;
    $array['count'] = $notificationsCount;
    echo json_encode($array);
}
if (Input::exists() && Input::get("executeUserAction")) {
    //action_made:action_made,cmd_arg:cmd_arg, current_dir: current_dir, current_dept: current_dept, owner: owner
    $user_id = (Input::get("owner") != "") ? Input::get("owner") : $_SESSION['system_user_id'];
    $current_dept = Input::get("current_dept");
    $current_dir = Input::get("current_dir");
    $dir_destination = Input::get("dir_destination");
    $absolute_destination = "uploads/folders/" . $dir_destination;
    $exact_name = trim(str_replace("/", " ", Input::get("cmd_arg")), " ");
    $name_submitted = htmlentities($exact_name, ENT_QUOTES);
    $action_made = Input::get("action_made");
    $client_id = Input::get("client_id");
    $testCondition = ($current_dir != "") ? " AND parent_folder='$current_dir'" : " AND department_id='$current_dept'";
    if ($action_made == "create_directory") {
        $folderQuery = "SELECT * FROM folder WHERE folder_name='$name_submitted' $testCondition LIMIT 1";
        if (!DB::getInstance()->checkRows($folderQuery)) {
            $folder_id = DB::getInstance()->insert("folder", array(
                "folder_name" => $name_submitted,
                "parent_folder" => $current_dir,
                "folder_url"=> $dir_destination,
                "user_id" => $user_id,
                "client_id" => ($client_id) ? $client_id : NULL
            ));
            DB::getInstance()->update("folder",$folder_id,array("folder_url"=> $dir_destination. $folder_id.'/'),"folder_id");
            DB::getInstance()->insert("logs", array("user_id" => $user_id, "log_action" => "created folder " . $exact_name));
            if (!file_exists($absolute_destination . $folder_id)) {
                @mkdir($absolute_destination . $folder_id);
                @chmod($absolute_destination . $folder_id, 0777);

                if (Input::get("create_input_and_output_folders") == 1) {
                    $input_id = DB::getInstance()->insert("folder", array(
                        "folder_name" => 'input',
                        "parent_folder" => $folder_id,
                        "folder_url"=> $dir_destination. $folder_id. '/',
                        "client_id" => ($client_id) ? $client_id : NULL
                    ));
                    DB::getInstance()->update("folder",$input_id,array("folder_url"=> $dir_destination. $folder_id. '/' . $input_id.'/'),"folder_id");
                    $output_id = DB::getInstance()->insert("folder", array(
                        "folder_name" => 'output',
                        "parent_folder" => $folder_id,
                        "folder_url"=> $dir_destination. $folder_id. '/',
                        "client_id" => ($client_id) ? $client_id : NULL
                    ));
                    DB::getInstance()->update("folder",$output_id,array("folder_url"=> $dir_destination. $folder_id. '/' . $output_id.'/'),"folder_id");
                    @mkdir($absolute_destination . $folder_id . '/' . $input_id);
                    @mkdir($absolute_destination . $folder_id . '/' . $output_id);
                    @chmod($absolute_destination . $folder_id . '/' . $input_id, 0777);
                    @chmod($absolute_destination . $folder_id . '/' . $output_id, 0777);
                }
            }
            $body=DB::getInstance()->displayDirectoryInformation($current_dir, $accessAllFiles);
            echo json_encode(array(
                "body"=>$body,
                "notif_title" => "Success",
                "notif_body" => "Sharing made successfully",
                "notif_type" => "success"
            ));
        } else {
            echo json_encode(array(
                "body"=>"Folder already exists",
                "notif_title" => "Error",
                "notif_body" => "Folder Exists",
                "notif_type" => "error"
            ));
        }
    } else if ($action_made == "sync_directory") {
        $sync_directory = Input::get("sync_directory");
        $sync_destination_directory = Input::get("sync_destination_directory");
        $absolute_destination = "uploads/folders/";
        // echo '2###Folder Synced ' . $sync_destination_directory;
        echo json_encode(array(
            "notif_title" => "Success",
            "notif_body" => "Directory synced successfully",
            "notif_type" => "success"
        ));
        $r = syncDirectory($absolute_destination, $sync_directory, $sync_destination_directory);
    } else if ($action_made == "upload_file") {
        $file_names = $_FILES["file"]["name"];
        $client_id = Input::get("client_id");
        for ($i = 0; $i < count($file_names); $i++) {
            $file_name = $file_names[$i];
            $extension = end(explode(".", $file_name));
            $key = strtolower($extension);
            $file_type = (array_key_exists($key, $file_extensions)) ? $file_extensions[$key] : 'code';
            $original_file_name = pathinfo($file_name, PATHINFO_FILENAME);
            $file_url = $original_file_name . "-" . date("YmdHis") . "." . $extension;
            DB::getInstance()->insert("file", array(
                "file_url" => $file_url,
                "file_extension" => $extension,
                "file_name" => $original_file_name,
                "file_type" => $file_type,
                "parent_folder" => $current_dir,
                "user_id" => $user_id,
                "client_id" => ($client_id) ? $client_id : NULL
            ));
            @move_uploaded_file($_FILES["file"]["tmp_name"][$i], $absolute_destination . $file_url);
            @chmod($absolute_destination . $file_url, 0777);
        }
        if ($subsystem == 'client_portal') {
            //Try to send email
            if (in_array("uploadOutputFile", $user_permissions)) {
                $sender = (object) array('email' => $SYSTEM_EMAIL, 'name' => $SITE_NAME . ' Administrator');
                $receiver = DB::getInstance()->querySample("SELECT c.client_email email,c.client_name name FROM client c WHERE c.client_id='$client_id' LIMIT 1")[0];
            } else {
                $sender = DB::getInstance()->querySample("SELECT c.client_email email,c.client_name name FROM client c WHERE c.client_id='$client_id' LIMIT 1")[0];
                $receiver = (object) array('email' => $SYSTEM_EMAIL, 'name' => $SITE_NAME . ' Administrator');
            }
            $parentFolder = DB::getInstance()->querySample("SELECT pf.* FROM folder f,folder pf WHERE pf.folder_id=f.parent_folder AND f.folder_id='$current_dir' AND f.status=1 LIMIT 1")[0];
            $template = DB::getInstance()->getRow('notificationtemplate', 'new_file_upload', '*', 'code');
            $search = array('{names}', '{uploaded_by}', '{parent_folder}', '{company}');
            $replace = array($receiver->name, $sender->name, $parentFolder->folder_name, $COMPANY_NAME);
            $message = str_replace($search, $replace, $template->message);
            sendEmail($receiver->email, $receiver->name, $template->subject, $message);
        }
        DB::getInstance()->insert("logs", array("user_id" => $user_id, "log_action" => " uploaded " . count($file_names) . " file(s)"));
        $body=DB::getInstance()->displayDirectoryInformation($current_dir, $accessAllFiles);
        echo json_encode(array(
            "body"=>$body,
            "notif_title" => "Success",
            "notif_body" => "Uploaded successfully",
            "notif_type" => "success"
        ));
    } else if ($action_made == "rename") {
        $old_name = Input::get("old_name");
        $type = Input::get("type");
        $id = Input::get("id");
        DB::getInstance()->update($type, $id, array($type . '_name' => $name_submitted), $type . '_id');
        DB::getInstance()->insert("logs", array("user_id" => $user_id, "log_action" => "changed " . $type . " name from " . $old_name . " to " . $name_submitted));
        rename($absolute_destination . $old_name . '-' . $id, $absolute_destination . $name_submitted . '-' . $id);
        $body=DB::getInstance()->displayDirectoryInformation($current_dir, $accessAllFiles);
        echo json_encode(array(
            "body"=>$body,
            "notif_title" => "Success",
            "notif_body" => "Renamed successfully",
            "notif_type" => "success"
        ));
    } else if ($action_made == 'delete') {
        $type = Input::get("user_cmd_type");
        $cmd_arg = Input::get("cmd_arg");
        DB::getInstance()->update($type, $cmd_arg, array('status' => 0), $type . '_id');
        DB::getInstance()->insert("logs", array("user_id" => $user_id, "log_action" => "deleted " . $type));
        $body=DB::getInstance()->displayDirectoryInformation($current_dir, $accessAllFiles);
        echo json_encode(array(
            "body"=>$body,
            "notif_title" => "Success",
            "notif_body" => "Deleted successfully",
            "notif_type" => "success"
        ));
    } else if ($action_made == 'move_file' || $action_made == 'copy_file') {
        $file_array = explode(",", trim(Input::get("cmd_arg"), ','));
        $next_destination_array = explode('***', Input::get("next_destination"));
        $next_destination_id = $next_destination_array[0];
        $next_destination_url = 'uploads/folders/' . $next_destination_array[1];
        $absolute_destination;
        $file_ids = array();
        $folder_ids = array();
        for ($i = 0; $i < count($file_array); $i++) {
            $file_data = explode('-', $file_array[$i]);
            $file_id = $file_data[0];
            $file_type = $file_data[1];
            if ($file_type == 'file') {
                $file_ids[] = $file_id;
            } else {
                $folder_ids[] = $file_id;
            }
        }
        if (count($file_ids) > 0) {
            $f = implode(',', $file_ids);
            $filesLoop = DB::getInstance()->querySample("SELECT * FROM file WHERE file_id IN ($f)");
            foreach ($filesLoop as $list) {
                $file_url = $list->file_url;
                $file_name = $list->file_name;
                if (file_exists($next_destination_url . $list->file_url)) {
                    $file_url = date('Ymd') . 'copy-' . $list->file_url;
                    $file_name = date('Ymd') . 'copy-' . $file_name;
                }
                if ($action_made == 'copy_file') {
                    @copy($absolute_destination . $list->file_url, $next_destination_url . $file_url);
                    @chmod($next_destination_url . $file_url, 0777);
                    DB::getInstance()->insert("file", array(
                        "file_url" => $file_url,
                        "file_extension" => $list->file_extension,
                        "file_name" => $file_name,
                        "file_type" => $list->file_type,
                        "parent_folder" => $next_destination_id,
                        "user_id" => $list->user_id
                    ));
                } else {
                    @rename($absolute_destination . $list->file_url, $next_destination_url . $file_url);
                    DB::getInstance()->update($file_type, $list->file_id, array('parent_folder' => $next_destination_id, 'file_url' => $file_url), $file_type . '_id');
                }
            }
        }
        if (count($folder_ids) > 0) {
            $f = implode(',', $folder_ids);
            $foldersLoop = DB::getInstance()->querySample("SELECT * FROM folder WHERE folder_id IN ($f)");
            foreach ($foldersLoop as $list) {
                $stored_folder_name = $list->folder_id;
                $saved_folder_name = $list->folder_name;
                if (file_exists($next_destination_url . $stored_folder_name)) {
                    $saved_folder_name = date('Ymd') . 'copy-' . $saved_folder_name;
                }
                if ($list->folder_id != $next_destination_id) {
                    if ($action_made == 'copy_file') {
                        DB::getInstance()->copyFolder($absolute_destination . $list->folder_id, trim($next_destination_url, '/'), $list->folder_id, $list->folder_name, $next_destination_id, $user_id, true);
                    } else {
                        $parentRow=DB::getInstance()->getRow("folder", $next_destination_id, "*", "folder_id");
                        @rename($absolute_destination . $list->folder_id, $next_destination_url . $stored_folder_name);
                        DB::getInstance()->update($file_type, $list->folder_id, array('parent_folder' => $next_destination_id,'folder_url'=>$parentRow->folder_url.$list->folder_id.'/', 'folder_name' => $saved_folder_name), $file_type . '_id');
                    }
                }
            }
        }
        $submitted_action = ($action_made == 'copy_file') ? 'copied ' : 'moved ';
        DB::getInstance()->insert("logs", array("user_id" => $user_id, "log_action" => $submitted_action . " folder data "));
        $body=DB::getInstance()->displayDirectoryInformation($current_dir, $accessAllFiles);
        echo json_encode(array(
            "body"=>$body,
            "notif_title" => "Success",
            "notif_body" => "Copied successfully",
            "notif_type" => "success"
        ));
    } else if ($action_made == "shareFile") {
        $shared_to = Input::get("shared_to");
        $share_type = Input::get("share_type");
        $shared_item = Input::get("id");
        $can_edit = Input::get("can_edit");
        $can_delete = Input::get("can_delete");

        DB::getInstance()->delete('file_shared', array($share_type, '=', $shared_item));
        if ($shared_to) {
            for ($i = 0; $i < count($shared_to); $i++) {
                if ($shared_to[$i]) {
                    DB::getInstance()->insert('file_shared', array(
                        'shared_by' => $user_id,
                        $share_type => $shared_item,
                        'shared_to' => $shared_to[$i],
                        'can_edit' => ($can_edit[$shared_to[$i]]) ? $can_edit[$shared_to[$i]] : NULL,
                        'can_delete' => ($can_delete[$shared_to[$i]]) ? $can_delete[$shared_to[$i]] : NULL
                    ));
                }
            }
            echo json_encode(array(
                "notif_title" => "Success",
                "notif_body" => "Sharing made successfully",
                "notif_type" => "success"
            ));
        } else {
            echo json_encode(array(
                "notif_title" => "Success",
                "notif_body" => "All shared users removed",
                "notif_type" => "success"
            ));
        }
    }
}
if (Input::exists() && Input::get("displayDirectoryInfo")) {
    //directory_id: dir_id, directory_name: dir_name, owner:
    $directory_id = Input::get("directory_id");
    $directory_name = Input::get("directory_name");
    $is_shared = Input::get("is_shared");
    $display_type = Input::get("display_type");
    setcookie("current_directory", $directory_id, time() + 3600);
    $body = DB::getInstance()->displayDirectoryInformation($directory_id, $accessAllFiles, $is_shared, $display_type);
    $dirInfo = DB::getInstance()->getRow("folder", $directory_id, "*", "folder_id");
    // echo '###' . DB::getInstance()->getName("folder", $directory_id, "department_id", "folder_id");

    // $otherDirectories=DB::getInstance()->querySample("SELECT * FROM folder WHERE folder_url IS NOT NULL AND folder_id!='$directory_id'");
    // $directoriesData='';
    // foreach ($otherDirectories as $folder) {
    //     $directoriesData.='<option value="' . $folder->folder_id . '***' . $folder->folder_url. '">'.$folder->folder_name.'</option>';
    // }
    echo json_encode(array(
        "body" => $body,
        "pagination" => "",
        "destination_url" => $dirInfo->folder_url,
        "other_directories_options" => $directoriesData,
        "department_id" => $dirInfo->department_id,
        "notification_title" => "Success",
        "notification_body" => "OK",
        "notification_type" => "success"
    ));
}
