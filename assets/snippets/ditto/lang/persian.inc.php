<?php

/**
 * Filename:       assets/snippets/ditto/persian.inc.php
 * Function:       Persian/Farsi language file for Ditto.
 * Author:         The MODx Project
 * Translation:	   Mohsen.H.Z.
 * Date:           22 DEC 2006
 * Version:        1.0.0
 * MODx version:   0.9.5
*/

// NOTE: New language keys should added at the bottom of this page

$_lang['file_does_not_exist'] = " موجود نیست. لطفا فایل را چک کنید.";

$_lang['default_template'] = '
    <div class="ditto_summaryPost">
        <h3><a href="[~[+id+]~]">[+title+]</a></h3>
        <div>[+summary+]</div>
        <p>[+link+]</p>
        <div style="text-align:right;">توسط <strong>[+author+]</strong> در [+date+]</div>
    </div>
';

$_lang['blank_tpl'] = "خالی می باشد و یا تایپو در نام چانک خود دارید, لطفا چک کنید.";

$_lang['missing_placeholders_tpl'] = 'یکی از پوسته های دیتتوی شما مقادیرشان به درستی جاگذاری نشده است, لطفا قالب زیر را چک کنید: <br /><br /><hr /><br /><br />';

$_lang['missing_placeholders_tpl_2'] = '<br /><br /><hr /><br />';

$_lang['default_splitter'] = "<!-- جاگذار یا جداکن - splitter -->";

$_lang['more_text'] = "ادامه...";

$_lang['no_entries'] = '<p>هیچ نتیجه ای یافت نشد.</p>';

$_lang['date_format'] = "%d-%b-%y %H:%M";

$_lang['archives'] = "بایگانی";

$_lang['prev'] = "&lt; قبلی";

$_lang['next'] = "بعدی &gt;";

$_lang['button_splitter'] = "|";

$_lang['default_copyright'] = "[(site_name)] 2006";	

$_lang['rss_lang'] = "fa";

$_lang['debug_summarized'] = "مجموعی که انتظار اختصار آنرا داریم (summarize):";

$_lang['debug_returned'] = "<br />مجموعی که انتظار بازگشت داریم:";

$_lang['debug_retrieved_from_db'] = "تعداد کل در دیتابیس:";

$_lang['debug_sort_by'] = "چگونگی تنظیم با (sortBy):";

$_lang['debug_sort_dir'] = "مسیر یا سمت را مشخص کنید (sortDir):";

$_lang['debug_start_at'] = "شروع از";

$_lang['debug_stop_at'] = "و توقف در";

$_lang['debug_out_of'] = "خارج از";

$_lang['debug_document_data'] = "محتوای پرونده برای ";

$_lang['default_archive_template'] = "<a href=\"[~[+id+]~]\">[+title+]</a> (<span class=\"ditto_date\">[+date+]</span>)";

$_lang['invalid_class'] = "کلاس دیتتو صحیح نیست. لطفا مجددا مرور کنید.";

// New language key added 2-July-2006 to 5-July-2006

// Keys deprecated : $_lang['api_method'] and $_lang['GetAllSubDocs_method'] 

$_lang['tvs'] = "متغیرهای قالب:";

$_lang['api'] = "با استفاده از ای پی آی مدایکس 0.9.2.1";

?>