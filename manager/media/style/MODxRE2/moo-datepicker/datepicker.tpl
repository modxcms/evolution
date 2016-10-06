<script type="text/javascript" src="media/style/MODxCarbon/moo-datepicker/datepicker.js"></script>
<script>
    var dpOffset = [(datepicker_offset)];
    var dpformat = "[(datetime_format)]" + ' hh:mm:00';
    var dpdayNames = [%dp_dayNames%];
    var dpmonthNames = [%dp_monthNames%];
    var dpstartDay = [%dp_startDay%];
    $$('input.DatePicker').each(function(el){
        new DatePicker(el, {yearOffset:dpOffset, format:dpformat, dayNames:dpdayNames, monthNames:dpmonthNames, startDay:dpstartDay});
});
    
</script>
