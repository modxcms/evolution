window.addEvent('domready', function(){

    // get collation from the database server
    $('servertest').addEvent('click', function(e) {
        e = new Event(e).stop();

        var url = "connection.collation.php";

        host = $('databasehost').value;
        uid = $('databaseloginname').value;
        pwd = $('databaseloginpassword').value;
        database_collation = $('database_collation').value;
        database_connection_method = $('database_connection_method').value;
                
        var pars = Object.toQueryString({
            q: url,
            host: host,
            uid: uid,
            pwd: pwd,
            database_collation: database_collation,
            database_connection_method: database_connection_method,
            language: language
        });
         
        new Ajax(url, { postBody: pars, update: $('collation'), onComplete: testServer } ).request();
    });

    // database test
    $('databasetest').addEvent('click', function(e) {
        e = new Event(e).stop();

        var url = "connection.databasetest.php";

        host = $('databasehost').value;
        uid = $('databaseloginname').value;
        pwd = $('databaseloginpassword').value;
        database_name = $('database_name').value;
        tableprefix = $('tableprefix').value;
        database_collation = $('database_collation').value;
        database_connection_method = $('database_connection_method').value;

        var pars = Object.toQueryString({
            q: url,
            host: host,
            uid: uid,
            pwd: pwd,
            database_name: database_name,
            tableprefix: tableprefix,
            database_collation: database_collation,
            database_connection_method: database_connection_method,
            language: language,
            installMode: installMode
        });

        new Ajax(url, { postBody: pars, update: $('databasestatus'), onComplete: setDefaults } ).request();
    });

   
	Slider1 = new Fx.Slide('setCollation', {duration:477});//transition:Fx.Sine.easeOut,
	Slider1.hide();
	$('setCollation').style.backgroundColor = '#ffff00';
	$('setCollation').style.display = 'block';
	if(document.getElementById('AUH')) {
		Slider2 = new Fx.Slide('AUH', {duration:477});//transition:Fx.Sine.easeOut,
		Slider2.hide();
		$('AUH').style.display = 'block';
		$('AUH').style.backgroundColor = '#ffff00';
	}

});



function testServer(){
// get the server test status as soon as collation received
    var url = "connection.servertest.php";

    host = $('databasehost').value;
    uid = $('databaseloginname').value;
    pwd = $('databaseloginpassword').value;
        
    var pars = Object.toQueryString({
        q: url,
        host: host,
        uid: uid,
        pwd: pwd,
        language: language
    });
         
    new Ajax(url, { postBody: pars, update: $('serverstatus'), onComplete: setColor } ).request();
}

function setDefaults(){
	if($('database_pass') !== null && document.getElementById('AUH')) {
		window.Slider2.slideIn();
		var Slider2FX = new Fx.Styles('AUHMask', {duration: 997,transition: Fx.Transitions.linear});
		Slider2FX.start({'opacity':[0,1]});
		window.setTimeout("$('AUH').style.backgroundColor = '#ffffff';", 1000);
		Slider2Scroll = new Fx.Scroll(window);
		Slider2Scroll.toElement('managerlanguage_select');
	}
}

function setColor(){
	var col = $('database_collation');

	ss = document.getElementById('serverstatus');
	ssv = ss.innerHTML;
	if ($('server_pass') !== null) {
		col.setStyle('background-color', '#9CCD00');
//        col.setStyle('color', '#0000CD');
		col.setStyle('font-weight','bold');

		window.Slider1.slideIn(); //toggle the slider up and down.
		var Slider1FX = new Fx.Styles('collationMask', {duration: 997,transition: Fx.Transitions.linear});
		Slider1FX.start({'opacity':[0,1]});
		window.setTimeout("$('setCollation').style.backgroundColor = '#ffffff';", 1000);
		Slider1Scroll = new Fx.Scroll(window);
		Slider1Scroll.toElement('databasestatus');
		$('database_name').focus();
    }
}