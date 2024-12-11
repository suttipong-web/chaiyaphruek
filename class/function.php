<?php 
function setStep($mysqli,$step,$pid,$status,$cid){	 
	$sql= "	INSERT INTO `tbl_timeline` (`project_id`, `is_step`, `updated`,status_id,cmuaccount) VALUES ('{$pid}', '{$step}',NOW(),'{$status}','{$cid}') ";
	$mysqli->query($sql);
}
function setStepUPDATED($mysqli,$step,$id){	 
$sql= "	UPDATE `tbl_timeline` SET `is_step`='{$step}',  `updated`=NOW(), `is_STDRead`='0', `is_ADVRead`='0',`is_MajorRead`='0', `is_OfficerRead`='0'  WHERE (`id`='{$id}') ";
	$mysqli->query($sql);
}


function getPrenameEn ($prename){
if($prename=="นาย")$str="Mr.";
else if($prename=="น.ส." ||$prename=="นางสาว" ||$prename=="น.ส" )$str="Miss."; 
else if($prename=="นาง")$str="Mrs.";
else $str="";
return $str;
}


function getCountMajorComfirmDoc($mysqli,$sid) {
			
		  $sqlCHkLOCK = "
					SELECT
						tbl_project.student_id,
						tbl_project.is_major_comfirm_doc
						FROM
						tbl_project
						WHERE
						tbl_project.is_major_comfirm_doc = 1 AND
						tbl_project.student_id = '{$sid}' 
				 "		;
			
			$qCLock =  $mysqli->query($sqlCHkLOCK) ;			
			$Chknumrow = $qCLock->num_rows;	
			return (int)$Chknumrow;
}
function convDatetoInsertSql ($datein){
	//08/04/2022
	$MONTH = array("", "ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
	if(!empty($datein)){
		$tempDate = explode("/",$datein);
		$val = ($tempDate[2]+543)."-".$MONTH[$tempDate[1]]."-".$tempDate[0];
	}else {
		$val ="0000-00-00";
	}
	return $val ;
}

 	 function gettypeProjectSTD($student_id){
		 	
			$str = substr($student_id,5,1);
			if((int)$str==1) {
				$strVal = "วิทยานิพนธ์";	
			}
			else if((int)$str==2) {
				$strVal = "การค้นคว้าอิสระ";	
			}else {
				$strVal = "วิทยานิพนธ์";	
			}
				
			return 	$str ;
   }
	
function convDatetoInsertSq2l ($datein){
	//08/04/2022

	$MONTH = array("", "ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");
	if(!empty($datein)){
		$tempDate = explode("/",$datein);
		$val = ($tempDate[2]+543)."-".$MONTH[$tempDate[1]]."-".$tempDate[0];
	}else {
		$val ="0000-00-00";
	}
	return $val ;
}






function convDatePrint($datein){
	//2022-12-16 09:00:00	
	$datein = substr($datein,0,10);
	$MONTH = array("", "มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฏาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
	if(!empty($datein)){
		$tempDate = explode("-",$datein);
		$val = $tempDate[2]." ".$MONTH[$tempDate[1]]." ".($tempDate[0]+543);
	}else {
		$val ="0000-00-00";
	}
	return $val ;
}



function convDatetoSlad ($datein){
	//08/04/2022
	$MONTH = array("", "มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฏาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
	if(!empty($datein)){
		$tempDate = explode("/",$datein);
		$val = $tempDate[0]." ".$MONTH[(int)$tempDate[1]]." พ.ศ. ".($tempDate[2]+543);
	}else {
		$val ="...........";
	}
	return $val ;
}



function  getAmtStudentMajor($mysqli,$majorID,$levelID,$majorid2,$majorid3){
	$sql="  SELECT Count(tbl_student.student_id) as totals FROM  tbl_student
	 WHERE 
	 tbl_student.major_id2 = '{$majorID}'  ";
	          if(!empty($_COOKIE["major_id2"])){        
                   $sql .=" OR (tbl_student.major_id2 = '{$_COOKIE["major_id2"]}') " ;                      
                }
                if(!empty($_COOKIE["major_id3"])){                      
                   $sql .=" OR (tbl_student.major_id2 = '{$_COOKIE["major_id3"]}' ) " ;    
                }
	 $sql .="
	 AND tbl_student.faculty_id = '06'  
	 ";
	 
	 if(!empty($levelID)){
	  $sql .= " AND tbl_student.level_id = '{$levelID}' ";
	 }
	$q = $mysqli->query($sql);
	$rs  = $q->fetch_assoc();
	return (int)$rs["totals"];
}
function  getAmtStudentEng($mysqli,$levelID){
	$sql="  SELECT Count(tbl_student.student_id) as totals FROM  tbl_student
	 WHERE  tbl_student.faculty_id = '06'  ";
	 
	 if(!empty($levelID)){
	  $sql .= " AND tbl_student.level_id = '{$levelID}' ";
	 }
	$q = $mysqli->query($sql);
	$rs  = $q->fetch_assoc();
	return (int)$rs["totals"];
}


function getNameMajor($mysqli,$majorId,$ln){
  $sql= " 
	 SELECT
tbl_major2.id,
tbl_major2.level_id,
tbl_major2.faculty_id,
tbl_major2.major_id,
tbl_major2.major_name_th,
tbl_major2.major_name_en,
tbl_major2.major_reg
FROM
tbl_major2
WHERE
tbl_major2.major_reg = '{$majorId}'  

	 
	 ";
		$q = $mysqli->query($sql);
	$rs  = $q->fetch_assoc();
	if($ln=="en"){
		$str = $rs["major_name_en"];
	}else {
		$str = $rs["major_name_th"];
	}
	return $str;
}

 function getcountBYStatus($mysqli,$majorId,$statusID){
	   			$sql = "					
						SELECT
						Count(tbl_project.id) as qry 
						FROM
						tbl_project
						INNER JOIN tbl_timeline ON tbl_project.id = tbl_timeline.project_id
						INNER JOIN tbl_student ON tbl_project.student_id = tbl_student.student_id
						WHERE
						tbl_timeline.status_id = '{$statusID}'  ";
						
						if(!empty($majorId)){
						$sql .= " AND tbl_student.major_id =  '{$majorId}'	";										
						}
								
					$q = $mysqli->query($sql);			
					$rs = $q->fetch_assoc();					
					return  (int)$rs["qry"];
	   }
 function getcountChkupdate($mysqli,$majorId,$statusID,$isREAD){
	  				
				$sql="SELECT
						tbl_timeline.project_id,
						tbl_project.project_title_th,
						tbl_student.student_id,
						tbl_timeline.id,
						tbl_status.status_title
						FROM
						tbl_project
						INNER JOIN tbl_timeline ON tbl_project.id = tbl_timeline.project_id
						INNER JOIN tbl_student ON tbl_project.student_id = tbl_student.student_id
						INNER JOIN tbl_status ON tbl_timeline.status_id = tbl_status.status_id
						WHERE
						tbl_student.major_id = '{$majorId}' AND
						tbl_timeline.is_ADVRead = 0 AND
						tbl_timeline.status_id = 1 "; 
						if(!empty($majorId)){
						$sql .= " AND tbl_student.major_id =  '{$majorId}'	";										
						}
								
					$q = $mysqli->query($sql);			
					$total = $q->num_rows;
								
					return  (int)$total;
	   }


	  	function  getLastStatusTimeline($mysqli,$projectId) {
			  $sql = " 
			  SELECT
				tbl_status.status_title
				FROM
				tbl_timeline
				INNER JOIN tbl_status ON tbl_timeline.status_id = tbl_status.status_id
				WHERE
				tbl_timeline.project_id = '{$projectId}'	
				ORDER BY
				tbl_timeline.id DESC	  
				limit  1 
			  "; 
			  $q = $mysqli->query($sql);
			  $rs   =  $q->fetch_assoc();
			  return $rs["status_title"];		  
		}
		
		
	  	function  getLastStatusID($mysqli,$projectId) {
			  $sql = " 
			  SELECT
				tbl_status.status_id
				FROM
				tbl_timeline
				INNER JOIN tbl_status ON tbl_timeline.status_id = tbl_status.status_id
				WHERE
				tbl_timeline.project_id = '{$projectId}'	
				ORDER BY
				tbl_timeline.id DESC	  
				limit  1 
			  "; 
			  $q = $mysqli->query($sql);
			  $rs   =  $q->fetch_assoc();
			  return (int)$rs["status_id"];		  
		}
		
		


function  getFilenamePDF($dt,$id){
	$namefiles = $dt."p".$id.".pdf";
	return $namefiles ; 
}
function  getFilenamePDF_Fac($dt,$id){
	$subdate = substr($dt,0,10);			
	$datetemp = explode("-",$subdate);
	$namefiles = "ff_Fac_".$dt."p".$id.".pdf";
	return $namefiles ; 
}
function  getFilenamePDF_Grad($dt,$id){
	$subdate = substr($dt,0,10);			
	$datetemp = explode("-",$subdate);
	$namefiles = "ff_Grad_".$dt."p".$id.".pdf";
	return $namefiles ; 
}
function  getStatusDetail($mysqli,$stepId,$pid){	
	$str = "";
	if(!empty($pid)){
		$sql ="	SELECT
				tbl_timeline.is_step
				FROM
				tbl_timeline
				WHERE
				tbl_timeline.project_id  ='{$pid}' AND
				tbl_timeline.status_id ='{$stepId}' 
				";
			$q = $mysqli->query($sql);
			$rs = $q->fetch_assoc();
			$str =	$rs["is_step"]; 
	}
	return $str ;	
}
function  updateIs7DayAppove($mysqli){

	$sql ="	SELECT
		tbl_timeline.project_id,
		tbl_timeline.updated,
		TIMESTAMPDIFF(DAY,tbl_timeline.updated,NOW()) as remain_time,
		tbl_timeline.status_id
		FROM
		tbl_timeline
		WHERE
		tbl_timeline.status_id IN (5, 12) 
		GROUP BY tbl_timeline.project_id 
		ORDER BY tbl_timeline.updated DESC ";
	$q =  $mysqli->query($sql);
	while ($rs=$q->fetch_assoc()){
	  if((int)$rs["remain_time"]>7) { 
	   $mysqli->query(" UPDATE `tbl_project` SET 
						is_student_confirn_edit = 1 ,
						is_adviser_confirm= 1  
						WHERE (`id`='{$rs["project_id"]}') ");
						
		//$q  = $mysqli->query( " SELECT
		//		tbl_timeline.id
		//		FROM
		//		tbl_timeline
		///		WHERE
		//		tbl_timeline.status_id = 7 AND
		//		tbl_timeline.project_id ='{$rs["project_id"]}'
		//		");
		//$chkrow = (int)$q->num_rows;				
		//if($chkrow==0) {	
		//		if(!empty($rs["project_id"])){
		//			$mysqli->query("INSERT INTO `tbl_timeline` (`project_id`, `is_step`, `updated`,status_id,cmuaccount,description)
		//				VALUES (
		//						'{$rs["project_id"]}',
		//						'อาจารย์ที่ปรึกษารับรองหัวข้อโครงร่าง  ',
		//						NOW(),
		//						'7','','Auto') ");
		//		}
		//}	
	  }
	}
	$rs="";
}




function secondsToTime($inputSeconds) {

    $secondsInAMinute = 60;
    $secondsInAnHour  = 60 * $secondsInAMinute;
    $secondsInADay    = 24 * $secondsInAnHour;

    // extract days
    $days = floor($inputSeconds / $secondsInADay);

    // extract hours
    $hourSeconds = $inputSeconds % $secondsInADay;
    $hours = floor($hourSeconds / $secondsInAnHour);

    // extract minutes
    $minuteSeconds = $hourSeconds % $secondsInAnHour;
    $minutes = floor($minuteSeconds / $secondsInAMinute);

    // extract the remaining seconds
    $remainingSeconds = $minuteSeconds % $secondsInAMinute;
    $seconds = ceil($remainingSeconds);

    // return the final array
    $obj = array(
        'd' => (int) $days,
        'h' => (int) $hours,
        'm' => (int) $minutes,
        's' => (int) $seconds,
    );
	
	
	if((int)$minutes <10) $minutes ="0".$minutes;
	
	if((int)$days>0) {
		$return = $days." ???";
		if((int)$hours>0){
			$return .= " ".$hours." ??";
		}
	}
	elseif ((int)$hours>0) {
		$return = $hours;
		if((int)$minutes>0){			
			$return .= ":".$minutes." ??";
		}
	} elseif ((int)$minutes>0)$return = (int)$minutes." ????";
	else $return = $seconds." ??????";
    return $obj;
}
function   getCodeAdviser($mysqli,$fullname){
	$adviname = explode(" ",$fullname);
	$countArry = count($adviname) ;
	if($countArry==2){
		$fname = $adviname[0];	
		$lname = $adviname[1];
	} else if($countArry==3){
		$fname = $adviname[1];	
		$lname = $adviname[2];
	} else if($countArry>=4){		
		$lname =$adviname[2]." ".$adviname[3];	
		$fname = $adviname[1];	
	}else {
		$fname = $adviname[1];
		$lname = $adviname[2];
	}
		
	$sql= "  SELECT				
				tbl_instructor.`CODE` 
				FROM
				tbl_instructor
				WHERE 
				(
				tbl_instructor.FNAME  = '{$fname}' 
				OR  tbl_instructor.FNAME  = '{$adviname[0]}' 
				OR  tbl_instructor.FNAME  = '{$adviname[1]}'
				OR  tbl_instructor.NAME_E  = '{$fname}'
				OR  tbl_instructor.NAME_E  = '{$adviname[0]}'
				OR  tbl_instructor.NAME_E  = '{$adviname[1]}'
				) 
				AND
				( tbl_instructor.LNAME  = '{$lname}' 
				OR  tbl_instructor.LNAME  = '{$adviname[1]}' 
				OR  tbl_instructor.LNAME  = '{$adviname[2]}'	
				OR  tbl_instructor.NAME_E  = '{$lname}' 
				OR  tbl_instructor.NAME_E  = '{$adviname[1]}' 
				OR  tbl_instructor.NAME_E  = '{$adviname[2]}'	
	
				)
				
				";
	$qAdv = $mysqli->query($sql);
	$row = $qAdv->fetch_assoc();
	return $row["CODE"];
}




?>