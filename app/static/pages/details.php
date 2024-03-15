<?php
function get_client_ip() {
  return $_SERVER['HTTP_X_FORWARDED_FOR']
    ?? $_SERVER['REMOTE_ADDR']
    ?? $_SERVER['HTTP_CLIENT_IP']
    ?? '';
}
function client_ip() {
  $raw_ip = get_client_ip();
  if ($raw_ip) {
    if (strpos($raw_ip, ",") !== false ) {
      $raw_array = explode(',', $raw_ip);
      $trimmed_array = array_map('trim', $raw_array);
      $ip = $trimmed_array[0];
    } else {
      $ip = $raw_ip;
    }
  } else {
    $ip = "IP Address Not Found";
  }
  return $ip;
}
function validate_ipv4($strIp) {
  if (filter_var($strIp, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) === false &&
      filter_var($strIp, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) === false) {
    return "127.0.0.1";
  } else {
    return $strIp;
  }
}
if (!isset($_GET['ip']) || $_GET['ip'] !== client_ip()) {
  $newURL = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . '?ip=' . client_ip();
  header("Location: $newURL");
  exit;
}
?>
<html>
<head>
<title>Hello</title>
</head>
<body>
<h1>Success</h1>
</body
</html>
