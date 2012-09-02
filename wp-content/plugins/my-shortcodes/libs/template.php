<?php
echo ce_configOption('_mainCode', 'mainCode', 'textarea', '', $Element);
?>

<script type="text/javascript">
var htmleditor = CodeMirror.fromTextArea(document.getElementById("_mainCode"), {
	theme: "default",
	lineNumbers: true,
        matchBrackets: true,
	mode: "application/x-httpd-php",        
	indentUnit: 4,
	tabSize: 4,
        indentWithTabs: false,
        enterMode: "keep",
        tabMode: "shift",
	gutter: true,
        onBlur: function(){
            htmleditor.save();
        }
});



</script>