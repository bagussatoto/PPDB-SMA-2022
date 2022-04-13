<?php
##if exists(@TABLE)##
	##pages_info @TABLE.strDataSourceTable##;
##else##
	##pages_info "<global>"##;
##endif##
?>
