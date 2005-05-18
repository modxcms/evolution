/*
 * FCKeditor - The text editor for internet
 * Copyright (C) 2003-2005 Frederico Caldeira Knabben
 * 
 * Licensed under the terms of the GNU Lesser General Public License:
 * 		http://www.opensource.org/licenses/lgpl-license.php
 * 
 * For further information visit:
 * 		http://www.fckeditor.net/
 * 
 * File Name: sr.js
 * 	Serbian (Cyrillic) language file.
 * 
 * File Authors:
 * 		Zoran Subić (zoran@tf.zr.ac.yu)
 */

var FCKLang =
{
// Language direction : "ltr" (left to right) or "rtl" (right to left).
Dir					: "ltr",

ToolbarCollapse		: "Collapse Toolbar",	//MISSING
ToolbarExpand		: "Expand Toolbar",	//MISSING

// Toolbar Items and Context Menu
Save				: "Сачувај",
NewPage				: "Нова страница",
Preview				: "Изглед странице",
Cut					: "Исеци",
Copy				: "Копирај",
Paste				: "Залепи",
PasteText			: "Залепи као неформатиран текст",
PasteWord			: "Залепи из Worda",
Print				: "Штампа",
SelectAll			: "Означи све",
RemoveFormat		: "Уклони форматирање",
InsertLinkLbl		: "Линк",
InsertLink			: "Унеси/измени линк",
RemoveLink			: "Уклони линк",
Anchor				: "Insert/Edit Anchor",	//MISSING
InsertImageLbl		: "Слика",
InsertImage			: "Унеси/измени слику",
InsertTableLbl		: "Табела",
InsertTable			: "Унеси/измени табелу",
InsertLineLbl		: "Линија",
InsertLine			: "Унеси хоризонталну линију",
InsertSpecialCharLbl: "Специјални карактери",
InsertSpecialChar	: "Унеси специјални карактер",
InsertSmileyLbl		: "Смајли",
InsertSmiley		: "Унеси смајлија",
About				: "О ФЦКедитору",
Bold				: "Подебљано",
Italic				: "Курзив",
Underline			: "Подвучено",
StrikeThrough		: "Прецртано",
Subscript			: "Индекс",
Superscript			: "Степен",
LeftJustify			: "Лево равнање",
CenterJustify		: "Центриран текст",
RightJustify		: "Десно равнање",
BlockJustify		: "Обострано равнање",
DecreaseIndent		: "Смањи леву маргину",
IncreaseIndent		: "Увећај леву маргину",
Undo				: "Поништи акцију",
Redo				: "Понови акцију",
NumberedListLbl		: "Набројиву листу",
NumberedList		: "Унеси/уклони набројиву листу",
BulletedListLbl		: "Ненабројива листа",
BulletedList		: "Унеси/уклони ненабројиву листу",
ShowTableBorders	: "Прикажи оквир табеле",
ShowDetails			: "Прикажи детаље",
Style				: "Стил",
FontFormat			: "Формат",
Font				: "Фонт",
FontSize			: "Величина фонта",
TextColor			: "Боја текста",
BGColor				: "Боја позадине",
Source				: "K&ocirc;д",
Find				: "Претрага",
Replace				: "Замена",
SpellCheck			: "Check Spell",	//MISSING
UniversalKeyboard	: "Universal Keyboard",	//MISSING

Form			: "Form",	//MISSING
Checkbox		: "Checkbox",	//MISSING
RadioButton		: "Radio Button",	//MISSING
TextField		: "Text Field",	//MISSING
Textarea		: "Textarea",	//MISSING
HiddenField		: "Hidden Field",	//MISSING
Button			: "Button",	//MISSING
SelectionField	: "Selection Field",	//MISSING
ImageButton		: "Image Button",	//MISSING

// Context Menu
EditLink			: "Промени линк",
InsertRow			: "Унеси ред",
DeleteRows			: "Обриши редове",
InsertColumn		: "Унеси колону",
DeleteColumns		: "Обриши колоне",
InsertCell			: "Унеси ћелије",
DeleteCells			: "Обриши ћелије",
MergeCells			: "Спој ћелије",
SplitCell			: "Раздвоји ћелије",
CellProperties		: "Особине ћелије",
TableProperties		: "Особине табеле",
ImageProperties		: "Особине слике",

AnchorProp			: "Anchor Properties",	//MISSING
ButtonProp			: "Button Properties",	//MISSING
CheckboxProp		: "Checkbox Properties",	//MISSING
HiddenFieldProp		: "Hidden Field Properties",	//MISSING
RadioButtonProp		: "Radio Button Properties",	//MISSING
ImageButtonProp		: "Image Button Properties",	//MISSING
TextFieldProp		: "Text Field Properties",	//MISSING
SelectionFieldProp	: "Selection Field Properties",	//MISSING
TextareaProp		: "Textarea Properties",	//MISSING
FormProp			: "Form Properties",	//MISSING

FontFormats			: "Normal;Formatirano;Adresa;Heading 1;Heading 2;Heading 3;Heading 4;Heading 5;Heading 6",

// Alerts and Messages
ProcessingXHTML		: "Обрађујем XHTML. Maлo стрпљења...",
Done				: "Завршио",
PasteWordConfirm	: "Текст који желите да налепите копиран је из Worda. Да ли желите да буде очишћен од формата пре лепљења?",
NotCompatiblePaste	: "Ова команда је доступна само за Интернет Екплорер од верзије 5.5. Да ли желите да налепим текст без чишћења?",
UnknownToolbarItem	: "Непозната ставка toolbara \"%1\"",
UnknownCommand		: "Непозната наредба \"%1\"",
NotImplemented		: "Наредба није имплементирана",
UnknownToolbarSet	: "Toolbar \"%1\" не постоји",

// Dialogs
DlgBtnOK			: "OK",
DlgBtnCancel		: "Oткажи",
DlgBtnClose			: "Затвори",
DlgBtnBrowseServer	: "Browse Server",	//MISSING
DlgAdvancedTag		: "Напредни тагови",
DlgOpOther			: "&lt;Other&gt;",	//MISSING

// General Dialogs Labels
DlgGenNotSet		: "&lt;није постављено&gt;",
DlgGenId			: "Ид",
DlgGenLangDir		: "Смер језика",
DlgGenLangDirLtr	: "С лева на десно (LTR)",
DlgGenLangDirRtl	: "С десна на лево (RTL)",
DlgGenLangCode		: "K&ocirc;д језика",
DlgGenAccessKey		: "Приступни тастер",
DlgGenName			: "Назив",
DlgGenTabIndex		: "Таб индекс",
DlgGenLongDescr		: "Пун опис УРЛ",
DlgGenClass			: "Stylesheet класе",
DlgGenTitle			: "Advisory наслов",
DlgGenContType		: "Advisory врста садржаја",
DlgGenLinkCharset	: "Linked Resource Charset",
DlgGenStyle			: "Стил",

// Image Dialog
DlgImgTitle			: "Особине слика",
DlgImgInfoTab		: "Инфо слике",
DlgImgBtnUpload		: "Пошаљи на сервер",
DlgImgURL			: "УРЛ",
DlgImgUpload		: "Пошаљи",
DlgImgAlt			: "Алтернативни текст",
DlgImgWidth			: "Ширина",
DlgImgHeight		: "Висина",
DlgImgLockRatio		: "Закључај однос",
DlgBtnResetSize		: "Ресетуј величину",
DlgImgBorder		: "Оквир",
DlgImgHSpace		: "HSpace",
DlgImgVSpace		: "VSpace",
DlgImgAlign			: "Равнање",
DlgImgAlignLeft		: "Лево",
DlgImgAlignAbsBottom: "Abs доле",
DlgImgAlignAbsMiddle: "Abs средина",
DlgImgAlignBaseline	: "Базно",
DlgImgAlignBottom	: "Доле",
DlgImgAlignMiddle	: "Средина",
DlgImgAlignRight	: "Десно",
DlgImgAlignTextTop	: "Врх текста",
DlgImgAlignTop		: "Врх",
DlgImgPreview		: "Изглед",
DlgImgAlertUrl		: "Унесите УРЛ слике",
DlgImgLinkTab		: "Link",	//MISSING

// Link Dialog
DlgLnkWindowTitle	: "Линк",
DlgLnkInfoTab		: "Линк инфо",
DlgLnkTargetTab		: "Мета",

DlgLnkType			: "Врста линка",
DlgLnkTypeURL		: "URL",
DlgLnkTypeAnchor	: "Сидро на овој странициц",
DlgLnkTypeEMail		: "Eлектронска пошта",
DlgLnkProto			: "Протокол",
DlgLnkProtoOther	: "&lt;друго&gt;",
DlgLnkURL			: "УРЛ",
DlgLnkAnchorSel		: "Одабери сидро",
DlgLnkAnchorByName	: "По називу сидра",
DlgLnkAnchorById	: "Пo Ид-jу елемента",
DlgLnkNoAnchors		: "&lt;Нема доступних сидра&gt;",
DlgLnkEMail			: "Адреса електронске поште",
DlgLnkEMailSubject	: "Наслов",
DlgLnkEMailBody		: "Садржај поруке",
DlgLnkUpload		: "Пошаљи",
DlgLnkBtnUpload		: "Пошаљи на сервер",

DlgLnkTarget		: "Meтa",
DlgLnkTargetFrame	: "&lt;оквир&gt;",
DlgLnkTargetPopup	: "&lt;искачући прозор&gt;",
DlgLnkTargetBlank	: "Нови прозор (_blank)",
DlgLnkTargetParent	: "Родитељски прозор (_parent)",
DlgLnkTargetSelf	: "Исти прозор (_self)",
DlgLnkTargetTop		: "Прозор на врху (_top)",
DlgLnkTargetFrameName	: "Target Frame Name",	//MISSING
DlgLnkPopWinName	: "Назив искачућег прозора",
DlgLnkPopWinFeat	: "Могућности искачућег прозора",
DlgLnkPopResize		: "Променљива величина",
DlgLnkPopLocation	: "Локација",
DlgLnkPopMenu		: "Контекстни мени",
DlgLnkPopScroll		: "Скрол бар",
DlgLnkPopStatus		: "Статусна линија",
DlgLnkPopToolbar	: "Toolbar",
DlgLnkPopFullScrn	: "Приказ преко целог екрана (ИE)",
DlgLnkPopDependent	: "Зависно (Netscape)",
DlgLnkPopWidth		: "Ширина",
DlgLnkPopHeight		: "Висина",
DlgLnkPopLeft		: "Од леве ивице екрана (пиксела)",
DlgLnkPopTop		: "Од врха екрана (пиксела)",

DlnLnkMsgNoUrl		: "Please type the link URL",	//MISSING
DlnLnkMsgNoEMail	: "Please type the e-mail address",	//MISSING
DlnLnkMsgNoAnchor	: "Please select an anchor",	//MISSING

// Color Dialog
DlgColorTitle		: "Одаберите боју",
DlgColorBtnClear	: "Обриши",
DlgColorHighlight	: "Посветли",
DlgColorSelected	: "Одабери",

// Smiley Dialog
DlgSmileyTitle		: "Унеси смајлија",

// Special Character Dialog
DlgSpecialCharTitle	: "Одаберите специјални карактер",

// Table Dialog
DlgTableTitle		: "Особине табеле",
DlgTableRows		: "Редова",
DlgTableColumns		: "Kолона",
DlgTableBorder		: "Величина оквира",
DlgTableAlign		: "Равнање",
DlgTableAlignNotSet	: "<није постављено>",
DlgTableAlignLeft	: "Лево",
DlgTableAlignCenter	: "Средина",
DlgTableAlignRight	: "Десно",
DlgTableWidth		: "Ширина",
DlgTableWidthPx		: "пиксела",
DlgTableWidthPc		: "процената",
DlgTableHeight		: "Висина",
DlgTableCellSpace	: "Ћелијски простор",
DlgTableCellPad		: "Размак ћелија",
DlgTableCaption		: "Наслов табеле",

// Table Cell Dialog
DlgCellTitle		: "Особине ћелије",
DlgCellWidth		: "Ширина",
DlgCellWidthPx		: "пиксела",
DlgCellWidthPc		: "процената",
DlgCellHeight		: "Висина",
DlgCellWordWrap		: "Дељење речи",
DlgCellWordWrapNotSet	: "<није постављено>",
DlgCellWordWrapYes	: "Да",
DlgCellWordWrapNo	: "Не",
DlgCellHorAlign		: "Водоравно равнање",
DlgCellHorAlignNotSet	: "<није постављено>",
DlgCellHorAlignLeft	: "Лево",
DlgCellHorAlignCenter	: "Средина",
DlgCellHorAlignRight: "Десно",
DlgCellVerAlign		: "Вертикално равнање",
DlgCellVerAlignNotSet	: "<није постављено>",
DlgCellVerAlignTop	: "Горње",
DlgCellVerAlignMiddle	: "Средина",
DlgCellVerAlignBottom	: "Доње",
DlgCellVerAlignBaseline	: "Базно",
DlgCellRowSpan		: "Спајање редова",
DlgCellCollSpan		: "Спајање колона",
DlgCellBackColor	: "Боја позадине",
DlgCellBorderColor	: "Боја оквира",
DlgCellBtnSelect	: "Oдабери...",

// Find Dialog
DlgFindTitle		: "Пронађи",
DlgFindFindBtn		: "Пронађи",
DlgFindNotFoundMsg	: "Тражени текст није пронађен.",

// Replace Dialog
DlgReplaceTitle			: "Замени",
DlgReplaceFindLbl		: "Пронађи:",
DlgReplaceReplaceLbl	: "Замени са:",
DlgReplaceCaseChk		: "Разликуј велика и мала слова",
DlgReplaceReplaceBtn	: "Замени",
DlgReplaceReplAllBtn	: "Замени све",
DlgReplaceWordChk		: "Упореди целе речи",

// Paste Operations / Dialog
PasteErrorPaste	: "Сигурносна подешавања Вашег претраживача не дозвољавају операције аутоматског лепљења текста. Молимо Вас да користите пречицу са тастатуре (Ctrl+V).",
PasteErrorCut	: "Сигурносна подешавања Вашег претраживача не дозвољавају операције аутоматског исецања текста. Молимо Вас да користите пречицу са тастатуре (Ctrl+X).",
PasteErrorCopy	: "Сигурносна подешавања Вашег претраживача не дозвољавају операције аутоматског копирања текста. Молимо Вас да користите пречицу са тастатуре (Ctrl+C).",

PasteAsText		: "Залепи као чист текст",
PasteFromWord	: "Залепи из Worda",

DlgPasteMsg		: "Едитор није могао да изврши аутоматско лепљење због <STRONG>сигурносних поставки</STRONG> Вашег претраживача.<BR>Молимо да залепите садржај унутар следеће површине користећи тастатурну пречицу (<STRONG>Ctrl+V</STRONG>), a затим кликните на <STRONG>OK</STRONG>.",

// Color Picker
ColorAutomatic	: "Аутоматски",
ColorMoreColors	: "Више боја...",

// Document Properties
DocProps		: "Document Properties",	//MISSING

// Anchor Dialog
DlgAnchorTitle		: "Anchor Properties",	//MISSING
DlgAnchorName		: "Anchor Name",	//MISSING
DlgAnchorErrorName	: "Please type the anchor name",	//MISSING

// Speller Pages Dialog
DlgSpellNotInDic		: "Not in dictionary",	//MISSING
DlgSpellChangeTo		: "Change to",	//MISSING
DlgSpellBtnIgnore		: "Ignore",	//MISSING
DlgSpellBtnIgnoreAll	: "Ignore All",	//MISSING
DlgSpellBtnReplace		: "Replace",	//MISSING
DlgSpellBtnReplaceAll	: "Replace All",	//MISSING
DlgSpellBtnUndo			: "Undo",	//MISSING
DlgSpellNoSuggestions	: "- No suggestions -",	//MISSING
DlgSpellProgress		: "Spell check in progress...",	//MISSING
DlgSpellNoMispell		: "Spell check complete: No misspellings found",	//MISSING
DlgSpellNoChanges		: "Spell check complete: No words changed",	//MISSING
DlgSpellOneChange		: "Spell check complete: One word changed",	//MISSING
DlgSpellManyChanges		: "Spell check complete: %1 words changed",	//MISSING

IeSpellDownload			: "Spell checker not installed. Do you want to download it now?",	//MISSING

// Button Dialog
DlgButtonText	: "Text (Value)",	//MISSING
DlgButtonType	: "Type",	//MISSING

// Checkbox and Radio Button Dialogs
DlgCheckboxName		: "Name",	//MISSING
DlgCheckboxValue	: "Value",	//MISSING
DlgCheckboxSelected	: "Selected",	//MISSING

// Form Dialog
DlgFormName		: "Name",	//MISSING
DlgFormAction	: "Action",	//MISSING
DlgFormMethod	: "Method",	//MISSING

// Select Field Dialog
DlgSelectName		: "Name",	//MISSING
DlgSelectValue		: "Value",	//MISSING
DlgSelectSize		: "Size",	//MISSING
DlgSelectLines		: "lines",	//MISSING
DlgSelectChkMulti	: "Allow multiple selections",	//MISSING
DlgSelectOpAvail	: "Available Options",	//MISSING
DlgSelectOpText		: "Text",	//MISSING
DlgSelectOpValue	: "Value",	//MISSING
DlgSelectBtnAdd		: "Add",	//MISSING
DlgSelectBtnModify	: "Modify",	//MISSING
DlgSelectBtnUp		: "Up",	//MISSING
DlgSelectBtnDown	: "Down",	//MISSING
DlgSelectBtnSetValue : "Set as selected value",	//MISSING
DlgSelectBtnDelete	: "Delete",	//MISSING

// Textarea Dialog
DlgTextareaName	: "Name",	//MISSING
DlgTextareaCols	: "Columns",	//MISSING
DlgTextareaRows	: "Rows",	//MISSING

// Text Field Dialog
DlgTextName			: "Name",	//MISSING
DlgTextValue		: "Value",	//MISSING
DlgTextCharWidth	: "Character Width",	//MISSING
DlgTextMaxChars		: "Maximum Characters",	//MISSING
DlgTextType			: "Type",	//MISSING
DlgTextTypeText		: "Text",	//MISSING
DlgTextTypePass		: "Password",	//MISSING

// Hidden Field Dialog
DlgHiddenName	: "Name",	//MISSING
DlgHiddenValue	: "Value",	//MISSING

// Bulleted List Dialog
BulletedListProp	: "Bulleted List Properties",	//MISSING
NumberedListProp	: "Numbered List Properties",	//MISSING
DlgLstType			: "Type",	//MISSING
DlgLstTypeCircle	: "Circle",	//MISSING
DlgLstTypeDisk		: "Disk",	//MISSING
DlgLstTypeSquare	: "Square",	//MISSING
DlgLstTypeNumbers	: "Numbers (1, 2, 3)",	//MISSING
DlgLstTypeLCase		: "Lowercase Letters (a, b, c)",	//MISSING
DlgLstTypeUCase		: "Uppercase Letters (A, B, C)",	//MISSING
DlgLstTypeSRoman	: "Small Roman Numerals (i, ii, iii)",	//MISSING
DlgLstTypeLRoman	: "Large Roman Numerals (I, II, III)",	//MISSING

// Document Properties Dialog
DlgDocGeneralTab	: "General",	//MISSING
DlgDocBackTab		: "Background",	//MISSING
DlgDocColorsTab		: "Colors and Margins",	//MISSING
DlgDocMetaTab		: "Meta Data",	//MISSING

DlgDocPageTitle		: "Page Title",	//MISSING
DlgDocLangDir		: "Language Direction",	//MISSING
DlgDocLangDirLTR	: "Left to Right (LTR)",	//MISSING
DlgDocLangDirRTL	: "Right to Left (RTL)",	//MISSING
DlgDocLangCode		: "Language Code",	//MISSING
DlgDocCharSet		: "Character Set Encoding",	//MISSING
DlgDocCharSetOther	: "Other Character Set Encoding",	//MISSING

DlgDocDocType		: "Document Type Heading",	//MISSING
DlgDocDocTypeOther	: "Other Document Type Heading",	//MISSING
DlgDocIncXHTML		: "Include XHTML Declarations",	//MISSING
DlgDocBgColor		: "Background Color",	//MISSING
DlgDocBgImage		: "Background Image URL",	//MISSING
DlgDocBgNoScroll	: "Nonscrolling Background",	//MISSING
DlgDocCText			: "Text",	//MISSING
DlgDocCLink			: "Link",	//MISSING
DlgDocCVisited		: "Visited Link",	//MISSING
DlgDocCActive		: "Active Link",	//MISSING
DlgDocMargins		: "Page Margins",	//MISSING
DlgDocMaTop			: "Top",	//MISSING
DlgDocMaLeft		: "Left",	//MISSING
DlgDocMaRight		: "Right",	//MISSING
DlgDocMaBottom		: "Bottom",	//MISSING
DlgDocMeIndex		: "Document Indexing Keywords (comma separated)",	//MISSING
DlgDocMeDescr		: "Document Description",	//MISSING
DlgDocMeAuthor		: "Author",	//MISSING
DlgDocMeCopy		: "Copyright",	//MISSING
DlgDocPreview		: "Preview",	//MISSING

// Templates Dialog
Templates			: "Templates",	//MISSING
DlgTemplatesTitle	: "Content Templates",	//MISSING
DlgTemplatesSelMsg	: "Please select the template to open in the editor<br>(the actual contents will be lost):",	//MISSING
DlgTemplatesLoading	: "Loading templates list. Please wait...",	//MISSING
DlgTemplatesNoTpl	: "(No templates defined)",	//MISSING

// About Dialog
DlgAboutAboutTab	: "About",	//MISSING
DlgAboutBrowserInfoTab	: "Browser Info",	//MISSING
DlgAboutVersion		: "верзија",
DlgAboutLicense		: "Лиценцирано под условима GNU Lesser General Public License",
DlgAboutInfo		: "За више информација посетите"
}