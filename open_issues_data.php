<?php
require_once ("/var/www/html/CZCRM/modules/SESSION/session_config.php");
require_once ("/var/www/html/CZCRM/modules/SESSION/session.php");
$SESS = new SESSION (true);
require_once ("/var/www/html/CZCRM/configs/config.php");
require_once ("/var/www/html/CZCRM/configs/dashboard_config.php");
require_once (_MODULE_PATH . "DATABASE/database_config.php");
require_once (_MODULE_PATH . "DATABASE/DatabaseManageri.php");

$DB = new DATABASE_MANAGER (DB_HOST, DB_USERNAME, DB_PASSWORD,DB_NAME);
$DB_H = $DB->CONNECT ();

$id = isset($_GET["id"])?$_GET["id"]:0;
$range = isset($_GET["range"])?$_GET["range"]:'today';

$table_suffix = isset($table_suffix[$range])?$table_suffix[$range]:'today';

$tableName = 'open_inprogress_'.$table_suffix;
if($id == '1'){
        $tName = $tableName;
        $f = array ('json_data');
        $w = " and key_name='department_wise'";
        $tName = $DB->SELECT ($tName , $f, $_BLANK_ARRAY, $w , $DB_H);
     
        $row_count = $DB->GET_ROWS_COUNT($tName);

	if ($row_count > 0) {
                while ($row = $DB->FETCH_ARRAY ($tName, MYSQLI_ASSOC)) {    
                        $jsonData=json_decode($row["json_data"],true);  
                }

                // $final_data = $jsonData["OPEN"];   ---changed
                $final_data = $jsonData["NEW"];
                
        	$narr=array();
        	$a=0;
	        foreach($final_data as $key=>$val){
        	        $narr["open"][$a]["name"]=str_replace(" ","_",$key);
                	$narr["open"][$a]["y"]=intval($val);
	                $narr["open"][$a]["drilldown"]="OPEN:".str_replace(" ","_",$key);
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
                        $tName = $tableName;
                        $f = array ('json_data');
                        $w = " and key_name='agent_wise'";
                        $tName = $DB->SELECT ($tName , $f, $_BLANK_ARRAY, $w , $DB_H);

                        $row_count = $DB->GET_ROWS_COUNT($tName);

                        if ($row_count > 0) {
                                while ($row = $DB->FETCH_ARRAY ($tName, MYSQLI_ASSOC)) {    
                                        $jsonData=json_decode($row["json_data"],true);  
                                }
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
                // case "3":
                //         $tName = $tableName;
                //         $f = array ('json_data');
                //         $w = " and key_name='company_wise'";
                //         $tName = $DB->SELECT ($tName , $f, $_BLANK_ARRAY, $w , $DB_H);

                //         $row_count = $DB->GET_ROWS_COUNT($tName);

                //         if ($row_count > 0) {
                //                 while ($row = $DB->FETCH_ARRAY ($tName, MYSQLI_ASSOC)) {    
                //                         $jsonData=json_decode($row["json_data"],true);  
                //                 }

                //                 $final_data = $jsonData[$asr[0]][$asr[1]][$asr[2]];
                                
                //                 arsort($final_data);
                //                 $narr=array();
                //                 $a=0;
                //                 foreach($final_data as $key=>$val){
                //                         $narr[$a]["name"]=str_replace(" ","_",$key);
                //                         $narr[$a]["y"]=intval($val);
                //                         if(intval($val)>0){
                //                                 $narr[$a]["drilldown"]=$asr[0].":".$asr[1].":".$asr[2].":".str_replace(" ","_",$key);
                //                         }
                //                         $a++;
                //                 }
                //                 $nar=array("name"=>$asr[2],"data"=>$narr);
                //                 print(json_encode($nar));
                //         }
                // break;
                case "3":
                        $tName = $tableName;
                        $f = array ('json_data');
                        $w = " and key_name='ticket_type_wise'";
                        $tName = $DB->SELECT ($tName , $f, $_BLANK_ARRAY, $w , $DB_H);

                        $row_count = $DB->GET_ROWS_COUNT($tName);

                        if ($row_count > 0) {
                                while ($row = $DB->FETCH_ARRAY ($tName, MYSQLI_ASSOC)) {    
                                        $jsonData=json_decode($row["json_data"],true);  
                                }
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
                case "4":
                        $tName = $tableName;
                        $f = array ('json_data');
                        $w = " and key_name='disposition_wise'";
                        $tName = $DB->SELECT ($tName , $f, $_BLANK_ARRAY, $w , $DB_H);

                        $row_count = $DB->GET_ROWS_COUNT($tName);

                        if ($row_count > 0) {
                                while ($row = $DB->FETCH_ARRAY ($tName, MYSQLI_ASSOC)) {    
                                        $jsonData=json_decode($row["json_data"],true);  
                                }

                                $final_data = $jsonData[$asr[0]][$asr[1]][$asr[2]][$asr[3]][$asr[4]];
                                
                                arsort($final_data);
                                $narr=array();
                                $a=0;
                                foreach($final_data as $key=>$val){
                                        $narr[$a]["name"]=str_replace(" ","_",$key);
                                        $narr[$a]["y"]=intval($val);
                                        if(intval($val)>0){
                                                $narr[$a]["drilldown"]=$asr[0].":".$asr[1].":".$asr[2].":".$asr[3].":".$asr[4].":".str_replace(" ","_",$key);
                                        }
                                        $a++;
                                }
                                $nar=array("name"=>$asr[4],"data"=>$narr);
                                print(json_encode($nar));
                        }
                break;
                case "5":
                        $tName = $tableName;
                        $f = array ('json_data');
                        $w = " and key_name='sub_disposition_wise'";
                        $tName = $DB->SELECT ($tName , $f, $_BLANK_ARRAY, $w , $DB_H);

                        $row_count = $DB->GET_ROWS_COUNT($tName);

                        if ($row_count > 0) {
                                while ($row = $DB->FETCH_ARRAY ($tName, MYSQLI_ASSOC)) {    
                                        $jsonData=json_decode($row["json_data"],true);  
                                }

                                $final_data = $jsonData[$asr[0]][$asr[1]][$asr[2]][$asr[3]][$asr[4]][$asr[5]];
                              
                                arsort($final_data);
                                $narr=array();
                                $a=0;
                                foreach($final_data as $key=>$val){
                                        $narr[$a]["name"]=str_replace(" ","_",$key);
                                        $narr[$a]["y"]=intval($val);
                                       /* if(intval($val)>0){
                                                $narr[$a]["drilldown"]=$asr[0].":".$asr[1].":".$asr[2].":".$asr[3].":".$asr[4].":".$asr[5].":".str_replace(" ","_",$key);
                                        }*/
                                        $a++;
                                }
                                $nar=array("name"=>$asr[5],"data"=>$narr);
                                print(json_encode($nar));
                        }
                break;
	}
}
