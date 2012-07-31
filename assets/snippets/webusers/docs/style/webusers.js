$(document).ready(function() {
	$('.parameter').corner();
	$('.example').corner();
	$('.servicesHead').corner('top');
});

function viewTemplate(template)
{
	var url = '../Default%20Forms/' + template + '.html';
	window.open(url, template);
}

function viewTemplateAsText(template)
{
	var url = '../Default%20Forms/' + template + '.html.txt';
	window.open(url, template);
}