<?php
/**
 * Document Manager Module - persian.inc.php
 * 
 * Purpose: Contains the language strings for use in the module.
 * Author: Garry Nutting
 * Translator: Mohsen.H.Z.
 * For: MODx CMS (www.modxcms.com)
 * Date:29/09/2006 Version: 1.6
 * 
 */
 
//-- Persian/Farsi LANGUAGE FILE
 
//-- titles
$_lang['DM_module_title'] = 'مدیریت پرونده';
$_lang['DM_action_title'] = 'انتخاب دستورالعمل';
$_lang['DM_range_title'] = 'بازه ی شناسه ای پرونده ها را انتخاب کنید';
$_lang['DM_tree_title'] = 'انتخاب پرونده ها از درختی';
$_lang['DM_update_title'] = 'بروزرسانی انجام شد';
$_lang['DM_sort_title'] = 'ویرایش محتوای فهرست(منو)';

//-- tabs
$_lang['DM_doc_permissions'] = 'سطوح دسترسی به پرونده';
$_lang['DM_template_variables'] = 'متغیرهای پوسته یا قالب';
$_lang['DM_sort_menu'] = 'ترتیب موارد فهرست(منو)';
$_lang['DM_change_template'] = 'تغییر پوسته یا قالب';
$_lang['DM_publish'] = 'انتشار/ تعلیق';
$_lang['DM_other'] = 'سایر تنظیمات';
 
//-- buttons
$_lang['DM_close'] = 'بستن مدیریت پرونده';
$_lang['DM_cancel'] = 'بازگشت به قبل';
$_lang['DM_go'] = 'برو';
$_lang['DM_save'] = 'حفظ و ذخیره';
$_lang['DM_sort_another'] = 'تنظیم و ترتیب دهی بعدی';

//-- templates tab
$_lang['DM_tpl_desc'] = 'از جدول زیر پوسته یا قالب مورد نیاز را انتخاب کنید و سپس شناسه پرونده هایی را که بایستی تغییر داده شوند را انتخاب کنید. یا با انتخاب بازه ی شناسه های پرونده ها و یا استفاده از حالت درختی زیر.';
$_lang['DM_tpl_no_templates'] = 'پوسته یا قالبی یافت نشد';
$_lang['DM_tpl_column_id'] = 'شناسه';
$_lang['DM_tpl_column_name'] = 'نام';
$_lang['DM_tpl_column_description'] ='توضیحات';
$_lang['DM_tpl_blank_template'] = 'پوسته یا قالب خالی';

$_lang['DM_tpl_results_message']= 'در صورتیکه نازمند تغییرات بیشتر هستید از دکمه ی بازگشت به قبل استفاده کنید. ذخیره یا کش وبگاه(سایت) بصورت خودکار پاک شده است.';

//-- template variables tab
$_lang['DM_tv_desc'] = 'شناسه ی پرونده هایی را که نیازمند تغییرات هستند را مشخص کنید, یا از طریق مشخص کردن بازه ی شناسه ی پرونده ها و یا از طریق استفاده از حالت درختی زیر, سپس پوسته یا قالب مورد نیاز را از جدول انتخاب کنید تا متغیرهای مربوط به آن پوسته یا قالب اجرا شوند. مقادیر متغیرهای مورد نظر پوسته یا قالب خود را برای پردازش وارد کنید.';
$_lang['DM_tv_template_mismatch'] = 'این پرونده از پوسته یا قالب انتخابی استفاده نمی کند.';
$_lang['DM_tv_doc_not_found'] = 'این پرونده در دیتابیس موجود نمی باشد.';
$_lang['DM_tv_no_tv'] = 'هیچ متغیر پوسته یا قالبی در قالب یافت نشد.';
$_lang['DM_tv_no_docs'] = 'هیچ پرونده ای برای به روز رسانی انتخاب نشده است.';
$_lang['DM_tv_no_template_selected'] = 'هیچ پوسته یا قالبی انتخاب نشده است.';
$_lang['DM_tv_loading'] = 'مقادیر متغیرهای پوسته یا قالب در حال بازخوانی می باشد...';
$_lang['DM_tv_ignore_tv'] = 'ترتیب اثری به این متغیرها داده نشود (مقادیر را با کاما از هم جدا کنید):';
$_lang['DM_tv_ajax_insertbutton'] = 'وارد کردن';

//-- document permissions tab
$_lang['DM_doc_desc'] = 'برای اضافه یا حذف کردن گروه پرونده ها گروه پرونده های مورد نیاز را از جدول زیر انتخاب کنید. سپس شناسه ی پرونده هایی که نیازمند به تغییرات هستند را مشخص کنید. یا از طریق مشخص کردن بازه ی شناسه ها یا از طریق استفاده از حالت درختی زیر.';
$_lang['DM_doc_no_docs'] = 'هیچ گروه پرونده ای یافت نشد';
$_lang['DM_doc_column_id'] = 'شناسه';
$_lang['DM_doc_column_name'] = 'نام';
$_lang['DM_doc_radio_add'] = 'افزودن گروه پرونده ای';
$_lang['DM_doc_radio_remove'] = 'حذف گروه پرونده ای';

$_lang['DM_doc_skip_message1'] = 'پرونده ای یا شناسه ی';
$_lang['DM_doc_skip_message2'] = 'در حال حاضر جزیی از گروه پرونده ای انتخاب شده است (اسکیپ کردن یا ترتیب اثر ندادن)';

//-- sort menu tab
$_lang['DM_sort_pick_item'] = 'لطفا شاخه ی اصلی وبگاه(روت) یا پرونده ی سرگروه یا سربخشی را از درختی اصلی پرونده ها انتخاب کنید که تمایل دارید پرونده در آن مرتب قرار داده شود.'; 
$_lang['DM_sort_updating'] = 'در حال به روز رسانی ...';
$_lang['DM_sort_updated'] = 'به روز رسانی شد';
$_lang['DM_sort_nochildren'] = 'سرگروه یا سربخش هیچ زیر شاخه یا زیر بخشی ندارد';
$_lang['DM_sort_noid']='هیچ پرونده ای انتخاب نشده است. لطفا به عقب بازگشته و پرونده ای را انتخاب کنید.';

//-- other tab
$_lang['DM_other_header'] = 'انواع تنظیمات پرونده';
$_lang['DM_misc_label'] = 'تنظیمات های موجود:';
$_lang['DM_misc_desc'] = 'لطفا ابتدا تنظیماتی را از فهرست کشویی سپس حالت مورد نیاز را انتخاب کنید. لطفا توجه داشته باشید که تنها یکی از تنظیمات در آن واحد قابل تغییر است.';

$_lang['DM_other_dropdown_publish'] = 'انتشار / تعلیق';
$_lang['DM_other_dropdown_show'] = 'نمایش / مخفی کردن در فهرست(منو)';
$_lang['DM_other_dropdown_search'] = 'قابل جستجو/غیر قابل جستجو';
$_lang['DM_other_dropdown_cache'] = 'قابل ذخیره یا کش/غیر قابل ذخیره یا کش';
$_lang['DM_other_dropdown_richtext'] = 'همراه با ویرایشگر متن/بدون ویرایشگر متن';
$_lang['DM_other_dropdown_delete'] = 'حذف/بازگردانی از حالت حذف';

//-- radio button text
$_lang['DM_other_publish_radio1'] = 'انتشار'; 
$_lang['DM_other_publish_radio2'] = 'تعلیق یا عدم انتشار';
$_lang['DM_other_show_radio1'] = 'مخقی کردن از فهرست(منو)'; 
$_lang['DM_other_show_radio2'] = 'نمایش در فهرست(منو)';
$_lang['DM_other_search_radio1'] = 'قابل جستجو'; 
$_lang['DM_other_search_radio2'] = 'غیر قابل جستجو';
$_lang['DM_other_cache_radio1'] = 'قابل ذخیره یا کش'; 
$_lang['DM_other_cache_radio2'] = 'غیر قابل ذخیره یا کش';
$_lang['DM_other_richtext_radio1'] = 'ویرایشگر متن'; 
$_lang['DM_other_richtext_radio2'] = 'بدون ویرایشگر متن';
$_lang['DM_other_delete_radio1'] = 'حذف'; 
$_lang['DM_other_delete_radio2'] = 'برگردانی از حذف';

//-- adjust dates 
$_lang['DM_adjust_dates_header'] = 'تنظیم تاریخ پرونده';
$_lang['DM_adjust_dates_desc'] = 'هر یک از تنظیمات تاریخ پرونده قابل تغییر می باشد. با استفاده از حالت "مرور تقویم" تاریخ را تنظیم کنید.';
$_lang['DM_view_calendar'] = 'مرور تقویم';
$_lang['DM_clear_date'] = 'پاک کردن تاریخ';

//-- adjust authors
$_lang['DM_adjust_authors_header'] = 'انتخاب ویراستاران';
$_lang['DM_adjust_authors_desc'] = 'برای تعیین ویراستاران جدید پرونده از فهرست(منو) کشویی استفاده کنید.';
$_lang['DM_adjust_authors_createdby'] = 'ایجاد شده توسط:';
$_lang['DM_adjust_authors_editedby'] = 'ویرایش شده توسط:';
$_lang['DM_adjust_authors_noselection'] = 'بدون تغییر';

 //-- labels
$_lang['DM_date_pubdate'] = 'تاریخ انتشار:';
$_lang['DM_date_unpubdate'] = 'تاریخ تعلیق یا خروج از انتشار:';
$_lang['DM_date_createdon'] = 'ایجاد شده در تاریخ:';
$_lang['DM_date_editedon'] = 'ویرایش شده در تاریخ:';
//$_lang['DM_date_deletedon'] = 'Deleted On Date';

$_lang['DM_date_notset'] = ' (بدون تنظیم)';
//deprecated
$_lang['DM_date_dateselect_label'] = 'انتخاب تاریخ: ';

//-- document select section
$_lang['DM_select_submit'] = 'ارسال';
$_lang['DM_select_range'] = 'برای انتخاب بازه ی شناسه ی پرونده ها به عقب برگردید';
$_lang['DM_select_range_text'] = '<p><strong>کلید (که ایکس شماره ی شناسه ی پرونده است):</strong><br /><br />
							  ایکس* - اعمال تغییرات در تنظیمات این پرونده و اولین سطح از زیرشاخه و بخش<br /> 
							  ایکس** - اعمال تغییرات در تنظیمات این پرونده و کلیه ی زیرشاخه و بخش آن<br /> 
							  ایکس-ایکس2 - اعمال تغییر در این بازه از پرونده ها<br /> 
							  ایکس - اعمال تغییرات در تنظیمات یک پرونده</p> 
							  <p>مثال: 1*,4**,2-20,25 - این تنظیمات انتخاب شده را تغییر می دهد
						      برای پرونده های 1 و زیربخش های آن, پرونده ی شماره ی 4 و همه ی زیر بخش های آن, پرونده های شماره ی 2 
						      تا شماره ی 20 و پرونده ی شماره ی 25.</p>';
$_lang['DM_select_tree'] ='مرور و انتخاب پرونده ها با استفاده از درختی پرونده ها';

//-- process tree/range messages
$_lang['DM_process_noselection'] = 'هیچ انتخابی انجام نشده. ';
$_lang['DM_process_novalues'] = 'هیچ مقداری وارد نشده.';
$_lang['DM_process_limits_error'] = 'حد بالایی کمتر از حد پایین است:';
$_lang['DM_process_invalid_error'] = 'مقدار نادرست:';
$_lang['DM_process_update_success'] = 'به روز رسانی موفقیت آمیز بود, بدون خطا.';
$_lang['DM_process_update_error'] = 'بروز رسانی انجام شد اما همراه خطا انجام شد:';
$_lang['DM_process_back'] = 'عقب';

//-- manager access logging
$_lang['DM_log_template'] = 'مدیریت پرونده: پوسته ها یا قالبها تغییر داده شده.';
$_lang['DM_log_templatevariables'] = 'مدیریت پرونده: متغیرهای پوسته یا قالب تغییر داده شد.';
$_lang['DM_log_docpermissions'] ='مدیریت پرونده: سطوح دسترسی پرونده عوض شد.';
$_lang['DM_log_sortmenu']='مدیریت پرونده: عملیات روی فهرست(منو) موفقیت آمیز بود.';
$_lang['DM_log_publish']='مدیریت پرونده: تنظیمات انتشار یا تعلیق پرونده عوض شد.';
$_lang['DM_log_hidemenu']='مدیریت پرونده: تنظیمات نمایش یا مخفی کردن در فهرست(منو) عوض شد.';
$_lang['DM_log_search']='مدیریت پرونده: تنظیمات قابلیت جستجو یا عدم آن در پرونده عوض شد.';
$_lang['DM_log_cache']='مدیریت پرونده: تنظیمات ذخیره یا کش پرونده عوض شد.';
$_lang['DM_log_richtext']='مدیریت پرونده: تنظیمات استفاده یا عدم استفاده از ویرایشگر متون عوض شد.';
$_lang['DM_log_delete']='مدیریت پرونده: تنظیمات حذف/خروج از حذف پرونده عوض شد.';
$_lang['DM_log_dates']='مدیریت پرونده: تنظیمات تاریخ پرونده عوض شد.';
$_lang['DM_log_authors']='مدیریت پرونده: تنظیمات ویراستار پرونده عوض شد.';

?>
