<?php
//	active states
function active_state($state,$id,$table,$field='state'){
	global $icons,$lang;
	if($state==1) 		$icon=$icons["tick"];
	elseif($state==0) 	$icon=$icons["cross"];
	elseif($state==2) 	$icon=$icons["pending"];
	
	return '<span class="update_state" id="state_'.$id.'" rel="'.$table.'" state="'.$state.'" field="'.$field.'" title="'.$lang["click_update_state"].'">'.$icon.'</a>';
}
//	get item
function get_item($table,$id,$sql_condition=""){
	global $db_cal;
	$sql="SELECT b.* FROM ".$table." AS b WHERE b.id='".$id."' $sql_condition LIMIT 1";
	$res=mysqli_query($db_cal,$sql) or die("Error getting item.<br>".mysqli_error($db_cal));
	if(mysqli_num_rows($res)==0) return false;
	else 						return mysqli_fetch_assoc($res);
}

//	get last order number
function get_next_order($table){
	global $db_cal;
	$sql="SELECT list_order FROM ".$table." WHERE state=1 ORDER BY list_order DESC";
	$res=mysqli_query($db_cal,$sql) or die("Error getting highest list order");
	$row=mysqli_fetch_assoc($res);
	return ($row["list_order"]+1);
}


//	add item
function add_item($table,$values,$debug=false){
	global $db_cal;
	$add_data="";
	foreach($values AS $field=>$val){
		if($field=="password") 	$add_data.="`".$field."` = md5('".$val."'),";
		else 					$add_data.="`".$field."` = '".mysqli_real_escape_string($db_cal,$val)."',";
	}
	$add_data=substr($add_data,0,-1);
	$add="INSERT INTO `".$table."` SET ".$add_data."";
	if($debug) echo $add."<br>";
	if(mysqli_query($db_cal,$add)) 	return true;
	else{
		if($debug) echo "<br>".mysqli_error($db_cal);
		return false;
	}
}
//	modify item
function mod_item($table,$id_item,$values,$debug=false){
	global $db_cal;
	$mod_data="";
	foreach($values AS $field=>$val){
		if($field=="password" && $val!="") 	$mod_data.="`".$field."` = md5('".$val."'),";
		else 								$mod_data.="`".$field."` = '".mysqli_real_escape_string($db_cal,$val)."',";
	}
	$mod_data=substr($mod_data,0,-1);
	$update="UPDATE `".$table."` SET ".$mod_data." WHERE id='".$id_item."' LIMIT 1";
	if($debug) echo $update."<br>";
	if(mysqli_query($db_cal,$update)) 	return true;
	else{
		if($debug) echo "<br>".mysqli_error($db_cal);
		return false;
	}
}

function delete_item($table,$id,$debug=false){
	global $db_cal;
	$del="DELETE FROM ".$table." WHERE id='".$id."' LIMIT 1";
	if($debug) echo $del."<br>";
	if(mysqli_query($db_cal,$del)) 	return true;
	else 						return false;
}

// multi_array_key_exists function.
function multi_array_key_exists( $needle, $haystack ) {
	foreach ( $haystack as $key => $value ) :
		if ( $needle == $key )
            return true;
        if ( is_array( $value ) ) :
             if ( multi_array_key_exists( $needle, $value ) == true )
                return true;
             else
                 continue;
        endif;
    endforeach;
    return false;
} 
?>