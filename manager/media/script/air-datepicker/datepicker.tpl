<link href="media/script/air-datepicker/css/datepicker.min.css" rel="stylesheet" type="text/css">
<style>.datepickers-container{z-index:99999;}</style>
<script type="text/javascript" src="media/script/air-datepicker/datepicker.min.js"></script>
<script src="media/script/air-datepicker/i18n/datepicker.[(lang_code)].js"></script>
<script type="text/javascript">

jQuery(function(){
    var dateFormat = '[(datetime_format_lc)]';
    var start = new Date();
    start.setHours(0);
    start.setMinutes(0);
    
    var options = {
        language      : '[(lang_code)]',
        timepicker    : true,
        todayButton   : new Date(),
        keyboardNav   : false,
        startDate     : start,
        autoClose     : true,
        toggleSelected: false,
        clearButton   : true,
        minutesStep   : 5,
        dateFormat    : dateFormat,
        timeFormat    : 'hh:ii',
        onSelect      : function (fd, d, picker) {
            documentDirty = true;
        },
        navTitles: {
           days: 'yyyy/mm'
        }
    };
    
    jQuery('.DatePicker').datepicker(options);
    jQuery('.DatePicker').each(function(i, elm){
        var v=jQuery(elm).val();
        if(v) {
            if(v.indexOf('-')) v = v.replace(/(\d+)\-(\d+)\-(\d+)(.*)/g , "$3/$2/$1$4");
            jQuery(elm).data('datepicker').selectDate(new Date(v));
        }
        documentDirty = false;
    });
});

</script>
