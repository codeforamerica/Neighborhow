<?php
	if ($field['type'] == 'table'){
		$entry_id = FrmAppHelper::get_param('id');
		if ($entry_id != '' and !isset($field['value'])){
			$field['value'] = FrmPlusEntryMetaHelper::fetch($field['id'],$entry_id);
		}
		require('table.php');
	}
?>