<?php
$_POST = array();
$_POST['installmode'] = 1;
$installMode = 1;
?>

<script>
	parent.$.fancybox.close();
	parent.jQuery.fancybox.close();
</script>
<!--
<h2>Загрузите пакет для установки</h2>
<p>установка не проверенных елементов может привести к печальным последствиям потому рекомендую делать бекапы</p>
<form name="install" id="install_form" action="<?php echo $moduleurl?>action=options" method="post" enctype="multipart/form-data">
  <div>
    <input type="file" name="zip"/>
  </div>


    </div>
    <p class="buttonlinks">
        <!-- тут кнопку отменить разве что поставить<a href="javascript:document.getElementById('install_form').action='index.php?action=<?php echo (($installMode == 1) ? 'mode' : 'connection'); ?>';document.getElementById('install_form').submit();" class="prev" title="<?php echo $_lang['btnback_value']?>"><span><?php echo $_lang['btnback_value']?></span></a>
        <a href="javascript:document.getElementById('install_form').submit();" title="<?php echo $_lang['install']?>"><span><?php echo $_lang['install']?></span></a>
    </p>

</form>
-->