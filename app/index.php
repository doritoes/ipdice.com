<?php
require_once('BrowserDetection.php');
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
$Browser = new foroco\BrowserDetection();
$useragent = $_SERVER['HTTP_USER_AGENT'];
$result = $Browser->getAll($useragent);
if ($client_ip == "127.0.0.1") {
  $hostname = "";
} else {
  $hostname = gethostbyaddr($client_ip);
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <title>ipdice.com</title>
    <link rel="stylesheet" href="/static/styles/main.css" id="theme-stylesheet">
    <link rel="apple-touch-icon" sizes="180x180" href="/static/images/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/static/images/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/static/images/favicon-16x16.png">
    <link rel="manifest" href="/static/styles/site.webmanifest">
    <link rel="mask-icon" href="/static/images/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">
    <script src="/static/scripts/copy.js" defer></script>
    <script src="/static/scripts/theme.js" defer></script>
  </head>
  <body>
    <div class="background-image"></div>
    <div class="header-container">
      <header>
        <h1>IPDICE.COM</h1>
      </header>
    </div>
    <main>
      <div class="title">Your IP Address</div>
      <div class="image"><img src="/static/images/image2.png"></div>
      <p id="ip-address"><?php echo $client_ip ?></p>
      <div class="details">
<?php
echo "<p>Device: " . ucfirst($result['os_family'] ). " " . ucfirst($result['device_type'] ) . "</p>";
echo "<p>OS: " . ucfirst($result['os_title']) .  "</p>";
echo "<p>Browser: " . ucfirst($result['browser_title']) . "</p>";
if ($hostname) {
  echo "<p>Reverse lookup: " . $hostname . "</p>";
}
if ($result['64bits_mode']) {
  echo "<p>64-bits: enabled</p>";
}
?>
      </div>
      <p><button id="copy-button">COPY IP</button></p>
    </main>
    <footer>
      <p id="dice-text" class="ipaddress"><?php echo dicetext($client_ip) ?></p>
    </footer>
  </body>
</html>
