<?php
    if(isset($_GET["id_page"])){
        $file = $_GET["id_page"];
        $filepath = "zips/".$file.".zip";
        if(file_exists($filepath)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/zip');
            header('Content-Disposition: attachment; filename="'.basename($filepath).'"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header("Pragma: no-cache");
            header('Content-Length: '.filesize($filepath));
            flush();
            readfile($filepath);
            die();
        } else {
            http_response_code(404);
            die();
        }
    } else {
        die("Nome inválido!");
    }
?>