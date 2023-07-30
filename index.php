<?php
$example_persons_array = [
    [
        'fullname' => 'Иванов Иван Иванович',
        'job' => 'tester',
    ],
    [
        'fullname' => 'Степанова Наталья Степановна',
        'job' => 'frontend-developer',
    ],
    [
        'fullname' => 'Пащенко Владимир Александрович',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Громов Александр Иванович',
        'job' => 'fullstack-developer',
    ],
    [
        'fullname' => 'Славин Семён Сергеевич',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Цой Владимир Антонович',
        'job' => 'frontend-developer',
    ],
    [
        'fullname' => 'Быстрая Юлия Сергеевна',
        'job' => 'PR-manager',
    ],
    [
        'fullname' => 'Шматко Антонина Сергеевна',
        'job' => 'HR-manager',
    ],
    [
        'fullname' => 'аль-Хорезми Мухаммад ибн-Муса',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Бардо Жаклин Фёдоровна',
        'job' => 'android-developer',
    ],
    [
        'fullname' => 'Шварцнегер Арнольд Густавович',
        'job' => 'babysitter',
    ],
];

# Разбиение и объединение ФИО
function getPartsFromFullname($fullname)
{
    [$surname, $name, $patronomyc] = explode(" ", $fullname);
    return array("surname" => $surname, "name" => $name, "patronomyc" => $patronomyc);
}

function getFullnameFromParts($surname, $name, $patronomyc)
{
    return implode(" ", [$surname, $name, $patronomyc]);
}


# Сокращение ФИО
function getShortName($fullname)
{
    $person = getPartsFromFullname($fullname);
    $name = $person["name"];
    $shortsurname = mb_substr($person["surname"], 0, 1);
    return "$name $shortsurname. ";
}


# Функция определения пола по ФИО
function getGenderFromName($fullname)
{
    $person = getPartsFromFullname($fullname);
    $gender = 0;

    $gender = (
        str_ends_with($person["surname"], "в") ||
        (str_ends_with($person["name"], "й") || str_ends_with($person["name"], "н")) ||
        str_ends_with($person["patronomyc"], "ич")) <=> (str_ends_with($person["surname"], "ва") ||
        str_ends_with($person["name"], "a") || str_ends_with($person["patronomyc"], "вна")
    );

    return ($gender > 0) ? "мужской" : (($gender < 0) ? "женский" : "неопределённый");
}


# Определение возрастно-полового состава
function getGenderDescription($array)
{
    $womans = array_filter($array, function($item) {
        return getGenderFromName($item['fullname']) == "женский";
    });
    $mans = array_filter($array, function($item) {
        return getGenderFromName($item['fullname']) == "мужской";
    });
    $unknown = array_filter($array, function($item) {
        return getGenderFromName($item['fullname']) == "неопределённый";
    });

    $manscount = number_format((100 * count($mans)) / count($array), 2, '.', '');
    $womanscount = number_format((100 * count($womans)) / count($array), 2, '.', '');
    $unknownscount = number_format((100 * count($unknown)) / count($array), 2, '.', '');

    $res = <<<TEXT
    Гендерный состав аудитории:
    ---------------------------
    Мужчины - $manscount%
    Женщины - $womanscount%
    Не удалось определить - $unknownscount%
    TEXT;
    return $res;
}


# Идеальный подбор пары
function getPerfectPartner($surname, $name, $patronomyc, $personos_array)
{
    [$surname, $name, $patronomyc] = [
        mb_convert_case($surname, MB_CASE_TITLE),
        mb_convert_case($name, MB_CASE_TITLE),
        mb_convert_case($patronomyc, MB_CASE_TITLE),
    ];
    $fullname = getFullnameFromParts($surname, $name, $patronomyc);
    $gender = getGenderFromName($fullname);
    $rand_person = $personos_array[rand(0, count($personos_array) - 1)]['fullname'];
    $gender_rand_person = getGenderFromName($rand_person);
    $res = '';

    while ($gender == $gender_rand_person)
    {
        $rand_person = $personos_array[rand(0, count($personos_array) - 1)]['fullname'];
        $gender_rand_person = getGenderFromName($rand_person);
    }
    $short_person = getShortName($fullname);
    $short_rand_person = getShortName($rand_person);
    $rand_persent = rand(10000, 0) / 100;

    $res = <<<MYTEXT
    $short_person + $short_rand_person =
    ♡ Идеально на $rand_persent% ♡
    MYTEXT;

    return $res;
}

