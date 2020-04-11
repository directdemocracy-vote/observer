<?php
require_once '../php/database.php';

function error($message) {
  die("{\"error\":\"$message\"}");
}

$mysqli = new mysqli($database_host, $database_username, $database_password, $database_name);
if ($mysqli->connect_errno)
  error("Failed to connect to MySQL database: $mysqli->connect_error ($mysqli->connect_errno)");
$mysqli->set_charset('utf8mb4');

if (!isset($_POST['referendum']))
  error('Missing referendum POST argument');
$referendum = $mysqli->escape_string($_POST['referendum']);

?>
