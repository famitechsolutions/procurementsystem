<?php

$user_id = $_SESSION['system_user_id'];
require('pdf_header.php');
$pdf = new PDF();
$pdf->AliasNbPages();
if (isset($_GET['type']) && ($_GET['type'] == "download_mentorship_form")) {
    $pdf->AddPage('P');
    $pdf->createHeader('');
    $pdf->SetTextColor(0, 0, 0);
    $form_id = $crypt->decode($_GET['form_id']);
    $formsQuery = "SELECT * FROM mentorship_form,user WHERE user.user_id=mentorship_form.mentee AND mentorship_form.status=1  AND form_id='$form_id'";
    $form_data = DB::getInstance()->querySample($formsQuery);
    foreach ($form_data as $data) {
        $reviewer = DB::getInstance()->DisplayTableColumnValue("SELECT CONCAT(fname,' ',lname) AS reviewer_name FROM user WHERE user_id='$data->reviewer'", "reviewer_name");
        $time_submitted = $data->time_submitted;
        $pdf->Cell(0, 6, "Mentoring Session Documentation Form", 0, 1, "C");
        $pdf->SetFont("Arial", "", 8);
        $pdf->SetWidths(array(60, 130));
        $pdf->Row(array("Session Date:", "$data->session_date"));
        $pdf->Row(array("Session Duration:", "$data->session_duration"));
        $pdf->Row(array("Location:", getConfigValue("office_location")));
        $pdf->Row(array("Mentee:", "$data->fname    $data->lname "));
        $pdf->Row(array("Mentor:", "$data->mentor"));
        $pdf->Row(array("Reviewer:", "$reviewer"));

        $pdf->SetWidths(array(190));
        $pdf->SetFont("", "B");
        $pdf->write(4, "\nSession Part 1: Review of agreed To Dos / Aspects to be reflected on from previous session. \nThis session part should answer the questions:\nTo what extent have the tasks / research / reflections / observations been carried out?\nWhat worked, what did not work?\nWhat are the results / implications / effects?\nHow do mentor / mentee feel about this issue? (e.g. relieved / happy that it worked)\n");
        $pdf->SetFont("", "");
        $pdf->Row(array("$data->agreed_todos"));
        $pdf->SetFont("", "B");
        $pdf->Write(4, "\nSession Part 2: Focus on Current Issues.\nAny current / upcoming issues to be discussed \n What is urgent, relevant, pressing, interesting at the moment?\nHas there been a specific incident which needs discussing?\n");
        $pdf->SetFont("", "");
        $pdf->Row(array("$data->current_issues"));
        $pdf->SetFont("", "B");
        $pdf->write(4, "\nSession Part 3:Focus on Agreement on Next Step,\nAgreed To Dos, any other aspects to be reflected / researched until last session\nWhich aspect will mentor / mentee focus on?\nWhat exactly need mentor / mentee do / observe / reflect on / research … until next session?\n");
        $pdf->SetFont("", "");
        $pdf->Row(array("$data->other_aspects"));
    }
    DB::getInstance()->insert("logs", array("user_id" => $user_id, "log_action" => "printed mentorship form ID $form_id"));
    //$pdf->output('D', 'MENTORSHIP FORM-' . date("Ymdhis") . '.pdf');
    $pdf->AutoPrint();
    $pdf->Output();
} else if (isset($_GET['type']) && ($_GET['type'] == "download_assignment_performance_form")) {
    $pdf->AddPage('P');
    $pdf->createHeader('');
    $pdf->SetTextColor(0, 0, 0);
    $form_id = $crypt->decode($_GET['form_id']);
    $pdf->Cell(0, 6, "Assignment Performance and Development Review - Employee Grades", 0, 1, "C");
    $pdf->SetFont("", "U", 6);
    $pdf->Cell(0, 5, "Part 1.1: Appraisee Details", 0, 1, "L");
    $pdf->SetFont("", "");
    $formsQuery = "SELECT * FROM assignment_performance_form ,user,department,user_grades WHERE user.grade_id=user_grades.grade_id AND user.department_id=department.department_id AND user.user_id=assignment_performance_form.submitted_by AND assignment_performance_form.form_id='$form_id' AND assignment_performance_form.status=1";
    $form_data = DB::getInstance()->querySample($formsQuery);
    foreach ($form_data as $data) {
        $time_submitted = $data->time_submitted;
        $appraiser = DB::getInstance()->DisplayTableColumnValue("SELECT CONCAT(fname,' ',lname) AS appraiser_names FROM user WHERE user_id='$data->appraiser'", "appraiser_names");

        $pdf->SetWidths(array(60, 130));
        $pdf->Row(array("Surname:", "$data->fname"));
        $pdf->Row(array("First Name:", "$data->lname"));
        $pdf->Row(array("Grade:", "$data->grade_name"));
        $pdf->Row(array("Department:", "$data->department_name "));
        $pdf->Row(array("Date started in current grade:", "$data->date_started"));
        $pdf->Row(array("Appraisal Year:", "$data->appraisal_year "));
        $pdf->Row(array("Appraiser Name:", "$appraiser"));
        $pdf->Row(array("Date of Assessment:", "$data->assignment_date"));

        $pdf->SetFont("", "U", 6);
        $pdf->Cell(0, 5, "Part 1.2: Client Information", 0, 1, "L");
        $pdf->SetFont("", "");
        $pdf->Row(array("Project Name:", "$data->project_name "));
        $pdf->Row(array("Assignment Type:", "$data->assignment_type"));
        $pdf->Row(array("Assignment Start and End Dates:", "$data->assignment_start_date  to  $data->assignment_end_date"));

        $pdf->SetFont("", "U", 6);
        $pdf->Cell(0, 5, "Part 2: Assignment Performance Planning and Review", 0, 1, "L");
        $pdf->SetFont("", "");
        $pdf->SetWidths(array(40, 150));
        $pdf->Row(array("", "The Appraisee and Assignment Manager must agree and record key operational and developmental objectives, expected results and KPIs prior to starting the assignment. On completion of the assignment, the Assignment Manager must evaluate the Appraisee's performance during the assignment. "));


        $pdf->SetWidths(array(40, 75, 35, 20, 20));
        $pdf->Row(array("", "Assignment Performance Plan", "Assignment Review", "", "Assignment Score"));

        $pdf->SetFont("", "");
        $pdf->SetWidths(array(40, 40, 35, 35, 20, 20));
        $pdf->Row(
            array(
                "Competency Block",
                "Operational and Developmental Objectives. What must I achieve?What targets will I hit?",
                "Results and KPIs. What will success look like?",
                "Assignment Manager's Evidence. What did the Appraisee achieve? What targets were hit?",
                "Employee's score,  How do I rate myself? (1-3 = poor, 4-6 = satisfactory, 7-9 = good, 10 = excellent)",
                "Engagement Manager's score How does my manager rate me? (1-3 = poor, 4-6 = satisfactory, 7-9 = good, 10 = excellent)"
            )
        );

        $pdf->SetFont("", "", 6);
        $assignment_performance_planning_review = unserialize($data->assignment_performance_planning_review);
        $names = $assignment_performance_planning_review['name'];
        $reviews = $assignment_performance_planning_review['review'];
        $overall_percentage_score = $assignment_performance_planning_review['overall_percentage'];
        for ($x = 0; $x < count($names); $x++) {
            $arr = array($names[$x]);
            foreach ($reviews[$names[$x]] as $value) {
                array_push($arr, $value);
            }
            $pdf->Row($arr);
        }
        $pdf->SetFont("", "B");
        $pdf->Cell(150, 4, "Overall percentage score", 1, 0, "L");
        for ($i = 0; $i < 2; $i++) {
            $break = ($i == 1) ? 1 : 0;
            $pdf->Cell(20, 4, $overall_percentage_score[$i], 1, $break, "L");
        }
        $pdf->SetFont("", "B");
        $pdf->write(4, "\nAssignment Manager's Overall Assessment of Performance and Recommendations for Further Development\n");
        $pdf->SetFont("", "");
        $pdf->SetWidths(array(190));
        $pdf->Row(array("$data->overall_recommendations"));
        $pdf->SetFont("", "B");
        $pdf->write(4, "\nPart 3: Appraisee's Comments\n");
        $pdf->SetFont("", "");
        $pdf->Row(array("$data->appraisee_comment"));
        $pdf->write(4, "\nSignatories\n");

        $pdf->SetWidths(array(30, 50, 30, 80));
        $pdf->Row(array("", "Signature", "Date", "Comment"));
        $pdf->Row(array("Appraisee", "$data->appraisee_signature", "$data->appraisee_date", ""));
        $pdf->Row(array("Assignment Manager", "$data->appraiser_signature", "$data->appraiser_date", ""));
        $pdf->Row(array("HR", "$data->hr_signature", "$data->hr_date", "$data->hr_comment"));
        $pdf->Row(array("Engagement Partner", "$data->partner_signature", "$data->partner_date", ""));
    }
    DB::getInstance()->insert("logs", array("user_id" => $user_id, "log_action" => "printed Assignment Performance and Development Review form ID $form_id"));
    $pdf->AutoPrint();
    $pdf->Output();
} else if (isset($_GET['type']) && ($_GET['type'] == "download_performance_appraisal_form")) {
    $pdf->AddPage('P');
    $pdf->createHeader('');
    $pdf->SetTextColor(0, 0, 0);
    $form_id = $crypt->decode($_GET['form_id']);
    $pdf->Cell(0, 10, "Performance Appraisal Form", 0, 1, "C");
    $pdf->SetFont("", "", 6);
    $formsQuery = "SELECT * FROM performance_appraisal_form,user,user_grades,department WHERE user.grade_id=user_grades.grade_id AND user.department_id=department.department_id AND performance_appraisal_form.submitted_by=user.user_id AND performance_appraisal_form.form_id='$form_id' AND performance_appraisal_form.status=1";
    $form_data = DB::getInstance()->querySample($formsQuery);
    foreach ($form_data as $data) {
        $appraiser = DB::getInstance()->DisplayTableColumnValue("SELECT CONCAT(fname,' ',lname) AS appraiser_names FROM user WHERE user_id='$data->appraiser'", "appraiser_names");
        $pdf->SetWidths(array(90, 100));
        $pdf->Row(array("Department: $data->department_name", "Location: " . getConfigValue("office_location")));

        $pdf->SetWidths(array(60, 40, 50, 40));
        $pdf->Row(array("Name: $data->fname  $data->lname", "Grade: $data->grade_name", "Education level: $data->education_level", "Experience: $data->years_of_experience"));

        $pdf->Row(array("Time in present position: $data->time_in_present_position", "Year or period covered: $data->period_covered", "Appraisal date and time: $data->appraisal_time", "Appraiser: $appraiser"));

        $pdf->write(4, "\nPart A Appraisee to complete before the interview and return to the appraiser by (date): $data->appraisee_completion_date\n");
        $pdf->write(4, "A1 Please describe your understanding of your main duties and responsibilities as indicated in your Key Result Area ('KRA').\n");
        $pdf->SetWidths(array(190));
        $pdf->Row(array("$data->main_duties_and_responsibilities"));
        $discussion_points = unserialize($data->discussion_points);
        for ($i = 0; $i < count($discussion_points_array); $i++) {
            $pdf->SetFont("", "B", 6);
            $pdf->Write(4, "\n$discussion_points_array[$i]\n");
            $pdf->SetFont("", "");
            $pdf->Row(array($discussion_points['response'][$i]));
        }
        $pdf->SetFont("", "B");
        $pdf->SetWidths(array(30, 15, 65, 15, 65));
        $pdf->Row(array("", "Own Capability", "Comment", "Apraiser Score", "Appraiser Comment"));

        $pdf->SetFont("", "");
        $own_capability_or_knowledge = unserialize($data->own_capability_or_knowledge);
        $appraisee_capability_or_knowledge = unserialize($data->appraisee_capability_or_knowledge);
        for ($i = 0; $i < count($own_capability_or_knowledge['qn']); $i++) {
            $pdf->Row(array(($i + 1) . '. ' . $own_capability_or_knowledge['qn'][$i], $own_capability_or_knowledge['response'][$i], $own_capability_or_knowledge['comment'][$i], $appraisee_capability_or_knowledge['response'][$i], $appraisee_capability_or_knowledge['comment'][$i]));
        }
        $pdf->write(4, "\nA4: In light of your current capabilities, your performance against past objectives, and your future personal growth and/or job aspirations, what activities and tasks would you like to focus on during the next year. Again, also think of development and experiences outside of job skills - related to personal aims, fulfilment, passions.\n");
        $pdf->write(4, "$data->tasks_to_focus_on");
        $pdf->SetFont("", "B", 6);
        $pdf->write(4, "\nA5: Specific job Appraisals\nSummary of Assignments performance during the period\n");

        $pdf->SetWidths(array(60, 35, 35, 60));
        $pdf->Row(array("Projects undertaken", "Supervisor's Score", "Own Score", "Appraser's Comment"));
        $pdf->SetFont("", "");
        $specific_job_appraisals = unserialize($data->specific_job_appraisals);
        for ($i = 0; $i < count($specific_job_appraisals['project_undertaken']); $i++) {
            $pdf->Row(array($specific_job_appraisals['project_undertaken'][$i], $specific_job_appraisals['supervisor_score'][$i], $specific_job_appraisals['self_score'][$i], $specific_job_appraisals['appraiser_comment'][$i]));
        }
        $pdf->SetFont("", "B");
        $pdf->Cell(60, 5, "Total Average score (Out of 60%)", 1, 0, "L");
        $pdf->Cell(35, 5, $specific_job_appraisals['supervisor_score_average'][0], 1, 0, "L");
        $pdf->Cell(35, 5, $specific_job_appraisals['self_score_average'][0], 1, 0, "L");
        $pdf->Cell(60, 5, "", 1, 1, "L");
        $pdf->SetFont("", "B", 8);
        $pdf->write(4, "PART B\n\n");
        $pdf->SetFont("", "B", 6);
        $pdf->write(4, "To be completed during the appraisal by the appraiser - where appropriate and safe to do so, certain items can be completed by the appraiser before the appraisal, and then discussed and validated or amended in discussion with the appraisee during the appraisal.\n\nPlease describe the purpose of the appraisee's job. Discuss and compare with self-appraisal entry in A1. Clarify job purpose and priorities where necessary.\n");
        $pdf->SetFont("", "");
        $pdf->SetWidths(array(190));
        $pdf->Row(array("$data->appraisee_job_purpose"));
        $pdf->SetFont("", "B");
        $pdf->write(4, "\nB2: Review the completed discussion points A2, and note the points of and act\n");
        $pdf->SetFont("", "");
        $pdf->Row(array("$data->completed_discussion_points"));
        $pdf->SetFont("", "B");
        $pdf->write(4, "\nB3 List the objectives that the appraisee set out to achieve in the past 12 months (or the period covered by this appraisal/when\n");
        $pdf->SetFont("", "");
        $pdf->Row(array("$data->general_objectives"));
        $pdf->SetFont("", "B");
        $pdf->write(4, "\nB4 Discuss and agree the appraisee's career direction options and wishes, and readiness for promotion, and compare with and discuss the self-appraisal entry in A5. (Some people do not wish for promotion, but everyone is capable of, and generally benefits from, personal development - development and growth should be available to all, not just people seeking promotion). Note the agreed development aim(s):\n");
        $pdf->SetFont("", "");
        $pdf->Row(array("$data->appraisee_career_direction"));
        $pdf->SetFont("", "B");
        $pdf->write(4, "\nB5 Discuss and agree the skills, capabilities and experience required for competence in current role, and if appropriate, for readiness to progress to the next role or roles. Refer to actions arising from B3 and the skill-set in B4, in order to accurately identify all development areas, whether for competence at current level or readiness to progress to next job level/type.) Note the agreed development areas:\n");
        $pdf->SetFont("", "");
        $pdf->Row(array("$data->skills_required_for_competence"));
        $pdf->SetFont("", "B");
        $pdf->write(4, "\nB6 Discuss and agree the specific objectives that will enable the appraisee to reach the desired competence and to meet required performance in current job, if appropriate taking account of the coming year's plans, budgets, targets etc., and that will enable the appraisee to move towards, or achieve readiness for, the next job level/type, or if no particular next role is identified or sought, to achieve the desired personal growth or experience. These objectives must adhere to the SMARTER rules - specific, measurable, agreed, realistic, time-bound, ethical and recorded.\n");
        $pdf->SetFont("", "");
        $pdf->Row(array("$data->specific_objectives"));
        $pdf->SetFont("", "B");
        $pdf->write(4, "\nB7 Discuss and agree (as far as is possible, given budgetary, availability and authorisation considerations) the training and development support to be given to help the appraisee meet the agreed objectives above.\n");
        $pdf->SetFont("", "");
        $pdf->Row(array("$data->training_support_to_be_given"));
        $pdf->SetFont("", "B");
        $pdf->write(4, "\nGrade/ recommendation/summary as applicable:\n");
        $pdf->SetFont("", "");
        $pdf->Row(array("$data->recommendations_as_applicable"));
        $pdf->write(4, "\n\n");

        $pdf->SetWidths(array(30, 50, 30, 80));
        $pdf->Row(array("", "Signature", "Date", "Comment"));
        $pdf->Row(array("Signature", "$data->appraisee_signature", "$data->appraisee_date", ""));
        $pdf->Row(array("Appraiser", "$data->appraiser_signature", "$data->appraiser_date", ""));
        $pdf->Row(array("HR", "$data->hr_signature", "$data->hr_date", ""));
        $pdf->Row(array("Partner", "$data->partner_signature", "$data->partner_date", ""));
    }
    DB::getInstance()->insert("logs", array("user_id" => $user_id, "log_action" => "printed Performance appraisal form ID $form_id"));
    $pdf->AutoPrint();
    $pdf->Output();
} else if (isset($_GET['type']) && ($_GET['type'] == "download_interim_form")) {
    $pdf->AddPage('P');
    $pdf->createHeader('');
    $pdf->SetTextColor(0, 0, 0);
    $form_id = $crypt->decode($_GET['form_id']);
    $pdf->Cell(0, 10, "Interim Form", 0, 1, "C");
    $pdf->SetFont("", "B", 8);
    $pdf->Cell(0, 10, "Part 1: Appraisee details", 0, 1, "L");
    $pdf->SetFont("", "", 6);
    $formsQuery = "SELECT * FROM interim_form,user WHERE user.user_id=interim_form.submitted_by AND interim_form.form_id='$form_id' AND interim_form.status=1";
    $form_data = DB::getInstance()->querySample($formsQuery);
    foreach ($form_data as $data) {
        $appraiser = DB::getInstance()->DisplayTableColumnValue("SELECT CONCAT(fname,' ',lname) AS appraiser_name FROM user WHERE user_id='$data->appraiser'", "appraiser_name");
        $pdf->SetWidths(array(30, 65, 30, 65));
        $pdf->Row(array("Employee:", "$data->fname    $data->lname ", "International Line:", "$data->international_line"));
        $pdf->Row(array("Sector:", "$data->sector", "Office:", getConfigValue("office_location")));
        $pdf->Row(array("Professional Background:", "$data->professional_background", "Professional Qualification:", "$data->professional_qualification"));
        $pdf->Row(array("Date started in current Grade:", "$data->date_started", "Appraisal Year:", "$data->appraisal_year"));
        $pdf->Row(array("Appraiser:", "$appraiser", "Interim Performance Date:", "$data->interim_performance_date"));


        $pdf->SetFont("", "B", 8);
        $pdf->Cell(0, 10, "Part 2: INTERIM PERFORMANCE REVIEW", 0, 1, "L");
        $pdf->SetFont("", "", 6);
        $pdf->write(4, "The Appraisee and Appraiser must complete a summary of performance against each role relevant competency block making reference to Assignment Performance and Development Reviews completed during the first 6 months of Season N, where applicable. The Appraisee completes his/her comments before sending the form to the Appraiser who will then complete his/her remarks prior to the Interim Performance and Development Review Meeting. Reference should be made to the appropriate level of the Competency Matrix.\n\n");
        $pdf->Cell(40, 5, "", 1, 0, "");
        $pdf->Cell(150, 5, "Interim Performance Assessment", 1, 1, "L");

        $pdf->SetWidths(array(40, 75, 75));
        $pdf->Row(array(
            "What must I achieve?",
            "Appraisee's comments on performance against each competency block",
            "Appraiser's comments on performance against each competency block"
        ));

        $interim_performance_review = unserialize($data->interim_performance_review);
        for ($i = 0; $i < count($interim_competency_block_array); $i++) {
            $pdf->Row(array($interim_competency_block_array[$i], $interim_performance_review['appraisee_comments'][$i], $interim_performance_review['appraiser_comments'][$i]));
        }
        $pdf->SetFont("", "B", 8);
        $pdf->Cell(0, 5, "Part 3: Interim Development Review and Planning", 0, 1, "");
        $pdf->SetFont("", "", 7);
        $pdf->Cell(0, 5, "Part 3.1: Interim Review of Development Objectives", 0, 1, "");
        $pdf->SetFont("", "", 6);
        $pdf->Write(4, "The Appraisee must record development actions carried out to meet the development objectives agreed at the start of Season N and give comments before sending the form to the Appraiser. The Appraiser must also comment on achievement against developmental objectives prior to the Interim Performance and Development Review Meeting. Reference should be made to the appropriate level of the Competency Matrix.\n");
        $pdf->SetFont("", "B");

        $pdf->SetWidths(array(35, 35, 15, 35, 35, 35));
        $pdf->Row(array("Developmental Objectives", "Development Actions", "Timeframe:", "KPIs", "Appraisee's comments", "Appraiser's comments"));
        $pdf->SetFont("", "");
        $interim_development_review = unserialize($data->interim_development_review);
        for ($i = 0; $i < count($interim_development_review['objectives']); $i++) {
            $pdf->Row(
                array(
                    $interim_development_review['objectives'][$i],
                    $interim_development_review['actions'][$i],
                    $interim_development_review['time_frame'][$i],
                    $interim_development_review['kpis'][$i],
                    $interim_development_review['appraisee_comments'][$i],
                    $interim_development_review['appraiser_comments'][$i]
                )
            );
        }
        $pdf->SetFont("", "", 7);
        $pdf->Cell(0, 5, "Part 3.2: Career Aspirations", 0, 1, "");
        $pdf->SetFont("", "", 6);
        $pdf->Write(4, "The Appraisee and Appraiser must review career aspirations to ensure that they are still appropriate following the Interim Performance and Development Review Meeting. Any adjustments to career aspirations should be recorded below.\n");
        $pdf->SetFont("", "B");
        $pdf->SetWidths(array(45, 45, 20, 40, 40));
        $pdf->Row(array("Career Aspirations (inc mobility)", "Development Actions", "Timeframe:", "Help Needed", "Appraiser's comments"));
        $pdf->SetFont("", "");
        $career_aspirations = unserialize($data->career_aspirations);
        for ($i = 0; $i < count($career_aspirations['names']); $i++) {
            $pdf->Row(
                array(
                    $career_aspirations['names'][$i],
                    $career_aspirations['actions'][$i],
                    $career_aspirations['time_frame'][$i],
                    $career_aspirations['help_needed'][$i],
                    $career_aspirations['appraiser_comments'][$i]
                )
            );
        }
        $pdf->SetFont("", "", 7);
        $pdf->Cell(0, 5, "Part 3.3: Development Plan", 0, 1, "");
        $pdf->SetFont("", "", 6);
        $pdf->Write(4, "The Appraisee and Appraiser should review the development plan set at the beginning of Season N to ensure that it is still appropriate following the Interim Performance and Development Review Meeting. Any adjustments to the development plan should be recorded below. Reference should be made to the appropriate level of the Competency Matrix.\n");
        $pdf->SetFont("", "B");
        $pdf->Row(array("Development Requirements", "Development Actions", "Timeframe:", "Help Needed", "Appraiser Feedback"));
        $pdf->SetFont("", "");
        $development_plan = unserialize($data->development_plan);
        for ($i = 0; $i < count($development_plan['requirements']); $i++) {
            $pdf->Row(array(
                $development_plan['requirements'][$i],
                $development_plan['actions'][$i],
                $development_plan['time_frame'][$i],
                $development_plan['help_needed'][$i],
                $development_plan['appraiser_feedback'][$i]
            ));
        }
        $pdf->SetFont("", "B");
        $pdf->SetWidths(array(190));
        $pdf->Write(4, "\nAppraisee's Final Comments on Career Aspirations and Development Plan\n");
        $pdf->SetFont("", "");
        $pdf->Row(array("$data->appraisee_final_comments"));
        $pdf->SetFont("", "B");
        $pdf->Write(4, "\nAppraisee's Final Comments on Career Aspirations and Development Plan\n");
        $pdf->SetFont("", "");
        $pdf->Row(array( "$data->appraiser_final_comments"));
        $pdf->SetFont("", "B", 8);
        $pdf->Cell(0, 5, "Part 4: INTERIM APPRAISAL SUMMARY", 0, 1, "");
        $pdf->SetFont("", "", 7);
        $pdf->Cell(0, 5, "Part 4.1: Appraiser's summary", 0, 1, "");
        $pdf->SetFont("", "", 6);
        $pdf->Write(4, "\nThe Appraiser must give an overall summary of performance during the first 6 months of the Season, highlighting areas of good performance, with examples, and areas where further development is required\n");
        $pdf->Row(array( "$data->interim_appraisal_summary"));
        $pdf->SetFont("", "", 7);
        $pdf->Cell(0, 5, "\n\nPart 4.2: Final comments by Appraisee", 0, 1, "");
        $pdf->SetFont("", "", 6);
        $pdf->Write(4, "Do you agree with the interim appraisal? If not, you must give comments below.\n");
        $pdf->Row(array( "$data->appraisee_agreement_comments"));

        $pdf->Write(4, "\nSignatories\n");
        $pdf->SetWidths(array(30,50,30,80));
        $pdf->Row(array("","Signature","Date", "Comment"));
        $pdf->Row(array( "Appraisee","$data->appraisee_signature","$data->appraisee_date","",));
        $pdf->Row(array("Appraiser", "$data->appraiser_signature","$data->appraiser_date", ""));
        $pdf->Row(array("HR", "$data->hr_signature","$data->hr_date","$data->hr_comment"));
    }
    DB::getInstance()->insert("logs", array("user_id" => $user_id, "log_action" => "printed interim form ID $form_id"));
    $pdf->AutoPrint();
    $pdf->Output();
} else if (isset($_GET['type']) && ($_GET['type'] == "payslip")) {
    $data_sent = unserialize($crypt->decode($_GET['data_sent']));
    $name = $data_sent['emp_name'];
    $user_id = $data_sent['user_id'];
    $month = $data_sent['month'];
    $year = $data_sent['year'];
    $current_month_and_year = $year . '-' . $month;
    $organisation_id = $_SESSION['system_organisation_id'];
    $pdf->SetLineWidth(0.1);
    $pdf->SetAutoPageBreak(false, 0);
    $pdf->AliasNbPages();
    $pdf->isFinished = true;
    $default_y = 34;
    $y = $default_y;
    $default_footer = 19;
    $baris = 1;
    $subtotal = 0;
    $no_footer = TRUE;
    $pdf->SetTopMargin(1);
    $pdf->SetLeftMargin(1);
    $pdf->SetRightMargin(1);
    $pdf->setXY(10, $y);
    $pdf->AddPage('P', 'struck');
    $pdf->SetTextColor(0, 0, 0);
    $pdf->createReceiptHeader($organisation_id);
    $pdf->SetFont("Courier", "B", 3);
    $pdf->Cell(0, 2, "EMPLOYEE PAYSLIP FOR " . english_months($month) . " " . $year . " ", 0, 1, "C");
    $pdf->SetTextColor(0, 0, 0);
    $pdf->SetDash(0.2, 0.5);



    $usersQuery = "SELECT * FROM user WHERE user_id='$user_id' AND user.status=1";
    $usersList = DB::getInstance()->querySample($usersQuery);
    foreach ($usersList as $users) :

        $pdf->SetFont("Courier", "", 2.5);
        $pdf->Cell(5, 2, "Name", 1, 0, "L");
        $pdf->SetFont("Courier", "B", 3);
        $pdf->Cell(28, 2, "$name", 1, 1, "L");
        $pdf->SetFont("Courier", "", 2.5);
        $pdf->Cell(5, 2, "TIN", 1, 0, "L");
        $pdf->SetFont("Courier", "B", 3);
        $pdf->Cell(28, 2, "$users->tin", 1, 1, "L");
        $pdf->SetFont("Courier", "", 2.5);
        $pdf->Cell(0, 1.5, "", 0, 1);

        $benefit_received = 0;
        $allowancesQuery = "SELECT * FROM allowances WHERE status=1";
        $scaleQuery = "SELECT * FROM salary_scale WHERE user_id='$users->user_id' AND substr(date_from,1,7)<='$current_month_and_year' ORDER BY id DESC LIMIT 1";
        $salaryScale = DB::getInstance()->querySample($scaleQuery)[0];
        $salary_scale_id = $salaryScale->id;
        $basic_salary = $salaryScale->basic_salary;
        $bonus_pay = $salaryScale->bonus_pay;
        $net_salary = $salaryScale->net_salary;
        $gross_income = $basic_salary + $bonus_pay;
        $local_service_tax = $salaryScale->local_service_tax;
        $local_service_tax = (in_array($month, $local_service_taxed_months)) ? $local_service_tax : "";
        $deductions = unserialize($salaryScale->allowable_deductions);
        if ($users->payroll_type == 'Salary Scale') {
            for ($i = 0; $i < count($allowable_deductions_array); $i++) {
                if ($allowable_deductions_array[$i] == "Benefit Received") {
                    $benefit_received = $amt = ($deductions['name'][$allowable_deductions_array[$i]] == $allowable_deductions_array[$i]) ? $deductions['amount'][$allowable_deductions_array[$i]] : "";
                }
            }
        } else {
            $benefitsQuery = "SELECT (vehicle_benefit+housing_benefit+utility_benefit+domestic_assistant_benefit) AS benefits FROM workable_benefit WHERE user_id='$users->user_id' AND substr(date_from,1,7)<='$current_month_and_year' AND status=1 ORDER BY workable_benefit_id DESC LIMIT 1";
            $benefit_received = DB::getInstance()->DisplayTableColumnValue($benefitsQuery, "benefits");
            $gross_income = round(($net_salary / 0.65) - 150769);
        }
        $taxable_benefit = round($benefit_received / 0.65);
        $taxable_benefit = ($taxable_benefit) ? $taxable_benefit : "";

        $gross_income += $taxable_benefit;
        $pdf->SetLeftMargin(5.8);
        $pdf->SetFont("Courier", "", 2);
        $pdf->Cell(15, 2, "Basic Salary", 1, 0, "L");
        $pdf->Cell(13, 2, customNumberFormat($basic_salary), 1, 1, "R");

        $pdf->Cell(15, 2, "Bonus Pay", 1, 0, "L");
        $pdf->Cell(13, 2, customNumberFormat($bonus_pay), 1, 1, "R");

        $pdf->Cell(15, 2, "Taxable Benefit", 1, 0, "L");
        $pdf->Cell(13, 2, customNumberFormat($taxable_benefit), 1, 1, "R");

        $allowancesList = DB::getInstance()->querySample($allowancesQuery);
        foreach ($allowancesList as $allowances) {
            if ($users->payroll_type == 'Salary Scale') {
                $gross_income += $allowance_amount = DB::getInstance()->DisplayTableColumnValue("SELECT allowance_amount FROM allowance_expected WHERE scale_id='$salary_scale_id' AND allowance_id='$allowances->id' AND status=1", "allowance_amount");
            } else {
                $allowance_amount = "";
            }
            $pdf->Cell(15, 2, "$allowances->name", 1, 0, "L");
            $pdf->Cell(13, 2, customNumberFormat($allowance_amount), 1, 1, "R");
        }
        $paye = calculateEmployeeTax($gross_income, $benefit_received)['paye'];
        $nssf_5percent = calculateEmployeeTax($gross_income, $benefit_received)['nssf_5percent'];
        $nssf_10percent = calculateEmployeeTax($gross_income, $benefit_received)['nssf_10percent'];
        $chargeable_income = $gross_income - $local_service_tax;

        $pdf->Cell(15, 2, "Gross Income", 1, 0, "L");
        $pdf->Cell(13, 2, customNumberFormat($gross_income), 1, 1, "R");
        $pdf->Cell(15, 2, "Local Service Tax", 1, 0, "L");
        $pdf->Cell(13, 2, customNumberFormat($local_service_tax), 1, 1, "R");
        $pdf->Cell(15, 2, "Chargeable Income", 1, 0, "L");
        $pdf->Cell(13, 2, customNumberFormat($chargeable_income), 1, 1, "R");
        $pdf->Cell(15, 2, "PAYE", 1, 0, "L");
        $pdf->Cell(13, 2, customNumberFormat($paye), 1, 1, "R");
        $pdf->Cell(15, 2, "5%NSSF employee contribution", 1, 0, "L");
        $pdf->Cell(13, 2, customNumberFormat($nssf_5percent), 1, 1, "R");
        $pdf->Cell(28, 2, "Deductions", 1, 1, "L");


        $total_deductions = $paye + $nssf_5percent;
        for ($i = 0; $i < count($allowable_deductions_array); $i++) {
            $amt = ($deductions['name'][$allowable_deductions_array[$i]] == $allowable_deductions_array[$i]) ? $deductions['amount'][$allowable_deductions_array[$i]] : "";
            $amt = ($users->payroll_type == 'Workback' && $allowable_deductions_array[$i] == "Benefit Received") ? $benefit_received : $amt;
            $total_deductions += $amt;
            $pdf->Cell(15, 2, $allowable_deductions_array[$i], 1, 0, "L");
            $pdf->Cell(13, 2, customNumberFormat($amt), 1, 1, "R");
        }

        $pdf->SetFont("Courier", "B", 2.5);
        $pdf->Cell(15, 2, "Total Deductions", 1, 0, "L");
        $pdf->Cell(13, 2, customNumberFormat($total_deductions), 1, 1, "R");
        $pdf->Cell(15, 2, "Net Salary", 1, 0, "L");
        $pdf->Cell(13, 2, ($users->payroll_type == 'Workback') ? $net_salary : (customNumberFormat($chargeable_income - $total_deductions)), 1, 1, "R");
        $pdf->SetFont("Courier", "", 2);
        $pdf->Cell(15, 2, "10%NSSF employer contribution", 1, 0, "L");
        $pdf->Cell(13, 2, customNumberFormat($nssf_10percent), 1, 1, "R");
        $pdf->Cell(15, 2, "15%NSSF total contribution", 1, 0, "L");
        $pdf->Cell(13, 2, customNumberFormat($nssf_5percent + $nssf_10percent), 1, 1, "R");
    endforeach;

    $pdf->SetFont("Courier", "", 3);
    $pdf->Cell(0, 4, "", 0, 1);
    $pdf->Cell(15, 2, "Prepared by:", 0, 0, "L");
    $pdf->Cell(13, 2, "Signature", 0, 1, "R");
    $pdf->Cell(15, 2, $_SESSION['user_full_names'], 0, 0, "L");
    $pdf->Cell(13, 2, ".........", 0, 1, "R");
    if ($baris >= $default_footer) {
        $pdf->AddPageNew();
        $pdf->SetDash(0.2, 0.5);
        $baris = 1;
        $y = 0;
    }
    $y += 17;
    $pdf->SetXY(1, $pdf->GetY());
    $pdf->SetDash(0.2, 0.5);
    //$pdf->line(1, $y, ($pdf->GetPageWidth()-1), $pdf->GetY());
    if ($baris >= $default_footer) {
        $pdf->AddPageNew();
    } else {
        $y += 3;
    }
    $pdf->output('D', 'EMPLOYEE PAYSLIP-' . date("Ymdhis") . '.pdf');
    //$pdf->AutoPrint();
    //$pdf->Output();
}
