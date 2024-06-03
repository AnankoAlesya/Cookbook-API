<?php

// установим HTTP-заголовки
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// подключение файлов
include_once "../config/core.php";
include_once "../shared/utilities.php";
include_once "../config/database.php";
include_once "../objects/Dish.php";

// utilities
$utilities = new Utilities();

// создание подключения
$database = new Database();
$db = $database->getConnection();

// инициализация объекта
$dish = new Dish($db);

// запрос товаров
$stmt = $dish->readPaging($from_record_num, $records_per_page);
$num = $stmt->rowCount();

// если больше 0 записей
if ($num > 0) {

    // массив товаров
    $dishes_arr = array();
    $dishes_arr["records"] = array();
    $dishes_arr["paging"] = array();

    // получаем содержимое нашей таблицы
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

        // извлечение строки
        extract($row);
        $dish_item = array(
            "DishId" => $DishId,
            "UserId" => $UserId,
            "Name" => $Name,
            "Image" => $Image,
            "Description" => $description,
            "Ingredients" => $Ingredients,
            "Recipe" => $Resipe
        );
        array_push($dishes_arr["records"], $dish_item);
    }

    // подключим пагинацию
    $total_rows = $dish->count();
    $page_url = "{$home_url}product/read_paging.php?";
    $paging = $utilities->getPaging($page, $total_rows, $records_per_page, $page_url);
    $dishes_arr["paging"] = $paging;

    // установим код ответа - 200 OK
    http_response_code(200);

    // вывод в json-формате
    echo json_encode($dishes_arr);
} else {

    // код ответа - 404 Ничего не найдено
    http_response_code(404);

    // сообщим пользователю, что товаров не существует
    echo json_encode(array("message" => "Блюда не найдены"), JSON_UNESCAPED_UNICODE);
}