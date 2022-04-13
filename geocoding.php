<?php
if( $_SERVER['PHP_SELF'] == '/'.GetTableLink("geocoding") )
{
	echo "<!DOCTYPE html>
		  <html>
			<head>
				<style type='text/css'>
					div#message {
						width: 300px;
						height: 50px auto;
						background: #fff;
						border-radius: 10px;
						margin: 100px auto 10px;
						box-shadow: 10px 20px 30px rgba(0,0,0,0.4);
						text-align: left;
						padding: 10px 20px 10px 20px;
						font: 14px Arial, sans-serif;
					}								
				</style>
			</head>
			<body style='background: #bbb'>				         
				<div id='message'> 
					<h3> How-to </h3>	
					<p> Add the following line to the 'Before process' event of your list page with a 'View as a Map' field: </p> 
					<hr />
					<p> &nbsp;&nbsp;include_once(\"geocoding.php\");</p>
					<hr />
					<p> Set for your 'View as a Map' field 'Address', 'Latitude', 'Longitude' field names. 
					 (You can also define them manually in the event's code.)</p>
					<p> Run you list page contains one 'View as a Map' field with the 'geocoding=1' parameter,  eg:</p>
					<hr />
					<p> &nbsp;&nbsp;".GetTableLink("map_list", "", "geocoding=1")."</p>
					<hr />
							
				</div>
			</body>    
		  </html>
	";  
	exit();
}

if( postvalue("geocoding") )
{
	global $cman, $gSettings, $strTableName, $strOriginalTableName;

	$_connection = $cman->byTable( $strTableName );
	$origTableName = $strOriginalTableName;
	$goodTableName = goodFieldName($strTableName);

	$gData = $gSettings->getGeocodingData();
	$addressFields = array();
	
	if( !isset($addressField) )
		$addressFields = $gData["addressFields"];
	else
		$addressFields[] = $addressField;
		
	if( !isset($latField) ) 	
		$latField = $gData["latField"];
	
	if( !isset($lngField) )	
		$lngField = $gData["lngField"];
	
	// an address field is not defined or lat&lng fields are undefined while an address field is set 
	if( !isset($addressField) && !count( $addressFields ) ||  $latField == "" && $lngField == "" ) 
	{  
		foreach($gSettings->getFieldsList() as $field)
		{
			if( $gSettings->getViewFormat($field) == 'Map' )
				$fieldsMapData[] = $gSettings->getMapData($field);
		}
		if( count($fieldsMapData) > 0 )
		{
			if( !count( $addressFields ) )
				$addressFields[] = $fieldsMapData[0]['address'];
			
			if( $latField == "" )
				$latField = $fieldsMapData[0]['lat'];
				
			if( $lngField == "" )
				$lngField = $fieldsMapData[0]['lng'];
		}	
	}
	  
	if( !postvalue("ajax") ) 
	{
		if( !count($addressFields) ||  $latField == ""  || $lngField == "" )
		{ 
			//some fields are empty
			echo "<!DOCTYPE html>
				  <html>
					<head>
						<style type='text/css'>
							div#message {
								width: 300px;
								height: 50px;
								background: #fff;
								border-radius: 10px;
								margin: 0px auto 10px;
								box-shadow: 10px 20px 30px rgba(0,0,0,0.4);
								text-align:center;
								padding: 30px;
								font: 16px Arial, sans-serif;
							}
							div#inf  {
								width: 300px;
								height: 60px;
								background: #fff;
								border-radius: 10px;
								margin: 100px auto 30px auto;
								padding: 10px 10px 10px 50px;
								box-shadow: 10px 20px 30px rgba(0,0,0,0.4);
								text-align: left;
								font: 16px Arial, sans-serif;				
							}									
						</style>
					</head>
					<body style='background: #bbb'>
						<div id='inf'> The 'Address' fields names: &nbsp;&nbsp;&nbsp;".(count($addressFields) ? implode(" ", $addressFields) : "&mdash;")
							."<br> The 'Latitude' field name:&nbsp;&nbsp;&nbsp; ".($latField ? $latField : "&mdash;")
							."<br> the 'Longtitude' field name: ".($lngField ? $lngField : "&mdash;")
						."<br></div>            
						<div id='message'> <p> Some field names were not defined </p> </div>
					</body>    
				  </html>
			";                
			exit();
		}	
		 
		$keyFields = array();
		$addresses = array();
	   
		$query = $gSettings->getSQLQuery();
	   
		$tmpWhere = $_connection->addFieldWrappers($latField).' is NULL or '.$_connection->addFieldWrappers($lngField).' is NULL';
	   
		$fieldType = $gSettings->getFieldType($latField);
		if( IsCharType($fieldType) )
			$tmpWhere.=' or '.$_connection->addFieldWrappers($latField).'=""';
		
		$fieldType = $gSettings->getFieldType($lngField);
		if( IsCharType($fieldType) )
			$tmpWhere.=' or '.$_connection->addFieldWrappers($lngField).'=""';
		
		$query->addWhere( $tmpWhere );
		  
		$qResult = $_connection->query( $query->tosql() ); 
		while( $data = $qResult->fetchAssoc() )
		{
			$address = "";
			foreach ($addressFields as $aField )
			{
				if ( isset( $data[ $aField ] ) && strlen( trim( $data[ $aField ] ) ) )
					$address.= trim( $data[ $aField ] ) . " ";
			}			
			
			$addresses[] = $address;
			
			$items = array();
			foreach($gSettings->getTableKeys() as $name)
			{
				$items[ $name ] = $data[ $name ];
			}
			$keyFields[] = $items;       
		}
		echo "<!DOCTYPE html>
		  <html>
			<head>
				<title> Geocoding </title>
				<script src='".GetRootPathForResources("include/jquery.js")."'></script>
				<script type='text/javascript'>
					function initialize() {
						var adresses = [],
							keyFields = [],
							promises = [],
							updates =[],
							errors = [], 
							i;
					   
						keyFields = ".my_json_encode($keyFields).";
						addresses = ".my_json_encode($addresses).";

						//traversing
						if (addresses.length > 0) {
							$('#progress p').text(addresses.length + ' address field(s) in progress');                   
							$('#progress').fadeIn();
					   
							for (i = 0; i < addresses.length; i++) {
								setTimeout( (function(i) {
									var d = $.Deferred();
									promises.push(d);
									
									return function() {
										var update = {
											keyfields: keyFields[i],
											addr: addresses[i]
										};    
										
										$('#bar').css('width', Math.round(100 * i/ (addresses.length - 1)) + '%');
										$('#counts').html((i + 1) + '/' + addresses.length + ' processed');								
										
										$.post('".GetTableLink( $goodTableName, "list")."', {ajax: 1, geocoding: 1, update: update}, function(status) {
											if (status != 'OK') {
												//Geocode was not successful
												
											    $('#error')
													.text( status )
													.fadeIn()
													.fadeOut();
													
												$('#error_report2')
													.show();
												
												errors.push('<br>&nbsp;&nbsp;' + status + ', ' + update.addr);
												
												$('div#error_list')
													.html( '<br>Geocode was not successful <br> for the&nbsp;' 
														+ errors.length + '&nbsp;address(es) <br> for the following reasons:' + errors.join('') );                                       
											}
											d.resolve();
										});  
									};
								})(i), 300 * i);

							}
							
							$.when.apply(null, promises).done( function() {
								setTimeout( function() {
									$('#progress').hide();                       
									$('#message')
										.hide()
										.fadeIn();
									
									$('#message p')
										.html('All done<br><br><br><button class=\'runner-btnframe\' type=\'button\' onclick=\"$(\'#message\').css(\'opacity\', 0);\">Ok</button><button class=\'runner-btnframe\' type=\'button\' onclick=\"window.location.href=\'".GetTableLink($goodTableName, "list", "geocoding=1")."\'\">Try again</button>');
									
									if (errors.length > 0) {
										$('#error_report2 span').hide();
										$('#error_list').html('<br>Geocode was not successful <br> for the&nbsp;'+ errors.length 
											+'&nbsp;address(es) <br> for the following reasons:' + errors.toString());							
									}
								}, 250);
							});		  
						} else {
							$('#message p').html('<br>All addresses were resolved before');
							$('#message').fadeIn();
						}
					}    
				</script>
				<style type='text/css'>       
					div.progress, div.message {
						width: 300px;
						height: 120px;
						background: #fff;
						border-radius: 10px;
						margin: 0px auto 10px;
						box-shadow: 10px 20px 30px rgba(0,0,0,0.4);
						text-align:center;
						padding: 30px;
						display: none;
						font: 16px Arial, sans-serif;
					}	
					div.bar-container {
						height: 24px;
						border: 1px solid #333;
						margin-bottom: 5px;
					}
					div.bar {               
						height: 100%;
						width: 0%;
						background: green;
						-webkit-transition: width 0.5s;
						-moz-transition: width 0.5s;
						-ms-transition: width 0.5s;
						-o-transition: width 0.5s;
						transition: width 0.5s;
					}
					div.error{
						display: none;
						margin: 5px;
					}
					div#error_report{
						text-align: left;
						height: auto;
					}
					div#error_report2{
						margin: 0 auto 10px;
						display: none;
						height: 150px auto;
						width: 360px;
						background: #fff;
						border-radius: 10px;
						box-shadow: 10px 20px 30px rgba(0,0,0,0.4);
						text-align: left;
						font: 16px Arial, sans-serif;	
					}
					div#error_list{
						padding-bottom: 10px;
						margin: 30px 30px auto 30px;
					}
					.runner-btnframe {
						display: inline-block;
						position: relative;
						white-space: nowrap;
						width: auto;
						z-index: 0;
						vertical-align: middle;
						margin: 0 4pt;
						font: 14px Arial, sans-serif;
						height: 30px;		
					}
					div#inf{
						width: 300px;
						height: 60px;
						background: #fff;
						border-radius: 10px;
						margin: 100px auto 30px auto;
						padding: 10px 10px 10px 50px;
						box-shadow: 10px 20px 30px rgba(0,0,0,0.4);
						text-align: left;
						font: 16px Arial, sans-serif;				
					}		
				</style>
			</head>
			<body style='background: #bbb;' onload='initialize()'>
				<div id='inf'>
				<p> Updating coordinates (<b>".$latField."</b> and <b>".$lngField."</b> fields) based on the <b>".implode(" ", $addressFields)."</b> fields values</p>	
				</div>
				<div id='progress' class='progress'>
					<p></p>
					<div id='bar-container' class='bar-container'>
						<div id='bar' class='bar'></div>
					</div>
					<div id='counts'></div>
					<div id='error' class='error'></div>
				</div>
				<div id='message' class='message'>
					<p></p>
				</div>
				<div id='error_report2'>
					<div id='error_list'></div>
				</div>
			</body>
		</html>
		";
		exit();
	}
	else 
	{
		$addr = urlencode($_POST["update"]["addr"]);
		$url = 'http://maps.googleapis.com/maps/api/geocode/json?address='.$addr.'&sensor=false';
		$get = file_get_contents($url);
		$a = my_json_decode($get);
		echo  $a['status'];
		
		if( $a['status'] == 'OK' )
		{
			$where = keyWhere($_POST["update"]["keyfields"], $strTableName);
			$lat = $a['results'][0]['geometry']['location']['lat'];
			$lng = $a['results'][0]['geometry']['location']['lng'];
			
			$sql = "update ".$_connection->addTableWrappers($origTableName)." set ".$_connection->addFieldWrappers($latField)." = ".$lat
				.", ".$_connection->addFieldWrappers($lngField)." = ".$lng." where ".$where;
			$_connection->exec( $sql );
		}  
		exit();
	}
}
?>