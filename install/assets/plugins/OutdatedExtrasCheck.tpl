/**
 * OutdatedExtrasCheck
 *
 * Check for Outdated critical extras not compatible with EVO 1.4.5
 *
 * @category	plugin
 * @version     1.4.5
 * @license 	http://www.gnu.org/copyleft/gpl.html GNU Public License (GPL)
 * @package     evo
 * @author      Author: Nicola Lambathakis
 * @internal    @events OnManagerWelcomeHome
 * @internal    @properties &wdgVisibility=Show widget for:;menu;All,AdminOnly,AdminExcluded,ThisRoleOnly,ThisUserOnly;AdminOnly &ThisRole=Run only for this role:;string;;;(role id) &ThisUser=Run only for this user:;string;;;(username) &DittoVersion=Min Ditto version:;string;2.1.3 &EformVersion=Min eForm version:;string;1.4.9 &AjaxSearchVersion=Min AjaxSearch version:;string;1.12.0 &WayfinderVersion=Min Wayfinder version:;string;2.0.5 &WebLoginVersion=Min WebLogin version:;string;1.2 &WebSignupVersion=Min WebSignup version:;string;1.1.2 &WebChangePwdVersion=Min WebChangePwd version:;string;1.1.2 &BreadcrumbsVersion=Min Breadcrumbs version:;string;1.0.5 &ReflectVersion=Min Reflect version:;string;2.2 &JotVersion=Min Jot version:;string;1.1.5 &MtvVersion=Min multiTV version:;string;2.0.13 &badthemes=Outdated Manager Themes:;string;MODxRE2_DropdownMenu,MODxRE2,MODxRE,MODxCarbon,D3X,MODxFLAT,wMOD,ScienceStyle
 * @internal    @modx_category Manager and Admin
 * @internal    @installset base
 * @internal    @disabled 0
 */

require MODX_BASE_PATH . 'assets/plugins/extrascheck/plugin.OutdatedExtrasCheck.php';
