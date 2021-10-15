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
            if (!DB::getInstance()->checkRows("SELECT id FROM department WHERE name='$department_name' AND status=1")) {
                $dept_id = DB::getInstance()->insert('department', array('name' => $department_name));
            }
            break;

        case 'addUser':
            $email = Input::get("email");
            $username = Input::get("username");
            $queryDup = "SELECT * FROM user WHERE (username='$username' OR email='$email') AND status=1";
            if (DB::getInstance()->checkRows($queryDup)) {
                $message = 'User already exists';
                $status = 'danger';
            } else {
                $user_id = DB::getInstance()->insert("user", array(
                    'fname' => $data['fname'],
                    'lname' => $data['lname'],
                    'username' => $data['username'],
                    'email' => $data['email'],
                    'password' => sha1($data['password']),
                    'category' => $data['role'] ? $data['role'] : NULL,
                    'department_id' => $data['department_id'] ? $data['department_id'] : NULL,
                    'gender' => $data['gender'],
                    'designation' => $data['designation'],
                    'address' => $data['address'],
                    'phone' => $data['phone'],
                    'nin' => $data['nin'],
                    'is_verified' => 1
                ));
                if ($user_id) {
                    $message = 'User registered successfully';
                    $status = 'success';
                } else {
                    $message = 'Error while registering new user';
                    $status = 'danger';
                }
            }
            break;
        case "editNotificationTemplate":
            $code = $_POST['code'];
            $data = array('code' => $code, 'name' => $_POST['name'], 'subject' => $_POST['subject'], 'message' => $_POST['message'], 'sms' => $_POST['sms']);
            if (DB::getInstance()->checkRows("SELECT code FROM notificationtemplate WHERE code='$code'")) {
                DB::getInstance()->update('notificationtemplate', $code, $data, 'code');
            } else {
                DB::getInstance()->insert('notificationtemplate', $data);
            }
            $message = 'Template updated successfully.';
            $status = 'success';
            break;
        case 'editUserProfile':
            $data = $_POST;
            $array = array(
                'fname' => $data['fname'],
                'lname' => $data['lname'],
                'dob' => ($data['dob']) ? $data['dob'] : NULL,
                'nin' => $data['nin'],
                'phone' => $data['phone'],
                'designation' => $data['designation'],
                'gender' => $data['gender']
            );
            if ($data['password'] && $data['password'] == $data['confirm_password']) {
                $array['password'] = sha1($data['password']);
            }
            $attachment_name = $_FILES["profile_picture"]["name"];
            $attachment_tmp = $_FILES["profile_picture"]["tmp_name"];
            if ($attachment_name != "") {
                $extension = end(explode(".", $attachment_name));
                $image = DB::getInstance()->getName("user", $data['user_id'], "photo", "id");
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
            DB::getInstance()->update('user', $data['user_id'], $array, 'id');
            if ($user_id == $data['user_id']) {
                $_SESSION['user_full_names'] = $data['fname'] . ' ' . $data['lname'];
            }
            DB::getInstance()->insert("logs", array("user_id" => $user_id, "log_action" => "changed user information for user id " . $data['user_id']));

            break;
            //Register User
        case 'registerUser':
            $email = $data['email'];
            $arr= array(
                'fname' => $data['fname'],
                'lname' => $data['lname'],
                'username' => $data['email'],
                'category' => 'Supplier',
                'gender' => $data['gender'],
                'email' => $data['email'],
                'designation' => $data['designation'],
                'address' => $data['address'],
                'phone' => $data['phone'],
                'nin' => $data['nin'],
                'dob' => $data['dob']?$data['dob']:null,
                'is_verified' => '0',
            );
            if ($data['password'] != '') {
                $arr['password'] = sha1($data['password']);
            }

            //    var_dump($data);
            $registerQuery = "SELECT * FROM user WHERE email='$email'";
            if (!DB::getInstance()->checkRows($registerQuery)) {
                $id = DB::getInstance()->insert('user', $arr);
                $template = DB::getInstance()->getRow("notificationtemplate", "account_activation", "subject,message", "code");
                $search = array('{names}', '{link}', '{company}');
                $link = $SYSTEM_URL . '?page=' . $crypt->encode('activate') . "&user_verification=true&email=$email&user=" . $crypt->encode($id);
                $body_replace = array($data['fname'] . ' ' . $data['lname'], $link, $COMPANY_NAME);
                $msg = str_replace($search, $body_replace, $template->message);
                sendEmail($email, $data['fname'] . ' ' . $data['lname'], $template->subject, $msg);
                $message = 'Supplier account created successfully, check your email to verify and activate your account';
                $status = 'success';
            } else {
                $message = 'Error while Creating new user';
                $status = 'danger';
            }
            break;
            //edit User
        case 'editUser':
            $array = array(
                'fname' => $data['fname'],
                'lname' => $data['lname'],
                'category' => $data['role'] ? $data['role'] : NULL,
                'department_id' => $data['department_id'] ? $data['department_id'] : NULL,
                'gender' => $data['gender'],
                'designation' => $data['designation'],
                'address' => $data['address'],
                'phone' => $data['phone'],
                'nin' => $data['nin'],
                // 'is_approved' => 1
            );
            if ($data['password'] != '') {
                $array['password'] = sha1($data['password']);
            }
            DB::getInstance()->update('user', $data['id'], $array, 'id');

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
                if (empty($data['name'])) {
                    $emptyfilename = true;
                    $data['name'] = $filename;
                }

                $targetfile = $targetdir . $filename;

                if (!file_exists($targetfile)) {
                    if (move_uploaded_file($files["file"]["tmp_name"][$i], $targetfile)) {
                        DB::getInstance()->insert("attachment", [
                            "requisition_id" => $data['requisition_id'] ? $data['requisition_id'] : NULL,
                            "purchase_order_id" => $data['lpo_id'] ? $data['lpo_id'] : NULL,
                            'rfp_id' => $data['rfp_id'] ? $data['rfp_id'] : NULL,
                            "title" => $data['name'],
                            "url" => $filename,
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
            $id = DB::getInstance()->insert('requisition', [
                'date' => ($data['date']) ? $data['date'] : $date_today,
                'department_id' => ($data['department_id']) ? $data['department_id'] : NULL,
                'user_id' => $user_id,
                'requisition_number' => $data['requisition_number'],
                'amount_requested' => ($data['amount_requested']) ? $data['amount_requested'] : 0
            ]);
            if ($id) {
                foreach ($data['item'] as $i => $item) {
                    if ($item) {
                        DB::getInstance()->insert('requisition_item', [
                            'item_id' => $item,
                            'quantity' => ($data['quantity'][$i]) ? $data['quantity'][$i] : 0,
                            'unit_measure' => $data['unit_measure'][$i],
                            'unit_price' => ($data['unit_cost'][$i]) ? $data['unit_cost'][$i] : 0,
                            'requisition_id' => $id,
                        ]);
                    }
                }
            }
            $status = 'success';
            $message = 'Requisition uploaded';
            break;
        case 'approveRequisition':
            $request = DB::getInstance()->getRow("requisition", $data['id'], "*", "id");
            $array = array('requisition_status' => $data['status']);

            $array['approval_time'] = $date_today . ' ' . date('H:i:s');
            $array['approval_by'] = $user_id;
            $array['approval_comment'] = $data['comment'];
            // $array['final_approver_signature'] = $data['signature'];

            DB::getInstance()->update('requisition', $data['id'], $array, 'id');
            // if ($approver_id) {
            //     $approver = DB::getInstance()->getRow("user", $approver_id, "*", "user_id");
            //     $template = DB::getInstance()->getRow("notificationtemplate", "requisition_approval_request", "subject,message", "code");
            //     $search = array('{names}', '{requisition_number}', '{requisition_status}', '{comment}', '{company}');
            //     $body_replace = array($approver->fname . ' ' . $approver->lname, $request->requisition_number, $request->requisition_status, $data['comment'], $COMPANY_NAME);
            //     $message = str_replace($search, $body_replace, $template->message);
            //     sendEmail($approver->user_email, $approver->fname . ' ' . $approver->lname, $template->subject, $message);
            // }
            $status = 'success';
            $message = 'Requisition approved';
            break;
        case 'editRequisition':
            $request = DB::getInstance()->getRow("requisition", $data['id'], "*", "id");
            DB::getInstance()->update('requisition', $data['id'], [
                'date' => ($data['date']) ? $data['date'] : $date_today,
                'department_id' => ($data['department_id']) ? $data['department_id'] : 0,
                'requisition_number' => $data['requisition_number'],
                'amount_requested' => ($data['amount_requested']) ? $data['amount_requested'] : 0,
                'requisition_status' => "Pending",
                'approval_comment' => NULL,
                'approval_by' => NULL,
                'approval_time' => NULL,
            ], 'id');
            DB::getInstance()->delete("requisition_item", array("requisition_id", "=", $data['id']));
            foreach ($data['item'] as $i => $item) {
                if ($item) {
                    DB::getInstance()->insert('requisition_item', [
                        'item_id' => $item,
                        'quantity' => ($data['quantity'][$i]) ? $data['quantity'][$i] : 0,
                        'unit_measure' => $data['unit_measure'][$i],
                        'unit_price' => ($data['unit_cost'][$i]) ? $data['unit_cost'][$i] : 0,
                        'requisition_id' => $data['id'],
                    ]);
                }
            }
            $status = 'success';
            $message = 'Requisition updated';
            break;

        case 'rejectRequisition':
            $id = $data['requisition_id'];
            $array = array(
                'approval_comment' => $data['comment'],
                'approval_by' => $user_id,
                'approval_time' => $date_today . ' ' . date('H:i:s'),
                'requisition_status' => "Rejected",
            );
            DB::getInstance()->update('requisition', $id, $array, 'id');
            break;
        case 'deleteRequisition':
            DB::getInstance()->delete('requisition', array('id', '=', $data['requisition_id']));
            $status = 'warning';
            $message = 'Requisition deleted successfully';
            break;
        case 'addLPO':
            if ($data['lpo_amount'] > 0) {
                $unique_number = date("Ymdhis");
                $lpo_id = DB::getInstance()->insert('purchase_order', [
                    'delivery_date' => ($data['delivery_date']) ? $data['delivery_date'] : NULL,
                    'serial_number' => ($data['serial_number']) ? $data['serial_number'] : $unique_number,
                    'vendor_details' => $data['vendor'],
                    'payment_terms' => $data['payment_terms'],
                    'requisition_id' => ($data['requisition_id']) ? $data['requisition_id'] : 0,
                    'delivery_point' => $data['delivery_point'],
                    'order_date' => ($data['order_date']) ? $data['order_date'] : NULL,
                    'tax' => ($data['percentage_tax']) ? round(($data['percentage_tax'] / 100) * $data['lpo_amount'], 2) : 0
                ]);
                if ($lpo_id) {
                    foreach ($data['item_id'] as $i => $item_id) {
                        if ($item_id) {
                            DB::getInstance()->update('requisition_item', $item_id, ['purchase_order_id' => $lpo_id], 'id');
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
            DB::getInstance()->delete('lpo', array('id', '=', $data['lpo_id']));
            $status = 'warning';
            $message = 'LPO deleted successfully';
            break;

        case 'addRFP':
            $rfp = $data['rfp'];
            $rfp['requisition_id'] = $data['requisition_id'] ? $data['requisition_id'] : null;
            $rfp['user_id'] = $user_id;
            $id = DB::getInstance()->insert('rfp', $rfp);
            if ($id) {
                foreach ($data['item_id'] as $i => $item) {
                    if ($item) {
                        DB::getInstance()->insert('rfp_item', [
                            'item_id' => $item,
                            'quantity' => ($data['quantity'][$item]) ? $data['quantity'][$item] : 0,
                            'rfp_id' => $id,
                            'description' => $data['description'][$item],
                            'status' => 'Pending'
                        ]);
                    }
                }
            }
            $status = 'success';
            $message = 'Request for approval uploaded';
            break;
        case 'editRFP':
            $id = $data['id'];
            $rfp = $data['rfp'];
            $rfp['requisition_id'] = $data['requisition_id'] ? $data['requisition_id'] : null;
            $rfp['user_id'] = $user_id;
            $rfp['rfp_status'] = 'Pending';
            DB::getInstance()->update('rfp', $id, $rfp, 'id');
            if ($id) {
                DB::getInstance()->delete("rfp_item", array("rfp_id", "=", $data['id']));
                foreach ($data['item_id'] as $i => $item) {
                    if ($item) {
                        DB::getInstance()->insert('rfp_item', [
                            'item_id' => $item,
                            'quantity' => ($data['quantity'][$item]) ? $data['quantity'][$item] : 0,
                            'rfp_id' => $id,
                            'description' => $data['description'][$item],
                            'status' => 'Pending'
                        ]);
                    }
                }
            }
            $status = 'success';
            $message = 'Request for approval updated';
            break;
        case 'deleteRFP':
            DB::getInstance()->delete('rfp', array('id', '=', $data['id']));
            $status = 'warning';
            $message = 'Request deleted successfully';
            break;
        case 'openRFP':
            DB::getInstance()->update('rfp', $data['id'], array('rfp_status' => "Open"), 'id');
            $status = 'warning';
            $message = 'Item opened successfully';
            break;
        case 'approveProposal':
            $rfp_id = $data['rfp_id'];
            $proposal_id = $data['proposal_id'];
            $contract = $data['contract'];
            $contract['application_status'] = "Approved";
            DB::getInstance()->update("rfp", $rfp_id, array('rfp_status' => "Complete"), 'id');
            DB::getInstance()->update("contract_application", $rfp_id, array('application_status' => "Rejected"), 'rfp_id');
            DB::getInstance()->update("contract_application", $proposal_id, $contract, 'id');
            $status = 'success';
            $message = 'Proposal approved';
            break;
    }
    if ($message != "") {
        $_SESSION["message"] = array('status' => $status, 'message' => $message);
    }
    Redirect::to('?' . $crypt->decode($_POST['reroute']));
    //Redirect::to("index.php?page=" . $_POST['page'] . "&tab=" . $_POST['tab']);
}
