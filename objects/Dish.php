<?php
class Dish
{
    // подключение к базе данных и таблице "products"
    private $conn;
    private $table_name = "Dish";

    // свойства объекта
    public $DishId;
    public $UserId;
    public $Name;
    public $Image;
    public $Description;
    public $Ingredients;
    public $Recipe;

    // конструктор для соединения с базой данных
    public function __construct($db)
    {
        $this->conn = $db;
    }

    // метод для получения товаров
function read()
{
    // выбираем все записи
    $query = "SELECT
        c.Name as CategoryName, p.DishId, p.UserId, p.Name, p.Image, p.Description, p.Ingredients, p.Recipe
    FROM
        " . $this->table_name . " p
    ORDER BY
        p.DishId DESC";

    // подготовка запроса
    $stmt = $this->conn->prepare($query);

    // выполняем запрос
    $stmt->execute();
    return $stmt;
}

// метод для получения конкретного блюда по DishId
function readOne()
{
    // запрос для чтения одной записи (блюда)
    $query = "SELECT
            c.Name as CategoryName, p.DishId, p.UserId, p.Name, p.Image, p.Description, p.Ingredients, p.Recipe
        FROM
            " . $this->table_name . " p
            LEFT JOIN
            Category c
                ON p.CategoryId = c.id
        WHERE
            p.DishId = ?
        LIMIT
            0,1";

    // подготовка запроса
    $stmt = $this->conn->prepare($query);

    // привязываем DishId блюда, которое будет получено
    $stmt->bindParam(1, $this->DishId);

    // выполняем запрос
    $stmt->execute();

    // получаем извлеченную строку
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    // установим значения свойств объекта
    $this->UserId = $row["UserId"];
    $this->Name = $row["Name"];
    $this->Image = $row["Image"];
    $this->Description = $row["Description"];
    $this->Ingredients = $row["Ingredients"];
    $this->Recipe = $row["Recipe"];
}

// метод для поиска блюд
function search($keywords)
{
    // поиск записей (блюд) по "названию блюда", "описанию блюда"
    $query = "SELECT
            c.Name as CategoryName, p.DishId, p.UserId, p.Name, p.Image, p.Description, p.Ingredients, p.Recipe
        FROM
            " . $this->table_name . " p
            LEFT JOIN
                Category c
                 ON p.CategoryId = c.Id
        WHERE
            p.Name LIKE ? OR p.Description LIKE ? OR c.Name LIKE ?
        ORDER BY
            p.DishId DESC";

    // подготовка запроса
    $stmt = $this->conn->prepare($query);

    // очистка
    $keywords = htmlspecialchars(strip_tags($keywords));
    $keywords = "%{$keywords}%";

    // привязка
    $stmt->bindParam(1, $keywords);
    $stmt->bindParam(2, $keywords);
    $stmt->bindParam(3, $keywords);

    // выполняем запрос
    $stmt->execute();

    return $stmt;
}

// получение блюд с пагинацией
public function readPaging($from_record_num, $records_per_page)
{
    // выборка
    $query = "SELECT
            c.name as category_name, p.DishId, p.UserId, p.Name, p.Image, p.Description, p.Ingredients, p.Recipe
        FROM
            " . $this->table_name . " p
            LEFT JOIN
                categories c
                    ON p.category_id = c.id
        ORDER BY p.created DESC
        LIMIT ?, ?";

    // подготовка запроса
    $stmt = $this->conn->prepare($query);

    // свяжем значения переменных
    $stmt->bindParam(1, $from_record_num, PDO::PARAM_INT);
    $stmt->bindParam(2, $records_per_page, PDO::PARAM_INT);

    // выполняем запрос
    $stmt->execute();

    // вернём значения из базы данных
    return $stmt;
}

// данный метод возвращает кол-во товаров
public function count()
{
    $query = "SELECT COUNT(*) as total_rows FROM " . $this->table_name . "";

    $stmt = $this->conn->prepare($query);
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    return $row["total_rows"];
}

}