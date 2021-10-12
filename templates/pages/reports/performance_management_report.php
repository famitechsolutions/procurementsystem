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
                                <header class="card-title">Employee Reporting Report </header>
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
                                                        foreach ($departmentList as $list) {
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
                                                        foreach ($gradesList as $list) {
                                                            echo '<option value="' . $list->grade_id . '">' . $list->grade_name . '</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <label>Financial Year</label>
                                                <input type="" name="financial_year" class="form-control">
                                            </div>
                                            <div class="col-md-1"><br /><button type="submit" name="btn_search" value="btn_search" class="btn btn-success btn-lg">Search</button></div>
                                        </div>
                                    </form>
                                    <hr>
                                    <?php
                                    $userFilter = "";
                                    $financialYear = date("Y");
                                    if (Input::get("btn_search") == "btn_search") {
                                        $departments = Input::get("department");
                                        $grades = Input::get("grade");
                                        $financial_year = Input::get("financial_year");
                                        $userFilter .= $departments != "" ? " AND u.department_id IN (" . implode(",", $departments) . ")" : "";
                                        $userFilter .= $grades != "" ? " AND u.grade_id IN (" . implode(",", $grades) . ")" : "";
                                        $financialYear = $financial_year != "" ? $financial_year:$financialYear;
                                    }

                                    $daysRange = financialYearDateRange(array($financialYear, $financialYear + 1));



                                    $query = "SELECT er.*,CONCAT(u.fname,' ',u.lname) name,g.grade_name grade,(SELECT CONCAT(fname,' ',lname)name FROM user ui WHERE ui.user_id=er.reports_to LIMIT 1)reports_to_name FROM user_grades g,user u, employee_reporting er WHERE er.user_id=u.user_id AND er.financial_year='$financialYear' AND g.grade_id=u.grade_id $userFilter GROUP BY u.user_id";
                                    $tasksList = DB::getInstance()->querySample($query);
                                    ?>
                                    <div class="row">
                                        <div class="col-sm-12 col-xs-12 table-responsive-">
                                            <?php if ($tasksList) {
                                                $start = strtotime($daysRange['start_date']);
                                                $end = strtotime($daysRange['end_date']);
                                                // while ($start < $end) {
                                                //     echo date('M Y', $start) . ', ';
                                                //     $start = strtotime("+1 month", $start);
                                                // }

                                                $secondLowerRow = "";
                                                $emptyRows = "";
                                                $months = array();
                                                $totalOneOnOne=array();
                                            ?>
                                                <table id="table" class="table table-bordered table-responsive" data-toggle="table" data-pagination="true" data-search="true" data-show-columns="true" data-show-pagination-switch="true" data-key-events="true" data-show-toggle="true" data-resizable="true" data-cookie="true" data-cookie-id-table="saveId" data-show-export="true" data-click-to-select="true" data-toolbar="#toolbar">
                                                    <thead>
                                                        <tr>
                                                            <th rowspan="2">Employee Number</th>
                                                            <th rowspan="2">Name</th>
                                                            <th rowspan="2">Reports To</th>
                                                            <?php while ($start < $end) {
                                                                $year = $month < FINANCIAL_YEAR_START_MONTH ? $financialYear + 1 : $financialYear;
                                                                echo '<th colspan="2">' . date('M Y', $start) . '</th>';
                                                                $secondLowerRow .= '<th>Scheduled Date </th><th>Done</th>';
                                                                $emptyRows .= "<td></td><td></td>";
                                                                $month=date('m', $start);
                                                                array_push($months, $month);
                                                                $start = strtotime("+1 month", $start);
                                                                $totalOneOnOne[$month]['scheduled']=0;
                                                                $totalOneOnOne[$month]['total']=0;
                                                                $totalOneOnOne[$month]['done']=0;
                                                            } ?>
                                                        </tr>
                                                        <tr><?php echo $secondLowerRow ?></tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php
                                                        foreach ($tasksList as $list) {
                                                            $employee_schedule = DB::getInstance()->querySample("SELECT * FROM employee_schedule es WHERE es.reporting_id='$list->id'");
                                                            $scheduledMOnths = array_column(json_decode(json_encode($employee_schedule), true), 'month');
                                                        ?>
                                                            <tr>
                                                                <td><?php echo $list->employee_number ?></td>
                                                                <td><?php echo $list->name ?></td>
                                                                <td><?php echo $list->reports_to_name ?></td>
                                                                <?php foreach ($months as $month) {
                                                                        $totalOneOnOne[$month]['total']+=1;
                                                                    if (in_array($month, $scheduledMOnths)) {
                                                                        $index=array_search($month,$scheduledMOnths);
                                                                        $schedule=$employee_schedule[$index];
                                                                        $totalOneOnOne[$month]['scheduled']+=1;
                                                                        $totalOneOnOne[$month]['done']+=$schedule->schedule_status=='Done'?1:0;
                                                                        echo '<td>' . $schedule->schedule_date . '</td><td>'.$schedule->schedule_status.'</td>';
                                                                    } else {
                                                                        echo '<td></td><td></td>';
                                                                    }
                                                                } ?>
                                                            </tr>
                                                        <?php
                                                        }
                                                        ?>
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <td colspan="3">Total 1-on-1</td><?php 
                                                            $totalPercentagesString='';
                                                            foreach($months AS $month){
                                                                echo '<td>'.$totalOneOnOne[$month]['scheduled'].'</td><td>'.$totalOneOnOne[$month]['done'].'</td>';
                                                                $scheduled=round($totalOneOnOne[$month]['scheduled']*100/$totalOneOnOne[$month]['total'],2);
                                                                $done=round($totalOneOnOne[$month]['done']*100/$totalOneOnOne[$month]['total'],2);
                                                                $done_below=$done<MONTHLY_ONE_ON_ONE_TARGET?'color:red':'';
                                                                $scheduled_below=$scheduled<MONTHLY_ONE_ON_ONE_TARGET?'color:red':'';
                                                                $totalPercentagesString.='<td style="'.$scheduled_below.'">'.$scheduled.'</td><td style="'.$done_below.'">'.$done.'</td>';
                                                            } ?>
                                                        </tr>
                                                        <!-- <tr>
                                                            <td colspan="3">Actual 1-on-1</td><?php echo $emptyRows ?>
                                                        </tr> -->
                                                        <tr>
                                                            <td colspan="3">Overall 1-on-1 % Done</td><?php echo $totalPercentagesString ?>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="3">Target</td><?php echo $emptyRows ?>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            <?php } else {
                                                echo '<div class="alert alert-danger">Nothing to display</div>';
                                            } ?>
                                        </div>
                                    </div>
                                    <!-- /.row -->
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
    <?php require_once 'includes/footer.php'; ?>
</body>

</html>