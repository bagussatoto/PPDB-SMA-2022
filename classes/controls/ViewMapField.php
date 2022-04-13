<?php
class ViewMapField extends ViewControl
{
	public function showDBValue( &$data, $keylink, $html = true )
	{
		if( !$this->pageObject)
		{
			return runner_htmlspecialchars($data[$this->field]);
 		}
		elseif($this->pageObject->pageType == PAGE_EXPORT || ($this->pageObject->pageType == PAGE_RPRINT && $this->container->forExport == "excel") )
		{
			return runner_htmlspecialchars($data[$this->field]);
 		}

		if($this->pageObject->pageType != PAGE_LIST)
		{
			$mapData = $this->pageObject->addGoogleMapData($this->field, $data);
		}

		if($this->pageObject->pageType != PAGE_PRINT && $this->pageObject->pageType != PAGE_MASTER_INFO_PRINT && $this->pageObject->pageType != PAGE_RPRINT && $this->pageObject->pageType != PAGE_REPORT && !($this->pageObject->mode == VIEW_SIMPLE && $this->pageObject->pdfMode))
		{
			return '<div id="littleMap_'.GoodFieldName($this->field).'_'.$this->pageObject->recId.
				'" style="width:'.
				(!isset($this->pageObject->googleMapCfg['fieldsAsMap']) ? "300" : $this->pageObject->googleMapCfg['fieldsAsMap'][$this->field]['width']).'px; '.
				'height: '.(!isset($this->pageObject->googleMapCfg['fieldsAsMap']) ? "225" : $this->pageObject->googleMapCfg['fieldsAsMap'][$this->field]['height']).'px; '.
				'" data-gridlink class="littleMap"></div>';
		}


		$location = $this->getLocation( $mapData['markers'][0] );
		$icon = $mapData['markers'][0]['mapIcon'];

		return '<img border="0" alt="" src="'.$this->getStaticMapURL( $location, $mapData['zoom'], $icon ).'">';
	}

	function getLocation( $markerData )
	{
		if( $markerData['lat'] == "" && $markerData['lng'] == "" )
		{
			if( !$markerData['address'] )
				return '';

			if ( getMapProvider() == GOOGLE_MAPS )
				return $markerData['address'];

			$locationByAddress = getLatLngByAddr( $markerData['address'] );
			return $locationByAddress['lat'].','.$locationByAddress['lng'];
		}

		return $markerData['lat'].','.$markerData['lng'];
	}


	function getStaticMapURL( $location, $zoom, $icon )
	{
		$markerLocation = $location;
		$apiKey = $this->pageObject->googleMapCfg["APIcode"];

		$width = "300";
		$height = "225";

		if( isset($this->pageObject->googleMapCfg['fieldsAsMap'] ) )
		{
			$width = $this->pageObject->googleMapCfg['fieldsAsMap'][ $this->field ]['width'];
			$height = $this->pageObject->googleMapCfg['fieldsAsMap'][ $this->field ]['height'];
		}

		switch( getMapProvider() )
		{
			case GOOGLE_MAPS:
				global $showCustomMarkerOnPrint;

				if( $icon && $showCustomMarkerOnPrint )
				{
					$here = request_protocol() . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
					$pos = strrpos($here, '/');
					$here = substr($here, 0, $pos)."/images/menuicons/".$icon;

					$markerLocation = "icon:".$here."|".$location;
				}

				return 'https://maps.googleapis.com/maps/api/staticmap?center='.$location.
					'&zoom='.$zoom.'&size='.$width.'x'.$height.'&maptype=mobile&markers='.$markerLocation.'&key='.$apiKey;

			case OPEN_STREET_MAPS:
				return 'https://staticmap.openstreetmap.de/staticmap.php?center='.$location.
					'&zoom='.$zoom.'&size='.$width.'x'.$height.'&maptype=mobile&markers='.$markerLocation. ',ol-marker';

			case BING_MAPS:
				return 'https://dev.virtualearth.net/REST/v1/Imagery/Map/Road/'.$location.'/'
					.$zoom.'?mapSize='.$width.','.$height.'&pp='.$markerLocation.';63;&key='.$apiKey;

			default:
				return '';
		}
	}

	public function getPdfValue( &$data, $keylink = "" )
	{
		$mapData = $this->pageObject->addGoogleMapData( $this->field, $data );

		$location = $this->getLocation( $mapData['markers'][0] );
		$staticUrl = $this->getStaticMapURL( $location, $mapData['zoom'], $mapData['markers'][0]['mapIcon'] );

		$content = myurl_get_contents_binary( $staticUrl );

		$imageType = SupposeImageType( $content );
		if( $imageType == "image/jpeg" || $imageType == "image/png" )
		{
			return '{
				image: "' . jsreplace( 'data:'. $imageType . ';base64,' . base64_bin2str( $content ) ) . '",
			}';
		}

		return '""';
	}
}
?>