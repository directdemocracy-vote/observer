<?php
require_once '../php/database.php';
$publisher = 'https://publisher.directdemocracy.vote';

function error($message) {
  die("{\"error\":\"$message\"}");
}

function post($url, $data) {
  $options = array('http' => array('header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                                   'method'  => 'POST',
                                   'content' => http_build_query($data)));
  $context  = stream_context_create($options);
  return file_get_contents($url, false, $context);
}

$mysqli = new mysqli($database_host, $database_username, $database_password, $database_name);
if ($mysqli->connect_errno)
  error("Failed to connect to MySQL database: $mysqli->connect_error ($mysqli->connect_errno)");
$mysqli->set_charset('utf8mb4');

if (!isset($_POST['referendum']))
  error('Missing referendum POST argument');
$referendum = $mysqli->escape_string($_POST['referendum']);
if (isset($_POST['update']))
  $update = $mysqli->escape_string($_POST['update']);
else
  $update = 0;

if ($update) {
  # list all registrations for referendum
  $response = post($publisher + '/registrations.php', array('referendum' => $referendum));
  $registrations = JSON_decode($response);
  if (isset($registration->error))
    error("Cannot get registrations from publisher: $registration->error");

}
?>
