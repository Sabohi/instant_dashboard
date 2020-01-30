<?php
require_once ("/var/www/html/CZCRM/modules/SESSION/session_config.php");
require_once ("/var/www/html/CZCRM/modules/SESSION/session.php");
$SESS = new SESSION (true);
require_once ("/var/www/html/CZCRM/configs/config.php");
require_once (_MODULE_PATH . "DATABASE/database_config.php");
require_once (_MODULE_PATH . "DATABASE/DatabaseManageri.php");

$DB = new DATABASE_MANAGER (DB_HOST, DB_USERNAME, DB_PASSWORD,DB_NAME);
$DB_H = $DB->CONNECT ();

$id = isset($_GET["id"])?$_GET["id"]:0;
if($id == '1'){
	$tName = 'repeat_companies';

        $f = array ('json_data');
        $w = " and key_name='repeat_company'";
        $tName = $DB->SELECT ($tName , $f, $_BLANK_ARRAY, $w , $DB_H);
        // print $DB->getLastQuery();
        $row_count = $DB->GET_ROWS_COUNT($tName);

        if ($row_count > 0) {
                while ($row = $DB->FETCH_ARRAY ($tName, MYSQLI_ASSOC)) {
                        $jsonData=json_decode($row["json_data"],true);
                }

                $narr=array();
                $a=0;
                foreach($jsonData as $key=>$val){
                        $narr[$a]["name"]=str_replace(" ","_",$key);
                        $narr[$a]["y"]=intval($val);
                        $narr[$a]["drilldown"]="Repeat_Issue:".str_replace(" ","_",$key);
                        $a++;
                }
	        print_r(json_encode($narr));
        }
}
else
{
        $asr=explode(":",$id);
        $cnt=count($asr);
        switch($cnt){
                case "2":
                        $tName = 'repeat_companies';

                        $f = array ('json_data');
                        $w = " and key_name='repeat_company_with_disposition'";
                        $tName = $DB->SELECT ($tName , $f, $_BLANK_ARRAY, $w , $DB_H);
                        $row_count = $DB->GET_ROWS_COUNT($tName);

                        if ($row_count > 0) {
                                $row = $result->fetch_assoc();
                                while ($row = $DB->FETCH_ARRAY ($tName, MYSQLI_ASSOC)) {
                                        $jsonData=json_decode($row["json_data"],true);
                                }

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
                        }
                break;
                case "3":    $tName = 'repeat_companies';

                        $f = array ('json_data');
                        $w = " and key_name='repeat_company_with_sub_disposition'";
                        $tName = $DB->SELECT ($tName , $f, $_BLANK_ARRAY, $w , $DB_H);
                        $row_count = $DB->GET_ROWS_COUNT($tName);

                        if ($row_count > 0) {
                                $row = $result->fetch_assoc();
                                while ($row = $DB->FETCH_ARRAY ($tName, MYSQLI_ASSOC)) {
                                        $jsonData=json_decode($row["json_data"],true);
                                }

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
                        }
                break;
        }
}
/*$id = $_GET["id"];
$json_data = file_get_contents("new_json.json");
$json_data = json_decode($json_data,true);
if(!empty($id) && $id !=1){
$json_data = $json_data [$id];
print_r(json_encode($json_data));
}
if($id == '1'){
 $json_data = $json_data ["months"];
 print_r(json_encode($json_data));
 //print_r('[{"name":"January","y":62.74,"drilldown":"January"},{"name":"Fevruary","y":62.74,"drilldown":"Fevruary"}]');
}*/
