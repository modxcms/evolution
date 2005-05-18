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
 * File Name: en-uk.js
 * 	English (United Kingdom) language file.
 * 
 * File Authors:
 * 		Frederico Caldeira Knabben (fredck@fckeditor.net)
 * 		Christopher Dawes (fckeditor@dawes.id.au)
 */

var FCKLang =
{
// Language direction : "ltr" (left to right) or "rtl" (right to left).
Dir					: "ltr",

ToolbarCollapse		: "Collapse Toolbar",
ToolbarExpand		: "Expand Toolbar",

// Toolbar Items and Context Menu
Save				: "Save",
NewPage				: "New Page",
Preview				: "Preview",
Cut					: "Cut",
Copy				: "Copy",
Paste				: "Paste",
PasteText			: "Paste as plain text",
PasteWord			: "Paste from Word",
Print				: "Print",
SelectAll			: "Select All",
RemoveFormat		: "Remove Format",
InsertLinkLbl		: "Link",
InsertLink			: "Insert/Edit Link",
RemoveLink			: "Remove Link",
Anchor				: "Insert/Edit Anchor",	//MISSING
InsertImageLbl		: "Image",
InsertImage			: "Insert/Edit Image",
InsertTableLbl		: "Table",
InsertTable			: "Insert/Edit Table",
InsertLineLbl		: "Line",
InsertLine			: "Insert Horizontal Line",
InsertSpecialCharLbl: "Special Char",
InsertSpecialChar	: "Insert Special Character",
InsertSmileyLbl		: "Smiley",
InsertSmiley		: "Insert Smiley",
About				: "About FCKeditor",
Bold				: "Bold",
Italic				: "Italic",
Underline			: "Underline",
StrikeThrough		: "Strike Through",
Subscript			: "Subscript",
Superscript			: "Superscript",
LeftJustify			: "Left Justify",
CenterJustify		: "Center Justify",
RightJustify		: "Right Justify",
BlockJustify		: "Block Justify",
DecreaseIndent		: "Decrease Indent",
IncreaseIndent		: "Increase Indent",
Undo				: "Undo",
Redo				: "Redo",
NumberedListLbl		: "Numbered List",
NumberedList		: "Insert/Remove Numbered List",
BulletedListLbl		: "Bulleted List",
BulletedList		: "Insert/Remove Bulleted List",
ShowTableBorders	: "Show Table Borders",
ShowDetails			: "Show Details",
Style				: "Style",
FontFormat			: "Format",
Font				: "Font",
FontSize			: "Size",
TextColor			: "Text Color",	//MISSING
BGColor				: "Background Color",	//MISSING
Source				: "Source",
Find				: "Find",
Replace				: "Replace",
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
EditLink			: "Edit Link",
InsertRow			: "Insert Row",
DeleteRows			: "Delete Rows",
InsertColumn		: "Insert Column",
DeleteColumns		: "Delete Columns",
InsertCell			: "Insert Cell",
DeleteCells			: "Delete Cells",
MergeCells			: "Merge Cells",
SplitCell			: "Split Cell",
CellProperties		: "Cell Properties",
TableProperties		: "Table Properties",
ImageProperties		: "Image Properties",

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

FontFormats			: "Normal;Formatted;Address;Heading 1;Heading 2;Heading 3;Heading 4;Heading 5;Heading 6;Paragraph (DIV)",

// Alerts and Messages
ProcessingXHTML		: "Processing XHTML. Please wait...",
Done				: "Done",
PasteWordConfirm	: "The text you want to paste seems to be copied from Word. Do you want to clean it before pasting?",
NotCompatiblePaste	: "This command is available for Internet Explorer version 5.5 or more. Do you want to paste without cleaning?",
UnknownToolbarItem	: "Unknown toolbar item \"%1\"",
UnknownCommand		: "Unknown command name \"%1\"",
NotImplemented		: "Command not implemented",
UnknownToolbarSet	: "Toolbar set \"%1\" doesn't exist",

// Dialogs
DlgBtnOK			: "OK",
DlgBtnCancel		: "Cancel",
DlgBtnClose			: "Close",
DlgBtnBrowseServer	: "Browse Server",	//MISSING
DlgAdvancedTag		: "Advanced",
DlgOpOther			: "&lt;Other&gt;",	//MISSING

// General Dialogs Labels
DlgGenNotSet		: "&lt;not set&gt;",
DlgGenId			: "Id",
DlgGenLangDir		: "Language Direction",
DlgGenLangDirLtr	: "Left to Right (LTR)",
DlgGenLangDirRtl	: "Right to Left (RTL)",
DlgGenLangCode		: "Language Code",
DlgGenAccessKey		: "Access Key",
DlgGenName			: "Name",
DlgGenTabIndex		: "Tab Index",
DlgGenLongDescr		: "Long Description URL",
DlgGenClass			: "Stylesheet Classes",
DlgGenTitle			: "Advisory Title",
DlgGenContType		: "Advisory Content Type",
DlgGenLinkCharset	: "Linked Resource Charset",
DlgGenStyle			: "Style",

// Image Dialog
DlgImgTitle			: "Image Properties",
DlgImgInfoTab		: "Image Info",
DlgImgBtnUpload		: "Send it to the Server",
DlgImgURL			: "URL",
DlgImgUpload		: "Upload",
DlgImgAlt			: "Alternative Text",
DlgImgWidth			: "Width",
DlgImgHeight		: "Height",
DlgImgLockRatio		: "Lock Ratio",
DlgBtnResetSize		: "Reset Size",
DlgImgBorder		: "Border",
DlgImgHSpace		: "HSpace",
DlgImgVSpace		: "VSpace",
DlgImgAlign			: "Align",
DlgImgAlignLeft		: "Left",
DlgImgAlignAbsBottom: "Abs Bottom",
DlgImgAlignAbsMiddle: "Abs Middle",
DlgImgAlignBaseline	: "Baseline",
DlgImgAlignBottom	: "Bottom",
DlgImgAlignMiddle	: "Middle",
DlgImgAlignRight	: "Right",
DlgImgAlignTextTop	: "Text Top",
DlgImgAlignTop		: "Top",
DlgImgPreview		: "Preview",
DlgImgAlertUrl		: "Please type the image URL",
DlgImgLinkTab		: "Link",	//MISSING

// Link Dialog
DlgLnkWindowTitle	: "Link",
DlgLnkInfoTab		: "Link Info",
DlgLnkTargetTab		: "Target",

DlgLnkType			: "Link Type",
DlgLnkTypeURL		: "URL",
DlgLnkTypeAnchor	: "Anchor in this page",
DlgLnkTypeEMail		: "E-Mail",
DlgLnkProto			: "Protocol",
DlgLnkProtoOther	: "&lt;other&gt;",
DlgLnkURL			: "URL",
DlgLnkAnchorSel		: "Select an Anchor",
DlgLnkAnchorByName	: "By Anchor Name",
DlgLnkAnchorById	: "By Element Id",
DlgLnkNoAnchors		: "&lt;No anchors available in the document&gt;",
DlgLnkEMail			: "E-Mail Address",
DlgLnkEMailSubject	: "Message Subject",
DlgLnkEMailBody		: "Message Body",
DlgLnkUpload		: "Upload",
DlgLnkBtnUpload		: "Send it to the Server",

DlgLnkTarget		: "Target",
DlgLnkTargetFrame	: "&lt;frame&gt;",
DlgLnkTargetPopup	: "&lt;popup window&gt;",
DlgLnkTargetBlank	: "New Window (_blank)",
DlgLnkTargetParent	: "Parent Window (_parent)",
DlgLnkTargetSelf	: "Same Window (_self)",
DlgLnkTargetTop		: "Topmost Window (_top)",
DlgLnkTargetFrameName	: "Target Frame Name",	//MISSING
DlgLnkPopWinName	: "Popup Window Name",
DlgLnkPopWinFeat	: "Popup Window Features",
DlgLnkPopResize		: "Resizable",
DlgLnkPopLocation	: "Location Bar",
DlgLnkPopMenu		: "Menu Bar",
DlgLnkPopScroll		: "Scroll Bars",
DlgLnkPopStatus		: "Status Bar",
DlgLnkPopToolbar	: "Toolbar",
DlgLnkPopFullScrn	: "Full Screen (IE)",
DlgLnkPopDependent	: "Dependent (Netscape)",
DlgLnkPopWidth		: "Width",
DlgLnkPopHeight		: "Height",
DlgLnkPopLeft		: "Left Position",
DlgLnkPopTop		: "Top Position",

DlnLnkMsgNoUrl		: "Please type the link URL",
DlnLnkMsgNoEMail	: "Please type the e-mail address",
DlnLnkMsgNoAnchor	: "Please select an anchor",

// Color Dialog
DlgColorTitle		: "Select Color",	//MISSING
DlgColorBtnClear	: "Clear",	//MISSING
DlgColorHighlight	: "Highlight",	//MISSING
DlgColorSelected	: "Selected",	//MISSING

// Smiley Dialog
DlgSmileyTitle		: "Insert a Smiley",

// Special Character Dialog
DlgSpecialCharTitle	: "Select Special Character",

// Table Dialog
DlgTableTitle		: "Table Properties",
DlgTableRows		: "Rows",
DlgTableColumns		: "Columns",
DlgTableBorder		: "Border size",
DlgTableAlign		: "Alignment",
DlgTableAlignNotSet	: "<Not set>",
DlgTableAlignLeft	: "Left",
DlgTableAlignCenter	: "Centre",
DlgTableAlignRight	: "Right",
DlgTableWidth		: "Width",
DlgTableWidthPx		: "pixels",
DlgTableWidthPc		: "percent",
DlgTableHeight		: "Height",
DlgTableCellSpace	: "Cell spacing",
DlgTableCellPad		: "Cell padding",
DlgTableCaption		: "Caption",

// Table Cell Dialog
DlgCellTitle		: "Cell Properties",
DlgCellWidth		: "Width",
DlgCellWidthPx		: "pixels",
DlgCellWidthPc		: "percent",
DlgCellHeight		: "Height",
DlgCellWordWrap		: "Word Wrap",
DlgCellWordWrapNotSet	: "&lt;Not set&gt;",
DlgCellWordWrapYes	: "Yes",
DlgCellWordWrapNo	: "No",
DlgCellHorAlign		: "Horizontal Alignment",
DlgCellHorAlignNotSet	: "&lt;Not set&gt;",
DlgCellHorAlignLeft	: "Left",
DlgCellHorAlignCenter	: "Centre",
DlgCellHorAlignRight: "Right",
DlgCellVerAlign		: "Vertical Alignment",
DlgCellVerAlignNotSet	: "&lt;Not set&gt;",
DlgCellVerAlignTop	: "Top",
DlgCellVerAlignMiddle	: "Middle",
DlgCellVerAlignBottom	: "Bottom",
DlgCellVerAlignBaseline	: "Baseline",
DlgCellRowSpan		: "Rows Span",
DlgCellCollSpan		: "Columns Span",
DlgCellBackColor	: "Background Color",	//MISSING
DlgCellBorderColor	: "Border Color",	//MISSING
DlgCellBtnSelect	: "Select...",

// Find Dialog
DlgFindTitle		: "Find",
DlgFindFindBtn		: "Find",
DlgFindNotFoundMsg	: "The specified text was not found.",

// Replace Dialog
DlgReplaceTitle			: "Replace",
DlgReplaceFindLbl		: "Find what:",
DlgReplaceReplaceLbl	: "Replace with:",
DlgReplaceCaseChk		: "Match case",
DlgReplaceReplaceBtn	: "Replace",
DlgReplaceReplAllBtn	: "Replace All",
DlgReplaceWordChk		: "Match whole word",

// Paste Operations / Dialog
PasteErrorPaste	: "Your browser security settings don't permit the editor to automaticaly execute pasting operations. Please use the keyboard for that (Ctrl+V).",
PasteErrorCut	: "Your browser security settings don't permit the editor to automaticaly execute cutting operations. Please use the keyboard for that (Ctrl+X).",
PasteErrorCopy	: "Your browser security settings don't permit the editor to automaticaly execute copying operations. Please use the keyboard for that (Ctrl+C).",

PasteAsText		: "Paste as Plain Text",
PasteFromWord	: "Paste from Word",

DlgPasteMsg		: "The editor was not able to automaticaly execute pasting because of the <STRONG>security settings</STRONG> of your browser.<BR>Please paste inside the following box using the keyboard (<STRONG>Ctrl+V</STRONG>) and hit <STRONG>OK</STRONG>.",

// Color Picker
ColorAutomatic	: "Automatic",	//MISSING
ColorMoreColors	: "More Colors...",	//MISSING

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
DlgAboutVersion		: "version",
DlgAboutLicense		: "Licensed under the terms of the GNU Lesser General Public License",
DlgAboutInfo		: "For further information go to"
}