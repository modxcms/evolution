<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
    <head>
        <title>[+lang.DM_update_title+]</title>
        <link rel="stylesheet" type="text/css" href="media/style[+theme+]/style.css" />
        <script type="text/javascript" src="media/script/mootools/mootools.js"></script>
        <script type="text/javascript" src="media/script/mootools/moodx.js"></script>
        <script type="text/javascript">
	        function reset() {
	           $('backform').submit();
	        }
        </script>
        <style type="text/css"> 
            .topdiv {
                border:0;
            } 
            .subdiv {
                border:0;
            } 
            ul, li {
                list-style:none;
            } 
        </style>
        <script type="text/javascript">parent.tree.updateTree();</script>
    </head>
    <body> 
	    <table cellpadding="0" cellspacing="0" class="actionButtons">
	        <tr>
	           <td id="Button1"><a href="#" onclick="document.location.href='index.php?a=106';"><img src="media/style/MODxCarbon/images/icons/close.gif" align="absmiddle"> [+lang.DM_close+]</a></td>
	           <td id="Button4"><a href="#" onclick="reset();"><img src="media/style[+theme+]/images/icons/cancel.gif" align="absmiddle"> [+lang.DM_cancel+]</a></td>
	        </tr>
	    </table>
	   
	    <div class="sectionHeader">[+lang.DM_update_title+]</div> 
	    <div class="sectionBody"> 
	       <p>[+update.message+]</p>
		   <form id="backform" method="post" style="display: none;">
		      <input type="submit" name="back" value="[+lang.DM_process_back+]" />
		   </form>
	    </div>
    </body>
</html>