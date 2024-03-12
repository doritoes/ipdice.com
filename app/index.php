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
function dicetext($strText, $hollow = false) {
  if ($hollow) {
    return strtr($strText, "0123456789.", "abcdefghij1");
  }
  return strtr($strText, "0123456789.", "ABCDEFGHIJ!");
}
$client_ip = validate_ipv4(client_ip());
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>ipdice.com</title>
    <link rel="stylesheet" href="/static/styles/main.css">
    <script src="/static/scripts/copy.js" defer></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  </head>
  <body>
    <header>
      <h1>IPDICE.COM</h1>
    </header>
    <main>
      <div class="title">Your IP Address</div>
      <p id="ip-address"><?php echo $client_ip ?></p>
      <p></p><button id="copy-button">COPY IP</button></p>
    </main>
    <footer>
      <p class="ipaddress"><?php echo dicetext($client_ip) ?></p>
    </footer>
  </body>
</html>
