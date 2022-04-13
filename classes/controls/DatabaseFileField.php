<?php
class DatabaseFileField extends EditControl
{
	function __construct($field, $pageObject, $id, $connection)
	{
		parent::__construct($field, $pageObject, $id, $connection);
		$this->format = $pageObject->pSetEdit->getEditFormat($field);
	}

	/**
	 * addJSFiles
	 * Add control JS files to page object
	 */
	function addJSFiles()
	{
	}

	/**
	 * addCSSFiles
	 * Add control CSS files to page object
	 */
	function addCSSFiles()
	{
	}

	function buildControl($value, $mode, $fieldNum, $validate, $additionalCtrlParams, $data)
	{
		parent::buildControl($value, $mode, $fieldNum, $validate, $additionalCtrlParams, $data);

		$disp = "";
		$strfilename = "";

		if($mode == MODE_EDIT || $mode == MODE_INLINE_EDIT)
		{
			$value = $this->connection->stripSlashesBinary( $value );
			$itype = SupposeImageType($value);

			if($itype)
			{
				if( $this->format == EDIT_FORMAT_DATABASE_IMAGE && !$this->pageObject->pSetEdit->showThumbnail( $this->field ) )
				{
					// show real db image instead of icon
					$src = GetTableLink( "mfhandler", "", "filename=file.jpg&table=".rawurlencode( $this->container->tName )
						."&field=".rawurlencode( $this->field )
						."&nodisp=1"
						."&pageType=".$this->container->pageType
						."&page=".$this->pageObject->pageName
						.$this->keylink."&rndVal=".rand(0,32768) );

					$imgWidth = $this->container->pSetEdit->getImageWidth( $this->field );
					$imgHeight = $this->container->pSetEdit->getImageHeight( $this->field );

					$style = '';
					if( $imgWidth )
						$style.= 'max-width:'.$imgWidth.'px;';
					if( $imgHeight )
						$style.= 'max-height:'.$imgHeight.'px;';

					$style = $style ? ' style="'.$style.'"' : '';

					$disp = '<img class="mupload-preview-img" '.$style.' id="image_'.GoodFieldName( $this->field ).'_'.$this->id.'" name="'.$this->cfield.'"';
					if( $this->is508 )
						$disp.= ' alt="Image from DB"';
					$disp.= ' border=0 src="'.$src.'">';
				}
				else if( $this->pageObject->pSetEdit->showThumbnail($this->field) )
				{
					$disp = "<a target=_blank";

					$disp.=" href=\"".
						GetTableLink("imager", "",
						"page=".$this->pageObject->pageName.
						"&table=".GetTableURL($this->pageObject->tName).
						"&".$this->iquery.
						"&rndVal=".rand(0,32768))."\" >";
					$disp.= "<img class=\"mupload-preview-img\" id=\"image_".GoodFieldName($this->field)."_".$this->id."\" name=\"".$this->cfield."\" border=0";
					if($this->is508)
						$disp .= " alt=\"Image from DB\"";
					
					//	show thumbnail or fullsize image
					$displayField = $this->pageObject->pSetEdit->getStrThumbnail($this->field);
					if( !strlen( $data[ $displayField ]) ) {
						$displayField = $this->field;
					}

					$disp .= " src=\"".
						GetTableLink("imager", "",
							"page=".$this->pageObject->pageName.
							"&table=".GetTableURL($this->pageObject->tName).
							"&field=".rawurlencode( $displayField ).
							$this->keylink.
							"&rndVal=".rand(0,32768))."\">";
					$disp.= "</a>";
				}
				else
				{
					$disp='<img class="mupload-preview-img" id="image_'.GoodFieldName($this->field).'_'.$this->id.'" name="'.$this->cfield.'"';
					if($this->is508)
						$disp.= ' alt="Image from DB"';
					$disp.=' border=0 src="'.
						GetTableLink("imager", "",
							'table='.GetTableURL($this->pageObject->tName).
							"&page=".$this->pageObject->pageName.
							'&'.$this->iquery."&src=1&rndVal=".rand(0,32768)).'">';
				}
			}
			else
			{
				if(strlen($value))
				{
					$disp = '<img class="mupload-preview-img" id="image_'.GoodFieldName($this->field).'_'.$this->id.'" name="'.$this->cfield.'" border=0 ';
					if($this->is508)
						$disp .= ' alt="file"';
					$disp .= ' src="'.GetRootPathForResources("images/file.gif").'">';
				}
			}
//	filename
			if($this->format == EDIT_FORMAT_DATABASE_FILE && !$itype && strlen($value))
			{
				if(!($filename = @$data[$this->pageObject->pSetEdit->getFilenameField($this->field)]))
					$filename = "file.bin";

				$disp = '<a href="'.GetTableLink("getfile", "", 'table='.GetTableURL( $this->pageObject->tName ).'&filename='.runner_htmlspecialchars( $filename )
					.'&pagename='.runner_htmlspecialchars( $this->pageObject->pSetEdit->pageName() )
					.'&'.$this->iquery).'".>'.$disp.'</a>';
			}
//	filename edit
			if($this->format == EDIT_FORMAT_DATABASE_FILE && $this->pageObject->pSetEdit->getFilenameField($this->field))
			{
				if(!($filename = @$data[$this->pageObject->pSetEdit->getFilenameField($this->field)]))
					$filename = "";
				if($mode == MODE_INLINE_EDIT)
				{
					$strfilename = '<br><label for="filename_'.$this->cfieldname.'">'."Nama file"
						.'</label>&nbsp;&nbsp;<input type="text" '.$this->inputStyle.' id="filename_'.$this->cfieldname
						.'" name="filename_'.$this->cfieldname.'" size="20" maxlength="50" value="'.runner_htmlspecialchars($filename).'">';
				}
				else
				{
					$strfilename = '<br><label for="filename_'.$this->cfieldname.'">'."Nama file"
						.'</label>&nbsp;&nbsp;<input type="text" '.$this->inputStyle.' id="filename_'.$this->cfieldname.'" name="filename_'
						.$this->cfieldname.'" size="20" maxlength="50" value="'.runner_htmlspecialchars($filename).'">';
				}
			}
			if(strlen($value)) {
				$strtype = '<br><input id="'.$this->ctype.'_keep" type="Radio" name="'.$this->ctype.'" value="file0" checked class="rnr-uploadtype">'."Simpan";

			if(strlen($value) && !$this->pageObject->pSetEdit->isRequired($this->field))
			{
					$strtype .= '<input id="'.$this->ctype.'_delete" type="Radio" name="'.$this->ctype.'" value="file1" class="rnr-uploadtype">'."Hapus";
			}
				$strtype .= '<input id="'.$this->ctype.'_update" type="Radio" name="'.$this->ctype.'" value="file2" class="rnr-uploadtype">'."Update";
			} else {
				$strtype = '<input id="'.$this->ctype.'_update" type="hidden" name="'.$this->ctype.'" value="file2" class="rnr-uploadtype">';
		}
		}
		else
		{
//	if Add mode
			$strtype = '<input id="'.$this->ctype.'" type="hidden" name="'.$this->ctype.'" value="file2">';
			if($this->format == EDIT_FORMAT_DATABASE_FILE && $this->pageObject->pSetEdit->getFilenameField($this->field))
			{
				$strfilename = '<br><label for="filename_'.$this->cfieldname.'">'."Nama file"
					.'</label>&nbsp;&nbsp;<input type="text" '.$this->inputStyle.' id="filename_'.$this->cfieldname.'" name="filename_'
					.$this->cfieldname.'" size="20" maxlength="50">';
			}
		}

		if($mode == MODE_INLINE_EDIT && $this->format == EDIT_FORMAT_DATABASE_FILE)
			$disp = "";
		echo $disp.$strtype;
		if (($mode == MODE_EDIT || $mode==MODE_INLINE_EDIT) && (strlen($value)))
		{
			echo '<br>';
		}
		echo '<input type="File" '.$this->inputStyle.' id="'.$this->cfield.'" '
			.'accept="'.$this->pageObject->pSetEdit->getAcceptFileTypesHtml($this->field).'" '
			.(($mode==MODE_INLINE_EDIT || $mode==MODE_INLINE_ADD) && $this->is508 ? 'alt="'.$this->strLabel.'" ' : '').' name="'
			.$this->cfield.'" >'.$strfilename;
		echo '<input type="Hidden" id="notempty_'.$this->cfieldname.'" value="'.(strlen($value) ? 1 : 0).'">';
		$this->buildControlEnd($validate, $mode);
	}

	/**
	* Create CSS code for specifying control's width
	*/
	function makeWidthStyle($widthPx)
	{
		if(0 == $widthPx)
			return "";
		return "min-width: ".$widthPx."px";
	}

	function readWebValue(&$avalues, &$blobfields, $legacy1, $legacy2, &$filename_values)
	{
		$filename = "";
		$this->getPostValueAndType();
		if (FieldSubmitted($this->goodFieldName."_".$this->id))
		{
			$fileNameForPrepareFunc = securityCheckFileName(postvalue("filename_".$this->goodFieldName."_".$this->id));
			if($this->pageObject->pageType != PAGE_EDIT)
			{
				$prepearedFile = prepare_file($this->webValue, $this->field, "file2", $fileNameForPrepareFunc, $this->id);
				if($prepearedFile !== false)
				{
					$this->webValue = $prepearedFile["value"];
					$filename = $prepearedFile["filename"];
				}
				else
					$this->webValue = false;
			}
			else
			{
				if(substr($this->webType, 0, 4) == "file")
				{
					$prepearedFile = prepare_file($this->webValue, $this->field, $this->webType, $fileNameForPrepareFunc, $this->id);
					if($prepearedFile !== false)
					{
						$this->webValue = $prepearedFile["value"];
						$filename = $prepearedFile["filename"];
					}
					else
						$this->webValue = false;
				}
				else if(substr($this->webType, 0, 6) == "upload")
				{
					if($this->webType == "upload1")
					{
						// file deletion, read filename from the database
						$oldValues = $this->pageObject->getOldRecordData();
						$fileNameForPrepareFunc = $oldValues[$this->field];
					}
					$this->webValue = prepare_upload($this->field, $this->webType, $fileNameForPrepareFunc, $this->webValue, "", $this->id, $this->pageObject);
				}
				else
					$this->webValue = false;
			}
		}
		else
			$this->webValue = false;

		if(!($this->webValue === false))
		{
			if($this->webValue)
			{
				if($this->pageObject->pSetEdit->getCreateThumbnail($this->field))
				{
					$ext = CheckImageExtension(GetUploadedFileName("value_".$this->goodFieldName."_".$this->id));
					$thumb = CreateThumbnail($this->webValue, $this->pageObject->pSetEdit->getThumbnailSize($this->field), $ext);
					$blobfields[] = $this->pageObject->pSetEdit->getStrThumbnail($this->field);
					$avalues[$blobfields[count($blobfields) - 1]] = $thumb;
				}
				if($this->pageObject->pSetEdit->getResizeOnUpload($this->field))
				{
					$ext = CheckImageExtension(GetUploadedFileName("value_".$this->goodFieldName."_".$this->id));
					$this->webValue = CreateThumbnail($this->webValue, $this->pageObject->pSetEdit->getNewImageSize($this->field), $ext);
				}
			}
			else if($this->pageObject->pageType == PAGE_EDIT && $this->pageObject->pSetEdit->getCreateThumbnail($this->field))
			{
				$blobfields[] = $this->pageObject->pSetEdit->getStrThumbnail($this->field);
				$avalues[$blobfields[count($blobfields) - 1]] = "";
			}
			$blobfields[] = $this->field;
			$avalues[$this->field] = $this->webValue;
		}
		if($filename && $this->pageObject->pSetEdit->getStrFilename($this->field))
			$filename_values[$this->pageObject->pSetEdit->getStrFilename($this->field)] = $filename;
	}
}
?>