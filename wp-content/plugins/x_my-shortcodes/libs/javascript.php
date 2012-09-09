<?php
echo ce_configOption('_javascriptCode', 'javascriptCode', 'textarea', '', $Element);
?>

<script type="text/javascript">

    var jseditor = CodeMirror.fromTextArea(document.getElementById("_javascriptCode"), {
            theme: "default",
            autofocus: true,
            lineNumbers: true,
            matchBrackets: true,
            mode: "text/javascript",
            indentUnit: 4,
            tabSize: 4,
            indentWithTabs: false,
            enterMode: "keep",
            tabMode: "shift",
            gutter: true,
            onBlur: function(){
                jseditor.save();
            }

    });

</script>