<?php

// HTTP-заголовки
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// подключение необходимых файлов
include_once "../config/core.php";
include_once "../config/database.php";
include_once "../objects/Dish.php";

// создание подключения к БД
$database = new Database();
$db = $database->getConnection();

// инициализируем объект
$dish = new Dish($db);

// получаем ключевые слова
$keywords = isset($_GET["s"]) ? $_GET["s"] : "";

// запрос товаров
$stmt = $dish->search($keywords);
$num = $stmt->rowCount();

// проверяем, найдено ли больше 0 записей
if ($num > 0) {
    // массив товаров
    $dishes_arr = array();
    $dishes_arr["records"] = array();

    // получаем содержимое нашей таблицы
    // fetch() быстрее чем fetchAll()
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

        // извлечём строку
        extract($row);
        $dish_item = array(
            "DishId" => $DishId,
            "UserId" => $UserId,
            "Name" => $Name,
            "Image" => $Image,
            "Description" => $Description,
            "Ingredients" => $Ingredients,
            "Recipe" => $Recipe
        );
        array_push($dishes_arr["records"], $dish_item);
    }
    // код ответа - 200 OK
    http_response_code(200);

    // покажем товары
    echo json_encode($dishes_arr);
} else {
    // код ответа - 404 Ничего не найдено
    http_response_code(404);

    // скажем пользователю, что товары не найдены
    echo json_encode(array("message" => "Блюда не найдены."), JSON_UNESCAPED_UNICODE);
}