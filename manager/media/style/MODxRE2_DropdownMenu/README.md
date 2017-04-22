# MODxRE2_ver_2

Кастомизация шаблона MODxRE2 ver2 для версий MODX выше 1.1.1

<br>
<br>

Для корректной работы, требуется внести изменения в файл \manager\includes\menu.class.inc.php

Примерно на 18 строке поменять местами переменные, чтобы получилось так

```PHP
$this->defaults = $setting + $this->defaults;
