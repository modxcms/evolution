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
	var def = $('AUH');
	//	Need to add Yellow Fade Technique or slide-down transition
	def.setStyle('display','block');
}

function setColor(){
    var col = $('database_collation');
	var sho = $('setCollation');
	
	//	Need to add Yellow Fade Technique or slide-down transition
	sho.setStyle('display','block');
	
    ss = document.getElementById('serverstatus');
    ssv = ss.innerHTML;
    if (ssv.indexOf(passed) >=0) {
        col.setStyle('background-color', '#9CCD00');
//        col.setStyle('color', '#0000CD');
		col.setStyle('font-weight','bold');
    }
}