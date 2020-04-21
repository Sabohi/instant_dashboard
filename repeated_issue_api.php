<?php
require_once ("/var/www/html/CZCRM/modules/SESSION/session_config.php");
require_once ("/var/www/html/CZCRM/modules/SESSION/session.php");
$SESS = new SESSION (true);
require_once ("/var/www/html/CZCRM/configs/config.php");
require_once ("/var/www/html/CZCRM/configs/dashboard_config.php");
require_once (_MODULE_PATH . "DATABASE/database_config.php");
require_once (_MODULE_PATH . "DATABASE/DatabaseManageri.php");

$DB = new DATABASE_MANAGER (DB_HOST, DB_USERNAME, DB_PASSWORD,DASH_DB_NAME);
$DB_H = $DB->CONNECT ();

$id = isset($_GET["id"])?$_GET["id"]:0;
$range = isset($_GET["range"])?$_GET["range"]:'today';
$client_id = isset($_GET["client_id"])?$_GET["client_id"]:'';

$key_name = isset($table_key[$range])?$table_key[$range]:'today';
$global_condition = ' and key_name="'.$key_name.'"';

$tableName = 'repeat_issue_'.$client_id;

$full_json_data = '';
$f = array ('json_data');
$w = '';

$tName = $DB->SELECT ($tableName , $f, $_BLANK_ARRAY, $w.$global_condition , $DB_H);
$row_count = $DB->GET_ROWS_COUNT($tName);

if ($row_count > 0) {
        while ($row = $DB->FETCH_ARRAY ($tName, MYSQLI_ASSOC)) {  
                $full_json_data=json_decode($row["json_data"],true);  
        }
}
if($id == '1'){
	
// print_r($full_json_data);
        $required_data = isset($full_json_data['repeat_ticket_type'])?$full_json_data['repeat_ticket_type']:'';

       // if(!empty($full_json_data)){
                // while ($row = $DB->FETCH_ARRAY ($tName, MYSQLI_ASSOC)) {
                //         $jsonData=json_decode($row["json_data"],true);
                // }

                $jsonData = $required_data;
                // print_r($jsonData);
                $narr=array();
                $a=0;
                foreach($jsonData as $key=>$val){
                        $narr[$a]["name"]=str_replace(" ","_",$key);
                        $narr[$a]["y"]=intval($val);
                        $narr[$a]["drilldown"]="Repeat_Issue:".str_replace(" ","_",$key);
                        $a++;
                }
	        print_r(json_encode($narr));
       // }
}
else
{
        $asr=explode(":",$id);
        $cnt=count($asr);
        switch($cnt){
                case "2":
                        // $tName = $tableName;

                        // $f = array ('json_data');
                        // $w = " and key_name='repeat_company_with_disposition'";
                        // $tName = $DB->SELECT ($tName , $f, $_BLANK_ARRAY, $w.$global_condition , $DB_H);
                        // $row_count = $DB->GET_ROWS_COUNT($tName);
                        $required_data = isset($full_json_data['repeat_ticket_type_with_disposition'])?$full_json_data['repeat_ticket_type_with_disposition']:'';

                        // if (!empty($required_data)) {
                                // $row = $result->fetch_assoc();
                                // while ($row = $DB->FETCH_ARRAY ($tName, MYSQLI_ASSOC)) {
                                //         $jsonData=json_decode($row["json_data"],true);
                                // }
                                $jsonData = $required_data;
                                // print_r($jsonData);

                                $final_data = isset($jsonData[$asr[1]])?$jsonData[$asr[1]]:array();
                                arsort($final_data);
                                $narr=array();
                                $a=0;
                                foreach($final_data as $key=>$val){
                                        $narr[$a]["name"]=str_replace(" ","_",$key);
                                        $narr[$a]["y"]=intval($val);
                                        if(intval($val)>0){
                                                $narr[$a]["drilldown"]=$asr[0].":".$asr[1].":".str_replace(" ","_",$key);
                                        }
                                        $a++;
                                }
                                $nar=array("name"=>$asr[1],"data"=>$narr);
                                print(json_encode($nar));
                        // }
                break;
                case "3":    $tName = $tableName;

                        // $f = array ('json_data');
                        // $w = " and key_name='repeat_company_with_sub_disposition'";
                        // $tName = $DB->SELECT ($tName , $f, $_BLANK_ARRAY, $w.$global_condition , $DB_H);
                        // $row_count = $DB->GET_ROWS_COUNT($tName);
                        $required_data = isset($full_json_data['repeat_ticket_type_with_sub_disposition'])?$full_json_data['repeat_ticket_type_with_sub_disposition']:'';

                        // if (!empty($required_data)) {
                                // $row = $result->fetch_assoc();
                                // while ($row = $DB->FETCH_ARRAY ($tName, MYSQLI_ASSOC)) {
                                //         $jsonData=json_decode($row["json_data"],true);
                                // }

                                $jsonData = $required_data;

                                //print_r($asr[2]);die();
                                $final_data = $jsonData[$asr[1]][$asr[2]];
                                //print_r($final_data);die();
                                arsort($final_data);
                                $narr=array();
                                $a=0;
                                foreach($final_data as $key=>$val){
                                        $narr[$a]["name"]=str_replace(" ","_",$key);
                                        $narr[$a]["y"]=intval($val);
                                //        if(intval($val)>0){
                                        //              $narr[$a]["drilldown"]=$asr[0].":".$asr[1].":".$asr[2].":".str_replace(" ","_",$key);
                                        //    }
                                        $a++;
                                }
                                $nar=array("name"=>$asr[2],"data"=>$narr);
                                print(json_encode($nar));
                        // }
                break;
        }
}