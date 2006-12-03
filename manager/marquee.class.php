<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>mooMarquee Development Test</title>
<script src="media/script/mootools/mootools.js" type="text/javascript"></script>
<script src="media/script/mootools/moodx.js" type="text/javascript"></script>
<style type="text/css">
#scrollingContainer{
	width:170px;	/* 170 pixels in width */
	height:250px;	/* Height of box */
	
	border:1px solid #000;	/* Black border around box */
	background-color: #E2EBED;	/* Light blue background color */

	padding:2px;	/* A little bit of space between border of box and text inside */
	float:left;	/* I want the text to wrap around the box */
	margin-right:10px;	/* Right margin of 10 pixels */
	font-size:0.9em;	/* Smaller font size than the rest of the page */
	overflow:hidden;	/* Hide overflow content */
}
</style>
	
<script type="text/javascript">
var MooMarquee = new Class({
	initialize: function(id, speed){
		this.slideSpeed = speed;
		this.origSlideSpeed = this.slideSpeed;
		this.scrollingContainer = id;
		this.scrollingContent = this.scrollingContainer.getFirst();
		
		this.scrollingContainer.setStyle('position', 'relative');
		this.scrollingContainer.setStyle('overflow', 'hidden');
		this.scrollingContent.setStyle('position', 'relative');
		this.scrollingContent.setStyle('top', '0px');

		this.marquee = this.slideContent.pass([this.scrollingContent,this.scrollingContainer,this.slideSpeed]).delay(30);
		this.scrollingContainer.addEvent('mouseover', function(event){
			this.marquee = $clear(this.marquee);
		});
		this.scrollingContainer.addEvent('mouseout', function(event){
			this.marquee = this.slideContent.pass([this.scrollingContent,this.scrollingContainer,this.slideSpeed]).delay(30);
		});

	 	// try implementing an FX method with the clearTimer function.
	},
	
	slideContent: function(){
		var topPos = this.scrollingContent.getStyle('top').toInt();
		topPos = topPos - this.slideSpeed;
		if(topPos/1 + (this.scrollingContent.offsetHeight)/1<0){
			topPos = this.scrollingContainer.clientHeight;
		}
		this.scrollingContent.setStyle('top', topPos + 'px')
		this.slideContent.pass([this.scrollingContent,this.scrollingContainer,this.slideSpeed]).delay(30);
	}
});

Window.onDomReady( function(){
	var marqueeTest = new MooMarquee($('scrollingContainer'), 3);
});
</script>
</head>
<body>
	<div id="scrollingContainer">
		<div>
			<p>This is the content of my first scrolling box. The content here is just plain HTML. 
			The script has been tested in IE5+, Firefox and Opera 7+</p>
			<p>You can have unlimited number of scrolling boxes on your page.</p>

			<p>When you move your mouse over this box, the scrolling will stop.</p>
			<p>Download this and other cool scripts from <a href="http://www.dhtmlgoodies.com">dhtmlgoodies.com</a></p>	
		</div>
	</div>
</body>
</html>
