<?php
$installMode = intval($_POST['installmode']);

// Determine upgradeability
$upgradeable= 0;
if ($installMode > 0) {
  if (file_exists("../manager/includes/config.inc.php")) {
      // Include the file so we can test its validity
      include "../manager/includes/config.inc.php";
      // We need to have all connection settings - but prefix may be empty so we have to ignore it
      if ($dbase) {
          if (!@ $conn = mysql_connect($database_server, $database_user, $database_password)) {
              $upgradeable = isset ($_POST['installmode']) && $_POST['installmode'] == 'new' ? 0 : 2;
          }
          elseif (!@ mysql_select_db(trim($dbase, '`'), $conn)) {
              $upgradeable = isset ($_POST['installmode']) && $_POST['installmode'] == 'new' ? 0 : 2;
          } else {
              $upgradeable = 1;
          }
          $database_name= trim($dbase, '`');
      } else {
          $upgradable= 2;
      }
  }
} else {
    $database_name= 'modx';
    $database_server= 'localhost';
    $table_prefix= 'modx_';
}

// check the database collation if not specified in the configuration
if ($upgradeable && (!isset ($database_connection_charset) || empty($database_connection_charset))) {
    if (!$rs = @ mysql_query("show session variables like 'collation_database'")) {
        $rs = @ mysql_query("show session variables like 'collation_server'");
    }
    if ($rs && $collation = mysql_fetch_row($rs)) {
        $database_collation = $collation[1];
    }
    if (empty ($database_collation)) {
        $database_collation = 'utf8_general_ci';
    }
    $database_charset = substr($database_collation, 0, strpos($database_collation, '_'));
    $database_connection_charset = $database_charset;
} else {
    $database_collation = 'utf8_general_ci';
}

?>
<script type="text/javascript" src="connection.mootools.1.11.js"></script>
<script type="text/javascript" src="connection.js"></script>
<script type="text/javascript">language ='<?php echo $install_language?>'</script>

<form name="install" action="index.php?action=options" method="post">
  <div>
    <input type="hidden" value="<?php echo $install_language?>" name="language" />
    <input type="hidden" value="1" name="chkagree" <?php echo isset($_POST['chkagree']) ? 'checked="checked" ':""; ?>/>
    <input type="hidden" value="<?php echo $installMode ?>" name="installmode" />
  </div>
  <p class="title"><?php echo $_lang['connection_screen_connection_information']?></p>
  <p class="subtitle"><?php echo $_lang['connection_screen_server_connection_information']?></p>
  <p><?php echo $_lang['connection_screen_server_connection_note']?></p>
  <div class="labelHolder"><label for="databasehost"><?php echo $_lang['connection_screen_database_host']?></label>
    <input id="databasehost" value="<?php echo isset($_POST['databasehost']) ? $_POST['databasehost']: $database_server ?>" name="databasehost" />
  </div>
  <div class="labelHolder"><label for="databaseloginname"><?php echo $_lang['connection_screen_database_login']?></label>
    <input id="databaseloginname" name="databaseloginname" value="<?php echo isset($_POST['databaseloginname']) ? $_POST['databaseloginname']: "" ?>" />
  </div>
  <div class="labelHolder"><label for="databaseloginpassword"><?php echo $_lang['connection_screen_database_pass']?></label>
    <input id="databaseloginpassword" type="password" name="databaseloginpassword" value="<?php echo isset($_POST['databaseloginpassword']) ? $_POST['databaseloginpassword']: "" ?>" />
  </div>
  <div class="clickHere"><a id="servertest" href="#"><?php echo $_lang['connection_screen_server_test_connection']?></a></div>
  <div class="status" id="serverstatus"></div>
  <p class="subtitle"><?php echo $_lang['connection_screen_database_connection_information']?></p>
  <div class="labelHolder"><label for="database_name"><?php echo $_lang['connection_screen_database_name']?></label>
    <input id="database_name" value="<?php echo isset($_POST['database_name']) ? $_POST['database_name']: $database_name ?>" name="database_name" />
  </div>
  <div class="labelHolder"><label for="tableprefix"><?php echo $_lang['connection_screen_table_prefix']?></label>
    <input id="tableprefix" value="<?php echo isset($_POST['tableprefix']) ? $_POST['tableprefix']: $table_prefix ?>" name="tableprefix" />
  </div>
<?php if ($installMode == 0) { ?>
  <div class="labelHolder"><label for="database_collation"><?php echo $_lang['connection_screen_collation']?></label>
    <div id="collation" name="collation"><select id="database_collation" name="database_collation">
        <option value="<?php echo isset($_POST['database_collation']) ? $_POST['database_collation']: $database_collation ?>" selected >
          <?php echo isset($_POST['database_collation']) ? $_POST['database_collation']: $database_collation ?>
        </option>
    </select></div>
  </div>
<?php } else { ?>
  <div class="labelHolder"><label for="database_connection_charset"><?php echo $_lang['connection_screen_character_set']?></label> 
    <input id="database_connection_charset" value="<?php echo isset($_POST['database_connection_charset']) ? $_POST['database_connection_charset']: $database_connection_charset ?>" name="database_connection_charset" />
  </div>
<?php } ?>
  <div class="clickHere"><a id="databasetest" href="#"><?php echo $_lang['connection_screen_database_test_connection']?></a></div>
  <div class="status" id="databasestatus"></div>
<?php
  if ($installMode == 0) {
?>
  <div id="AUH">
    <p class="title"><?php echo $_lang['connection_screen_default_admin_user']?></p>
    <p><?php echo $_lang['connection_screen_default_admin_note']?></p>
    <div class="labelHolder"><label for="cmsadmin"><?php echo $_lang['connection_screen_default_admin_login']?></label>
      <input id="cmsadmin" value="<?php echo isset($_POST['cmsadmin']) ? $_POST['cmsadmin']:"admin" ?>" name="cmsadmin" />
    </div>
    <div class="labelHolder"><label for="cmsadminemail"><?php echo $_lang['connection_screen_default_admin_email']?></label>
      <input id="cmsadminemail" value="<?php echo isset($_POST['cmsadminemail']) ? $_POST['cmsadminemail']:"" ?>" name="cmsadminemail" />
    </div>
    <div class="labelHolder"><label for="cmspassword"><?php echo $_lang['connection_screen_default_admin_password']?></label>
      <input id="cmspassword" type="password" name="cmspassword" value="<?php echo isset($_POST['cmspassword']) ? $_POST['cmspassword']:"" ?>" />
    </div>
    <div class="labelHolder"><label for="cmspasswordconfirm"><?php echo $_lang['connection_screen_default_admin_password_confirm']?></label>
      <input id="cmspasswordconfirm" type="password" name="cmspasswordconfirm" value="<?php echo isset($_POST['cmspasswordconfirm']) ? $_POST['cmspasswordconfirm']:"" ?>" />
    </div>
  </div>
  <br />
<?php
  }
?>
  <br />
  <div id="navbar">
    <input type="submit" value="<?php echo $_lang['btnnext_value']?>" name="cmdnext" style="float:right;width:100px;" onclick="if (validate()) this.form.submit(); return false;" />
    <span style="float:right">&nbsp;</span>
    <input type="submit" value="<?php echo $_lang['btnback_value']?>" name="cmdback" style="float:right;width:100px;" onclick="this.form.action='index.php?action=mode';this.form.submit();return false;" />
  </div>
</form>
<script type="text/javascript">
/* <![CDATA[ */
  // validate
  function validate() {
    var f = document.install;
    if(f.databasehost.value=="") {
      alert("<?php echo $_lang['alert_enter_host']?>");
      f.databasehost.focus();
      return false;
    }
    if(f.databaseloginname.value=="") {
      alert("<?php echo $_lang['alert_enter_login']?>");
      f.databaseloginname.focus();
      return false;
    }
    ss = document.getElementById('serverstatus');
    ssv = ss.innerHTML;
    if(ssv.length==0) {
      alert("<?php echo $_lang['alert_server_test_connection']?>");
      return false;
    }
    if (ssv.indexOf("failed") >=0) {
      alert("<?php echo $_lang['alert_server_test_connection_failed']?>");
      return false;
    }   
    if(f.database_name.value=="") {
      alert("<?php echo $_lang['alert_enter_database_name']?>");
      f.database_name.focus();
      return false;
    }
    var alpha = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    if(alpha.indexOf(f.tableprefix.value.charAt(0),0) == -1) {
      alert("<?php echo $_lang['alert_table_prefixes']?>");
      f.tableprefix.focus();
      return false;
    }
    dbs = document.getElementById('databasestatus');
    dbsv = dbs.innerHTML;
    if(dbsv.length==0) {
      alert("<?php echo $_lang['alert_database_test_connection']?>");
      return false;
    }
    if (dbsv.indexOf("failed") >=0) {
      alert("<?php echo $_lang['alert_database_test_connection_failed']?>");
      return false;
    }   
    if(f.cmsadmin.value=="") {
      alert("<?php echo $_lang['alert_enter_adminlogin']?>");
      f.cmsadmin.focus();
      return false;
    }
    if(f.cmspassword.value=="") {
      alert("<?php echo $_lang['alert_enter_adminpassword']?>");
      f.cmspassword.focus();
      return false;
    }
    if(f.cmspassword.value!=f.cmspasswordconfirm.value) {
      alert("<?php echo $_lang['alert_enter_adminconfirm']?>");
      f.cmspassword.focus();
      return false;
    }
    return true;
  }
  /* ]]> */
</script>