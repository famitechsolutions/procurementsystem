<?php
if (isset($_GET['type']) && $_GET['type'] == "payroll") {
    $data_sent = unserialize($crypt->decode($_GET['data_sent']));
    $headingTitle = $data_sent['title'];
    $usersCheck = $data_sent['query'];
    $month = $data_sent['month'];
    $year = $data_sent['year'];
    $current_month_and_year = $year . '-' . $month;
    header("Content-Type: application/xls");
    header("Content-Disposition: attachment; filename=" . $headingTitle . ".xls");
    header("Pragma: no-cache");
    header("Expires: 0");
    $org_name = COMPANY_NAME;
    $org_logo = $logo;
    $organisation_location = COMPANY_LOCATION;
    echo '<table style="font-size: 30px;"><br/><br/><tr><td colspan="2"></td><td colspan="6">' . $org_name . '</td></tr></table>';
    echo '<table style="font-size: 20px;"><tr><td colspan="2"></td><td></td></tr>'
    . '<tr><td colspan="2"></td><td colspan="6">' . $organisation_location . '</td></tr>'
    . '<tr><td colspan="2"></td><td colspan="6">' . $headingTitle . '</td></tr>'
    . '<br/><br/></table>';
    ?>
    <table style="font-size: 13px;" border="1">
        <thead>
            <tr>
                <th>Employee</th>
                <th>Basic Salary</th>
                <th>Bonus Pay</th>
                <th>Taxable Benefit</th>
                <?php
                $allowancesQuery = "SELECT * FROM allowances WHERE status=1";
                $allowancesList = DB::getInstance()->querySample($allowancesQuery);
                foreach ($allowancesList AS $allowance) {
                    echo '<th>' . $allowance->name . '</th>';
                }
                ?>
                <th>Gross Income</th>
                <th>Local service tax</th>
                <th>Chargeable Income</th>
                <th>PAYE</th>
                <th>5%NSSF employee contribution</th>
                <?php
                for ($i = 0; $i < count($allowable_deductions_array); $i++) {
                    echo '<th>' . $allowable_deductions_array[$i] . '</th>';
                }
                ?>
                <th>Total Deductions</th>
                <th>Taxed Salary Loan</th>
                <th>Net Salary</th>
                <th>10%NSSF employer contribution</th>
                <th>15%NSSF total contribution</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $users_list = DB::getInstance()->querySample($usersCheck);
            foreach ($users_list AS $users):
                $scaleQuery = "SELECT * FROM salary_scale WHERE user_id='$users->user_id' AND substr(date_from,1,7)<='$current_month_and_year' AND status=1 ORDER BY id DESC LIMIT 1";
                $salaryScale = DB::getInstance()->querySample($scaleQuery)[0];
                $salary_scale_id = $salaryScale->id;
                $basic_salary = $salaryScale->basic_salary;
                $bonus_pay = $salaryScale->bonus_pay;
                $net_salary = $salaryScale->net_salary;
                $gross_income = $basic_salary + $bonus_pay;
                $local_service_tax = $salaryScale->local_service_tax;
                $local_service_tax = (in_array($month, $local_service_taxed_months)) ? $local_service_tax : "";
                $deductions = unserialize($salaryScale->allowable_deductions);

                $other_name = ($users->other_name) ? " (" . $users->other_name . ") " : "";
                if (($users->payroll_type == 'Salary Scale' && $gross_income > 0) || ($users->payroll_type == 'Workback' && $net_salary > 0)) {
                    if ($users->payroll_type == 'Salary Scale') {
                        for ($i = 0; $i < count($allowable_deductions_array); $i++) {
                            if ($allowable_deductions_array[$i] == "Benefit Received") {
                                $benefit_received = $amt = ($deductions['name'][$allowable_deductions_array[$i]] == $allowable_deductions_array[$i]) ? $deductions['amount'][$allowable_deductions_array[$i]] : "";
                            }
                        }
                    } else {
                        $benefitsQuery = "SELECT (vehicle_benefit+housing_benefit+utility_benefit+domestic_assistant_benefit) AS benefits FROM workable_benefit WHERE user_id='$users->user_id' AND substr(date_from,1,7)<='$current_month_and_year' AND status=1 ORDER BY id DESC LIMIT 1";
                        $benefit_received = DB::getInstance()->DisplayTableColumnValue($benefitsQuery, "benefits");
                        $gross_income = round(($net_salary / 0.65) - 150769);
                    }
                    $taxable_benefit = round($benefit_received / 0.65);
                    $taxable_benefit = ($taxable_benefit) ? $taxable_benefit : "";

                    $gross_income += $taxable_benefit;
                    ?>
                    <tr>
                        <td><?php echo $emp_name = ( $users->fname . ' ' . $users->lname . $other_name) ?></td>
                        <td><?php echo $basic_salary ?></td>
                        <td><?php echo $bonus_pay ?></td>
                        <td><?php echo $taxable_benefit ?></td>
                        <?php
                        $allowancesList = DB::getInstance()->querySample($allowancesQuery);
                        foreach ($allowancesList AS $allowances) {
                            if ($users->payroll_type == 'Salary Scale') {
                                $gross_income += $allowance_amount = DB::getInstance()->DisplayTableColumnValue("SELECT allowance_amount FROM allowance_expected WHERE scale_id='$salary_scale_id' AND allowance_id='$allowances->id' AND status=1", "allowance_amount");
                            } else {
                                $allowance_amount = "";
                            }
                            echo '<td>' . $allowance_amount . '</td>';
                        }
                        $paye = calculateEmployeeTax($gross_income, $benefit_received)['paye'];
                        $nssf_5percent = calculateEmployeeTax($gross_income, $benefit_received)['nssf_5percent'];
                        $nssf_10percent = calculateEmployeeTax($gross_income, $benefit_received)['nssf_10percent'];
                        ?>
                        <td><?php echo $gross_income; ?></td>
                        <td><?php echo $local_service_tax ?></td>
                        <td><?php echo $chargeable_income = $gross_income - $local_service_tax ?></td>
                        <td><?php echo $paye ?></td>
                        <td><?php echo $nssf_5percent ?></td>
                        <?php
                        $total_deductions = $paye + $nssf_5percent;
                        for ($i = 0; $i < count($allowable_deductions_array); $i++) {
                            $amt = ($deductions['name'][$allowable_deductions_array[$i]] == $allowable_deductions_array[$i]) ? $deductions['amount'][$allowable_deductions_array[$i]] : "";
                            $amt = ($users->payroll_type == 'Workback' && $allowable_deductions_array[$i] == "Benefit Received") ? $benefit_received : $amt;
                            $total_deductions += $amt;
                            echo '<td>' . $amt . '</td>';
                        }
                        ?>
                        <td><?php echo $total_deductions; ?></td>
                        <td></td>
                        <td><?php echo ($users->payroll_type == 'Workback') ? $net_salary : ($chargeable_income - $total_deductions) ?></td>
                        <td><?php echo $nssf_10percent ?></td>
                        <td><?php echo $nssf_5percent + $nssf_10percent ?></td>
                    </tr>
                    <?php
                }
            endforeach;
            ?>
        </tbody>
    </table>
    <br/><br/><strong style="font-size: 15px;">Exported by: <?php echo $_SESSION['user_full_names']; ?></strong>
    <?php
} 

