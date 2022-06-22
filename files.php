<?php
include("./includes/auth_session.php");
require_once("./libs/utils.php");
require_once("./config/db.php");
require_once "./libs/csrf_token.php";

$filename = stripslashes($_GET['filename']);    // removes backslashes
$filename = htmlspecialchars($filename, ENT_QUOTES, 'UTF-8');

$fullpath = '/opt/lampp/htdocs/www/itsec-project/assets/uploads/' . basename($filename);

if (file_exists($fullpath)) {
    if ($isAdmin) {
        $fp = fopen($fullpath, "r+");

        if (flock($fp, LOCK_SH)) { // acquire an exclusive lock
            flock($fp, LOCK_UN); // release the lock
            // get the mime type
            $finfo = finfo_open();
            $mime = finfo_file($finfo, $fullpath, FILEINFO_MIME_TYPE);
            // send it to the client
            header('Content-Disposition: uploads; filename=' . basename($fullpath));
            header('Content-Type: ' . $mime);
            readfile($fullpath);
        }

        fclose($fp);
    } elseif (!$isAdmin) {
        $sql = "SELECT * FROM feedbacks WHERE user_id = :id AND file_path = :filename;";
        $stmt = $conn->prepare($sql);
        $stmt->execute(['id' => $_SESSION["id"], 'filename' => $_GET['filename']]);
        $count = $stmt->rowCount();
        if ($count > 0) {
            $fp = fopen($fullpath, "r+");
            if (flock($fp, LOCK_SH)) { // acquire an exclusive lock
                flock($fp, LOCK_UN); // release the lock
                // get the mime type
                $finfo = finfo_open();
                $mime = finfo_file($finfo, $fullpath, FILEINFO_MIME_TYPE);
                // send it to the client
                header('Content-Disposition: uploads; filename=' . basename($fullpath));
                header('Content-Type: ' . $mime);
                readfile($fullpath);
            }

            fclose($fp);
        }
    } else {
        header("Location:login.php");
    }
} else {
    header("Location:login.php");
}
