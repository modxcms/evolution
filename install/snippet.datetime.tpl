/**
 * DateTime 
 * Makes a date and time... thingy.
 *
 */
 

if(!isset($timestamp)) {
    $timestamp=time();
}

return strftime("%d-%m-%Y %H:%M:%S", $timestamp);