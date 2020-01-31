<?php

$graph = isset($_POST["graph"])?trim($_POST["graph"]):'';
$range = isset($_POST["range"])?trim($_POST["range"]):'';

require_once ("/var/www/html/CZCRM/modules/SESSION/session_config.php");
require_once ("/var/www/html/CZCRM/modules/SESSION/session.php");
$SESS = new SESSION (true);
require_once ("/var/www/html/CZCRM/configs/config.php");
require_once ("/var/www/html/CZCRM/configs/dashboard_config.php");
require_once (_MODULE_PATH . "DATABASE/database_config.php");
require_once (_MODULE_PATH . "DATABASE/DatabaseManageri.php");

$DB = new DATABASE_MANAGER (DB_HOST, DB_USERNAME, DB_PASSWORD,DB_NAME);
$DB_H = $DB->CONNECT ();

require_once("/var/www/html/CZCRM/classes/redisHandler.class.php");
$redis = new redisHandler();

//derive table suffix

$table_suffix = isset($table_suffix[$range])?$table_suffix[$range]:'today';

function ticket_status_count(){
   GLOBAL $DB, $DB_H, $redis, $table_suffix;

   $ticket_status_count = array();

   $tName2 = 'ticket_status_count_'.$table_suffix;

   $f2 = array ('status');
   $w2 = '';
   $tName2 = $DB->SELECT ($tName2 , $f2, $_BLANK_ARRAY, $w2 , $DB_H);

   if(!$tName2) {
      // die('Could not get data: ' . mysqli_error());
   }
   
   while ($row = $DB->FETCH_ARRAY ($tName2, MYSQLI_ASSOC)) {
      $ticket_status_count =  $row['status'];
   }

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

   $ts_array = array("ticket_status_graph" => array("data_key"=>$data_key,"data_val"=>$data_val));

   return base64_encode(json_encode($ts_array));
}

function department_ticket_status_count(){
   GLOBAL $DB, $DB_H, $redis, $table_suffix;

   // $tName = 'department_ticket_count_2015';
   $tName = 'department_ticket_count_'.$table_suffix;

   $f = array ('key_name','json_data');
   $w = '';
   $tName = $DB->SELECT ($tName , $f, $_BLANK_ARRAY, $w , $DB_H);
   $row_count = $DB->GET_ROWS_COUNT($tName);

   // if ($row_count > 0) {
      $row_data=array();
      while ($row = $DB->FETCH_ARRAY ($tName, MYSQLI_ASSOC)) {
         $row_data[trim($row["key_name"])]=$row["json_data"];
      }
   
      $tc=json_decode($row_data["ticket_status_count"],true);
      $dtc=$row_data["dept_total_ticket_count"];  
      
      $ttl=array_sum(json_decode($dtc,true));
   
      $tcid=json_decode($row_data["department_wise_ticket_count"],true);

      $a=0;	
      $series=array();
      foreach($tcid as $tk=>$tv){
         if($tc[$tk]){
            $series["data"][$a]=array("name"=>$tk,"y"=>$tv,"drilldown"=>$tk);
         }
         else
         {
            $series["data"][$a]=array("name"=>$tk,"y"=>$tv);
         }
         $a++;
      }
      $dlseries=array();
      $a=0;
      foreach($tc as $tk=>$tv){
         $dlseries[$a]["name"]=$tk;
         $dlseries[$a]["id"]=$tk;
         foreach($tv as $tvk=>$tvv){
                  $dlseries[$a]["data"][]=array($tvk,$tvv);
         }
         $a++;
      }
   // } else {
   //    echo "No Data Found";
   // }

   $jsonStr=json_encode($series);
   $jsonDlStr=json_encode($dlseries);

   $dts_array = array("department_ticket_status_graph" => array("jsonStr"=>$jsonStr,"jsonDlStr"=>$jsonDlStr,"ttl"=>$ttl,"dtc"=>$dtc));

   return base64_encode(json_encode($dts_array));
}


function channel_wise_traffic(){
   
   GLOBAL $DB, $DB_H, $redis, $table_suffix;
   $data = array();
   $traffic_analysis = array();
   $a=0;

   $tName1 = 'channel_'.$table_suffix;

   $f1 = array ('traffic');
   $w1 = '';
   $tName1 = $DB->SELECT ($tName1 , $f1, $_BLANK_ARRAY, $w1 , $DB_H);

   if(! $tName1 ) {
      // die('Could not get data: ' . mysqli_error());
   }
   
   while ($row = $DB->FETCH_ARRAY ($tName1, MYSQLI_ASSOC)) {
      $traffic_analysis =  $row['traffic'];
   }

   $traffic_analysis = json_decode($traffic_analysis,true);

   foreach($traffic_analysis as $key=>$val){
         $data[$a]["name"]=$key;
         $data[$a]["y"]=intval($val);
         $a++;
   }
   $data = json_encode($data);

   $cwt_array = array("channel_wise_traffic_graph" => array("data"=>$data));

   return base64_encode(json_encode($cwt_array));

}



if(!empty($graph) && !empty($range)){
    switch ($graph) {
         case 'ticket_status_count':
            $ticket_status_string = ticket_status_count();
            $final_array = array("ticket_status_string"=>$ticket_status_string);
            $final_string = json_encode($final_array);
         break;
         case 'channel_wise_traffic':
            $channel_wise_traffic_string = channel_wise_traffic();
            $final_array = array("channel_wise_traffic_string"=>$channel_wise_traffic_string);
            $final_string = json_encode($final_array);
         break;
         case 'department_ticket_status_count':
            $department_ticket_status_string = department_ticket_status_count();
            $final_array = array("department_ticket_status_string"=>$department_ticket_status_string);
            $final_string = json_encode($final_array);
         break;
        case 'all':
            $ticket_status_string = ticket_status_count();
            $channel_wise_traffic_string = channel_wise_traffic();
            $department_ticket_status_string = department_ticket_status_count();
            $final_array = array("ticket_status_string"=>$ticket_status_string,"channel_wise_traffic_string"=>$channel_wise_traffic_string,"department_ticket_status_string"=>$department_ticket_status_string);
            $final_string = json_encode($final_array);
        break;
        default:
         $final_string = '';
   }     
    
   print base64_encode($final_string);
}

//    $data = array();
//    $traffic_analysis = array();
//    $a=0;

//    $tName1 = 'channel';

//    $f1 = array ('traffic');
//    $w1 = '';
//    $tName1 = $DB->SELECT ($tName1 , $f1, $_BLANK_ARRAY, $w1 , $DB_H);

//    if(! $tName1 ) {
//       die('Could not get data: ' . mysqli_error());
//    }
   
//    while ($row = $DB->FETCH_ARRAY ($tName1, MYSQLI_ASSOC)) {
//       $traffic_analysis =  $row['traffic'];
//    }

// $traffic_analysis = json_decode($traffic_analysis,true);

// foreach($traffic_analysis as $key=>$val){
//       $data[$a]["name"]=$key;
//       $data[$a]["y"]=intval($val);
//       $a++;
// }
// $data = json_encode($data);
//=========================================================================================================================

//    $ticket_status_count = array();

//    $tName2 = 'ticket_status_count_24';

//    $f2 = array ('ticket_status');
//    $w2 = '';
//    $tName2 = $DB->SELECT ($tName2 , $f2, $_BLANK_ARRAY, $w2 , $DB_H);

//    if(! $tName2 ) {
//       die('Could not get data: ' . mysqli_error());
//    }
   
//    while ($row = $DB->FETCH_ARRAY ($tName2, MYSQLI_ASSOC)) {
//       $ticket_status_count =  $row['ticket_status'];
//    }


// $data_key  = array();
// $b=0;
// $ticket_status_count = json_decode($ticket_status_count,true);

// $data_key  = array();
// $b=0;
// foreach($ticket_status_count as $key=>$val){
//    $data_key[$b]=$key;
//    $data_val[$b]=intval($val);
//    $b++; 
// }
// $data_key = json_encode($data_key);
// $data_val = json_encode($data_val);

//=========================================================================================================================
// $ticket_status_count_2015 = array();

// $tName3 = 'ticket_status_count_2015';

// $f3 = array ('status');
// $w3 = '';
// $tName3 = $DB->SELECT ($tName3 , $f3, $_BLANK_ARRAY, $w3 , $DB_H);

// if(!$tName3){
//    die('Could not get data: ' . mysqli_error());
// }

// while ($row = $DB->FETCH_ARRAY ($tName3, MYSQLI_ASSOC)) {
//    $ticket_status_count_2015 =  $row['status'];
// }  

// $data_key_2015  = array();
// $b=0;
// $totalArr=array();
// $excludeArr=array("Total_Tickets","CLOSED","RESOLVED");

// $ticket_status_count_2015 = json_decode($ticket_status_count_2015,true);
// foreach($ticket_status_count_2015 as $key=>$val){
// 	if(in_array($key,$excludeArr)){
// 	   $totalArr[$key]=$val;
// 	}
// 	else
// 	{
//       $data_key_2015[$b]=$key;
//       $data_val_2015[$b]=intval($val);
//       $b++;
// 	}
// }
// $data_key_2015 = json_encode($data_key_2015);
// $data_val_2015 = json_encode($data_val_2015);
//=========================================================================================================================

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

//=========================================================================================================================

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

//=========================================================================================================================

?>