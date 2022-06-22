<?php
// http://localhost/project/lce.php?page=/etc/passwd
$page_files = array(
    // whitelist
    "about.php" => "about.php",
    "dashboard.php" => "dashboard.php",
);


if (in_array($_GET["page"], array_keys($page_files))) {
    include $page_files[$_GET["page"]];
} else {

    include $page_files["dashboard.php"];
}
