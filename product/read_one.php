<?php

// необходимые HTTP-заголовки
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: access");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json");

// подключение файла для соединения с базой и файл с объектом
include_once "../config/database.php";
include_once "../objects/Dish.php";

// получаем соединение с базой данных
$database = new Database();
$db = $database->getConnection();

// подготовка объекта
$dish = new Dish($db);

// установим свойство ID записи для чтения
$dish->DishId = isset($_GET["DishId"]) ? $_GET["DishId"] : die();

// получим детали товара
$dish->readOne();

if ($dish->Name != null) {

    // создание массива
    $dishes_arr = array(
        "DishId" =>  $dish->DishId,
        "UserId" =>  $dish->UserId,
        "Name" => $dish->Name,
        "Image" => $dish->Image,
        "Description" => $dish->Description,
        "Ingredients" => $dish->Ingredients,
        "Recipe" => $dish->Recipe,
        
    );

    // код ответа - 200 OK
    http_response_code(200);

    // вывод в формате json
    echo json_encode($dishes_arr);
} else {
    // код ответа - 404 Не найдено
    http_response_code(404);

    // сообщим пользователю, что такой товар не существует
    echo json_encode(array("message" => "Блюда не существует"), JSON_UNESCAPED_UNICODE);
}