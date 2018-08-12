<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
	<title>Monitoring Digital Operation</title>
  <link rel="icon" type="image/png" href="fav.png" sizes="16x16">
	<meta http-equiv="refresh" content="90">
	<meta name="robots" content="noindex">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	
    <!-- Einbinden des Bootstrap-Stylesheets -->
    <link rel="stylesheet" href="css/bootstrap.min.css">

    <!-- optional: Einbinden der jQuery-Bibliothek -->
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
	
	<script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
	<link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">

    <!-- optional: Einbinden der Bootstrap-JavaScript-Plugins -->
    <script src="js/bootstrap.min.js"></script>

	<style type="text/css">
		body, a { font-family: Courier New; }
		h1 { font: 18px "Courier New"; }
		a { font-family: Arial, Verdana; }
	</style>	
  </head>

  <body>
	<section class="container">
	<?php

	// pingdom api request - checks
	$ch = curl_init();	
	$fp = fopen('errorlog.txt', 'w');
	curl_setopt($ch, CURLOPT_URL, "https://api.pingdom.com/api/2.1/checks");
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
	curl_setopt($ch, CURLOPT_USERPWD, "support.migros-ch@mgb.ch:m96&m1ts");
	curl_setopt($ch, CURLOPT_HTTPHEADER, array("app-key: z1zygypqxr5ikzt23wug0cnoopaqqaok"));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_VERBOSE, 1);
	curl_setopt($ch, CURLOPT_STDERR, $fp);

	// pingdom api request - shared for web
	$ch2 = curl_init();	
	$fp2 = fopen('errorlog.txt', 'w');
	curl_setopt($ch2, CURLOPT_URL, "https://api.pingdom.com/api/2.1/reports.public");
	curl_setopt($ch2, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch2, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($ch2, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
	curl_setopt($ch2, CURLOPT_USERPWD, "support.migros-ch@mgb.ch:m96&m1ts");
	curl_setopt($ch2, CURLOPT_HTTPHEADER, array("app-key: z1zygypqxr5ikzt23wug0cnoopaqqaok"));
	curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch2, CURLOPT_VERBOSE, 1);
	curl_setopt($ch2, CURLOPT_STDERR, $fp2);

	if(isset($_GET['check'])) {
		$showCheck = explode(',', $_GET['check']);
	} else {
		$showCheck = 0;
	}

	$curl_response = curl_exec($ch);
	curl_close($ch);
	$arrChecks = json_decode($curl_response);

	$curl_response2 = curl_exec($ch2);
	curl_close($ch2);
	$arrShared = json_decode($curl_response2);

	// list of public checks
	$arrPublicId = array();
	foreach ($arrShared->public as $shared) {
		$arrPublicId[] = $shared->checkid;
	}

	// debug informations
	if(isset($_GET['debug'])) {
		print_r($arrChecks);
    exit;
	}

	// header output
	echo "<h1><nobr>Monitoring Digital Operation</nobr></h1>\n";
	echo "<p>Refresh alle 90 Sekunden</p>\n";
  
  // category links
  if($showCheck != 0 && in_array(543210, $showCheck)) {
    echo '<a href="http://media.migros.ch/monitoring/?check=2129204,790608,1922885,1922888,1526957,1922886,1482395,972027,1689232,1979788,1957207">migros.ch</a> | ';
    echo '<a href="http://media.migros.ch/monitoring/?check=972260,1602331,3758203,2265019,3758176,3758089,3758149,2268735,2271851,3758377,3758404,3758428,3860014">M-API</a> | ';
    echo '<a href="http://media.migros.ch/monitoring/?check=3758377,3758404,3388594,3388813,848099,3758089,3758149,3758404,2268735,2271851,3860014,3388516,3388543">DWH-WS</a> | ';	  
	  echo '<a href="http://media.migros.ch/monitoring/?check=3388516,3388543,3388594,3388639,3325018,3388657,3388813,3388762,3388648,3388729">Testsysteme</a>';
    echo "<br/><br/>";
  } else {
    echo '<a href="http://media.migros.ch/monitoring/?check=543210">Show all</a>';
    echo "<br/><br/>";
  }
  echo '<table id="dashboard" class="table table-striped table-bordered table-sm">';
	echo "<thead><tr><th>Up</th><th>Checkname</th><th>Hostname</th><th><nobr>letzter Ausfall</nobr></th><th>ID</th></tr></thead>\n";
	echo "<tbody>\n";

	// check sort
	usort($arrChecks->checks, "my_cmp");
	function my_cmp($a, $b) {
	  if ($a->name == $b->name) {
		return 0;
	  }
	  return (strtolower($a->name) < strtolower($b->name)) ? -1 : 1;
	}

	// stati
	$arrStati = array("down","up");
  
  // print_r($arrChecks);exit;
  
	// checks output
    foreach ($arrChecks->checks as $check) {
        
        // status image
        if($check->status == "up") {
          $statusImg = "up.png";
        } else if($check->status == "paused") {
          $statusImg = "paused.png";
        } else {
          $statusImg = "down.png";
        }
             
        //$statusImg = $check->status == "up" ? "up.png" : "down.png";
        $checkLink = "http://stats.pingdom.com/gmn5333biru8/" . $check->id;
        
        $lasterror = "";
        $lasterrorStyle = "";
        if(isset($check->lasterrortime)) {
          $lasterror = date("Y-m-d, H:i:s", $check->lasterrortime);
          if(date("d.m.Y", $check->lasterrortime) == date("d.m.Y")) {
            $lasterrorStyle = " style='font-weight:bold; color:red' ";
          }
        } 
        
        if($showCheck != 0 && in_array($check->id, $arrPublicId) && (in_array($check->id, $showCheck) || in_array(543210, $showCheck))) {
          //print_r($check);
          echo "<tr>";
          echo "<td><img src='./".$statusImg."' width='22' height='22' title='".$check->status."'></td>";
          echo "<td><a href='".$checkLink."' target='_blank'><b>".$check->name."</b></a></td>";
          echo "<td>".$check->hostname."</td>";
          echo "<td " . $lasterrorStyle . ">".$lasterror."</td>";
          echo "<td>".$check->id."</td>";
          echo "</tr>\n";
        }
    }
	echo "</tbody>\n";
	echo "</table>\n";
	?>
	</section>
  </body>

</html>

<script>
$(document).ready(function() {
    $('#dashboard').dataTable( {
		"paging": false, 
		"order": [[ 3, 'desc' ]]
	} );
} );
</script>

