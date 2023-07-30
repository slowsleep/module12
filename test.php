<?php

require_once 'index.php';

echo "1. Разбиение и объединение ФИО\n";
$test1 = getPartsFromFullname($example_persons_array[8]['fullname']);
print_r($test1);
$test2 = getFullnameFromParts($test1['surname'], $test1['name'], $test1['patronomyc']);
print_r($test2 . "\n\n");

echo "2. Сокращение ФИО\n";
$test3 = getShortName($example_persons_array[8]['fullname']);
print_r($test3 . "\n\n");

echo "3. Функция определения пола по ФИО\n";
$test4 = getGenderFromName($example_persons_array[8]['fullname']);
print_r($test4 . "\n\n");

echo "4. Определение возрастно-полового состава\n";
$test5 = getGenderDescription($example_persons_array);
print_r($test5 . "\n\n");

echo "5. Идеальный подбор пары\n";
$randperson = getPartsFromFullname($example_persons_array[rand(0 ,count($example_persons_array) - 1)]['fullname']);
$test6 = getPerfectPartner(mb_strtoupper($randperson['surname']), mb_strtolower($randperson['name']), $randperson['patronomyc'], $example_persons_array);
print_r($test6 . "\n\n");

