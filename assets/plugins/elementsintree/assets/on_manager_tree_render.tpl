<@IF:[[#hasAnyPermission]]>
<@ENDIF>

<@IF:[!#hasPermission?permission=edit_template!] >
      <div class="tab-page" id="tabTemp" style="padding-left:0; padding-right:0;">
      <h2 class="tab" title="Templates">[+tabLabel_template+]</h2>
      <script type="text/javascript">treePane.addTabPage( document.getElementById( "tabTemp" ) );</script>
      [+templates+]
      <ul class="actionButtons--eit">
      <li><a href="index.php?a=19" target="main" title="[%new_template%]">[+tabLabel_create+]</a></li>
      <li><a href="javascript:reloadElementsInTree();" title="[+text_reload_title+]">[+tabLabel_refresh+]</a></li>
      </ul>
      </div>
<@ENDIF>

<@IF:[!#hasPermission?permission=edit_template!] >
      <div class="tab-page" id="tabTV" style="padding-left:0; padding-right:0;">
      <h2 class="tab" title="Template Variables">[+tabLabel_tv+]</h2>
      <script type="text/javascript">treePane.addTabPage( document.getElementById( "tabTV" ) );</script>
      [+tmplvars+]
      <ul class="actionButtons--eit">
      <li><a href="index.php?a=300" target="main" title="[%new_tmplvars%]">[+tabLabel_create+]</a></li>
      <li><a href="javascript:reloadElementsInTree();" title="[+text_reload_title+]">[+tabLabel_refresh+]</a></li>
      </ul>
      </div>
<@ENDIF>

<@IF:[!#hasPermission?permission=edit_chunk!] >
      <div class="tab-page" id="tabCH" style="padding-left:0; padding-right:0;">
      <h2 class="tab" title="Chunks">[+tabLabel_chunk+]</h2>
      <script type="text/javascript">treePane.addTabPage( document.getElementById( "tabCH" ) );</script>
      [+chunk+]
      <ul class="actionButtons--eit">
      <li><a href="index.php?a=77" target="main" title="[%new_htmlsnippet%]">[+tabLabel_create+]</a></li>
      <li><a href="javascript:reloadElementsInTree();" title="[+text_reload_title+]">[+tabLabel_refresh+]</a></li>
      </ul>
      </div>
<@ENDIF>

<@IF:[!#hasPermission?permission=edit_snippet!] >
      <div class="tab-page" id="tabSN" style="padding-left:0; padding-right:0;">
      <h2 class="tab" title="Snippets">[+tabLabel_snippet+]</h2>
      <script type="text/javascript">treePane.addTabPage( document.getElementById( "tabSN" ) );</script>
      [+snippet+]
      <ul class="actionButtons--eit">
      <li><a href="index.php?a=23" target="main" title="[%new_snippet%]">[+tabLabel_create+]</a></li>
      <li><a href="javascript:reloadElementsInTree();" title="[+text_reload_title+]">[+tabLabel_refresh+]</a></li>
      </ul>
      </div>
<@ENDIF>

<@IF:[!#hasPermission?permission=edit_plugin!] >
      <div class="tab-page" id="tabPL" style="padding-left:0; padding-right:0;">
      <h2 class="tab" title="Plugins">[+tabLabel_plugin+]</h2>
      <script type="text/javascript">treePane.addTabPage( document.getElementById( "tabPL" ) );</script>
      [+plugin+]
      <ul class="actionButtons--eit">
      <li><a href="index.php?a=101" target="main" title="[%new_plugin%]">[+tabLabel_create+]</a></li>
      <li><a href="javascript:reloadElementsInTree();" title="[+text_reload_title+]">[+tabLabel_refresh+]</a></li>
      </ul>
      </div>
<@ENDIF>

<@IF:[!#hasPermission?permission=exec_module!] >
      <div class="tab-page" id="tabMD" style="padding-left:0; padding-right:0;">
      <h2 class="tab" title="Modules">[+tabLabel_module+]</h2>
      <script type="text/javascript">treePane.addTabPage( document.getElementById( "tabMD" ) );</script>
      [+module+]
      <ul class="actionButtons--eit">
      <@IF:[!#hasPermission?permission=new_module!] ><li><a href="index.php?a=107" target="main" title="[%new_module%]">[+tabLabel_create+]</a></li><@ENDIF>
      <li><a href="javascript:reloadElementsInTree();" title="[+text_reload_title+]">[+tabLabel_refresh+]</a></li>
      </ul>
      </div>
<@ENDIF>

<@IF:[[#hasAnyPermission]]>
    <script>
        jQuery(function() {
            var context = jQuery("#treePane").nuContextMenu({
              hideAfterClick: true,
              items: ".context-menu",
              callback: function(action, element) {
                var el = jQuery(element);
                var name = el.html();
                var cm = el.closest(".context-menu");
                eitAction(name, action, cm.data("type"), cm.data("id"));
              },
              menu: [
                { name: "create",    title: "[%create_new:addslashes%]", icon: "plus", },
                { name: "edit",      title: "[%edit:addslashes%]",       icon: "edit", },
                { name: "duplicate", title: "[%duplicate:addslashes%]",  icon: "clone", },
                { name: "void" },
                { name: "delete",    title: "[%delete:addslashes%]",     icon: "trash", },
              ]
            });
        });
        
        function eitAction(name, action, type, id) {
            var actionsIds, deleteMsg;
            
            switch(type) {
                case "site_templates" :
                    actionsIds = { "create":19, "edit":16, "duplicate":96, "delete":21 }; 
                    deleteMsg = "[%confirm_delete_template:addslashes%]";
                    break;
                case "site_tmplvars" :
                    actionsIds = { "create":300, "edit":301, "duplicate":304, "delete":303 };
                    deleteMsg = "[%confirm_delete_tmplvars:addslashes%]";
                    break;
                case "site_htmlsnippets" :
                    actionsIds = { "create":77, "edit":78, "duplicate":97, "delete":80 };
                    deleteMsg = "[%confirm_delete_htmlsnippet:addslashes%]";
                    break;
                case "site_snippets" :
                    actionsIds = { "create":23, "edit":22, "duplicate":98, "delete":25 };
                    deleteMsg = "[%confirm_delete_snippet:addslashes%]";
                    break;
                case "site_plugins" :
                    actionsIds = { "create":101, "edit":102, "duplicate":105, "delete":104 };
                    deleteMsg = "[%confirm_delete_plugin:addslashes%]";
                    break;
                case "site_modules" :
                    actionsIds = { "create":107, "edit":108, "duplicate":111, "delete":110 };
                    deleteMsg = "[%confirm_delete_module:addslashes%]";
                    break;
                default :
                    alert("Unknown type");
                    return;
            }
            
            // Actions that need confirmation
            var confirmMsg = '';
            switch(action) {
                case "create" : id = false; break;
                case "edit" : break;
                case "duplicate" : confirmMsg = "[%confirm_duplicate_record:addslashes%]"; break;
                case "delete" : confirmMsg = deleteMsg; break;
            }
            
            if(confirmMsg) {
                confirmMsg += " \n \n " + name + " ("+id+")";
                if (confirm(confirmMsg) !== true) return;
            }

            if (typeof modx !== 'undefined' && modx.config.global_tabs) {
              modx.tabs({url: modx.MODX_MANAGER_URL + '?a=' + actionsIds[action] + (id ? '&id=' + id : ''), title: name})
            } else {
              top.main.document.location.href="index.php?a="+actionsIds[action]+ (id ? "&id="+id : "");
            }
        }
      </script>
<@ENDIF>
</div>
