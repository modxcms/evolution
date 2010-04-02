function SetUrl(fileUrl)
{
	window.top.opener.SetUrl(fileUrl);
	window.top.close();
	window.top.opener.focus();
}
