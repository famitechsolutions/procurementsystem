<?php

function generatePassword($length = 8)
{

    // start with a blank password
    $password = "";

    // define possible characters - any character in this string can be
    // picked for use in the password, so if you want to put vowels back in
    // or add special characters such as exclamation marks, this is where
    // you should do it
    $possible = "2346789bcdfghjkmnpqrtvwxyzBCDFGHJKLMNPQRTVWXYZ";

    // we refer to the length of $possible a few times, so let's grab it now
    $maxlength = strlen($possible);

    // check for length overflow and truncate if necessary
    if ($length > $maxlength) {
        $length = $maxlength;
    }

    // set up a counter for how many characters are in the password so far
    $i = 0;

    // add random characters to $password until $length is reached
    while ($i < $length) {

        // pick a random character from the possible ones
        $char = substr($possible, mt_rand(0, $maxlength - 1), 1);

        // have we already used this character in $password?
        if (!strstr($password, $char)) {
            // no, so it's OK to add it onto the end of whatever we've already got...
            $password .= $char;
            // ... and increase the counter by one
            $i++;
        }
    }

    // done!
    return $password;
}

function escape($string)
{
    return htmlentities($string);
}

function english_date($date)
{
    $create_date = date_create($date);
    $new_date = date_format($create_date, "j M Y");
    return $new_date;
}

function redirect($message, $url)
{
?>
    <script type="text/javascript">
        //        function Redirect()
        //        {
        //            window.location = "<?php echo $url; ?>";
        //        }
        //        alert('<?php echo $message; ?>');
        //        setTimeout('Redirect()', 10);
        alert('<?php echo $message; ?>');
        window.location = "<?php echo $url; ?>"
    </script>
<?php
}

function english_date_time($date)
{
    $create_date = date_create($date);
    $new_date = date_format($create_date, "jS F Y  h:i:s a");
    return $new_date;
}

function english_months($date)
{
    $datecame = "0000-" . $date . "-04";

    $create_date = date_create($datecame);
    $new_date = date_format($create_date, "M");
    return $new_date;
}

function english_time($date)
{
    $create_date = date_create($date);
    $new_date = date_format($create_date, "h:i:s a");
    return $new_date;
}

function ugandan_shillings($value)
{
    $value = number_format($value, 0, ".", ",");
    return $value . " UGx";
}

function increaseDateToDate($value, $type, $dateConvert)
{
    $date = date_create($dateConvert);
    date_add($date, date_interval_create_from_date_string($value . ' ' . $type));
    if ($type == 'minute') {
        return date_format($date, 'Y-m-d H:i');
    } else
        return date_format($date, 'Y-m-d');
}

function calculateAge($smallDate, $largeDate)
{
    $age = "";
    $diff = date_diff(date_create($smallDate), date_create($largeDate));
    $age .= ($diff->y > 0) ? $diff->y . "Y " : "";
    $age .= ($diff->m > 0 && $diff->y < 10) ? $diff->m . "M " : "";
    $age .= ($diff->d > 0 && $diff->y < 1) ? $diff->d . "D " : "";
    $age = ($age != "") ? $age : 0;
    return $age;
}

function calculateDateDifference($smallDate, $largeDate, $type)
{
    $age = 0;
    $diff = strtotime($largeDate) - strtotime($smallDate);
    $age = ($type == "years") ? $diff / (60 * 60 * 24 * 30 * 12) : $age;
    $age = ($type == "months") ? $diff / (60 * 60 * 24 * 30) : $age;
    $age = ($type == "days") ? $diff / (60 * 60 * 24) : $age;
    $age = ($type == "hours") ? $diff / (60 * 60) : $age;
    return $age;
}

//Function to generate a tag
function generate_tag($code, $middle, $string_value)
{
    $code = ($code != "") ? $code . "-" : "";
    $middle = ($middle != "") ? $middle . "-" : "";
    //    $strData = explode(' ', $string_value);
    //    $tag = '';
    //    foreach ($strData as $substring_data) {
    //        $tag .= strtoupper(substr($substring_data, 0, 1));
    //    }
    $Tag = $code . $middle . str_pad($string_value, 2, '0', STR_PAD_LEFT);
    return $Tag;
}

//Function for sending email
function sendEmail($mail_to, $mail_to_name, $subject, $mail_body, $cc = NULL, $isCustom = FALSE)
{
    /**
     * This example shows settings to use when sending via Google's Gmail servers.
     * The IMAP section shows how to save this message to the 'Sent Mail' folder using IMAP commands.
     */
    //SMTP needs accurate times, and the PHP time zone MUST be set
    //This should be done in your php.ini, but this is how to do it if you don't have access to that
    date_default_timezone_set('Etc/UTC');

    require_once 'PHPMailer/PHPMailerAutoload.php';

    //Create a new PHPMailer instance
    $mail = new PHPMailer;

    //Tell PHPMailer to use SMTP
    $mail->isSMTP();

    //Enable SMTP debugging
    // 0 = off (for production use)
    // 1 = client messages
    // 2 = client and server messages
    // 4 = client and server messages in details

    $mail->SMTPDebug = 0;

    //Ask for HTML-friendly debug output
    $mail->Debugoutput = 'html';

    //Set the hostname of the mail server
    $mail->Host = getConfigValue("email_smtp_host");
    // use
    // $mail->Host = gethostbyname('smtp.gmail.com');
    // if your network does not support SMTP over IPv6
    //Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
    $mail->Port = getConfigValue("email_smtp_port");

    //Set the encryption system to use - ssl (deprecated) or tls
    $mail->SMTPSecure = getConfigValue("email_smtp_security");

    //Whether to use SMTP authentication
    $mail->SMTPAuth = getConfigValue("email_smtp_enable");

    //Set the SSL options
    $mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    );
    //Username to use for SMTP authentication - use full email address for gmail
    $mail->Username = getConfigValue("email_smtp_username"); //Email address
    //Password to use for SMTP authentication
    $mail->Password = getConfigValue("email_smtp_password"); //Email Password

    $mail_from = getConfigValue("email_from_address");
    $mail_from_name = getConfigValue("email_from_name");
    //Set who the message is to be sent from
    $mail->setFrom($mail_from, $mail_from_name);

    //Set an alternative reply-to address
    $mail->addReplyTo($mail_from, $mail_from_name);

    //Set who the message is to be sent to
    $mail->addAddress($mail_to, $mail_to_name);

    //Set the subject line
    $mail->Subject = $subject;

    //Add CC to email
    if ($cc) {
        foreach ($cc as $c) {
            if ($c) {
                $mail->addCC($c);
            }
        }
    }


    //Read an HTML message body from an external file, convert referenced images to embedded,
    //convert HTML into a basic plain-text alternative body
    $mail->msgHTML('<!DOCTYPE><html><body>' . $mail_body . '</body></html>');

    //Replace the plain text body with one created manually
    $mail->AltBody = $mail_body;

    //Attach an image file
    //$mail->addAttachment('images/phpmailer_mini.png');
    //send the message, check for errors
    if (!$mail->send()) {
        return "Mailer Error: " . $mail->ErrorInfo;
    } else {
        return "success";
        //Section 2: IMAP
        //Uncomment these to save your message in the 'Sent Mail' folder.
        #if (save_mail($mail)) {
        #    echo "Message saved!";
        #}
    }
}

function save_mail($mail)
{
    //You can change 'Sent Mail' to any other folder or tag
    $path = "{imap.gmail.com:993/imap/ssl}[Gmail]/Sent Mail";

    //Tell your server to open an IMAP connection using the same username and password as you used for SMTP
    $imapStream = imap_open($path, $mail->Username, $mail->Password);

    $result = imap_append($imapStream, $path, $mail->getSentMIMEMessage());
    imap_close($imapStream);

    return $result;
}

function customNumberFormat($value)
{
    $dp = strlen(substr(strrchr($value, "."), 1));
    return ($value) ? number_format($value, $dp) : "";
}


function __($text)
{
    //global $t;
    //if(isset($t)) return $t->translate($text);
    //else return $text;
    return $text;
}

function _e($text)
{
    echo __($text);
}

function _x($sg, $pl, $count)
{
    global $t;
    if (isset($t))
        return sprintf($t->ngettext($sg, $pl, intval($count)), $count);
    else {
        if ($count == "1")
            return sprintf($sg, $count);
        elseif ($count > 1)
            return sprintf($pl, $count);
    }
}

function getConfigValue($name)
{ //return config value from database
    return DB::getInstance()->getName("config", $name, "value", "name");
}

function randomColor()
{ //generate random color
    return '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
}

function escapeJavaScriptText($string)
{
    return str_replace("\n", '\n', str_replace('"', '\"', addcslashes(str_replace("\r", '', (string) $string), "\0..\37'\\")));
}

function smartDate($timestamp)
{
    $diff = time() - $timestamp;

    if ($diff <= 0) {
        return __('Now');
    } else if ($diff < 60) {
        return _x("%d second ago", "%d seconds ago", floor($diff));
    } else if ($diff < 60 * 60) {
        return _x("%d minute ago", "%d minutes ago", floor($diff / 60));
    } else if ($diff < 60 * 60 * 24) {
        return _x("%d hour ago", "%d hours ago", floor($diff / (60 * 60)));
    } else if ($diff < 60 * 60 * 24 * 30) {
        return _x("%d day ago", "%d days ago", floor($diff / (60 * 60 * 24)));
    } else if ($diff < 60 * 60 * 24 * 30 * 12) {
        return _x("%d month ago", "%d months ago", floor($diff / (60 * 60 * 24 * 30)));
    } else {
        return _x("%d year ago", "%d years ago", floor($diff / (60 * 60 * 24 * 30 * 12)));
    }
}

function timeAgo($from, $to = '')
{
    $from = strtotime($from);
    $cur_time = ($to !== '') ? strtotime($to) : time();
    $time_elapsed = $cur_time - $from;
    $seconds = $time_elapsed;
    $minutes = round($time_elapsed / MINUTE_IN_SECONDS);
    $hours = round($time_elapsed / HOUR_IN_SECONDS);
    $days = round($time_elapsed / DAY_IN_SECONDS);
    $weeks = round($time_elapsed / WEEK_IN_SECONDS);
    $months = round($time_elapsed / MONTH_IN_SECONDS);
    $years = round($time_elapsed / YEAR_IN_SECONDS);
    // Seconds
    if ($seconds <= 60) {
        return "just now";
    }
    //Minutes
    else if ($minutes <= 60) {
        return ($minutes == 1) ? 'one minute ago' : "$minutes minutes ago";
    }
    //Hours
    else if ($hours <= 24) {
        return ($hours == 1) ? "an hour ago" : "$hours hrs ago";
    }
    //Days
    else if ($days <= 7) {
        return ($days == 1) ? "yesterday" : "$days days ago";
    }
    //Weeks
    else if ($weeks <= 4.3) {
        return ($weeks == 1) ? "a week ago" : "$weeks weeks ago";
    }
    //Months
    else if ($months <= 12) {
        return ($months == 1) ? "a month ago" : "$months months ago";
    }
    //Years
    else {
        return ($years == 1) ? "one year ago" : "$years years ago";
    }
}

function formatMoney($value)
{
    $currency_symbol = CURRENCY_SYMBOL;
    $currency_symbol_location = CURRENCY_SYMBOL_LOCATION;
    return ($currency_symbol_location == "Left") ? $currency_symbol . customNumberFormat($value) : customNumberFormat($value) . $currency_symbol;
}
function financialYearDateRange($years)
{
    global $FINANCIAL_YEAR_START_MONTH;
    $end_date = new DateTime($years[1] . '-' . $FINANCIAL_YEAR_START_MONTH . '-01');
    $start_date = new DateTime($years[0] . '-' . $FINANCIAL_YEAR_START_MONTH . '-01');
    //$start_date = clone $end_date;
    //$start_date->modify('-1 year');
    $end_date->modify('-1 day');
    $date_range_array = array('start_date' => $start_date->format('Y-m-d'), 'end_date' => $end_date->format('Y-m-d'));
    return $date_range_array;
}
