<?php
  // mandanten und server
  $migros = array("swapp627", "swapp628", "swapp265", "suapp016", "suapp031", "suapp510");
  $famigros = array("swapp371", "swapp372", "suapp033");
  $impuls = array("swapp318", "swapp319");
  $bk1 = array("swapp630", "swapp631", "suapp519", "suapp520");
  $bk2 = array("swapp320", "swapp321", "suapp039", "suapp040");
  
  // get url-param
  foreach($_GET as $man => $value);
  
  // exit if no valid url-param
  if(!count($$man)) {
    echo "Nix da!";
    exit;
  }
?>

<html>
<head>
<?php 
  echo "<title>".$man." - Graphs</title>\n";
?>
<meta http-equiv="refresh" content="180">
</head>
<body>
<?php
  echo "<h1>".$man."</h1>\n";
  /* ************** */
  echo "<h2>Http Sessions</h2>\n";
  foreach($$man as $inst) echo "<a href='http://".$inst.":8080/monitoring' target='_blank'><img src='http://".$inst.":8080/monitoring?width=500&height=300&graph=httpSessions' /></a>\n";
  /* ************** */
  echo "<h2>CPU</h2>\n";
  foreach($$man as $inst) echo "<a href='http://".$inst.":8080/monitoring' target='_blank'><img src='http://".$inst.":8080/monitoring?width=500&height=300&graph=cpu' /></a>\n";
  /* ************** */
  echo "<h2>Http Hits pro Minute</h2>\n";
  foreach($$man as $inst) echo "<a href='http://".$inst.":8080/monitoring' target='_blank'><img src='http://".$inst.":8080/monitoring?width=500&height=300&graph=httpHitsRate' /></a>\n";
  /* ************** */
  // echo "<h2>Aktive Threads</h2>\n";
  // foreach($$man as $inst) echo "<a href='http://".$inst.":8080/monitoring' target='_blank'><img src='http://".$inst.":8080/monitoring?width=500&height=300&graph=tomcatBusyThreads' /></a>\n";
  /* ************** */
?>
</body>
</html>