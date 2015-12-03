<?php

$con = new mysqli("103.21.59.166:3306", "appcom_sensor", "sensor123", "appcom_sensor_data");

if ($con->connect_errno) {
    printf("Connect failed: %s\n", $mysqli->connect_error);
    exit();
}

$con->options(MYSQLI_OPT_CONNECT_TIMEOUT, 500);
$queryOfDeviceNo = "SELECT DISTINCT device_serial_no FROM trial_pollution";
  $sql=mysqli_query($con,$queryOfDeviceNo);
  $device_serial_no=array();
  while($rowdata = mysqli_fetch_assoc($sql)){
	  $device_serial_no[]=$rowdata;
  }
  //mysqli_close($con);
//echo json_encode($device_serial_no);
	date_default_timezone_set('Asia/Kolkata');
	$currentDateTime = date("Y-m-d H:i:s");
	foreach($device_serial_no as $value){
		
        $deviceSerailNo=implode(",",$value);
        $con->options(MYSQLI_OPT_CONNECT_TIMEOUT, 500);
        $queryOfAVG = "SELECT avg(co)as co FROM trial_pollution 
                        WHERE sen_time >=DATE_SUB('$currentDateTime', INTERVAL 8 HOUR) AND device_serial_no='$deviceSerailNo' ";
                    
         $isAvarage = mysqli_query($con,$queryOfAVG);
		 $avgValue = mysqli_fetch_row($isAvarage);
         $COAvgValue = $avgValue[0];
		 
                $count=mysqli_num_rows($isAvarage);
                if($count==1) {
                    $con->options(MYSQLI_OPT_CONNECT_TIMEOUT, 500);
                    $query = "INSERT INTO sensorAverage(co,device_serial_no) VALUES ('$COAvgValue','$deviceSerailNo') ";
					$isInserted = mysqli_query($con,$query);						
						if($isInserted) {
							echo "inserted";
						} else{
							echo "Error";
						}                     
                } else{
                    echo"Cant Calculate Avg"; 
                } 
        
	}

?>