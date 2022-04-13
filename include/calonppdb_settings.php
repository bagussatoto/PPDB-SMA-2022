<?php



$tdatacalonppdb = array();
$tdatacalonppdb[".searchableFields"] = array();
$tdatacalonppdb[".ShortName"] = "calonppdb";
$tdatacalonppdb[".OwnerID"] = "";
$tdatacalonppdb[".OriginalTable"] = "calonppdb";


$defaultPages = my_json_decode( "{\"add\":\"add\",\"edit\":\"edit\",\"export\":\"export\",\"import\":\"import\",\"list\":\"list\",\"print\":\"print\",\"search\":\"search\",\"view\":\"view\"}" );

$tdatacalonppdb[".pagesByType"] = my_json_decode( "{\"add\":[\"add\"],\"edit\":[\"edit\"],\"export\":[\"export\"],\"import\":[\"import\"],\"list\":[\"list\"],\"print\":[\"print\"],\"search\":[\"search\"],\"view\":[\"view\"]}" );
$tdatacalonppdb[".pages"] = types2pages( my_json_decode( "{\"add\":[\"add\"],\"edit\":[\"edit\"],\"export\":[\"export\"],\"import\":[\"import\"],\"list\":[\"list\"],\"print\":[\"print\"],\"search\":[\"search\"],\"view\":[\"view\"]}" ) );
$tdatacalonppdb[".defaultPages"] = $defaultPages;

//	field labels
$fieldLabelscalonppdb = array();
$fieldToolTipscalonppdb = array();
$pageTitlescalonppdb = array();
$placeHolderscalonppdb = array();

if(mlang_getcurrentlang()=="Indonesian")
{
	$fieldLabelscalonppdb["Indonesian"] = array();
	$fieldToolTipscalonppdb["Indonesian"] = array();
	$placeHolderscalonppdb["Indonesian"] = array();
	$pageTitlescalonppdb["Indonesian"] = array();
	$fieldLabelscalonppdb["Indonesian"]["ID"] = "No";
	$fieldToolTipscalonppdb["Indonesian"]["ID"] = "";
	$placeHolderscalonppdb["Indonesian"]["ID"] = "";
	$fieldLabelscalonppdb["Indonesian"]["tanggaldaftar"] = "Tanggal Daftar";
	$fieldToolTipscalonppdb["Indonesian"]["tanggaldaftar"] = "";
	$placeHolderscalonppdb["Indonesian"]["tanggaldaftar"] = "";
	$fieldLabelscalonppdb["Indonesian"]["nomap"] = "No. MAP/Urut PPDB";
	$fieldToolTipscalonppdb["Indonesian"]["nomap"] = "";
	$placeHolderscalonppdb["Indonesian"]["nomap"] = "";
	$fieldLabelscalonppdb["Indonesian"]["namalengkap"] = "Nama Lengkap Siswa";
	$fieldToolTipscalonppdb["Indonesian"]["namalengkap"] = "";
	$placeHolderscalonppdb["Indonesian"]["namalengkap"] = "";
	$fieldLabelscalonppdb["Indonesian"]["jeniskelamin"] = "Jenis Kelamin";
	$fieldToolTipscalonppdb["Indonesian"]["jeniskelamin"] = "";
	$placeHolderscalonppdb["Indonesian"]["jeniskelamin"] = "";
	$fieldLabelscalonppdb["Indonesian"]["nisn"] = "NISN";
	$fieldToolTipscalonppdb["Indonesian"]["nisn"] = "";
	$placeHolderscalonppdb["Indonesian"]["nisn"] = "";
	$fieldLabelscalonppdb["Indonesian"]["nik"] = "NIK";
	$fieldToolTipscalonppdb["Indonesian"]["nik"] = "";
	$placeHolderscalonppdb["Indonesian"]["nik"] = "";
	$fieldLabelscalonppdb["Indonesian"]["tempatlahir"] = "Tempat Lahir";
	$fieldToolTipscalonppdb["Indonesian"]["tempatlahir"] = "";
	$placeHolderscalonppdb["Indonesian"]["tempatlahir"] = "";
	$fieldLabelscalonppdb["Indonesian"]["tanggallahir1"] = "Tanggal Lahir";
	$fieldToolTipscalonppdb["Indonesian"]["tanggallahir1"] = "";
	$placeHolderscalonppdb["Indonesian"]["tanggallahir1"] = "";
	$fieldLabelscalonppdb["Indonesian"]["noaktalahir"] = "No. Akta Lahir / Surat Lahir";
	$fieldToolTipscalonppdb["Indonesian"]["noaktalahir"] = "";
	$placeHolderscalonppdb["Indonesian"]["noaktalahir"] = "";
	$fieldLabelscalonppdb["Indonesian"]["agama"] = "Agama";
	$fieldToolTipscalonppdb["Indonesian"]["agama"] = "";
	$placeHolderscalonppdb["Indonesian"]["agama"] = "";
	$fieldLabelscalonppdb["Indonesian"]["alamatjalan"] = "Nama Jalan";
	$fieldToolTipscalonppdb["Indonesian"]["alamatjalan"] = "";
	$placeHolderscalonppdb["Indonesian"]["alamatjalan"] = "";
	$fieldLabelscalonppdb["Indonesian"]["rt"] = "RT";
	$fieldToolTipscalonppdb["Indonesian"]["rt"] = "";
	$placeHolderscalonppdb["Indonesian"]["rt"] = "";
	$fieldLabelscalonppdb["Indonesian"]["rw"] = "RW";
	$fieldToolTipscalonppdb["Indonesian"]["rw"] = "";
	$placeHolderscalonppdb["Indonesian"]["rw"] = "";
	$fieldLabelscalonppdb["Indonesian"]["dusun"] = "Dusun";
	$fieldToolTipscalonppdb["Indonesian"]["dusun"] = "";
	$placeHolderscalonppdb["Indonesian"]["dusun"] = "";
	$fieldLabelscalonppdb["Indonesian"]["deskel"] = "Desa / Kelurahan";
	$fieldToolTipscalonppdb["Indonesian"]["deskel"] = "";
	$placeHolderscalonppdb["Indonesian"]["deskel"] = "";
	$fieldLabelscalonppdb["Indonesian"]["kecamatan"] = "Kecamatan";
	$fieldToolTipscalonppdb["Indonesian"]["kecamatan"] = "";
	$placeHolderscalonppdb["Indonesian"]["kecamatan"] = "";
	$fieldLabelscalonppdb["Indonesian"]["kodepos"] = "Kode Pos";
	$fieldToolTipscalonppdb["Indonesian"]["kodepos"] = "";
	$placeHolderscalonppdb["Indonesian"]["kodepos"] = "";
	$fieldLabelscalonppdb["Indonesian"]["tempattinggal"] = "Tempat Tinggal Saat Ini";
	$fieldToolTipscalonppdb["Indonesian"]["tempattinggal"] = "";
	$placeHolderscalonppdb["Indonesian"]["tempattinggal"] = "";
	$fieldLabelscalonppdb["Indonesian"]["modatransportasi"] = "Moda Transportasi";
	$fieldToolTipscalonppdb["Indonesian"]["modatransportasi"] = "";
	$placeHolderscalonppdb["Indonesian"]["modatransportasi"] = "";
	$fieldLabelscalonppdb["Indonesian"]["anakke"] = "Anak ke-";
	$fieldToolTipscalonppdb["Indonesian"]["anakke"] = "";
	$placeHolderscalonppdb["Indonesian"]["anakke"] = "";
	$fieldLabelscalonppdb["Indonesian"]["namaayah"] = "Nama Ayah Kandung";
	$fieldToolTipscalonppdb["Indonesian"]["namaayah"] = "";
	$placeHolderscalonppdb["Indonesian"]["namaayah"] = "";
	$fieldLabelscalonppdb["Indonesian"]["nikayah"] = "No. NIK Ayah";
	$fieldToolTipscalonppdb["Indonesian"]["nikayah"] = "";
	$placeHolderscalonppdb["Indonesian"]["nikayah"] = "";
	$fieldLabelscalonppdb["Indonesian"]["tanggallahir2"] = "Tanggal Lahir";
	$fieldToolTipscalonppdb["Indonesian"]["tanggallahir2"] = "";
	$placeHolderscalonppdb["Indonesian"]["tanggallahir2"] = "";
	$fieldLabelscalonppdb["Indonesian"]["pendidikan1"] = "Pendidikan";
	$fieldToolTipscalonppdb["Indonesian"]["pendidikan1"] = "";
	$placeHolderscalonppdb["Indonesian"]["pendidikan1"] = "";
	$fieldLabelscalonppdb["Indonesian"]["pekerjaan1"] = "Pekerjaan";
	$fieldToolTipscalonppdb["Indonesian"]["pekerjaan1"] = "";
	$placeHolderscalonppdb["Indonesian"]["pekerjaan1"] = "";
	$fieldLabelscalonppdb["Indonesian"]["penghasilan1"] = "Penghasilan perbulan";
	$fieldToolTipscalonppdb["Indonesian"]["penghasilan1"] = "";
	$placeHolderscalonppdb["Indonesian"]["penghasilan1"] = "";
	$fieldLabelscalonppdb["Indonesian"]["statusayah"] = "Status Ayah";
	$fieldToolTipscalonppdb["Indonesian"]["statusayah"] = "";
	$placeHolderscalonppdb["Indonesian"]["statusayah"] = "";
	$fieldLabelscalonppdb["Indonesian"]["statusibu"] = "Status Ibu";
	$fieldToolTipscalonppdb["Indonesian"]["statusibu"] = "";
	$placeHolderscalonppdb["Indonesian"]["statusibu"] = "";
	$fieldLabelscalonppdb["Indonesian"]["nikibu"] = "NIK Ibu";
	$fieldToolTipscalonppdb["Indonesian"]["nikibu"] = "";
	$placeHolderscalonppdb["Indonesian"]["nikibu"] = "";
	$fieldLabelscalonppdb["Indonesian"]["tanggallahir3"] = "Tanggal Lahir";
	$fieldToolTipscalonppdb["Indonesian"]["tanggallahir3"] = "";
	$placeHolderscalonppdb["Indonesian"]["tanggallahir3"] = "";
	$fieldLabelscalonppdb["Indonesian"]["pendidikan2"] = "Pendidikan";
	$fieldToolTipscalonppdb["Indonesian"]["pendidikan2"] = "";
	$placeHolderscalonppdb["Indonesian"]["pendidikan2"] = "";
	$fieldLabelscalonppdb["Indonesian"]["pekerjaan2"] = "Pekerjaan";
	$fieldToolTipscalonppdb["Indonesian"]["pekerjaan2"] = "";
	$placeHolderscalonppdb["Indonesian"]["pekerjaan2"] = "";
	$fieldLabelscalonppdb["Indonesian"]["penghasilan2"] = "Penghasilan perbulan";
	$fieldToolTipscalonppdb["Indonesian"]["penghasilan2"] = "";
	$placeHolderscalonppdb["Indonesian"]["penghasilan2"] = "";
	$fieldLabelscalonppdb["Indonesian"]["namawali"] = "Nama Wali";
	$fieldToolTipscalonppdb["Indonesian"]["namawali"] = "";
	$placeHolderscalonppdb["Indonesian"]["namawali"] = "";
	$fieldLabelscalonppdb["Indonesian"]["nikwali"] = "NIK Wali";
	$fieldToolTipscalonppdb["Indonesian"]["nikwali"] = "";
	$placeHolderscalonppdb["Indonesian"]["nikwali"] = "";
	$fieldLabelscalonppdb["Indonesian"]["tanggallahir4"] = "Tanggal Lahir";
	$fieldToolTipscalonppdb["Indonesian"]["tanggallahir4"] = "";
	$placeHolderscalonppdb["Indonesian"]["tanggallahir4"] = "";
	$fieldLabelscalonppdb["Indonesian"]["pendidikan3"] = "Pendidikan";
	$fieldToolTipscalonppdb["Indonesian"]["pendidikan3"] = "";
	$placeHolderscalonppdb["Indonesian"]["pendidikan3"] = "";
	$fieldLabelscalonppdb["Indonesian"]["pekerjaan3"] = "Pekerjaan";
	$fieldToolTipscalonppdb["Indonesian"]["pekerjaan3"] = "";
	$placeHolderscalonppdb["Indonesian"]["pekerjaan3"] = "";
	$fieldLabelscalonppdb["Indonesian"]["penghasilan3"] = "Penghasilan";
	$fieldToolTipscalonppdb["Indonesian"]["penghasilan3"] = "";
	$placeHolderscalonppdb["Indonesian"]["penghasilan3"] = "";
	$fieldLabelscalonppdb["Indonesian"]["kontak"] = "No. HP / Whatsapp Ayah";
	$fieldToolTipscalonppdb["Indonesian"]["kontak"] = "";
	$placeHolderscalonppdb["Indonesian"]["kontak"] = "";
	$fieldLabelscalonppdb["Indonesian"]["nomorwa"] = "No. HP / Whatsapp Ibu";
	$fieldToolTipscalonppdb["Indonesian"]["nomorwa"] = "";
	$placeHolderscalonppdb["Indonesian"]["nomorwa"] = "";
	$fieldLabelscalonppdb["Indonesian"]["email"] = "Email";
	$fieldToolTipscalonppdb["Indonesian"]["email"] = "";
	$placeHolderscalonppdb["Indonesian"]["email"] = "";
	$fieldLabelscalonppdb["Indonesian"]["tinggibadan"] = "Tinggi Badan";
	$fieldToolTipscalonppdb["Indonesian"]["tinggibadan"] = "";
	$placeHolderscalonppdb["Indonesian"]["tinggibadan"] = "";
	$fieldLabelscalonppdb["Indonesian"]["beratbadan"] = "Berat Badan";
	$fieldToolTipscalonppdb["Indonesian"]["beratbadan"] = "";
	$placeHolderscalonppdb["Indonesian"]["beratbadan"] = "";
	$fieldLabelscalonppdb["Indonesian"]["jaraktempatkesekolah"] = "Jarak rumah ke sekolah";
	$fieldToolTipscalonppdb["Indonesian"]["jaraktempatkesekolah"] = "";
	$placeHolderscalonppdb["Indonesian"]["jaraktempatkesekolah"] = "";
	$fieldLabelscalonppdb["Indonesian"]["jumlahsaudarakandung"] = "Jumlah saudara kandung";
	$fieldToolTipscalonppdb["Indonesian"]["jumlahsaudarakandung"] = "";
	$placeHolderscalonppdb["Indonesian"]["jumlahsaudarakandung"] = "";
	$fieldLabelscalonppdb["Indonesian"]["asaltk"] = "Asal SMP / MTS";
	$fieldToolTipscalonppdb["Indonesian"]["asaltk"] = "";
	$placeHolderscalonppdb["Indonesian"]["asaltk"] = "";
	$fieldLabelscalonppdb["Indonesian"]["nomorsurattk"] = "Nomor Surat Keterangan Lulus";
	$fieldToolTipscalonppdb["Indonesian"]["nomorsurattk"] = "";
	$placeHolderscalonppdb["Indonesian"]["nomorsurattk"] = "";
	$fieldLabelscalonppdb["Indonesian"]["paktaintegritas"] = "Pakta Integritas";
	$fieldToolTipscalonppdb["Indonesian"]["paktaintegritas"] = "";
	$placeHolderscalonppdb["Indonesian"]["paktaintegritas"] = "";
	$fieldLabelscalonppdb["Indonesian"]["namaibu"] = "Nama Ibu Kandung";
	$fieldToolTipscalonppdb["Indonesian"]["namaibu"] = "";
	$placeHolderscalonppdb["Indonesian"]["namaibu"] = "";
	$fieldLabelscalonppdb["Indonesian"]["status"] = "Status";
	$fieldToolTipscalonppdb["Indonesian"]["status"] = "";
	$placeHolderscalonppdb["Indonesian"]["status"] = "";
	$pageTitlescalonppdb["Indonesian"]["add"] = "Formulir Pendaftaran Online PPDB 2022";
	if (count($fieldToolTipscalonppdb["Indonesian"]))
		$tdatacalonppdb[".isUseToolTips"] = true;
}


	$tdatacalonppdb[".NCSearch"] = true;



$tdatacalonppdb[".shortTableName"] = "calonppdb";
$tdatacalonppdb[".nSecOptions"] = 0;

$tdatacalonppdb[".mainTableOwnerID"] = "";
$tdatacalonppdb[".entityType"] = 0;

$tdatacalonppdb[".strOriginalTableName"] = "calonppdb";

	



$tdatacalonppdb[".showAddInPopup"] = false;

$tdatacalonppdb[".showEditInPopup"] = false;

$tdatacalonppdb[".showViewInPopup"] = false;

//page's base css files names
$popupPagesLayoutNames = array();
$tdatacalonppdb[".popupPagesLayoutNames"] = $popupPagesLayoutNames;


$tdatacalonppdb[".listAjax"] = false;
//	temporary
$tdatacalonppdb[".listAjax"] = false;

	$tdatacalonppdb[".audit"] = false;

	$tdatacalonppdb[".locking"] = false;


$pages = $tdatacalonppdb[".defaultPages"];

if( $pages[PAGE_EDIT] ) {
	$tdatacalonppdb[".edit"] = true;
	$tdatacalonppdb[".afterEditAction"] = 1;
	$tdatacalonppdb[".closePopupAfterEdit"] = 1;
	$tdatacalonppdb[".afterEditActionDetTable"] = "";
}

if( $pages[PAGE_ADD] ) {
$tdatacalonppdb[".add"] = true;
$tdatacalonppdb[".afterAddAction"] = 1;
$tdatacalonppdb[".closePopupAfterAdd"] = 1;
$tdatacalonppdb[".afterAddActionDetTable"] = "";
}

if( $pages[PAGE_LIST] ) {
	$tdatacalonppdb[".list"] = true;
}



$tdatacalonppdb[".strSortControlSettingsJSON"] = "";




if( $pages[PAGE_VIEW] ) {
$tdatacalonppdb[".view"] = true;
}

if( $pages[PAGE_IMPORT] ) {
$tdatacalonppdb[".import"] = true;
}

if( $pages[PAGE_EXPORT] ) {
$tdatacalonppdb[".exportTo"] = true;
}

if( $pages[PAGE_PRINT] ) {
$tdatacalonppdb[".printFriendly"] = true;
}



$tdatacalonppdb[".showSimpleSearchOptions"] = true; // temp fix #13449

// Allow Show/Hide Fields in GRID
$tdatacalonppdb[".allowShowHideFields"] = true; // temp fix #13449
//

// Allow Fields Reordering in GRID
$tdatacalonppdb[".allowFieldsReordering"] = true; // temp fix #13449
//

$tdatacalonppdb[".isUseAjaxSuggest"] = true;

$tdatacalonppdb[".rowHighlite"] = true;



			

$tdatacalonppdb[".ajaxCodeSnippetAdded"] = false;

$tdatacalonppdb[".buttonsAdded"] = false;

$tdatacalonppdb[".addPageEvents"] = false;

// use timepicker for search panel
$tdatacalonppdb[".isUseTimeForSearch"] = false;


$tdatacalonppdb[".badgeColor"] = "B22222";


$tdatacalonppdb[".allSearchFields"] = array();
$tdatacalonppdb[".filterFields"] = array();
$tdatacalonppdb[".requiredSearchFields"] = array();

$tdatacalonppdb[".googleLikeFields"] = array();
$tdatacalonppdb[".googleLikeFields"][] = "ID";
$tdatacalonppdb[".googleLikeFields"][] = "tanggaldaftar";
$tdatacalonppdb[".googleLikeFields"][] = "nomap";
$tdatacalonppdb[".googleLikeFields"][] = "namalengkap";
$tdatacalonppdb[".googleLikeFields"][] = "jeniskelamin";
$tdatacalonppdb[".googleLikeFields"][] = "nisn";
$tdatacalonppdb[".googleLikeFields"][] = "nik";
$tdatacalonppdb[".googleLikeFields"][] = "tempatlahir";
$tdatacalonppdb[".googleLikeFields"][] = "tanggallahir1";
$tdatacalonppdb[".googleLikeFields"][] = "noaktalahir";
$tdatacalonppdb[".googleLikeFields"][] = "agama";
$tdatacalonppdb[".googleLikeFields"][] = "alamatjalan";
$tdatacalonppdb[".googleLikeFields"][] = "rt";
$tdatacalonppdb[".googleLikeFields"][] = "rw";
$tdatacalonppdb[".googleLikeFields"][] = "dusun";
$tdatacalonppdb[".googleLikeFields"][] = "deskel";
$tdatacalonppdb[".googleLikeFields"][] = "kecamatan";
$tdatacalonppdb[".googleLikeFields"][] = "kodepos";
$tdatacalonppdb[".googleLikeFields"][] = "tempattinggal";
$tdatacalonppdb[".googleLikeFields"][] = "modatransportasi";
$tdatacalonppdb[".googleLikeFields"][] = "anakke";
$tdatacalonppdb[".googleLikeFields"][] = "namaayah";
$tdatacalonppdb[".googleLikeFields"][] = "nikayah";
$tdatacalonppdb[".googleLikeFields"][] = "tanggallahir2";
$tdatacalonppdb[".googleLikeFields"][] = "pendidikan1";
$tdatacalonppdb[".googleLikeFields"][] = "pekerjaan1";
$tdatacalonppdb[".googleLikeFields"][] = "penghasilan1";
$tdatacalonppdb[".googleLikeFields"][] = "statusayah";
$tdatacalonppdb[".googleLikeFields"][] = "statusibu";
$tdatacalonppdb[".googleLikeFields"][] = "nikibu";
$tdatacalonppdb[".googleLikeFields"][] = "tanggallahir3";
$tdatacalonppdb[".googleLikeFields"][] = "pendidikan2";
$tdatacalonppdb[".googleLikeFields"][] = "pekerjaan2";
$tdatacalonppdb[".googleLikeFields"][] = "penghasilan2";
$tdatacalonppdb[".googleLikeFields"][] = "namawali";
$tdatacalonppdb[".googleLikeFields"][] = "nikwali";
$tdatacalonppdb[".googleLikeFields"][] = "tanggallahir4";
$tdatacalonppdb[".googleLikeFields"][] = "pendidikan3";
$tdatacalonppdb[".googleLikeFields"][] = "pekerjaan3";
$tdatacalonppdb[".googleLikeFields"][] = "penghasilan3";
$tdatacalonppdb[".googleLikeFields"][] = "kontak";
$tdatacalonppdb[".googleLikeFields"][] = "nomorwa";
$tdatacalonppdb[".googleLikeFields"][] = "email";
$tdatacalonppdb[".googleLikeFields"][] = "tinggibadan";
$tdatacalonppdb[".googleLikeFields"][] = "beratbadan";
$tdatacalonppdb[".googleLikeFields"][] = "jaraktempatkesekolah";
$tdatacalonppdb[".googleLikeFields"][] = "jumlahsaudarakandung";
$tdatacalonppdb[".googleLikeFields"][] = "asaltk";
$tdatacalonppdb[".googleLikeFields"][] = "nomorsurattk";
$tdatacalonppdb[".googleLikeFields"][] = "paktaintegritas";
$tdatacalonppdb[".googleLikeFields"][] = "namaibu";
$tdatacalonppdb[".googleLikeFields"][] = "status";



$tdatacalonppdb[".tableType"] = "list";

$tdatacalonppdb[".printerPageOrientation"] = 0;
$tdatacalonppdb[".nPrinterPageScale"] = 100;

$tdatacalonppdb[".nPrinterSplitRecords"] = 40;

$tdatacalonppdb[".geocodingEnabled"] = false;










$tdatacalonppdb[".pageSize"] = 20;

$tdatacalonppdb[".warnLeavingPages"] = true;



$tstrOrderBy = "ORDER BY tanggaldaftar DESC";
if(strlen($tstrOrderBy) && strtolower(substr($tstrOrderBy,0,8))!="order by")
	$tstrOrderBy = "order by ".$tstrOrderBy;
$tdatacalonppdb[".strOrderBy"] = $tstrOrderBy;

$tdatacalonppdb[".orderindexes"] = array();
	$tdatacalonppdb[".orderindexes"][] = array(2, (0 ? "ASC" : "DESC"), "tanggaldaftar");


$tdatacalonppdb[".sqlHead"] = "SELECT ID,  tanggaldaftar,  nomap,  namalengkap,  jeniskelamin,  nisn,  nik,  tempatlahir,  tanggallahir1,  noaktalahir,  agama,  alamatjalan,  rt,  rw,  dusun,  deskel,  kecamatan,  kodepos,  tempattinggal,  modatransportasi,  anakke,  namaayah,  nikayah,  tanggallahir2,  pendidikan1,  pekerjaan1,  penghasilan1,  statusayah,  statusibu,  nikibu,  tanggallahir3,  pendidikan2,  pekerjaan2,  penghasilan2,  namawali,  nikwali,  tanggallahir4,  pendidikan3,  pekerjaan3,  penghasilan3,  kontak,  nomorwa,  email,  tinggibadan,  beratbadan,  jaraktempatkesekolah,  jumlahsaudarakandung,  asaltk,  nomorsurattk,  paktaintegritas,  namaibu,  status";
$tdatacalonppdb[".sqlFrom"] = "FROM calonppdb";
$tdatacalonppdb[".sqlWhereExpr"] = "";
$tdatacalonppdb[".sqlTail"] = "";










//fill array of records per page for list and report without group fields
$arrRPP = array();
$arrRPP[] = 10;
$arrRPP[] = 20;
$arrRPP[] = 30;
$arrRPP[] = 50;
$arrRPP[] = 100;
$arrRPP[] = 500;
$arrRPP[] = -1;
$tdatacalonppdb[".arrRecsPerPage"] = $arrRPP;

//fill array of groups per page for report with group fields
$arrGPP = array();
$arrGPP[] = 1;
$arrGPP[] = 3;
$arrGPP[] = 5;
$arrGPP[] = 10;
$arrGPP[] = 50;
$arrGPP[] = 100;
$arrGPP[] = -1;
$tdatacalonppdb[".arrGroupsPerPage"] = $arrGPP;

$tdatacalonppdb[".highlightSearchResults"] = true;

$tableKeyscalonppdb = array();
$tableKeyscalonppdb[] = "ID";
$tdatacalonppdb[".Keys"] = $tableKeyscalonppdb;


$tdatacalonppdb[".hideMobileList"] = array();




//	ID
//	Custom field settings
	$fdata = array();
	$fdata["Index"] = 1;
	$fdata["strName"] = "ID";
	$fdata["GoodName"] = "ID";
	$fdata["ownerTable"] = "calonppdb";
	$fdata["Label"] = GetFieldLabel("calonppdb","ID");
	$fdata["FieldType"] = 3;

	
		$fdata["AutoInc"] = true;

	
			

		$fdata["strField"] = "ID";

		$fdata["isSQLExpression"] = true;
	$fdata["FullName"] = "ID";

	
	
				$fdata["UploadFolder"] = "files";

//  Begin View Formats
	$fdata["ViewFormats"] = array();

	$vdata = array("ViewFormat" => "");

	
	
	
	
	
	
	
	
	
	
	
	
		$vdata["NeedEncode"] = true;

	
		$vdata["truncateText"] = true;
	$vdata["NumberOfChars"] = 80;

	$fdata["ViewFormats"]["view"] = $vdata;
//  End View Formats

//	Begin Edit Formats
	$fdata["EditFormats"] = array();

	$edata = array("EditFormat" => "Text field");

	
		$edata["weekdayMessage"] = array("message" => "", "messageType" => "Text");
	$edata["weekdays"] = "[]";


	
	



		$edata["IsRequired"] = true;

	
	
	
			$edata["acceptFileTypes"] = ".+$";
		$edata["acceptFileTypesHtml"] = "";

		$edata["maxNumberOfFiles"] = 1;

	
	
	
	
			$edata["HTML5InuptType"] = "text";

		$edata["EditParams"] = "";
		
		$edata["controlWidth"] = 200;

//	Begin validation
	$edata["validateAs"] = array();
	$edata["validateAs"]["basicValidate"] = array();
	$edata["validateAs"]["customMessages"] = array();
				$edata["validateAs"]["basicValidate"][] = getJsValidatorName("Number");
						$edata["validateAs"]["basicValidate"][] = "IsRequired";
		
	
	//	End validation

	
			
	
	
	
	$fdata["EditFormats"]["edit"] = $edata;
//	End Edit Formats


	$fdata["isSeparate"] = false;




// the field's search options settings
		$fdata["defaultSearchOption"] = "Contains";

			// the default search options list
				$fdata["searchOptionsList"] = array("Contains", "Equals", "Starts with", "More than", "Less than", "Between", "Empty", NOT_EMPTY);
// the end of search options settings


//Filters settings
	$fdata["filterTotals"] = 0;
		$fdata["filterMultiSelect"] = 0;
			$fdata["filterFormat"] = "Values list";
		$fdata["showCollapsed"] = false;

		$fdata["sortValueType"] = 0;
		$fdata["numberOfVisibleItems"] = 10;

		$fdata["filterBy"] = 0;

	

	
	
//end of Filters settings


	$tdatacalonppdb["ID"] = $fdata;
		$tdatacalonppdb[".searchableFields"][] = "ID";
//	tanggaldaftar
//	Custom field settings
	$fdata = array();
	$fdata["Index"] = 2;
	$fdata["strName"] = "tanggaldaftar";
	$fdata["GoodName"] = "tanggaldaftar";
	$fdata["ownerTable"] = "calonppdb";
	$fdata["Label"] = GetFieldLabel("calonppdb","tanggaldaftar");
	$fdata["FieldType"] = 200;

	
	
	
			

		$fdata["strField"] = "tanggaldaftar";

		$fdata["isSQLExpression"] = true;
	$fdata["FullName"] = "tanggaldaftar";

	
	
				$fdata["UploadFolder"] = "files";

//  Begin View Formats
	$fdata["ViewFormats"] = array();

	$vdata = array("ViewFormat" => "");

	
	
	
	
	
	
	
	
	
	
	
	
		$vdata["NeedEncode"] = true;

	
		$vdata["truncateText"] = true;
	$vdata["NumberOfChars"] = 80;

	$fdata["ViewFormats"]["view"] = $vdata;
//  End View Formats

//	Begin Edit Formats
	$fdata["EditFormats"] = array();

	$edata = array("EditFormat" => "Readonly");

	
		$edata["weekdayMessage"] = array("message" => "Invalid week day", "messageType" => "Text");
	$edata["weekdays"] = "[]";


	
	



	
	
	
	
			$edata["acceptFileTypes"] = ".+$";
		$edata["acceptFileTypesHtml"] = "";

		$edata["maxNumberOfFiles"] = 1;

	
	
	
	
	
	
		$edata["controlWidth"] = 200;

//	Begin validation
	$edata["validateAs"] = array();
	$edata["validateAs"]["basicValidate"] = array();
	$edata["validateAs"]["customMessages"] = array();
	
	
	//	End validation

	
			
	
	
	
	$fdata["EditFormats"]["edit"] = $edata;
//	End Edit Formats


	$fdata["isSeparate"] = false;




// the field's search options settings
		$fdata["defaultSearchOption"] = "Contains";

			// the default search options list
				$fdata["searchOptionsList"] = array("Contains", "Equals", "Starts with", "More than", "Less than", "Between", "Empty", NOT_EMPTY);
// the end of search options settings


//Filters settings
	$fdata["filterTotals"] = 0;
		$fdata["filterMultiSelect"] = 0;
			$fdata["filterFormat"] = "Values list";
		$fdata["showCollapsed"] = false;

		$fdata["sortValueType"] = 0;
		$fdata["numberOfVisibleItems"] = 10;

		$fdata["filterBy"] = 0;

	

	
	
//end of Filters settings


	$tdatacalonppdb["tanggaldaftar"] = $fdata;
		$tdatacalonppdb[".searchableFields"][] = "tanggaldaftar";
//	nomap
//	Custom field settings
	$fdata = array();
	$fdata["Index"] = 3;
	$fdata["strName"] = "nomap";
	$fdata["GoodName"] = "nomap";
	$fdata["ownerTable"] = "calonppdb";
	$fdata["Label"] = GetFieldLabel("calonppdb","nomap");
	$fdata["FieldType"] = 200;

	
	
	
			

		$fdata["strField"] = "nomap";

		$fdata["isSQLExpression"] = true;
	$fdata["FullName"] = "nomap";

	
	
				$fdata["UploadFolder"] = "files";

//  Begin View Formats
	$fdata["ViewFormats"] = array();

	$vdata = array("ViewFormat" => "Custom");

	
	
	
	
	
	
	
	
	
	
	
	
	
	
		$vdata["truncateText"] = true;
	$vdata["NumberOfChars"] = 80;

	$fdata["ViewFormats"]["view"] = $vdata;
//  End View Formats

//	Begin Edit Formats
	$fdata["EditFormats"] = array();

	$edata = array("EditFormat" => "Text field");

	
		$edata["weekdayMessage"] = array("message" => "", "messageType" => "Text");
	$edata["weekdays"] = "[]";


	
	



		$edata["IsRequired"] = true;

	
	
	
			$edata["acceptFileTypes"] = ".+$";
		$edata["acceptFileTypesHtml"] = "";

		$edata["maxNumberOfFiles"] = 1;

	
	
	
	
			$edata["HTML5InuptType"] = "text";

		$edata["EditParams"] = "";
			$edata["EditParams"].= " maxlength=4";

		$edata["controlWidth"] = 200;

//	Begin validation
	$edata["validateAs"] = array();
	$edata["validateAs"]["basicValidate"] = array();
	$edata["validateAs"]["customMessages"] = array();
						$edata["validateAs"]["basicValidate"][] = "IsRequired";
			$edata["validateAs"]["basicValidate"][] = "DenyDuplicated";
	$edata["validateAs"]["customMessages"]["DenyDuplicated"] = array("message" => "No. MAP %value% sudah masuk database PPDB, silahkan cek No. MAP PPDB Anda.", "messageType" => "Text");

	
	//	End validation

	
			
	
		$edata["denyDuplicates"] = true;

	
	$fdata["EditFormats"]["edit"] = $edata;
//	End Edit Formats


	$fdata["isSeparate"] = false;




// the field's search options settings
		$fdata["defaultSearchOption"] = "Contains";

			// the default search options list
				$fdata["searchOptionsList"] = array("Contains", "Equals", "Starts with", "More than", "Less than", "Between", "Empty", NOT_EMPTY);
// the end of search options settings


//Filters settings
	$fdata["filterTotals"] = 0;
		$fdata["filterMultiSelect"] = 0;
			$fdata["filterFormat"] = "Values list";
		$fdata["showCollapsed"] = false;

		$fdata["sortValueType"] = 0;
		$fdata["numberOfVisibleItems"] = 10;

		$fdata["filterBy"] = 0;

	

	
	
//end of Filters settings


	$tdatacalonppdb["nomap"] = $fdata;
		$tdatacalonppdb[".searchableFields"][] = "nomap";
//	namalengkap
//	Custom field settings
	$fdata = array();
	$fdata["Index"] = 4;
	$fdata["strName"] = "namalengkap";
	$fdata["GoodName"] = "namalengkap";
	$fdata["ownerTable"] = "calonppdb";
	$fdata["Label"] = GetFieldLabel("calonppdb","namalengkap");
	$fdata["FieldType"] = 200;

	
	
	
			

		$fdata["strField"] = "namalengkap";

		$fdata["isSQLExpression"] = true;
	$fdata["FullName"] = "namalengkap";

	
	
				$fdata["UploadFolder"] = "files";

//  Begin View Formats
	$fdata["ViewFormats"] = array();

	$vdata = array("ViewFormat" => "Custom");

	
	
	
	
	
	
	
	
	
	
	
	
	
	
		$vdata["truncateText"] = true;
	$vdata["NumberOfChars"] = 80;

	$fdata["ViewFormats"]["view"] = $vdata;
//  End View Formats

//	Begin Edit Formats
	$fdata["EditFormats"] = array();

	$edata = array("EditFormat" => "Text field");

	
		$edata["weekdayMessage"] = array("message" => "", "messageType" => "Text");
	$edata["weekdays"] = "[]";


	
	



		$edata["IsRequired"] = true;

	
	
	
			$edata["acceptFileTypes"] = ".+$";
		$edata["acceptFileTypesHtml"] = "";

		$edata["maxNumberOfFiles"] = 1;

	
	
	
	
			$edata["HTML5InuptType"] = "text";

		$edata["EditParams"] = "";
			$edata["EditParams"].= " maxlength=100";

		$edata["controlWidth"] = 200;

//	Begin validation
	$edata["validateAs"] = array();
	$edata["validateAs"]["basicValidate"] = array();
	$edata["validateAs"]["customMessages"] = array();
						$edata["validateAs"]["basicValidate"][] = "IsRequired";
		
	
	//	End validation

	
			
	
	
	
	$fdata["EditFormats"]["edit"] = $edata;
//	End Edit Formats


	$fdata["isSeparate"] = false;




// the field's search options settings
		$fdata["defaultSearchOption"] = "Contains";

			// the default search options list
				$fdata["searchOptionsList"] = array("Contains", "Equals", "Starts with", "More than", "Less than", "Between", "Empty", NOT_EMPTY);
// the end of search options settings


//Filters settings
	$fdata["filterTotals"] = 0;
		$fdata["filterMultiSelect"] = 0;
			$fdata["filterFormat"] = "Values list";
		$fdata["showCollapsed"] = false;

		$fdata["sortValueType"] = 0;
		$fdata["numberOfVisibleItems"] = 10;

		$fdata["filterBy"] = 0;

	

	
	
//end of Filters settings


	$tdatacalonppdb["namalengkap"] = $fdata;
		$tdatacalonppdb[".searchableFields"][] = "namalengkap";
//	jeniskelamin
//	Custom field settings
	$fdata = array();
	$fdata["Index"] = 5;
	$fdata["strName"] = "jeniskelamin";
	$fdata["GoodName"] = "jeniskelamin";
	$fdata["ownerTable"] = "calonppdb";
	$fdata["Label"] = GetFieldLabel("calonppdb","jeniskelamin");
	$fdata["FieldType"] = 200;

	
	
	
			

		$fdata["strField"] = "jeniskelamin";

		$fdata["isSQLExpression"] = true;
	$fdata["FullName"] = "jeniskelamin";

	
	
				$fdata["UploadFolder"] = "files";

//  Begin View Formats
	$fdata["ViewFormats"] = array();

	$vdata = array("ViewFormat" => "");

	
	
	
	
	
	
	
	
	
	
	
	
		$vdata["NeedEncode"] = true;

	
		$vdata["truncateText"] = true;
	$vdata["NumberOfChars"] = 80;

	$fdata["ViewFormats"]["view"] = $vdata;
//  End View Formats

//	Begin Edit Formats
	$fdata["EditFormats"] = array();

	$edata = array("EditFormat" => "Lookup wizard");

	
		$edata["weekdayMessage"] = array("message" => "", "messageType" => "Text");
	$edata["weekdays"] = "[]";


	
	

// Begin Lookup settings
		$edata["LookupType"] = 0;
			$edata["autoCompleteFieldsOnEdit"] = 0;
	$edata["autoCompleteFields"] = array();
		$edata["LCType"] = 0;

		$edata["HorizontalLookup"] = true;

	
		$edata["LookupValues"] = array();
	$edata["LookupValues"][] = "Laki - laki";
	$edata["LookupValues"][] = "Perempuan";

	
		$edata["SelectSize"] = 1;

// End Lookup Settings


		$edata["IsRequired"] = true;

	
	
	
			$edata["acceptFileTypes"] = ".+$";
		$edata["acceptFileTypesHtml"] = "";

		$edata["maxNumberOfFiles"] = 1;

	
	
	
	
	
	
		$edata["controlWidth"] = 200;

//	Begin validation
	$edata["validateAs"] = array();
	$edata["validateAs"]["basicValidate"] = array();
	$edata["validateAs"]["customMessages"] = array();
						$edata["validateAs"]["basicValidate"][] = "IsRequired";
		
	
	//	End validation

	
			
	
	
	
	$fdata["EditFormats"]["edit"] = $edata;
//	End Edit Formats


	$fdata["isSeparate"] = false;




// the field's search options settings
		$fdata["defaultSearchOption"] = "Equals";

			// the default search options list
				$fdata["searchOptionsList"] = array("Contains", "Equals", "Starts with", "More than", "Less than", "Between", "Empty", NOT_EMPTY);
// the end of search options settings


//Filters settings
	$fdata["filterTotals"] = 0;
		$fdata["filterMultiSelect"] = 0;
			$fdata["filterFormat"] = "Values list";
		$fdata["showCollapsed"] = false;

		$fdata["sortValueType"] = 0;
		$fdata["numberOfVisibleItems"] = 10;

		$fdata["filterBy"] = 0;

	

	
	
//end of Filters settings


	$tdatacalonppdb["jeniskelamin"] = $fdata;
		$tdatacalonppdb[".searchableFields"][] = "jeniskelamin";
//	nisn
//	Custom field settings
	$fdata = array();
	$fdata["Index"] = 6;
	$fdata["strName"] = "nisn";
	$fdata["GoodName"] = "nisn";
	$fdata["ownerTable"] = "calonppdb";
	$fdata["Label"] = GetFieldLabel("calonppdb","nisn");
	$fdata["FieldType"] = 200;

	
	
	
			

		$fdata["strField"] = "nisn";

		$fdata["isSQLExpression"] = true;
	$fdata["FullName"] = "nisn";

	
	
				$fdata["UploadFolder"] = "files";

//  Begin View Formats
	$fdata["ViewFormats"] = array();

	$vdata = array("ViewFormat" => "");

	
	
	
	
	
	
	
	
	
	
	
	
		$vdata["NeedEncode"] = true;

	
		$vdata["truncateText"] = true;
	$vdata["NumberOfChars"] = 80;

	$fdata["ViewFormats"]["view"] = $vdata;
//  End View Formats

//	Begin Edit Formats
	$fdata["EditFormats"] = array();

	$edata = array("EditFormat" => "Text field");

	
		$edata["weekdayMessage"] = array("message" => "", "messageType" => "Text");
	$edata["weekdays"] = "[]";


	
	



		$edata["IsRequired"] = true;

	
	
	
			$edata["acceptFileTypes"] = ".+$";
		$edata["acceptFileTypesHtml"] = "";

		$edata["maxNumberOfFiles"] = 1;

	
	
	
	
			$edata["HTML5InuptType"] = "tel";

		$edata["EditParams"] = "";
			$edata["EditParams"].= " maxlength=10";

		$edata["controlWidth"] = 200;

//	Begin validation
	$edata["validateAs"] = array();
	$edata["validateAs"]["basicValidate"] = array();
	$edata["validateAs"]["customMessages"] = array();
				$edata["validateAs"]["basicValidate"][] = getJsValidatorName("Number");
						$edata["validateAs"]["basicValidate"][] = "IsRequired";
		
	
	//	End validation

	
			
	
	
	
	$fdata["EditFormats"]["edit"] = $edata;
//	End Edit Formats


	$fdata["isSeparate"] = false;




// the field's search options settings
		$fdata["defaultSearchOption"] = "Contains";

			// the default search options list
				$fdata["searchOptionsList"] = array("Contains", "Equals", "Starts with", "More than", "Less than", "Between", "Empty", NOT_EMPTY);
// the end of search options settings


//Filters settings
	$fdata["filterTotals"] = 0;
		$fdata["filterMultiSelect"] = 0;
			$fdata["filterFormat"] = "Values list";
		$fdata["showCollapsed"] = false;

		$fdata["sortValueType"] = 0;
		$fdata["numberOfVisibleItems"] = 10;

		$fdata["filterBy"] = 0;

	

	
	
//end of Filters settings


	$tdatacalonppdb["nisn"] = $fdata;
		$tdatacalonppdb[".searchableFields"][] = "nisn";
//	nik
//	Custom field settings
	$fdata = array();
	$fdata["Index"] = 7;
	$fdata["strName"] = "nik";
	$fdata["GoodName"] = "nik";
	$fdata["ownerTable"] = "calonppdb";
	$fdata["Label"] = GetFieldLabel("calonppdb","nik");
	$fdata["FieldType"] = 200;

	
	
	
			

		$fdata["strField"] = "nik";

		$fdata["isSQLExpression"] = true;
	$fdata["FullName"] = "nik";

	
	
				$fdata["UploadFolder"] = "files";

//  Begin View Formats
	$fdata["ViewFormats"] = array();

	$vdata = array("ViewFormat" => "");

	
	
	
	
	
	
	
	
	
	
	
	
		$vdata["NeedEncode"] = true;

	
		$vdata["truncateText"] = true;
	$vdata["NumberOfChars"] = 80;

	$fdata["ViewFormats"]["view"] = $vdata;
//  End View Formats

//	Begin Edit Formats
	$fdata["EditFormats"] = array();

	$edata = array("EditFormat" => "Text field");

	
		$edata["weekdayMessage"] = array("message" => "", "messageType" => "Text");
	$edata["weekdays"] = "[]";


	
	



		$edata["IsRequired"] = true;

	
	
	
			$edata["acceptFileTypes"] = ".+$";
		$edata["acceptFileTypesHtml"] = "";

		$edata["maxNumberOfFiles"] = 1;

	
	
	
	
			$edata["HTML5InuptType"] = "tel";

		$edata["EditParams"] = "";
			$edata["EditParams"].= " maxlength=16";

		$edata["controlWidth"] = 200;

//	Begin validation
	$edata["validateAs"] = array();
	$edata["validateAs"]["basicValidate"] = array();
	$edata["validateAs"]["customMessages"] = array();
				$edata["validateAs"]["basicValidate"][] = getJsValidatorName("Number");
						$edata["validateAs"]["basicValidate"][] = "IsRequired";
			$edata["validateAs"]["basicValidate"][] = "DenyDuplicated";
	$edata["validateAs"]["customMessages"]["DenyDuplicated"] = array("message" => "Nomor NIK %value% sudah masuk database. Silahkan pilih menu data Calon Siswa Baru", "messageType" => "Text");

	
	//	End validation

	
			
	
		$edata["denyDuplicates"] = true;

	
	$fdata["EditFormats"]["edit"] = $edata;
//	End Edit Formats


	$fdata["isSeparate"] = false;




// the field's search options settings
		$fdata["defaultSearchOption"] = "Contains";

			// the default search options list
				$fdata["searchOptionsList"] = array("Contains", "Equals", "Starts with", "More than", "Less than", "Between", "Empty", NOT_EMPTY);
// the end of search options settings


//Filters settings
	$fdata["filterTotals"] = 0;
		$fdata["filterMultiSelect"] = 0;
			$fdata["filterFormat"] = "Values list";
		$fdata["showCollapsed"] = false;

		$fdata["sortValueType"] = 0;
		$fdata["numberOfVisibleItems"] = 10;

		$fdata["filterBy"] = 0;

	

	
	
//end of Filters settings


	$tdatacalonppdb["nik"] = $fdata;
		$tdatacalonppdb[".searchableFields"][] = "nik";
//	tempatlahir
//	Custom field settings
	$fdata = array();
	$fdata["Index"] = 8;
	$fdata["strName"] = "tempatlahir";
	$fdata["GoodName"] = "tempatlahir";
	$fdata["ownerTable"] = "calonppdb";
	$fdata["Label"] = GetFieldLabel("calonppdb","tempatlahir");
	$fdata["FieldType"] = 200;

	
	
	
			

		$fdata["strField"] = "tempatlahir";

		$fdata["isSQLExpression"] = true;
	$fdata["FullName"] = "tempatlahir";

	
	
				$fdata["UploadFolder"] = "files";

//  Begin View Formats
	$fdata["ViewFormats"] = array();

	$vdata = array("ViewFormat" => "Custom");

	
	
	
	
	
	
	
	
	
	
	
	
	
	
		$vdata["truncateText"] = true;
	$vdata["NumberOfChars"] = 80;

	$fdata["ViewFormats"]["view"] = $vdata;
//  End View Formats

//	Begin Edit Formats
	$fdata["EditFormats"] = array();

	$edata = array("EditFormat" => "Text field");

	
		$edata["weekdayMessage"] = array("message" => "", "messageType" => "Text");
	$edata["weekdays"] = "[]";


	
	



		$edata["IsRequired"] = true;

	
	
	
			$edata["acceptFileTypes"] = ".+$";
		$edata["acceptFileTypesHtml"] = "";

		$edata["maxNumberOfFiles"] = 1;

	
	
	
	
			$edata["HTML5InuptType"] = "text";

		$edata["EditParams"] = "";
			$edata["EditParams"].= " maxlength=50";

		$edata["controlWidth"] = 200;

//	Begin validation
	$edata["validateAs"] = array();
	$edata["validateAs"]["basicValidate"] = array();
	$edata["validateAs"]["customMessages"] = array();
						$edata["validateAs"]["basicValidate"][] = "IsRequired";
		
	
	//	End validation

	
			
	
	
	
	$fdata["EditFormats"]["edit"] = $edata;
//	End Edit Formats


	$fdata["isSeparate"] = false;




// the field's search options settings
		$fdata["defaultSearchOption"] = "Contains";

			// the default search options list
				$fdata["searchOptionsList"] = array("Contains", "Equals", "Starts with", "More than", "Less than", "Between", "Empty", NOT_EMPTY);
// the end of search options settings


//Filters settings
	$fdata["filterTotals"] = 0;
		$fdata["filterMultiSelect"] = 0;
			$fdata["filterFormat"] = "Values list";
		$fdata["showCollapsed"] = false;

		$fdata["sortValueType"] = 0;
		$fdata["numberOfVisibleItems"] = 10;

		$fdata["filterBy"] = 0;

	

	
	
//end of Filters settings


	$tdatacalonppdb["tempatlahir"] = $fdata;
		$tdatacalonppdb[".searchableFields"][] = "tempatlahir";
//	tanggallahir1
//	Custom field settings
	$fdata = array();
	$fdata["Index"] = 9;
	$fdata["strName"] = "tanggallahir1";
	$fdata["GoodName"] = "tanggallahir1";
	$fdata["ownerTable"] = "calonppdb";
	$fdata["Label"] = GetFieldLabel("calonppdb","tanggallahir1");
	$fdata["FieldType"] = 200;

	
	
	
			

		$fdata["strField"] = "tanggallahir1";

		$fdata["isSQLExpression"] = true;
	$fdata["FullName"] = "tanggallahir1";

	
	
				$fdata["UploadFolder"] = "files";

//  Begin View Formats
	$fdata["ViewFormats"] = array();

	$vdata = array("ViewFormat" => "");

	
	
	
	
	
	
	
	
	
	
	
	
		$vdata["NeedEncode"] = true;

	
		$vdata["truncateText"] = true;
	$vdata["NumberOfChars"] = 80;

	$fdata["ViewFormats"]["view"] = $vdata;
//  End View Formats

//	Begin Edit Formats
	$fdata["EditFormats"] = array();

	$edata = array("EditFormat" => "Date");

	
		$edata["weekdayMessage"] = array("message" => "Invalid week day", "messageType" => "Text");
	$edata["weekdays"] = "[]";


	
	



		$edata["IsRequired"] = true;

	
	
	
			$edata["acceptFileTypes"] = ".+$";
		$edata["acceptFileTypesHtml"] = "";

		$edata["maxNumberOfFiles"] = 1;

	
	
		$edata["DateEditType"] = 12;
	$edata["InitialYearFactor"] = 14;
	$edata["LastYearFactor"] = 0;

	
	
	
		$edata["controlWidth"] = 200;

//	Begin validation
	$edata["validateAs"] = array();
	$edata["validateAs"]["basicValidate"] = array();
	$edata["validateAs"]["customMessages"] = array();
						$edata["validateAs"]["basicValidate"][] = "IsRequired";
		
	
	//	End validation

	
			
	
	
	
	$fdata["EditFormats"]["edit"] = $edata;
//	End Edit Formats


	$fdata["isSeparate"] = false;




// the field's search options settings
		$fdata["defaultSearchOption"] = "Equals";

			// the default search options list
				$fdata["searchOptionsList"] = array("Contains", "Equals", "Starts with", "More than", "Less than", "Between", "Empty", NOT_EMPTY);
// the end of search options settings


//Filters settings
	$fdata["filterTotals"] = 0;
		$fdata["filterMultiSelect"] = 0;
			$fdata["filterFormat"] = "Values list";
		$fdata["showCollapsed"] = false;

		$fdata["sortValueType"] = 0;
		$fdata["numberOfVisibleItems"] = 10;

		$fdata["filterBy"] = 0;

	

	
	
//end of Filters settings


	$tdatacalonppdb["tanggallahir1"] = $fdata;
		$tdatacalonppdb[".searchableFields"][] = "tanggallahir1";
//	noaktalahir
//	Custom field settings
	$fdata = array();
	$fdata["Index"] = 10;
	$fdata["strName"] = "noaktalahir";
	$fdata["GoodName"] = "noaktalahir";
	$fdata["ownerTable"] = "calonppdb";
	$fdata["Label"] = GetFieldLabel("calonppdb","noaktalahir");
	$fdata["FieldType"] = 200;

	
	
	
			

		$fdata["strField"] = "noaktalahir";

		$fdata["isSQLExpression"] = true;
	$fdata["FullName"] = "noaktalahir";

	
	
				$fdata["UploadFolder"] = "files";

//  Begin View Formats
	$fdata["ViewFormats"] = array();

	$vdata = array("ViewFormat" => "");

	
	
	
	
	
	
	
	
	
	
	
	
		$vdata["NeedEncode"] = true;

	
		$vdata["truncateText"] = true;
	$vdata["NumberOfChars"] = 80;

	$fdata["ViewFormats"]["view"] = $vdata;
//  End View Formats

//	Begin Edit Formats
	$fdata["EditFormats"] = array();

	$edata = array("EditFormat" => "Text field");

	
		$edata["weekdayMessage"] = array("message" => "", "messageType" => "Text");
	$edata["weekdays"] = "[]";


	
	



		$edata["IsRequired"] = true;

	
	
	
			$edata["acceptFileTypes"] = ".+$";
		$edata["acceptFileTypesHtml"] = "";

		$edata["maxNumberOfFiles"] = 1;

	
	
	
	
			$edata["HTML5InuptType"] = "text";

		$edata["EditParams"] = "";
			$edata["EditParams"].= " maxlength=50";

		$edata["controlWidth"] = 200;

//	Begin validation
	$edata["validateAs"] = array();
	$edata["validateAs"]["basicValidate"] = array();
	$edata["validateAs"]["customMessages"] = array();
						$edata["validateAs"]["basicValidate"][] = "IsRequired";
			$edata["validateAs"]["basicValidate"][] = "DenyDuplicated";
	$edata["validateAs"]["customMessages"]["DenyDuplicated"] = array("message" => "Nomor Akta %value% sudah masuk database. Silahkan cek kembali nomor Akta / Surat Lahir Siswa", "messageType" => "Text");

	
	//	End validation

	
			
	
		$edata["denyDuplicates"] = true;

	
	$fdata["EditFormats"]["edit"] = $edata;
//	End Edit Formats


	$fdata["isSeparate"] = false;




// the field's search options settings
		$fdata["defaultSearchOption"] = "Contains";

			// the default search options list
				$fdata["searchOptionsList"] = array("Contains", "Equals", "Starts with", "More than", "Less than", "Between", "Empty", NOT_EMPTY);
// the end of search options settings


//Filters settings
	$fdata["filterTotals"] = 0;
		$fdata["filterMultiSelect"] = 0;
			$fdata["filterFormat"] = "Values list";
		$fdata["showCollapsed"] = false;

		$fdata["sortValueType"] = 0;
		$fdata["numberOfVisibleItems"] = 10;

		$fdata["filterBy"] = 0;

	

	
	
//end of Filters settings


	$tdatacalonppdb["noaktalahir"] = $fdata;
		$tdatacalonppdb[".searchableFields"][] = "noaktalahir";
//	agama
//	Custom field settings
	$fdata = array();
	$fdata["Index"] = 11;
	$fdata["strName"] = "agama";
	$fdata["GoodName"] = "agama";
	$fdata["ownerTable"] = "calonppdb";
	$fdata["Label"] = GetFieldLabel("calonppdb","agama");
	$fdata["FieldType"] = 200;

	
	
	
			

		$fdata["strField"] = "agama";

		$fdata["isSQLExpression"] = true;
	$fdata["FullName"] = "agama";

	
	
				$fdata["UploadFolder"] = "files";

//  Begin View Formats
	$fdata["ViewFormats"] = array();

	$vdata = array("ViewFormat" => "");

	
	
	
	
	
	
	
	
	
	
	
	
		$vdata["NeedEncode"] = true;

	
		$vdata["truncateText"] = true;
	$vdata["NumberOfChars"] = 80;

	$fdata["ViewFormats"]["view"] = $vdata;
//  End View Formats

//	Begin Edit Formats
	$fdata["EditFormats"] = array();

	$edata = array("EditFormat" => "Lookup wizard");

	
		$edata["weekdayMessage"] = array("message" => "", "messageType" => "Text");
	$edata["weekdays"] = "[]";


	
	

// Begin Lookup settings
		$edata["LookupType"] = 0;
			$edata["autoCompleteFieldsOnEdit"] = 0;
	$edata["autoCompleteFields"] = array();
		$edata["LCType"] = 0;

	
	
		$edata["LookupValues"] = array();
	$edata["LookupValues"][] = "Islam";
	$edata["LookupValues"][] = "Kristen";
	$edata["LookupValues"][] = "Katolik";
	$edata["LookupValues"][] = "Hindu";
	$edata["LookupValues"][] = "Budha";
	$edata["LookupValues"][] = "Konghucu";

	
		$edata["SelectSize"] = 1;

// End Lookup Settings


		$edata["IsRequired"] = true;

	
	
	
			$edata["acceptFileTypes"] = ".+$";
		$edata["acceptFileTypesHtml"] = "";

		$edata["maxNumberOfFiles"] = 1;

	
	
	
	
	
	
		$edata["controlWidth"] = 200;

//	Begin validation
	$edata["validateAs"] = array();
	$edata["validateAs"]["basicValidate"] = array();
	$edata["validateAs"]["customMessages"] = array();
						$edata["validateAs"]["basicValidate"][] = "IsRequired";
		
	
	//	End validation

	
			
	
	
	
	$fdata["EditFormats"]["edit"] = $edata;
//	End Edit Formats


	$fdata["isSeparate"] = false;




// the field's search options settings
		$fdata["defaultSearchOption"] = "Equals";

			// the default search options list
				$fdata["searchOptionsList"] = array("Contains", "Equals", "Starts with", "More than", "Less than", "Between", "Empty", NOT_EMPTY);
// the end of search options settings


//Filters settings
	$fdata["filterTotals"] = 0;
		$fdata["filterMultiSelect"] = 0;
			$fdata["filterFormat"] = "Values list";
		$fdata["showCollapsed"] = false;

		$fdata["sortValueType"] = 0;
		$fdata["numberOfVisibleItems"] = 10;

		$fdata["filterBy"] = 0;

	

	
	
//end of Filters settings


	$tdatacalonppdb["agama"] = $fdata;
		$tdatacalonppdb[".searchableFields"][] = "agama";
//	alamatjalan
//	Custom field settings
	$fdata = array();
	$fdata["Index"] = 12;
	$fdata["strName"] = "alamatjalan";
	$fdata["GoodName"] = "alamatjalan";
	$fdata["ownerTable"] = "calonppdb";
	$fdata["Label"] = GetFieldLabel("calonppdb","alamatjalan");
	$fdata["FieldType"] = 200;

	
	
	
			

		$fdata["strField"] = "alamatjalan";

		$fdata["isSQLExpression"] = true;
	$fdata["FullName"] = "alamatjalan";

	
	
				$fdata["UploadFolder"] = "files";

//  Begin View Formats
	$fdata["ViewFormats"] = array();

	$vdata = array("ViewFormat" => "Custom");

	
	
	
	
	
	
	
	
	
	
	
	
	
	
		$vdata["truncateText"] = true;
	$vdata["NumberOfChars"] = 80;

	$fdata["ViewFormats"]["view"] = $vdata;
//  End View Formats

//	Begin Edit Formats
	$fdata["EditFormats"] = array();

	$edata = array("EditFormat" => "Text field");

	
		$edata["weekdayMessage"] = array("message" => "", "messageType" => "Text");
	$edata["weekdays"] = "[]";


	
	



		$edata["IsRequired"] = true;

	
	
	
			$edata["acceptFileTypes"] = ".+$";
		$edata["acceptFileTypesHtml"] = "";

		$edata["maxNumberOfFiles"] = 1;

	
	
	
	
			$edata["HTML5InuptType"] = "text";

		$edata["EditParams"] = "";
			$edata["EditParams"].= " maxlength=100";

		$edata["controlWidth"] = 200;

//	Begin validation
	$edata["validateAs"] = array();
	$edata["validateAs"]["basicValidate"] = array();
	$edata["validateAs"]["customMessages"] = array();
						$edata["validateAs"]["basicValidate"][] = "IsRequired";
		
	
	//	End validation

	
			
	
	
	
	$fdata["EditFormats"]["edit"] = $edata;
//	End Edit Formats


	$fdata["isSeparate"] = false;




// the field's search options settings
		$fdata["defaultSearchOption"] = "Contains";

			// the default search options list
				$fdata["searchOptionsList"] = array("Contains", "Equals", "Starts with", "More than", "Less than", "Between", "Empty", NOT_EMPTY);
// the end of search options settings


//Filters settings
	$fdata["filterTotals"] = 0;
		$fdata["filterMultiSelect"] = 0;
			$fdata["filterFormat"] = "Values list";
		$fdata["showCollapsed"] = false;

		$fdata["sortValueType"] = 0;
		$fdata["numberOfVisibleItems"] = 10;

		$fdata["filterBy"] = 0;

	

	
	
//end of Filters settings


	$tdatacalonppdb["alamatjalan"] = $fdata;
		$tdatacalonppdb[".searchableFields"][] = "alamatjalan";
//	rt
//	Custom field settings
	$fdata = array();
	$fdata["Index"] = 13;
	$fdata["strName"] = "rt";
	$fdata["GoodName"] = "rt";
	$fdata["ownerTable"] = "calonppdb";
	$fdata["Label"] = GetFieldLabel("calonppdb","rt");
	$fdata["FieldType"] = 200;

	
	
	
			

		$fdata["strField"] = "rt";

		$fdata["isSQLExpression"] = true;
	$fdata["FullName"] = "rt";

	
	
				$fdata["UploadFolder"] = "files";

//  Begin View Formats
	$fdata["ViewFormats"] = array();

	$vdata = array("ViewFormat" => "");

	
	
	
	
	
	
	
	
	
	
	
	
		$vdata["NeedEncode"] = true;

	
		$vdata["truncateText"] = true;
	$vdata["NumberOfChars"] = 80;

	$fdata["ViewFormats"]["view"] = $vdata;
//  End View Formats

//	Begin Edit Formats
	$fdata["EditFormats"] = array();

	$edata = array("EditFormat" => "Text field");

	
		$edata["weekdayMessage"] = array("message" => "", "messageType" => "Text");
	$edata["weekdays"] = "[]";


	
	



		$edata["IsRequired"] = true;

	
	
	
			$edata["acceptFileTypes"] = ".+$";
		$edata["acceptFileTypesHtml"] = "";

		$edata["maxNumberOfFiles"] = 1;

	
	
	
	
			$edata["HTML5InuptType"] = "tel";

		$edata["EditParams"] = "";
			$edata["EditParams"].= " maxlength=3";

		$edata["controlWidth"] = 200;

//	Begin validation
	$edata["validateAs"] = array();
	$edata["validateAs"]["basicValidate"] = array();
	$edata["validateAs"]["customMessages"] = array();
				$edata["validateAs"]["basicValidate"][] = getJsValidatorName("Number");
						$edata["validateAs"]["basicValidate"][] = "IsRequired";
		
	
	//	End validation

	
			
	
	
	
	$fdata["EditFormats"]["edit"] = $edata;
//	End Edit Formats


	$fdata["isSeparate"] = false;




// the field's search options settings
		$fdata["defaultSearchOption"] = "Contains";

			// the default search options list
				$fdata["searchOptionsList"] = array("Contains", "Equals", "Starts with", "More than", "Less than", "Between", "Empty", NOT_EMPTY);
// the end of search options settings


//Filters settings
	$fdata["filterTotals"] = 0;
		$fdata["filterMultiSelect"] = 0;
			$fdata["filterFormat"] = "Values list";
		$fdata["showCollapsed"] = false;

		$fdata["sortValueType"] = 0;
		$fdata["numberOfVisibleItems"] = 10;

		$fdata["filterBy"] = 0;

	

	
	
//end of Filters settings


	$tdatacalonppdb["rt"] = $fdata;
		$tdatacalonppdb[".searchableFields"][] = "rt";
//	rw
//	Custom field settings
	$fdata = array();
	$fdata["Index"] = 14;
	$fdata["strName"] = "rw";
	$fdata["GoodName"] = "rw";
	$fdata["ownerTable"] = "calonppdb";
	$fdata["Label"] = GetFieldLabel("calonppdb","rw");
	$fdata["FieldType"] = 200;

	
	
	
			

		$fdata["strField"] = "rw";

		$fdata["isSQLExpression"] = true;
	$fdata["FullName"] = "rw";

	
	
				$fdata["UploadFolder"] = "files";

//  Begin View Formats
	$fdata["ViewFormats"] = array();

	$vdata = array("ViewFormat" => "");

	
	
	
	
	
	
	
	
	
	
	
	
		$vdata["NeedEncode"] = true;

	
		$vdata["truncateText"] = true;
	$vdata["NumberOfChars"] = 80;

	$fdata["ViewFormats"]["view"] = $vdata;
//  End View Formats

//	Begin Edit Formats
	$fdata["EditFormats"] = array();

	$edata = array("EditFormat" => "Text field");

	
		$edata["weekdayMessage"] = array("message" => "", "messageType" => "Text");
	$edata["weekdays"] = "[]";


	
	



		$edata["IsRequired"] = true;

	
	
	
			$edata["acceptFileTypes"] = ".+$";
		$edata["acceptFileTypesHtml"] = "";

		$edata["maxNumberOfFiles"] = 1;

	
	
	
	
			$edata["HTML5InuptType"] = "tel";

		$edata["EditParams"] = "";
			$edata["EditParams"].= " maxlength=3";

		$edata["controlWidth"] = 200;

//	Begin validation
	$edata["validateAs"] = array();
	$edata["validateAs"]["basicValidate"] = array();
	$edata["validateAs"]["customMessages"] = array();
				$edata["validateAs"]["basicValidate"][] = getJsValidatorName("Number");
						$edata["validateAs"]["basicValidate"][] = "IsRequired";
		
	
	//	End validation

	
			
	
	
	
	$fdata["EditFormats"]["edit"] = $edata;
//	End Edit Formats


	$fdata["isSeparate"] = false;




// the field's search options settings
		$fdata["defaultSearchOption"] = "Contains";

			// the default search options list
				$fdata["searchOptionsList"] = array("Contains", "Equals", "Starts with", "More than", "Less than", "Between", "Empty", NOT_EMPTY);
// the end of search options settings


//Filters settings
	$fdata["filterTotals"] = 0;
		$fdata["filterMultiSelect"] = 0;
			$fdata["filterFormat"] = "Values list";
		$fdata["showCollapsed"] = false;

		$fdata["sortValueType"] = 0;
		$fdata["numberOfVisibleItems"] = 10;

		$fdata["filterBy"] = 0;

	

	
	
//end of Filters settings


	$tdatacalonppdb["rw"] = $fdata;
		$tdatacalonppdb[".searchableFields"][] = "rw";
//	dusun
//	Custom field settings
	$fdata = array();
	$fdata["Index"] = 15;
	$fdata["strName"] = "dusun";
	$fdata["GoodName"] = "dusun";
	$fdata["ownerTable"] = "calonppdb";
	$fdata["Label"] = GetFieldLabel("calonppdb","dusun");
	$fdata["FieldType"] = 200;

	
	
	
			

		$fdata["strField"] = "dusun";

		$fdata["isSQLExpression"] = true;
	$fdata["FullName"] = "dusun";

	
	
				$fdata["UploadFolder"] = "files";

//  Begin View Formats
	$fdata["ViewFormats"] = array();

	$vdata = array("ViewFormat" => "Custom");

	
	
	
	
	
	
	
	
	
	
	
	
	
	
		$vdata["truncateText"] = true;
	$vdata["NumberOfChars"] = 80;

	$fdata["ViewFormats"]["view"] = $vdata;
//  End View Formats

//	Begin Edit Formats
	$fdata["EditFormats"] = array();

	$edata = array("EditFormat" => "Text field");

	
		$edata["weekdayMessage"] = array("message" => "", "messageType" => "Text");
	$edata["weekdays"] = "[]";


	
	



	
	
	
	
			$edata["acceptFileTypes"] = ".+$";
		$edata["acceptFileTypesHtml"] = "";

		$edata["maxNumberOfFiles"] = 1;

	
	
	
	
			$edata["HTML5InuptType"] = "text";

		$edata["EditParams"] = "";
			$edata["EditParams"].= " maxlength=50";

		$edata["controlWidth"] = 200;

//	Begin validation
	$edata["validateAs"] = array();
	$edata["validateAs"]["basicValidate"] = array();
	$edata["validateAs"]["customMessages"] = array();
	
	
	//	End validation

	
			
	
	
	
	$fdata["EditFormats"]["edit"] = $edata;
//	End Edit Formats


	$fdata["isSeparate"] = false;




// the field's search options settings
		$fdata["defaultSearchOption"] = "Contains";

			// the default search options list
				$fdata["searchOptionsList"] = array("Contains", "Equals", "Starts with", "More than", "Less than", "Between", "Empty", NOT_EMPTY);
// the end of search options settings


//Filters settings
	$fdata["filterTotals"] = 0;
		$fdata["filterMultiSelect"] = 0;
			$fdata["filterFormat"] = "Values list";
		$fdata["showCollapsed"] = false;

		$fdata["sortValueType"] = 0;
		$fdata["numberOfVisibleItems"] = 10;

		$fdata["filterBy"] = 0;

	

	
	
//end of Filters settings


	$tdatacalonppdb["dusun"] = $fdata;
		$tdatacalonppdb[".searchableFields"][] = "dusun";
//	deskel
//	Custom field settings
	$fdata = array();
	$fdata["Index"] = 16;
	$fdata["strName"] = "deskel";
	$fdata["GoodName"] = "deskel";
	$fdata["ownerTable"] = "calonppdb";
	$fdata["Label"] = GetFieldLabel("calonppdb","deskel");
	$fdata["FieldType"] = 200;

	
	
	
			

		$fdata["strField"] = "deskel";

		$fdata["isSQLExpression"] = true;
	$fdata["FullName"] = "deskel";

	
	
				$fdata["UploadFolder"] = "files";

//  Begin View Formats
	$fdata["ViewFormats"] = array();

	$vdata = array("ViewFormat" => "Custom");

	
	
	
	
	
	
	
	
	
	
	
	
	
	
		$vdata["truncateText"] = true;
	$vdata["NumberOfChars"] = 80;

	$fdata["ViewFormats"]["view"] = $vdata;
//  End View Formats

//	Begin Edit Formats
	$fdata["EditFormats"] = array();

	$edata = array("EditFormat" => "Text field");

	
		$edata["weekdayMessage"] = array("message" => "", "messageType" => "Text");
	$edata["weekdays"] = "[]";


	
	



		$edata["IsRequired"] = true;

	
	
	
			$edata["acceptFileTypes"] = ".+$";
		$edata["acceptFileTypesHtml"] = "";

		$edata["maxNumberOfFiles"] = 1;

	
	
	
	
			$edata["HTML5InuptType"] = "text";

		$edata["EditParams"] = "";
			$edata["EditParams"].= " maxlength=50";

		$edata["controlWidth"] = 200;

//	Begin validation
	$edata["validateAs"] = array();
	$edata["validateAs"]["basicValidate"] = array();
	$edata["validateAs"]["customMessages"] = array();
						$edata["validateAs"]["basicValidate"][] = "IsRequired";
		
	
	//	End validation

	
			
	
	
	
	$fdata["EditFormats"]["edit"] = $edata;
//	End Edit Formats


	$fdata["isSeparate"] = false;




// the field's search options settings
		$fdata["defaultSearchOption"] = "Contains";

			// the default search options list
				$fdata["searchOptionsList"] = array("Contains", "Equals", "Starts with", "More than", "Less than", "Between", "Empty", NOT_EMPTY);
// the end of search options settings


//Filters settings
	$fdata["filterTotals"] = 0;
		$fdata["filterMultiSelect"] = 0;
			$fdata["filterFormat"] = "Values list";
		$fdata["showCollapsed"] = false;

		$fdata["sortValueType"] = 0;
		$fdata["numberOfVisibleItems"] = 10;

		$fdata["filterBy"] = 0;

	

	
	
//end of Filters settings


	$tdatacalonppdb["deskel"] = $fdata;
		$tdatacalonppdb[".searchableFields"][] = "deskel";
//	kecamatan
//	Custom field settings
	$fdata = array();
	$fdata["Index"] = 17;
	$fdata["strName"] = "kecamatan";
	$fdata["GoodName"] = "kecamatan";
	$fdata["ownerTable"] = "calonppdb";
	$fdata["Label"] = GetFieldLabel("calonppdb","kecamatan");
	$fdata["FieldType"] = 200;

	
	
	
			

		$fdata["strField"] = "kecamatan";

		$fdata["isSQLExpression"] = true;
	$fdata["FullName"] = "kecamatan";

	
	
				$fdata["UploadFolder"] = "files";

//  Begin View Formats
	$fdata["ViewFormats"] = array();

	$vdata = array("ViewFormat" => "Custom");

	
	
	
	
	
	
	
	
	
	
	
	
	
	
		$vdata["truncateText"] = true;
	$vdata["NumberOfChars"] = 80;

	$fdata["ViewFormats"]["view"] = $vdata;
//  End View Formats

//	Begin Edit Formats
	$fdata["EditFormats"] = array();

	$edata = array("EditFormat" => "Text field");

	
		$edata["weekdayMessage"] = array("message" => "", "messageType" => "Text");
	$edata["weekdays"] = "[]";


	
	



		$edata["IsRequired"] = true;

	
	
	
			$edata["acceptFileTypes"] = ".+$";
		$edata["acceptFileTypesHtml"] = "";

		$edata["maxNumberOfFiles"] = 1;

	
	
	
	
			$edata["HTML5InuptType"] = "text";

		$edata["EditParams"] = "";
			$edata["EditParams"].= " maxlength=50";

		$edata["controlWidth"] = 200;

//	Begin validation
	$edata["validateAs"] = array();
	$edata["validateAs"]["basicValidate"] = array();
	$edata["validateAs"]["customMessages"] = array();
						$edata["validateAs"]["basicValidate"][] = "IsRequired";
		
	
	//	End validation

	
			
	
	
	
	$fdata["EditFormats"]["edit"] = $edata;
//	End Edit Formats


	$fdata["isSeparate"] = false;




// the field's search options settings
		$fdata["defaultSearchOption"] = "Contains";

			// the default search options list
				$fdata["searchOptionsList"] = array("Contains", "Equals", "Starts with", "More than", "Less than", "Between", "Empty", NOT_EMPTY);
// the end of search options settings


//Filters settings
	$fdata["filterTotals"] = 0;
		$fdata["filterMultiSelect"] = 0;
			$fdata["filterFormat"] = "Values list";
		$fdata["showCollapsed"] = false;

		$fdata["sortValueType"] = 0;
		$fdata["numberOfVisibleItems"] = 10;

		$fdata["filterBy"] = 0;

	

	
	
//end of Filters settings


	$tdatacalonppdb["kecamatan"] = $fdata;
		$tdatacalonppdb[".searchableFields"][] = "kecamatan";
//	kodepos
//	Custom field settings
	$fdata = array();
	$fdata["Index"] = 18;
	$fdata["strName"] = "kodepos";
	$fdata["GoodName"] = "kodepos";
	$fdata["ownerTable"] = "calonppdb";
	$fdata["Label"] = GetFieldLabel("calonppdb","kodepos");
	$fdata["FieldType"] = 200;

	
	
	
			

		$fdata["strField"] = "kodepos";

		$fdata["isSQLExpression"] = true;
	$fdata["FullName"] = "kodepos";

	
	
				$fdata["UploadFolder"] = "files";

//  Begin View Formats
	$fdata["ViewFormats"] = array();

	$vdata = array("ViewFormat" => "");

	
	
	
	
	
	
	
	
	
	
	
	
		$vdata["NeedEncode"] = true;

	
		$vdata["truncateText"] = true;
	$vdata["NumberOfChars"] = 80;

	$fdata["ViewFormats"]["view"] = $vdata;
//  End View Formats

//	Begin Edit Formats
	$fdata["EditFormats"] = array();

	$edata = array("EditFormat" => "Text field");

	
		$edata["weekdayMessage"] = array("message" => "", "messageType" => "Text");
	$edata["weekdays"] = "[]";


	
	



		$edata["IsRequired"] = true;

	
	
	
			$edata["acceptFileTypes"] = ".+$";
		$edata["acceptFileTypesHtml"] = "";

		$edata["maxNumberOfFiles"] = 1;

	
	
	
	
			$edata["HTML5InuptType"] = "tel";

		$edata["EditParams"] = "";
			$edata["EditParams"].= " maxlength=5";

		$edata["controlWidth"] = 200;

//	Begin validation
	$edata["validateAs"] = array();
	$edata["validateAs"]["basicValidate"] = array();
	$edata["validateAs"]["customMessages"] = array();
				$edata["validateAs"]["basicValidate"][] = getJsValidatorName("Number");
						$edata["validateAs"]["basicValidate"][] = "IsRequired";
		
	
	//	End validation

	
			
	
	
	
	$fdata["EditFormats"]["edit"] = $edata;
//	End Edit Formats


	$fdata["isSeparate"] = false;




// the field's search options settings
		$fdata["defaultSearchOption"] = "Contains";

			// the default search options list
				$fdata["searchOptionsList"] = array("Contains", "Equals", "Starts with", "More than", "Less than", "Between", "Empty", NOT_EMPTY);
// the end of search options settings


//Filters settings
	$fdata["filterTotals"] = 0;
		$fdata["filterMultiSelect"] = 0;
			$fdata["filterFormat"] = "Values list";
		$fdata["showCollapsed"] = false;

		$fdata["sortValueType"] = 0;
		$fdata["numberOfVisibleItems"] = 10;

		$fdata["filterBy"] = 0;

	

	
	
//end of Filters settings


	$tdatacalonppdb["kodepos"] = $fdata;
		$tdatacalonppdb[".searchableFields"][] = "kodepos";
//	tempattinggal
//	Custom field settings
	$fdata = array();
	$fdata["Index"] = 19;
	$fdata["strName"] = "tempattinggal";
	$fdata["GoodName"] = "tempattinggal";
	$fdata["ownerTable"] = "calonppdb";
	$fdata["Label"] = GetFieldLabel("calonppdb","tempattinggal");
	$fdata["FieldType"] = 200;

	
	
	
			

		$fdata["strField"] = "tempattinggal";

		$fdata["isSQLExpression"] = true;
	$fdata["FullName"] = "tempattinggal";

	
	
				$fdata["UploadFolder"] = "files";

//  Begin View Formats
	$fdata["ViewFormats"] = array();

	$vdata = array("ViewFormat" => "");

	
	
	
	
	
	
	
	
	
	
	
	
		$vdata["NeedEncode"] = true;

	
		$vdata["truncateText"] = true;
	$vdata["NumberOfChars"] = 80;

	$fdata["ViewFormats"]["view"] = $vdata;
//  End View Formats

//	Begin Edit Formats
	$fdata["EditFormats"] = array();

	$edata = array("EditFormat" => "Lookup wizard");

	
		$edata["weekdayMessage"] = array("message" => "", "messageType" => "Text");
	$edata["weekdays"] = "[]";


	
	

// Begin Lookup settings
		$edata["LookupType"] = 0;
			$edata["autoCompleteFieldsOnEdit"] = 0;
	$edata["autoCompleteFields"] = array();
		$edata["LCType"] = 0;

	
	
		$edata["LookupValues"] = array();
	$edata["LookupValues"][] = "Bersama Orang Tua";
	$edata["LookupValues"][] = "Bersama Wali";
	$edata["LookupValues"][] = "Panti Asuhan";
	$edata["LookupValues"][] = "Pesantran";

	
		$edata["SelectSize"] = 1;

// End Lookup Settings


		$edata["IsRequired"] = true;

	
	
	
			$edata["acceptFileTypes"] = ".+$";
		$edata["acceptFileTypesHtml"] = "";

		$edata["maxNumberOfFiles"] = 1;

	
	
	
	
	
	
		$edata["controlWidth"] = 200;

//	Begin validation
	$edata["validateAs"] = array();
	$edata["validateAs"]["basicValidate"] = array();
	$edata["validateAs"]["customMessages"] = array();
						$edata["validateAs"]["basicValidate"][] = "IsRequired";
		
	
	//	End validation

	
			
	
	
	
	$fdata["EditFormats"]["edit"] = $edata;
//	End Edit Formats


	$fdata["isSeparate"] = false;




// the field's search options settings
		$fdata["defaultSearchOption"] = "Equals";

			// the default search options list
				$fdata["searchOptionsList"] = array("Contains", "Equals", "Starts with", "More than", "Less than", "Between", "Empty", NOT_EMPTY);
// the end of search options settings


//Filters settings
	$fdata["filterTotals"] = 0;
		$fdata["filterMultiSelect"] = 0;
			$fdata["filterFormat"] = "Values list";
		$fdata["showCollapsed"] = false;

		$fdata["sortValueType"] = 0;
		$fdata["numberOfVisibleItems"] = 10;

		$fdata["filterBy"] = 0;

	

	
	
//end of Filters settings


	$tdatacalonppdb["tempattinggal"] = $fdata;
		$tdatacalonppdb[".searchableFields"][] = "tempattinggal";
//	modatransportasi
//	Custom field settings
	$fdata = array();
	$fdata["Index"] = 20;
	$fdata["strName"] = "modatransportasi";
	$fdata["GoodName"] = "modatransportasi";
	$fdata["ownerTable"] = "calonppdb";
	$fdata["Label"] = GetFieldLabel("calonppdb","modatransportasi");
	$fdata["FieldType"] = 200;

	
	
	
			

		$fdata["strField"] = "modatransportasi";

		$fdata["isSQLExpression"] = true;
	$fdata["FullName"] = "modatransportasi";

	
	
				$fdata["UploadFolder"] = "files";

//  Begin View Formats
	$fdata["ViewFormats"] = array();

	$vdata = array("ViewFormat" => "");

	
	
	
	
	
	
	
	
	
	
	
	
		$vdata["NeedEncode"] = true;

	
		$vdata["truncateText"] = true;
	$vdata["NumberOfChars"] = 80;

	$fdata["ViewFormats"]["view"] = $vdata;
//  End View Formats

//	Begin Edit Formats
	$fdata["EditFormats"] = array();

	$edata = array("EditFormat" => "Lookup wizard");

	
		$edata["weekdayMessage"] = array("message" => "", "messageType" => "Text");
	$edata["weekdays"] = "[]";


	
	

// Begin Lookup settings
		$edata["LookupType"] = 0;
			$edata["autoCompleteFieldsOnEdit"] = 0;
	$edata["autoCompleteFields"] = array();
		$edata["LCType"] = 0;

	
	
		$edata["LookupValues"] = array();
	$edata["LookupValues"][] = "Jalan Kaki";
	$edata["LookupValues"][] = "Diantar Orang Tua";
	$edata["LookupValues"][] = "Naik Angkutan Umum";
	$edata["LookupValues"][] = "Naik Ojek";

	
		$edata["SelectSize"] = 1;

// End Lookup Settings


		$edata["IsRequired"] = true;

	
	
	
			$edata["acceptFileTypes"] = ".+$";
		$edata["acceptFileTypesHtml"] = "";

		$edata["maxNumberOfFiles"] = 1;

	
	
	
	
	
	
		$edata["controlWidth"] = 200;

//	Begin validation
	$edata["validateAs"] = array();
	$edata["validateAs"]["basicValidate"] = array();
	$edata["validateAs"]["customMessages"] = array();
						$edata["validateAs"]["basicValidate"][] = "IsRequired";
		
	
	//	End validation

	
			
	
	
	
	$fdata["EditFormats"]["edit"] = $edata;
//	End Edit Formats


	$fdata["isSeparate"] = false;




// the field's search options settings
		$fdata["defaultSearchOption"] = "Equals";

			// the default search options list
				$fdata["searchOptionsList"] = array("Contains", "Equals", "Starts with", "More than", "Less than", "Between", "Empty", NOT_EMPTY);
// the end of search options settings


//Filters settings
	$fdata["filterTotals"] = 0;
		$fdata["filterMultiSelect"] = 0;
			$fdata["filterFormat"] = "Values list";
		$fdata["showCollapsed"] = false;

		$fdata["sortValueType"] = 0;
		$fdata["numberOfVisibleItems"] = 10;

		$fdata["filterBy"] = 0;

	

	
	
//end of Filters settings


	$tdatacalonppdb["modatransportasi"] = $fdata;
		$tdatacalonppdb[".searchableFields"][] = "modatransportasi";
//	anakke
//	Custom field settings
	$fdata = array();
	$fdata["Index"] = 21;
	$fdata["strName"] = "anakke";
	$fdata["GoodName"] = "anakke";
	$fdata["ownerTable"] = "calonppdb";
	$fdata["Label"] = GetFieldLabel("calonppdb","anakke");
	$fdata["FieldType"] = 200;

	
	
	
			

		$fdata["strField"] = "anakke";

		$fdata["isSQLExpression"] = true;
	$fdata["FullName"] = "anakke";

	
	
				$fdata["UploadFolder"] = "files";

//  Begin View Formats
	$fdata["ViewFormats"] = array();

	$vdata = array("ViewFormat" => "");

	
	
	
	
	
	
	
	
	
	
	
	
		$vdata["NeedEncode"] = true;

	
		$vdata["truncateText"] = true;
	$vdata["NumberOfChars"] = 80;

	$fdata["ViewFormats"]["view"] = $vdata;
//  End View Formats

//	Begin Edit Formats
	$fdata["EditFormats"] = array();

	$edata = array("EditFormat" => "Lookup wizard");

	
		$edata["weekdayMessage"] = array("message" => "", "messageType" => "Text");
	$edata["weekdays"] = "[]";


	
	

// Begin Lookup settings
		$edata["LookupType"] = 0;
			$edata["autoCompleteFieldsOnEdit"] = 0;
	$edata["autoCompleteFields"] = array();
		$edata["LCType"] = 0;

	
	
		$edata["LookupValues"] = array();
	$edata["LookupValues"][] = "1";
	$edata["LookupValues"][] = "2";
	$edata["LookupValues"][] = "3";
	$edata["LookupValues"][] = "4";
	$edata["LookupValues"][] = "5";
	$edata["LookupValues"][] = "6";

	
		$edata["SelectSize"] = 1;

// End Lookup Settings


		$edata["IsRequired"] = true;

	
	
	
			$edata["acceptFileTypes"] = ".+$";
		$edata["acceptFileTypesHtml"] = "";

		$edata["maxNumberOfFiles"] = 1;

	
	
	
	
	
	
		$edata["controlWidth"] = 200;

//	Begin validation
	$edata["validateAs"] = array();
	$edata["validateAs"]["basicValidate"] = array();
	$edata["validateAs"]["customMessages"] = array();
						$edata["validateAs"]["basicValidate"][] = "IsRequired";
		
	
	//	End validation

	
			
	
	
	
	$fdata["EditFormats"]["edit"] = $edata;
//	End Edit Formats


	$fdata["isSeparate"] = false;




// the field's search options settings
		$fdata["defaultSearchOption"] = "Equals";

			// the default search options list
				$fdata["searchOptionsList"] = array("Contains", "Equals", "Starts with", "More than", "Less than", "Between", "Empty", NOT_EMPTY);
// the end of search options settings


//Filters settings
	$fdata["filterTotals"] = 0;
		$fdata["filterMultiSelect"] = 0;
			$fdata["filterFormat"] = "Values list";
		$fdata["showCollapsed"] = false;

		$fdata["sortValueType"] = 0;
		$fdata["numberOfVisibleItems"] = 10;

		$fdata["filterBy"] = 0;

	

	
	
//end of Filters settings


	$tdatacalonppdb["anakke"] = $fdata;
		$tdatacalonppdb[".searchableFields"][] = "anakke";
//	namaayah
//	Custom field settings
	$fdata = array();
	$fdata["Index"] = 22;
	$fdata["strName"] = "namaayah";
	$fdata["GoodName"] = "namaayah";
	$fdata["ownerTable"] = "calonppdb";
	$fdata["Label"] = GetFieldLabel("calonppdb","namaayah");
	$fdata["FieldType"] = 200;

	
	
	
			

		$fdata["strField"] = "namaayah";

		$fdata["isSQLExpression"] = true;
	$fdata["FullName"] = "namaayah";

	
	
				$fdata["UploadFolder"] = "files";

//  Begin View Formats
	$fdata["ViewFormats"] = array();

	$vdata = array("ViewFormat" => "Custom");

	
	
	
	
	
	
	
	
	
	
	
	
	
	
		$vdata["truncateText"] = true;
	$vdata["NumberOfChars"] = 80;

	$fdata["ViewFormats"]["view"] = $vdata;
//  End View Formats

//	Begin Edit Formats
	$fdata["EditFormats"] = array();

	$edata = array("EditFormat" => "Text field");

	
		$edata["weekdayMessage"] = array("message" => "", "messageType" => "Text");
	$edata["weekdays"] = "[]";


	
	



		$edata["IsRequired"] = true;

	
	
	
			$edata["acceptFileTypes"] = ".+$";
		$edata["acceptFileTypesHtml"] = "";

		$edata["maxNumberOfFiles"] = 1;

	
	
	
	
			$edata["HTML5InuptType"] = "text";

		$edata["EditParams"] = "";
			$edata["EditParams"].= " maxlength=50";

		$edata["controlWidth"] = 200;

//	Begin validation
	$edata["validateAs"] = array();
	$edata["validateAs"]["basicValidate"] = array();
	$edata["validateAs"]["customMessages"] = array();
						$edata["validateAs"]["basicValidate"][] = "IsRequired";
		
	
	//	End validation

	
			
	
	
	
	$fdata["EditFormats"]["edit"] = $edata;
//	End Edit Formats


	$fdata["isSeparate"] = false;




// the field's search options settings
		$fdata["defaultSearchOption"] = "Contains";

			// the default search options list
				$fdata["searchOptionsList"] = array("Contains", "Equals", "Starts with", "More than", "Less than", "Between", "Empty", NOT_EMPTY);
// the end of search options settings


//Filters settings
	$fdata["filterTotals"] = 0;
		$fdata["filterMultiSelect"] = 0;
			$fdata["filterFormat"] = "Values list";
		$fdata["showCollapsed"] = false;

		$fdata["sortValueType"] = 0;
		$fdata["numberOfVisibleItems"] = 10;

		$fdata["filterBy"] = 0;

	

	
	
//end of Filters settings


	$tdatacalonppdb["namaayah"] = $fdata;
		$tdatacalonppdb[".searchableFields"][] = "namaayah";
//	nikayah
//	Custom field settings
	$fdata = array();
	$fdata["Index"] = 23;
	$fdata["strName"] = "nikayah";
	$fdata["GoodName"] = "nikayah";
	$fdata["ownerTable"] = "calonppdb";
	$fdata["Label"] = GetFieldLabel("calonppdb","nikayah");
	$fdata["FieldType"] = 200;

	
	
	
			

		$fdata["strField"] = "nikayah";

		$fdata["isSQLExpression"] = true;
	$fdata["FullName"] = "nikayah";

	
	
				$fdata["UploadFolder"] = "files";

//  Begin View Formats
	$fdata["ViewFormats"] = array();

	$vdata = array("ViewFormat" => "");

	
	
	
	
	
	
	
	
	
	
	
	
		$vdata["NeedEncode"] = true;

	
		$vdata["truncateText"] = true;
	$vdata["NumberOfChars"] = 80;

	$fdata["ViewFormats"]["view"] = $vdata;
//  End View Formats

//	Begin Edit Formats
	$fdata["EditFormats"] = array();

	$edata = array("EditFormat" => "Text field");

	
		$edata["weekdayMessage"] = array("message" => "", "messageType" => "Text");
	$edata["weekdays"] = "[]";


	
	



		$edata["IsRequired"] = true;

	
	
	
			$edata["acceptFileTypes"] = ".+$";
		$edata["acceptFileTypesHtml"] = "";

		$edata["maxNumberOfFiles"] = 1;

	
	
	
	
			$edata["HTML5InuptType"] = "tel";

		$edata["EditParams"] = "";
			$edata["EditParams"].= " maxlength=16";

		$edata["controlWidth"] = 200;

//	Begin validation
	$edata["validateAs"] = array();
	$edata["validateAs"]["basicValidate"] = array();
	$edata["validateAs"]["customMessages"] = array();
				$edata["validateAs"]["basicValidate"][] = getJsValidatorName("Number");
						$edata["validateAs"]["basicValidate"][] = "IsRequired";
		
	
	//	End validation

	
			
	
	
	
	$fdata["EditFormats"]["edit"] = $edata;
//	End Edit Formats


	$fdata["isSeparate"] = false;




// the field's search options settings
		$fdata["defaultSearchOption"] = "Contains";

			// the default search options list
				$fdata["searchOptionsList"] = array("Contains", "Equals", "Starts with", "More than", "Less than", "Between", "Empty", NOT_EMPTY);
// the end of search options settings


//Filters settings
	$fdata["filterTotals"] = 0;
		$fdata["filterMultiSelect"] = 0;
			$fdata["filterFormat"] = "Values list";
		$fdata["showCollapsed"] = false;

		$fdata["sortValueType"] = 0;
		$fdata["numberOfVisibleItems"] = 10;

		$fdata["filterBy"] = 0;

	

	
	
//end of Filters settings


	$tdatacalonppdb["nikayah"] = $fdata;
		$tdatacalonppdb[".searchableFields"][] = "nikayah";
//	tanggallahir2
//	Custom field settings
	$fdata = array();
	$fdata["Index"] = 24;
	$fdata["strName"] = "tanggallahir2";
	$fdata["GoodName"] = "tanggallahir2";
	$fdata["ownerTable"] = "calonppdb";
	$fdata["Label"] = GetFieldLabel("calonppdb","tanggallahir2");
	$fdata["FieldType"] = 200;

	
	
	
			

		$fdata["strField"] = "tanggallahir2";

		$fdata["isSQLExpression"] = true;
	$fdata["FullName"] = "tanggallahir2";

	
	
				$fdata["UploadFolder"] = "files";

//  Begin View Formats
	$fdata["ViewFormats"] = array();

	$vdata = array("ViewFormat" => "");

	
	
	
	
	
	
	
	
	
	
	
	
		$vdata["NeedEncode"] = true;

	
		$vdata["truncateText"] = true;
	$vdata["NumberOfChars"] = 80;

	$fdata["ViewFormats"]["view"] = $vdata;
//  End View Formats

//	Begin Edit Formats
	$fdata["EditFormats"] = array();

	$edata = array("EditFormat" => "Date");

	
		$edata["weekdayMessage"] = array("message" => "Invalid week day", "messageType" => "Text");
	$edata["weekdays"] = "[]";


	
	



		$edata["IsRequired"] = true;

	
	
	
			$edata["acceptFileTypes"] = ".+$";
		$edata["acceptFileTypesHtml"] = "";

		$edata["maxNumberOfFiles"] = 1;

	
	
		$edata["DateEditType"] = 12;
	$edata["InitialYearFactor"] = 100;
	$edata["LastYearFactor"] = 0;

	
	
	
		$edata["controlWidth"] = 200;

//	Begin validation
	$edata["validateAs"] = array();
	$edata["validateAs"]["basicValidate"] = array();
	$edata["validateAs"]["customMessages"] = array();
						$edata["validateAs"]["basicValidate"][] = "IsRequired";
		
	
	//	End validation

	
			
	
	
	
	$fdata["EditFormats"]["edit"] = $edata;
//	End Edit Formats


	$fdata["isSeparate"] = false;




// the field's search options settings
		$fdata["defaultSearchOption"] = "Equals";

			// the default search options list
				$fdata["searchOptionsList"] = array("Contains", "Equals", "Starts with", "More than", "Less than", "Between", "Empty", NOT_EMPTY);
// the end of search options settings


//Filters settings
	$fdata["filterTotals"] = 0;
		$fdata["filterMultiSelect"] = 0;
			$fdata["filterFormat"] = "Values list";
		$fdata["showCollapsed"] = false;

		$fdata["sortValueType"] = 0;
		$fdata["numberOfVisibleItems"] = 10;

		$fdata["filterBy"] = 0;

	

	
	
//end of Filters settings


	$tdatacalonppdb["tanggallahir2"] = $fdata;
		$tdatacalonppdb[".searchableFields"][] = "tanggallahir2";
//	pendidikan1
//	Custom field settings
	$fdata = array();
	$fdata["Index"] = 25;
	$fdata["strName"] = "pendidikan1";
	$fdata["GoodName"] = "pendidikan1";
	$fdata["ownerTable"] = "calonppdb";
	$fdata["Label"] = GetFieldLabel("calonppdb","pendidikan1");
	$fdata["FieldType"] = 200;

	
	
	
			

		$fdata["strField"] = "pendidikan1";

		$fdata["isSQLExpression"] = true;
	$fdata["FullName"] = "pendidikan1";

	
	
				$fdata["UploadFolder"] = "files";

//  Begin View Formats
	$fdata["ViewFormats"] = array();

	$vdata = array("ViewFormat" => "");

	
	
	
	
	
	
	
	
	
	
	
	
		$vdata["NeedEncode"] = true;

	
		$vdata["truncateText"] = true;
	$vdata["NumberOfChars"] = 80;

	$fdata["ViewFormats"]["view"] = $vdata;
//  End View Formats

//	Begin Edit Formats
	$fdata["EditFormats"] = array();

	$edata = array("EditFormat" => "Lookup wizard");

	
		$edata["weekdayMessage"] = array("message" => "", "messageType" => "Text");
	$edata["weekdays"] = "[]";


	
	

// Begin Lookup settings
				$edata["LookupType"] = 2;
	$edata["LookupTable"] = "pendidikan";
			$edata["autoCompleteFieldsOnEdit"] = 0;
	$edata["autoCompleteFields"] = array();
		$edata["LCType"] = 0;

	
		
	$edata["LinkField"] = "namatingkat";
	$edata["LinkFieldType"] = 0;
	$edata["DisplayField"] = "namatingkat";

	

	
	$edata["LookupOrderBy"] = "";

	
	
	
	

	
	
		$edata["SelectSize"] = 1;

// End Lookup Settings


		$edata["IsRequired"] = true;

	
	
	
			$edata["acceptFileTypes"] = ".+$";
		$edata["acceptFileTypesHtml"] = "";

		$edata["maxNumberOfFiles"] = 1;

	
	
	
	
	
	
		$edata["controlWidth"] = 200;

//	Begin validation
	$edata["validateAs"] = array();
	$edata["validateAs"]["basicValidate"] = array();
	$edata["validateAs"]["customMessages"] = array();
						$edata["validateAs"]["basicValidate"][] = "IsRequired";
		
	
	//	End validation

	
			
	
	
	
	$fdata["EditFormats"]["edit"] = $edata;
//	End Edit Formats


	$fdata["isSeparate"] = false;




// the field's search options settings
		$fdata["defaultSearchOption"] = "Equals";

			// the default search options list
				$fdata["searchOptionsList"] = array("Contains", "Equals", "Starts with", "More than", "Less than", "Between", "Empty", NOT_EMPTY);
// the end of search options settings


//Filters settings
	$fdata["filterTotals"] = 0;
		$fdata["filterMultiSelect"] = 0;
			$fdata["filterFormat"] = "Values list";
		$fdata["showCollapsed"] = false;

		$fdata["sortValueType"] = 0;
		$fdata["numberOfVisibleItems"] = 10;

		$fdata["filterBy"] = 0;

	

	
	
//end of Filters settings


	$tdatacalonppdb["pendidikan1"] = $fdata;
		$tdatacalonppdb[".searchableFields"][] = "pendidikan1";
//	pekerjaan1
//	Custom field settings
	$fdata = array();
	$fdata["Index"] = 26;
	$fdata["strName"] = "pekerjaan1";
	$fdata["GoodName"] = "pekerjaan1";
	$fdata["ownerTable"] = "calonppdb";
	$fdata["Label"] = GetFieldLabel("calonppdb","pekerjaan1");
	$fdata["FieldType"] = 200;

	
	
	
			

		$fdata["strField"] = "pekerjaan1";

		$fdata["isSQLExpression"] = true;
	$fdata["FullName"] = "pekerjaan1";

	
	
				$fdata["UploadFolder"] = "files";

//  Begin View Formats
	$fdata["ViewFormats"] = array();

	$vdata = array("ViewFormat" => "");

	
	
	
	
	
	
	
	
	
	
	
	
		$vdata["NeedEncode"] = true;

	
		$vdata["truncateText"] = true;
	$vdata["NumberOfChars"] = 80;

	$fdata["ViewFormats"]["view"] = $vdata;
//  End View Formats

//	Begin Edit Formats
	$fdata["EditFormats"] = array();

	$edata = array("EditFormat" => "Lookup wizard");

	
		$edata["weekdayMessage"] = array("message" => "", "messageType" => "Text");
	$edata["weekdays"] = "[]";


	
	

// Begin Lookup settings
				$edata["LookupType"] = 2;
	$edata["LookupTable"] = "pekerjaan";
			$edata["autoCompleteFieldsOnEdit"] = 0;
	$edata["autoCompleteFields"] = array();
		$edata["LCType"] = 0;

	
		
	$edata["LinkField"] = "namapekerjaan";
	$edata["LinkFieldType"] = 0;
	$edata["DisplayField"] = "namapekerjaan";

	

	
	$edata["LookupOrderBy"] = "";

	
	
	
	

	
	
		$edata["SelectSize"] = 1;

// End Lookup Settings


		$edata["IsRequired"] = true;

	
	
	
			$edata["acceptFileTypes"] = ".+$";
		$edata["acceptFileTypesHtml"] = "";

		$edata["maxNumberOfFiles"] = 1;

	
	
	
	
	
	
		$edata["controlWidth"] = 200;

//	Begin validation
	$edata["validateAs"] = array();
	$edata["validateAs"]["basicValidate"] = array();
	$edata["validateAs"]["customMessages"] = array();
						$edata["validateAs"]["basicValidate"][] = "IsRequired";
		
	
	//	End validation

	
			
	
	
	
	$fdata["EditFormats"]["edit"] = $edata;
//	End Edit Formats


	$fdata["isSeparate"] = false;




// the field's search options settings
		$fdata["defaultSearchOption"] = "Equals";

			// the default search options list
				$fdata["searchOptionsList"] = array("Contains", "Equals", "Starts with", "More than", "Less than", "Between", "Empty", NOT_EMPTY);
// the end of search options settings


//Filters settings
	$fdata["filterTotals"] = 0;
		$fdata["filterMultiSelect"] = 0;
			$fdata["filterFormat"] = "Values list";
		$fdata["showCollapsed"] = false;

		$fdata["sortValueType"] = 0;
		$fdata["numberOfVisibleItems"] = 10;

		$fdata["filterBy"] = 0;

	

	
	
//end of Filters settings


	$tdatacalonppdb["pekerjaan1"] = $fdata;
		$tdatacalonppdb[".searchableFields"][] = "pekerjaan1";
//	penghasilan1
//	Custom field settings
	$fdata = array();
	$fdata["Index"] = 27;
	$fdata["strName"] = "penghasilan1";
	$fdata["GoodName"] = "penghasilan1";
	$fdata["ownerTable"] = "calonppdb";
	$fdata["Label"] = GetFieldLabel("calonppdb","penghasilan1");
	$fdata["FieldType"] = 200;

	
	
	
			

		$fdata["strField"] = "penghasilan1";

		$fdata["isSQLExpression"] = true;
	$fdata["FullName"] = "penghasilan1";

	
	
				$fdata["UploadFolder"] = "files";

//  Begin View Formats
	$fdata["ViewFormats"] = array();

	$vdata = array("ViewFormat" => "");

	
	
	
	
	
	
	
	
	
	
	
	
		$vdata["NeedEncode"] = true;

	
		$vdata["truncateText"] = true;
	$vdata["NumberOfChars"] = 80;

	$fdata["ViewFormats"]["view"] = $vdata;
//  End View Formats

//	Begin Edit Formats
	$fdata["EditFormats"] = array();

	$edata = array("EditFormat" => "Lookup wizard");

	
		$edata["weekdayMessage"] = array("message" => "", "messageType" => "Text");
	$edata["weekdays"] = "[]";


	
	

// Begin Lookup settings
				$edata["LookupType"] = 2;
	$edata["LookupTable"] = "penghasilan";
			$edata["autoCompleteFieldsOnEdit"] = 0;
	$edata["autoCompleteFields"] = array();
		$edata["LCType"] = 0;

	
		
	$edata["LinkField"] = "namapenghasilan";
	$edata["LinkFieldType"] = 0;
	$edata["DisplayField"] = "namapenghasilan";

	

	
	$edata["LookupOrderBy"] = "";

	
	
	
	

	
	
		$edata["SelectSize"] = 1;

// End Lookup Settings


		$edata["IsRequired"] = true;

	
	
	
			$edata["acceptFileTypes"] = ".+$";
		$edata["acceptFileTypesHtml"] = "";

		$edata["maxNumberOfFiles"] = 1;

	
	
	
	
	
	
		$edata["controlWidth"] = 200;

//	Begin validation
	$edata["validateAs"] = array();
	$edata["validateAs"]["basicValidate"] = array();
	$edata["validateAs"]["customMessages"] = array();
						$edata["validateAs"]["basicValidate"][] = "IsRequired";
		
	
	//	End validation

	
			
	
	
	
	$fdata["EditFormats"]["edit"] = $edata;
//	End Edit Formats


	$fdata["isSeparate"] = false;




// the field's search options settings
		$fdata["defaultSearchOption"] = "Equals";

			// the default search options list
				$fdata["searchOptionsList"] = array("Contains", "Equals", "Starts with", "More than", "Less than", "Between", "Empty", NOT_EMPTY);
// the end of search options settings


//Filters settings
	$fdata["filterTotals"] = 0;
		$fdata["filterMultiSelect"] = 0;
			$fdata["filterFormat"] = "Values list";
		$fdata["showCollapsed"] = false;

		$fdata["sortValueType"] = 0;
		$fdata["numberOfVisibleItems"] = 10;

		$fdata["filterBy"] = 0;

	

	
	
//end of Filters settings


	$tdatacalonppdb["penghasilan1"] = $fdata;
		$tdatacalonppdb[".searchableFields"][] = "penghasilan1";
//	statusayah
//	Custom field settings
	$fdata = array();
	$fdata["Index"] = 28;
	$fdata["strName"] = "statusayah";
	$fdata["GoodName"] = "statusayah";
	$fdata["ownerTable"] = "calonppdb";
	$fdata["Label"] = GetFieldLabel("calonppdb","statusayah");
	$fdata["FieldType"] = 200;

	
	
	
			

		$fdata["strField"] = "statusayah";

		$fdata["isSQLExpression"] = true;
	$fdata["FullName"] = "statusayah";

	
	
				$fdata["UploadFolder"] = "files";

//  Begin View Formats
	$fdata["ViewFormats"] = array();

	$vdata = array("ViewFormat" => "");

	
	
	
	
	
	
	
	
	
	
	
	
		$vdata["NeedEncode"] = true;

	
		$vdata["truncateText"] = true;
	$vdata["NumberOfChars"] = 80;

	$fdata["ViewFormats"]["view"] = $vdata;
//  End View Formats

//	Begin Edit Formats
	$fdata["EditFormats"] = array();

	$edata = array("EditFormat" => "Lookup wizard");

	
		$edata["weekdayMessage"] = array("message" => "", "messageType" => "Text");
	$edata["weekdays"] = "[]";


	
	

// Begin Lookup settings
		$edata["LookupType"] = 0;
			$edata["autoCompleteFieldsOnEdit"] = 0;
	$edata["autoCompleteFields"] = array();
		$edata["LCType"] = 0;

	
	
		$edata["LookupValues"] = array();
	$edata["LookupValues"][] = "Masih Hidup";
	$edata["LookupValues"][] = "Sudah Meninggal";

	
		$edata["SelectSize"] = 1;

// End Lookup Settings


		$edata["IsRequired"] = true;

	
	
	
			$edata["acceptFileTypes"] = ".+$";
		$edata["acceptFileTypesHtml"] = "";

		$edata["maxNumberOfFiles"] = 1;

	
	
	
	
	
	
		$edata["controlWidth"] = 200;

//	Begin validation
	$edata["validateAs"] = array();
	$edata["validateAs"]["basicValidate"] = array();
	$edata["validateAs"]["customMessages"] = array();
						$edata["validateAs"]["basicValidate"][] = "IsRequired";
		
	
	//	End validation

	
			
	
	
	
	$fdata["EditFormats"]["edit"] = $edata;
//	End Edit Formats


	$fdata["isSeparate"] = false;




// the field's search options settings
		$fdata["defaultSearchOption"] = "Equals";

			// the default search options list
				$fdata["searchOptionsList"] = array("Contains", "Equals", "Starts with", "More than", "Less than", "Between", "Empty", NOT_EMPTY);
// the end of search options settings


//Filters settings
	$fdata["filterTotals"] = 0;
		$fdata["filterMultiSelect"] = 0;
			$fdata["filterFormat"] = "Values list";
		$fdata["showCollapsed"] = false;

		$fdata["sortValueType"] = 0;
		$fdata["numberOfVisibleItems"] = 10;

		$fdata["filterBy"] = 0;

	

	
	
//end of Filters settings


	$tdatacalonppdb["statusayah"] = $fdata;
		$tdatacalonppdb[".searchableFields"][] = "statusayah";
//	statusibu
//	Custom field settings
	$fdata = array();
	$fdata["Index"] = 29;
	$fdata["strName"] = "statusibu";
	$fdata["GoodName"] = "statusibu";
	$fdata["ownerTable"] = "calonppdb";
	$fdata["Label"] = GetFieldLabel("calonppdb","statusibu");
	$fdata["FieldType"] = 200;

	
	
	
			

		$fdata["strField"] = "statusibu";

		$fdata["isSQLExpression"] = true;
	$fdata["FullName"] = "statusibu";

	
	
				$fdata["UploadFolder"] = "files";

//  Begin View Formats
	$fdata["ViewFormats"] = array();

	$vdata = array("ViewFormat" => "");

	
	
	
	
	
	
	
	
	
	
	
	
		$vdata["NeedEncode"] = true;

	
		$vdata["truncateText"] = true;
	$vdata["NumberOfChars"] = 80;

	$fdata["ViewFormats"]["view"] = $vdata;
//  End View Formats

//	Begin Edit Formats
	$fdata["EditFormats"] = array();

	$edata = array("EditFormat" => "Lookup wizard");

	
		$edata["weekdayMessage"] = array("message" => "", "messageType" => "Text");
	$edata["weekdays"] = "[]";


	
	

// Begin Lookup settings
		$edata["LookupType"] = 0;
			$edata["autoCompleteFieldsOnEdit"] = 0;
	$edata["autoCompleteFields"] = array();
		$edata["LCType"] = 0;

	
	
		$edata["LookupValues"] = array();
	$edata["LookupValues"][] = "Masih Hidup";
	$edata["LookupValues"][] = "Sudah Meninggal";

	
		$edata["SelectSize"] = 1;

// End Lookup Settings


		$edata["IsRequired"] = true;

	
	
	
			$edata["acceptFileTypes"] = ".+$";
		$edata["acceptFileTypesHtml"] = "";

		$edata["maxNumberOfFiles"] = 1;

	
	
	
	
	
	
		$edata["controlWidth"] = 200;

//	Begin validation
	$edata["validateAs"] = array();
	$edata["validateAs"]["basicValidate"] = array();
	$edata["validateAs"]["customMessages"] = array();
						$edata["validateAs"]["basicValidate"][] = "IsRequired";
		
	
	//	End validation

	
			
	
	
	
	$fdata["EditFormats"]["edit"] = $edata;
//	End Edit Formats


	$fdata["isSeparate"] = false;




// the field's search options settings
		$fdata["defaultSearchOption"] = "Equals";

			// the default search options list
				$fdata["searchOptionsList"] = array("Contains", "Equals", "Starts with", "More than", "Less than", "Between", "Empty", NOT_EMPTY);
// the end of search options settings


//Filters settings
	$fdata["filterTotals"] = 0;
		$fdata["filterMultiSelect"] = 0;
			$fdata["filterFormat"] = "Values list";
		$fdata["showCollapsed"] = false;

		$fdata["sortValueType"] = 0;
		$fdata["numberOfVisibleItems"] = 10;

		$fdata["filterBy"] = 0;

	

	
	
//end of Filters settings


	$tdatacalonppdb["statusibu"] = $fdata;
		$tdatacalonppdb[".searchableFields"][] = "statusibu";
//	nikibu
//	Custom field settings
	$fdata = array();
	$fdata["Index"] = 30;
	$fdata["strName"] = "nikibu";
	$fdata["GoodName"] = "nikibu";
	$fdata["ownerTable"] = "calonppdb";
	$fdata["Label"] = GetFieldLabel("calonppdb","nikibu");
	$fdata["FieldType"] = 200;

	
	
	
			

		$fdata["strField"] = "nikibu";

		$fdata["isSQLExpression"] = true;
	$fdata["FullName"] = "nikibu";

	
	
				$fdata["UploadFolder"] = "files";

//  Begin View Formats
	$fdata["ViewFormats"] = array();

	$vdata = array("ViewFormat" => "");

	
	
	
	
	
	
	
	
	
	
	
	
		$vdata["NeedEncode"] = true;

	
		$vdata["truncateText"] = true;
	$vdata["NumberOfChars"] = 80;

	$fdata["ViewFormats"]["view"] = $vdata;
//  End View Formats

//	Begin Edit Formats
	$fdata["EditFormats"] = array();

	$edata = array("EditFormat" => "Text field");

	
		$edata["weekdayMessage"] = array("message" => "", "messageType" => "Text");
	$edata["weekdays"] = "[]";


	
	



		$edata["IsRequired"] = true;

	
	
	
			$edata["acceptFileTypes"] = ".+$";
		$edata["acceptFileTypesHtml"] = "";

		$edata["maxNumberOfFiles"] = 1;

	
	
	
	
			$edata["HTML5InuptType"] = "tel";

		$edata["EditParams"] = "";
			$edata["EditParams"].= " maxlength=16";

		$edata["controlWidth"] = 200;

//	Begin validation
	$edata["validateAs"] = array();
	$edata["validateAs"]["basicValidate"] = array();
	$edata["validateAs"]["customMessages"] = array();
				$edata["validateAs"]["basicValidate"][] = getJsValidatorName("Number");
						$edata["validateAs"]["basicValidate"][] = "IsRequired";
		
	
	//	End validation

	
			
	
	
	
	$fdata["EditFormats"]["edit"] = $edata;
//	End Edit Formats


	$fdata["isSeparate"] = false;




// the field's search options settings
		$fdata["defaultSearchOption"] = "Contains";

			// the default search options list
				$fdata["searchOptionsList"] = array("Contains", "Equals", "Starts with", "More than", "Less than", "Between", "Empty", NOT_EMPTY);
// the end of search options settings


//Filters settings
	$fdata["filterTotals"] = 0;
		$fdata["filterMultiSelect"] = 0;
			$fdata["filterFormat"] = "Values list";
		$fdata["showCollapsed"] = false;

		$fdata["sortValueType"] = 0;
		$fdata["numberOfVisibleItems"] = 10;

		$fdata["filterBy"] = 0;

	

	
	
//end of Filters settings


	$tdatacalonppdb["nikibu"] = $fdata;
		$tdatacalonppdb[".searchableFields"][] = "nikibu";
//	tanggallahir3
//	Custom field settings
	$fdata = array();
	$fdata["Index"] = 31;
	$fdata["strName"] = "tanggallahir3";
	$fdata["GoodName"] = "tanggallahir3";
	$fdata["ownerTable"] = "calonppdb";
	$fdata["Label"] = GetFieldLabel("calonppdb","tanggallahir3");
	$fdata["FieldType"] = 200;

	
	
	
			

		$fdata["strField"] = "tanggallahir3";

		$fdata["isSQLExpression"] = true;
	$fdata["FullName"] = "tanggallahir3";

	
	
				$fdata["UploadFolder"] = "files";

//  Begin View Formats
	$fdata["ViewFormats"] = array();

	$vdata = array("ViewFormat" => "");

	
	
	
	
	
	
	
	
	
	
	
	
		$vdata["NeedEncode"] = true;

	
		$vdata["truncateText"] = true;
	$vdata["NumberOfChars"] = 80;

	$fdata["ViewFormats"]["view"] = $vdata;
//  End View Formats

//	Begin Edit Formats
	$fdata["EditFormats"] = array();

	$edata = array("EditFormat" => "Date");

	
		$edata["weekdayMessage"] = array("message" => "Invalid week day", "messageType" => "Text");
	$edata["weekdays"] = "[]";


	
	



		$edata["IsRequired"] = true;

	
	
	
			$edata["acceptFileTypes"] = ".+$";
		$edata["acceptFileTypesHtml"] = "";

		$edata["maxNumberOfFiles"] = 1;

	
	
		$edata["DateEditType"] = 12;
	$edata["InitialYearFactor"] = 100;
	$edata["LastYearFactor"] = 10;

	
	
	
		$edata["controlWidth"] = 200;

//	Begin validation
	$edata["validateAs"] = array();
	$edata["validateAs"]["basicValidate"] = array();
	$edata["validateAs"]["customMessages"] = array();
						$edata["validateAs"]["basicValidate"][] = "IsRequired";
		
	
	//	End validation

	
			
	
	
	
	$fdata["EditFormats"]["edit"] = $edata;
//	End Edit Formats


	$fdata["isSeparate"] = false;




// the field's search options settings
		$fdata["defaultSearchOption"] = "Equals";

			// the default search options list
				$fdata["searchOptionsList"] = array("Contains", "Equals", "Starts with", "More than", "Less than", "Between", "Empty", NOT_EMPTY);
// the end of search options settings


//Filters settings
	$fdata["filterTotals"] = 0;
		$fdata["filterMultiSelect"] = 0;
			$fdata["filterFormat"] = "Values list";
		$fdata["showCollapsed"] = false;

		$fdata["sortValueType"] = 0;
		$fdata["numberOfVisibleItems"] = 10;

		$fdata["filterBy"] = 0;

	

	
	
//end of Filters settings


	$tdatacalonppdb["tanggallahir3"] = $fdata;
		$tdatacalonppdb[".searchableFields"][] = "tanggallahir3";
//	pendidikan2
//	Custom field settings
	$fdata = array();
	$fdata["Index"] = 32;
	$fdata["strName"] = "pendidikan2";
	$fdata["GoodName"] = "pendidikan2";
	$fdata["ownerTable"] = "calonppdb";
	$fdata["Label"] = GetFieldLabel("calonppdb","pendidikan2");
	$fdata["FieldType"] = 200;

	
	
	
			

		$fdata["strField"] = "pendidikan2";

		$fdata["isSQLExpression"] = true;
	$fdata["FullName"] = "pendidikan2";

	
	
				$fdata["UploadFolder"] = "files";

//  Begin View Formats
	$fdata["ViewFormats"] = array();

	$vdata = array("ViewFormat" => "");

	
	
	
	
	
	
	
	
	
	
	
	
		$vdata["NeedEncode"] = true;

	
		$vdata["truncateText"] = true;
	$vdata["NumberOfChars"] = 80;

	$fdata["ViewFormats"]["view"] = $vdata;
//  End View Formats

//	Begin Edit Formats
	$fdata["EditFormats"] = array();

	$edata = array("EditFormat" => "Lookup wizard");

	
		$edata["weekdayMessage"] = array("message" => "", "messageType" => "Text");
	$edata["weekdays"] = "[]";


	
	

// Begin Lookup settings
				$edata["LookupType"] = 2;
	$edata["LookupTable"] = "pendidikan";
			$edata["autoCompleteFieldsOnEdit"] = 0;
	$edata["autoCompleteFields"] = array();
		$edata["LCType"] = 0;

	
		
	$edata["LinkField"] = "namatingkat";
	$edata["LinkFieldType"] = 0;
	$edata["DisplayField"] = "namatingkat";

	

	
	$edata["LookupOrderBy"] = "";

	
	
	
	

	
	
		$edata["SelectSize"] = 1;

// End Lookup Settings


		$edata["IsRequired"] = true;

	
	
	
			$edata["acceptFileTypes"] = ".+$";
		$edata["acceptFileTypesHtml"] = "";

		$edata["maxNumberOfFiles"] = 1;

	
	
	
	
	
	
		$edata["controlWidth"] = 200;

//	Begin validation
	$edata["validateAs"] = array();
	$edata["validateAs"]["basicValidate"] = array();
	$edata["validateAs"]["customMessages"] = array();
						$edata["validateAs"]["basicValidate"][] = "IsRequired";
		
	
	//	End validation

	
			
	
	
	
	$fdata["EditFormats"]["edit"] = $edata;
//	End Edit Formats


	$fdata["isSeparate"] = false;




// the field's search options settings
		$fdata["defaultSearchOption"] = "Equals";

			// the default search options list
				$fdata["searchOptionsList"] = array("Contains", "Equals", "Starts with", "More than", "Less than", "Between", "Empty", NOT_EMPTY);
// the end of search options settings


//Filters settings
	$fdata["filterTotals"] = 0;
		$fdata["filterMultiSelect"] = 0;
			$fdata["filterFormat"] = "Values list";
		$fdata["showCollapsed"] = false;

		$fdata["sortValueType"] = 0;
		$fdata["numberOfVisibleItems"] = 10;

		$fdata["filterBy"] = 0;

	

	
	
//end of Filters settings


	$tdatacalonppdb["pendidikan2"] = $fdata;
		$tdatacalonppdb[".searchableFields"][] = "pendidikan2";
//	pekerjaan2
//	Custom field settings
	$fdata = array();
	$fdata["Index"] = 33;
	$fdata["strName"] = "pekerjaan2";
	$fdata["GoodName"] = "pekerjaan2";
	$fdata["ownerTable"] = "calonppdb";
	$fdata["Label"] = GetFieldLabel("calonppdb","pekerjaan2");
	$fdata["FieldType"] = 200;

	
	
	
			

		$fdata["strField"] = "pekerjaan2";

		$fdata["isSQLExpression"] = true;
	$fdata["FullName"] = "pekerjaan2";

	
	
				$fdata["UploadFolder"] = "files";

//  Begin View Formats
	$fdata["ViewFormats"] = array();

	$vdata = array("ViewFormat" => "");

	
	
	
	
	
	
	
	
	
	
	
	
		$vdata["NeedEncode"] = true;

	
		$vdata["truncateText"] = true;
	$vdata["NumberOfChars"] = 80;

	$fdata["ViewFormats"]["view"] = $vdata;
//  End View Formats

//	Begin Edit Formats
	$fdata["EditFormats"] = array();

	$edata = array("EditFormat" => "Lookup wizard");

	
		$edata["weekdayMessage"] = array("message" => "", "messageType" => "Text");
	$edata["weekdays"] = "[]";


	
	

// Begin Lookup settings
				$edata["LookupType"] = 2;
	$edata["LookupTable"] = "pekerjaan";
			$edata["autoCompleteFieldsOnEdit"] = 0;
	$edata["autoCompleteFields"] = array();
		$edata["LCType"] = 0;

	
		
	$edata["LinkField"] = "namapekerjaan";
	$edata["LinkFieldType"] = 0;
	$edata["DisplayField"] = "namapekerjaan";

	

	
	$edata["LookupOrderBy"] = "";

	
	
	
	

	
	
		$edata["SelectSize"] = 1;

// End Lookup Settings


		$edata["IsRequired"] = true;

	
	
	
			$edata["acceptFileTypes"] = ".+$";
		$edata["acceptFileTypesHtml"] = "";

		$edata["maxNumberOfFiles"] = 1;

	
	
	
	
	
	
		$edata["controlWidth"] = 200;

//	Begin validation
	$edata["validateAs"] = array();
	$edata["validateAs"]["basicValidate"] = array();
	$edata["validateAs"]["customMessages"] = array();
						$edata["validateAs"]["basicValidate"][] = "IsRequired";
		
	
	//	End validation

	
			
	
	
	
	$fdata["EditFormats"]["edit"] = $edata;
//	End Edit Formats


	$fdata["isSeparate"] = false;




// the field's search options settings
		$fdata["defaultSearchOption"] = "Equals";

			// the default search options list
				$fdata["searchOptionsList"] = array("Contains", "Equals", "Starts with", "More than", "Less than", "Between", "Empty", NOT_EMPTY);
// the end of search options settings


//Filters settings
	$fdata["filterTotals"] = 0;
		$fdata["filterMultiSelect"] = 0;
			$fdata["filterFormat"] = "Values list";
		$fdata["showCollapsed"] = false;

		$fdata["sortValueType"] = 0;
		$fdata["numberOfVisibleItems"] = 10;

		$fdata["filterBy"] = 0;

	

	
	
//end of Filters settings


	$tdatacalonppdb["pekerjaan2"] = $fdata;
		$tdatacalonppdb[".searchableFields"][] = "pekerjaan2";
//	penghasilan2
//	Custom field settings
	$fdata = array();
	$fdata["Index"] = 34;
	$fdata["strName"] = "penghasilan2";
	$fdata["GoodName"] = "penghasilan2";
	$fdata["ownerTable"] = "calonppdb";
	$fdata["Label"] = GetFieldLabel("calonppdb","penghasilan2");
	$fdata["FieldType"] = 200;

	
	
	
			

		$fdata["strField"] = "penghasilan2";

		$fdata["isSQLExpression"] = true;
	$fdata["FullName"] = "penghasilan2";

	
	
				$fdata["UploadFolder"] = "files";

//  Begin View Formats
	$fdata["ViewFormats"] = array();

	$vdata = array("ViewFormat" => "");

	
	
	
	
	
	
	
	
	
	
	
	
		$vdata["NeedEncode"] = true;

	
		$vdata["truncateText"] = true;
	$vdata["NumberOfChars"] = 80;

	$fdata["ViewFormats"]["view"] = $vdata;
//  End View Formats

//	Begin Edit Formats
	$fdata["EditFormats"] = array();

	$edata = array("EditFormat" => "Lookup wizard");

	
		$edata["weekdayMessage"] = array("message" => "", "messageType" => "Text");
	$edata["weekdays"] = "[]";


	
	

// Begin Lookup settings
				$edata["LookupType"] = 2;
	$edata["LookupTable"] = "penghasilan";
			$edata["autoCompleteFieldsOnEdit"] = 0;
	$edata["autoCompleteFields"] = array();
		$edata["LCType"] = 0;

	
		
	$edata["LinkField"] = "namapenghasilan";
	$edata["LinkFieldType"] = 0;
	$edata["DisplayField"] = "namapenghasilan";

	

	
	$edata["LookupOrderBy"] = "";

	
	
	
	

	
	
		$edata["SelectSize"] = 1;

// End Lookup Settings


		$edata["IsRequired"] = true;

	
	
	
			$edata["acceptFileTypes"] = ".+$";
		$edata["acceptFileTypesHtml"] = "";

		$edata["maxNumberOfFiles"] = 1;

	
	
	
	
	
	
		$edata["controlWidth"] = 200;

//	Begin validation
	$edata["validateAs"] = array();
	$edata["validateAs"]["basicValidate"] = array();
	$edata["validateAs"]["customMessages"] = array();
						$edata["validateAs"]["basicValidate"][] = "IsRequired";
		
	
	//	End validation

	
			
	
	
	
	$fdata["EditFormats"]["edit"] = $edata;
//	End Edit Formats


	$fdata["isSeparate"] = false;




// the field's search options settings
		$fdata["defaultSearchOption"] = "Equals";

			// the default search options list
				$fdata["searchOptionsList"] = array("Contains", "Equals", "Starts with", "More than", "Less than", "Between", "Empty", NOT_EMPTY);
// the end of search options settings


//Filters settings
	$fdata["filterTotals"] = 0;
		$fdata["filterMultiSelect"] = 0;
			$fdata["filterFormat"] = "Values list";
		$fdata["showCollapsed"] = false;

		$fdata["sortValueType"] = 0;
		$fdata["numberOfVisibleItems"] = 10;

		$fdata["filterBy"] = 0;

	

	
	
//end of Filters settings


	$tdatacalonppdb["penghasilan2"] = $fdata;
		$tdatacalonppdb[".searchableFields"][] = "penghasilan2";
//	namawali
//	Custom field settings
	$fdata = array();
	$fdata["Index"] = 35;
	$fdata["strName"] = "namawali";
	$fdata["GoodName"] = "namawali";
	$fdata["ownerTable"] = "calonppdb";
	$fdata["Label"] = GetFieldLabel("calonppdb","namawali");
	$fdata["FieldType"] = 200;

	
	
	
			

		$fdata["strField"] = "namawali";

		$fdata["isSQLExpression"] = true;
	$fdata["FullName"] = "namawali";

	
	
				$fdata["UploadFolder"] = "files";

//  Begin View Formats
	$fdata["ViewFormats"] = array();

	$vdata = array("ViewFormat" => "");

	
	
	
	
	
	
	
	
	
	
	
	
		$vdata["NeedEncode"] = true;

	
		$vdata["truncateText"] = true;
	$vdata["NumberOfChars"] = 80;

	$fdata["ViewFormats"]["view"] = $vdata;
//  End View Formats

//	Begin Edit Formats
	$fdata["EditFormats"] = array();

	$edata = array("EditFormat" => "Text field");

	
		$edata["weekdayMessage"] = array("message" => "", "messageType" => "Text");
	$edata["weekdays"] = "[]";


	
	



	
	
	
	
			$edata["acceptFileTypes"] = ".+$";
		$edata["acceptFileTypesHtml"] = "";

		$edata["maxNumberOfFiles"] = 1;

	
	
	
	
			$edata["HTML5InuptType"] = "text";

		$edata["EditParams"] = "";
			$edata["EditParams"].= " maxlength=50";

		$edata["controlWidth"] = 200;

//	Begin validation
	$edata["validateAs"] = array();
	$edata["validateAs"]["basicValidate"] = array();
	$edata["validateAs"]["customMessages"] = array();
	
	
	//	End validation

	
			
	
	
	
	$fdata["EditFormats"]["edit"] = $edata;
//	End Edit Formats


	$fdata["isSeparate"] = false;




// the field's search options settings
		$fdata["defaultSearchOption"] = "Contains";

			// the default search options list
				$fdata["searchOptionsList"] = array("Contains", "Equals", "Starts with", "More than", "Less than", "Between", "Empty", NOT_EMPTY);
// the end of search options settings


//Filters settings
	$fdata["filterTotals"] = 0;
		$fdata["filterMultiSelect"] = 0;
			$fdata["filterFormat"] = "Values list";
		$fdata["showCollapsed"] = false;

		$fdata["sortValueType"] = 0;
		$fdata["numberOfVisibleItems"] = 10;

		$fdata["filterBy"] = 0;

	

	
	
//end of Filters settings


	$tdatacalonppdb["namawali"] = $fdata;
		$tdatacalonppdb[".searchableFields"][] = "namawali";
//	nikwali
//	Custom field settings
	$fdata = array();
	$fdata["Index"] = 36;
	$fdata["strName"] = "nikwali";
	$fdata["GoodName"] = "nikwali";
	$fdata["ownerTable"] = "calonppdb";
	$fdata["Label"] = GetFieldLabel("calonppdb","nikwali");
	$fdata["FieldType"] = 200;

	
	
	
			

		$fdata["strField"] = "nikwali";

		$fdata["isSQLExpression"] = true;
	$fdata["FullName"] = "nikwali";

	
	
				$fdata["UploadFolder"] = "files";

//  Begin View Formats
	$fdata["ViewFormats"] = array();

	$vdata = array("ViewFormat" => "");

	
	
	
	
	
	
	
	
	
	
	
	
		$vdata["NeedEncode"] = true;

	
		$vdata["truncateText"] = true;
	$vdata["NumberOfChars"] = 80;

	$fdata["ViewFormats"]["view"] = $vdata;
//  End View Formats

//	Begin Edit Formats
	$fdata["EditFormats"] = array();

	$edata = array("EditFormat" => "Text field");

	
		$edata["weekdayMessage"] = array("message" => "", "messageType" => "Text");
	$edata["weekdays"] = "[]";


	
	



	
	
	
	
			$edata["acceptFileTypes"] = ".+$";
		$edata["acceptFileTypesHtml"] = "";

		$edata["maxNumberOfFiles"] = 1;

	
	
	
	
			$edata["HTML5InuptType"] = "text";

		$edata["EditParams"] = "";
			$edata["EditParams"].= " maxlength=50";

		$edata["controlWidth"] = 200;

//	Begin validation
	$edata["validateAs"] = array();
	$edata["validateAs"]["basicValidate"] = array();
	$edata["validateAs"]["customMessages"] = array();
	
	
	//	End validation

	
			
	
	
	
	$fdata["EditFormats"]["edit"] = $edata;
//	End Edit Formats


	$fdata["isSeparate"] = false;




// the field's search options settings
		$fdata["defaultSearchOption"] = "Contains";

			// the default search options list
				$fdata["searchOptionsList"] = array("Contains", "Equals", "Starts with", "More than", "Less than", "Between", "Empty", NOT_EMPTY);
// the end of search options settings


//Filters settings
	$fdata["filterTotals"] = 0;
		$fdata["filterMultiSelect"] = 0;
			$fdata["filterFormat"] = "Values list";
		$fdata["showCollapsed"] = false;

		$fdata["sortValueType"] = 0;
		$fdata["numberOfVisibleItems"] = 10;

		$fdata["filterBy"] = 0;

	

	
	
//end of Filters settings


	$tdatacalonppdb["nikwali"] = $fdata;
		$tdatacalonppdb[".searchableFields"][] = "nikwali";
//	tanggallahir4
//	Custom field settings
	$fdata = array();
	$fdata["Index"] = 37;
	$fdata["strName"] = "tanggallahir4";
	$fdata["GoodName"] = "tanggallahir4";
	$fdata["ownerTable"] = "calonppdb";
	$fdata["Label"] = GetFieldLabel("calonppdb","tanggallahir4");
	$fdata["FieldType"] = 200;

	
	
	
			

		$fdata["strField"] = "tanggallahir4";

		$fdata["isSQLExpression"] = true;
	$fdata["FullName"] = "tanggallahir4";

	
	
				$fdata["UploadFolder"] = "files";

//  Begin View Formats
	$fdata["ViewFormats"] = array();

	$vdata = array("ViewFormat" => "");

	
	
	
	
	
	
	
	
	
	
	
	
		$vdata["NeedEncode"] = true;

	
		$vdata["truncateText"] = true;
	$vdata["NumberOfChars"] = 80;

	$fdata["ViewFormats"]["view"] = $vdata;
//  End View Formats

//	Begin Edit Formats
	$fdata["EditFormats"] = array();

	$edata = array("EditFormat" => "Date");

	
		$edata["weekdayMessage"] = array("message" => "Invalid week day", "messageType" => "Text");
	$edata["weekdays"] = "[]";


	
	



	
	
	
	
			$edata["acceptFileTypes"] = ".+$";
		$edata["acceptFileTypesHtml"] = "";

		$edata["maxNumberOfFiles"] = 1;

	
	
		$edata["DateEditType"] = 12;
	$edata["InitialYearFactor"] = 100;
	$edata["LastYearFactor"] = 10;

	
	
	
		$edata["controlWidth"] = 200;

//	Begin validation
	$edata["validateAs"] = array();
	$edata["validateAs"]["basicValidate"] = array();
	$edata["validateAs"]["customMessages"] = array();
	
	
	//	End validation

	
			
	
	
	
	$fdata["EditFormats"]["edit"] = $edata;
//	End Edit Formats


	$fdata["isSeparate"] = false;




// the field's search options settings
		$fdata["defaultSearchOption"] = "Equals";

			// the default search options list
				$fdata["searchOptionsList"] = array("Contains", "Equals", "Starts with", "More than", "Less than", "Between", "Empty", NOT_EMPTY);
// the end of search options settings


//Filters settings
	$fdata["filterTotals"] = 0;
		$fdata["filterMultiSelect"] = 0;
			$fdata["filterFormat"] = "Values list";
		$fdata["showCollapsed"] = false;

		$fdata["sortValueType"] = 0;
		$fdata["numberOfVisibleItems"] = 10;

		$fdata["filterBy"] = 0;

	

	
	
//end of Filters settings


	$tdatacalonppdb["tanggallahir4"] = $fdata;
		$tdatacalonppdb[".searchableFields"][] = "tanggallahir4";
//	pendidikan3
//	Custom field settings
	$fdata = array();
	$fdata["Index"] = 38;
	$fdata["strName"] = "pendidikan3";
	$fdata["GoodName"] = "pendidikan3";
	$fdata["ownerTable"] = "calonppdb";
	$fdata["Label"] = GetFieldLabel("calonppdb","pendidikan3");
	$fdata["FieldType"] = 200;

	
	
	
			

		$fdata["strField"] = "pendidikan3";

		$fdata["isSQLExpression"] = true;
	$fdata["FullName"] = "pendidikan3";

	
	
				$fdata["UploadFolder"] = "files";

//  Begin View Formats
	$fdata["ViewFormats"] = array();

	$vdata = array("ViewFormat" => "");

	
	
	
	
	
	
	
	
	
	
	
	
		$vdata["NeedEncode"] = true;

	
		$vdata["truncateText"] = true;
	$vdata["NumberOfChars"] = 80;

	$fdata["ViewFormats"]["view"] = $vdata;
//  End View Formats

//	Begin Edit Formats
	$fdata["EditFormats"] = array();

	$edata = array("EditFormat" => "Lookup wizard");

	
		$edata["weekdayMessage"] = array("message" => "", "messageType" => "Text");
	$edata["weekdays"] = "[]";


	
	

// Begin Lookup settings
				$edata["LookupType"] = 2;
	$edata["LookupTable"] = "pendidikan";
			$edata["autoCompleteFieldsOnEdit"] = 0;
	$edata["autoCompleteFields"] = array();
		$edata["LCType"] = 0;

	
		
	$edata["LinkField"] = "namatingkat";
	$edata["LinkFieldType"] = 0;
	$edata["DisplayField"] = "namatingkat";

	

	
	$edata["LookupOrderBy"] = "";

	
	
	
	

	
	
		$edata["SelectSize"] = 1;

// End Lookup Settings


	
	
	
	
			$edata["acceptFileTypes"] = ".+$";
		$edata["acceptFileTypesHtml"] = "";

		$edata["maxNumberOfFiles"] = 1;

	
	
	
	
	
	
		$edata["controlWidth"] = 200;

//	Begin validation
	$edata["validateAs"] = array();
	$edata["validateAs"]["basicValidate"] = array();
	$edata["validateAs"]["customMessages"] = array();
	
	
	//	End validation

	
			
	
	
	
	$fdata["EditFormats"]["edit"] = $edata;
//	End Edit Formats


	$fdata["isSeparate"] = false;




// the field's search options settings
		$fdata["defaultSearchOption"] = "Equals";

			// the default search options list
				$fdata["searchOptionsList"] = array("Contains", "Equals", "Starts with", "More than", "Less than", "Between", "Empty", NOT_EMPTY);
// the end of search options settings


//Filters settings
	$fdata["filterTotals"] = 0;
		$fdata["filterMultiSelect"] = 0;
			$fdata["filterFormat"] = "Values list";
		$fdata["showCollapsed"] = false;

		$fdata["sortValueType"] = 0;
		$fdata["numberOfVisibleItems"] = 10;

		$fdata["filterBy"] = 0;

	

	
	
//end of Filters settings


	$tdatacalonppdb["pendidikan3"] = $fdata;
		$tdatacalonppdb[".searchableFields"][] = "pendidikan3";
//	pekerjaan3
//	Custom field settings
	$fdata = array();
	$fdata["Index"] = 39;
	$fdata["strName"] = "pekerjaan3";
	$fdata["GoodName"] = "pekerjaan3";
	$fdata["ownerTable"] = "calonppdb";
	$fdata["Label"] = GetFieldLabel("calonppdb","pekerjaan3");
	$fdata["FieldType"] = 200;

	
	
	
			

		$fdata["strField"] = "pekerjaan3";

		$fdata["isSQLExpression"] = true;
	$fdata["FullName"] = "pekerjaan3";

	
	
				$fdata["UploadFolder"] = "files";

//  Begin View Formats
	$fdata["ViewFormats"] = array();

	$vdata = array("ViewFormat" => "");

	
	
	
	
	
	
	
	
	
	
	
	
		$vdata["NeedEncode"] = true;

	
		$vdata["truncateText"] = true;
	$vdata["NumberOfChars"] = 80;

	$fdata["ViewFormats"]["view"] = $vdata;
//  End View Formats

//	Begin Edit Formats
	$fdata["EditFormats"] = array();

	$edata = array("EditFormat" => "Lookup wizard");

	
		$edata["weekdayMessage"] = array("message" => "", "messageType" => "Text");
	$edata["weekdays"] = "[]";


	
	

// Begin Lookup settings
				$edata["LookupType"] = 2;
	$edata["LookupTable"] = "pekerjaan";
			$edata["autoCompleteFieldsOnEdit"] = 0;
	$edata["autoCompleteFields"] = array();
		$edata["LCType"] = 0;

	
		
	$edata["LinkField"] = "namapekerjaan";
	$edata["LinkFieldType"] = 0;
	$edata["DisplayField"] = "namapekerjaan";

	

	
	$edata["LookupOrderBy"] = "";

	
	
	
	

	
	
		$edata["SelectSize"] = 1;

// End Lookup Settings


	
	
	
	
			$edata["acceptFileTypes"] = ".+$";
		$edata["acceptFileTypesHtml"] = "";

		$edata["maxNumberOfFiles"] = 1;

	
	
	
	
	
	
		$edata["controlWidth"] = 200;

//	Begin validation
	$edata["validateAs"] = array();
	$edata["validateAs"]["basicValidate"] = array();
	$edata["validateAs"]["customMessages"] = array();
	
	
	//	End validation

	
			
	
	
	
	$fdata["EditFormats"]["edit"] = $edata;
//	End Edit Formats


	$fdata["isSeparate"] = false;




// the field's search options settings
		$fdata["defaultSearchOption"] = "Equals";

			// the default search options list
				$fdata["searchOptionsList"] = array("Contains", "Equals", "Starts with", "More than", "Less than", "Between", "Empty", NOT_EMPTY);
// the end of search options settings


//Filters settings
	$fdata["filterTotals"] = 0;
		$fdata["filterMultiSelect"] = 0;
			$fdata["filterFormat"] = "Values list";
		$fdata["showCollapsed"] = false;

		$fdata["sortValueType"] = 0;
		$fdata["numberOfVisibleItems"] = 10;

		$fdata["filterBy"] = 0;

	

	
	
//end of Filters settings


	$tdatacalonppdb["pekerjaan3"] = $fdata;
		$tdatacalonppdb[".searchableFields"][] = "pekerjaan3";
//	penghasilan3
//	Custom field settings
	$fdata = array();
	$fdata["Index"] = 40;
	$fdata["strName"] = "penghasilan3";
	$fdata["GoodName"] = "penghasilan3";
	$fdata["ownerTable"] = "calonppdb";
	$fdata["Label"] = GetFieldLabel("calonppdb","penghasilan3");
	$fdata["FieldType"] = 200;

	
	
	
			

		$fdata["strField"] = "penghasilan3";

		$fdata["isSQLExpression"] = true;
	$fdata["FullName"] = "penghasilan3";

	
	
				$fdata["UploadFolder"] = "files";

//  Begin View Formats
	$fdata["ViewFormats"] = array();

	$vdata = array("ViewFormat" => "");

	
	
	
	
	
	
	
	
	
	
	
	
		$vdata["NeedEncode"] = true;

	
		$vdata["truncateText"] = true;
	$vdata["NumberOfChars"] = 80;

	$fdata["ViewFormats"]["view"] = $vdata;
//  End View Formats

//	Begin Edit Formats
	$fdata["EditFormats"] = array();

	$edata = array("EditFormat" => "Lookup wizard");

	
		$edata["weekdayMessage"] = array("message" => "", "messageType" => "Text");
	$edata["weekdays"] = "[]";


	
	

// Begin Lookup settings
				$edata["LookupType"] = 2;
	$edata["LookupTable"] = "penghasilan";
			$edata["autoCompleteFieldsOnEdit"] = 0;
	$edata["autoCompleteFields"] = array();
		$edata["LCType"] = 0;

	
		
	$edata["LinkField"] = "namapenghasilan";
	$edata["LinkFieldType"] = 0;
	$edata["DisplayField"] = "namapenghasilan";

	

	
	$edata["LookupOrderBy"] = "";

	
	
	
	

	
	
		$edata["SelectSize"] = 1;

// End Lookup Settings


	
	
	
	
			$edata["acceptFileTypes"] = ".+$";
		$edata["acceptFileTypesHtml"] = "";

		$edata["maxNumberOfFiles"] = 1;

	
	
	
	
	
	
		$edata["controlWidth"] = 200;

//	Begin validation
	$edata["validateAs"] = array();
	$edata["validateAs"]["basicValidate"] = array();
	$edata["validateAs"]["customMessages"] = array();
	
	
	//	End validation

	
			
	
	
	
	$fdata["EditFormats"]["edit"] = $edata;
//	End Edit Formats


	$fdata["isSeparate"] = false;




// the field's search options settings
		$fdata["defaultSearchOption"] = "Equals";

			// the default search options list
				$fdata["searchOptionsList"] = array("Contains", "Equals", "Starts with", "More than", "Less than", "Between", "Empty", NOT_EMPTY);
// the end of search options settings


//Filters settings
	$fdata["filterTotals"] = 0;
		$fdata["filterMultiSelect"] = 0;
			$fdata["filterFormat"] = "Values list";
		$fdata["showCollapsed"] = false;

		$fdata["sortValueType"] = 0;
		$fdata["numberOfVisibleItems"] = 10;

		$fdata["filterBy"] = 0;

	

	
	
//end of Filters settings


	$tdatacalonppdb["penghasilan3"] = $fdata;
		$tdatacalonppdb[".searchableFields"][] = "penghasilan3";
//	kontak
//	Custom field settings
	$fdata = array();
	$fdata["Index"] = 41;
	$fdata["strName"] = "kontak";
	$fdata["GoodName"] = "kontak";
	$fdata["ownerTable"] = "calonppdb";
	$fdata["Label"] = GetFieldLabel("calonppdb","kontak");
	$fdata["FieldType"] = 200;

	
	
	
			

		$fdata["strField"] = "kontak";

		$fdata["isSQLExpression"] = true;
	$fdata["FullName"] = "kontak";

	
	
				$fdata["UploadFolder"] = "files";

//  Begin View Formats
	$fdata["ViewFormats"] = array();

	$vdata = array("ViewFormat" => "");

	
	
	
	
	
	
	
	
	
	
	
	
		$vdata["NeedEncode"] = true;

	
		$vdata["truncateText"] = true;
	$vdata["NumberOfChars"] = 80;

	$fdata["ViewFormats"]["view"] = $vdata;
//  End View Formats

//	Begin Edit Formats
	$fdata["EditFormats"] = array();

	$edata = array("EditFormat" => "Text field");

	
		$edata["weekdayMessage"] = array("message" => "", "messageType" => "Text");
	$edata["weekdays"] = "[]";


	
	



		$edata["IsRequired"] = true;

	
	
	
			$edata["acceptFileTypes"] = ".+$";
		$edata["acceptFileTypesHtml"] = "";

		$edata["maxNumberOfFiles"] = 1;

	
	
	
	
			$edata["HTML5InuptType"] = "tel";

		$edata["EditParams"] = "";
			$edata["EditParams"].= " maxlength=14";

		$edata["controlWidth"] = 200;

//	Begin validation
	$edata["validateAs"] = array();
	$edata["validateAs"]["basicValidate"] = array();
	$edata["validateAs"]["customMessages"] = array();
				$edata["validateAs"]["basicValidate"][] = getJsValidatorName("Number");
						$edata["validateAs"]["basicValidate"][] = "IsRequired";
		
	
	//	End validation

	
			
	
	
	
	$fdata["EditFormats"]["edit"] = $edata;
//	End Edit Formats


	$fdata["isSeparate"] = false;




// the field's search options settings
		$fdata["defaultSearchOption"] = "Contains";

			// the default search options list
				$fdata["searchOptionsList"] = array("Contains", "Equals", "Starts with", "More than", "Less than", "Between", "Empty", NOT_EMPTY);
// the end of search options settings


//Filters settings
	$fdata["filterTotals"] = 0;
		$fdata["filterMultiSelect"] = 0;
			$fdata["filterFormat"] = "Values list";
		$fdata["showCollapsed"] = false;

		$fdata["sortValueType"] = 0;
		$fdata["numberOfVisibleItems"] = 10;

		$fdata["filterBy"] = 0;

	

	
	
//end of Filters settings


	$tdatacalonppdb["kontak"] = $fdata;
		$tdatacalonppdb[".searchableFields"][] = "kontak";
//	nomorwa
//	Custom field settings
	$fdata = array();
	$fdata["Index"] = 42;
	$fdata["strName"] = "nomorwa";
	$fdata["GoodName"] = "nomorwa";
	$fdata["ownerTable"] = "calonppdb";
	$fdata["Label"] = GetFieldLabel("calonppdb","nomorwa");
	$fdata["FieldType"] = 200;

	
	
	
			

		$fdata["strField"] = "nomorwa";

		$fdata["isSQLExpression"] = true;
	$fdata["FullName"] = "nomorwa";

	
	
				$fdata["UploadFolder"] = "files";

//  Begin View Formats
	$fdata["ViewFormats"] = array();

	$vdata = array("ViewFormat" => "");

	
	
	
	
	
	
	
	
	
	
	
	
		$vdata["NeedEncode"] = true;

	
		$vdata["truncateText"] = true;
	$vdata["NumberOfChars"] = 80;

	$fdata["ViewFormats"]["view"] = $vdata;
//  End View Formats

//	Begin Edit Formats
	$fdata["EditFormats"] = array();

	$edata = array("EditFormat" => "Text field");

	
		$edata["weekdayMessage"] = array("message" => "", "messageType" => "Text");
	$edata["weekdays"] = "[]";


	
	



		$edata["IsRequired"] = true;

	
	
	
			$edata["acceptFileTypes"] = ".+$";
		$edata["acceptFileTypesHtml"] = "";

		$edata["maxNumberOfFiles"] = 1;

	
	
	
	
			$edata["HTML5InuptType"] = "tel";

		$edata["EditParams"] = "";
			$edata["EditParams"].= " maxlength=50";

		$edata["controlWidth"] = 200;

//	Begin validation
	$edata["validateAs"] = array();
	$edata["validateAs"]["basicValidate"] = array();
	$edata["validateAs"]["customMessages"] = array();
				$edata["validateAs"]["basicValidate"][] = getJsValidatorName("Number");
						$edata["validateAs"]["basicValidate"][] = "IsRequired";
		
	
	//	End validation

	
			
	
	
	
	$fdata["EditFormats"]["edit"] = $edata;
//	End Edit Formats


	$fdata["isSeparate"] = false;




// the field's search options settings
		$fdata["defaultSearchOption"] = "Contains";

			// the default search options list
				$fdata["searchOptionsList"] = array("Contains", "Equals", "Starts with", "More than", "Less than", "Between", "Empty", NOT_EMPTY);
// the end of search options settings


//Filters settings
	$fdata["filterTotals"] = 0;
		$fdata["filterMultiSelect"] = 0;
			$fdata["filterFormat"] = "Values list";
		$fdata["showCollapsed"] = false;

		$fdata["sortValueType"] = 0;
		$fdata["numberOfVisibleItems"] = 10;

		$fdata["filterBy"] = 0;

	

	
	
//end of Filters settings


	$tdatacalonppdb["nomorwa"] = $fdata;
		$tdatacalonppdb[".searchableFields"][] = "nomorwa";
//	email
//	Custom field settings
	$fdata = array();
	$fdata["Index"] = 43;
	$fdata["strName"] = "email";
	$fdata["GoodName"] = "email";
	$fdata["ownerTable"] = "calonppdb";
	$fdata["Label"] = GetFieldLabel("calonppdb","email");
	$fdata["FieldType"] = 200;

	
	
	
			

		$fdata["strField"] = "email";

		$fdata["isSQLExpression"] = true;
	$fdata["FullName"] = "email";

	
	
				$fdata["UploadFolder"] = "files";

//  Begin View Formats
	$fdata["ViewFormats"] = array();

	$vdata = array("ViewFormat" => "");

	
	
	
	
	
	
	
	
	
	
	
	
		$vdata["NeedEncode"] = true;

	
		$vdata["truncateText"] = true;
	$vdata["NumberOfChars"] = 80;

	$fdata["ViewFormats"]["view"] = $vdata;
//  End View Formats

//	Begin Edit Formats
	$fdata["EditFormats"] = array();

	$edata = array("EditFormat" => "Text field");

	
		$edata["weekdayMessage"] = array("message" => "", "messageType" => "Text");
	$edata["weekdays"] = "[]";


	
	



	
	
	
	
			$edata["acceptFileTypes"] = ".+$";
		$edata["acceptFileTypesHtml"] = "";

		$edata["maxNumberOfFiles"] = 1;

	
	
	
	
			$edata["HTML5InuptType"] = "email";

		$edata["EditParams"] = "";
			$edata["EditParams"].= " maxlength=50";

		$edata["controlWidth"] = 200;

//	Begin validation
	$edata["validateAs"] = array();
	$edata["validateAs"]["basicValidate"] = array();
	$edata["validateAs"]["customMessages"] = array();
				$edata["validateAs"]["basicValidate"][] = getJsValidatorName("Email");
								$edata["validateAs"]["basicValidate"][] = "DenyDuplicated";
	$edata["validateAs"]["customMessages"]["DenyDuplicated"] = array("message" => "Email %value% telah digunakan dan sudah masuk database. <br>Silahkan gunakan email yang lainnya", "messageType" => "Text");

	
	//	End validation

	
			
	
		$edata["denyDuplicates"] = true;

	
	$fdata["EditFormats"]["edit"] = $edata;
//	End Edit Formats


	$fdata["isSeparate"] = false;




// the field's search options settings
		$fdata["defaultSearchOption"] = "Contains";

			// the default search options list
				$fdata["searchOptionsList"] = array("Contains", "Equals", "Starts with", "More than", "Less than", "Between", "Empty", NOT_EMPTY);
// the end of search options settings


//Filters settings
	$fdata["filterTotals"] = 0;
		$fdata["filterMultiSelect"] = 0;
			$fdata["filterFormat"] = "Values list";
		$fdata["showCollapsed"] = false;

		$fdata["sortValueType"] = 0;
		$fdata["numberOfVisibleItems"] = 10;

		$fdata["filterBy"] = 0;

	

	
	
//end of Filters settings


	$tdatacalonppdb["email"] = $fdata;
		$tdatacalonppdb[".searchableFields"][] = "email";
//	tinggibadan
//	Custom field settings
	$fdata = array();
	$fdata["Index"] = 44;
	$fdata["strName"] = "tinggibadan";
	$fdata["GoodName"] = "tinggibadan";
	$fdata["ownerTable"] = "calonppdb";
	$fdata["Label"] = GetFieldLabel("calonppdb","tinggibadan");
	$fdata["FieldType"] = 200;

	
	
	
			

		$fdata["strField"] = "tinggibadan";

		$fdata["isSQLExpression"] = true;
	$fdata["FullName"] = "tinggibadan";

	
	
				$fdata["UploadFolder"] = "files";

//  Begin View Formats
	$fdata["ViewFormats"] = array();

	$vdata = array("ViewFormat" => "");

	
	
	
	
	
	
	
	
	
	
	
	
		$vdata["NeedEncode"] = true;

	
		$vdata["truncateText"] = true;
	$vdata["NumberOfChars"] = 80;

	$fdata["ViewFormats"]["view"] = $vdata;
//  End View Formats

//	Begin Edit Formats
	$fdata["EditFormats"] = array();

	$edata = array("EditFormat" => "Text field");

	
		$edata["weekdayMessage"] = array("message" => "", "messageType" => "Text");
	$edata["weekdays"] = "[]";


	
	



		$edata["IsRequired"] = true;

	
	
	
			$edata["acceptFileTypes"] = ".+$";
		$edata["acceptFileTypesHtml"] = "";

		$edata["maxNumberOfFiles"] = 1;

	
	
	
	
			$edata["HTML5InuptType"] = "text";

		$edata["EditParams"] = "";
			$edata["EditParams"].= " maxlength=5";

		$edata["controlWidth"] = 200;

//	Begin validation
	$edata["validateAs"] = array();
	$edata["validateAs"]["basicValidate"] = array();
	$edata["validateAs"]["customMessages"] = array();
						$edata["validateAs"]["basicValidate"][] = "IsRequired";
		
	
	//	End validation

	
			
	
	
	
	$fdata["EditFormats"]["edit"] = $edata;
//	End Edit Formats


	$fdata["isSeparate"] = false;




// the field's search options settings
		$fdata["defaultSearchOption"] = "Contains";

			// the default search options list
				$fdata["searchOptionsList"] = array("Contains", "Equals", "Starts with", "More than", "Less than", "Between", "Empty", NOT_EMPTY);
// the end of search options settings


//Filters settings
	$fdata["filterTotals"] = 0;
		$fdata["filterMultiSelect"] = 0;
			$fdata["filterFormat"] = "Values list";
		$fdata["showCollapsed"] = false;

		$fdata["sortValueType"] = 0;
		$fdata["numberOfVisibleItems"] = 10;

		$fdata["filterBy"] = 0;

	

	
	
//end of Filters settings


	$tdatacalonppdb["tinggibadan"] = $fdata;
		$tdatacalonppdb[".searchableFields"][] = "tinggibadan";
//	beratbadan
//	Custom field settings
	$fdata = array();
	$fdata["Index"] = 45;
	$fdata["strName"] = "beratbadan";
	$fdata["GoodName"] = "beratbadan";
	$fdata["ownerTable"] = "calonppdb";
	$fdata["Label"] = GetFieldLabel("calonppdb","beratbadan");
	$fdata["FieldType"] = 200;

	
	
	
			

		$fdata["strField"] = "beratbadan";

		$fdata["isSQLExpression"] = true;
	$fdata["FullName"] = "beratbadan";

	
	
				$fdata["UploadFolder"] = "files";

//  Begin View Formats
	$fdata["ViewFormats"] = array();

	$vdata = array("ViewFormat" => "");

	
	
	
	
	
	
	
	
	
	
	
	
		$vdata["NeedEncode"] = true;

	
		$vdata["truncateText"] = true;
	$vdata["NumberOfChars"] = 80;

	$fdata["ViewFormats"]["view"] = $vdata;
//  End View Formats

//	Begin Edit Formats
	$fdata["EditFormats"] = array();

	$edata = array("EditFormat" => "Text field");

	
		$edata["weekdayMessage"] = array("message" => "", "messageType" => "Text");
	$edata["weekdays"] = "[]";


	
	



		$edata["IsRequired"] = true;

	
	
	
			$edata["acceptFileTypes"] = ".+$";
		$edata["acceptFileTypesHtml"] = "";

		$edata["maxNumberOfFiles"] = 1;

	
	
	
	
			$edata["HTML5InuptType"] = "text";

		$edata["EditParams"] = "";
			$edata["EditParams"].= " maxlength=5";

		$edata["controlWidth"] = 200;

//	Begin validation
	$edata["validateAs"] = array();
	$edata["validateAs"]["basicValidate"] = array();
	$edata["validateAs"]["customMessages"] = array();
						$edata["validateAs"]["basicValidate"][] = "IsRequired";
		
	
	//	End validation

	
			
	
	
	
	$fdata["EditFormats"]["edit"] = $edata;
//	End Edit Formats


	$fdata["isSeparate"] = false;




// the field's search options settings
		$fdata["defaultSearchOption"] = "Contains";

			// the default search options list
				$fdata["searchOptionsList"] = array("Contains", "Equals", "Starts with", "More than", "Less than", "Between", "Empty", NOT_EMPTY);
// the end of search options settings


//Filters settings
	$fdata["filterTotals"] = 0;
		$fdata["filterMultiSelect"] = 0;
			$fdata["filterFormat"] = "Values list";
		$fdata["showCollapsed"] = false;

		$fdata["sortValueType"] = 0;
		$fdata["numberOfVisibleItems"] = 10;

		$fdata["filterBy"] = 0;

	

	
	
//end of Filters settings


	$tdatacalonppdb["beratbadan"] = $fdata;
		$tdatacalonppdb[".searchableFields"][] = "beratbadan";
//	jaraktempatkesekolah
//	Custom field settings
	$fdata = array();
	$fdata["Index"] = 46;
	$fdata["strName"] = "jaraktempatkesekolah";
	$fdata["GoodName"] = "jaraktempatkesekolah";
	$fdata["ownerTable"] = "calonppdb";
	$fdata["Label"] = GetFieldLabel("calonppdb","jaraktempatkesekolah");
	$fdata["FieldType"] = 200;

	
	
	
			

		$fdata["strField"] = "jaraktempatkesekolah";

		$fdata["isSQLExpression"] = true;
	$fdata["FullName"] = "jaraktempatkesekolah";

	
	
				$fdata["UploadFolder"] = "files";

//  Begin View Formats
	$fdata["ViewFormats"] = array();

	$vdata = array("ViewFormat" => "");

	
	
	
	
	
	
	
	
	
	
	
	
		$vdata["NeedEncode"] = true;

	
		$vdata["truncateText"] = true;
	$vdata["NumberOfChars"] = 80;

	$fdata["ViewFormats"]["view"] = $vdata;
//  End View Formats

//	Begin Edit Formats
	$fdata["EditFormats"] = array();

	$edata = array("EditFormat" => "Text field");

	
		$edata["weekdayMessage"] = array("message" => "", "messageType" => "Text");
	$edata["weekdays"] = "[]";


	
	



		$edata["IsRequired"] = true;

	
	
	
			$edata["acceptFileTypes"] = ".+$";
		$edata["acceptFileTypesHtml"] = "";

		$edata["maxNumberOfFiles"] = 1;

	
	
	
	
			$edata["HTML5InuptType"] = "text";

		$edata["EditParams"] = "";
			$edata["EditParams"].= " maxlength=5";

		$edata["controlWidth"] = 200;

//	Begin validation
	$edata["validateAs"] = array();
	$edata["validateAs"]["basicValidate"] = array();
	$edata["validateAs"]["customMessages"] = array();
						$edata["validateAs"]["basicValidate"][] = "IsRequired";
		
	
	//	End validation

	
			
	
	
	
	$fdata["EditFormats"]["edit"] = $edata;
//	End Edit Formats


	$fdata["isSeparate"] = false;




// the field's search options settings
		$fdata["defaultSearchOption"] = "Contains";

			// the default search options list
				$fdata["searchOptionsList"] = array("Contains", "Equals", "Starts with", "More than", "Less than", "Between", "Empty", NOT_EMPTY);
// the end of search options settings


//Filters settings
	$fdata["filterTotals"] = 0;
		$fdata["filterMultiSelect"] = 0;
			$fdata["filterFormat"] = "Values list";
		$fdata["showCollapsed"] = false;

		$fdata["sortValueType"] = 0;
		$fdata["numberOfVisibleItems"] = 10;

		$fdata["filterBy"] = 0;

	

	
	
//end of Filters settings


	$tdatacalonppdb["jaraktempatkesekolah"] = $fdata;
		$tdatacalonppdb[".searchableFields"][] = "jaraktempatkesekolah";
//	jumlahsaudarakandung
//	Custom field settings
	$fdata = array();
	$fdata["Index"] = 47;
	$fdata["strName"] = "jumlahsaudarakandung";
	$fdata["GoodName"] = "jumlahsaudarakandung";
	$fdata["ownerTable"] = "calonppdb";
	$fdata["Label"] = GetFieldLabel("calonppdb","jumlahsaudarakandung");
	$fdata["FieldType"] = 200;

	
	
	
			

		$fdata["strField"] = "jumlahsaudarakandung";

		$fdata["isSQLExpression"] = true;
	$fdata["FullName"] = "jumlahsaudarakandung";

	
	
				$fdata["UploadFolder"] = "files";

//  Begin View Formats
	$fdata["ViewFormats"] = array();

	$vdata = array("ViewFormat" => "");

	
	
	
	
	
	
	
	
	
	
	
	
		$vdata["NeedEncode"] = true;

	
		$vdata["truncateText"] = true;
	$vdata["NumberOfChars"] = 80;

	$fdata["ViewFormats"]["view"] = $vdata;
//  End View Formats

//	Begin Edit Formats
	$fdata["EditFormats"] = array();

	$edata = array("EditFormat" => "Lookup wizard");

	
		$edata["weekdayMessage"] = array("message" => "", "messageType" => "Text");
	$edata["weekdays"] = "[]";


	
	

// Begin Lookup settings
		$edata["LookupType"] = 0;
			$edata["autoCompleteFieldsOnEdit"] = 0;
	$edata["autoCompleteFields"] = array();
		$edata["LCType"] = 0;

	
	
		$edata["LookupValues"] = array();
	$edata["LookupValues"][] = "0";
	$edata["LookupValues"][] = "1";
	$edata["LookupValues"][] = "2";
	$edata["LookupValues"][] = "3";
	$edata["LookupValues"][] = "4";
	$edata["LookupValues"][] = "5";
	$edata["LookupValues"][] = "6";

	
		$edata["SelectSize"] = 1;

// End Lookup Settings


		$edata["IsRequired"] = true;

	
	
	
			$edata["acceptFileTypes"] = ".+$";
		$edata["acceptFileTypesHtml"] = "";

		$edata["maxNumberOfFiles"] = 1;

	
	
	
	
	
	
		$edata["controlWidth"] = 200;

//	Begin validation
	$edata["validateAs"] = array();
	$edata["validateAs"]["basicValidate"] = array();
	$edata["validateAs"]["customMessages"] = array();
						$edata["validateAs"]["basicValidate"][] = "IsRequired";
		
	
	//	End validation

	
			
	
	
	
	$fdata["EditFormats"]["edit"] = $edata;
//	End Edit Formats


	$fdata["isSeparate"] = false;




// the field's search options settings
		$fdata["defaultSearchOption"] = "Equals";

			// the default search options list
				$fdata["searchOptionsList"] = array("Contains", "Equals", "Starts with", "More than", "Less than", "Between", "Empty", NOT_EMPTY);
// the end of search options settings


//Filters settings
	$fdata["filterTotals"] = 0;
		$fdata["filterMultiSelect"] = 0;
			$fdata["filterFormat"] = "Values list";
		$fdata["showCollapsed"] = false;

		$fdata["sortValueType"] = 0;
		$fdata["numberOfVisibleItems"] = 10;

		$fdata["filterBy"] = 0;

	

	
	
//end of Filters settings


	$tdatacalonppdb["jumlahsaudarakandung"] = $fdata;
		$tdatacalonppdb[".searchableFields"][] = "jumlahsaudarakandung";
//	asaltk
//	Custom field settings
	$fdata = array();
	$fdata["Index"] = 48;
	$fdata["strName"] = "asaltk";
	$fdata["GoodName"] = "asaltk";
	$fdata["ownerTable"] = "calonppdb";
	$fdata["Label"] = GetFieldLabel("calonppdb","asaltk");
	$fdata["FieldType"] = 200;

	
	
	
			

		$fdata["strField"] = "asaltk";

		$fdata["isSQLExpression"] = true;
	$fdata["FullName"] = "asaltk";

	
	
				$fdata["UploadFolder"] = "files";

//  Begin View Formats
	$fdata["ViewFormats"] = array();

	$vdata = array("ViewFormat" => "Custom");

	
	
	
	
	
	
	
	
	
	
	
	
	
	
		$vdata["truncateText"] = true;
	$vdata["NumberOfChars"] = 80;

	$fdata["ViewFormats"]["view"] = $vdata;
//  End View Formats

//	Begin Edit Formats
	$fdata["EditFormats"] = array();

	$edata = array("EditFormat" => "Text field");

	
		$edata["weekdayMessage"] = array("message" => "", "messageType" => "Text");
	$edata["weekdays"] = "[]";


	
	



	
	
	
	
			$edata["acceptFileTypes"] = ".+$";
		$edata["acceptFileTypesHtml"] = "";

		$edata["maxNumberOfFiles"] = 1;

	
	
	
	
			$edata["HTML5InuptType"] = "text";

		$edata["EditParams"] = "";
			$edata["EditParams"].= " maxlength=50";

		$edata["controlWidth"] = 200;

//	Begin validation
	$edata["validateAs"] = array();
	$edata["validateAs"]["basicValidate"] = array();
	$edata["validateAs"]["customMessages"] = array();
	
	
	//	End validation

	
			
	
	
	
	$fdata["EditFormats"]["edit"] = $edata;
//	End Edit Formats


	$fdata["isSeparate"] = false;




// the field's search options settings
		$fdata["defaultSearchOption"] = "Contains";

			// the default search options list
				$fdata["searchOptionsList"] = array("Contains", "Equals", "Starts with", "More than", "Less than", "Between", "Empty", NOT_EMPTY);
// the end of search options settings


//Filters settings
	$fdata["filterTotals"] = 0;
		$fdata["filterMultiSelect"] = 0;
			$fdata["filterFormat"] = "Values list";
		$fdata["showCollapsed"] = false;

		$fdata["sortValueType"] = 0;
		$fdata["numberOfVisibleItems"] = 10;

		$fdata["filterBy"] = 0;

	

	
	
//end of Filters settings


	$tdatacalonppdb["asaltk"] = $fdata;
		$tdatacalonppdb[".searchableFields"][] = "asaltk";
//	nomorsurattk
//	Custom field settings
	$fdata = array();
	$fdata["Index"] = 49;
	$fdata["strName"] = "nomorsurattk";
	$fdata["GoodName"] = "nomorsurattk";
	$fdata["ownerTable"] = "calonppdb";
	$fdata["Label"] = GetFieldLabel("calonppdb","nomorsurattk");
	$fdata["FieldType"] = 200;

	
	
	
			

		$fdata["strField"] = "nomorsurattk";

		$fdata["isSQLExpression"] = true;
	$fdata["FullName"] = "nomorsurattk";

	
	
				$fdata["UploadFolder"] = "files";

//  Begin View Formats
	$fdata["ViewFormats"] = array();

	$vdata = array("ViewFormat" => "");

	
	
	
	
	
	
	
	
	
	
	
	
		$vdata["NeedEncode"] = true;

	
		$vdata["truncateText"] = true;
	$vdata["NumberOfChars"] = 80;

	$fdata["ViewFormats"]["view"] = $vdata;
//  End View Formats

//	Begin Edit Formats
	$fdata["EditFormats"] = array();

	$edata = array("EditFormat" => "Text field");

	
		$edata["weekdayMessage"] = array("message" => "", "messageType" => "Text");
	$edata["weekdays"] = "[]";


	
	



	
	
	
	
			$edata["acceptFileTypes"] = ".+$";
		$edata["acceptFileTypesHtml"] = "";

		$edata["maxNumberOfFiles"] = 1;

	
	
	
	
			$edata["HTML5InuptType"] = "text";

		$edata["EditParams"] = "";
			$edata["EditParams"].= " maxlength=50";

		$edata["controlWidth"] = 200;

//	Begin validation
	$edata["validateAs"] = array();
	$edata["validateAs"]["basicValidate"] = array();
	$edata["validateAs"]["customMessages"] = array();
	
	
	//	End validation

	
			
	
	
	
	$fdata["EditFormats"]["edit"] = $edata;
//	End Edit Formats


	$fdata["isSeparate"] = false;




// the field's search options settings
		$fdata["defaultSearchOption"] = "Contains";

			// the default search options list
				$fdata["searchOptionsList"] = array("Contains", "Equals", "Starts with", "More than", "Less than", "Between", "Empty", NOT_EMPTY);
// the end of search options settings


//Filters settings
	$fdata["filterTotals"] = 0;
		$fdata["filterMultiSelect"] = 0;
			$fdata["filterFormat"] = "Values list";
		$fdata["showCollapsed"] = false;

		$fdata["sortValueType"] = 0;
		$fdata["numberOfVisibleItems"] = 10;

		$fdata["filterBy"] = 0;

	

	
	
//end of Filters settings


	$tdatacalonppdb["nomorsurattk"] = $fdata;
		$tdatacalonppdb[".searchableFields"][] = "nomorsurattk";
//	paktaintegritas
//	Custom field settings
	$fdata = array();
	$fdata["Index"] = 50;
	$fdata["strName"] = "paktaintegritas";
	$fdata["GoodName"] = "paktaintegritas";
	$fdata["ownerTable"] = "calonppdb";
	$fdata["Label"] = GetFieldLabel("calonppdb","paktaintegritas");
	$fdata["FieldType"] = 200;

	
	
	
			

		$fdata["strField"] = "paktaintegritas";

		$fdata["isSQLExpression"] = true;
	$fdata["FullName"] = "paktaintegritas";

	
	
				$fdata["UploadFolder"] = "files";

//  Begin View Formats
	$fdata["ViewFormats"] = array();

	$vdata = array("ViewFormat" => "");

	
	
	
	
	
	
	
	
	
	
	
	
		$vdata["NeedEncode"] = true;

	
		$vdata["truncateText"] = true;
	$vdata["NumberOfChars"] = 80;

	$fdata["ViewFormats"]["view"] = $vdata;
//  End View Formats

//	Begin Edit Formats
	$fdata["EditFormats"] = array();

	$edata = array("EditFormat" => "SignaturePad");

	
		$edata["weekdayMessage"] = array("message" => "", "messageType" => "Text");
	$edata["weekdays"] = "[]";


	
	



		$edata["IsRequired"] = true;

	
	
	
			$edata["acceptFileTypes"] = ".+$";
		$edata["acceptFileTypesHtml"] = "";

		$edata["maxNumberOfFiles"] = 1;

	
	
	
	
	
	
		$edata["controlWidth"] = 200;

//	Begin validation
	$edata["validateAs"] = array();
	$edata["validateAs"]["basicValidate"] = array();
	$edata["validateAs"]["customMessages"] = array();
						$edata["validateAs"]["basicValidate"][] = "IsRequired";
		
	
	//	End validation

	
			
	
	
	
	$fdata["EditFormats"]["edit"] = $edata;
//	End Edit Formats


	$fdata["isSeparate"] = false;




// the field's search options settings
		$fdata["defaultSearchOption"] = "Equals";

			// the default search options list
				$fdata["searchOptionsList"] = array("Contains", "Equals", "Starts with", "More than", "Less than", "Between", "Empty", NOT_EMPTY);
// the end of search options settings


//Filters settings
	$fdata["filterTotals"] = 0;
		$fdata["filterMultiSelect"] = 0;
			$fdata["filterFormat"] = "Values list";
		$fdata["showCollapsed"] = false;

		$fdata["sortValueType"] = 0;
		$fdata["numberOfVisibleItems"] = 10;

		$fdata["filterBy"] = 0;

	

	
	
//end of Filters settings


	$tdatacalonppdb["paktaintegritas"] = $fdata;
		$tdatacalonppdb[".searchableFields"][] = "paktaintegritas";
//	namaibu
//	Custom field settings
	$fdata = array();
	$fdata["Index"] = 51;
	$fdata["strName"] = "namaibu";
	$fdata["GoodName"] = "namaibu";
	$fdata["ownerTable"] = "calonppdb";
	$fdata["Label"] = GetFieldLabel("calonppdb","namaibu");
	$fdata["FieldType"] = 200;

	
	
	
			

		$fdata["strField"] = "namaibu";

		$fdata["isSQLExpression"] = true;
	$fdata["FullName"] = "namaibu";

	
	
				$fdata["UploadFolder"] = "files";

//  Begin View Formats
	$fdata["ViewFormats"] = array();

	$vdata = array("ViewFormat" => "Custom");

	
	
	
	
	
	
	
	
	
	
	
	
	
	
		$vdata["truncateText"] = true;
	$vdata["NumberOfChars"] = 80;

	$fdata["ViewFormats"]["view"] = $vdata;
//  End View Formats

//	Begin Edit Formats
	$fdata["EditFormats"] = array();

	$edata = array("EditFormat" => "Text field");

	
		$edata["weekdayMessage"] = array("message" => "", "messageType" => "Text");
	$edata["weekdays"] = "[]";


	
	



		$edata["IsRequired"] = true;

	
	
	
			$edata["acceptFileTypes"] = ".+$";
		$edata["acceptFileTypesHtml"] = "";

		$edata["maxNumberOfFiles"] = 1;

	
	
	
	
			$edata["HTML5InuptType"] = "text";

		$edata["EditParams"] = "";
			$edata["EditParams"].= " maxlength=50";

		$edata["controlWidth"] = 200;

//	Begin validation
	$edata["validateAs"] = array();
	$edata["validateAs"]["basicValidate"] = array();
	$edata["validateAs"]["customMessages"] = array();
						$edata["validateAs"]["basicValidate"][] = "IsRequired";
		
	
	//	End validation

	
			
	
	
	
	$fdata["EditFormats"]["edit"] = $edata;
//	End Edit Formats


	$fdata["isSeparate"] = false;




// the field's search options settings
		$fdata["defaultSearchOption"] = "Contains";

			// the default search options list
				$fdata["searchOptionsList"] = array("Contains", "Equals", "Starts with", "More than", "Less than", "Between", "Empty", NOT_EMPTY);
// the end of search options settings


//Filters settings
	$fdata["filterTotals"] = 0;
		$fdata["filterMultiSelect"] = 0;
			$fdata["filterFormat"] = "Values list";
		$fdata["showCollapsed"] = false;

		$fdata["sortValueType"] = 0;
		$fdata["numberOfVisibleItems"] = 10;

		$fdata["filterBy"] = 0;

	

	
	
//end of Filters settings


	$tdatacalonppdb["namaibu"] = $fdata;
		$tdatacalonppdb[".searchableFields"][] = "namaibu";
//	status
//	Custom field settings
	$fdata = array();
	$fdata["Index"] = 52;
	$fdata["strName"] = "status";
	$fdata["GoodName"] = "status";
	$fdata["ownerTable"] = "calonppdb";
	$fdata["Label"] = GetFieldLabel("calonppdb","status");
	$fdata["FieldType"] = 200;

	
	
	
			

		$fdata["strField"] = "status";

		$fdata["isSQLExpression"] = true;
	$fdata["FullName"] = "status";

	
	
				$fdata["UploadFolder"] = "files";

//  Begin View Formats
	$fdata["ViewFormats"] = array();

	$vdata = array("ViewFormat" => "");

	
	
	
	
	
	
	
	
	
	
	
	
		$vdata["NeedEncode"] = true;

	
		$vdata["truncateText"] = true;
	$vdata["NumberOfChars"] = 80;

	$fdata["ViewFormats"]["view"] = $vdata;
//  End View Formats

//	Begin Edit Formats
	$fdata["EditFormats"] = array();

	$edata = array("EditFormat" => "Text field");

	
		$edata["weekdayMessage"] = array("message" => "", "messageType" => "Text");
	$edata["weekdays"] = "[]";


	
	



	
	
	
	
			$edata["acceptFileTypes"] = ".+$";
		$edata["acceptFileTypesHtml"] = "";

		$edata["maxNumberOfFiles"] = 1;

	
	
	
	
			$edata["HTML5InuptType"] = "text";

		$edata["EditParams"] = "";
			$edata["EditParams"].= " maxlength=50";

		$edata["controlWidth"] = 200;

//	Begin validation
	$edata["validateAs"] = array();
	$edata["validateAs"]["basicValidate"] = array();
	$edata["validateAs"]["customMessages"] = array();
	
	
	//	End validation

	
			
	
	
	
	$fdata["EditFormats"]["edit"] = $edata;
//	End Edit Formats


	$fdata["isSeparate"] = false;




// the field's search options settings
		$fdata["defaultSearchOption"] = "Contains";

			// the default search options list
				$fdata["searchOptionsList"] = array("Contains", "Equals", "Starts with", "More than", "Less than", "Between", "Empty", NOT_EMPTY);
// the end of search options settings


//Filters settings
	$fdata["filterTotals"] = 0;
		$fdata["filterMultiSelect"] = 0;
			$fdata["filterFormat"] = "Values list";
		$fdata["showCollapsed"] = false;

		$fdata["sortValueType"] = 0;
		$fdata["numberOfVisibleItems"] = 10;

		$fdata["filterBy"] = 0;

	

	
	
//end of Filters settings


	$tdatacalonppdb["status"] = $fdata;
		$tdatacalonppdb[".searchableFields"][] = "status";


$tables_data["calonppdb"]=&$tdatacalonppdb;
$field_labels["calonppdb"] = &$fieldLabelscalonppdb;
$fieldToolTips["calonppdb"] = &$fieldToolTipscalonppdb;
$placeHolders["calonppdb"] = &$placeHolderscalonppdb;
$page_titles["calonppdb"] = &$pageTitlescalonppdb;

// -----------------start  prepare master-details data arrays ------------------------------//
// tables which are detail tables for current table (master)
$detailsTablesData["calonppdb"] = array();

// tables which are master tables for current table (detail)
$masterTablesData["calonppdb"] = array();



// -----------------end  prepare master-details data arrays ------------------------------//


require_once(getabspath("classes/sql.php"));










function createSqlQuery_calonppdb()
{
$proto0=array();
$proto0["m_strHead"] = "SELECT";
$proto0["m_strFieldList"] = "ID,  tanggaldaftar,  nomap,  namalengkap,  jeniskelamin,  nisn,  nik,  tempatlahir,  tanggallahir1,  noaktalahir,  agama,  alamatjalan,  rt,  rw,  dusun,  deskel,  kecamatan,  kodepos,  tempattinggal,  modatransportasi,  anakke,  namaayah,  nikayah,  tanggallahir2,  pendidikan1,  pekerjaan1,  penghasilan1,  statusayah,  statusibu,  nikibu,  tanggallahir3,  pendidikan2,  pekerjaan2,  penghasilan2,  namawali,  nikwali,  tanggallahir4,  pendidikan3,  pekerjaan3,  penghasilan3,  kontak,  nomorwa,  email,  tinggibadan,  beratbadan,  jaraktempatkesekolah,  jumlahsaudarakandung,  asaltk,  nomorsurattk,  paktaintegritas,  namaibu,  status";
$proto0["m_strFrom"] = "FROM calonppdb";
$proto0["m_strWhere"] = "";
$proto0["m_strOrderBy"] = "ORDER BY tanggaldaftar DESC";
	
		;
			$proto0["cipherer"] = null;
$proto2=array();
$proto2["m_sql"] = "";
$proto2["m_uniontype"] = "SQLL_UNKNOWN";
	$obj = new SQLNonParsed(array(
	"m_sql" => ""
));

$proto2["m_column"]=$obj;
$proto2["m_contained"] = array();
$proto2["m_strCase"] = "";
$proto2["m_havingmode"] = false;
$proto2["m_inBrackets"] = false;
$proto2["m_useAlias"] = false;
$obj = new SQLLogicalExpr($proto2);

$proto0["m_where"] = $obj;
$proto4=array();
$proto4["m_sql"] = "";
$proto4["m_uniontype"] = "SQLL_UNKNOWN";
	$obj = new SQLNonParsed(array(
	"m_sql" => ""
));

$proto4["m_column"]=$obj;
$proto4["m_contained"] = array();
$proto4["m_strCase"] = "";
$proto4["m_havingmode"] = false;
$proto4["m_inBrackets"] = false;
$proto4["m_useAlias"] = false;
$obj = new SQLLogicalExpr($proto4);

$proto0["m_having"] = $obj;
$proto0["m_fieldlist"] = array();
						$proto6=array();
			$obj = new SQLField(array(
	"m_strName" => "ID",
	"m_strTable" => "calonppdb",
	"m_srcTableName" => "calonppdb"
));

$proto6["m_sql"] = "ID";
$proto6["m_srcTableName"] = "calonppdb";
$proto6["m_expr"]=$obj;
$proto6["m_alias"] = "";
$obj = new SQLFieldListItem($proto6);

$proto0["m_fieldlist"][]=$obj;
						$proto8=array();
			$obj = new SQLField(array(
	"m_strName" => "tanggaldaftar",
	"m_strTable" => "calonppdb",
	"m_srcTableName" => "calonppdb"
));

$proto8["m_sql"] = "tanggaldaftar";
$proto8["m_srcTableName"] = "calonppdb";
$proto8["m_expr"]=$obj;
$proto8["m_alias"] = "";
$obj = new SQLFieldListItem($proto8);

$proto0["m_fieldlist"][]=$obj;
						$proto10=array();
			$obj = new SQLField(array(
	"m_strName" => "nomap",
	"m_strTable" => "calonppdb",
	"m_srcTableName" => "calonppdb"
));

$proto10["m_sql"] = "nomap";
$proto10["m_srcTableName"] = "calonppdb";
$proto10["m_expr"]=$obj;
$proto10["m_alias"] = "";
$obj = new SQLFieldListItem($proto10);

$proto0["m_fieldlist"][]=$obj;
						$proto12=array();
			$obj = new SQLField(array(
	"m_strName" => "namalengkap",
	"m_strTable" => "calonppdb",
	"m_srcTableName" => "calonppdb"
));

$proto12["m_sql"] = "namalengkap";
$proto12["m_srcTableName"] = "calonppdb";
$proto12["m_expr"]=$obj;
$proto12["m_alias"] = "";
$obj = new SQLFieldListItem($proto12);

$proto0["m_fieldlist"][]=$obj;
						$proto14=array();
			$obj = new SQLField(array(
	"m_strName" => "jeniskelamin",
	"m_strTable" => "calonppdb",
	"m_srcTableName" => "calonppdb"
));

$proto14["m_sql"] = "jeniskelamin";
$proto14["m_srcTableName"] = "calonppdb";
$proto14["m_expr"]=$obj;
$proto14["m_alias"] = "";
$obj = new SQLFieldListItem($proto14);

$proto0["m_fieldlist"][]=$obj;
						$proto16=array();
			$obj = new SQLField(array(
	"m_strName" => "nisn",
	"m_strTable" => "calonppdb",
	"m_srcTableName" => "calonppdb"
));

$proto16["m_sql"] = "nisn";
$proto16["m_srcTableName"] = "calonppdb";
$proto16["m_expr"]=$obj;
$proto16["m_alias"] = "";
$obj = new SQLFieldListItem($proto16);

$proto0["m_fieldlist"][]=$obj;
						$proto18=array();
			$obj = new SQLField(array(
	"m_strName" => "nik",
	"m_strTable" => "calonppdb",
	"m_srcTableName" => "calonppdb"
));

$proto18["m_sql"] = "nik";
$proto18["m_srcTableName"] = "calonppdb";
$proto18["m_expr"]=$obj;
$proto18["m_alias"] = "";
$obj = new SQLFieldListItem($proto18);

$proto0["m_fieldlist"][]=$obj;
						$proto20=array();
			$obj = new SQLField(array(
	"m_strName" => "tempatlahir",
	"m_strTable" => "calonppdb",
	"m_srcTableName" => "calonppdb"
));

$proto20["m_sql"] = "tempatlahir";
$proto20["m_srcTableName"] = "calonppdb";
$proto20["m_expr"]=$obj;
$proto20["m_alias"] = "";
$obj = new SQLFieldListItem($proto20);

$proto0["m_fieldlist"][]=$obj;
						$proto22=array();
			$obj = new SQLField(array(
	"m_strName" => "tanggallahir1",
	"m_strTable" => "calonppdb",
	"m_srcTableName" => "calonppdb"
));

$proto22["m_sql"] = "tanggallahir1";
$proto22["m_srcTableName"] = "calonppdb";
$proto22["m_expr"]=$obj;
$proto22["m_alias"] = "";
$obj = new SQLFieldListItem($proto22);

$proto0["m_fieldlist"][]=$obj;
						$proto24=array();
			$obj = new SQLField(array(
	"m_strName" => "noaktalahir",
	"m_strTable" => "calonppdb",
	"m_srcTableName" => "calonppdb"
));

$proto24["m_sql"] = "noaktalahir";
$proto24["m_srcTableName"] = "calonppdb";
$proto24["m_expr"]=$obj;
$proto24["m_alias"] = "";
$obj = new SQLFieldListItem($proto24);

$proto0["m_fieldlist"][]=$obj;
						$proto26=array();
			$obj = new SQLField(array(
	"m_strName" => "agama",
	"m_strTable" => "calonppdb",
	"m_srcTableName" => "calonppdb"
));

$proto26["m_sql"] = "agama";
$proto26["m_srcTableName"] = "calonppdb";
$proto26["m_expr"]=$obj;
$proto26["m_alias"] = "";
$obj = new SQLFieldListItem($proto26);

$proto0["m_fieldlist"][]=$obj;
						$proto28=array();
			$obj = new SQLField(array(
	"m_strName" => "alamatjalan",
	"m_strTable" => "calonppdb",
	"m_srcTableName" => "calonppdb"
));

$proto28["m_sql"] = "alamatjalan";
$proto28["m_srcTableName"] = "calonppdb";
$proto28["m_expr"]=$obj;
$proto28["m_alias"] = "";
$obj = new SQLFieldListItem($proto28);

$proto0["m_fieldlist"][]=$obj;
						$proto30=array();
			$obj = new SQLField(array(
	"m_strName" => "rt",
	"m_strTable" => "calonppdb",
	"m_srcTableName" => "calonppdb"
));

$proto30["m_sql"] = "rt";
$proto30["m_srcTableName"] = "calonppdb";
$proto30["m_expr"]=$obj;
$proto30["m_alias"] = "";
$obj = new SQLFieldListItem($proto30);

$proto0["m_fieldlist"][]=$obj;
						$proto32=array();
			$obj = new SQLField(array(
	"m_strName" => "rw",
	"m_strTable" => "calonppdb",
	"m_srcTableName" => "calonppdb"
));

$proto32["m_sql"] = "rw";
$proto32["m_srcTableName"] = "calonppdb";
$proto32["m_expr"]=$obj;
$proto32["m_alias"] = "";
$obj = new SQLFieldListItem($proto32);

$proto0["m_fieldlist"][]=$obj;
						$proto34=array();
			$obj = new SQLField(array(
	"m_strName" => "dusun",
	"m_strTable" => "calonppdb",
	"m_srcTableName" => "calonppdb"
));

$proto34["m_sql"] = "dusun";
$proto34["m_srcTableName"] = "calonppdb";
$proto34["m_expr"]=$obj;
$proto34["m_alias"] = "";
$obj = new SQLFieldListItem($proto34);

$proto0["m_fieldlist"][]=$obj;
						$proto36=array();
			$obj = new SQLField(array(
	"m_strName" => "deskel",
	"m_strTable" => "calonppdb",
	"m_srcTableName" => "calonppdb"
));

$proto36["m_sql"] = "deskel";
$proto36["m_srcTableName"] = "calonppdb";
$proto36["m_expr"]=$obj;
$proto36["m_alias"] = "";
$obj = new SQLFieldListItem($proto36);

$proto0["m_fieldlist"][]=$obj;
						$proto38=array();
			$obj = new SQLField(array(
	"m_strName" => "kecamatan",
	"m_strTable" => "calonppdb",
	"m_srcTableName" => "calonppdb"
));

$proto38["m_sql"] = "kecamatan";
$proto38["m_srcTableName"] = "calonppdb";
$proto38["m_expr"]=$obj;
$proto38["m_alias"] = "";
$obj = new SQLFieldListItem($proto38);

$proto0["m_fieldlist"][]=$obj;
						$proto40=array();
			$obj = new SQLField(array(
	"m_strName" => "kodepos",
	"m_strTable" => "calonppdb",
	"m_srcTableName" => "calonppdb"
));

$proto40["m_sql"] = "kodepos";
$proto40["m_srcTableName"] = "calonppdb";
$proto40["m_expr"]=$obj;
$proto40["m_alias"] = "";
$obj = new SQLFieldListItem($proto40);

$proto0["m_fieldlist"][]=$obj;
						$proto42=array();
			$obj = new SQLField(array(
	"m_strName" => "tempattinggal",
	"m_strTable" => "calonppdb",
	"m_srcTableName" => "calonppdb"
));

$proto42["m_sql"] = "tempattinggal";
$proto42["m_srcTableName"] = "calonppdb";
$proto42["m_expr"]=$obj;
$proto42["m_alias"] = "";
$obj = new SQLFieldListItem($proto42);

$proto0["m_fieldlist"][]=$obj;
						$proto44=array();
			$obj = new SQLField(array(
	"m_strName" => "modatransportasi",
	"m_strTable" => "calonppdb",
	"m_srcTableName" => "calonppdb"
));

$proto44["m_sql"] = "modatransportasi";
$proto44["m_srcTableName"] = "calonppdb";
$proto44["m_expr"]=$obj;
$proto44["m_alias"] = "";
$obj = new SQLFieldListItem($proto44);

$proto0["m_fieldlist"][]=$obj;
						$proto46=array();
			$obj = new SQLField(array(
	"m_strName" => "anakke",
	"m_strTable" => "calonppdb",
	"m_srcTableName" => "calonppdb"
));

$proto46["m_sql"] = "anakke";
$proto46["m_srcTableName"] = "calonppdb";
$proto46["m_expr"]=$obj;
$proto46["m_alias"] = "";
$obj = new SQLFieldListItem($proto46);

$proto0["m_fieldlist"][]=$obj;
						$proto48=array();
			$obj = new SQLField(array(
	"m_strName" => "namaayah",
	"m_strTable" => "calonppdb",
	"m_srcTableName" => "calonppdb"
));

$proto48["m_sql"] = "namaayah";
$proto48["m_srcTableName"] = "calonppdb";
$proto48["m_expr"]=$obj;
$proto48["m_alias"] = "";
$obj = new SQLFieldListItem($proto48);

$proto0["m_fieldlist"][]=$obj;
						$proto50=array();
			$obj = new SQLField(array(
	"m_strName" => "nikayah",
	"m_strTable" => "calonppdb",
	"m_srcTableName" => "calonppdb"
));

$proto50["m_sql"] = "nikayah";
$proto50["m_srcTableName"] = "calonppdb";
$proto50["m_expr"]=$obj;
$proto50["m_alias"] = "";
$obj = new SQLFieldListItem($proto50);

$proto0["m_fieldlist"][]=$obj;
						$proto52=array();
			$obj = new SQLField(array(
	"m_strName" => "tanggallahir2",
	"m_strTable" => "calonppdb",
	"m_srcTableName" => "calonppdb"
));

$proto52["m_sql"] = "tanggallahir2";
$proto52["m_srcTableName"] = "calonppdb";
$proto52["m_expr"]=$obj;
$proto52["m_alias"] = "";
$obj = new SQLFieldListItem($proto52);

$proto0["m_fieldlist"][]=$obj;
						$proto54=array();
			$obj = new SQLField(array(
	"m_strName" => "pendidikan1",
	"m_strTable" => "calonppdb",
	"m_srcTableName" => "calonppdb"
));

$proto54["m_sql"] = "pendidikan1";
$proto54["m_srcTableName"] = "calonppdb";
$proto54["m_expr"]=$obj;
$proto54["m_alias"] = "";
$obj = new SQLFieldListItem($proto54);

$proto0["m_fieldlist"][]=$obj;
						$proto56=array();
			$obj = new SQLField(array(
	"m_strName" => "pekerjaan1",
	"m_strTable" => "calonppdb",
	"m_srcTableName" => "calonppdb"
));

$proto56["m_sql"] = "pekerjaan1";
$proto56["m_srcTableName"] = "calonppdb";
$proto56["m_expr"]=$obj;
$proto56["m_alias"] = "";
$obj = new SQLFieldListItem($proto56);

$proto0["m_fieldlist"][]=$obj;
						$proto58=array();
			$obj = new SQLField(array(
	"m_strName" => "penghasilan1",
	"m_strTable" => "calonppdb",
	"m_srcTableName" => "calonppdb"
));

$proto58["m_sql"] = "penghasilan1";
$proto58["m_srcTableName"] = "calonppdb";
$proto58["m_expr"]=$obj;
$proto58["m_alias"] = "";
$obj = new SQLFieldListItem($proto58);

$proto0["m_fieldlist"][]=$obj;
						$proto60=array();
			$obj = new SQLField(array(
	"m_strName" => "statusayah",
	"m_strTable" => "calonppdb",
	"m_srcTableName" => "calonppdb"
));

$proto60["m_sql"] = "statusayah";
$proto60["m_srcTableName"] = "calonppdb";
$proto60["m_expr"]=$obj;
$proto60["m_alias"] = "";
$obj = new SQLFieldListItem($proto60);

$proto0["m_fieldlist"][]=$obj;
						$proto62=array();
			$obj = new SQLField(array(
	"m_strName" => "statusibu",
	"m_strTable" => "calonppdb",
	"m_srcTableName" => "calonppdb"
));

$proto62["m_sql"] = "statusibu";
$proto62["m_srcTableName"] = "calonppdb";
$proto62["m_expr"]=$obj;
$proto62["m_alias"] = "";
$obj = new SQLFieldListItem($proto62);

$proto0["m_fieldlist"][]=$obj;
						$proto64=array();
			$obj = new SQLField(array(
	"m_strName" => "nikibu",
	"m_strTable" => "calonppdb",
	"m_srcTableName" => "calonppdb"
));

$proto64["m_sql"] = "nikibu";
$proto64["m_srcTableName"] = "calonppdb";
$proto64["m_expr"]=$obj;
$proto64["m_alias"] = "";
$obj = new SQLFieldListItem($proto64);

$proto0["m_fieldlist"][]=$obj;
						$proto66=array();
			$obj = new SQLField(array(
	"m_strName" => "tanggallahir3",
	"m_strTable" => "calonppdb",
	"m_srcTableName" => "calonppdb"
));

$proto66["m_sql"] = "tanggallahir3";
$proto66["m_srcTableName"] = "calonppdb";
$proto66["m_expr"]=$obj;
$proto66["m_alias"] = "";
$obj = new SQLFieldListItem($proto66);

$proto0["m_fieldlist"][]=$obj;
						$proto68=array();
			$obj = new SQLField(array(
	"m_strName" => "pendidikan2",
	"m_strTable" => "calonppdb",
	"m_srcTableName" => "calonppdb"
));

$proto68["m_sql"] = "pendidikan2";
$proto68["m_srcTableName"] = "calonppdb";
$proto68["m_expr"]=$obj;
$proto68["m_alias"] = "";
$obj = new SQLFieldListItem($proto68);

$proto0["m_fieldlist"][]=$obj;
						$proto70=array();
			$obj = new SQLField(array(
	"m_strName" => "pekerjaan2",
	"m_strTable" => "calonppdb",
	"m_srcTableName" => "calonppdb"
));

$proto70["m_sql"] = "pekerjaan2";
$proto70["m_srcTableName"] = "calonppdb";
$proto70["m_expr"]=$obj;
$proto70["m_alias"] = "";
$obj = new SQLFieldListItem($proto70);

$proto0["m_fieldlist"][]=$obj;
						$proto72=array();
			$obj = new SQLField(array(
	"m_strName" => "penghasilan2",
	"m_strTable" => "calonppdb",
	"m_srcTableName" => "calonppdb"
));

$proto72["m_sql"] = "penghasilan2";
$proto72["m_srcTableName"] = "calonppdb";
$proto72["m_expr"]=$obj;
$proto72["m_alias"] = "";
$obj = new SQLFieldListItem($proto72);

$proto0["m_fieldlist"][]=$obj;
						$proto74=array();
			$obj = new SQLField(array(
	"m_strName" => "namawali",
	"m_strTable" => "calonppdb",
	"m_srcTableName" => "calonppdb"
));

$proto74["m_sql"] = "namawali";
$proto74["m_srcTableName"] = "calonppdb";
$proto74["m_expr"]=$obj;
$proto74["m_alias"] = "";
$obj = new SQLFieldListItem($proto74);

$proto0["m_fieldlist"][]=$obj;
						$proto76=array();
			$obj = new SQLField(array(
	"m_strName" => "nikwali",
	"m_strTable" => "calonppdb",
	"m_srcTableName" => "calonppdb"
));

$proto76["m_sql"] = "nikwali";
$proto76["m_srcTableName"] = "calonppdb";
$proto76["m_expr"]=$obj;
$proto76["m_alias"] = "";
$obj = new SQLFieldListItem($proto76);

$proto0["m_fieldlist"][]=$obj;
						$proto78=array();
			$obj = new SQLField(array(
	"m_strName" => "tanggallahir4",
	"m_strTable" => "calonppdb",
	"m_srcTableName" => "calonppdb"
));

$proto78["m_sql"] = "tanggallahir4";
$proto78["m_srcTableName"] = "calonppdb";
$proto78["m_expr"]=$obj;
$proto78["m_alias"] = "";
$obj = new SQLFieldListItem($proto78);

$proto0["m_fieldlist"][]=$obj;
						$proto80=array();
			$obj = new SQLField(array(
	"m_strName" => "pendidikan3",
	"m_strTable" => "calonppdb",
	"m_srcTableName" => "calonppdb"
));

$proto80["m_sql"] = "pendidikan3";
$proto80["m_srcTableName"] = "calonppdb";
$proto80["m_expr"]=$obj;
$proto80["m_alias"] = "";
$obj = new SQLFieldListItem($proto80);

$proto0["m_fieldlist"][]=$obj;
						$proto82=array();
			$obj = new SQLField(array(
	"m_strName" => "pekerjaan3",
	"m_strTable" => "calonppdb",
	"m_srcTableName" => "calonppdb"
));

$proto82["m_sql"] = "pekerjaan3";
$proto82["m_srcTableName"] = "calonppdb";
$proto82["m_expr"]=$obj;
$proto82["m_alias"] = "";
$obj = new SQLFieldListItem($proto82);

$proto0["m_fieldlist"][]=$obj;
						$proto84=array();
			$obj = new SQLField(array(
	"m_strName" => "penghasilan3",
	"m_strTable" => "calonppdb",
	"m_srcTableName" => "calonppdb"
));

$proto84["m_sql"] = "penghasilan3";
$proto84["m_srcTableName"] = "calonppdb";
$proto84["m_expr"]=$obj;
$proto84["m_alias"] = "";
$obj = new SQLFieldListItem($proto84);

$proto0["m_fieldlist"][]=$obj;
						$proto86=array();
			$obj = new SQLField(array(
	"m_strName" => "kontak",
	"m_strTable" => "calonppdb",
	"m_srcTableName" => "calonppdb"
));

$proto86["m_sql"] = "kontak";
$proto86["m_srcTableName"] = "calonppdb";
$proto86["m_expr"]=$obj;
$proto86["m_alias"] = "";
$obj = new SQLFieldListItem($proto86);

$proto0["m_fieldlist"][]=$obj;
						$proto88=array();
			$obj = new SQLField(array(
	"m_strName" => "nomorwa",
	"m_strTable" => "calonppdb",
	"m_srcTableName" => "calonppdb"
));

$proto88["m_sql"] = "nomorwa";
$proto88["m_srcTableName"] = "calonppdb";
$proto88["m_expr"]=$obj;
$proto88["m_alias"] = "";
$obj = new SQLFieldListItem($proto88);

$proto0["m_fieldlist"][]=$obj;
						$proto90=array();
			$obj = new SQLField(array(
	"m_strName" => "email",
	"m_strTable" => "calonppdb",
	"m_srcTableName" => "calonppdb"
));

$proto90["m_sql"] = "email";
$proto90["m_srcTableName"] = "calonppdb";
$proto90["m_expr"]=$obj;
$proto90["m_alias"] = "";
$obj = new SQLFieldListItem($proto90);

$proto0["m_fieldlist"][]=$obj;
						$proto92=array();
			$obj = new SQLField(array(
	"m_strName" => "tinggibadan",
	"m_strTable" => "calonppdb",
	"m_srcTableName" => "calonppdb"
));

$proto92["m_sql"] = "tinggibadan";
$proto92["m_srcTableName"] = "calonppdb";
$proto92["m_expr"]=$obj;
$proto92["m_alias"] = "";
$obj = new SQLFieldListItem($proto92);

$proto0["m_fieldlist"][]=$obj;
						$proto94=array();
			$obj = new SQLField(array(
	"m_strName" => "beratbadan",
	"m_strTable" => "calonppdb",
	"m_srcTableName" => "calonppdb"
));

$proto94["m_sql"] = "beratbadan";
$proto94["m_srcTableName"] = "calonppdb";
$proto94["m_expr"]=$obj;
$proto94["m_alias"] = "";
$obj = new SQLFieldListItem($proto94);

$proto0["m_fieldlist"][]=$obj;
						$proto96=array();
			$obj = new SQLField(array(
	"m_strName" => "jaraktempatkesekolah",
	"m_strTable" => "calonppdb",
	"m_srcTableName" => "calonppdb"
));

$proto96["m_sql"] = "jaraktempatkesekolah";
$proto96["m_srcTableName"] = "calonppdb";
$proto96["m_expr"]=$obj;
$proto96["m_alias"] = "";
$obj = new SQLFieldListItem($proto96);

$proto0["m_fieldlist"][]=$obj;
						$proto98=array();
			$obj = new SQLField(array(
	"m_strName" => "jumlahsaudarakandung",
	"m_strTable" => "calonppdb",
	"m_srcTableName" => "calonppdb"
));

$proto98["m_sql"] = "jumlahsaudarakandung";
$proto98["m_srcTableName"] = "calonppdb";
$proto98["m_expr"]=$obj;
$proto98["m_alias"] = "";
$obj = new SQLFieldListItem($proto98);

$proto0["m_fieldlist"][]=$obj;
						$proto100=array();
			$obj = new SQLField(array(
	"m_strName" => "asaltk",
	"m_strTable" => "calonppdb",
	"m_srcTableName" => "calonppdb"
));

$proto100["m_sql"] = "asaltk";
$proto100["m_srcTableName"] = "calonppdb";
$proto100["m_expr"]=$obj;
$proto100["m_alias"] = "";
$obj = new SQLFieldListItem($proto100);

$proto0["m_fieldlist"][]=$obj;
						$proto102=array();
			$obj = new SQLField(array(
	"m_strName" => "nomorsurattk",
	"m_strTable" => "calonppdb",
	"m_srcTableName" => "calonppdb"
));

$proto102["m_sql"] = "nomorsurattk";
$proto102["m_srcTableName"] = "calonppdb";
$proto102["m_expr"]=$obj;
$proto102["m_alias"] = "";
$obj = new SQLFieldListItem($proto102);

$proto0["m_fieldlist"][]=$obj;
						$proto104=array();
			$obj = new SQLField(array(
	"m_strName" => "paktaintegritas",
	"m_strTable" => "calonppdb",
	"m_srcTableName" => "calonppdb"
));

$proto104["m_sql"] = "paktaintegritas";
$proto104["m_srcTableName"] = "calonppdb";
$proto104["m_expr"]=$obj;
$proto104["m_alias"] = "";
$obj = new SQLFieldListItem($proto104);

$proto0["m_fieldlist"][]=$obj;
						$proto106=array();
			$obj = new SQLField(array(
	"m_strName" => "namaibu",
	"m_strTable" => "calonppdb",
	"m_srcTableName" => "calonppdb"
));

$proto106["m_sql"] = "namaibu";
$proto106["m_srcTableName"] = "calonppdb";
$proto106["m_expr"]=$obj;
$proto106["m_alias"] = "";
$obj = new SQLFieldListItem($proto106);

$proto0["m_fieldlist"][]=$obj;
						$proto108=array();
			$obj = new SQLField(array(
	"m_strName" => "status",
	"m_strTable" => "calonppdb",
	"m_srcTableName" => "calonppdb"
));

$proto108["m_sql"] = "status";
$proto108["m_srcTableName"] = "calonppdb";
$proto108["m_expr"]=$obj;
$proto108["m_alias"] = "";
$obj = new SQLFieldListItem($proto108);

$proto0["m_fieldlist"][]=$obj;
$proto0["m_fromlist"] = array();
												$proto110=array();
$proto110["m_link"] = "SQLL_MAIN";
			$proto111=array();
$proto111["m_strName"] = "calonppdb";
$proto111["m_srcTableName"] = "calonppdb";
$proto111["m_columns"] = array();
$proto111["m_columns"][] = "ID";
$proto111["m_columns"][] = "tanggaldaftar";
$proto111["m_columns"][] = "nomap";
$proto111["m_columns"][] = "namalengkap";
$proto111["m_columns"][] = "jeniskelamin";
$proto111["m_columns"][] = "nisn";
$proto111["m_columns"][] = "nik";
$proto111["m_columns"][] = "tempatlahir";
$proto111["m_columns"][] = "tanggallahir1";
$proto111["m_columns"][] = "noaktalahir";
$proto111["m_columns"][] = "agama";
$proto111["m_columns"][] = "alamatjalan";
$proto111["m_columns"][] = "rt";
$proto111["m_columns"][] = "rw";
$proto111["m_columns"][] = "dusun";
$proto111["m_columns"][] = "deskel";
$proto111["m_columns"][] = "kecamatan";
$proto111["m_columns"][] = "kodepos";
$proto111["m_columns"][] = "tempattinggal";
$proto111["m_columns"][] = "modatransportasi";
$proto111["m_columns"][] = "anakke";
$proto111["m_columns"][] = "namaayah";
$proto111["m_columns"][] = "nikayah";
$proto111["m_columns"][] = "tanggallahir2";
$proto111["m_columns"][] = "pendidikan1";
$proto111["m_columns"][] = "pekerjaan1";
$proto111["m_columns"][] = "penghasilan1";
$proto111["m_columns"][] = "statusayah";
$proto111["m_columns"][] = "statusibu";
$proto111["m_columns"][] = "nikibu";
$proto111["m_columns"][] = "tanggallahir3";
$proto111["m_columns"][] = "pendidikan2";
$proto111["m_columns"][] = "pekerjaan2";
$proto111["m_columns"][] = "penghasilan2";
$proto111["m_columns"][] = "namawali";
$proto111["m_columns"][] = "nikwali";
$proto111["m_columns"][] = "tanggallahir4";
$proto111["m_columns"][] = "pendidikan3";
$proto111["m_columns"][] = "pekerjaan3";
$proto111["m_columns"][] = "penghasilan3";
$proto111["m_columns"][] = "kontak";
$proto111["m_columns"][] = "nomorwa";
$proto111["m_columns"][] = "email";
$proto111["m_columns"][] = "tinggibadan";
$proto111["m_columns"][] = "beratbadan";
$proto111["m_columns"][] = "jaraktempatkesekolah";
$proto111["m_columns"][] = "jumlahsaudarakandung";
$proto111["m_columns"][] = "asaltk";
$proto111["m_columns"][] = "nomorsurattk";
$proto111["m_columns"][] = "paktaintegritas";
$proto111["m_columns"][] = "namaibu";
$proto111["m_columns"][] = "status";
$obj = new SQLTable($proto111);

$proto110["m_table"] = $obj;
$proto110["m_sql"] = "calonppdb";
$proto110["m_alias"] = "";
$proto110["m_srcTableName"] = "calonppdb";
$proto112=array();
$proto112["m_sql"] = "";
$proto112["m_uniontype"] = "SQLL_UNKNOWN";
	$obj = new SQLNonParsed(array(
	"m_sql" => ""
));

$proto112["m_column"]=$obj;
$proto112["m_contained"] = array();
$proto112["m_strCase"] = "";
$proto112["m_havingmode"] = false;
$proto112["m_inBrackets"] = false;
$proto112["m_useAlias"] = false;
$obj = new SQLLogicalExpr($proto112);

$proto110["m_joinon"] = $obj;
$obj = new SQLFromListItem($proto110);

$proto0["m_fromlist"][]=$obj;
$proto0["m_groupby"] = array();
$proto0["m_orderby"] = array();
												$proto114=array();
						$obj = new SQLField(array(
	"m_strName" => "tanggaldaftar",
	"m_strTable" => "calonppdb",
	"m_srcTableName" => "calonppdb"
));

$proto114["m_column"]=$obj;
$proto114["m_bAsc"] = 0;
$proto114["m_nColumn"] = 0;
$obj = new SQLOrderByItem($proto114);

$proto0["m_orderby"][]=$obj;					
$proto0["m_srcTableName"]="calonppdb";		
$obj = new SQLQuery($proto0);

	return $obj;
}
$queryData_calonppdb = createSqlQuery_calonppdb();


	
		;

																																																				

$tdatacalonppdb[".sqlquery"] = $queryData_calonppdb;

include_once(getabspath("include/calonppdb_events.php"));
$tableEvents["calonppdb"] = new eventclass_calonppdb;
$tdatacalonppdb[".hasEvents"] = true;

?>