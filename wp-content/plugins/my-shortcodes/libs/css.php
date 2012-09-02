<?php
echo ce_configOption('_cssCode', 'cssCode', 'textarea', '', $Element);
?>

<script type="text/javascript">
var csseditor = CodeMirror.fromTextArea(document.getElementById("_cssCode"), {
	theme: "default",
	lineNumbers: true,
        matchBrackets: true,
	mode: "text/css",
	indentUnit: 4,
	tabSize: 4,
        indentWithTabs: false,
        enterMode: "keep",
        tabMode: "shift",
	gutter: true,
        onBlur: function(){
            csseditor.save();
        }
});

</script>