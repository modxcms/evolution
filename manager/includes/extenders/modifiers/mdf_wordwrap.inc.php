<?php
return preg_replace_callback("~(\b\w+\b)~",function($m){return wordwrap($m[1],$this->wrapat,' ',1);},$value);
