<?php
/**
 * [[DLReflect?
 *    &idType=`parents`
 *    &parents=`87`
 *    &reflectType=`year`
 *    &reflectSource=`tv`
 *    &reflectField=`date`
 *    &limitBefore=`1`
 *    &limitAfter=`3`
 * ]]
 */
include_once(MODX_BASE_PATH . 'assets/snippets/DocLister/lib/DLCollection.class.php');
include_once(MODX_BASE_PATH . 'assets/lib/APIHelpers.class.php');
include_once(MODX_BASE_PATH . 'assets/snippets/DocLister/lib/DLReflect.class.php');

$params = is_array($modx->event->params) ? $modx->event->params : array();

$debug = APIHelpers::getkey($params, 'debug', 0);

/**
 * reflectType
 *        Тип фильтрации. Возможные значения:
 *            month - по месяцам (значение по умолчанию)
 *            year - по годам
 */
$reflectType = APIHelpers::getkey($params, 'reflectType', 'month');
if (!in_array($reflectType, array('year', 'month'))) {
    return '';
}

$wrapTPL = APIHelpers::getkey($params, 'wrapTPL', '@CODE: <div class="reflect-list"><ul>[+wrap+]</ul></div>');
/**
 * reflectTPL
 *        Шаблон даты. Поддерживается плейсхолдеры:
 *            [+url+] - ссылка на страницу где настроена фильтрация по документам за выбранную дату
 *            [+monthName+] - Название месяца. Плейсхолдер доступен только в режиме reflectType = month
 *            [+monthNum+] - Номер месяца с ведущим нулем (01, 02, 03, ..., 12). Плейсхолдер доступен только в режиме reflectType = month
 *            [+year+] - Год
 *            [+title+] - Дата (числовое представление месяц + год или просто год в зависиомсти от reflectType)
 *            [+reflects+] - Общее число уникальных дат, которые возможно отобразить в списке
 *            [+displayReflects+] - Число уникальных дат отображаемых в общем списке
 */
$reflectTPL = APIHelpers::getkey(
    $params,
    'reflectTPL',
    '@CODE: <li><a href="[+url+]" title="[+title+]">[+title+]</a></li>'
);
/**
 * activeReflectTPL
 *        Шаблон активной даты.
 *    Поддерживается такие же плейсхолдеры, как и в шаблоне reflectTPL
 */
$activeReflectTPL = APIHelpers::getkey($params, 'activeReflectTPL', '@CODE: <li><span>[+title+]</span></li>');

list($dateFormat, $sqlDateFormat, $reflectValidator) = DLReflect::switchReflect($reflectType, function () {
    return array('m-Y', '%m-%Y', array('DLReflect', 'validateMonth'));
}, function () {
    return array('Y', '%Y', array('DLReflect', 'validateYear'));
});
$tmp = $originalDate = date($dateFormat);

/**
 * currentReflect
 *        Текущая дата (месяц в формате 00-0000 или год в формате 0000), где:
 *            00 - Номер месяца с ведущим нулем (01, 02, 03, ..., 12)
 *            0000 - Год
 *        Если не указан в параметре, то генерируется автоматически текущая дата
 */
$currentReflect = APIHelpers::getkey($params, 'currentReflect', $tmp);
if (!call_user_func($reflectValidator, $currentYear)) {
    $currentReflect = $tmp;
}
$originalCurrentReflect = $currentReflect;

$selectCurrentReflect = APIHelpers::getkey($params, 'selectCurrentReflect', 1);
if (!$selectCurrentReflect && $currentReflect == $tmp) {
    $currentReflect = null;
}

/**
 * appendCurrentReflect
 *        Если в списке дат не встречается указанная через параметр currentReflect, то
 *        этот параметр определяет - стоит ли добавлять дату или нет
 * Возможные значения:
 *        0 - не добавлять,
 *        1 - добавлять
 * Этот параметр тесно связан с параметром activeReflect
 */
$appendCurrentReflect = APIHelpers::getkey($params, 'appendCurrentReflect', 1);

/**
 * activeReflect
 *        Дата которую выбрал пользователь.
 *
 *        Если параметр не задан, то в качестве значения по умолчанию используется значение параметра currentReflect
 *        При наличии ГЕТ параметра month/year (в зависимости от значения параметра reflectType), приоритет отдается ему
 *
 *        При отсутствии выбранной даты в общем списке дат и совпадении значений параметров currentReflect и activeReflect,
 *        дата будет автоматически добавлена в общий список. Тем самым значение параметра appendCurrentReflect будет расцениваться как 1
 * Возможные значения: Текущая дата (месяц в формате 00-0000 или год в формате 0000)
 */
$tmp = APIHelpers::getkey($params, 'activeReflect', $currentReflect);
$tmpGet = APIHelpers::getkey($_GET, $reflectType, $tmp);
if (!call_user_func($reflectValidator, $tmpGet)) {
    $activeReflect = $tmp;
    if (!call_user_func($reflectValidator, $activeReflect)) {
        $activeReflect = $currentReflect;
    }
} else {
    $activeReflect = $tmpGet;
}

/**
 * reflectSource
 *        Источник даты.
 * Возможные значения:
 *    tv: ТВ параметр
 *    content или любое другое значение: Основные параметры документа
 */
$reflectSource = APIHelpers::getkey($params, 'reflectSource', 'content');

/**
 * reflectField
 *        Имя поля из которого берется дата документа.
 * Возможные значения:
 *        Любое имя существующего ТВ параметра или поля документа
 * Значение по умолчанию:
 *        Если не указана дата публикации, то использовать дату создания документа
 *        Актуально только для таблицы site_content
 */
$reflectField = APIHelpers::getkey($params, 'reflectField', 'if(pub_date=0,createdon,pub_date)');

/**
 * targetID
 *        ID документа на котором настроена фильтрация по месяцам
 * Значение по умолчанию:
 *        ID текущего документа
 */
$targetID = APIHelpers::getkey($params, 'targetID', $modx->documentObject['id']);

/**
 * limitBefore
 *        Число элементов до месяца указанного в activeMonth параметре
 * Возможные значения: Любое число. 0 расценивается как все доступные месяцы
 * Значение по умолчанию: 0
 */
$limitBefore = (int)APIHelpers::getkey($params, 'limitBefore', 0);
/**
 * limitAfter
 *        Число элементов после месяца указанного в activeMonth параметре
 * Возможные значения: Любое число. 0 расценивается как все доступные месяцы
 * Значение по умолчанию: 0
 */
$limitAfter = (int)APIHelpers::getkey($params, 'limitAfter', 0);
$display = $limitBefore + 1 + $limitAfter;

$out = '';

$DLParams = $params;
$DLParams['debug'] = $debug;
$DLParams['api'] = 'id';
$DLParams['orderBy'] = $reflectField;
$DLParams['saveDLObject'] = 'DLAPI';
if ($reflectSource === 'tv') {
    $DLParams['tvSortType'] = 'TVDATETIME';
    $query = 'STR_TO_DATE(`dltv_' . $reflectField . "_1`.`value`,'%d-%m-%Y %H:%i:%s')";
} else {
    $DLParams['orderBy'] = $reflectField;
    $query = 'FROM_UNIXTIME(' . $reflectField . ')';
}
$DLParams['selectFields'] = 'DATE_FORMAT(' . $query . ", '" . $sqlDateFormat . "') as `id`";
$totalReflects = $modx->runSnippet('DocLister', $DLParams);
/** Получаем объект DocLister'a */
$DLAPI = $modx->getPlaceholder('DLAPI');

if ($reflectType === 'month') {
    //Загружаем лексикон с месяцами
    $DLAPI->loadLang('months');
}

/** Разбираем API ответ от DocLister'a */
$totalReflects = json_decode($totalReflects, true);
if ($totalReflects === null) {
    $totalReflects = array();
}
$totalReflects = new DLCollection($modx, $totalReflects);
$totalReflects = $totalReflects->filter(function ($el) {
    return !empty($el['id']);
});
/** Добавляем активную дату в коллекцию */
if ($activeReflect !== null) {
    $totalReflects->add(array('id' => $activeReflect), $activeReflect);
}
$hasCurrentReflect = ($totalReflects->indexOf(array('id' => $originalCurrentReflect)) !== false);

/** Добавляем текущую дату в коллекцию */
if ($appendCurrentReflect) {
    $totalReflects->add(array('id' => $originalCurrentReflect), $originalCurrentReflect);
}
/** Сортируем даты по возрастанию */
$totalReflects->sort(function ($a, $b) use ($dateFormat) {
    $aDate = DateTime::createFromFormat($dateFormat, $a['id']);
    $bDate = DateTime::createFromFormat($dateFormat, $b['id']);

    return $aDate->getTimestamp() - $bDate->getTimestamp();
})->reindex();

/** Разделяем коллекцию дат на 2 части (до текущей даты и после) */
list($lReflect, $rReflect) = $totalReflects->partition(function (
    $key,
    $val
) use (
    $activeReflect,
    $originalDate,
    $dateFormat
) {
    $aDate = DateTime::createFromFormat($dateFormat, $val['id']);
    if ($activeReflect === null) {
        $activeReflect = $originalDate;
    }
    $bDate = DateTime::createFromFormat($dateFormat, $activeReflect);

    return $aDate->getTimestamp() < $bDate->getTimestamp();
});
/** Удаляем текущую активную дату из списка дат идущих за текущим */
if ($rReflect->indexOf(array('id' => $originalCurrentReflect)) !== false) {
    $rReflect->reindex()->remove(0);
}
/** Разворачиваем в обратном порядке список дат до текущей даты */
$lReflect = $lReflect->reverse();

/** Расчитываем сколько дат из какого списка взять */
$showBefore = ($lReflect->count() < $limitBefore || empty($limitBefore)) ? $lReflect->count() : $limitBefore;
if (($rReflect->count() < $limitAfter) || empty($limitAfter)) {
    $showAfter = $rReflect->count();
    $showBefore += !empty($limitAfter) ? ($limitAfter - $rReflect->count()) : 0;
} else {
    if ($limitBefore > 0) {
        $showAfter = $limitAfter + ($limitBefore - $showBefore);
    } else {
        $showAfter = $limitAfter;
    }
}
$showBefore += (($showAfter >= $limitAfter || $limitAfter > 0) ? 0 : ($limitAfter - $showAfter));

/** Создаем новую коллекцию дат */
$outReflects = new DLCollection($modx);
/** Берем нужное число элементов с левой стороны */
$i = 0;
foreach ($lReflect as $item) {
    if ((++$i) > $showBefore) {
        break;
    }
    $outReflects->add($item['id']);
}
/** Добавляем текущую дату */
if ($activeReflect === null) {
    if (($hasCurrentReflect && !$selectCurrentReflect) || $appendCurrentReflect) {
        $outReflects->add($originalCurrentReflect);
    }
} else {
    $outReflects->add($activeReflect);
}

/** Берем оставшее число позиций с правой стороны */
$i = 0;
foreach ($rReflect as $item) {
    if ((++$i) > $showAfter) {
        break;
    }
    $outReflects->add($item['id']);
}

$sortDir = APIHelpers::getkey($params, 'sortDir', 'ASC');
/** Сортируем результатирующий список  */
$outReflects = $outReflects->sort(function ($a, $b) use ($sortDir, $dateFormat) {
    $aDate = DateTime::createFromFormat($dateFormat, $a);
    $bDate = DateTime::createFromFormat($dateFormat, $b);
    $out = false;
    switch ($sortDir) {
        case 'ASC':
            $out = $aDate->getTimestamp() - $bDate->getTimestamp();
            break;
        case 'DESC':
            $out = $bDate->getTimestamp() - $aDate->getTimestamp();
            break;
    }

    return $out;
})->reindex()->unique();

/** Применяем шаблон к каждой отображаемой дате */
foreach ($outReflects as $reflectItem) {
    $tpl = (!is_null($activeReflect) && $activeReflect == $reflectItem) ? $activeReflectTPL : $reflectTPL;

    $data = DLReflect::switchReflect($reflectType, function () use ($reflectItem, $DLAPI) {
        list($vMonth, $vYear) = explode('-', $reflectItem, 2);

        return array(
            'monthNum'  => $vMonth,
            'monthName' => $DLAPI->getMsg('months.' . (int)$vMonth),
            'year'      => $vYear,
        );
    }, function () use ($reflectItem) {
        return array(
            'year' => $reflectItem
        );
    });
    $data = array_merge(array(
        'title'           => $reflectItem,
        'url'             => $modx->makeUrl($targetID, '', http_build_query(array($reflectType => $reflectItem))),
        'reflects'        => $totalReflects->count(),
        'displayReflects' => $outReflects->count()
    ), $data);
    $out .= $DLAPI->parseChunk($tpl, $data);
}

/**
 * Заворачиваем в шаблон обертку весь список дат
 */
$out = $DLAPI->parseChunk($wrapTPL, array(
    'wrap'            => $out,
    'reflects'        => $totalReflects->count(),
    'displayReflects' => $outReflects->count()
));

/**
 * Ну и выводим стек отладки если это нужно
 */
if (isset($_SESSION['usertype']) && $_SESSION['usertype'] == 'manager') {
    $debug = $DLAPI->debug->showLog();
} else {
    $debug = '';
}

return $debug . $out;
