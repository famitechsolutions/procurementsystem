<?php

updateDepreciation('','');
if (isset($_GET['type']) && $_GET['type'] == 'depreciated_assets') {
    $template = DB::getInstance()->getRow("notificationtemplate", "depreciated_assets", "*", "code");
    $deprecaitedAssetsList = DB::getInstance()->querySample("SELECT id,name,tag FROM assets WHERE salvage_value=0 AND is_notified=0 AND removal_date IS NULL");
    if ($deprecaitedAssetsList) {
        $asset_tags = array();
        $asset_names = array();
        $ids = array();
        foreach ($deprecaitedAssetsList AS $list) {
            $asset_tags[] = $list->tag;
            $asset_names[] = $list->name;
            $ids[] = $list->id;
        }
        $ids = implode(',', $ids);
        $link = '<a href="' . getConfigValue("site_url") . '/index.php?page=' . $crypt->encode('login') . '">Click here</a>';
        $search = array('{asset_tags}', '{asset_names}', '{login_link}', '{company}');
        $replace = array(implode(', ', $asset_tags), implode(', ', $asset_names), $link, getConfigValue("company_name"));

        $subject = str_replace($search, $replace, $template->subject);
        $message = str_replace($search, $replace, $template->message);
        $result = sendEmail(getConfigValue("email_from_address"), getConfigValue("company_name"), $subject, $message, "");
        if ($result == "success") {
            DB::getInstance()->query("UPDATE assets SET is_notified=1, notified_on='$date_today' WHERE id IN ('$ids')");
        }
    }
}