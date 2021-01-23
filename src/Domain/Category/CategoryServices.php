<?php

namespace App\Domain\Category;

use PDO;

class CategoryServices
{
    private $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function create($title, $userId)
    {
        $sql = "INSERT INTO categories (title, created_by, updated_by) values (:title, :userId, :userId);";
        $db = $this->connection->prepare($sql);
        $db->bindParam(':title', $title, PDO::PARAM_STR);
        $db->bindParam(':userId', $userId, PDO::PARAM_INT);
        $db->execute();
        return ['id' => (int)$this->connection->lastInsertId()];
    }

    public function fetchCategory($title = '', $from = null, $to = null, $page = 1, $limit = 1, $isAll = false)
    {
        $sql = '';
        $sqlCount = '';
        $total = 0;
        // fetch all category
        if($isAll) {
            $sqlCount = 'SELECT count(id) as total from categories;';
            $dbCount = $this->connection->prepare($sqlCount);

            $sql = 'SELECT * from categories;';
            $db = $this->connection->prepare($sql);
        } else { // Fetch Category by page
            $offset = ($page - 1) * $limit;
        
            if (!$title) {
                // sql for count
                $sqlCount = 'SELECT count(id) as total from categories;';
                $dbCount = $this->connection->prepare($sqlCount);
    
                // sql for select
                $sql = 'SELECT * from categories limit :limit offset :offset';
                $db = $this->connection->prepare($sql);
            }
            if ($title) {
                $sqlCount = 'SELECT count(id) as total from categories where title like :title;';
                $dbCount = $this->connection->prepare($sqlCount);
                $dbCount->bindParam(':title', $keyword, PDO::PARAM_STR);
    
                $sql = 'SELECT * from categories where title like :title limit :limit offset :offset;';
                $db = $this->connection->prepare($sql);
                $keyword = "%" . $title . "%";
                $db->bindParam(':title', $keyword, PDO::PARAM_STR);
            }
            if ($from && $to) {
                $sqlCount = 'SELECT count(id) as total from categories where title like :title and created_at between :from and :to;';
                $dbCount = $this->connection->prepare($sqlCount);
                $dbCount->bindParam(':title', $keyword, PDO::PARAM_STR);
                $dbCount->bindParam(':from', $from, PDO::PARAM_STR);
                $dbCount->bindParam(':to', $to, PDO::PARAM_STR);
    
                $sql = 'SELECT * from categories where title like :title and created_at between :from and :to limit :limit offset :offset;';
                $db = $this->connection->prepare($sql);
                $keyword = "%" . $title . "%";
                $db->bindParam(':title', $keyword, PDO::PARAM_STR);
                $db->bindParam(':from', $from, PDO::PARAM_STR);
                $db->bindParam(':to', $to, PDO::PARAM_STR);
            }
            $db->bindParam(':limit', $limit, PDO::PARAM_INT);
            $db->bindParam(':offset', $offset, PDO::PARAM_INT);
        }
        
        $dbCount->execute();
        $total = (int) $dbCount->fetchAll()[0]['total'];

        $db->execute();
        $category = $db->fetchAll();
        return ['category' => $category, 'total' => $total];
    }

    public function update($categoryId, $title, $userId) {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $date = date("Y-m-d H:i:s");
        $sql = "UPDATE categories set title=:title, updated_by=:userId, updated_at=:date where id=:categoryId";
        $db = $this->connection->prepare($sql);
        $db->bindParam(':title', $title, PDO::PARAM_STR);
        $db->bindParam(':userId', $userId, PDO::PARAM_INT);
        $db->bindParam(':categoryId', $categoryId, PDO::PARAM_INT);
        $db->bindParam(':date', $date, PDO::PARAM_STR);
        $db->execute();
    }

    // fetch category by id, return category if exist or false if not
    public function fetchCategoryById($id) {

        $sql = "SELECT * FROM categories where id=:id;";
        $db = $this->connection->prepare($sql);
        $db->bindParam(':id', $id, PDO::PARAM_INT);
        $db->execute();
        $categories = $db->fetchAll();
        if(count($categories) > 0) {
            return true;
        }
        return false;
    }
}
