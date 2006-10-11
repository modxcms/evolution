// TemplateSwitcher - plugin for MODx
// sottwell@sottwell.com
// released to the Public Domain
// uses OnLoadWebDocument event
// stores template choice in cookie
// add the usecookie argument to the Configuration
// &usecookie=Use Cookie;list;no,yes;no
// default is 'no'
// set Configuration to 'yes' to use cookie
// place an HTML comment <!-- donotswitch --> in the content of pages you don't want switched
// add template=templatename to the URL

if(!strstr($modx->documentContent, "donotswitch")) { // this page is not switchable
    if (isset($_GET['template'])) {
      $overrideTemplate = $_GET['template'];
   } else if (isset($_COOKIE['template']) && $usecookie == 'yes') {
      $overrideTemplate = $_COOKIE['template']; 
    }
    if (isset($overrideTemplate)) {
      $table = $modx->getFullTableName("site_templates");
      $result = $modx->db->select("id, content",$table,"templatename = '".$overrideTemplate."'");
      if($modx->db->getRecordCount($result) == 1) {
        $row = $modx->db->getRow($result);
        $modx->documentObject['template']=$row['id'];
        $modx->documentContent = $row['content'];
      } else {
        $this->messageQuit("Error retrieving template.");
      }
      if($usecookie == 'yes') {
        setcookie("template",$overrideTemplate,time()+604800, "/", "", 0);
      }
    }
} // end if page is switchable