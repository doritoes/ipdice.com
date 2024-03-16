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
$ip_address =  client_ip();
if (!isset($_GET['ip'])) {
  $newURL = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . '?ip=' . $ip_address;
  header("Location: $newURL");
  exit;
}
if ($_GET['ip'] !== $ip_address) {
  $cleanURI = strtok($_SERVER['REQUEST_URI'], '?');
  $newURL = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . $cleanURI . '?ip=' . $ip_address;
  header("Location: $newURL");
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>ipdice.com - Details</title>
    <link rel="stylesheet" href="/static/styles/details.css" id="theme-stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Share+Tech+Mono&display=swap" rel="stylesheet">
    <link rel="apple-touch-icon" sizes="180x180" href="/static/images/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/static/images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/static/images/favicon-16x16.png">
    <link rel="manifest" href="/static/styles/site.webmanifest">
    <link rel="mask-icon" href="/static/images/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#000000">
    <script src="/static/scripts/details.js" defer></script>
  </head>
  <body>
    <div class="matrix-container">
      <div class="matrix-line"></div>
      <div class="matrix-line"></div>
      <div class="matrix-line"></div>
      <div class="matrix-line"></div>
      <div class="matrix-line"></div>
      <div class="matrix-line"></div>
      <div class="ip-display"><?php echo $ip_address ?></div>
  </div>
  </body>
</html>
