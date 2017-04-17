<?php
include("inc/common.inc.php");

//CnsPHP相对于index.php的目录位置
$GLOBALS['CnsPHP_base'] = "CnsPHP";

$GLOBALS['CnsPHP_db_host'] = "localhost";
$GLOBALS['CnsPHP_db_port'] = "3306";
$GLOBALS['CnsPHP_db_user'] = "user";
$GLOBALS['CnsPHP_db_pass'] = "pass";
$GLOBALS['CnsPHP_db_name'] = "dbname";

include($GLOBALS['CnsPHP_base']."/Controller/Controller.php");
(new CnsPHP\Controller())->init();
