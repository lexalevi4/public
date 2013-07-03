<?php 
$fields = array( 	"login" => "admin",   // Ваш логин к сайту pyxi.ru
					"days" => 1,    // Берём варианты за 1 сутки 
					"hours" => 5,   // 5 часов
					"minutes" => 45, // и 45 минут
					"type" => 1		// аренда
					// Можно взять варианты максимум за 3 суток, больше просто не отдаст, ошибок не будет.
				);
// Отправляем запрос
$xmlres = simplexml_load_string(file_get_contents("http://pyxi.ru/xml/?".http_build_query($fields))); 	
/*
Возможные ошибки:
1. Неверный логин или ip
2. Закончилась подписка
3. Пустой ответ (Нет вариантов за промежуток)
*/

//Перебираем в цикле варианты
foreach($xmlres->flat as $current){
	
	$current -> id; 				// Уникальный номер варианта по нашей базе
	$current -> object; 			// Объект ("1к", "2к", "3к", "4к", "5к", "6к", "7к", "8к", "Комн", "Дом", "Котт")
	$current -> plan; 				// Планировка  ("см","из","Студия");
	$current -> town; 				// Город (Для области)
	$current -> street; 			// Улица
	$current -> HomeNumber;			// Номер дома
	$current -> metro;				// Метро
	$current -> to_metro;			// Расстояние в минутах
	$current -> to_metro_by;		// 0 -Пешком, 1 - Транспортом
	$current -> flour;				// Этаж
	$current -> flour_of;			// Этажность
	$current -> meb;				// Мебель  (1 - есть, 0 - нет, пусто - нет данных)
	$current -> wash;				// Стиральная машина  (1 - есть, 0 - нет, пусто - нет данных)
	$current -> freez;				// Холодильник  (1 - есть, 0 - нет, пусто - нет данных)
	$current -> sost;				// Состояние  ("Евро", "Тр. ремонта" );
	$current -> subarenda;			// Субаренда (1 - да)
	$current -> bz;					// Без залога (1 - да)
	$current -> bh;					// Без хозяев (1 - да)
	$current -> balk;				// Балкон (1 - есть)
	$current -> TV;					// Телевизор (1 - есть)
	$current -> short;				// Краткосрочный (1 - да)
	
	$current -> clean;				// Свободная продажа (1 - да)
	$current -> morgage;			// Ипотека (1 - да)
	$current -> doc;				// Документы готовы (1 - да)
	$current -> years3;				// Более 3 лет в собственности (1 - да)
	$current -> invest;				// Новостройка (1 - да)
	$current -> kvartal;			// Квартал сдачи (для новостроек) (1 - да)
	$current -> year;				// Год сдачи (для новостроек) (1 - да)
	$current -> DDU;				// ДДУ (1 - да)
	$current -> mat;				// Материал стен 
	$current -> lodzs;				// Лоджии
	$current -> balks;				// Балконы
	$current -> SU;					// Санузел
	$current -> S_zh;				// Площадь кухни
	$current -> S_zh;				// Жилая площадь
	$current -> S_ob;				// Общая площадь

	$current -> comment;			// Комментарий
	$current -> price;				// Цена
	$current -> deposit; 			// Депозит (1 - есть)
	if (isset ($current -> photo)){
		$i = 0;
		foreach($current -> photo as $photo){
			// $photo - Ссылки на фотографии. Загружаем при помощи Curl, и сохраняем в папку images, называем по id + счётчик
			$ch = curl_init ();
			curl_setopt ($ch, CURLOPT_URL, $photo);
			$fp = fopen('images/'.$current -> id.$i.'.jpg', 'wb');
			curl_setopt($ch, CURLOPT_FILE, $fp);
		    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
			curl_setopt($ch, CURLOPT_HEADER, 0);
			$result=curl_exec ($ch);
			curl_close($ch);
			fclose($fp);
			/*
			Обработка
			*/
			$i++;
		}
	}
		/*
		Ваш код для импорта в базу...
		*/
	echo $current -> id."<br />";
}
?>
