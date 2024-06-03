<?php

class Category
{
    // соединение с БД и таблицей "categories"
    private $conn;
    private $table_name = "Category";

    // свойства объекта
    public $CategoryId;
    public $CategoryName;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // метод для получения всех категорий товаров
public function readAll()
{
    $query = "SELECT
                CategoryId, CategoryName
            FROM
                " . $this->table_name . "
            ORDER BY
                CategoryName";

    $stmt = $this->conn->prepare($query);
    $stmt->execute();

    return $stmt;
}
}