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
 * File Name: zh.js
 * 	Chinese Traditional language file.
 * 
 * File Authors:
 * 		NetRube (NetRube@126.com)
 */

var FCKLang =
{
// Language direction : "ltr" (left to right) or "rtl" (right to left).
Dir					: "ltr",

ToolbarCollapse		: "折疊工具欄",
ToolbarExpand		: "展開工具欄",

// Toolbar Items and Context Menu
Save				: "儲存",
NewPage				: "新建",
Preview				: "預覽",
Cut					: "剪切",
Copy				: "拷貝",
Paste				: "粘貼",
PasteText			: "粘貼為無格式文本",
PasteWord			: "從 MS Word 粘貼",
Print				: "列印",
SelectAll			: "全選",
RemoveFormat		: "清除格式",
InsertLinkLbl		: "超鏈結",
InsertLink			: "插入/編輯超鏈結",
RemoveLink			: "取消超鏈結",
Anchor				: "插入/編輯錨點鏈結",
InsertImageLbl		: "圖像",
InsertImage			: "插入/編輯圖像",
InsertTableLbl		: "表格",
InsertTable			: "插入/編輯表格",
InsertLineLbl		: "水平線",
InsertLine			: "插入水平線",
InsertSpecialCharLbl: "特殊符號",
InsertSpecialChar	: "插入特殊符號",
InsertSmileyLbl		: "圖釋",
InsertSmiley		: "插入圖釋",
About				: "關於 FCKeditor",
Bold				: "加粗",
Italic				: "傾斜",
Underline			: "下劃線",
StrikeThrough		: "刪除線",
Subscript			: "下標",
Superscript			: "上標",
LeftJustify			: "左對齊",
CenterJustify		: "居中對齊",
RightJustify		: "右對齊",
BlockJustify		: "兩端對齊",
DecreaseIndent		: "減少縮進量",
IncreaseIndent		: "增加縮進量",
Undo				: "撤銷",
Redo				: "重做",
NumberedListLbl		: "編號列表",
NumberedList		: "插入/刪除編號列表",
BulletedListLbl		: "項目列表",
BulletedList		: "插入/刪除項目列表",
ShowTableBorders	: "顯示表格邊框",
ShowDetails			: "顯示詳細資料",
Style				: "樣式",
FontFormat			: "格式",
Font				: "字體",
FontSize			: "尺寸",
TextColor			: "文本顏色",
BGColor				: "背景顏色",
Source				: "代碼",
Find				: "查找",
Replace				: "替換",
SpellCheck			: "拼寫檢查",
UniversalKeyboard	: "軟鍵盤",

Form			: "表單",
Checkbox		: "核取方塊",
RadioButton		: "單選按鈕",
TextField		: "單行文本",
Textarea		: "多行文本",
HiddenField		: "隱藏域",
Button			: "按鈕",
SelectionField	: "列表/菜單",
ImageButton		: "圖像域",

// Context Menu
EditLink			: "編輯超鏈結",
InsertRow			: "插入行",
DeleteRows			: "刪除行",
InsertColumn		: "插入列",
DeleteColumns		: "刪除列",
InsertCell			: "插入單格",
DeleteCells			: "刪除單格",
MergeCells			: "合併單格",
SplitCell			: "拆分單格",
CellProperties		: "單格屬性",
TableProperties		: "表格屬性",
ImageProperties		: "圖像屬性",

AnchorProp			: "錨點鏈結屬性",
ButtonProp			: "按鈕屬性",
CheckboxProp		: "核取方塊屬性",
HiddenFieldProp		: "隱藏域屬性",
RadioButtonProp		: "單選按鈕屬性",
ImageButtonProp		: "圖像域屬性",
TextFieldProp		: "單行文本屬性",
SelectionFieldProp	: "功能表/列表屬性",
TextareaProp		: "多行文本屬性",
FormProp			: "表單屬性",

FontFormats			: "普通;帶格式的;地址;標題 1;標題 2;標題 3;標題 4;標題 5;標題 6;段落(DIV)",

// Alerts and Messages
ProcessingXHTML		: "正在處理 XHTML，請稍等...",
Done				: "完成",
PasteWordConfirm	: "您要粘貼的內容好像是來自 MS Word，是否要清除 MS Word 格式後再粘貼？",
NotCompatiblePaste	: "該命令需要 Internet Explorer 5.5 或更高版本的支持，是否按常規粘貼進行？",
UnknownToolbarItem	: "未知工具欄項目 \"%1\"",
UnknownCommand		: "未知命令名稱 \"%1\"",
NotImplemented		: "命令無法執行",
UnknownToolbarSet	: "工具欄設置 \"%1\" 不存在",

// Dialogs
DlgBtnOK			: "確定",
DlgBtnCancel		: "取消",
DlgBtnClose			: "關閉",
DlgBtnBrowseServer	: "流覽伺服器",
DlgAdvancedTag		: "進階",
DlgOpOther			: "&lt;其他&gt;",

// General Dialogs Labels
DlgGenNotSet		: "&lt;沒有設置&gt;",
DlgGenId			: "ID",
DlgGenLangDir		: "語言方向",
DlgGenLangDirLtr	: "自左到右 (LTR)",
DlgGenLangDirRtl	: "自右到左 (RTL)",
DlgGenLangCode		: "語言代碼",
DlgGenAccessKey		: "訪問鍵",
DlgGenName			: "名稱",
DlgGenTabIndex		: "Tab 鍵次序",
DlgGenLongDescr		: "詳細說明地址",
DlgGenClass			: "樣式類",
DlgGenTitle			: "標題",
DlgGenContType		: "類型",
DlgGenLinkCharset	: "編碼",
DlgGenStyle			: "樣式",

// Image Dialog
DlgImgTitle			: "圖像屬性",
DlgImgInfoTab		: "圖像",
DlgImgBtnUpload		: "發送到伺服器上",
DlgImgURL			: "源檔案",
DlgImgUpload		: "上載",
DlgImgAlt			: "替換文本",
DlgImgWidth			: "寬度",
DlgImgHeight		: "高度",
DlgImgLockRatio		: "鎖定比例",
DlgBtnResetSize		: "恢復尺寸",
DlgImgBorder		: "邊框尺寸",
DlgImgHSpace		: "水準間距",
DlgImgVSpace		: "垂直間距",
DlgImgAlign			: "對齊方式",
DlgImgAlignLeft		: "左對齊",
DlgImgAlignAbsBottom: "絕對底邊",
DlgImgAlignAbsMiddle: "絕對居中",
DlgImgAlignBaseline	: "基線",
DlgImgAlignBottom	: "底邊",
DlgImgAlignMiddle	: "居中",
DlgImgAlignRight	: "右對齊",
DlgImgAlignTextTop	: "文本上方",
DlgImgAlignTop		: "頂端",
DlgImgPreview		: "預覽",
DlgImgAlertUrl		: "請輸入圖像位址",
DlgImgLinkTab		: "Link",	//MISSING

// Link Dialog
DlgLnkWindowTitle	: "超鏈結",
DlgLnkInfoTab		: "超鏈結資訊",
DlgLnkTargetTab		: "目標",

DlgLnkType			: "超鏈結類型",
DlgLnkTypeURL		: "網址",
DlgLnkTypeAnchor	: "頁內錨點鏈結",
DlgLnkTypeEMail		: "電子郵件",
DlgLnkProto			: "協議",
DlgLnkProtoOther	: "&lt;其他&gt;",
DlgLnkURL			: "地址",
DlgLnkAnchorSel		: "選擇一個錨點",
DlgLnkAnchorByName	: "按錨點名稱",
DlgLnkAnchorById	: "按錨點 ID",
DlgLnkNoAnchors		: "&lt;此文檔沒有可用的錨點&gt;",
DlgLnkEMail			: "地址",
DlgLnkEMailSubject	: "主題",
DlgLnkEMailBody		: "內容",
DlgLnkUpload		: "上载",
DlgLnkBtnUpload		: "發送到伺服器上",

DlgLnkTarget		: "目標",
DlgLnkTargetFrame	: "&lt;框架&gt;",
DlgLnkTargetPopup	: "&lt;彈出窗口&gt;",
DlgLnkTargetBlank	: "新窗口 (_blank)",
DlgLnkTargetParent	: "父窗口 (_parent)",
DlgLnkTargetSelf	: "本窗口 (_self)",
DlgLnkTargetTop		: "整頁 (_top)",
DlgLnkTargetFrameName	: "目標框架名稱",
DlgLnkPopWinName	: "彈出視窗名稱",
DlgLnkPopWinFeat	: "彈出視窗屬性",
DlgLnkPopResize		: "調整大小",
DlgLnkPopLocation	: "地址欄",
DlgLnkPopMenu		: "菜單欄",
DlgLnkPopScroll		: "捲軸",
DlgLnkPopStatus		: "狀態欄",
DlgLnkPopToolbar	: "工具欄",
DlgLnkPopFullScrn	: "全屏 (IE)",
DlgLnkPopDependent	: "依附 (NS)",
DlgLnkPopWidth		: "寬",
DlgLnkPopHeight		: "高",
DlgLnkPopLeft		: "左",
DlgLnkPopTop		: "右",

DlnLnkMsgNoUrl		: "請輸入超鏈結位址",
DlnLnkMsgNoEMail	: "請輸入電子郵件位址",
DlnLnkMsgNoAnchor	: "請選擇一個錨點",

// Color Dialog
DlgColorTitle		: "選擇顏色",
DlgColorBtnClear	: "清除",
DlgColorHighlight	: "預覽",
DlgColorSelected	: "選擇",

// Smiley Dialog
DlgSmileyTitle		: "插入一個圖釋",

// Special Character Dialog
DlgSpecialCharTitle	: "選擇特殊符號",

// Table Dialog
DlgTableTitle		: "表格屬性",
DlgTableRows		: "行數",
DlgTableColumns		: "列數",
DlgTableBorder		: "邊框",
DlgTableAlign		: "對齊",
DlgTableAlignNotSet	: "&lt;沒有設置&gt;",
DlgTableAlignLeft	: "左對齊",
DlgTableAlignCenter	: "居中",
DlgTableAlignRight	: "右對齊",
DlgTableWidth		: "寬度",
DlgTableWidthPx		: "圖元",
DlgTableWidthPc		: "百分比",
DlgTableHeight		: "高度",
DlgTableCellSpace	: "間距",
DlgTableCellPad		: "邊距",
DlgTableCaption		: "標題",

// Table Cell Dialog
DlgCellTitle		: "單格屬性",
DlgCellWidth		: "寬度",
DlgCellWidthPx		: "圖元",
DlgCellWidthPc		: "百分比",
DlgCellHeight		: "高度",
DlgCellWordWrap		: "自動換行",
DlgCellWordWrapNotSet	: "&lt;沒有設置&gt;",
DlgCellWordWrapYes	: "是",
DlgCellWordWrapNo	: "否",
DlgCellHorAlign		: "水準對齊",
DlgCellHorAlignNotSet	: "&lt;沒有設置&gt;",
DlgCellHorAlignLeft	: "左對齊",
DlgCellHorAlignCenter	: "居中",
DlgCellHorAlignRight: "右對齊",
DlgCellVerAlign		: "垂直對齊",
DlgCellVerAlignNotSet	: "&lt;沒有設置&gt;",
DlgCellVerAlignTop	: "頂端",
DlgCellVerAlignMiddle	: "居中",
DlgCellVerAlignBottom	: "底部",
DlgCellVerAlignBaseline	: "基線",
DlgCellRowSpan		: "縱跨行數",
DlgCellCollSpan		: "橫跨列數",
DlgCellBackColor	: "背景顏色",
DlgCellBorderColor	: "邊框顏色",
DlgCellBtnSelect	: "選擇...",

// Find Dialog
DlgFindTitle		: "查找",
DlgFindFindBtn		: "查找",
DlgFindNotFoundMsg	: "指定文本沒有找到。",

// Replace Dialog
DlgReplaceTitle			: "替換",
DlgReplaceFindLbl		: "查找:",
DlgReplaceReplaceLbl	: "替換:",
DlgReplaceCaseChk		: "區分大小寫",
DlgReplaceReplaceBtn	: "替換",
DlgReplaceReplAllBtn	: "全部替換",
DlgReplaceWordChk		: "全字匹配",

// Paste Operations / Dialog
PasteErrorPaste	: "您的流覽器安全設置不允許編輯器自動執行粘貼操作，請使用鍵盤快捷鍵(Ctrl+V)來完成。",
PasteErrorCut	: "您的流覽器安全設置不允許編輯器自動執行剪切操作，請使用鍵盤快捷鍵(Ctrl+X)來完成。",
PasteErrorCopy	: "您的流覽器安全設置不允許編輯器自動執行複製操作，請使用鍵盤快捷鍵(Ctrl+C)來完成。",

PasteAsText		: "粘貼為無格式文本",
PasteFromWord	: "從 MS Word 粘貼",

DlgPasteMsg		: "因為您的流覽器編輯器 <STRONG>安全設置</STRONG> 原因，不能自動執行粘貼。<BR>請使用鍵盤快捷鍵(<STRONG>Ctrl+V</STRONG>)粘貼到下面並按 <STRONG>確定</STRONG>。",

// Color Picker
ColorAutomatic	: "自動",
ColorMoreColors	: "其他顏色...",

// Document Properties
DocProps		: "頁面屬性",

// Anchor Dialog
DlgAnchorTitle		: "命名錨點",
DlgAnchorName		: "錨點名稱",
DlgAnchorErrorName	: "請輸入錨點名稱",

// Speller Pages Dialog
DlgSpellNotInDic		: "沒有在字典裏",
DlgSpellChangeTo		: "更改為",
DlgSpellBtnIgnore		: "忽略",
DlgSpellBtnIgnoreAll	: "全部忽略",
DlgSpellBtnReplace		: "替換",
DlgSpellBtnReplaceAll	: "全部替換",
DlgSpellBtnUndo			: "撤銷",
DlgSpellNoSuggestions	: "- 沒有建議 -",
DlgSpellProgress		: "正在進行拼寫檢查...",
DlgSpellNoMispell		: "拼寫檢查完成：沒有發現拼寫錯誤",
DlgSpellNoChanges		: "拼寫檢查完成：沒有更改任何單詞",
DlgSpellOneChange		: "拼寫檢查完成：更改了一個單詞",
DlgSpellManyChanges		: "拼寫檢查完成：更改了 %1 個單詞",

IeSpellDownload			: "拼寫檢查插件還沒安裝，你是否想現在就下載？",

// Button Dialog
DlgButtonText	: "標籤(值)",
DlgButtonType	: "類型",

// Checkbox and Radio Button Dialogs
DlgCheckboxName		: "名稱",
DlgCheckboxValue	: "選定值",
DlgCheckboxSelected	: "已勾選",

// Form Dialog
DlgFormName		: "名稱",
DlgFormAction	: "動作",
DlgFormMethod	: "方法",

// Select Field Dialog
DlgSelectName		: "名稱",
DlgSelectValue		: "選定",
DlgSelectSize		: "高度",
DlgSelectLines		: "行",
DlgSelectChkMulti	: "允許多選",
DlgSelectOpAvail	: "列表值",
DlgSelectOpText		: "標籤",
DlgSelectOpValue	: "值",
DlgSelectBtnAdd		: "新增",
DlgSelectBtnModify	: "修改",
DlgSelectBtnUp		: "上移",
DlgSelectBtnDown	: "下移",
DlgSelectBtnSetValue : "設為初始化時選定",
DlgSelectBtnDelete	: "移除",

// Textarea Dialog
DlgTextareaName	: "名稱",
DlgTextareaCols	: "字元寬度",
DlgTextareaRows	: "行數",

// Text Field Dialog
DlgTextName			: "名稱",
DlgTextValue		: "值",
DlgTextCharWidth	: "字元寬度",
DlgTextMaxChars		: "最多字元數",
DlgTextType			: "類型",
DlgTextTypeText		: "文本",
DlgTextTypePass		: "密碼",

// Hidden Field Dialog
DlgHiddenName	: "名稱",
DlgHiddenValue	: "值",

// Bulleted List Dialog
BulletedListProp	: "項目列表屬性",
NumberedListProp	: "編號列表屬性",
DlgLstType			: "列表類型",
DlgLstTypeCircle	: "圓圈",
DlgLstTypeDisk		: "圓點",
DlgLstTypeSquare	: "方塊",
DlgLstTypeNumbers	: "數字 (1, 2, 3)",
DlgLstTypeLCase		: "小寫字母 (a, b, c)",
DlgLstTypeUCase		: "大寫字母 (A, B, C)",
DlgLstTypeSRoman	: "小寫羅馬數字 (i, ii, iii)",
DlgLstTypeLRoman	: "大寫羅馬數字 (I, II, III)",

// Document Properties Dialog
DlgDocGeneralTab	: "常規",
DlgDocBackTab		: "背景",
DlgDocColorsTab		: "顏色和邊距",
DlgDocMetaTab		: "Meta 資料",

DlgDocPageTitle		: "頁面標題",
DlgDocLangDir		: "語言方向",
DlgDocLangDirLTR	: "從左到右 (LTR)",
DlgDocLangDirRTL	: "從右到左 (RTL)",
DlgDocLangCode		: "語言代碼",
DlgDocCharSet		: "字元編碼",
DlgDocCharSetOther	: "其他字元編碼",

DlgDocDocType		: "文檔類型",
DlgDocDocTypeOther	: "其他文檔類型",
DlgDocIncXHTML		: "包含 XHTML 聲明",
DlgDocBgColor		: "背景顏色",
DlgDocBgImage		: "背景圖像",
DlgDocBgNoScroll	: "不滾動背景圖像",
DlgDocCText			: "文本",
DlgDocCLink			: "超鏈結",
DlgDocCVisited		: "已訪問的超鏈結",
DlgDocCActive		: "活動超鏈結",
DlgDocMargins		: "頁面邊距",
DlgDocMaTop			: "上",
DlgDocMaLeft		: "左",
DlgDocMaRight		: "右",
DlgDocMaBottom		: "下",
DlgDocMeIndex		: "頁面索引關鍵字 (用半形逗號[,]分隔)",
DlgDocMeDescr		: "頁面說明",
DlgDocMeAuthor		: "作者",
DlgDocMeCopy		: "版權",
DlgDocPreview		: "預覽",

// Templates Dialog
Templates			: "Templates",	//MISSING
DlgTemplatesTitle	: "Content Templates",	//MISSING
DlgTemplatesSelMsg	: "Please select the template to open in the editor<br>(the actual contents will be lost):",	//MISSING
DlgTemplatesLoading	: "Loading templates list. Please wait...",	//MISSING
DlgTemplatesNoTpl	: "(No templates defined)",	//MISSING

// About Dialog
DlgAboutAboutTab	: "關於",
DlgAboutBrowserInfoTab	: "流覽器資訊",
DlgAboutVersion		: "版本",
DlgAboutLicense		: "基於 GNU 通用公共許可證授權發佈 ",
DlgAboutInfo		: "要獲得更多資訊請訪問 "
}