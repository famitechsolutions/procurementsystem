<!DOCTYPE html>
<html lang="en">

<head>
    <?php require_once 'includes/header.php'; ?>
</head>

<body>
    <div class="container-scroller">
        <!-- partial:../../partials/_navbar.html -->
        <?php require_once 'includes/header_menu.php'; ?>
        <!-- partial -->
        <div class="container-fluid page-body-wrapper">
            <!-- partial:../../partials/_sidebar.html -->
            <?php require_once 'includes/side_menu.php'; ?>
            <!-- partial -->
            <div class="main-panel">
                <div class="content-wrapper">
                    <div class="row">
                        <div class="col-md-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-body">
                                    <?php
                                    $tab = (isset($_GET['tab'])) ? $_GET['tab'] : 'general-tab';
                                    $general_tab_active = ($tab == "general-tab") ? 'active' : '';
                                    $departments_tab_active = ($tab == "departments-tab") ? 'active' : '';
                                    $items_tab_active = ($tab == "items-tab") ? 'active' : '';
                                    $email_tab_active = ($tab == "email-tab") ? 'active' : '';
                                    $templates_tab_active = ($tab == "templates-tab") ? 'active' : '';
                                    ?>
                                    <ul class="nav nav-tabs" role="tablist">
                                        <li class="nav-item "><a class="<?php echo $general_tab_active ?> nav-link" href="#general-tab" data-toggle="tab">General</a></li>
                                        <li class="nav-item "><a class="<?php echo $departments_tab_active ?> nav-link" href="#departments-tab" data-toggle="tab">Departments</a></li>
                                        <li class="nav-item "><a class="<?php echo $items_tab_active ?> nav-link" href="#items-tab" data-toggle="tab">Items</a></li>
                                        <li class="nav-item "><a class="<?php echo $email_tab_active ?> nav-link" href="#email-tab" data-toggle="tab">Emails</a></li>
                                        <li class="nav-item "><a class="<?php echo $templates_tab_active ?> nav-link" href="#templates-tab" data-toggle="tab">Notification Templates</a></li>
                                    </ul>
                                    <div class="tab-content">

                                        <div class="tab-pane <?php echo $general_tab_active ?>" id="general-tab">
                                            <form method="POST" action="" enctype="multipart/form-data">
                                                <div class="row form-group mb-2">
                                                    <div class="col-md-6">
                                                        <label>Site Name</label>
                                                        <input type="text" class="form-control" name="settings[site_name]" value="<?php echo getConfigValue("site_name") ?>" required>
                                                        <label>Site URL</label>
                                                        <input type="text" class="form-control" name="settings[site_url]" value="<?php echo getConfigValue("site_url") ?>" required>
                                                        <div class="form-group">
                                                            <label style="width:100%"><?php _e('Logo'); ?></label>
                                                            <img style="max-height:30px" src="<?php echo $logo ?>" alt="">
                                                            <input class="form-control" value="<?php echo getConfigValue("password_generator_length"); ?>" type="file" accept="image/*" name="logo">
                                                        </div>
                                                        <div class="form-group">
                                                            <label style="width:100%"><?php _e('Favicon (<small>this logo will appear in the browser title</small>)'); ?></label>
                                                            <img style="max-height:30px" src="<?php echo $logo_small ?>" alt="">
                                                            <input class="form-control" value="<?php echo getConfigValue("password_generator_length"); ?>" type="file" accept="image/*" name="favicon">
                                                        </div>

                                                        <label>Company description</label>
                                                        <textarea class="form-control" name="settings[company_description]"><?php echo getConfigValue("company_description") ?></textarea>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <label>Company Name</label>
                                                        <input type="text" class="form-control" name="settings[company_name]" value="<?php echo getConfigValue("company_name") ?>" required>
                                                        <label>Office (Location)</label>
                                                        <input type="text" class="form-control" name="settings[office_location]" value="<?php echo getConfigValue("office_location") ?>" required>
                                                        <label><?php _e('Password Generator Length'); ?></label>
                                                        <input class="form-control" value="<?php echo getConfigValue("password_generator_length"); ?>" min="1" type="number" name="settings[password_generator_length]">
                                                        <div class="form-group">
                                                            <label><?php _e('Percentage Tax <small>[E.g 18]</small>'); ?></label>
                                                            <input class="form-control" value="<?php echo getConfigValue("percentage_tax"); ?>" type="number" min="0" max="100" step="0.01" name="settings[percentage_tax]">
                                                        </div>
                                                        <div class="row form-group">
                                                            <div class="col-md-6">
                                                                <label>Currency Symbol</label>
                                                                <input class="form-control" value="<?php echo getConfigValue("currency_symbol"); ?>" name="settings[currency_symbol]">
                                                            </div>
                                                            <div class="col-md-6">
                                                                <label>Symbol Location</label>
                                                                <select class="form-control" name="settings[currency_symbol_location]">
                                                                    <option <?php echo (getConfigValue("currency_symbol_location") == "Left") ? 'selected' : ''; ?> value="Left">Left</option>
                                                                    <option <?php echo (getConfigValue("currency_symbol_location") == "Right") ? 'selected' : ''; ?> value="Right">Right</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <input type="hidden" name="action" value="generalSettings">
                                                <input type="hidden" name="reroute" value="<?php echo $crypt->encode('page=' . $_GET['page'] . '&tab=general-tab'); ?>">
                                                <button type="submit" class="btn btn-success">Save</button>
                                            </form>
                                        </div>
                                        <div class="tab-pane <?php echo $departments_tab_active ?>" id="departments-tab">
                                            <div class="">
                                                <a href="#new-department-modal" data-toggle="modal" class="btn btn-primary btn-sm"><?php _e('Add new department'); ?></a>
                                                <div class="modal fade" id="new-department-modal" tabindex="-1" role="dialog" aria-labelledby="ultraModal-Label" aria-hidden="true">
                                                    <div class="modal-dialog animated fadeInDown">
                                                        <div class="modal-content animated">
                                                            <form action="" method="POST">
                                                                <div class="modal-header">
                                                                    <h4 class="modal-title">New department</h4>
                                                                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span>
                                                                        <span class="sr-only">Close</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="form-group">
                                                                        <label>Department Name</label>
                                                                        <input type="text" class="form-control" name="department_name" required>
                                                                    </div>

                                                                </div>
                                                                <div class="modal-footer">
                                                                    <input type="hidden" name="reroute" value="<?php echo $crypt->encode('page=' . $_GET['page'] . '&tab=departments-tab'); ?>">
                                                                    <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
                                                                    <button type="submit" name="action" value="addDepartment" class="btn btn-primary">Submit</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                <table id="table" class="table table-bordered" data-toggle="table" data-pagination="true" data-search="true" data-show-columns="true" data-show-pagination-switch="true" data-key-events="true" data-show-toggle="true" data-resizable="true" data-cookie="true" data-cookie-id-table="saveId" data-show-export="true" data-click-to-select="true" data-toolbar="#toolbar">
                                                    <thead>
                                                        <tr>
                                                            <th>Name</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $departmentsQuery = "SELECT * FROM department ORDER BY name";
                                                        $departmentsList = DB::getInstance()->querySample($departmentsQuery);
                                                        foreach ($departmentsList as $department) {
                                                        ?>
                                                            <tr>
                                                                <td><?php echo $department->name ?></td>
                                                                <td></td>
                                                            </tr>
                                                        <?php }
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="tab-pane <?php echo $items_tab_active ?>" id="items-tab">
                                            <div class="">
                                                <a href="#new-item-modal" data-toggle="modal" class="btn btn-primary btn-sm"><?php _e('Add new item'); ?></a>
                                                <div class="modal fade" id="new-item-modal" tabindex="-1" role="dialog" aria-labelledby="ultraModal-Label" aria-hidden="true">
                                                    <div class="modal-dialog modal-sm animated fadeInDown">
                                                        <div class="modal-content animated">
                                                            <form action="" method="POST">
                                                                <div class="modal-header">
                                                                    <h4 class="modal-title">New item</h4>
                                                                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span>
                                                                        <span class="sr-only">Close</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="form-group">
                                                                        <label>Name</label>
                                                                        <input type="text" class="form-control" name="name" required>
                                                                        <label>Unit Measure</label>
                                                                        <input type="text" class="form-control" name="unit_measure">
                                                                    </div>

                                                                </div>
                                                                <div class="modal-footer">
                                                                    <input type="hidden" name="reroute" value="<?php echo $crypt->encode('page=' . $_GET['page'] . '&tab=items-tab'); ?>">
                                                                    <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
                                                                    <button type="submit" name="action" value="addItem" class="btn btn-primary">Submit</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                                <table id="table" class="table table-bordered" data-toggle="table" data-pagination="true" data-search="true" data-show-columns="true" data-show-pagination-switch="true" data-key-events="true" data-show-toggle="true" data-resizable="true" data-cookie="true" data-cookie-id-table="saveId" data-show-export="true" data-click-to-select="true" data-toolbar="#toolbar">
                                                    <thead>
                                                        <tr>
                                                            <th>Name</th>
                                                            <th>Unit Measure</th>
                                                            <th>Unit Price</th>
                                                            <th>Supplier <small>[From contract]</small></th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        $ditemsQuery = "SELECT i.*,CONCAT(u.fname,' ',u.lname) supplier FROM item i LEFT JOIN user u ON (i.supplier=u.id AND u.status=1) ORDER BY i.name";
                                                        $itemsList = DB::getInstance()->querySample($ditemsQuery);
                                                        foreach ($itemsList as $item) {
                                                        ?>
                                                            <tr>
                                                                <td><?php echo $item->name ?></td>
                                                                <td><?php echo $item->unit_measure ?></td>
                                                                <td><?php echo $item->unit_price ?></td>
                                                                <td><?php echo $item->supplier ?></td>
                                                                <td>
                                                                    <a href="#edit-item-modal<?php echo $item->id ?>" data-toggle="modal" class="btn btn-primary btn-xs"><?php _e('edit'); ?></a>
                                                                    <div class="modal fade" id="edit-item-modal<?php echo $item->id ?>" tabindex="-1" role="dialog" aria-labelledby="ultraModal-Label" aria-hidden="true">
                                                                        <div class="modal-dialog modal-sm animated fadeInDown">
                                                                            <div class="modal-content animated">
                                                                                <form action="" method="POST">
                                                                                    <div class="modal-header">
                                                                                        <h4 class="modal-title">Edit item</h4>
                                                                                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span>
                                                                                            <span class="sr-only">Close</span>
                                                                                        </button>
                                                                                    </div>
                                                                                    <div class="modal-body">
                                                                                        <div class="form-group">
                                                                                            <label>Name</label>
                                                                                            <input type="text" class="form-control" name="name" value="<?php echo $item->name?>" required>
                                                                                            <label>Unit Measure</label>
                                                                                            <input type="text" class="form-control" name="unit_measure" value="<?php echo $item->unit_measure?>">
                                                                                            <?php if($item->supplier!=""){?><br/><label><input type="checkbox" name="remove_supplier" value="1"/> Remove Supplier</label><?php }?>
                                                                                        </div>

                                                                                    </div>
                                                                                    <div class="modal-footer">
                                                                                        <input type="hidden" name="id" value="<?php echo $item->id?>"/>
                                                                                        <input type="hidden" name="reroute" value="<?php echo $crypt->encode('page=' . $_GET['page'] . '&tab=items-tab'); ?>">
                                                                                        <button type="button" class="btn btn-warning" data-dismiss="modal">Close</button>
                                                                                        <button type="submit" name="action" value="editItem" class="btn btn-primary">Submit</button>
                                                                                    </div>
                                                                                </form>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        <?php }
                                                        ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="tab-pane <?php echo $email_tab_active ?>" id="email-tab">
                                            <form method="POST" action="" enctype="multipart/form-data">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label><?php _e('Email From Address'); ?></label>
                                                            <input class="form-control" id="email_from_address" value="<?php echo getConfigValue("email_from_address"); ?>" type="text" name="email_from_address" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label><?php _e('Emails From Name'); ?></label>
                                                            <input class="form-control" id="email_from_name" value="<?php echo getConfigValue("email_from_name"); ?>" type="text" name="email_from_name" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <div class="checkbox"><label><input type="checkbox" name="email_smtp_enable" <?php if (getConfigValue("email_smtp_enable") == "true") echo 'checked="yes"'; ?> value="true"> Enable SMTP</label></div>
                                                            <div class="checkbox"><label><input type="checkbox" name="email_smtp_auth" <?php if (getConfigValue("email_smtp_auth") == "true") echo 'checked="yes"'; ?> value="true"> SMTP Requires Authentication</label></div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label><?php _e('SMTP Host'); ?></label>
                                                            <input class="form-control" id="email_smtp_host" value="<?php echo getConfigValue("email_smtp_host"); ?>" type="text" name="email_smtp_host">
                                                        </div>
                                                        <div class="form-group">
                                                            <label><?php _e('SMTP Port'); ?></label>
                                                            <input class="form-control" id="email_smtp_port" value="<?php echo getConfigValue("email_smtp_port"); ?>" type="text" name="email_smtp_port">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label><?php _e('SMTP Username'); ?></label>
                                                            <input class="form-control" id="email_smtp_username" value="<?php echo getConfigValue("email_smtp_username"); ?>" type="text" name="email_smtp_username">
                                                        </div>
                                                        <div class="form-group">
                                                            <label><?php _e('SMTP Password'); ?></label>
                                                            <input class="form-control" id="email_smtp_password" value="<?php echo getConfigValue("email_smtp_password"); ?>" type="password" name="email_smtp_password">
                                                        </div>
                                                        <div class="form-group">
                                                            <label><?php _e('SMTP Security'); ?></label>
                                                            <?php $smtp_security = getConfigValue("email_smtp_security"); ?>
                                                            <select class="form-control" id="email_smtp_security" name="settings[email_smtp_security]">
                                                                <option value=""><?php _e('None'); ?></option>
                                                                <option value="ssl" <?php echo ($smtp_security == "ssl") ? 'selected' : '' ?>><?php _e('SSL'); ?></option>
                                                                <option value="tls" <?php echo ($smtp_security == "tls") ? 'selected' : '' ?>><?php _e('TLS'); ?></option>
                                                            </select>
                                                        </div>
                                                        <div class="form-group">
                                                            <label><?php _e('SMTP Authentication Domain'); ?></label>
                                                            <input class="form-control" id="email_smtp_domain" value="<?php echo getConfigValue("email_smtp_domain"); ?>" type="text" name="settings[email_smtp_domain]">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <button type='submit' name="emailSettingsBtn" value="emailSettingsBtn" class="btn btn-success"><i class="fa fa-save"></i> <?php _e('Save Changes'); ?></button>
                                                </div>
                                                <input type="hidden" name="action" value="emailSettings">
                                                <input type="hidden" name="reroute" value="<?php echo $crypt->encode('page=' . $_GET['page'] . '&tab=email-tab'); ?>">
                                                <input type="hidden" name="tab" value="email-tab">
                                            </form>
                                        </div>
                                        <div class="tab-pane <?php echo $templates_tab_active ?>" id="templates-tab">
                                            <div class="alert alert-info"><i class="fa fa-info-circle"></i> To edit any template, just click on related button</div>
                                            <div class="text-center--">
                                                <button onClick='showModal("index.php?modal=notifications/edit&reroute=<?php echo $crypt->encode('page=' . $_GET['page'] . '&tab=templates-tab') ?>&code=request_for_proposal_approval&tab=templates-tab");return false' data-toggle="modal" class="btn btn-primary btn-sm mt-2 mr-2"><?php _e('Request for proposal approval'); ?></button>
                                                <!-- <button onClick='showModal("index.php?modal=notifications/edit&reroute=<?php echo $crypt->encode('page=' . $_GET['page'] . '&tab=templates-tab') ?>&code=new_account&tab=templates-tab");return false' data-toggle="modal" class="btn btn-primary btn-sm mt-2 mr-2"><?php _e('New Account'); ?></button> -->
                                                <button onClick='showModal("index.php?modal=notifications/edit&reroute=<?php echo $crypt->encode('page=' . $_GET['page'] . '&tab=templates-tab') ?>&code=account_activation&tab=templates-tab");return false' data-toggle="modal" class="btn btn-primary btn-sm mt-2 mr-2"><?php _e('Account Activation'); ?></button>
                                            </div>


                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- content-wrapper ends -->
                <!-- partial:../../partials/_footer.html -->
                <?php require_once 'includes/footer_menu.php'; ?>
                <!-- partial -->
            </div>
            <!-- main-panel ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>
    <!-- container-scroller -->
    <?php require_once 'includes/footer.php'; ?>
</body>

</html>