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
function is_rfc1918_ip($ipaddr) {
  $ip_long = ip2long($ipaddr);
  return ($ip_long & 0xff000000) === 0x0a000000 || // 10.0.0.0/8
         ($ip_long & 0xfff00000) === 0xac100000 || // 172.16.0.0/12
         ($ip_long & 0xffff0000) === 0xc0a80000;  // 192.168.0.0/16
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
if ($ip_address == "IP Address Not Found" || $ip_address == "127.0.0.1" || is_rfc1918_ip($ip_address)) {
  $scheme = "http";
} else {
  $scheme = "https";
}
if (!isset($_GET['ip'])) {
  $newURL = $scheme . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . '?ip=' . $ip_address;
  header("Location: $newURL");
  exit;
}
if ($_GET['ip'] !== $ip_address) {
  $cleanURI = strtok($_SERVER['REQUEST_URI'], '?');
  $newURL = $scheme . '://' . $_SERVER['HTTP_HOST'] . $cleanURI . '?ip=' . $ip_address;
  echo "Unmatched get ip " . $_GET['ip'] . " to client_ip " . $p_address\n$newURL;
  exit;
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
    <script src="/static/scripts/detect.js" defer></script>
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
      <div class="output-block">
        <div class="ip-display"><?php echo $ip_address ?></div>
        <?php
          if ($ip_address != "IP Address Not Found" && $ip_address != "127.0.0.1" && is_rfc1918_ip($ip_address)) {
            echo '<div class="sandbox">[+] Sandbox detected</div>' . "\n";
          }
        ?>
        <div class="tamper" id="tamper">[+] DOM Tampering detected</div>
      </div>
      <div class="honey">
        <form action="javascript:void(0);" method="get">
          <input id="username" type="text" size="20" name="user"><br>
          <input id="password" type="password" size="20" name="password">
        </form>
      </div>
    </div>
  </body>
</html>
