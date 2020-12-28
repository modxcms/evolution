<?php
return preg_replace_callback("~(\b\w+\b)~",function($m) use($wrapat) {return wordwrap($m[1],$wrapat,' ',1);},$value);
