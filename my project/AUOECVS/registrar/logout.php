<?php
session_start();
include_once("../db_config/dbconfig.php");
//User logout History  Add
$sql1=mysqli_query($con,"Insert into logouthistory(Username)values('$_SESSION[SESS_FIRST_NAME]')");
//Backup Database in Local E
$tables = array();
$result = mysqli_query($con,"SHOW TABLES");
while($row = mysqli_fetch_row($result)){
  $tables[] = $row[0];
}

$return = '';
foreach($tables as $table){
  $result = mysqli_query($con,"SELECT * FROM ".$table);
  $num_fields = mysqli_num_fields($result);
  
  $return .= 'DROP TABLE '.$table.';';
  $row2 = mysqli_fetch_row(mysqli_query($con,"SHOW CREATE TABLE ".$table));
  $return .= "\n\n".$row2[1].";\n\n";
  
  for($i=0;$i<$num_fields;$i++){
    while($row = mysqli_fetch_row($result)){
      $return .= "INSERT INTO ".$table." VALUES(";
      for($j=0;$j<$num_fields;$j++){
        $row[$j] = addslashes($row[$j]);
        if(isset($row[$j])){ $return .= '"'.$row[$j].'"';}
        else{ $return .= '""';}
        if($j<$num_fields-1){ $return .= ',';}
      }
      $return .= ");\n";
    }
  }
  $return .= "\n\n\n";
}

//save file
$handle = fopen("E:\backup\auoecvsdb.sql","w+");
fwrite($handle,$return);
fclose($handle);
//echo "Successfully Backed up";	
unset($_SESSION['SESS_MEMBER_ID']);
header('Location:../index.php');
?>