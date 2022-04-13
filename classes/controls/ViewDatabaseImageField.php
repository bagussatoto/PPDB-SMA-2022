<?php
include_once getabspath("classes/controls/ViewImageDownloadField.php");
class ViewDatabaseImageField extends ViewImageDownloadField
{

	/**
	 * It returns makePdf image notation { image: ..., width: ..., height: ... }
	*/
	public function getPdfValue(&$data, $keylink = "")
	{
		if( !$data[ $this->field ] )
			return '""';

		$width = $this->container->pSet->getImageWidth( $this->field );

		if( !$width )
		{
			$width = 100;
			if ( $this->container->pageType === PAGE_VIEW )
				$width = 300;
		}

		$imageType = SupposeImageType( $data[ $this->field ] );
		if( $imageType == "image/jpeg" || $imageType == "image/png" )
		{
			return '{
				image: "' . jsreplace( 'data:'. $imageType . ';base64,' . base64_bin2str( $data[ $this->field ] ) ) . '",
				width: ' . $width . ',
				height: ' .$this->container->pSet->getImageHeight( $this->field ) . '
			}';
		}

		return '""';
	}

	public function getFileURLs(&$data, $keylink)
	{
		$fileURLs = array();

		$this->upload_handler->tkeys = $keylink;
		$fileName = 'file.jpg';
		$fileNameF = $this->container->pSet->getFilenameField($this->field);
		if( $fileNameF && $data[$fileNameF] )
			$fileName = $data[$fileNameF];

		$url = array(
			"image" => GetTableLink("mfhandler", "", "filename=".$fileName."&table=".rawurlencode($this->container->pSet->_table)
						."&field=".rawurlencode($this->field)
						."&nodisp=1"
						."&page=".$this->container->pSet->pageName()
						."&pageType=".$this->container->pageType.$keylink."&rndVal=".rand(0,32768)),
			"filename" => $fileNameF
		);
		if( $this->showThumbnails ) {
			$hrefBegin = GetTableLink("mfhandler", "", "filename=".$fileName."&table=".rawurlencode($this->container->pSet->_table));
			$thumbPref = $this->container->pSet->getStrThumbnail($this->field);
			$hasThumbnail = $thumbPref != "" && strlen($data[ $thumbPref ]);
			$hrefEnd = "&nodisp=1&page=".$this->container->pSet->pageName()."&pageType=".$this->container->pageType.$keylink."&rndVal=".rand(0,32768);

			$url["thumbnail"] = $hrefBegin."&field=".rawurlencode($this->field).( $hasThumbnail ? "&thumb=1" : ""  ).$hrefEnd;
		}
		$fileURLs[] = $url;

		return $fileURLs;
	}

	/**
	 * @param &Array data
	 * @return String
	 */
	public function getTextValue(&$data)
	{
		if( !strlen( $data[ $this->field ] ) )
			return "";

		$fileNameField = $this->container->pSet->getFilenameField( $this->field );
		if( $fileNameField && $data[ $fileNameField ] )
			return $data[ $fileNameField ];

		return "<<Image>>";
	}

	/**
	 * Get the field's content that will be exported
	 * @prarm &Array data
	 * @prarm String keylink
	 * @return String
	 */
	public function getExportValue(&$data, $keylink = "")
	{
		return "Data Biner Panjang - tidak dapat ditampilkan";
	}

	/**
	 * Get the width and height setting for small thumbnails
	 * wrapping in a style attribute
	 * @param String imageSrc
	 * @param Boolean hasThumbnail
	 * @return String
	 */
	protected function getSmallThumbnailStyle( $imageSrc, $hasThumbnail )
	{
		$styles = array();

		if( $imageSrc )
		{
			//	this is required to avoid the corrupting of the tag by the html2xhtml function in html2ps library
			$imageSrc = str_replace( "=", "&#61;", $imageSrc );
			$styles[] = ' background-image: url('.$imageSrc.');';
			if( !$hasThumbnail )
				$styles[] = ' background-size: '. $this->thumbWidth.'px '.$this->thumbHeight.'px ;';
		}

		if( $this->thumbWidth )
			$styles[] = ' width: '.$this->thumbWidth.'px;';

		if( $this->thumbHeight )
			$styles[] = ' height: '.$this->thumbHeight.'px';

		return ' style="'. implode( '' , $styles ) .'"';
	}
}
?>