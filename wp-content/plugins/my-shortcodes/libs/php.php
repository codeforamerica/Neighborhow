<?php
echo ce_configOption('_phpCode', 'phpCode', 'textarea', '', $Element);
?>

<script type="text/javascript">
var phpeditor = CodeMirror.fromTextArea(document.getElementById("_phpCode"), {
	theme: "default",
	lineNumbers: true,
        matchBrackets: true,
	mode: "text/x-php",
	indentUnit: 4,
	tabSize: 4,
        indentWithTabs: false,
        enterMode: "keep",
        tabMode: "shift",
	gutter: true,
        onBlur: function(){
            phpeditor.save();
        }
});

</script>