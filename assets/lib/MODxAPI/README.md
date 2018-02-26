MODxAPI
=========
Легковесное подобие ORM прослойки для MODX Evolution.
Изначально проектировалось как замена библиотеки DocManager, но в итоге оптимизирован код и заложен потенциал для создания обертки с произвольной логикой для любых таблиц.

Обсуждение версии resource.php
---------
http://blog.agel-nash.ru/resourse-lib.html


Пример использования
---------
http://blog.agel-nash.ru/2013/6/eform-docbilder.html

Пример интеграции в MODX Evolution
---------
* Содержимое репозитория размещается в папке **/assets/lib/MODxAPI/**
* Создается плагин на событиях **OnWebPageInit**, **OnManagerPageInit** и **OnPageNotFound** с кодом:
```php
include_once(MODX_BASE_PATH."assets/lib/MODxAPI/modResource.php");
if(!isset($modx->doc)){
 $modx->doc = new modResource($modx);
}
```
* После чего создается сниппет допустим DocInfo
```php
$id = isset($id) ? (int)$id : $modx->documentObject['id'];
$field = isset($field) ? (string)$field : 'id';
if($field == 'id'){
    $out = $id;
}else{
    if($modx->documentObject['id'] == $id){
        $out = isset($modx->documentObject[$field]) ? $modx->documentObject[$field] : '';
        if(is_array($out)){
           $out = isset($out[1]) ? $out[1] : '';
        }
    }else{
        $out = $modx->doc->edit($id)->get($field);
    }
}
return (string)$out;
```
* **Profit!** Теперь сниппет DocInfo не будет нагружать страницу повторными SQL запросами при многократном получении значений из одного и того же документа
```
<h5>[[DocInfo? &id=`6` &field=`pagetitle`]]</h5>
<img src="[[DocInfo? &id=`6` &field=`image`]]" />
```

Спасибо
---------
* bumkaka за старт этого проекта
* Dmi3yy за финансовую помощь и тестирование версии 1.0.0
* Extremum за финансовую помощь и тестирование версии 1.0.0
