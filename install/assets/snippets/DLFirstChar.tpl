//<?php
/**
 * DLFirstChar
 *
 * Группировка документов по первой букве
 *
 * @category 	snippet
 * @version 	1
 * @license 	http://www.gnu.org/copyleft/gpl.html GNU Public License (GPL)
 * @internal	@properties
 * @internal	@modx_category Content
 *
 * 		[[DLFirstChar?
 *   		&documents=`2,4,23,3`
 *     		&idType=`documents`
 *       	&tpl=`@CODE:[+CharSeparator+][+OnNewChar+]<span class="brand_name"><a href="[+url+]">[+pagetitle+]</a></span><br />`
 *        	&tplOnNewChar=`@CODE:<div class="block"><strong class="bukva">[+char+]</strong> ([+total+])</div>`
 *         	&tplCharSeparator=`@CODE:</div>`
 *          &orderBy=`BINARY pagetitle ASC`
 *      ]]
 */

return require MODX_BASE_PATH.'assets/snippets/DocLister/snippet.DLFirstChar.php';