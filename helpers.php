<?php
/**
 * Проверяет переданную дату на соответствие формату 'ГГГГ-ММ-ДД'
 *
 * Примеры использования:
 * is_date_valid('2019-01-01'); // true
 * is_date_valid('2016-02-29'); // true
 * is_date_valid('2019-04-31'); // false
 * is_date_valid('10.10.2010'); // false
 * is_date_valid('10/10/2010'); // false
 *
 * @param string $date Дата в виде строки
 *
 * @return bool true при совпадении с форматом 'ГГГГ-ММ-ДД', иначе false
 */
function is_date_valid(string $date): bool
{
    $format_to_check = 'Y-m-d';
    $dateTimeObj = date_create_from_format($format_to_check, $date);

    return $dateTimeObj !== false && array_sum(date_get_last_errors()) === 0;
}

/**
 * Создает подготовленное выражение на основе готового SQL запроса и переданных данных
 *
 * @param $link mysqli Ресурс соединения
 * @param $sql string SQL запрос с плейсхолдерами вместо значений
 * @param array $data Данные для вставки на место плейсхолдеров
 *
 * @return mysqli_stmt Подготовленное выражение
 */
function db_get_prepare_stmt($link, $sql, $data = [])
{
    $stmt = mysqli_prepare($link, $sql);

    if ($stmt === false) {
        $errorMsg = 'Не удалось инициализировать подготовленное выражение: ' . mysqli_error($link);
        die($errorMsg);
    }

    if ($data) {
        $types = '';
        $stmt_data = [];

        foreach ($data as $value) {
            $type = 's';

            if (is_int($value)) {
                $type = 'i';
            } else {
                if (is_string($value)) {
                    $type = 's';
                } else {
                    if (is_double($value)) {
                        $type = 'd';
                    }
                }
            }

            if ($type) {
                $types .= $type;
                $stmt_data[] = $value;
            }
        }

        $values = array_merge([$stmt, $types], $stmt_data);

        $func = 'mysqli_stmt_bind_param';
        $func(...$values);

        if (mysqli_errno($link) > 0) {
            $errorMsg = 'Не удалось связать подготовленное выражение с параметрами: ' . mysqli_error($link);
            die($errorMsg);
        }
    }

    return $stmt;
}

/**
 * Возвращает корректную форму множественного числа
 * Ограничения: только для целых чисел
 *
 * Пример использования:
 * $remaining_minutes = 5;
 * echo "Я поставил таймер на {$remaining_minutes} " .
 *     get_noun_plural_form(
 *         $remaining_minutes,
 *         'минута',
 *         'минуты',
 *         'минут'
 *     );
 * Результат: "Я поставил таймер на 5 минут"
 *
 * @param int $number Число, по которому вычисляем форму множественного числа
 * @param string $one Форма единственного числа: яблоко, час, минута
 * @param string $two Форма множественного числа для 2, 3, 4: яблока, часа, минуты
 * @param string $many Форма множественного числа для остальных чисел
 *
 * @return string Рассчитанная форма множественнго числа
 */
function get_noun_plural_form(int $number, string $one, string $two, string $many): string
{
    $number = (int)$number;
    $mod10 = $number % 10;
    $mod100 = $number % 100;

    switch (true) {
        case ($mod100 >= 11 && $mod100 <= 20):
            return $many;

        case ($mod10 > 5):
            return $many;

        case ($mod10 === 1):
            return $one;

        case ($mod10 >= 2 && $mod10 <= 4):
            return $two;

        default:
            return $many;
    }
}

/**
 * Подключает шаблон, передает туда данные и возвращает итоговый HTML контент
 * @param string $name Путь к файлу шаблона относительно папки templates
 * @param array $data Ассоциативный массив с данными для шаблона
 * @return string Итоговый HTML
 */
function include_template($name, array $data = [])
{
    $name = 'templates/' . $name;

    ob_start();
    extract($data);
    require $name;

    return ob_get_clean();
}

/**
 * Функция-обертка для htmlspecialchars
 * @param string $str Конвертируемая строка
 * @return string Преобразованная строка
 */
function hsc($str) 
{
    return htmlspecialchars($str, ENT_QUOTES);
}

/**
 * Функция проверяет доступно ли видео по ссылке на youtube
 * @param string $url ссылка на видео
 *
 * @return string Ошибку если валидация не прошла
 */
function check_youtube_url($url)
{
    $id = extract_youtube_id($url);

    set_error_handler(function () {}, E_WARNING);
    $headers = get_headers('https://www.youtube.com/oembed?format=json&url=http://www.youtube.com/watch?v=' . $id);
    restore_error_handler();

    if (!is_array($headers)) {
        return "Видео по такой ссылке не найдено. Проверьте ссылку на видео";
    }

    $err_flag = strpos($headers[0], '200') ? 200 : 404;

    if ($err_flag !== 200) {
        return "Видео по такой ссылке не найдено. Проверьте ссылку на видео";
    }

    return true;
}

/**
 * Возвращает код iframe для вставки youtube видео на страницу
 * @param string $youtube_url Ссылка на youtube видео
 * @return string
 */
function embed_youtube_video($youtube_url)
{
    $res = "";
    $id = extract_youtube_id($youtube_url);

    if ($id) {
        $src = "https://www.youtube.com/embed/" . $id;
        $res = '<iframe width="760" height="400" src="' . $src . '" frameborder="0"></iframe>';
    }

    return $res;
}

/**
 * Возвращает img-тег с обложкой видео для вставки на страницу
 * @param string $youtube_url Ссылка на youtube видео
 * @return string
 */
function embed_youtube_cover($youtube_url)
{
    $res = "";
    $id = extract_youtube_id($youtube_url);

    if ($id) {
        $src = sprintf("https://img.youtube.com/vi/%s/mqdefault.jpg", $id);
        $res = '<img alt="youtube cover" width="320" height="120" src="' . $src . '" />';
    }

    return $res;
}

/**
 * Извлекает из ссылки на youtube видео его уникальный ID
 * @param string $youtube_url Ссылка на youtube видео
 * @return array
 */
function extract_youtube_id($youtube_url)
{
    $id = false;

    $parts = parse_url($youtube_url);

    if ($parts) {
        if ($parts['path'] == '/watch') {
            parse_str($parts['query'], $vars);
            $id = $vars['v'] ?? null;
        } else {
            if ($parts['host'] == 'youtu.be') {
                $id = substr($parts['path'], 1);
            }
        }
    }

    return $id;
}

/**
 * @param $index
 * @return false|string
 */
function generate_random_date($index)
{
    $deltas = [['minutes' => 59], ['hours' => 23], ['days' => 6], ['weeks' => 4], ['months' => 11]];
    $dcnt = count($deltas);

    if ($index < 0) {
        $index = 0;
    }

    if ($index >= $dcnt) {
        $index = $dcnt - 1;
    }

    $delta = $deltas[$index];
    $timeval = rand(1, current($delta));
    $timename = key($delta);

    $ts = strtotime("$timeval $timename ago");
    $dt = date('Y-m-d H:i:s', $ts);

    return $dt;
}

/**
 * Функция для сокращения текста на определённое количество символов
 * @param string $text Исходный текст
 * @param int $max_symbols Максимальное количество символов
 * @return string 
 */
function reduce_text(string $text, int $max_symbols = 300) 
{
    if (mb_strlen($text) <= $max_symbols) {
		return $text;
	}

    $words = explode(' ', $text);
    $symbol_counter = 0;
    $word_counter = 0;

    foreach($words as $word) {
        $symbol_counter += mb_strlen($word);
		if ($symbol_counter > $max_symbols) {
			break;
		}

		$symbol_counter++;
        $word_counter++;
    }

    $text = implode(' ', array_slice($words, 0, $word_counter));

    return $text . '...';
}

/**
 * Узнаём дату в виде количества прошедших с данного момента минут, часов, дней, недель или месяцев
 * @param datetime $date дата, когда произошло событие
 * @return string 
 */
function get_date_diff_from_now(datetime $date) 
{
    $date_now = date_create("now");
    $date_diff = date_diff($date_now, $date);

    $i_count = date_interval_format($date_diff, "%i");
    $h_count = date_interval_format($date_diff, "%h");
    $d_count = date_interval_format($date_diff, "%d");
    $m_count = date_interval_format($date_diff, "%m");

    $result = '';
    
    if ($m_count > 0) {
        $result = $m_count . ' ' . get_noun_plural_form($m_count, 'месяц', 'месяца', 'месяцев');
    } elseif ($d_count > 7) {
        $w_count =  round($d_count / 7, 0, PHP_ROUND_HALF_EVEN);
        $result = $w_count . ' ' . get_noun_plural_form($w_count, 'неделя', 'недели', 'недель');
    } elseif ($d_count <= 7 && $d_count > 0) {
        $result = $d_count . ' ' . get_noun_plural_form($d_count, 'день', 'дня', 'дней');
    } elseif ($h_count < 24 && $h_count > 0) {
        $result = $h_count . ' ' . get_noun_plural_form($h_count, 'час', 'часа', 'часов');
    } elseif ($i_count < 60) {
        $result = $i_count . ' ' . get_noun_plural_form($i_count, 'минута', 'минуты', 'минут');
    }

    return $result . ' назад';
}

/**
 * Получаем результаты на запрос как двумерные массивы
 * @param mysqli $db объект БД
 * @param string $sql_select запрос в БД
 * @return array 
 * @throws mysqli_sql_exception
 */
function select_query_and_fetch_all(mysqli $db, string $sql_select) 
{
    $result = $db->query($sql_select);

    return $result->fetch_all(MYSQLI_ASSOC);
}

/**
 * Получаем один результат как ассоциативный массив
 * @param mysqli $db объект БД
 * @param string $sql_select запрос в БД
 * @return array 
 * @throws mysqli_sql_exception
 */
function select_query_and_fetch_assoc(mysqli $db, string $sql_select) 
{
    $result = $db->query($sql_select);

    return $result->fetch_assoc();
}

/**
 * Добавляем новый GET параметр в уже имеющиеся
 * @param string $new_key ключ параметра
 * @param string $new_value значение параметра
 * @return string возвращает все параметры в виде строки 
 */
function add_get_param($new_key, $new_value) {
    $params = $_GET;
    $params[$new_key] = $new_value;
    $query = http_build_query($params);

    return $url = "?" . $query;
}
