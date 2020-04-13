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

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: content-type");
$mysqli = new mysqli($database_host, $database_username, $database_password, $database_name);
if ($mysqli->connect_errno)
  error("Failed to connect to MySQL database: $mysqli->connect_error ($mysqli->connect_errno)");
$mysqli->set_charset('utf8mb4');

if (isset($_GET['referendum']))
  $referendum = $mysqli->escape_string($_GET['referendum']);
else if (isset($_POST['referendum']))
  $referendum = $mysqli->escape_string($_POST['referendum']);
else
  error('Missing referendum POST argument');

if (isset($_POST['update']))
  $update = $mysqli->escape_string($_POST['update']);
elseif (isset($_GET['update']))
  $update = $mysqli->escape_string($_GET['update']);
else
  $update = 0;

if ($update) {
  # list all registrations for referendum
  $response = post("$publisher/registrations.php", array('referendum' => $referendum));
  $decoded = JSON_decode($response);
  $registrations = $decoded->registrations;
  $ballots = $decoded->ballots;
  if (isset($registration->error))
    error("Cannot get registrations from publisher: $registration->error");

  $data = [];
  for($i = 1; $i < 5; $i++) {
   $data += [ "element$i" => $i ];
  }

  $counts = [];
  foreach($registrations as $registration) {
    echo($registration->key);
    if (!isset($count[$r])) {
      $counts += [ $r => 1 ];
    } else
      $counts[$r]++;
  }
  /*
  foreach($counts as $answer => $count)
    echo "$answer: $count";
  */
  die(json_encode($registrations, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
}
?>
