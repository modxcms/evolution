/*
 *	SearchForm 
 *	Snippet to search the site.
 *
 */

 
$searchString = 
isset($_POST['search']) && 
$_POST['search']!= "{{" && 
$_POST['search']!= "[[" && 
$_POST['search']!= "[(" && 
$_POST['search']!= "[~" && 
$_POST['search']!= "[*" ?
$_POST['search'] : "" ;


$SearchForm .= '<form name="SearchForm" action="" method="post">'; 
$SearchForm .= '<input type="text" name="search" class="text" value="'.$searchString.'"><br />'; 
$SearchForm .= '<input type="submit" name="sub" class="button" value="Search">'; 
$SearchForm .= '</form>'; 

if(isset($_POST['search']) && $_POST['search']!='') { 
   $search = explode(" ", $_POST['search']); 
   $tbl = $modx->dbConfig['dbase'].".".$modx->dbConfig['table_prefix']."site_content";
   $sql = "SELECT id, pagetitle, parent, description FROM $tbl WHERE ($tbl.content LIKE '%".$search[0]."%'"; 
   for ($x=1;$x < count($search); $x++) { 
       $sql .= " AND $tbl.content like '%$search[$x]%'"; 
   } 
   $sql .= " OR $tbl.pagetitle LIKE '%".$search[0]."%' "; 
   for ($x=1;$x < count($search); $x++) { 
       $sql .= " AND $tbl.pagetitle like '%$search[$x]%'"; 
   } 
   $sql .= " OR $tbl.description LIKE '%".$search[0]."%' "; 
   for ($x=1;$x < count($search); $x++) { 
       $sql .= " AND $tbl.description like '%$search[$x]%'"; 
   } 
   $sql .= ") AND $tbl.published = 1 AND $tbl.searchable=1 AND $tbl.deleted=0;"; 
   $rs = $modx->dbQuery($sql); 
   $limit = $modx->recordCount($rs); 
   if($limit>0) { 
      $SearchForm .= "<p>The following results were found:</p><p><table cellspacing='0' cellpadding='0'>"; 
      for ($y = 0; $y < $limit; $y++) { 
         $SearchFormsrc=$modx->fetchRow($rs); 
         $SearchForm.="<tr><td style='padding: 1px'><a href='[~".$SearchFormsrc['id']."~]'><b>".$SearchFormsrc['pagetitle']."</b></a></td><td style='padding: 1px'>"; 
         $SearchForm.=$SearchFormsrc['description']!='' ? " - <small>".$SearchFormsrc['description']."</small>" : "" ; 
         $SearchForm .= "</td></tr>";
      } 
      $SearchForm .= "</table>";
   } else { 
      $SearchForm.="<p>Sorry, couldn't find anything!</p>"; 
   } 
} 

return $SearchForm;