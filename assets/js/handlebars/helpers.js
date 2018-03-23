(function (Handlebars) {
    Handlebars.registerHelper('stripText', function(str, len){
        str.replace(/<\/?[^>]+>/gi, '');
        if (str.length > len) str = str.slice(0,len) + '...';
        return str;
    });
    Handlebars.registerHelper('bytesToSize', function(bytes){
        if(bytes <= 0) return '0 B';
        var k = 1024;
        var sizes = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
        var i = Math.floor(Math.log(bytes) / Math.log(k));
        return (bytes / Math.pow(k, i)).toFixed(2) + ' ' + sizes[i];
    });
    Handlebars.registerHelper('ifCond', function(v1, v2, options) {
        if(v1 === v2) {
            return options.fn(this);
        }
        return options.inverse(this);
    });
})(Handlebars);
