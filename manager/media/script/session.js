/*
 * Small script to keep session alive in MODX
 */
function keepMeAlive() {
    var sessionJSON = new Ajax('includes/session_keepalive.php?tok=' + document.getElementById('sessTokenInput').value + '&o=' + Math.random(), {
        method: 'get',
        onComplete: function(sessionResponse) {
            resp = Json.evaluate(sessionResponse);
            if(resp.status != 'ok') {
                window.location.href = 'index.php?a=8';
            }
        }
    }).request();
}
window.setInterval(keepMeAlive, 1000 * 600); // Update session every 10min