<?php

class File {

    public static function icon($file) {
        global $scriptpath;
        //$filepath = $scriptpath . DIRECTORY_SEPARATOR . "uploads" . DIRECTORY_SEPARATOR . $file;
        //$ext = strtolower(pathinfo($filepath, PATHINFO_EXTENSION));
        
        $ext = strtolower(end(explode(".", $file)));
            $key = strtolower($extension);
        $icon = "file-o";

        $archive = array("zip", "rar", "7z", "gz", "iso", "tar", "bz2", "xz", "ace", "apk", "xar", "zz", "war", "wim", "tar.gz", "tgz", "tar.Z", "tar.bz2", "tbz2", "dmg", "s7z");
        $audio = array("mp3", "wav", "aac", "aa", "aax", "aiff", "au", "flac", "m4a", "m4b", "m4p", "ogg", "oga", "wma");
        $code = array("php", "html", "css", "js", "asp", "htm", "sql", "pl");
        $excel = array("xls", "xlsx", "xlsm", "xml", "xlam", "xla", "ods", "fods");
        $image = array("png", "jpg", "jpeg", "tiff", "tif", "gif", "bmp", "ai", "svg", "eps");
        $pdf = array("pdf", "xps");
        $powerpoint = array("ppt", "pot", "pps", "pptx", "pptm", "potx", "potm", "ppam", "ppsx", "ppsm", "sldx", "sldm", "odg", "fodg");
        $text = array("txt", "nfo", "rtf");
        $video = array("avi", "3gp", "wmv", "ogg", ",mpeg", "mpg", "mpe", "mov", "mkv", "flr", "fla", "flv");
        $word = array("doc", "dot", "docx", "docm", "dotx", "dotm", "docb", "odt", "fodt");

        if (in_array($ext, $archive))
            $icon = "file-archive-o";
        if (in_array($ext, $audio))
            $icon = "file-audio-o";
        if (in_array($ext, $code))
            $icon = "file-code-o";
        if (in_array($ext, $excel))
            $icon = "file-excel-o";
        if (in_array($ext, $image))
            $icon = "file-image-o";
        if (in_array($ext, $pdf))
            $icon = "file-pdf-o";
        if (in_array($ext, $powerpoint))
            $icon = "file-powerpoint-o";
        if (in_array($ext, $text))
            $icon = "file-text-o";
        if (in_array($ext, $video))
            $icon = "file-video-o";
        if (in_array($ext, $word))
            $icon = "file-word-o";

        return $icon;
    }

}

?>
