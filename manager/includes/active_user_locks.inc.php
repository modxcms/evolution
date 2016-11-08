<?php
if(IN_MANAGER_MODE!="true") die("<b>INCLUDE_ORDERING_ERROR</b><br /><br />Please use the MODX Content Manager instead of accessing this file directly.");

$lockElementId = intval($lockElementId);

if($lockElementId > 0) {
?>
<script>
	function keepMeLocked() {
		var sessionJSON = new Ajax('includes/active_user_locks.php?tok=<?php echo md5(session_id());?>&type=<?php echo $lockElementType;?>&id=<?php echo $lockElementId;?>&o=' + Math.random(), {
			method: 'get',
			onComplete: function(sessionResponse) {
				resp = Json.evaluate(sessionResponse);
				if(resp.status != 'ok') {
					var errorMsg = '<?php echo sprintf($_lang["lock_element_unknown_error"], $lockElementType, $lockElementId, $_lang["lock_element_type_".$lockElementType]);?>';
					jQuery('body').prepend('<div class="alert alert-error">'+errorMsg+'</div>');
					alert(errorMsg);
					clearInterval(keepMeLockedInterval);
				}
			}
		}).request();
	}
	var keepMeLockedInterval = window.setInterval("keepMeLocked()", 1000 * <?php echo isset($modx->config['lock_interval']) ? intval($modx->config['lock_interval']) : 15; ?>);
</script>
<?php
}
?>