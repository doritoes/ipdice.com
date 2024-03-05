<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>ipdice.com</title>
  </head>
  <body>
    <table>
      <tr>
        <th>Source</th><th>Value</th>
      </tr>
      <?php
         if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER)) {
           $raw_array = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
           $trimmed_array = array_map('trim', $raw_array);
           foreach ($trimmed_array as $ip) {
             echo "<tr><td>HTTP_X_FORWARDED_FOR</td><td>$ip</td></tr>";
           }
         }
         if (array_key_exists('REMOTE_ADDR', $_SERVER)) {
           echo "<tr><td>REMOTE_ADDR</td><td>" . $_SERVER['REMOTE_ADDR'] . "</td></tr>";
         }
         if (array_key_exists('HTTP_CLIENT_IP', $_SERVER)) {
           echo "<tr><td>HTTP_CLIENT_IP</td><td>" . $_SERVER['HTTP_CLIENT_IP'] . "</td></tr>";
         }       
      ?>
    </table>
  </body>
</html>
