<script type="text/javascript">
<?php echo $js ?>

<?php foreach ($fields as $field){ ?>
function get_data_<?php echo $field->id ?>(){return JSON.stringify(data_<?php echo $field->id ?>);}
var data_<?php echo $field->id ?>=<?php echo $data[$field->id] ?>;
<?php } 
foreach(array('time', 'hour', 'month', 'year') as $time){ 
    if(!isset($data[$time])) continue; ?>
function get_data_<?php echo $time ?>(){return JSON.stringify(data_<?php echo $time ?>);}
var data_<?php echo $time ?>=<?php echo $data[$time] ?>;
<?php } ?>

OFC = {};
OFC.jquery = {
    name: "jQuery",
    version: function(src){ return jQuery('#'+ src)[0].get_version() },
    rasterize: function (src, dst){ jQuery('#'+ dst).replaceWith(OFC.jquery.image(src)) },
    image: function(src){ return "<img class='frm_print_graph' src='data:image/png;base64," + jQuery('#'+src)[0].get_img_binary() + "' />"},
    popup: function(src){
        var img_win = window.open('', 'Charts: Export as Image')
        with(img_win.document) {
            write('<html><head><title>Charts: Export as Image<\/title><\/head><body>' + OFC.jquery.image(src) + '<\/body><\/html>') }
		// stop the 'loading...' message
		img_win.document.close();
     }
}

</script>