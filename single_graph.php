<?php

//$traffic_analysis = $redis->HGETALL('traffic_analysis');

require_once ("/var/www/html/CZCRM/modules/SESSION/session_config.php");
require_once ("/var/www/html/CZCRM/modules/SESSION/session.php");
$SESS = new SESSION (true);
require_once ("/var/www/html/CZCRM/configs/config.php");
require_once (_MODULE_PATH . "DATABASE/database_config.php");
require_once (_MODULE_PATH . "DATABASE/DatabaseManageri.php");

$DB = new DATABASE_MANAGER (DB_HOST, DB_USERNAME, DB_PASSWORD,DB_NAME);
$DB_H = $DB->CONNECT ();

require_once("/var/www/html/CZCRM/classes/redisHandler.class.php");
$redis = new redisHandler();

   $data = array();
   $traffic_analysis = array();
   $a=0;

   // $query = "select traffic from channel";
   // $retval = mysqli_query($conn, $query);
   $tName1 = 'channel';

   $f1 = array ('traffic');
   $w1 = '';
   $tName1 = $DB->SELECT ($tName1 , $f1, $_BLANK_ARRAY, $w1 , $DB_H);

   if(! $tName1 ) {
      die('Could not get data: ' . mysqli_error());
   }
   
   while ($row = $DB->FETCH_ARRAY ($tName1, MYSQLI_ASSOC)) {
      $traffic_analysis =  $row['traffic'];
   }
//   while($row = mysqli_fetch_assoc($retval)) {
//      $traffic_analysis =  $row['traffic'];
//    }

$traffic_analysis = json_decode($traffic_analysis,true);

foreach($traffic_analysis as $key=>$val){
      $data[$a]["name"]=$key;
      $data[$a]["y"]=intval($val);
      $a++;
}
$data = json_encode($data);

$ticket_status_count = array();

   $tName2 = 'ticket_status_count_24';

   $f2 = array ('ticket_status');
   $w2 = '';
   $tName2 = $DB->SELECT ($tName2 , $f2, $_BLANK_ARRAY, $w2 , $DB_H);

   if(! $tName2 ) {
      die('Could not get data: ' . mysqli_error());
   }
   
   while ($row = $DB->FETCH_ARRAY ($tName2, MYSQLI_ASSOC)) {
      $ticket_status_count =  $row['ticket_status'];
   }

// $ticket_status_count_24 = "select ticket_status from ticket_status_count_24";
// // mysqli_select_db('data_visualization');
//    $status_count_24 = mysqli_query($conn, $ticket_status_count_24);
//  if(! $retval ) {
//       die('Could not get data: ' . mysqli_error());
//    }

//   while($row = mysqli_fetch_assoc($status_count_24)) {
//      $ticket_status_count =  $row['ticket_status'];
//    }


$data_key  = array();
$b=0;
$ticket_status_count = json_decode($ticket_status_count,true);
$data_key  = array();
$b=0;
foreach($ticket_status_count as $key=>$val){
              	$data_key[$b]=$key;
               $data_val[$b]=intval($val);
  		 $b++; 
        }
        $data_key = json_encode($data_key);
        $data_val = json_encode($data_val);

$ticket_status_count_2015 = array();
// $ticket_status_count = "select status from ticket_status_count_2015";
// mysqli_select_db('data_visualization');
//    $status_count_2015 = mysqli_query($conn, $ticket_status_count);
//  if(! $retval ) {
//       die('Could not get data: ' . mysqli_error());
//    }

//   $row = mysqli_fetch_assoc($status_count_2015); 
//      $ticket_status_count_2015 =  $row['status'];

$tName3 = 'ticket_status_count_2015';

$f3 = array ('status');
$w3 = '';
$tName3 = $DB->SELECT ($tName3 , $f3, $_BLANK_ARRAY, $w3 , $DB_H);

if(! $tName3 ) {
   die('Could not get data: ' . mysqli_error());
}

while ($row = $DB->FETCH_ARRAY ($tName3, MYSQLI_ASSOC)) {
   $ticket_status_count_2015 =  $row['status'];
}  

// print_r($ticket_status_count_2015);
$data_key_2015  = array();
$b=0;
$totalArr=array();
$excludeArr=array("Total_Tickets","CLOSED","RESOLVED");
//print_r($ticket_status_count_2015);
$ticket_status_count_2015 = json_decode($ticket_status_count_2015,true);
foreach($ticket_status_count_2015 as $key=>$val){
	if(in_array($key,$excludeArr)){
		$totalArr[$key]=$val;
	}
	else
	{
      $data_key_2015[$b]=$key;
      $data_val_2015[$b]=intval($val);
      $b++;
	}
}
$data_key_2015 = json_encode($data_key_2015);
$data_val_2015 = json_encode($data_val_2015);
// print_r($data_val_2015);
    
// $dept_close_percent = "select dept_percent from department_closing_percent";
// mysqli_select_db('data_visualization');
//    $percent_close = mysqli_query($conn, $dept_close_percent);
//  if(! $retval ) {
//       die('Could not get data: ' . mysqli_error());
//    }

//   while($row = mysqli_fetch_assoc($percent_close)) {
//      $department_closing_percent =  $row['dept_percent'];
//    }


$tName4 = 'department_closing_percent';

$f4 = array ('dept_percent');
$w4 = '';
$tName4 = $DB->SELECT ($tName4 , $f4, $_BLANK_ARRAY, $w4 , $DB_H);

if(! $tName4 ) {
   die('Could not get data: ' . mysqli_error());
}

while ($row = $DB->FETCH_ARRAY ($tName4, MYSQLI_ASSOC)) {
   $department_closing_percent =  $row['dept_percent'];
}

$department_closing_percent = json_decode($department_closing_percent,true);

$data_close_key  = array();
$d=0;

foreach($department_closing_percent as $key=>$val){
   $data_close_key[$d]=$key;
   $data_close_val[$d]=intval($val);
   $d++;
}
$data_close_key = json_encode($data_close_key);
$data_close_val = json_encode($data_close_val);

$department_closing_percent_2015 = $redis->getAllHash('department_closing_percent_2015');

$data_close_key_2015  = array();
$d=0;
	
foreach($department_closing_percent_2015 as $key=>$val){
   $data_close_key_2015[$d]=$key;
   $data_close_val_2015[$d]=intval($val);
   $d++;
}
$data_close_key_2015 = json_encode($data_close_key_2015);
$data_close_val_2015 = json_encode($data_close_val_2015);

// $dept_below_percent = "select dept_below_percent from process_ticket_count";
// mysqli_select_db('data_visualization');
//    $below_percent_90 = mysqli_query($dept_below_percent, $conn);
//  if(! $retval ) {
//       die('Could not get data: ' . mysqli_error());
//    }

//   while($row = mysqli_fetch_assoc($below_percent_90)) {
//      $below_percent_count =  $row['dept_below_percent'];
//    }

$tName5 = 'process_ticket_count';

$f5 = array ('dept_below_percent');
$w5 = '';
$tName5 = $DB->SELECT ($tName5 , $f5, $_BLANK_ARRAY, $w5 , $DB_H);

if(! $tName5 ) {
   die('Could not get data: ' . mysqli_error());
}

while ($row = $DB->FETCH_ARRAY ($tName5, MYSQLI_ASSOC)) {
   $below_percent_count =  $row['dept_below_percent'];
}

$below_percent_count = json_decode($below_percent_count,true);

$data_process_key  = array();
$f=0;
foreach($below_percent_count as $key=>$val){
                $data_process_key[$f]=$key;
                $data_process_val[$f]=intval($val);
                $f++;
        }
        $data_process_key = json_encode($data_process_key);
        $data_process_val = json_encode($data_process_val);

//print_r($data_process_key);
//print_r($data_process_val);

?>
