<?php
function get_client_ip() {
  return $_SERVER['HTTP_X_FORWARDED_FOR']
    ?? $_SERVER['REMOTE_ADDR']
    ?? $_SERVER['HTTP_CLIENT_IP']
    ?? '';
}
$raw_array = explode(',', get_client_ip());
$trimmed_array = array_map('trim', $raw_array);
echo $trimmed_array[0];
?>
