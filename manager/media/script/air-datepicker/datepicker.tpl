<link href="media/script/air-datepicker/css/datepicker.min.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="media/script/air-datepicker/datepicker.min.js"></script>
<script src="media/script/air-datepicker/i18n/datepicker.[(lang_code)].js"></script>
<script type="text/javascript">

var dateFormat = '[(datetime_format:strtolower)]';
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

var pub_date   = jQuery('#pub_date');
var unpub_date = jQuery('#unpub_date');

pub_date.datepicker(options);
if(pub_date.val()) {
    pub_date_val = pub_date.val();
    if(pub_date_val.indexOf('-')) pub_date_val = pub_date_val.replace(/(\d+)\-(\d+)\-(\d+)(.*)/g , "$3/$2/$1$4");
    pub_date.data('datepicker').selectDate(new Date(pub_date_val));
    documentDirty = false;
}

unpub_date.datepicker(options);
if(unpub_date.val()) {
    unpub_date_val = unpub_date.val();
    if(unpub_date_val.indexOf('-')) unpub_date_val = unpub_date_val.replace(/(\d+)\-(\d+)\-(\d+)(.*)/g , "$3/$2/$1$4");
    unpub_date.data('datepicker').selectDate(new Date(unpub_date_val));
    documentDirty = false;
}

</script>
