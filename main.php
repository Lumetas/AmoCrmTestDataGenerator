#!/bin/env php
<?php
/**
 * Генератор тестовых данных для amoCRM
 */

// Проверка аргументов командной строки
if ($argc < 4) {
    die("Использование: php generate_amocrm_data.php <type> <count> <filename.csv> --data=<iterable|random|humanized> --fill=<full|base|minimum>\n");
}

$type = strtolower($argv[1]);
$count = (int)$argv[2];
$filename = $argv[3];

// Парсинг дополнительных параметров
$dataType = 'humanized'; // по умолчанию
$fillLevel = 'base';     // по умолчанию

for ($i = 4; $i < $argc; $i++) {
    if (strpos($argv[$i], '--data=') === 0) {
        $dataType = substr($argv[$i], 7);
    } elseif (strpos($argv[$i], '--fill=') === 0) {
        $fillLevel = substr($argv[$i], 7);
    }
}

// Проверка допустимых значений
if (!in_array($type, ['companies', 'contacts', 'leads', 'customers'])) {
    die("Ошибка: неверный тип данных. Допустимые значения: companies, contacts, leads, customers\n");
}

if ($count < 1) {
    die("Ошибка: количество записей должно быть не меньше 1\n");
}

if (!in_array($dataType, ['iterable', 'random', 'humanized'])) {
    die("Ошибка: неверный тип данных. Допустимые значения: iterable, random, humanized\n");
}

if (!in_array($fillLevel, ['full', 'base', 'minimum'])) {
    die("Ошибка: неверный уровень заполнения. Допустимые значения: full, base, minimum\n");
}

// Базы данных для генерации
$firstNamesMale = ['Иван', 'Александр', 'Сергей', 'Дмитрий', 'Алексей', 'Андрей', 'Максим', 'Евгений', 'Владимир', 'Артем'];
$firstNamesFemale = ['Елена', 'Ольга', 'Наталья', 'Ирина', 'Анна', 'Татьяна', 'Мария', 'Светлана', 'Юлия', 'Екатерина'];
$lastNames = ['Иванов', 'Петров', 'Сидоров', 'Смирнов', 'Кузнецов', 'Попов', 'Лебедев', 'Козлов', 'Новиков', 'Морозов'];
$middleNames = ['Иванович', 'Петрович', 'Сергеевич', 'Дмитриевич', 'Алексеевич', 'Андреевич', 'Владимирович', 'Александрович'];
$companyTypes = ['ООО', 'ЗАО', 'АО', 'ИП'];
$companyNames = ['Технологии', 'Решения', 'Системы', 'Консалтинг', 'Сервис', 'Трейд', 'Групп', 'Холдинг'];
$companySuffixes = ['Россия', 'Север', 'Юг', 'Восток', 'Запад', 'Центр', 'Москва', 'Санкт-Петербург'];
$positions = ['Директор', 'Менеджер', 'Руководитель отдела', 'Специалист', 'Аналитик', 'Разработчик', 'Маркетолог'];
$departments = ['продаж', 'закупок', 'маркетинга', 'IT', 'финансов', 'логистики', 'HR'];
$leadNames = ['Покупка', 'Запрос', 'Тендер', 'Поддержка', 'Разработка', 'Внедрение', 'Консультация'];
$leadSubjects = ['сайта', 'CRM', 'ERP', 'мобильного приложения', 'облачного решения', 'аналитики', '1С'];
$domains = ['mail.ru', 'yandex.ru', 'gmail.com', 'company.ru', 'business.com'];
$streets = ['Ленина', 'Гагарина', 'Кирова', 'Советская', 'Мира', 'Пушкина', 'Лермонтова'];
$cities = ['Москва', 'Санкт-Петербург', 'Новосибирск', 'Екатеринбург', 'Казань', 'Нижний Новгород', 'Челябинск'];

// Функции генерации данных
function generateName($dataType, $index, $gender = null) {
    global $firstNamesMale, $firstNamesFemale, $lastNames, $middleNames;
    
    if (is_null($gender)) {
        $gender = (mt_rand(0, 1) == 0) ? 'male' : 'female';
    }
    
    switch ($dataType) {
        case 'iterable':
            $lastName = $lastNames[0] . ' ' . ($index + 1);
            $firstName = ($gender == 'male') ? $firstNamesMale[0] : $firstNamesFemale[0];
            $middleName = ($gender == 'male') ? $middleNames[0] : '';
            return $lastName . ' ' . $firstName . ' ' . $middleName;
            
        case 'random':
            $lastName = $lastNames[array_rand($lastNames)] . substr(md5(mt_rand()), 0, 3);
            $firstName = ($gender == 'male') ? $firstNamesMale[array_rand($firstNamesMale)] : $firstNamesFemale[array_rand($firstNamesFemale)];
            $middleName = ($gender == 'male') ? $middleNames[array_rand($middleNames)] : '';
            return $lastName . ' ' . $firstName . ' ' . $middleName;
            
        case 'humanized':
        default:
            $lastName = $lastNames[array_rand($lastNames)];
            $firstName = ($gender == 'male') ? $firstNamesMale[array_rand($firstNamesMale)] : $firstNamesFemale[array_rand($firstNamesFemale)];
            $middleName = ($gender == 'male') ? $middleNames[array_rand($middleNames)] : '';
            return $lastName . ' ' . $firstName . ' ' . $middleName;
    }
}

function generateCompanyName($dataType, $index) {
    global $companyTypes, $companyNames, $companySuffixes;
    
    switch ($dataType) {
        case 'iterable':
            return $companyTypes[0] . ' ' . $companyNames[0] . ' ' . $index;
            
        case 'random':
            return $companyTypes[array_rand($companyTypes)] . ' ' . 
                   substr(md5(mt_rand()), 0, 6) . ' ' . 
                   $companySuffixes[array_rand($companySuffixes)];
                   
        case 'humanized':
        default:
            return $companyTypes[array_rand($companyTypes)] . ' ' . 
                   $companyNames[array_rand($companyNames)] . ' ' . 
                   $companySuffixes[array_rand($companySuffixes)];
    }
}

function generatePhone() {
    return '+7 (' . mt_rand(900, 999) . ') ' . mt_rand(100, 999) . '-' . mt_rand(10, 99) . '-' . mt_rand(10, 99);
}

function generateEmail($name, $dataType) {
    global $domains;
    
    $name = transliterate($name);
    $nameParts = explode(' ', $name);
    $domain = $domains[array_rand($domains)];
    
    switch ($dataType) {
        case 'iterable':
            return 'user' . substr(md5($name), 0, 3) . '@' . $domains[0];
            
        case 'random':
            return substr(md5(mt_rand()), 0, 8) . '@' . $domain;
            
        case 'humanized':
        default:
            $firstName = strtolower($nameParts[1]);
            $lastName = strtolower($nameParts[0]);
            $variations = [
                $firstName . '.' . $lastName,
                $firstName[0] . '.' . $lastName,
                $firstName . '_' . $lastName,
                $firstName . mt_rand(1, 99)
            ];
            return $variations[array_rand($variations)] . '@' . $domain;
    }
}

function transliterate($text) {
    $cyr = ['а','б','в','г','д','е','ё','ж','з','и','й','к','л','м','н','о','п',
            'р','с','т','у','ф','х','ц','ч','ш','щ','ъ','ы','ь','э','ю','я',
            'А','Б','В','Г','Д','Е','Ё','Ж','З','И','Й','К','Л','М','Н','О','П',
            'Р','С','Т','У','Ф','Х','Ц','Ч','Ш','Щ','Ъ','Ы','Ь','Э','Ю','Я'];
    $lat = ['a','b','v','g','d','e','io','zh','z','i','y','k','l','m','n','o','p',
            'r','s','t','u','f','h','ts','ch','sh','sht','a','i','y','e','yu','ya',
            'A','B','V','G','D','E','Io','Zh','Z','I','Y','K','L','M','N','O','P',
            'R','S','T','U','F','H','Ts','Ch','Sh','Sht','A','I','Y','E','Yu','Ya'];
    return str_replace($cyr, $lat, $text);
}

function generateAddress() {
    global $streets, $cities;
    return $cities[array_rand($cities)] . ', ул. ' . $streets[array_rand($streets)] . ', ' . mt_rand(1, 200);
}

function generateLeadName($dataType, $index) {
    global $leadNames, $leadSubjects;
    
    switch ($dataType) {
        case 'iterable':
            return $leadNames[0] . ' ' . $leadSubjects[0] . ' ' . $index;
            
        case 'random':
            return substr(md5(mt_rand()), 0, 8) . ' ' . substr(md5(mt_rand()), 0, 6);
            
        case 'humanized':
        default:
            return $leadNames[array_rand($leadNames)] . ' ' . $leadSubjects[array_rand($leadSubjects)];
    }
}

function generatePosition() {
    global $positions, $departments;
    return $positions[array_rand($positions)] . ' ' . $departments[array_rand($departments)];
}

function generateBudget() {
    return mt_rand(1, 100) * 10000;
}

function generateDate($start = '-2 years', $end = 'now') {
    $start = strtotime($start);
    $end = strtotime($end);
    $timestamp = mt_rand($start, $end);
    return date('d.m.Y H:i', $timestamp);
}

function generateNextDate() {
    return generateDate('now', '+1 year');
}

// Генерация данных в зависимости от типа
$data = [];
$headers = [];

switch ($type) {
    case 'companies':
        $headers = [
            'Название (компания)', 'Ответственный за компанию', 'Дата создания (компания)', 
            'Кем создана компания', 'Адрес (компания)', 'Рабочий телефон (компания)', 
            'Тег компании', 'Примечание к компании'
        ];
        
        if ($fillLevel == 'full') {
            $headers = array_merge($headers, [
                'Примечание к компании', 'Примечание к компании', 'Примечание к компании', 
                'Примечание к компании', 'Примечание к компании'
            ]);
        }
        
        for ($i = 0; $i < $count; $i++) {
            $company = [
                generateCompanyName($dataType, $i),
                generateName('humanized', $i),
                generateDate(),
                generateName('humanized', $i),
                ($fillLevel != 'minimum') ? generateAddress() : '',
                ($fillLevel != 'minimum') ? generatePhone() : '',
                ($fillLevel == 'full') ? 'тег1,тег2' : '',
                ($fillLevel != 'minimum') ? 'Тестовая компания ' . ($i + 1) : ''
            ];
            
            if ($fillLevel == 'full') {
                $company = array_merge($company, [
                    'Доп. примечание 1',
                    'Доп. примечание 2',
                    'Доп. примечание 3',
                    'Доп. примечание 4',
                    'Доп. примечание 5'
                ]);
            }
            
            $data[] = $company;
        }
        break;
        
    case 'contacts':
        $headers = [
            'Полное имя (контакт)', 'Должность (контакт)', 'Ответственный за контакт', 
            'Дата создания (контакт)', 'Кем создан контакт', 'Рабочий телефон (контакт)',
            'Рабочий email (контакт)', 'Название (компания)'
        ];
        
        if ($fillLevel != 'minimum') {
            $headers = array_merge($headers, [
                'Домашний телефон (контакт)', 'Мобильный телефон (контакт)', 
                'Личный email (контакт)', 'Адрес (компания)'
            ]);
        }
        
        if ($fillLevel == 'full') {
            $headers = array_merge($headers, [
                'Название (сделка)', 'Бюджет руб. (сделка)', 'Статус сделки',
                'Тег контакта', 'Примечание к контакту', 'Примечание к контакту',
                'Примечание к контакту', 'Примечание к контакту', 'Примечание к контакту',
                'Факс 1 (контакт)', 'Факс 2 (контакт)', 'Другой телефон 1 (контакт)',
                'Другой телефон 2 (контакт)', 'Другой email 1 (контакт)', 
                'Другой email 2 (контакт)', 'ICQ 1 (контакт)', 'ICQ 2 (контакт)',
                'Jabber 1 (контакт)', 'Jabber 2 (контакт)', 'Google Talk 1 (контакт)',
                'Google Talk 2 (контакт)', 'Skype 1 (контакт)', 'Skype 2 (контакт)',
                'MSN 1 (контакт)', 'MSN 2 (контакт)', 'Другой IM 1 (контакт)',
                'Другой IM 2 (контакт)'
            ]);
        }
        
        for ($i = 0; $i < $count; $i++) {
            $contactName = generateName($dataType, $i);
            $companyName = generateCompanyName($dataType, $i);
            
            $contact = [
                $contactName,
                generatePosition(),
                generateName('humanized', $i),
                generateDate(),
                generateName('humanized', $i),
                generatePhone(),
                generateEmail($contactName, $dataType),
                $companyName
            ];
            
            if ($fillLevel != 'minimum') {
                $contact = array_merge($contact, [
                    generatePhone(),
                    generatePhone(),
                    generateEmail($contactName, $dataType),
                    generateAddress()
                ]);
            }
            
            if ($fillLevel == 'full') {
                $contact = array_merge($contact, [
                    generateLeadName($dataType, $i),
                    generateBudget(),
                    'Новый',
                    'тег1,тег2',
                    'Тестовый контакт ' . ($i + 1),
                    'Доп. примечание 1',
                    'Доп. примечание 2',
                    'Доп. примечание 3',
                    'Доп. примечание 4',
                    generatePhone(),
                    generatePhone(),
                    generatePhone(),
                    generatePhone(),
                    generateEmail($contactName, $dataType),
                    generateEmail($contactName, $dataType),
                    mt_rand(100000, 999999),
                    mt_rand(100000, 999999),
                    generateEmail($contactName, $dataType),
                    generateEmail($contactName, $dataType),
                    generateEmail($contactName, $dataType),
                    generateEmail($contactName, $dataType),
                    strtolower(transliterate(explode(' ', $contactName)[1]) . mt_rand(1, 99)),
                    strtolower(transliterate(explode(' ', $contactName)[1]) . mt_rand(1, 99)),
                    generateEmail($contactName, $dataType),
                    generateEmail($contactName, $dataType),
                    'im' . mt_rand(1, 99),
                    'im' . mt_rand(1, 99)
                ]);
            }
            
            $data[] = $contact;
        }
        break;
        
    case 'leads':
        $headers = [
            'Название сделки', 'Бюджет сделки', 'Ответственный за сделку',
            'Дата создания (сделка)', 'Кем создана сделка', 'Статус сделки',
            'Полное имя (контакт)', 'Должность (контакт)', 'Рабочий телефон (контакт)',
            'Рабочий email (контакт)', 'Название (компания)'
        ];
        
        if ($fillLevel != 'minimum') {
            $headers = array_merge($headers, [
                'Тег сделки', 'Примечание к сделке', 'Примечание к сделке',
                'Домашний телефон (контакт)', 'Мобильный телефон (контакт)',
                'Личный email (контакт)', 'Адрес (компания)', 'Сайт компании'
            ]);
        }
        
        if ($fillLevel == 'full') {
            $headers = array_merge($headers, [
                'Примечание к сделке', 'Примечание к сделке', 'Примечание к сделке',
                'Факс (контакт)', 'Другой телефон (контакт)', 'Другой email (контакт)',
                'Рабочий телефон (компания)', 'Мобильный телефон (компания)',
                'Факс (компания)', 'Другой телефон (компания)', 'Рабочий email (компания)',
                'Личный email (компания)', 'Другой email (компания)', 'ICQ (контакт)',
                'Jabber (контакт)', 'Google Talk (контакт)', 'Skype (контакт)',
                'MSN (контакт)', 'Другой IM (контакт)'
            ]);
        }
        
        $statuses = ['Новый', 'В работе', 'Принимают решение', 'Согласование договора', 'Успешно реализовано', 'Закрыто и не реализовано'];
        
        for ($i = 0; $i < $count; $i++) {
            $contactName = generateName($dataType, $i);
            $companyName = generateCompanyName($dataType, $i);
            $leadName = generateLeadName($dataType, $i);
            
            $lead = [
                $leadName,
                generateBudget(),
                generateName('humanized', $i),
                generateDate(),
                generateName('humanized', $i),
                $statuses[array_rand($statuses)],
                $contactName,
                generatePosition(),
                generatePhone(),
                generateEmail($contactName, $dataType),
                $companyName
            ];
            
            if ($fillLevel != 'minimum') {
                $lead = array_merge($lead, [
                    'тег1,тег2',
                    'Тестовая сделка ' . ($i + 1),
                    'Доп. примечание 1',
                    generatePhone(),
                    generatePhone(),
                    generateEmail($contactName, $dataType),
                    generateAddress(),
                    'http://' . strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $companyName)) . '.ru'
                ]);
            }
            
            if ($fillLevel == 'full') {
                $lead = array_merge($lead, [
                    'Доп. примечание 2',
                    'Доп. примечание 3',
                    'Доп. примечание 4',
                    generatePhone(),
                    generatePhone(),
                    generateEmail($contactName, $dataType),
                    generatePhone(),
                    generatePhone(),
                    generatePhone(),
                    generatePhone(),
                    generateEmail($companyName, $dataType),
                    generateEmail($companyName, $dataType),
                    generateEmail($companyName, $dataType),
                    mt_rand(100000, 999999),
                    generateEmail($contactName, $dataType),
                    generateEmail($contactName, $dataType),
                    strtolower(transliterate(explode(' ', $contactName)[1]) . mt_rand(1, 99)),
                    generateEmail($contactName, $dataType),
                    'im' . mt_rand(1, 99)
                ]);
            }
            
            $data[] = $lead;
        }
        break;
        
    case 'customers':
        $headers = [
            'Название покупателя', 'Ожидаемая сумма', 'Ответственный за покупателя',
            'Дата следующей покупки', 'Полное имя (контакт)', 'Должность (контакт)',
            'Рабочий email (контакт)', 'Название (компания)'
        ];
        
        if ($fillLevel != 'minimum') {
            $headers = array_merge($headers, [
                'Тег покупателя', 'Примечание к покупателю', 'Примечание к покупателю',
                'Рабочий телефон (контакт)', 'Мобильный телефон (контакт)',
                'Личный email (контакт)', 'Адрес (компания)', 'Сайт компании'
            ]);
        }
        
        if ($fillLevel == 'full') {
            $headers = array_merge($headers, [
                'Примечание к покупателю', 'Примечание к покупателю', 'Примечание к покупателю',
                'Факс (контакт)', 'Другой телефон (контакт)', 'Другой email (контакт)',
                'Рабочий телефон (компания)', 'Мобильный телефон (компания)',
                'Факс (компания)', 'Другой телефон (компания)', 'Рабочий email (компания)',
                'Личный email (компания)', 'Другой email (компания)', 'ICQ (контакт)',
                'Jabber (контакт)', 'Google Talk (контакт)', 'Skype (контакт)',
                'MSN (контакт)', 'Другой IM (контакт)'
            ]);
        }
        
        for ($i = 0; $i < $count; $i++) {
            $contactName = generateName($dataType, $i);
            $companyName = generateCompanyName($dataType, $i);
            $leadName = generateLeadName($dataType, $i);
            
            $customer = [
                $leadName,
                generateBudget(),
                generateName('humanized', $i),
                generateNextDate(),
                $contactName,
                generatePosition(),
                generateEmail($contactName, $dataType),
                $companyName
            ];
            
            if ($fillLevel != 'minimum') {
                $customer = array_merge($customer, [
                    'тег1,тег2',
                    'Тестовый покупатель ' . ($i + 1),
                    'Доп. примечание 1',
                    generatePhone(),
                    generatePhone(),
                    generateEmail($contactName, $dataType),
                    generateAddress(),
                    'http://' . strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $companyName)) . '.ru'
                ]);
            }
            
            if ($fillLevel == 'full') {
                $customer = array_merge($customer, [
                    'Доп. примечание 2',
                    'Доп. примечание 3',
                    'Доп. примечание 4',
                    generatePhone(),
                    generatePhone(),
                    generateEmail($contactName, $dataType),
                    generatePhone(),
                    generatePhone(),
                    generatePhone(),
                    generatePhone(),
                    generateEmail($companyName, $dataType),
                    generateEmail($companyName, $dataType),
                    generateEmail($companyName, $dataType),
                    mt_rand(100000, 999999),
                    generateEmail($contactName, $dataType),
                    generateEmail($contactName, $dataType),
                    strtolower(transliterate(explode(' ', $contactName)[1]) . mt_rand(1, 99)),
                    generateEmail($contactName, $dataType),
                    'im' . mt_rand(1, 99)
                ]);
            }
            
            $data[] = $customer;
        }
        break;
}

// Запись в CSV файл
$file = fopen($filename, 'w');
if ($file === false) {
    die("Ошибка: не удалось создать файл $filename\n");
}

// Запись заголовков
fputcsv($file, $headers, ';');

// Запись данных
foreach ($data as $row) {
    fputcsv($file, $row, ';');
}

fclose($file);

echo "Успешно создан файл $filename с $count записями типа $type\n";
echo "Тип данных: $dataType, уровень заполнения: $fillLevel\n";
