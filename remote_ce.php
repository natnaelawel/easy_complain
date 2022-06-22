<?php
// http://localhost/project/rce.php?page=1;phpinfo();
// http://localhost/project/rce.php?page=1;system(%22id%22);
$var = $_GET['page'];

eval(escapeshellarg($var));
// eval($var);