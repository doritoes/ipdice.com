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
if (!isset($_GET['ip'])) {
  $newURL = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . '?ip=' . client_ip();
  header("Location: $newURL");
  exit;
}
if ($_GET['ip'] !== client_ip()) {
  $cleanURI = strtok($_SERVER['REQUEST_URI'], '?');
  $newURL = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . $cleanURI . '?ip=' . client_ip();
  header("Location: $newURL");
  exit;
}
?>
<html>
<head>
<title>Hello</title>
<style>
  body {
    background-color: black;
    overflow: hidden; /* Hide scrollbars */
}

.matrix-container {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  overflow: hidden; /* Important for containing falling characters */ 
}

.matrix-line {
  position: absolute;
  top: 0; /* Align the lines with the top edge */
  width: 10px; /* Adjust character width */
  height: 100%; 
  overflow: hidden; 
  opacity: 0.5; /* Adjust for faded effect */
}

.matrix-line span {
    color: limegreen;
    font-family: monospace;
    display: block;
    animation: matrix-fall 10s linear infinite; /* Adjust speed */
}

@keyframes matrix-fall {
    0% { top: -100%; }
    100% { top: 100%; }
}

.ip-display {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    font-size: 24px;
    font-family: monospace;
    color: limegreen;
}
</style>
</head>
<body>
<h1>Success</h1>
<div class="matrix-container">
<div class="matrix-line"></div>
<div class="matrix-line"></div>
<div class="matrix-line"></div>
<div class="matrix-line"></div>
<div class="matrix-line"></div>
<div class="matrix-line"></div>
<div class="ip-display"></div>
</div>
<script>
  // Fetch IP using an API (respecting privacy)
  fetch('https://api.ipify.org?format=json')
    .then(response => response.json())
    .then(data => document.querySelector('.ip-display').textContent = data.ip);

  const characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ@#$%*&*+=-~πΩ∞       ';

  function generateMatrixStream() {
    const span = document.createElement('span');
    const randomChar = characters.charAt(Math.floor(Math.random() * characters.length));
    span.textContent = randomChar;
    return span;
  }

  function positionMatrixLines() { // Wrap in a function for execution timing
    const matrixLines = document.querySelectorAll('.matrix-line');
    const containerWidth = document.querySelector('.matrix-container').offsetWidth;
    const columnWidth = 15; // Adjust the width of each column

    matrixLines.forEach((line, index) => {
      line.style.left = `${index * columnWidth}px`; // Calculate left offset

      setInterval(() => {
        line.appendChild(generateMatrixStream());
        // Clean up old characters (adjust if needed)
        if (line.children.length > 100) {
          line.removeChild(line.firstChild);
        }
      }, 50 + (Math.random() * 50)); // Adjust character fall speed
    });
  }

  document.addEventListener('DOMContentLoaded', function() {
    positionMatrixLines(); // Call the function after the DOM is ready 
  });
</script>
</body>
</html>
