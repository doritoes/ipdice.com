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
function dicetext($strText, $hollow = false) {
  if ($hollow) {
    return strtr($strText, "0123456789.", "abcdefghij1");
  }
  return strtr($strText, "0123456789.", "ABCDEFGHIJ!");
}
$client_ip = client_ip();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>ipdice.com</title>
    <link rel="stylesheet" href="/static/styles/main.css">
    <script src="/static/scripts/copy.js" defer></script>
  </head>
  <body>
    <header>
      <h1>IPDICE.COM</h1>
    </header>
    <main>
      <h2>Your IP Address</h2>
      <p><span id="ip-address"><?php echo $client_ip ?></span> <button id="copy-button">COPY IP</button></p>
    </main>
    <footer>
      <p class="ipaddress"><?php echo dicetext($client_ip) ?></p>
    </footer>
  </body>
</html>
