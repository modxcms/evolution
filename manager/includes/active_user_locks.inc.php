<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODX Content Manager instead of accessing this file directly.");

$lockElementId = intval($lockElementId);

if($lockElementId > 0) {
?>
<script>
	// Trigger unlock when leaving window
	var form_save = false;
	jQuery(window).on('beforeunload', function(){
		var stay = jQuery('#stay').val();
		var lastClickedElement = localStorage.getItem('MODX_lastClickedElement');
		var sameElement = false; 
		if(lastClickedElement != null) {
			try {
				lastClickedElement = JSON.parse( lastClickedElement );
				sameElement = lastClickedElement[0]==<?php echo $lockElementType;?> && lastClickedElement[1]==<?php echo $lockElementId;?> ? true : false;
			} catch(err) {
				console.log(err);
			}
		}
		
		// Trigger unlock 
		if((stay != 2 || !form_save) && !sameElement) {
			// console.log('unlock triggered:', 'stay='+stay, 'form_save='+form_save, 'sameElement='+sameElement, lastClickedElement);
			var unlockRequest = new Ajax('index.php?a=67&type=<?php echo $lockElementType;?>&id=<?php echo $lockElementId;?>&o=' + Math.random(), {
				method: 'get'
			}).request();
            top.mainMenu.reloadtree();
		}
	});
</script>
<?php
}
?>