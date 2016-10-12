<script type="text/javascript" src="media/style/common/moo-datepicker/datepicker.js"></script>
<script>
    $$('input.DatePicker').each(function(el){
        new DatePicker(el,
        {
            yearOffset : [(datepicker_offset)],
            format     : '[(datetime_format)]' + ' hh:mm:00',
            dayNames   : [%dp_dayNames%],
            monthNames : [%dp_monthNames%],
            startDay   : [%dp_startDay%]
        });
    });
</script>
