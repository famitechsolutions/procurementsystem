<?php

##################################
###           ACTIONS          ###
##################################
if (isset($_POST['action'])) {
    $user_id = $_SESSION['system_user_id'];
    $status = "";
    $message = "";
    $data = $_POST;
    switch ($_POST['action']) {

            //settings
        case "generalSettings":
            $settings = Input::get("settings");
            $company_logo_name = $_FILES["logo"]["name"];
            $company_logo_tmp = $_FILES["logo"]["tmp_name"];
            $company_favicon_name = $_FILES["favicon"]["name"];
            $company_favicon_tmp = $_FILES["favicon"]["tmp_name"];
            if ($company_logo_name != "") {
                $extension = end(explode(".", $company_logo_name));
                $file_url = "uploads/company_logo." . $extension;
                unlink($file_url);
                move_uploaded_file($company_logo_tmp, $file_url);
                DB::getInstance()->updateSetting("company_logo", $file_url);
                DB::getInstance()->insert("logs", array("user_id" => $user_id, "log_action" => "changed system logo"));
            }
            if ($company_favicon_name != "") {
                $extension = end(explode(".", $company_favicon_name));
                $file_url = "uploads/company_favicon." . $extension;
                unlink($file_url);
                move_uploaded_file($company_favicon_tmp, $file_url);
                DB::getInstance()->updateSetting("company_favicon", $file_url);
            }
            foreach ($settings as $setting => $value) {
                DB::getInstance()->updateSetting("$setting", $value);
            }
            DB::getInstance()->insert("logs", array("user_id" => $user_id, "log_action" => "changed system settings"));
            $status = 'success';
            $message = 'General settings updated successfully';
            break;

        case "emailSettings":
            //isAuthorized("manageSettings");
            DB::getInstance()->updateSetting("email_from_address", $_POST['email_from_address']);
            DB::getInstance()->updateSetting("email_from_name", $_POST['email_from_name']);
            DB::getInstance()->updateSetting("email_smtp_host", $_POST['email_smtp_host']);
            DB::getInstance()->updateSetting("email_smtp_port", $_POST['email_smtp_port']);
            DB::getInstance()->updateSetting("email_smtp_username", $_POST['email_smtp_username']);
            DB::getInstance()->updateSetting("email_smtp_password", $_POST['email_smtp_password']);
            DB::getInstance()->updateSetting("email_smtp_security", $_POST['email_smtp_security']);
            DB::getInstance()->updateSetting("email_smtp_auth", isset($_POST['email_smtp_auth']) ? 'true' : 'false');
            DB::getInstance()->updateSetting("email_smtp_enable", isset($_POST['email_smtp_enable']) ? 'true' : 'false');
            //DB::getInstance()->updateSetting("email_smtp_domain", $_POST['email_smtp_domain']);
            //DB::getInstance()->updateSetting("imap_encryption", $_POST['imap_encryption']);
            //DB::getInstance()->updateSetting("imap_server_address", $_POST['imap_server_address']);

            $settings = Input::get("settings");
            foreach ($settings as $setting => $value) {
                DB::getInstance()->updateSetting("$setting", $value);
            }
            DB::getInstance()->insert("logs", array("user_id" => $user_id, "log_action" => "changed system settings"));
            $message = 'Settings updated successfully.';
            $status = 'success';
            break;
        case 'addDepartment':
            $department_name = Input::get("department_name");
            if (!DB::getInstance()->checkRows("SELECT department_id FROM department WHERE department_name='$department_name' AND status=1")) {
                DB::getInstance()->insert('department', array('department_name' => $department_name));
                $dept_id = DB::getInstance()->getName("department", $department_name, "department_id", "department_name");
                $folder_id = DB::getInstance()->insert("folder", array("folder_name" => $department_name, "department_id" => $dept_id));
                DB::getInstance()->update("folder", $folder_id, array("folder_url" => $folder_id . "/"), "folder_id");
                if (!file_exists("uploads/folders/" . $folder_id)) {
                    @mkdir("uploads/folders/" . $folder_id);
                    @chmod("uploads/folders/" . $folder_id, 0777);
                }
            }
            break;
        case 'editUserProfile':
            $data = $_POST;
            $array = array(
                'fname' => $data['fname'],
                'lname' => $data['lname'],
                'theme' => $data['theme'],
                'sidebar' => $data['sidebar'],
                'dob' => ($data['dob']) ? $data['dob'] : NULL,
                'national_id' => $data['national_id'],
                'user_phone' => $data['user_phone'],
                'designation' => $data['designation'],
                'gender' => $data['gender'],
                'employee_number' => $data['employee_number'],
                'layout' => $data['layout']
            );
            if ($data['password'] && $data['password'] == $data['confirm_password']) {
                $array['password'] = sha1($data['password']);
            }
            $attachment_name = $_FILES["profile_picture"]["name"];
            $attachment_tmp = $_FILES["profile_picture"]["tmp_name"];
            if ($attachment_name != "") {
                $extension = end(explode(".", $attachment_name));
                $image = DB::getInstance()->getName("user", $data['user_id'], "photo", "user_id");
                if ($image) {
                    unlink("uploads/user_profiles/" . $image);
                }
                $file_name = $data['fname'] . '_' . $data['lname'] . '_' . date("Ymdh") . "." . $extension;
                $file_url = "uploads/user_profiles/" . $file_name;
                move_uploaded_file($attachment_tmp, $file_url);
                $array['photo'] = $file_url;
                if ($user_id == $data['user_id'] && $attachment_name != "") {
                    $_SESSION['user_profile_picture'] = $file_url;
                }
            }
            DB::getInstance()->update('user', $data['user_id'], $array, 'user_id');
            if ($user_id == $data['user_id']) {
                $_SESSION['user_full_names'] = $data['fname'] . ' ' . $data['lname'];
            }
            DB::getInstance()->insert("logs", array("user_id" => $user_id, "log_action" => "changed user information for user id " . $data['user_id']));

            break;
        case 'editUser':
            $array = array(
                'fname' => $data['fname'],
                'lname' => $data['lname'],
                'role_id' => $data['role_id'] ? $data['role_id'] : NULL,
                'client_id' => ($data['client_id']) ? $data['client_id'] : NULL,
                'department_id' => $data['department_id'] ? $data['department_id'] : NULL,
                'grade_id' => $data['grade_id'] ? $data['grade_id'] : NULL,
                'date_started' => $data['date_started'],
                'theme' => $data['theme'],
                'sidebar' => $data['sidebar'],
                'layout' => $data['layout'],
                'designation' => $data['designation'],
                'gender' => $data['gender'],
                'employee_number' => $data['employee_number'],
                'appointment_type' => $data['appointment_type'],
                'is_approved' => 1
            );
            if ($data['password'] != '') {
                $array['password'] = sha1($data['password']);
            }
            DB::getInstance()->update('user', $data['id'], $array, 'user_id');

            $message = 'User updated successfully';
            $status = 'success';
            break;
        case 'saveSuppliers':
            $edit_supplier = $_POST['edit_supplier'];
            $email = $_POST['email'];
            $data = array('name' => $_POST['name'], 'address' => $_POST['address'], 'contactnumber' => $_POST['contact_number'], 'phone' => $_POST['phone'], 'email' => $email, 'web' => $_POST['web'], 'notes' => $_POST['notes']);
            if ($edit_supplier) {
                DB::getInstance()->update('suppliers', $edit_supplier, $data, 'id');
            } else {
                if (!DB::getInstance()->checkRows("select * from suppliers where email='$email'")) {
                    DB::getInstance()->insert('suppliers', $data);
                } else {
                    DB::getInstance()->update('suppliers', $edit_supplier, $data, 'id');
                }
            }
            DB::getInstance()->insert("logs", array("user_id" => $user_id, "log_action" => "changed suppliers information"));
            $status = 'success';
            $message = 'Supplier(s) uploaded successfully';
            break;
        case 'uploadFile':
            $files = $_FILES;
            $total = count($files['file']['name']);

            for ($i = 0; $i < $total; $i++) {

                $targetdir = "uploads/files/";

                $nextfileid = 'File-' . date('Ymdhis') . '-' . $i;
                $filename = $nextfileid . "-" . basename($files["file"]["name"][$i]);

                $extension = end(explode(".", $files["file"]["name"][$i]));
                $key = strtolower($extension);
                $file_type = (array_key_exists($key, $file_extensions)) ? $file_extensions[$key] : 'code';

                if (empty($data['name'])) {
                    $emptyfilename = true;
                    $data['name'] = $filename;
                }

                $targetfile = $targetdir . $filename;

                if (!file_exists($targetfile)) {
                    if (move_uploaded_file($files["file"]["tmp_name"][$i], $targetfile)) {
                        DB::getInstance()->insert("file", [
                            "client_id" => $data['client_id'] ? $data['client_id'] : NULL,
                            "project_id" => $data['project_id'] ? $data['project_id'] : NULL,
                            "asset_id" => $data['asset_id'] ? $data['asset_id'] : NULL,
                            "ticketreply_id" => $data['ticketreply_id'] ? $data['ticketreply_id'] : NULL,
                            "requisition_id" => $data['requisition_id'] ? $data['requisition_id'] : NULL,
                            "lpo_id" => $data['lpo_id'] ? $data['lpo_id'] : NULL,
                            "file_name" => $data['name'],
                            "file_url" => $filename,
                            "file_extension" => $extension,
                            "file_type" => $file_type
                        ]);
                    }
                }



                if ($emptyfilename) {
                    $data['name'] = "";
                }
            }
            $message = "Files uploaded successfully";
            $status = "success";
            break;
        case 'addRequisition':
            $unique_number = date("Ymdhis");
            if ($data['direct_approver']) {
                $id = DB::getInstance()->insert('requisition', [
                    'date_submitted' => ($data['date_submitted']) ? $data['date_submitted'] : $date_today,
                    'unique_number' => $unique_number,
                    'department_id' => ($data['department_id']) ? $data['department_id'] : NULL,
                    'user_id' => $user_id,
                    'direct_approver' => ($data['direct_approver']) ? $data['direct_approver'] : NULL,
                    'project_id' => ($data['project_id']) ? $data['project_id'] : NULL,
                    'submitted_by' => $user_id,
                    'requester_signature' => $data['requester_signature'],
                    'reference_number' => $data['reference_number'],
                    'requisition_number' => $data['requisition_number'],
                    'category' => $data['category'] ? $data['category'] : 'requisition',
                    'amount_requested' => ($data['amount_requested']) ? $data['amount_requested'] : 0
                ]);
                if ($id) {
                    foreach ($data['name'] as $i => $name) {
                        if ($name) {
                            DB::getInstance()->insert('requisition_items', [
                                'name' => $name,
                                'quantity_requested' => ($data['quantity'][$i]) ? $data['quantity'][$i] : 0,
                                'unit_measure' => $data['unit_measure'][$i],
                                'unit_price' => ($data['unit_cost'][$i]) ? $data['unit_cost'][$i] : 0,
                                'requisition_id' => $id,
                                'payee' => $data['payee'][$i],
                            ]);
                        }
                    }
                }
                if ($data['direct_approver']) {
                    $approver = DB::getInstance()->getRow("user", $data['direct_approver'], "*", "user_id");
                    $template = DB::getInstance()->getRow("notificationtemplate", "requisition_approval_request", "subject,message", "code");
                    $search = array('{names}', '{requisition_number}', '{requisition_status}', '{comment}', '{company}');
                    $body_replace = array($approver->fname . ' ' . $approver->lname, $request->requisition_number, $request->requisition_status, $data['comment'], $COMPANY_NAME);
                    $message = str_replace($search, $body_replace, $template->message);
    
                    sendEmail($approver->user_email, $approver->fname . ' ' . $approver->lname, $template->subject, $message);
                }
                $status = 'success';
                $message = 'Requisition uploaded';
            } else {
                $status = 'danger';
                $message = 'Requisition not uploaded, no approver selected';
            }
            break;
        case 'approveRequisition':
            $request = DB::getInstance()->getRow("requisition", $data['id'], "*", "id");
            $array = array('requisition_status' => $data['status']);
            $approver_id = '';
            if ($request->requisition_status == 'Requested') {
                $array['amount_directly_approved'] = ($data['amount_approved']) ? $data['amount_approved'] : 0;
                $array['time_directly_approved'] = $date_today . ' ' . date('H:i:s');
                $array['financial_approver_id'] = $approver_id = $data['financial_approver'] ? $data['financial_approver'] : NULL;
                $array['direct_approver_comment'] = $data['comment'];
                $array['direct_approver_signature'] = $data['signature'];
            } else if ($request->requisition_status == 'Directly Approved') {
                $array['financial_approver_amount'] = ($data['amount_approved']) ? $data['amount_approved'] : 0;
                $array['final_approver'] = $approver_id = $data['final_approver'] ? $data['final_approver'] : NULL;
                $array['financial_approver_time'] = $date_today . ' ' . date('H:i:s');
                $array['financial_approver_comment'] = $data['comment'];
                $array['financial_approver_signature'] = $data['signature'];
            } else {
                $array['amount_approved'] = ($data['amount_approved']) ? $data['amount_approved'] : 0;
                $array['time_approved'] = $date_today . ' ' . date('H:i:s');
                $array['approved_by'] = $user_id;
                $array['approval_comment'] = $data['comment'];
                $array['final_approver_signature'] = $data['signature'];
            }



            if ($approver_id || $request->requisition_status == 'Financially Approved') {
                DB::getInstance()->update('requisition', $data['id'], $array, 'id');
                foreach ($data['item_id'] as $i => $item_id) {
                    if ($item_id) {
                        DB::getInstance()->update('requisition_items', $item_id, [
                            $request->requisition_status == 'Requested' ? 'quantity_directly_approved' : ($request->requisition_status == 'Directly Approved' ? 'quantity_financially_approved' : 'quantity_approved') => ($data['quantity'][$i]) ? $data['quantity'][$i] : 0,
                            'unit_price' => ($data['unit_cost'][$i]) ? $data['unit_cost'][$i] : 0
                        ], 'id');
                    }
                }
                if ($approver_id) {
                    $approver = DB::getInstance()->getRow("user", $approver_id, "*", "user_id");
                    $template = DB::getInstance()->getRow("notificationtemplate", "requisition_approval_request", "subject,message", "code");
                    $search = array('{names}', '{requisition_number}', '{requisition_status}', '{comment}', '{company}');
                    $body_replace = array($approver->fname . ' ' . $approver->lname, $request->requisition_number, $request->requisition_status, $data['comment'], $COMPANY_NAME);
                    $message = str_replace($search, $body_replace, $template->message);

                    sendEmail($approver->user_email, $approver->fname . ' ' . $approver->lname, $template->subject, $message);
                }
                $status = 'success';
                $message = 'Requisition approved';
            } else {
                $status = "danger";
                $message = "requsition not approved, Next Approver not specified";
            }
            break;
        case 'editRequisition':
            $request = DB::getInstance()->getRow("requisition", $data['id'], "*", "id");
            DB::getInstance()->update('requisition', $data['id'], [
                'date_submitted' => ($data['date_submitted']) ? $data['date_submitted'] : $date_today,
                'department_id' => ($data['department_id']) ? $data['department_id'] : 0,
                'project_id' => ($data['project_id']) ? $data['project_id'] : NULL,
                'reference_number' => $data['reference_number'],
                'requisition_number' => $data['requisition_number'],
                'amount_requested' => ($data['amount_requested']) ? $data['amount_requested'] : 0,
                'requisition_status' => $request->requisition_status == 'Directly Rejected' ? 'Requested' : ($request->requisition_status == 'Financially Rejected' ? 'Directly Approved' : ($request->requisition_status == 'Rejected' ? 'Financially Approved' : $request->requisition_status)),
            ], 'id');
            DB::getInstance()->delete("requisition_items", array("requisition_id", "=", $data['id']));
            foreach ($data['name'] as $i => $name) {
                if ($name) {
                    DB::getInstance()->insert('requisition_items', [
                        'name' => $name,
                        'quantity_requested' => ($data['quantity'][$i]) ? $data['quantity'][$i] : 0,
                        'unit_measure' => $data['unit_measure'][$i],
                        'unit_price' => ($data['unit_cost'][$i]) ? $data['unit_cost'][$i] : 0,
                        'requisition_id' => $data['id'],
                        'payee' => $data['payee'][$i],
                    ]);
                }
            }
            $status = 'success';
            $message = 'Requisition updated';
            break;

        case 'rejectRequisition':
            $id = $data['requisition_id'];
            $request = DB::getInstance()->getRow("requisition", $id, "*", "id");
            $status = $request->requisition_status == 'Requested' ? 'Directly Rejected' : ($request->requisition_status == 'Directly Approved' ? 'Financially Rejected' : 'Rejected');
            $array = array(
                'direct_approver_comment' => $request->requisition_status == 'Requested' ? $data['comment'] : $request->direct_approver_comment,
                'financial_approver_comment' => $request->requisition_status == 'Directly Approved' ? $data['comment'] : $request->financial_approver_comment,
                'approval_comment' => $request->requisition_status == 'Financially Approved' ? $data['comment'] : $request->approval_comment,
                'requisition_status' => $status,
            );
            $requester_id = $request->requisition_status == 'Requested' ? $request->user_id : ($request->requisition_status == 'Directly Approved' ? $request->direct_approver : $request->financial_approver_id);
            DB::getInstance()->update('requisition', $id, $array, 'id');
            if ($requester_id) {
                $requester = DB::getInstance()->getRow("user", $requester_id, "*", "user_id");
                $template = DB::getInstance()->getRow("notificationtemplate", "requisition_status_update", "subject,message", "code");
                $search = array('{names}', '{requisition_number}', '{requisition_status}', '{comment}', '{company}');
                $replace = array($requester->fname . ' ' . $requester->lname, $request->requisition_number, $status, $data['comment'], $COMPANY_NAME);
                $message = str_replace($search, $replace, $template->message);
                $subject = str_replace($search, $replace, $template->subject);

                sendEmail($requester->user_email, $requester->fname . ' ' . $requester->lname, $subject, $message);
            }
            break;
        case 'deleteRequisition':
            DB::getInstance()->delete('requisition', array('id', '=', $data['requisition_id']));
            $status = 'warning';
            $message = 'Requisition deleted successfully';
            break;
        case 'addLPO':
            if ($data['lpo_amount'] > 0) {
                $unique_number = date("Ymdhis");
                $lpo_id = DB::getInstance()->insert('lpo', [
                    'delivery_date' => ($data['delivery_date']) ? $data['delivery_date'] : NULL,
                    'serial_number' => ($data['serial_number']) ? $data['serial_number'] : $unique_number,
                    'vendor_details' => $data['vendor'],
                    'user_id' => $user_id,
                    'payment_terms' => $data['payment_terms'],
                    'requisition_id' => ($data['requisition_id']) ? $data['requisition_id'] : 0,
                    'delivery_point' => $data['delivery_point'],
                    'order_date' => ($data['order_date']) ? $data['order_date'] : NULL,
                    'tax' => ($data['percentage_tax']) ? round(($data['percentage_tax'] / 100) * $data['lpo_amount'], 2) : 0
                ]);
                if ($lpo_id) {
                    foreach ($data['item_id'] as $i => $item_id) {
                        if ($item_id) {
                            DB::getInstance()->update('requisition_items', $item_id, ['lpo_id' => $lpo_id], 'id');
                        }
                    }
                }
                $status = 'success';
                $message = 'LPO Generated successfully';
            } else {
                $status = 'danger';
                $message = 'Could not not submit data without total amount';
            }
            break;
        case 'editLPO':
            if ($data['lpo_amount'] > 0) {
                $unique_number = date("Ymdhis");
                DB::getInstance()->update('lpo', $data['lpo_id'], [
                    'delivery_date' => ($data['delivery_date']) ? $data['delivery_date'] : NULL,
                    'serial_number' => $data['serial_number'],
                    'vendor_details' => $data['vendor'],
                    'payment_terms' => $data['payment_terms'],
                    'delivery_point' => $data['delivery_point'],
                    'order_date' => ($data['order_date']) ? $data['order_date'] : NULL,
                    'tax' => ($data['percentage_tax']) ? round(($data['percentage_tax'] / 100) * $data['lpo_amount'], 2) : 0
                ], 'id');
                $status = 'success';
                $message = 'LPO updated successfully';
            } else {
                $status = 'danger';
                $message = 'Could not not submit data without total amount';
            }
            break;
        case 'deleteLPO':
            //DB::getInstance()->update('requisition_items', $data['lpo_id'], ['lpo_id' => NULL], 'lpo_id');
            DB::getInstance()->delete('lpo', array('id', '=', $data['lpo_id']));
            $status = 'warning';
            $message = 'LPO deleted successfully';
            break;
    }
    if ($message != "") {
        $_SESSION["message"] = array('status' => $status, 'message' => $message);
    }
    Redirect::to('?' . $crypt->decode($_POST['reroute']));
    //Redirect::to("index.php?page=" . $_POST['page'] . "&tab=" . $_POST['tab']);
}
