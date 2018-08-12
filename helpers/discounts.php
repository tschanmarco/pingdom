<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <title>Check Discounts</title>
    <link rel="icon" type="image/png" href="../fav.png" sizes="16x16">
    <meta name="robots" content="noindex">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Einbinden des Bootstrap-Stylesheets -->
    <link rel="stylesheet" href="../css/bootstrap.min.css">

    <!-- optional: Einbinden der jQuery-Bibliothek -->
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>

    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">

    <!-- optional: Einbinden der Bootstrap-JavaScript-Plugins -->
    <script src="../js/bootstrap.min.js"></script>

    <style type="text/css">
      body, a { font-family: Courier New; }
      h1 { font: 18px "Courier New"; }
      a { color: white; }
      ul, li { width: auto; }
      ul {float: left; margin: 10px; }
      div { margin: 10px; }
    </style>	
  </head>

  <body>
    <?php
    
    $curlRequestLoginNo = "https://fragments.migros.ch/latest/fragments/discounts.json?sort=recommended&limit=8&offset=0&order=desc&valid=today";
    $curlRequestLoginYes = "https://fragments.migros.ch/latest/fragments/discounts.json?sort=recommended&limit=8&offset=0&order=desc&valid=today&guid=C1DC11A6-CE88-4B7A-9517-8545DD0A3025";

    //curl: fragments no login
    $ch1 = curl_init();	
    $fp1 = fopen('errorlog1.txt', 'w');
    curl_setopt($ch1, CURLOPT_URL, $curlRequestLoginNo);
    curl_setopt($ch1, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch1, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
    curl_setopt($ch1, CURLOPT_USERPWD, "migros:bossNummer");
    //curl_setopt($ch1, CURLOPT_HTTPHEADER, array("app-key: z1zygypqxr5ikzt23wug0cnoopaqqaok"));
    curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch1, CURLOPT_VERBOSE, 1);
    curl_setopt($ch1, CURLOPT_STDERR, $fp1);

    //curl: fragments with login
    $ch2 = curl_init();	
    $fp2 = fopen('errorlog2.txt', 'w');
    curl_setopt($ch2, CURLOPT_URL, $curlRequestLoginYes);
    curl_setopt($ch2, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch2, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch2, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
    curl_setopt($ch2, CURLOPT_USERPWD, "migros:bossNummer");
    //curl_setopt($ch2, CURLOPT_HTTPHEADER, array("app-key: z1zygypqxr5ikzt23wug0cnoopaqqaok"));
    curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch2, CURLOPT_VERBOSE, 1);
    curl_setopt($ch2, CURLOPT_STDERR, $fp);
    
    //array discount no login 
    $curl_response1 = curl_exec($ch1);
    curl_close($ch1);
    $arrDiscountsLoginNo = json_decode($curl_response1);
    
    //array discount with login
    $curl_response2 = curl_exec($ch2);
    curl_close($ch2);
    $arrDiscountsLoginYes = json_decode($curl_response2);
    
    // output discounts
    
    //discount no login
    echo "<ul class='list-group'>";
    echo "<li class='list-group-item active'><a href='".$curlRequestLoginNo."' target='_blank'>No Login</a></li>";
    $i=0;
    $arrDiscountsLoginNoId = array();
    foreach ($arrDiscountsLoginNo as $discount) {
      print("<li class='list-group-item'>" . $discount->id . " " . $discount->name) . "</li>";  
      $arrDiscountsLoginNoId[$i] = $discount->id;
      $i++;
    }
    echo "</ul>";
    
    //discount with login
    echo "<ul class='list-group' style='widht:200px'>";
    echo "<li class='list-group-item active'><a href='".$curlRequestLoginYes."' target='_blank'>With Login (Support User)</a></li>";
    $i=0;
    $arrDiscountsLoginYesId = array();
    foreach ($arrDiscountsLoginYes as $discount) {
      print("<li class='list-group-item'>" . $discount->id . " " . $discount->name) . "</li>";  
      $arrDiscountsLoginYesId[$i] = $discount->id;
      $i++;
    }
    echo "</ul><br clear='all'/>";
    
    //diff der angebots-ids und output status
    $diff = array_diff($arrDiscountsLoginNoId, $arrDiscountsLoginYesId);
    echo "<div>";
    if(empty($diff)) {
      echo "Status bad";
    } else {
      echo "Status ok";
    }
    echo "</div>";
    ?>

  </body>
 </html>