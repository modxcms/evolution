/**
 * jQuery ddMM.mm_ddYMap Plugin
 * @version: 1.0.2 (2013-11-15)
 * 
 * @uses jQuery 1.9.1
 * @uses $.ddMM 1.0
 *
 * @copyright 2013, DivanDesign
 * http://www.DivanDesign.biz
 */

(function($){
$.ddMM.mm_ddYMap = {
	//Параметры по умолчанию
	defaults: {
		//Нужно ли скрывать оригинальное поле
		hideField: true,
		//Ширина контейнера с картой
		width: 'auto',
		//Высота контейнера с картой
		height: 400
	},
	//Флаг сабмита поиска
//	submitSerach: false,
	//Массив id всех TV
	tvs: new Array(),
	//Загруженна ли карта
	loaded: false,
	//Инициализация карты
	init: function(elem){
		//Создаём карту
		var map = new ymaps.Map('ddYMap' + elem.id, {
	 			center: [elem.LngLat[0], elem.LngLat[1]],
				zoom: 15,
			}),
			//Контрол поиска
			serachControl = new ymaps.control.SearchControl({useMapBounds: true, noPlacemark: true, width: 400}),
			//Создаём метку
			marker = new ymaps.Placemark(
				[elem.LngLat[0], elem.LngLat[1]],
				{},
				{draggable: true}
			);
	
/*		//При сабмите поиска
		serachControl.events.add('submit', function(){
			//Укажем это
			_this.submitSerach = true;
		});*/
		
		//При выборе результата поиска
		serachControl.events.add('resultselect', function(event){
			var coords = event.originalEvent.target.getResultsArray()[0].geometry.getCoordinates();
			
			//Переместим куда надо маркер
			marker.geometry.setCoordinates(coords);
			
			//Запишем значение в оригинальное поле
			elem.$elem.val(coords[0] + ',' + coords[1]);
		});
		
		//Добавляем контролы
		map.controls
			.add('zoomControl')
			.add('typeSelector')
			.add('scaleLine')
			.add('mapTools')
			.add(serachControl);
		
		//При клике по карте меняем координаты метки
		map.events.add('click', function(event){
			var coords = event.get('coordPosition');
			
			marker.geometry.setCoordinates([coords[0], coords[1]]);
			elem.$elem.val(coords[0] + ',' + coords[1]);
		});
		
		//Перетаскивание метки
		marker.events.add('dragend', function(event){
			var markerCoord = marker.geometry.getCoordinates();
			
			elem.$elem.val(markerCoord[0] + ',' + markerCoord[1]);
		});
		
		//Добавляем метку на карту
		map.geoObjects.add(marker);
	}
};

/**
 * jQuery.fn.mm_ddYMap Plugin
 * @version 1.0 (2013-09-16)
 * 
 * @description Делает карту.
 * 
 * @uses $.ddMM.mm_ddYMap 1.0
 * 
 * Параметры передаются в виде plain object.
 * @param hideField {boolean} - Нужно ли скрывать оригинальное поле. Default: true.
 * @param width {integer; 'auto'} - Ширина контейнера с картой. Default: 'auto'.
 * @param height {integer} - Высота контейнера с картой. Default: 400.
 * 
 * @copyright 2013, DivanDesign
 * http://www.DivanDesign.biz
 */
$.fn.mm_ddYMap = function(params){
	//Обрабатываем параметры
	params = $.extend({}, $.ddMM.mm_ddYMap.defaults, params || {});
	
	//Если ширина является числом
	if ($.isNumeric(params.width)){
		//Допишем пиксели
		params.width += 'px';
	}
	
	return $(this).each(function(){
		var elem = {};
		
		//TV с координатами
		elem.$elem = $(this);
		//ID оригинальной TV
		elem.id = elem.$elem.attr('id');
		//Координаты
		elem.LngLat = elem.$elem.val();
		
		//Родитель
		var	$elemParent = elem.$elem.parents('tr:first'),
			//Запоминаем название поля
			sectionName = $elemParent.find('.warning').text(),
			//Контейнер для карты
			$sectionContainer = $('<div class="sectionHeader">' + sectionName + '</div><div class="sectionBody tmplvars"><div id="ddYMap' + elem.id + '" style="width: ' + params.width + '; height: ' + params.height + 'px; position: relative; border: 1px solid #c3c3c3;"></div></div>'),
			$YMap = $sectionContainer.find('#ddYMap' + elem.id);
	
		//Добавляем контейнер
		elem.$elem.parents('.tab-page:first').append($sectionContainer);
		
		//Скрываем родителя и разделитель
		$elemParent.hide().prev('tr').hide();
	
		//Если скрывать не надо, засовываем перед картой
		if (!params.hideField){
		 	elem.$elem.insertBefore($YMap);
		}
	
		//Если координаты не заданны, то задаём дефолт
		if ($.trim(elem.LngLat) == ''){
			elem.LngLat = '55.17725339420589,61.29035648102616';
		}
		
		//Разбиваем координаты
		elem.LngLat = elem.LngLat.split(',');
		
		//Если карта ещё не загруженна
		if (!$.ddMM.mm_ddYMap.loaded){
			//Просто запоминаем (инициализируется само при загрузке)
			$.ddMM.mm_ddYMap.tvs.push(elem);
		//Если же карта уже загружена
		}else{
			//Просто инициализируем
			$.ddMM.mm_ddYMap.init(elem);
		}
	});
};

//On document.ready
$(function(){
/*	//Самбмит главной формы
	$('#mutate').on('submit', function(){
		//Если до этого был сабмит поиска
		if ($.ddMM.mm_ddYMap.submitSerach){
			//Сбросим защёлку
			$.ddMM.mm_ddYMap.submitSerach = false;
			//Выкидываем осечку
			return false;
		}
	});*/
	
	//Глобальная инициализация карт (для солбэка от Яндекс.Карт)
	window.mm_ddYMap_init = function(){
		//Перебираем все
		for (var i = $.ddMM.mm_ddYMap.tvs.length - 1; i >= 0; i--){
			//Инициализируем карту для нужной TV
			$.ddMM.mm_ddYMap.init($.ddMM.mm_ddYMap.tvs[i]);
		}
		
		//Запоминаем, что первый раз карта уже инициализированна
		$.ddMM.mm_ddYMap.loaded = true;
	};
});
})(jQuery);