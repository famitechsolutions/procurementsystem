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
                                    <header class="card-title">Employee Tasks Report </header>
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
                                        <?php
                                        $userFilter="";
                                        if(Input::get("btn_search")=="btn_search"){
                                            $departments= Input::get("department");
                                            $grades= Input::get("grade");
                                            $date_from= Input::get("date_from");
                                            $date_to= Input::get("date_to");
                                            $userFilter.=$departments!=""?" AND u.department_id IN (". implode(",", $departments).")":"";
                                            $userFilter.=$grades!=""?" AND u.grade_id IN (". implode(",", $grades).")":"";
                                            $userFilter.=$date_from!=""?" AND t.expected_start_time>='$date_from'":"";
                                            $userFilter.=$date_to!=""?" AND t.expected_end_time<='$date_to'":"";
                                        }

                                            $query = "SELECT CONCAT(u.fname,' ',u.lname) name,t.content task,g.grade_name grade,u.*,t.* FROM user_grades g,user u,tasks t WHERE g.grade_id=u.grade_id AND t.user_id=u.user_id $userFilter";
                                            $tasksList = DB::getInstance()->querySample($query);
                                            ?>
                                            <div class="row">
                                                <div class="col-sm-12 col-xs-12 table-responsive">
                                                    <?php if($tasksList){?>
                                                <table id="table" class="table table-bordered" data-toggle="table" data-pagination="true" data-search="true" data-show-columns="true" data-show-pagination-switch="true" data-key-events="true" data-show-toggle="true" data-resizable="true" data-cookie="true" data-cookie-id-table="saveId" data-show-export="true" data-click-to-select="true" data-toolbar="#toolbar">
                                                        <thead><tr><th>Name</th><th>ID</th><th>Task</th><th>Status</th><th>Updates</th><th>Challenges</th><th>Remarks</th></tr></thead>
                                                        <tbody>
                                                            <?php
                                                            foreach ($tasksList AS $list) {
                                                                $user_response=$list->user_response?json_decode($list->user_response):array();
                                                                $resp=$user_response[0];
                                                                $total_response=count($user_response)>0?count($user_response):1;
                                                                ?>
                                                                <tr>
                                                                    <td rowspan="<?php echo $total_response?>"><?php echo $list->name ?></td>
                                                                    <td rowspan="<?php echo $total_response?>"><?php echo $list->employee_number ?></td>
                                                                    <td rowspan="<?php echo $total_response?>"><?php echo $list->task ?></td>
                                                                    <td rowspan="<?php echo $total_response?>"><?php echo $list->is_started|| $list->user_response?($list->is_completed?'<i class="fa fa-check-square text-success" title="Completed"></i>':'<i class="fa fa-clock-o text-primary" title="In Progress"></i>'):'<i class="fa fa-ban text-danger" title="Not Started"></i>'; ?></td>
                                                                    <td><?php echo $resp->updates ?></td>
                                                                    <td><?php echo $resp->challenges?></td>
                                                                    <td><?php echo $resp->remarks?></td>
                                                                </tr>
                                                                <?php
                                                                for($i=1;$i<$total_response;$i++){
                                                                    $resp=$user_response[$i];
                                                                    ?>
                                                                <tr>
                                                                <td><?php echo $resp->updates ?></td>
                                                                    <td><?php echo $resp->challenges?></td>
                                                                    <td><?php echo $resp->remarks?></td>
                                                                </tr>
                                                                <?php }
                                                            }
                                                            ?>
                                                        </tbody>
                                                    </table>
                                                    <?php }else{
                                                        echo '<div class="alert alert-danger">Nothing to display</div>';
                                                    }?>
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
        <script>
            const KPIsChartData =<?php echo json_encode(($KPIChartData) ? $KPIChartData : array()); ?>;
        </script>
        <?php require_once 'includes/footer.php'; ?>
    </body>

</html>
