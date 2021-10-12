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
                            <div class="col-md-12">
                                <div class="card">
                                    <header class="card-title">Management KPIs </header>
                                    <div class="card-body">
                                        <form action="" method="POST">
                                            <div class="row">
                                                <?php
                                                $departmentList = DB::getInstance()->querySample("SELECT * FROM department WHERE department_name!='$GENERAL_DEPARTMENT'");
                                                $gradesList = DB::getInstance()->querySample("SELECT * FROM user_grades");
                                                ?>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>Department</label>
                                                        <select class="select2" multiple name="department[]">
                                                            <?php
                                                            foreach ($departmentList AS $list) {
                                                                echo '<option value="' . $list->department_id . '">' . $list->department_name . '</option>';
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label>Grade(s)</label>
                                                        <select class="select2" multiple name="grade[]">
                                                            <?php
                                                            foreach ($gradesList AS $list) {
                                                                echo '<option value="' . $list->grade_id . '">' . $list->grade_name . '</option>';
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <label>Date From</label>
                                                    <input type="date" max="<?php echo $date_today?>" name="date_from" class="form-control">
                                                </div>
                                                <div class="col-md-2">
                                                    <label>Date To</label>
                                                    <input type="date" max="<?php echo $date_today?>" name="date_to" class="form-control">
                                                </div>
                                                <div class="col-md-1"><br/><button type="submit" name="btn_search" value="btn_search" class="btn btn-success btn-lg">Search</button></div>
                                            </div>
                                        </form>
                                        <hr>
                                        <div class="nav-tabs-custom">
                                            <ul class="nav nav-tabs">
                                                <?php
                                                $userFilter="";
                                                $departmentFilter="";
                                                $gradesFilter="";
                                                if(Input::get("btn_search")=="btn_search"){
                                                    $departments= Input::get("department");
                                                    $grades= Input::get("grade");
                                                    $date_from= Input::get("date_from");
                                                    $date_to= Input::get("date_to");
                                                    $departmentFilter.=$departments!=""?" AND d.department_id IN (". implode(",", $departments).")":"";
                                                    $gradesFilter.=$grades!=""?" AND g.grade_id IN (". implode(",", $grades).")":"";
                                                    $userFilter.=$date_from!=""?" AND u.date_started>='$date_from'":"";
                                                    $userFilter.=$date_to!=""?" AND u.date_started<='$date_to'":"";
                                                }
                                                $tab = (isset($_GET['tab']) && $_GET['tab'] != "") ? $_GET['tab'] : 'appointment-ratio';
                                                $appointment_ratio_tab_active = ($tab == "appointment-ratio") ? 'active' : '';
                                                $poential_tab_active = ($tab == "potential") ? 'active' : '';
                                                $turnover_tab_active = ($tab == "turnover") ? 'active' : '';
                                                ?>
                                                <li class="nav-item"><a class="nav-link <?php echo $appointment_ratio_tab_active ?>" href="?<?php echo 'page=' . $_GET['page'] . '&tab=appointment-ratio' ?>"><?php _e('Appointment Ratio'); ?></a></li>
                                                <li class="nav-item"><a class="nav-link <?php echo $poential_tab_active ?>" href="?<?php echo 'page=' . $_GET['page'] . '&tab=potential' ?>"><?php _e('Potential'); ?></a></li>
                                                <li class="nav-item"><a class="nav-link <?php echo $turnover_tab_active ?>" href="?<?php echo 'page=' . $_GET['page'] . '&tab=turnover' ?>"><?php _e('Labour Turnover'); ?></a></li>
                                            </ul>
                                            <div class="tab-content">
                                                <div class="tab-pane active table-responsive">
                                                    <?php
                                                    $KPIChartData = array(
                                                        'combined' => array('internal_hire' => 0, 'external_hire' => 1),
                                                        'two' => array('internal_hire' => 1, 'external_hire' => 0),
                                                        'three' => array('internal_hire' => 0, 'external_hire' => 1),
                                                    );
                                                    if ($tab == "appointment-ratio") {
                                                        $query = "SELECT d.department_name,SUM(CASE WHEN u.appointment_type='$INTERNAL_HIRE' THEN 1 ELSE 0 END) internal_hire,SUM(CASE WHEN u.appointment_type='$EXTERNAL_HIRE' THEN 1 ELSE 0 END) external_hire FROM department d LEFT JOIN user u ON (d.department_id=u.department_id $userFilter) WHERE d.department_name!='$GENERAL_DEPARTMENT' $departmentFilter GROUP BY d.department_id";
                                                        $ratioByDepartment = DB::getInstance()->querySample($query);
                                                        $query = "SELECT g.grade_name name,SUM(CASE WHEN u.appointment_type='$INTERNAL_HIRE' THEN 1 ELSE 0 END) internal_hire,SUM(CASE WHEN u.appointment_type='$EXTERNAL_HIRE' THEN 1 ELSE 0 END) external_hire FROM user_grades g LEFT JOIN user u ON (g.grade_id=u.grade_id $userFilter) WHERE g.grade_id IS NOT NULL $gradesFilter GROUP BY g.grade_id";
                                                        $ratioByGrade = DB::getInstance()->querySample($query);
                                                        ?>
                                                        <div class="row">
                                                            <div class="col-sm-6 col-xs-12">
                                                                <div class="card-title">Appointment Ratio by Function</div>
                                                                <table id="table" class="table table-bordered" data-toggle="table" data-pagination="true" data-search="true" data-show-columns="true" data-show-pagination-switch="true" data-key-events="true" data-show-toggle="true" data-resizable="true" data-cookie="true" data-cookie-id-table="saveId" data-show-export="true" data-click-to-select="true" data-toolbar="#toolbar">
                                                                    <thead><tr><th>Function</th><th>Total Hires</th><th><?php echo $INTERNAL_HIRE ?></th><th><?php echo $EXTERNAL_HIRE ?></th></tr></thead>
                                                                    <tbody>
                                                                        <?php
                                                                        $KPIChartData['combined']['external_hire'] = 0;
                                                                        foreach ($ratioByDepartment AS $list) {
                                                                            $KPIChartData['combined']['internal_hire'] += $list->internal_hire;
                                                                            $KPIChartData['combined']['external_hire'] += $list->external_hire;
                                                                            $total_in+=$list->internal_hire;
                                                                            $total_ex+=$list->external_hire;
                                                                            ?>
                                                                            <tr>
                                                                                <td><?php echo $list->department_name ?></td>
                                                                                <td><?php echo $list->internal_hire + $list->external_hire ?></td>
                                                                                <td><?php echo $list->internal_hire ?></td>
                                                                                <td><?php echo $list->external_hire ?></td>
                                                                            </tr>
                                                                            <?php
                                                                        }

                                                                        $KPIChartData['two']['internal_hire'] = 1;
                                                                        $KPIChartData['two']['external_hire'] = 0;

                                                                        $KPIChartData['three']['internal_hire'] = 0;
                                                                        $KPIChartData['three']['external_hire'] = 1;
                                                                        ?>
                                                                    </tbody>
                                                                    <tfoot><tr><td>TOTAL</td><td><?php echo $total_ex+$total_in?></td><td><?php echo $total_in?></td><td><?php echo $total_ex?></td></tr></tfoot>
                                                                </table>
                                                            </div>
                                                            <div class="col-sm-6 col-xs-12">
                                                                <div class="card-title">Appointment Ratio by Grade</div>
                                                                <table id="table" class="table table-bordered" data-toggle="table" data-pagination="true" data-search="true" data-show-columns="true" data-show-pagination-switch="true" data-key-events="true" data-show-toggle="true" data-resizable="true" data-cookie="true" data-cookie-id-table="saveId" data-show-export="true" data-click-to-select="true" data-toolbar="#toolbar">
                                                                    <thead><tr><th>Grade</th><th>Total Hires</th><th><?php echo $INTERNAL_HIRE ?></th><th><?php echo $EXTERNAL_HIRE ?></th></tr></thead>
                                                                    <tbody>
                                                                        <?php 
                                                                        $total_in=0;
                                                                        $total_ex=0;
                                                                        foreach ($ratioByGrade AS $list) {
                                                                            $total_in+=$list->internal_hire;
                                                                            $total_ex+=$list->external_hire;
                                                                            ?>
                                                                            <tr>
                                                                                <td><?php echo $list->name ?></td>
                                                                                <td><?php echo $list->internal_hire + $list->external_hire ?></td>
                                                                                <td><?php echo $list->internal_hire ?></td>
                                                                                <td><?php echo $list->external_hire ?></td>
                                                                            </tr>
                                                                        <?php } ?>
                                                                    </tbody>
                                                                    <tfoot><tr><td>TOTAL</td><td><?php echo $total_ex+$total_in?></td><td><?php echo $total_in?></td><td><?php echo $total_ex?></td></tr></tfoot>
                                                                </table>
                                                            </div>
                                                        </div>
                                                        <!-- /.row -->

                                                        <?php
                                                    } else if ($tab == 'potential') {
                                                        $departmentList = DB::getInstance()->querySample("SELECT * FROM department d WHERE d.department_name!='$GENERAL_DEPARTMENT' $departmentFilter");
                                                        $gradesList = DB::getInstance()->querySample("SELECT * FROM user_grades g WHERE g.grade_id IS NOT NULL $gradesFilter");
                                                        ?>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="card-title"> 1.  Potential Ratings by Function</div>
                                                                <table id="table" class="table table-bordered" data-toggle="table" data-pagination="true" data-search="true" data-show-columns="true" data-show-pagination-switch="true" data-key-events="true" data-show-toggle="true" data-resizable="true" data-cookie="true" data-cookie-id-table="saveId" data-show-export="true" data-click-to-select="true" data-toolbar="#toolbar">
                                                                    <thead>
                                                                        <tr><th rowspan="2">Function</th><th rowspan="2">Total</th><th colspan="3">Potential Ratings</th></tr>
                                                                        <tr><th>High</th><th>Good</th><th>Limited</th></tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php foreach ($departmentList AS $list) { ?>
                                                                            <tr>
                                                                                <td><?php echo $list->department_name ?></td>
                                                                                <td></td>
                                                                                <td></td>
                                                                                <td></td>
                                                                                <td></td>
                                                                            </tr>
                                                                        <?php }
                                                                        ?>
                                                                    </tbody>
                                                                    <tfoot><tr><td>TOTAL</td><td></td><td></td><td></td><td></td></tr></tfoot>
                                                                </table>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="card-title"> 2.  Potential Ratings by Grades</div>
                                                                <table id="table" class="table table-bordered" data-toggle="table" data-pagination="true" data-search="true" data-show-columns="true" data-show-pagination-switch="true" data-key-events="true" data-show-toggle="true" data-resizable="true" data-cookie="true" data-cookie-id-table="saveId" data-show-export="true" data-click-to-select="true" data-toolbar="#toolbar">
                                                                    <thead>
                                                                        <tr><th rowspan="2">Grade</th><th rowspan="2">Total</th><th colspan="3">Potential Ratings</th></tr>
                                                                        <tr><th>High</th><th>Good</th><th>Limited</th></tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php foreach ($gradesList AS $list) { ?>
                                                                            <tr>
                                                                                <td><?php echo $list->grade_name ?></td>
                                                                                <td></td>
                                                                                <td></td>
                                                                                <td></td>
                                                                                <td></td>
                                                                            </tr>
                                                                        <?php }
                                                                        ?>
                                                                    </tbody>
                                                                    <tfoot><tr><td>TOTAL</td><td></td><td></td><td></td><td></td></tr></tfoot>
                                                                </table>
                                                            </div>
                                                        </div>

                                                        <?php
                                                    } else if ($tab == 'turnover') {
                                                        $departmentList = DB::getInstance()->querySample("SELECT * FROM department d WHERE d.department_name!='$GENERAL_DEPARTMENT' $departmentFilter");
                                                        $gradesList = DB::getInstance()->querySample("SELECT * FROM user_grades g WHERE g.grade_id IS NOT NULL $gradesFilter");
                                                        ?>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="card-title"> 1. Turnover by Function</div>
                                                                <table id="table" class="table table-bordered" data-toggle="table" data-pagination="true" data-search="true" data-show-columns="true" data-show-pagination-switch="true" data-key-events="true" data-show-toggle="true" data-resizable="true" data-cookie="true" data-cookie-id-table="saveId" data-show-export="true" data-click-to-select="true" data-toolbar="#toolbar">
                                                                    <thead>
                                                                        <tr><th>Function</th><th>Actual Headcount</th><th>Turnover</th><th>%Turnover</th></tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php foreach ($departmentList AS $list) { ?>
                                                                            <tr>
                                                                                <td><?php echo $list->department_name ?></td>
                                                                                <td></td>
                                                                                <td></td>
                                                                                <td></td>
                                                                            </tr>
                                                                        <?php }
                                                                        ?>
                                                                    </tbody>
                                                                    <tfoot><tr><td>TOTAL</td><td></td><td></td><td></td></tr></tfoot>
                                                                </table>
                                                                <div class="card-title"> 3. Turnover by Grades and Potential</div>
                                                                <table id="table" class="table table-bordered" data-toggle="table" data-pagination="true" data-search="true" data-show-columns="true" data-show-pagination-switch="true" data-key-events="true" data-show-toggle="true" data-resizable="true" data-cookie="true" data-cookie-id-table="saveId" data-show-export="true" data-click-to-select="true" data-toolbar="#toolbar">
                                                                    <thead>
                                                                        <tr><th rowspan="2">Grade</th><th rowspan="2">Total</th><th colspan="3">Potential Ratings</th></tr>
                                                                        <tr><th>High</th><th>Good</th><th>Limited</th></tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php foreach ($gradesList AS $list) { ?>
                                                                            <tr>
                                                                                <td><?php echo $list->grade_name ?></td>
                                                                                <td></td>
                                                                                <td></td>
                                                                                <td></td>
                                                                                <td></td>
                                                                            </tr>
                                                                        <?php }
                                                                        ?>
                                                                    </tbody>
                                                                    <tfoot><tr><td>TOTAL</td><td></td><td></td><td></td><td></td></tr></tfoot>
                                                                </table>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="card-title"> 2. Turnover by Grades</div>
                                                                <table id="table" class="table table-bordered" data-toggle="table" data-pagination="true" data-search="true" data-show-columns="true" data-show-pagination-switch="true" data-key-events="true" data-show-toggle="true" data-resizable="true" data-cookie="true" data-cookie-id-table="saveId" data-show-export="true" data-click-to-select="true" data-toolbar="#toolbar">
                                                                    <thead>
                                                                        <tr><th>Grade</th><th>Total</th><th>Turnover</th><th>% Turnover</th></tr>
                                                                    </thead>
                                                                    <tbody>
                                                                        <?php foreach ($gradesList AS $list) { ?>
                                                                            <tr>
                                                                                <td><?php echo $list->grade_name ?></td>
                                                                                <td></td>
                                                                                <td></td>
                                                                                <td></td>
                                                                            </tr>
                                                                        <?php }
                                                                        ?>
                                                                    </tbody>
                                                                    <tfoot><tr><td>TOTAL</td><td></td><td></td><td></td></tr></tfoot>
                                                                </table>
                                                            </div>
                                                        </div>

                                                    <?php } ?>
                                                    <div class="clearfix"><hr/></div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="card-title"><?php _e('Ratio of total internal versus external hires'); ?></div>
                                                            <div class="chart">
                                                                <canvas id="KPIsGraph1" style="height:230px"></canvas>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <h3 class="card-title"><?php _e('Ratio of internal versus external hires: Executive grades'); ?></h3>
                                                            <div class="chart">
                                                                <canvas id="KPIsGraph2" style="height:230px"></canvas>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <h3 class="card-title"><?php _e('Ratio of internal versus external hires: Management grades'); ?></h3>
                                                            <div class="chart">
                                                                <canvas id="KPIsGraph3" style="height:230px"></canvas>
                                                            </div>
                                                        </div>
                                                        <!-- /.col -->
                                                    </div>
                                                    <!-- /.row -->
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
        <script>
            const KPIsChartData =<?php echo json_encode(($KPIChartData) ? $KPIChartData : array()); ?>;
        </script>
        <?php require_once 'includes/footer.php'; ?>
    </body>

</html>
