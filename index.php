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

echo "1. Разбиение и объединение ФИО\n";
$test1 = getPartsFromFullname($example_persons_array[8]['fullname']);
print_r($test1);
$test2 = getFullnameFromParts($test1['surname'], $test1['name'], $test1['patronomyc']);
print_r($test2 . "\n\n");


# Сокращение ФИО

function getShortName($fullname)
{
    $person = getPartsFromFullname($fullname);
    $name = $person['name'];
    $shortsurname = mb_substr($person['surname'], 0, 1);
    return "$name $shortsurname. ";
}

echo "2. Сокращение ФИО\n";
$test3 = getShortName($example_persons_array[8]['fullname']);
print_r($test3 . "\n\n");


# Функция определения пола по ФИО

function getGenderFromName($fullname)
{
    $person = getPartsFromFullname($fullname);
    $gender = 0;

    $gender = (
        mb_substr($person['surname'], -1, 1) == "в" ||
        (mb_substr($person['name'], -1 , 1) == "й" || mb_substr($person['name'], -1 , 1) == "н") ||
        mb_substr($person['patronomyc'], -2, 2) == "ич") <=> (mb_substr($person['surname'], -2, 2) == "ва" ||
        mb_substr($person['name'], -1 , 1) == "a" || mb_substr($person['patronomyc'], -3, 3) == "вна"
    );

    return ($gender >= 1) ? "мужской" : (($gender < 1) ? "женский" : "неопределённый");
}

echo "3. Функция определения пола по ФИО\n";
$test4 = getGenderFromName($example_persons_array[8]['fullname']);
print_r($test4 . "\n\n");


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

echo "4. Определение возрастно-полового состава\n";
$test5 = getGenderDescription($example_persons_array);
print_r($test5 . "\n\n");


# Идеальный подбор пары

# TODO: работает через раз
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

    if ($gender == $gender_rand_person)
    {
        getPerfectPartner($surname, $name, $patronomyc, $personos_array);
    } else
    {
        $short_person = getShortName($fullname);
        $short_rand_person = getShortName($rand_person);
        $rand_persent = rand(10000, 0) / 100;

        $res = <<<MYTEXT
        $short_person + $short_rand_person =
        ♡ Идеально на $rand_persent% ♡
        MYTEXT;

        return $res;
    }
}

echo "5. Идеальный подбор пары\n";
$randperson = getPartsFromFullname($example_persons_array[rand(0 ,count($example_persons_array) - 1)]['fullname']);
$test6 = getPerfectPartner($randperson['surname'], $randperson['name'], $randperson['patronomyc'], $example_persons_array);
print_r($test6 . "\n\n");
