<?php
$dalTableuser = array();
$dalTableuser["ID"] = array("type"=>3,"varname"=>"ID", "name" => "ID");
$dalTableuser["username"] = array("type"=>200,"varname"=>"username", "name" => "username");
$dalTableuser["password"] = array("type"=>200,"varname"=>"password", "name" => "password");
	$dalTableuser["ID"]["key"]=true;

$dal_info["ppdb2022_at_localhost__user"] = &$dalTableuser;
?>