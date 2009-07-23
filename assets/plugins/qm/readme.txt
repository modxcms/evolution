/**
 * Qm+ — QuickManager+
 *  
 * @author      Urique Dertlian, urique@unix.am & Mikko Lammi, www.maagit.fi
 * @license     GNU General Public License (GPL), http://www.gnu.org/copyleft/gpl.html
 * @version     1.1.1 updated 21/04/2009                
 */

Description
-----------

QuickManager+ is an alternative to QuickEdit to quick access and control content from frontend.

This is a modified version of the original QuickManager plugin with extensive set of configuration parameters.
 
Qm+ is also tuned to work with ManagerManager and Inherit Selected Template plugins.

Biggest changes compared to original QuickManager:
- ThickBox support
- Many useful configuration options
- Reliable saving process
- No locked documents problem with multiple users
- Editor position fixed
- Hide control buttons on preview window  
- Full ManagerManager plugin support
- Support for Inherit Selected Template plugin
- All QuickManager own enhances are off by default, use ManagerManager plugin instead
- Many other minor changes 

Known issues:
- It's not possible to change document template. This is why only active template is visible on template dropdown list.
- Parent selection don't work at all with Qm+ due missing menu tree frame.
- Couple harmless JavaScript errors due missing menu tree which is normally present in MODx manager.


Installation
------------

1. Extract all files from package to site root.

2. Create plugin with 

Name: Qm+ 
Description: <strong>1.1</strong> Enables QuickManager support

3. Copy paste plugin code from "qm.plugin.txt".

4. Check events:

OnWebPagePrerender
OnDocFormPrerender
OnDocFormSave
OnManagerPageInit
	
5. Copy paste plugin configuration: 
    
&jqpath=Path to jQuery;text;assets/js/jquery.js &loadmanagerjq=Load jQuery in manager;list;true,false;false &loadfrontendjq=Load jQuery in front-end;list;true,false;true &loadtb=Load ThickBox in front-end;list;true,false;true &usemm=Use with ManagerManager plugin;list;true,false;true &tbwidth=ThickBox window width;int;800 &tbheight=ThickBox window height;int;500 &hidefields=Hide document fields from front-end editors;text;parent &addbutton=Show add document here button;list;true,false;true &tpltype=New document template type;list;parent,id,selected;parent &tplid=New document template id;int;3

6. Save plugin.
	
	
How to use
----------

Just login, open frontend and enjoy!


How to configure
----------------

Go to plugin configuration tab.

- Path to jQuery                                
assets/js/jquery.js  
Path to your jQuery script. Tested with jQuery 1.2.6. Newer versions are reported to work too.

- Load jQuery in manager                        
true || false  
Prevent loading jQuery twice if you are already using some other plugin which loads jQuery such as ManagerManager.

- Load jQuery in front-end                      
true || false  
Prevent loading jQuery twice if you are already using it in your site template.

- Load ThickBox in front-end                    
true || false  
Prevent loading ThickBox twice if you are already using it in your site template.

- Use with ManagerManager plugin                
true || false  
Select true especially if you are hiding document sections.

- ThickBox window width                         
800  
ThickBox window width in pixels.

- ThickBox window height                        
500  
ThickBox window width in pixels.

- Hide document fields from front-end editors   
parent  
Separare fields with commas, for example: parent,template,menuindex
Parent selection don't work at all with Qm+ due missing menu tree frame, so it should be hidden from front-end editors.
Possible fields to hide from front-end editors: content, pagetitle, longtitle, menuindex, parent, description, alias, link_attributes, introtext, template, menutitle.

- Show add document here button                 
true || false  
Define if it's possible to add documents with QuickManager.

- New document template type                    
parent || id || selected  
How to determine a new document template: 
* parent: Use parent document template
* id: Use template based on id number
* selected: Define template based on parent document "inheritTpl" template variable used by "Inherit Selected Template" plugin http://modxcms.com/extras.html?view=package/view&package=214
  You don't have to install the plugin, but you still have to have "inheritTpl" template variable on parent document with correct template id number.

- New document template id
3
Define which template id to use with new documents. Used only if new document template type is id.
