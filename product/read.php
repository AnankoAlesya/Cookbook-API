<?php

// необходимые HTTP-заголовки
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// подключение базы данных и файл, содержащий объекты
include_once "../config/database.php";
include_once "../objects/Dish.php";

// получаем соединение с базой данных
$database = new Database();
$db = $database->getConnection();

// инициализируем объект
$dish = new Dish($db);
 
// запрашиваем блюда
$stmt = $dish->read();
$num = $stmt->rowCount();

// проверка, найдено ли больше 0 записей
if ($num > 0) {
    // массив товаров
    $dishes_arr = array();
    $dishes_arr["records"] = array();

    // получаем содержимое нашей таблицы
    // fetch() быстрее, чем fetchAll()
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // извлекаем строку
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

    // устанавливаем код ответа - 200 OK
    http_response_code(200);

    // выводим данные о товаре в формате JSON
    echo json_encode($dishes_arr);
}

else {
    // установим код ответа - 404 Не найдено
    http_response_code(404);

    // сообщаем пользователю, что товары не найдены
    echo json_encode(array("message" => "Блюда не найдены."), JSON_UNESCAPED_UNICODE);
}