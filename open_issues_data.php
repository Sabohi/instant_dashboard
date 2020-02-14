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

$tableName = 'open_inprogress_'.$client_id;

$full_json_data = '';
$f = array ('json_data');
$global_condition = ' and key_name="'.$key_name.'"';
$tName = $DB->SELECT ($tableName , $f, $_BLANK_ARRAY, $global_condition , $DB_H);
$row_count = $DB->GET_ROWS_COUNT($tName);

if ($row_count > 0) {
        while ($row = $DB->FETCH_ARRAY ($tName, MYSQLI_ASSOC)) {  
                // print_r($row);  
                // print '<br />';
                $full_json_data=json_decode($row["json_data"],true);  
                // print_r($full_json_data);
                // print '<br />';
        }
}

if($id == '1'){
       
        $required_data = isset($full_json_data['department_wise'])?$full_json_data['department_wise']:'';

	
        if(!empty($required_data)){

                $jsonData =  $required_data;
                // $final_data = $jsonData["OPEN"];   ---changed
                $final_data = $jsonData["NEW"];
                
        	$narr=array();
        	$a=0;
	        foreach($final_data as $key=>$val){
        	        $narr["open"][$a]["name"]=str_replace(" ","_",$key);
                	$narr["open"][$a]["y"]=intval($val);
	                // $narr["open"][$a]["drilldown"]="OPEN:".str_replace(" ","_",$key);
	                $narr["open"][$a]["drilldown"]="NEW:".str_replace(" ","_",$key);
        	        $a++;
        	}
	        $final_dailyqa_data = isset($jsonData["INPROGRESS"])?$jsonData["INPROGRESS"]:array();
    
        	$a=0;
	        foreach($final_dailyqa_data as $key=>$val){
        	        $narr["inprogress"][$a]["name"]=str_replace(" ","_",$key);
                	$narr["inprogress"][$a]["y"]=intval($val);
	                $narr["inprogress"][$a]["drilldown"]="INPROGRESS:".str_replace(" ","_",$key);
        	        $a++;
                }
	        print(json_encode($narr));
	}
}
else{
	$asr=explode(":",$id);
	$cnt=count($asr);
	switch($cnt){
                case "2":
                         
                        $required_data = isset($full_json_data['agent_wise'])?$full_json_data['agent_wise']:'';
                       
                        if(!empty($required_data)){
                        
                                $jsonData =  $required_data;
                                
		                $final_data = $jsonData[$asr[0]][$asr[1]];
				
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
			}
		break;
                case "3":
                        
                        $required_data = isset($full_json_data['ticket_type_wise'])?$full_json_data['ticket_type_wise']:'';

                        if (!empty($required_data)) {
                               
                                $jsonData =  $required_data;

                                $final_data = $jsonData[$asr[0]][$asr[1]][$asr[2]];
                                
                                arsort($final_data);
                                $narr=array();
                                $a=0;
                                foreach($final_data as $key=>$val){
                                        $narr[$a]["name"]=str_replace(" ","_",$key);
                                        $narr[$a]["y"]=intval($val);
                                        if(intval($val)>0){
                                                $narr[$a]["drilldown"]=$asr[0].":".$asr[1].":".$asr[2].":".str_replace(" ","_",$key);
                                        }
                                        $a++;
                                }
                                $nar=array("name"=>$asr[2],"data"=>$narr);
                                print(json_encode($nar));
                        }
                break;
                case "4":
                        $required_data = isset($full_json_data['disposition_wise'])?$full_json_data['disposition_wise']:'';

                        if (!empty($required_data)) {
                                
                                $jsonData = $required_data;
                                
                                $final_data = $jsonData[$asr[0]][$asr[1]][$asr[2]][$asr[3]];
                              
                                arsort($final_data);
                                $narr=array();
                                $a=0;
                                foreach($final_data as $key=>$val){
                                        $narr[$a]["name"]=str_replace(" ","_",$key);
                                        $narr[$a]["y"]=intval($val);
                                        if(intval($val)>0){
                                                $narr[$a]["drilldown"]=$asr[0].":".$asr[1].":".$asr[2].":".$asr[3].":".str_replace(" ","_",$key);
                                        }
                                        $a++;
                                }
                                $nar=array("name"=>$asr[3],"data"=>$narr);
                                print(json_encode($nar));
                        }
                break;
                case "5":
                        $required_data = isset($full_json_data['sub_disposition_wise'])?$full_json_data['sub_disposition_wise']:'';

                        if (!empty($required_data)) {
                               
                                $jsonData = $required_data;

                                $final_data = $jsonData[$asr[0]][$asr[1]][$asr[2]][$asr[3]][$asr[4]];
                                
                                arsort($final_data);
                                $narr=array();
                                $a=0;
                                foreach($final_data as $key=>$val){
                                        $narr[$a]["name"]=str_replace(" ","_",$key);
                                        $narr[$a]["y"]=intval($val);
                                        // if(intval($val)>0){
                                        //         $narr[$a]["drilldown"]=$asr[0].":".$asr[1].":".$asr[2].":".$asr[3].":".$asr[4].":".str_replace(" ","_",$key);
                                        // }
                                        $a++;
                                }
                                $nar=array("name"=>$asr[4],"data"=>$narr);
                                print(json_encode($nar));
                        }
                break;
	}
}
