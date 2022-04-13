<?php
include_once getabspath("classes/controls/ViewFileField.php");
class ViewImageDownloadField extends ViewFileField
{
	protected $isImageURL = false;
	protected $showThumbnails = false;

	protected $setOfThumbnails = false;

	protected $useAbsolutePath = false;

	protected $imageWidth;
	protected $imageHeight;

	protected $thumbWidth;
	protected $thumbHeight;


	function __construct($field, $container, $pageobject)
	{
		parent::__construct($field, $container, $pageobject);
		$this->isImageURL = $container->pSet->isImageURL( $this->field );

		$this->showThumbnails = $container->pSet->showThumbnail( $this->field ) && !$this->isImageURL;
		$this->setOfThumbnails = $container->pSet->showListOfThumbnails( $this->field );
		$this->useAbsolutePath = $container->pSet->isAbsolute( $this->field );

		$this->imageWidth = $container->pSet->getImageWidth( $this->field );
		$this->imageHeight = $container->pSet->getImageHeight( $this->field );

		if( $this->showThumbnails )
		{
			$this->thumbWidth = $container->pSet->getThumbnailWidth( $this->field );
			$this->thumbHeight = $container->pSet->getThumbnailHeight( $this->field );
		}
	}

	/**
	 * addJSFiles
	 * Add control JS files to page object
	 */
	public function addJSFiles()
	{
		$this->getJSControl();
		$pSet = $this->pSettings();
		if( $pSet->isGalleryEnabled( $this->field ) ) {
			$this->addJSFile('include/photoswipe/photoswipe.min.js');
			$this->addJSFile('include/photoswipe/photoswipe-ui-default.min.js');
		}
	}

	/**
	 * addCSSFiles
	 * Add control CSS files to page object
	 */
	function addCSSFiles()
	{
		$pSet = $this->pSettings();
		if( $pSet->isGalleryEnabled( $this->field ) ) {
			$this->AddCSSFile("include/photoswipe/photoswipe.css");
			$this->AddCSSFile("include/photoswipe/default-skin/default-skin.css");
		}
	}

	/**
	 * It returns makePdf image notation { image: ..., width: ..., height: ... }
	 */
	public function getPdfValue(&$data, $keylink = "")
	{
		if( !$data[ $this->field ] )
			return '""';

		$defWidth = 100;
		if ( $this->container->pageType === PAGE_VIEW )
			$defWidth = 300;

		$width = $this->imageWidth ? $this->imageWidth : $defWidth;
		$thumbWidth = $this->thumbWidth ? $this->thumbWidth : 72;

		if ( $this->isImageURL )
		{
			$content = myurl_get_contents_binary( $data[ $this->field ] );
			$imageType = SupposeImageType( $content );
			if( $imageType == "image/jpeg" || $imageType == "image/png" )
			{
				return '{
					image: "' . jsreplace( 'data:'. $imageType . ';base64,' . base64_bin2str( $content ) ) . '",
					width: ' . $width  . ',
					height: ' . $this->imageHeight . '
				}';
			}
			return '""';
		}

		$this->upload_handler->tkeys = $keylink;

		$resultValues = array();

		$filesArray = $this->getFilesArray( $data[ $this->field ] );

		$pSet = $this->pSettings();
		$maxImages = $pSet->getMaxImages( $this->field );
		$imgCount = 0;

		foreach( $filesArray as $imageFile )
		{
			if( $maxImages > 0 && $imgCount++ > $maxImages ) {
				break;
			}

			if( !CheckImageExtension( $imageFile["name"] ) )
			{
				$resultValues[] = '""';
				continue;
			}

			$imagePath = $this->getImagePath( $imageFile["name"] ) ;
			$hasBigImage = myfile_exists( $imagePath );

			if( !$hasBigImage )
				continue;

			if( !$this->showThumbnails )
			{
				$content = myfile_get_contents_binary( $imagePath );
				$imageType = SupposeImageType( $content );
				if( $imageType == "image/jpeg" || $imageType == "image/png" )
				{
					$resultValues[] = '{
						image: "' . jsreplace( 'data:'. $imageType. ';base64,' . base64_bin2str( $content ) ) . '",
						width: ' . $width  . ',
						height: ' . $this->imageHeight . '
					}';
				}

				continue;
			}

			$thumbPath = $this->getImagePath( $imageFile["thumbnail"] );
			$hasThumbnail = myfile_exists( $thumbPath );

			if( $hasThumbnail )
			{
				$content = myfile_get_contents_binary( $thumbPath );
				$imageType = SupposeImageType( $content );
				if( $imageType == "image/jpeg" || $imageType == "image/png" )
				{
					$resultValues[] = '{
						image: "' . jsreplace( 'data:'. $imageType. ';base64,' . base64_bin2str( $content ) ) . '",
						width: ' . $thumbWidth . ',
						height: ' . $this->thumbHeight . '
					}';
				}
			}
			else
			{
				$content = myfile_get_contents_binary( $imagePath );
				$imageType = SupposeImageType( $content );
				if( $imageType == "image/jpeg" || $imageType == "image/png" )
				{
					$resultValues[] = '{
						image: "' . jsreplace( 'data:'. $imageType . ';base64,' . base64_bin2str( $content ) ) . '",
						width: ' . $thumbWidth . ',
						height: ' . $this->thumbHeight . '
					}';
				}
			}
		}

		if( count( $resultValues ) > 0 )
			return '[' . implode( ',', $resultValues ) . ']';

		return '""';
	}

	public function getFileURLs(&$data, $keylink)
	{
		$pSet = $this->pSettings();
		$showThumbnails = $pSet->showThumbnail( $this->field );

		$fileURLs = array();

		if ( $this->isImageURL ) {
			$path = pathinfo( $data[$this->field] );
			$fileURLs = array( 0 => array(
				"image" => $data[$this->field],
				"filename" => $path['filename'],
			) );
		}
		else
		{
			$this->upload_handler->tkeys = $keylink;
			$filesArray = $this->getFilesArray( $data [$this->field ]);
			foreach( $filesArray as $f ) {
				if( !myfile_exists( $f['name'] ) )
					continue;
				$userFile = $this->upload_handler->buildUserFile( $f );
				$url = array(
					"image" => $userFile["url"] . "&nodisp=1",
					"filename" => $f["usrName"]
				);
				if( $showThumbnails && $userFile["thumbnail_url"]) {
					if( myfile_exists( $f['thumbnail'] ) )
						$url["thumbnail"] = $userFile["thumbnail_url"] . "&nodisp=1";
				}
				$fileURLs[] = $url;
			}
		}

		return $fileURLs;
	}

	public function showDBValue( &$data, $keylink, $html = true )
	{
		if($data[$this->field] == '')
			return '';

		$pSet = $this->pSettings();
		$fileURLs = $this->getFileURLs($data, $keylink);

		$attrs = array();
		$attrs["images"] = my_json_encode( $fileURLs );

		$attrs["thumbnails"] = $pSet->showThumbnail( $this->field );
		$attrs["images"] = my_json_encode( $fileURLs );
		$attrs["multiple"] = $pSet->getMultipleImgMode( $this->field );
		$attrs["max-images"] = $pSet->getMaxImages( $this->field );
		$attrs["gallery"] = $pSet->isGalleryEnabled( $this->field );
		$attrs["gallery-mode"] = $pSet->getGalleryMode( $this->field );
		$attrs["caption-mode"] = $pSet->getCaptionMode( $this->field );
		if( $attrs["caption-mode"] == 3 )
			$attrs["caption"] = $data[ ''.$pSet->getCaptionField( $this->field ) ];
		$attrs['width'] = $pSet->getImageWidth( $this->field );
		$attrs['height'] = $pSet->getImageHeight( $this->field );
		if( $attrs["thumbnails"] ) {
			$attrs['th-width'] = $pSet->getThumbnailWidth( $this->field );
			$attrs['th-height'] = $pSet->getThumbnailHeight( $this->field );
		}
		if( $pSet->getImageBorder( $this->field ) )
			$attrs["border"] = "true";
		if( $pSet->getImageFullWidth( $this->field ) ) 
			$attrs["fullwidth"] = "true";

		$htmlAttrs = array();
		foreach( $attrs as $name => $value ) {
			$htmlAttrs[] = 'data-' . $name . '="' . runner_htmlspecialchars($value) . '"';
		}

		return '<div class="r-images" '. join( ' ', $htmlAttrs ) . '></div>';

	}

	/**
	 * @param &Array data
	 * @return String
	 */
	public function getTextValue(&$data)
	{
		if( !strlen( $data[ $this->field ] ) )
			return "";

		if ( !$this->isImageURL )
		{
			$fileNames = array();

			$filesData = $this->getFilesArray( $data[ $this->field ] );
			foreach($filesData as $imageFile)
			{
				$userFile = $this->upload_handler->buildUserFile($imageFile);
				$fileNames[] = $userFile["name"] ;
			}

			return implode(", ", $fileNames);
		}
		else
		{
			return $data[ $this->field ];
		}
	}

	/**
	 * Get the path to the image file
	 * @param String imageFile
	 * @return String
	 */
	protected function getImagePath( $imageFile )
	{
		if( $this->useAbsolutePath || isAbsolutePath($imageFile) )
			return $imageFile;

		return getabspath($imageFile);
	}

	/**
	 * Get the width and height setting for small thumbnails
	 * wrapping in a style attribute
	 * @param String imageSrc (optional)
	 * @param Boolean hasThumbnail (optional)
	 * @return String
	 */
	protected function getSmallThumbnailStyle( $imageSrc = false, $hasThumbnail = true )
	{
		$styles = array();

		if( $imageSrc )
		{
			//	this is required to avoid the corrupting of the tag by the html2xhtml function in html2ps library
			$imageSrc = str_replace( "=", "&#61;", $imageSrc );

			$styles[] = ' background-image: url('.$imageSrc.');';
		}

		if( $this->thumbWidth )
			$styles[] = ' width: '.$this->thumbWidth.'px;';

		if( $this->thumbHeight )
			$styles[] = ' height: '.$this->thumbHeight.'px';

		return ' style="'. implode( '' , $styles ) .'"';
	}

	/**
	 * Get the width and height styles set for big thumbnails
	 * (the 'Sets of thumbnails with preview' option)
	 * @param Boolean widthAutoSet	(optional)
	 * @return String
	 */
	protected function getBigThumbnailSizeStyles( $widthAutoSet = false )
	{
		$bigThumbnailSizeStyle = "";
		$bigThumbnailHeight = $this->imageHeight;
		$bigThumbnailWidth = $this->imageWidth;

		if( $bigThumbnailWidth )
			$bigThumbnailSizeStyle.= ' width: '.$bigThumbnailWidth.'px;';

		if($bigThumbnailHeight)
			$bigThumbnailSizeStyle.= ' height: '.$bigThumbnailHeight.'px;';

		if( !$bigThumbnailWidth && $bigThumbnailHeight && $widthAutoSet )
			$bigThumbnailSizeStyle.= ' width: '. floor( 4 * $bigThumbnailHeight / 3 ).'px;';

		return $bigThumbnailSizeStyle;
	}

	/**
	 * Check for need to load the javascript files
	 * @return boolean
	 */
	public function neededLoadJSFiles()
	{
		return true;
	}
}
?>