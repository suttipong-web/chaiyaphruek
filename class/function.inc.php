<?php

function convert_number($number)
{
    if (($number < 0) || ($number > 999999999)) {
        throw new Exception('Number is out of range');
    }
    $giga = floor($number / 1000000);
    // Millions (giga)
    $number -= $giga * 1000000;
    $kilo = floor($number / 1000);
    // Thousands (kilo)
    $number -= $kilo * 1000;
    $hecto = floor($number / 100);
    // Hundreds (hecto)
    $number -= $hecto * 100;
    $deca = floor($number / 10);
    // Tens (deca)
    $n = $number % 10;
    // Ones
    $result = '';
    if ($giga) {
        $result .= $this->convert_number($giga) .  'Million';
    }
    if ($kilo) {
        $result .= (empty($result) ? '' : ' ') . $this->convert_number($kilo) . ' Thousand';
    }
    if ($hecto) {
        $result .= (empty($result) ? '' : ' ') . $this->convert_number($hecto) . ' Hundred';
    }
    $ones = array('', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine', 'Ten', 'Eleven', 'Twelve', 'Thirteen', 'Fourteen', 'Fifteen', 'Sixteen', 'Seventeen', 'Eightteen', 'Nineteen');
    $tens = array('', '', 'Twenty', 'Thirty', 'Fourty', 'Fifty', 'Sixty', 'Seventy', 'Eigthy', 'Ninety');
    if ($deca || $n) {
        if (!empty($result)) {
            $result .= ' and ';
        }
        if ($deca < 2) {
            $result .= $ones[$deca * 10 + $n];
        } else {
            $result .= $tens[$deca];
            if ($n) {
                $result .= '-' . $ones[$n];
            }
        }
    }
    if (empty($result)) {
        $result = 'zero';
    }
    return $result;
}

function  convertMoneyToEN($number)
{

    $no = round($number);
    $hundred = null;
    $digits_1 = strlen($no);
    $i = 0;
    $str = array();
    $words = array(
        '0' => '', '1' => 'One', '2' => 'Two',
        '3' => 'Three', '4' => 'Four', '5' => 'Five', '6' => 'Six',
        '7' => 'Seven', '8' => 'Eight', '9' => 'Nine',
        '10' => 'Ten', '11' => 'Eleven', '12' => 'Twelve',
        '13' => 'Thirteen', '14' => 'Fourteen',
        '15' => 'Fifteen', '16' => 'Sixteen', '17' => 'Seventeen',
        '18' => 'Eighteen', '19' => 'Nineteen', '20' => 'Twenty',
        '30' => 'Thirty', '40' => 'Forty', '50' => 'Fifty',
        '60' => 'Sixty', '70' => 'Seventy',
        '80' => 'Eighty', '90' => 'Ninety'
    );
    $digits = array('', 'Hundred', 'Thousand', 'Lakh', 'Crore');
    while ($i < $digits_1) {
        $divider = ($i == 2) ? 10 : 100;
        $number = floor($no % $divider);
        $no = floor($no / $divider);
        $i += ($divider == 10) ? 1 : 2;
        if ($number) {
            $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
            $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
            $str[] = ($number < 21) ? $words[$number] .
                " " . $digits[$counter] . $plural . " " . $hundred
                :
                $words[floor($number / 10) * 10]
                . " " . $words[$number % 10] . " "
                . $digits[$counter] . $plural . " " . $hundred;
        } else $str[] = null;
    }
    $str = array_reverse($str);
    $result = implode('', $str);
    $p = $result . "Bath  ";
    return  $p;
}
function convert_number_to_words($number)
{

    $hyphen      = '-';
    $conjunction = ' and ';
    $separator   = ', ';
    $negative    = 'negative ';
    $decimal     = ' point ';
    $dictionary  = array(
        0                   => 'zero',
        1                   => 'one',
        2                   => 'two',
        3                   => 'three',
        4                   => 'four',
        5                   => 'five',
        6                   => 'six',
        7                   => 'seven',
        8                   => 'eight',
        9                   => 'nine',
        10                  => 'ten',
        11                  => 'eleven',
        12                  => 'twelve',
        13                  => 'thirteen',
        14                  => 'fourteen',
        15                  => 'fifteen',
        16                  => 'sixteen',
        17                  => 'seventeen',
        18                  => 'eighteen',
        19                  => 'nineteen',
        20                  => 'twenty',
        30                  => 'thirty',
        40                  => 'fourty',
        50                  => 'fifty',
        60                  => 'sixty',
        70                  => 'seventy',
        80                  => 'eighty',
        90                  => 'ninety',
        100                 => 'hundred',
        1000                => 'thousand',
        100000             => 'lakh',
        10000000          => 'crore'
    );

    if (!is_numeric($number)) {
        return false;
    }

    if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
        // overflow
        trigger_error(
            'convert_number_to_words only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
            E_USER_WARNING
        );
        return false;
    }

    if ($number < 0) {
        return $negative . $this->convert_number_to_words(abs($number));
    }

    $string = $fraction = null;

    if (strpos($number, '.') !== false) {
        list($number, $fraction) = explode('.', $number);
    }

    switch (true) {
        case $number < 21:
            $string = $dictionary[$number];
            break;
        case $number < 100:
            $tens   = ((int) ($number / 10)) * 10;
            $units  = $number % 10;
            $string = $dictionary[$tens];
            if ($units) {
                $string .= $hyphen . $dictionary[$units];
            }

            break;
        case $number < 1000:
            $hundreds  = $number / 100;
            $remainder = $number % 100;
            $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
            if ($remainder) {
                $string .= $conjunction . $this->convert_number_to_words($remainder);
            }
            break;
        case $number < 100000:
            $thousands   = ((int) ($number / 1000));
            $remainder = $number % 1000;

            $thousands = $this->convert_number_to_words($thousands);

            $string .= $thousands . ' ' . $dictionary[1000];
            if ($remainder) {
                $string .= $separator . $this->convert_number_to_words($remainder);
            }
            break;
        case $number < 10000000:
            $lakhs   = ((int) ($number / 100000));
            $remainder = $number % 100000;

            $lakhs = $this->convert_number_to_words($lakhs);

            $string = $lakhs . ' ' . $dictionary[100000];
            if ($remainder) {
                $string .= $separator . $this->convert_number_to_words($remainder);
            }
            break;
        case $number < 1000000000:
            $crores   = ((int) ($number / 10000000));
            $remainder = $number % 10000000;

            $crores = $this->convert_number_to_words($crores);

            $string = $crores . ' ' . $dictionary[10000000];
            if ($remainder) {
                $string .= $separator . $this->convert_number_to_words($remainder);
            }
            break;
        default:
            $baseUnit = pow(1000, floor(log($number, 1000)));
            $numBaseUnits = (int) ($number / $baseUnit);
            $remainder = $number % $baseUnit;
            $string = $this->convert_number_to_words($numBaseUnits) . ' ' . $dictionary[$baseUnit];
            if ($remainder) {
                $string .= $remainder < 100 ? $conjunction : $separator;
                $string .= $this->convert_number_to_words($remainder);
            }
            break;
    }

    if (null !== $fraction && is_numeric($fraction)) {
        $string .= $decimal;
        $words = array();
        foreach (str_split((string) $fraction) as $number) {
            $words[] = $dictionary[$number];
        }
        $string .= implode(' ', $words);
    }

    return $string;
}

function convertMoneyToTH($number)
{
    $txtnum1 = array('ศูนย์', 'หนึ่ง', 'สอง', 'สาม', 'สี่', 'ห้า', 'หก', 'เจ็ด', 'แปด', 'เก้า', 'สิบ');
    $txtnum2 = array('', 'สิบ', 'ร้อย', 'พัน', 'หมื่น', 'แสน', 'ล้าน');
    $number = str_replace(",", "", $number);
    $number = str_replace(" ", "", $number);
    $number = str_replace("บาท", "", $number);
    $number = explode(".", $number);

    $strlen = strlen($number[0]);
    $convert = '';
    for ($i = 0; $i < $strlen; $i++) {
        $n = substr($number[0], $i, 1);
        if ($n != 0) {
            if ($i == ($strlen - 1) && $n == 1) {
                $convert .= 'หนึ่ง';
            } elseif ($i == ($strlen - 2) && $n == 2) {
                $convert .= 'ยี่';
            } elseif ($i == ($strlen - 2) && $n == 1) {
                $convert .= '';
            } else {
                $convert .= $txtnum1[$n];
            }
            $convert .= $txtnum2[$strlen - $i - 1];
        }
    }
    $convert .= 'บาท';
    if ($number[1] == '0' or $number[1] == '00' or $number[1] == '') {
        $convert .= 'ถ้วน';
    } else {
        $strlen = strlen($number[1]);
        for ($i = 0; $i < $strlen; $i++) {
            $n = substr($number[1], $i, 1);
            if ($n != 0) {
                if ($i == ($strlen - 1) && $n == 1) {
                    $convert .= 'เอ็ด';
                } elseif ($i == ($strlen - 2) && $n == 2) {
                    $convert .= 'ยี่';
                } elseif ($i == ($strlen - 2) && $n == 1) {
                    $convert .= '';
                } else {
                    $convert .= $txtnum1[$n];
                }
                $convert .= $txtnum2[$strlen - $i - 1];
            }
        }
        $convert .= 'สตางค์';
    }
    return $convert;
}



function getgruopDepNameS($mysqli, $gropDepId, $fullname)
{
    $sql = "   SELECT
        department.dep_name,
        department.dep_title
        FROM
        department
        WHERE
        department.dep_id in ({$gropDepId})
          ";
    $q = $mysqli->query($sql);
    $numrow = $q->num_rows;
    $strName = "";
    $i = 0;
    while ($row = $q->fetch_assoc()) {
        if ($i > 0) {
            $strName .= ",";
        }
        if ($fullname) {
            $Name = $row["dep_name"];
        } else {
            $Name = $row["dep_title"];
        }
        $strName .=  $Name;
        $i++;
    }



    return  $strName;
}


function getDepName($mysqli,  $depId, $fullname)
{
    $sql = "   SELECT
department.dep_name,
department.dep_title
FROM
department
WHERE
department.dep_id ='{$depId}'
  ";
    $q = $mysqli->query($sql);
    $numrow = $q->num_rows;
    $row = $q->fetch_assoc();
    if ($fullname) {
        $Name = $row["dep_name"];
    } else {
        $Name = $row["dep_title"];
    }
    return  $Name;
}

function getNameBoard($mysqli, $email)
{
    $fullname = "";
    $sql = "  SELECT
    tbl_members.*     FROM `tbl_members`
    WHERE
    tbl_members.cmuitaccount  = '{$email}' ";

    $q = $mysqli->query($sql);
    $numrow = $q->num_rows;
    if ($numrow > 0) {
        $rs = $q->fetch_assoc();
        $fullname = $rs["position_work"].$rs["firstname_TH"] . " " . $rs["lastname_TH"]." (".$rs["position"].")";
    }
    return  $fullname;
}


function getName_tblMember($mysqli, $email, $DepName)
{
    $fullname = "";


    $sql = "
	  SELECT
		tbl_members.*,
		department.dep_title,
		department.dep_name,
		department.title as dep_title2
		FROM
		tbl_members
		left JOIN department ON tbl_members.dep_id = department.dep_id
	WHERE
    tbl_members.cmuitaccount  = '{$email}' 
	  ";
    $q = $mysqli->query($sql);
    $numrow = $q->num_rows;
    if ($numrow > 0) {
        $rs = $q->fetch_assoc();
		  if ((int)$rs["is_step_secretary"])  {
		     $fullname = $rs["prename_TH"] .$rs["firstname_TH"] . " " . $rs["lastname_TH"]." (".$rs["position"].")";
		  }else     if ((int)$rs["is_dean"]) {
            $fullname = $rs["position_work"] . " " . $rs["firstname_TH"] . " " . $rs["lastname_TH"]." (".$rs["position"].")";
        } else {
		  		if((int)$rs["typeposition_id"]==1) {
				 $fullname =  $rs["position_work"].$rs["firstname_TH"] . " " . $rs["lastname_TH"];
				}else {
				 $fullname =  $rs["prename_TH"].$rs["firstname_TH"] . " " . $rs["lastname_TH"];
				}
           
				
            if ((int)$DepName==1) {
                $fullname .= " (" . $rs["dep_title"] . ")";
            }else   if ((int)$DepName==2) {
				 $fullname .= " (" . $rs["dep_title2"] . ")";
				}
        }
    }

    return  $fullname;
}




function getFullNameCMUAcount($mysqli,  $email)
{
    $fullname = "";
    $sql = " SELECT
tbl_members.prename_TH,
tbl_members.firstname_TH,
tbl_members.lastname_TH
FROM
tbl_members
WHERE
tbl_members.cmuitaccount  = '{$email}' ";

    $q = $mysqli->query($sql);
    $numrow = $q->num_rows;
    if ($numrow > 0) {

        $rs = $q->fetch_assoc();
        $fullname = $rs["prename_TH"] . $rs["firstname_TH"] . " " . $rs["lastname_TH"];
    }
    return  $fullname;
}
function getNameCMUAcount($mysqli,  $email)
{
    $fullname = "";
    $sql = " SELECT
tbl_set_cmuitaccount.prename_TH,
tbl_set_cmuitaccount.firstname_TH,
tbl_set_cmuitaccount.lastname_TH
FROM
tbl_set_cmuitaccount
WHERE
tbl_set_cmuitaccount.cmuitaccount  = '{$email}' ";

    $q = $mysqli->query($sql);
    $numrow = $q->num_rows;
    if ($numrow > 0) {

        $rs = $q->fetch_assoc();
        $fullname = $rs["prename_TH"] . $rs["firstname_TH"] . " " . $rs["lastname_TH"];
    }
    return  $fullname;
}



function ShowQuotation($id)
{
    $str = "";
    if (!empty($id)) {
        $st_year = substr($id, 0, 2);
        $st_no = substr($id, 2);
        $str = "ENG" . $st_year . "-" . $st_no;
    }
    return $str;
}




function getReciptName($mysqli_pay, $token)
{
    $sql = " SELECT  `tbl_customer`.*  FROM  `tbl_customer`	WHERE  `tbl_customer`.token =  '{$token}'   LIMIT  1 ;";
    $qr = $mysqli_pay->query($sql);
    $profile = $qr->fetch_assoc();
    $receipt_no = $profile["receipt_no"];
    return  $receipt_no;
}

function CTDateReport($date, $short)
{
    //26/09/2023
    $tempd = explode("/", $date);
    if ((int)$short) {
        $MONTH = array("", "ม.ค.", "ก.พ.", "มี.ค.", "เม.ย.", "พ.ค.", "มิ.ย.", "ก.ค.", "ส.ค.", "ก.ย.", "ต.ค.", "พ.ย.", "ธ.ค.");
    } else {
        $MONTH = array("", "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฏาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน.", "ธันวาคม");
    }



    $y = (int)$tempd[2] + 543;
    $m = $MONTH[$tempd[1] + 0];
    $d = $tempd[0];
    return $d . " " . $m . " " . $y;
}

function  getMYearTh($date)
{
    $MONTH = array(1 => "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");

    $dt = explode("-", $date);
    if ($dt[0] != "0000" || $dt[0] != "") {
        $tyear = (int)$dt[0];
        $dt[0] = $dt[2] + 0;
        $dt[1] = $MONTH[$dt[1] + 0];
        $dt[2] = ($tyear + 543);
    } else {
        $dt = "";
    }
    $val =      $dt[1] . "  " . $dt[2];
    return $val;
}

function ConvertToEnDate($dt)
{
    //2018-09-17
    $temp = explode("-", $dt);
    $MONTH = array("", "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
    $val = $temp[2] . " " . $MONTH[(int)$temp[1]] . " " . $temp[0];
    return $val;
}

function ConvertToThaiDate_POST($date, $short)
{
    //02 มีนาคม 2023


    if ($short) {
        $MONTH = array("", "ม.ค.", "ก.พ.", "มี.ค.", "เม.ย.", "พ.ค.", "มิ.ย.", "ก.ค.", "ส.ค.", "ก.ย.", "ต.ค.", "พ.ย.", "ธ.ค.");
    } else {
        $MONTH = array(1 => "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
    }

    $dt = explode(" ", $date);


    $tyear = (int)$dt[2] + 543;

    $strdate =      $dt[0] . " " .    $dt[1] . " " .    $tyear;

    return $strdate;
}



function ConvertToThaiDate($date, $short)
{
    $chk =   substr($date, 0, 4);
    if (($chk == "0000")  || ($chk == "") || ((int)$chk == 0)) {
        return "";
    } else {
        if ($short) {
            $MONTH = array("", "ม.ค.", "ก.พ.", "มี.ค.", "เม.ย.", "พ.ค.", "มิ.ย.", "ก.ค.", "ส.ค.", "ก.ย.", "ต.ค.", "พ.ย.", "ธ.ค.");
        } else {
            $MONTH = array(1 => "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
        }
        $dt = explode("-", $date);
        if ($dt[0] != "0000" || $dt[0] != "") {
            $tyear = (int)$dt[0];
            $dt[0] = $dt[2] + 0;
            $dt[1] = $MONTH[$dt[1] + 0];
            $dt[2] = ($tyear + 543);
        } else {
            $dt = "";
        }
        return join(" ", $dt);
    }
}

function ConvertToThaiDateAndTime($date, $short)
{
    //2023-12-19 09:28:42

    $times = substr($date, 10, 6) . " น.";

    $chk =   substr($date, 0, 4);
    if (($chk == "0000")  || ($chk == "") || ((int)$chk == 0)) {
        return "";
    } else {
        if ($short) {
            $MONTH = array("", "ม.ค.", "ก.พ.", "มี.ค.", "เม.ย.", "พ.ค.", "มิ.ย.", "ก.ค.", "ส.ค.", "ก.ย.", "ต.ค.", "พ.ย.", "ธ.ค.");
        } else {
            $MONTH = array(1 => "มกราคม", "กุมภาพันธ์", "มีนาคม", "เมษายน", "พฤษภาคม", "มิถุนายน", "กรกฎาคม", "สิงหาคม", "กันยายน", "ตุลาคม", "พฤศจิกายน", "ธันวาคม");
        }
        $dt = explode("-", $date);
        if ($dt[0] != "0000" || $dt[0] != "") {
            $tyear = (int)$dt[0];
            $dt[0] = $dt[2] + 0;
            $dt[1] = $MONTH[$dt[1] + 0];
            $dt[2] = ($tyear + 543);
        } else {
            $dt = "";
        }

        $dateshow  =  join(" ", $dt);

        return           $dateshow . " " . $times;
    }
}




function  convertArrayToComma($dataArr)
{
    $val = implode(",", $dataArr);
    return $val;
}
//เลชานุการ 
function getSecretary($mysqli, $cmuitaccount)
{
    $sql = "SELECT  tbl_members.is_step_secretary FROM tbl_members WHERE tbl_members.cmuitaccount  ='{$cmuitaccount}'     ";
    $q = $mysqli->query($sql);
    $rs =  $q->fetch_assoc();
    return (int)$rs["is_step_secretary"];
}

//เลขานุการ 
function getHeadFis($mysqli, $cmuitaccount)
{
    $sql = "SELECT  tbl_members.is_step_headfis FROM  tbl_members  WHERE  tbl_members.cmuitaccount  ='{$cmuitaccount}'     ";
    $q = $mysqli->query($sql);
    $rs =  $q->fetch_assoc();
    return (int)$rs["is_step_headfis"];
}

function getCaptainEng($mysqli, $cmuitaccount)
{
    $sql = "SELECT  tbl_members.is_step_dean  FROM tbl_members WHERE tbl_members.cmuitaccount  ='{$cmuitaccount}'     ";
    $q = $mysqli->query($sql);
    $rs =  $q->fetch_assoc();
    return (int)$rs["is_step_dean"];
}



function agoTimes($inputSeconds)
{

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


    if ((int)$minutes < 10) $minutes = "0" . $minutes;

    if ((int)$days > 0) {
        $return = $days . " วัน";
        if ((int)$hours > 0) {
            $return .= " " . $hours . " ชม";
        }
    } elseif ((int)$hours > 0) {
        $return = $hours;
        if ((int)$minutes > 0) {
            $return .= ":" . $minutes . " ชม";
        }
    } elseif ((int)$minutes > 0) $return = (int)$minutes . " นาที";
    else $return = $seconds . " วินาที";
    return $return;
}


function getUserPositions($mysqli, $email)
{
    $isStatus = false;
    $sql = " 
          SELECT
            tbl_members.is_dean
            FROM
            tbl_members
            WHERE
            tbl_members.cmuitaccount = '{$email}' 
        ";
    $q = $mysqli->query($sql);
    $row = $q->fetch_assoc();
    if ((int)$row["is_dean"] == 1) {
        $isStatus = true;
    }
    return $isStatus;
}

function getAcountFIS($mysqli) {
       $sql =  "  SELECT tbl_members.cmuitaccount FROM tbl_members WHERE tbl_members.is_finance = 1 ";
       $qfis = $mysqli->query($sql);
       $rows =  $qfis->fetch_assoc();
	   return $rows["cmuitaccount"];
}

function getAcountDean($mysqli) {
       $sql =  "  SELECT tbl_members.cmuitaccount FROM tbl_members WHERE tbl_members.is_step_dean = 1 ";
       $qDesn = $mysqli->query($sql);
       $rows =  $qDesn->fetch_assoc();
	   return $rows["cmuitaccount"];
}

function checkUserPlan($mysqli, $email)
{
    $sql =  "  SELECT tbl_members.is_step_plan FROM tbl_members WHERE tbl_members.cmuitaccount ='{$email}' ";
    $qchkFIS  =  $mysqli->query($sql);
    $rowsChkfis =  $qchkFIS->fetch_assoc();
    return  $rowsChkfis["is_step_plan"];
}
function checkUserFIS($mysqli,$email)
{
    $sql =  "  SELECT tbl_members.is_finance FROM tbl_members WHERE tbl_members.cmuitaccount ='{$email}' ";
    $qchkFIS  =  $mysqli->query($sql);
    $rowsChkfis =  $qchkFIS->fetch_assoc();
    return  $rowsChkfis["is_finance"];
}
function checkUserPlan_report($mysqli, $email)
{
    $sql =  "  SELECT tbl_members.is_step_plan FROM tbl_members WHERE tbl_members.cmuitaccount ='{$email}' ";
    $qchkFIS  =  $mysqli->query($sql);
    $rowsChkfis =  $qchkFIS->fetch_assoc();
    return  $rowsChkfis["is_step_plan"];
}
function checkUserFIS_report($mysqli,$email)
{
    $sql =  "  SELECT tbl_members.is_finance FROM tbl_members WHERE tbl_members.cmuitaccount ='{$email}' ";
    $qchkFIS  =  $mysqli->query($sql);
    $rowsChkfis =  $qchkFIS->fetch_assoc();
    return  $rowsChkfis["is_finance"];
}


function getUserManageSequence($mysqli, $email)
{
    $isStatus = false;
    $sql = " 
          SELECT
            tbl_members.is_finance,
            tbl_members.is_manage_sequence
            FROM
            tbl_members
            WHERE
            tbl_members.cmuitaccount = '{$email}' 
        ";
    $q = $mysqli->query($sql);
    $row = $q->fetch_assoc();
    if ((int)$row["is_finance"] == 1 || (int)$row["is_manage_sequence"] == 1) {
        $isStatus = true;
    }
    return $isStatus;
}

function chkClickIsPassProcess_report($mysqli, $project_id, $cmuID)
{
    $sql = " SELECT
    tbl_assign_process_report.is_pass
    FROM
    tbl_assign_process_report
    WHERE
    tbl_assign_process_report.assignCmuitaccount = '{$cmuID}'
    AND  project_id='{$project_id}'
	 
	 ORDER BY assignId
  DESC
LIMIT 1 ;

";
    $q = $mysqli->query($sql);
    $rs = $q->fetch_assoc();

    return (int)$rs["is_pass"];
}


function chkClickIsPassProcess($mysqli, $project_id, $cmuID)
{
    $sql = " SELECT
    tbl_assign_process.is_pass
    FROM
    tbl_assign_process
    WHERE
    tbl_assign_process.assignCmuitaccount = '{$cmuID}'
    AND  project_id='{$project_id}'
	 
	 ORDER BY assignId
  DESC
LIMIT 1 ;

";
    $q = $mysqli->query($sql);
    $rs = $q->fetch_assoc();

    return (int)$rs["is_pass"];
}

function chkStatusPassFIS_report($mysqli, $project_id)
{
    $sql = "SELECT `is_step_fis_report` FROM `tbl_project` WHERE `project_id`= '{$project_id}' ";

    $q  =  $mysqli->query($sql);
    $rs = $q->fetch_assoc();
   //	return $sql;
    return (int)$rs["is_step_fis_report"];
}
function chkStatusPassPlan_report($mysqli, $project_id)
{
    $sql = "SELECT `is_step_plan_report` FROM `tbl_project` WHERE `project_id`= '{$project_id}' ";

    $q  =  $mysqli->query($sql);
    $rs = $q->fetch_assoc();
    //	return $sql;
    return (int)$rs["is_step_plan_report"];
}




function chkStatusPassFIS($mysqli, $project_id)
{
    $sql = "SELECT `is_step_fis` FROM `tbl_project` WHERE `project_id`= '{$project_id}' ";

    $q  =  $mysqli->query($sql);
    $rs = $q->fetch_assoc();
    //	return $sql;
    return (int)$rs["is_step_fis"];
}
function chkStatusPassPlan($mysqli, $project_id)
{
    $sql = "SELECT `is_step_plan` FROM `tbl_project` WHERE `project_id`= '{$project_id}' ";

    $q  =  $mysqli->query($sql);
    $rs = $q->fetch_assoc();
    //	return $sql;
    return (int)$rs["is_step_plan"];
}
function getCateTAssigntitle($mysqli, $cateId)
{
    $sql = " SELECT
    tbl_assign_case.case_title
    FROM
    tbl_assign_case
    WHERE
    tbl_assign_case.caseId = '{$cateId}'
    ";
    $q = $mysqli->query($sql);
    $rs = $q->fetch_assoc();
    return $rs["case_title"];
}

function getAlertNewInbox_report($mysqli, $projectId, $userEmail)
{
    $sql = "SELECT
tbl_assign_process_report.assignId,
tbl_assign_process_report.is_status
FROM
tbl_assign_process_report
WHERE
tbl_assign_process_report.assignCmuitaccount   ='{$userEmail}' AND
tbl_assign_process_report.is_read  = 0   AND tbl_assign_process_report.is_pass=0 AND
tbl_assign_process_report.project_id = '{$projectId}'

    ";
    $q = $mysqli->query($sql);
    $numrow  = $q->num_rows;
    if ((int)$numrow > 0) $status = 1;
    else $status = 0;
    return  $status;
}


function getAlertNewInbox($mysqli, $projectId, $userEmail)
{
    $sql = "SELECT
tbl_assign_process.assignId,
tbl_assign_process.is_status
FROM
tbl_assign_process
WHERE
tbl_assign_process.assignCmuitaccount   ='{$userEmail}' AND
tbl_assign_process.is_read  = 0   AND
 tbl_assign_process.is_pass=0 AND
tbl_assign_process.project_id = '{$projectId}'

    ";
    $q = $mysqli->query($sql);
    $numrow  = $q->num_rows;
    if ((int)$numrow > 0) $status = 1;
    else $status = 0;
    return  $status;
}

function setStatusProject_report($mysqli, $projectId, $status_id, $email)
{
    $SQL = " INSERT INTO `tbl_timelines_report` 
	(`projects_id`, `status_id`, `is_read`, `update_at`, `date_add`,cmuitaccount)
	VALUES ('{$projectId}', '{$status_id}', '0',now(), now() ,'{$email}') ";
    $q = $mysqli->query($SQL);
}


function setStatusProject($mysqli, $projectId, $status_id, $email)
{
    $SQL = " INSERT INTO `tbl_timelines` 
	(`projects_id`, `status_id`, `is_read`, `update_at`, `date_add`,cmuitaccount)
	VALUES ('{$projectId}', '{$status_id}', '0',now(), now() ,'{$email}') ";
    $q = $mysqli->query($SQL);
	return $SQL;
}
function getStatusId($mysqli, $project_id, $step)
{
    $sql = " SELECT
tbl_project.projectCaseID,
tbl_timeline_status.status_id
FROM
tbl_project
INNER JOIN tbl_timeline_status ON tbl_project.projectCaseID = tbl_timeline_status.project_case_id
WHERE
tbl_project.project_id = '{$project_id}'
ANd 
tbl_timeline_status.steps =  '{$step}'
";
    $q = $mysqli->query($sql);
    $rs = $q->fetch_assoc();
    return  $rs["status_id"];
}

function getStatusProjectNOW_report($mysqli, $projectId)
{

    $sql = " SELECT
tbl_timelines_report.timeline_id,
tbl_timeline_status.status_title,
tbl_timelines_report.projects_id
FROM
tbl_timelines_report
INNER JOIN tbl_timeline_status ON tbl_timelines_report.status_id = tbl_timeline_status.status_id
WHERE
tbl_timelines_report.projects_id ='{$projectId}'
ORDER BY
tbl_timelines_report.timeline_id DESC
LIMIT 1 ;

";
    $q = $mysqli->query($sql);
    $rs = $q->fetch_assoc();
     return  $rs["status_title"];
}

function getStatusProjectNOW($mysqli, $projectId)
{

    $sql = " SELECT
tbl_timelines.timeline_id,
tbl_timeline_status.status_title,
tbl_timelines.projects_id
FROM
tbl_timelines
INNER JOIN tbl_timeline_status ON tbl_timelines.status_id = tbl_timeline_status.status_id
WHERE
tbl_timelines.projects_id ='{$projectId}'
ORDER BY
tbl_timelines.timeline_id DESC
LIMIT 1 ;

";
    $q = $mysqli->query($sql);
    $rs = $q->fetch_assoc();
    return  $rs["status_title"];
}

function getProjectcaseID ($mysqli, $project_id) {
    $sql = " SELECT tbl_project.projectCaseID   FROM tbl_project where tbl_project.project_id = '{$project_id}' ";
    $q = $mysqli->query($sql);
    $rs = $q->fetch_assoc();
    return  $rs["projectCaseID"];
}


function getStatusProjectNowPercent($mysqli, $projectId)
{
    $sql = " SELECT
tbl_timelines.timeline_id,
tbl_timeline_status.status_title,
tbl_timeline_status.status_id,
tbl_timeline_status.steps,
tbl_timelines.projects_id
FROM
tbl_timelines
INNER JOIN tbl_timeline_status ON tbl_timelines.status_id = tbl_timeline_status.status_id
WHERE
tbl_timelines.projects_id ='{$projectId}'
ORDER BY
tbl_timelines.timeline_id DESC
LIMIT 1 ;

";
    $q = $mysqli->query($sql);
    $rs = $q->fetch_assoc();

    $pcasseId= getProjectcaseID($mysqli,$projectId);

    if($pcasseId==2)   {
        $ArrStep  = array("10", "20", "30", "20", "50" ,"80", "90", "100", "100");
    } if($pcasseId==3)   {
        $ArrStep  = array("10", "20", "40", "20", "50" ,"80", "90", "100", "100");
    }else {
        $ArrStep  = array("10", "20", "40", "20","60","80" ,"90", "100", "100");
    }
    

    $perNow = $ArrStep[$rs["steps"]];

    return  $perNow;
}


function getStatusProjectNowPercent_report($mysqli, $projectId)
{
    $sql = " SELECT
tbl_timelines_report.timeline_id,
tbl_timeline_status.status_title,
tbl_timeline_status.status_id,
tbl_timeline_status.steps,
tbl_timelines_report.projects_id
FROM
tbl_timelines_report
INNER JOIN tbl_timeline_status ON tbl_timelines_report.status_id = tbl_timeline_status.status_id
WHERE
tbl_timelines_report.projects_id ='{$projectId}'
ORDER BY
tbl_timelines_report.timeline_id DESC
LIMIT 1 ;

";
    $q = $mysqli->query($sql);
    $rs = $q->fetch_assoc();

    $pcasseId= getProjectcaseID($mysqli,$projectId);

    if($pcasseId==2)   {
        $ArrStep  = array("10", "20", "30", "20", "50" ,"80", "90", "100", "100");
    } if($pcasseId==3)   {
        $ArrStep  = array("10", "20", "40", "20", "50" ,"80", "90", "100", "100");
    }else {
        $ArrStep  = array("10", "20", "40", "20","60","80" ,"90", "100", "100");
    }
    

    $perNow = $ArrStep[$rs["steps"]];

    return  $perNow;
}








function genProjectCode($mysqli, $year, $projectNewID, $depID)
{
    $sql = " SELECT
department.title,
department.dep_id
FROM `department`
WHERE
department.dep_id = '{$depID}' 
";
    $q = $mysqli->query($sql);
    $rsDep = $q->fetch_assoc();
    $depTitle = "";
    if (empty($rsDep["title"])) {
        $depTitle = "00";
    } else {
        $depTitle = $rsDep["title"];
    }

    $code = "";
    $q =  $mysqli->query("SELECT tbl_project.pyear,tbl_project.pno  
    FROM   tbl_project   WHERE  tbl_project.pyear = '{$year}'   
    ORDER BY  tbl_project.pno DESC  ");
    $rs =   $q->fetch_assoc();
    $NewID  =  ((int)$rs["pno"] + 1);

    if ($NewID < 100 && $NewID >= 10) {
        $newID2 = "0" . $NewID;
    } else if ($NewID < 10 && $NewID > 0) {
        $newID2 = "00" . $NewID;
    } else {
        $newID2 = $NewID;
    }
    $years =  substr($year, 2);
    $code = "EPRO" . $years . "-" . $depTitle . "-" . $newID2;
    // Update หมายเลขรหัสโครงการเพิ่ม  RUN NUMBER   
    $mysqli->query("update  `tbl_project`  set pno = '{$NewID}'  ,  project_code='{$code}' where project_id = '{$projectNewID}'   AND  is_confirm = 0 AND  is_step_input =0   ");

    return $code;
}

function  getOwnerProject($mysqli, $projec_id)
{
    $sql = " SELECT  tbl_project.cmuitaccount  FROM `tbl_project` WHERE tbl_project.project_id   ='{$projec_id}' ";
    $q = $mysqli->query($sql);
    $rs  =     $q->fetch_assoc();
    return $rs["cmuitaccount"];
}

function  getstepDeputy($mysqli, $cmuitaccount, $projec_id)
{
    $statusDean = 0 ;
    $sql = "SELECT  tbl_members.is_dean,tbl_members.position, is_step_secretary ,is_step_dean  FROM tbl_members WHERE tbl_members.cmuitaccount  ='{$cmuitaccount}'     ";
    $q = $mysqli->query($sql);
    $dean =  $q->fetch_assoc();

    // ตรวจสอบว่าเป็น ผู้บริหาร และ รองคณบดี
    
    if ((int)$dean["is_dean"] == 1 && (int)$dean["is_step_dean"]==0  && (int)$dean["is_step_secretary"]==0 ) {
          // เช็คสถานะว่าผ่านการเงินมาแล้ว และผ่าน เลขา 
        $sql = " SELECT
                tbl_project.is_step_input,
                tbl_project.is_step_fis,
                tbl_project.is_secretary
                FROM
                tbl_project
                WHERE
                tbl_project.project_id = '{$projec_id}'
                ";
        $q2 = $mysqli->query($sql);
        $project = $q2->fetch_assoc();
        if (((int)$project["is_step_fis"] == 1) ||  ((int)$project["is_secretary"] == 1)) {
            $statusDean  = 1;
        }
    }
    return   $statusDean ;
}

function daysBetweenDates($date1, $date2) {
    // สร้าง DateTime จากวันที่ที่กำหนด
    $start_date = DateTime::createFromFormat('d/m/Y', $date1);
    $end_date = DateTime::createFromFormat('d/m/Y', $date2);
    
    // หาความแตกต่างของวันที่
    $difference = $start_date->diff($end_date);

    // คืนค่าจำนวนวันที่ต่างกัน
    return $difference->days;
}