<? define("SHORT_INSTALL_CHECK", true);?><?
/* Ansible managed: /etc/ansible/roles/web/templates/dbconn.php.j2 modified on 2015-01-22 13:20:28 by root on portal.o2k.ru */

date_default_timezone_set("Europe/Moscow");

define("DBPersistent", false);
$DBType = "mysql";
$DBHost = "localhost";
$DBLogin = "userbigms";
$DBPassword = "hXCWzc9OAYZW";
$DBName = "dbbigms";
$DBDebug = true;
$DBDebugToFile = false;

define("DELAY_DB_CONNECT", true);
define("CACHED_b_file", 3600);
define("CACHED_b_file_bucket_size", 10);
define("CACHED_b_lang", 3600);
define("CACHED_b_option", 3600);
define("CACHED_b_lang_domain", 3600);
define("CACHED_b_site_template", 3600);
define("CACHED_b_event", 3600);
define("CACHED_b_agent", 3660);
define("CACHED_menu", 3600);

define("BX_FILE_PERMISSIONS", 0664);
define("BX_DIR_PERMISSIONS", 0775);
@umask(~BX_DIR_PERMISSIONS);

define("MYSQL_TABLE_TYPE", "INNODB");
define("SHORT_INSTALL", true);
define("VM_INSTALL", true);

define("BX_UTF", true);
define("BX_COMPRESSION_DISABLED", true);


define( "BX_COMPOSITE_DEBUG", true );
//define( "LOG_FILENAME", $_SERVER["DOCUMENT_ROOT"]."/log.txt" );

$DBDebug = true;

umask(000);
@umask(~BX_DIR_PERMISSIONS);
?>
