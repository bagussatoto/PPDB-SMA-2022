<?php 
@ini_set("display_errors","1");
@ini_set("display_startup_errors","1");

require_once("include/dbcommon.php");

$shortTable = postvalue("table");
$table = GetTableByShort( $shortTable );
if( !$table )
	exit(0);

$pageName = postvalue('page');
$ptype = postvalue("ptype");
$field = postvalue("field");

if( !Security::userHasFieldPermissions( $table, $field, $ptype, $pageName, true ) )
	return;

$pSet = new ProjectSettings($table, $ptype, $pageName);
$gQuery = $pSet->getSQLQuery();

$_connection = $cman->byTable( $table );	
	
//	construct sql
$data = false;
if( @$_GET["action"]<>"add" )
{
	$keysArr = $pSet->getTableKeys();
	$keys = array();
	foreach ($keysArr as $ind=>$k)
		$keys[$k] = postvalue("key".($ind+1));
	
	$where = KeyWhere($keys, $table);
	if($pSet->getAdvancedSecurityType() == ADVSECURITY_VIEW_OWN)
		$where = whereAdd($where,SecuritySQL("Search", $table ));	
	
	$sql = $gQuery->gSQLWhere($where);
	$qResult = $_connection->query( $sql );
	if(!$qResult)
		return;
	$data = $qResult->fetchAssoc();
}
else 
{
	$data = array();
	$data[$field] = @$_SESSION[$table."_".$field."_rte"];
}

$nWidth = $pSet->getNCols($field);
$nHeight = $pSet->getNRows($field);
if( $nHeight< 300 )
	$nHeight = 300;
$id = postvalue_number("id");
$cfieldname = GoodFieldName($field)."_".($id!=='' ? $id : '1');
$cfield = "value_".GoodFieldName($field)."_".($id!=='' ? $id : '1');
$onsubmit = "";
if(@$_REQUEST["browser"]!="ie")
	$onsubmit = "onsubmit = \"return document.getElementById('".$cfield."').value;\"";
	
echo '<html>
		<body style="margin:0;">
		<form name="innovaform" '.$onsubmit.'>
			<script type="text/javascript" src="'. GetFullSiteUrl() . ("plugins/innovaeditor/scripts/innovaeditor.js").'"></script>
			<textarea id="'.$cfield.'" name="'.$cfield.'" style="width:100%; height:'.($nHeight).'px;">';
			
if($data)
	echo runner_htmlspecialchars($data[$field]);
	
echo'		</textarea>
			<script>
				oEdit'.$cfieldname.' = new InnovaEditor("oEdit'.$cfieldname.'");
				oEdit'.$cfieldname.'.mode = "HTMLBody";
				oEdit'.$cfieldname.'.width = "100%";
				oEdit'.$cfieldname.'.height = "'.($nHeight).'px";
				oEdit'.$cfieldname.'.cmdAssetManager = "modalDialogShow(\"'. GetFullSiteUrl() .'plugins/innovaeditor/assetmanager/'.GetTableLink("assetmanager").'\",640,445);";
				oEdit'.$cfieldname.'.arrCustomButtons = [["AssetManager", oEdit'.$cfieldname.'.cmdAssetManager, "Asset Manager", "btnCustom1.gif"]];
				//oEdit'.$cfieldname.'.features = ["Preview"];
				oEdit'.$cfieldname.'.REPLACE("'.$cfield.'");
			</script>
		</form>
	</body>
</html>';
return;
?>