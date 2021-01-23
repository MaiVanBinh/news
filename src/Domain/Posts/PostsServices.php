<?php

namespace App\Domain\Posts;

use Exception;
use PDO;

class PostsServices
{
    private $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function fetchPostById($id)
    {
        try {
            $sql = "SELECT * FROM posts WHERE id=:postId;";
            $db = $this->connection->prepare($sql);
            $db->execute(['postId' => $id]);
            $post = $db->fetchAll();
            if (count($post) === 0) {
                throw new Exception('Not Found');
            }
            // $sql = "UPDATE vncreatures.posts SET content='{$post[0]['content']}' WHERE id=:postId;";
            // $db = $this->connection->prepare($sql);
            // $db->execute(['postId' => $id]);
            return $post[0];
        } catch (Exception $err) {
            throw new Exception($err->getMessage());
        }
    }

    public function fetchPosts($category, $limitPost = 10, $page = 1, $title = null,  $dateFrom = null, $dateTo = null)
    {
        $offset = ($page - 1) * $limitPost;
        $sql = "SELECT * FROM posts order by id LIMIT :limitPost OFFSET :offset";
        $sqlCount = "SELECT count(id) as total FROM posts;";
        $db = $this->connection->prepare($sql);
        $dbCount = $this->connection->prepare($sqlCount);
        if($title && $category && $dateFrom && $dateTo) {
            $categoryString = '(' . join(", ", $category) . ')';
            $sql = "SELECT * FROM posts where title like :title and category in {$categoryString} and (created_at between :dateFrom and :dateTo) order by id LIMIT :limitPost OFFSET :offset;";

            $titleString = '%' . $title . '%';
            
            $db = $this->connection->prepare($sql);
            $db->bindParam(':title', $titleString, PDO::PARAM_STR);
            $db->bindParam(':dateFrom', $dateFrom, PDO::PARAM_STR);
            $db->bindParam(':dateTo', $dateTo, PDO::PARAM_STR);
            // $db->bindParam('categoryString', , PDO::PARAM);
            
            $sqlCount = "SELECT count(id) as total FROM posts where title like :title and category in {$categoryString} and (created_at between :dateFrom and :dateTo);";
            $dbCount = $this->connection->prepare($sqlCount); //and category in :categoryString;
            $dbCount->bindParam(':title', $titleString, PDO::PARAM_STR);
            $dbCount->bindParam(':dateFrom', $dateFrom, PDO::PARAM_STR);
            $dbCount->bindParam(':dateTo', $dateTo, PDO::PARAM_STR);
            // $dbCount->bindParam('categoryString', $categoryString, PDO::PARAM_STR);
        } else if ($title && $category) {
            $categoryString = '(' . join(", ", $category) . ')';
            $sql = "SELECT * FROM posts where title like :title and category in {$categoryString} order by id LIMIT :limitPost OFFSET :offset;";

            $titleString = '%' . $title . '%';
            
            $db = $this->connection->prepare($sql);
            $db->bindParam(':title', $titleString, PDO::PARAM_STR);
            // $db->bindParam('categoryString', , PDO::PARAM);
            
            $sqlCount = "SELECT count(id) as total FROM posts where title like :title and category in {$categoryString};";
            $dbCount = $this->connection->prepare($sqlCount); //and category in :categoryString;
            $dbCount->bindParam(':title', $titleString, PDO::PARAM_STR);
            // $dbCount->bindParam('categoryString', $categoryString, PDO::PARAM_STR);
        } else if ($title && !$category && !$dateFrom && !$dateTo) {
            $sql = "SELECT * FROM posts where title like :title order by id LIMIT :limitPost OFFSET :offset";
            $titleString = '%' . $title . '%';
            $db = $this->connection->prepare($sql);
            $db->bindParam(':title', $titleString, PDO::PARAM_STR);

            $sqlCount = "SELECT count(id) as total FROM posts where title like :title;";
            $dbCount = $this->connection->prepare($sqlCount);
            $dbCount->bindParam(':title', $titleString, PDO::PARAM_STR);
        }
        else if ($category && !$title && !$dateFrom && !$dateTo) {
            $categoryString = '(' . join(", ", $category) . ')';
            $sql = "SELECT * FROM posts where category in {$categoryString} order by id LIMIT :limitPost OFFSET :offset";
            // $db->bindParam('categoryString', $categoryString, PDO::PARAM_STR);
            $sqlCount = "SELECT count(id) as total FROM posts where category in {$categoryString};";
            $db = $this->connection->prepare($sql);
            $dbCount = $this->connection->prepare($sqlCount);
        }
       
        $dbCount->execute();
        $total = $dbCount->fetchAll()[0]['total'];
        $db->bindParam(':limitPost', $limitPost, PDO::PARAM_INT);
        $db->bindParam(':offset', $offset, PDO::PARAM_INT);
        $db->execute();
        $posts = $db->fetchAll();
        $postsUpdate = [];
        for ($i = 0; $i < count($posts); $i++) {
            $id = (int) $posts[$i]['id'];
            $sql = "SELECT i.id, i.url from images i, post_image p  where i.id = p.image and p.post =:id;";
            $db = $this->connection->prepare($sql);
            $db->bindParam(':id', $id, PDO::PARAM_INT);
            $db->execute();
            $images = $db->fetchAll();
            array_push($postsUpdate, [
                'image' => $images,
                'id' => $posts[$i]['id'],
                'title' => $posts[$i]['title'],
                'content' => $posts[$i]['content'],
                'category' => $posts[$i]['category'],
                'created_at' => $posts[$i]['created_at'],
                'updated_at' => $posts[$i]['updated_at'],
                'created_by' => $posts[$i]['created_by'],
                'description' => $posts[$i]['description']
            ]);
        }
        return ['total' => $total, 'posts' => $postsUpdate];
    }

    public function updatePost($id, $title, $category, $description, $content, $userId)
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $date = date("Y-m-d H:i:s");
        $sql = "UPDATE posts 
            SET 
                title=:title, 
                category=:category, 
                description=:description, 
                content=:content, 
                updated_at=:updated_at,
                updated_by=:updated_by
                 WHERE id=:id";

        $db = $this->connection->prepare($sql);
        $db->bindParam(':updated_at', $date, PDO::PARAM_STR);
        $db->bindParam(':title', $title, PDO::PARAM_STR);
        $db->bindParam(':category', $category, PDO::PARAM_INT);
        $db->bindParam(':description', $description, PDO::PARAM_STR);
        $db->bindParam(':content', $content, PDO::PARAM_STR);
        $db->bindParam(':updated_by', $userId, PDO::PARAM_INT);
        $db->bindParam(':id', $id, PDO::PARAM_INT);
        $db->execute();
    }

    public function createPost($title, $category, $description, $content, $userId)
    {
        $sql = "INSERT 
            posts (title, category, description, content, created_by, updated_by) 
        values (:title, :category, :description, :content, :userId, :userId);";
        $db = $this->connection->prepare($sql);
        $db->bindParam(':title', $title, PDO::PARAM_STR);
        $db->bindParam(':category', $category, PDO::PARAM_INT);
        $db->bindParam(':description', $description, PDO::PARAM_STR);
        $db->bindParam(':content', $content, PDO::PARAM_STR);
        $db->bindParam(':userId', $userId, PDO::PARAM_INT);
        $db->execute();
        return (int)$this->connection->lastInsertId();
    }

    public function deletePost($id)
    {
        $sql = "DELETE FROM posts WHERE id=:id";
        $db = $this->connection->prepare($sql);
        $db->bindParam(':id', $id, PDO::PARAM_INT);
        $db->execute();
    }

    public function publicPost($id, $userId, $is_public)
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $date = date("Y-m-d H:i:s");
        $sql = "UPDATE posts SET is_publish=:is_public, updated_at=:updated_at, updated_by=:userId where id=:id;";
        $db = $this->connection->prepare($sql);
        $db->bindParam(':id', $id, PDO::PARAM_INT);
        $db->bindParam(':updated_at', $date, PDO::PARAM_STR);
        $db->bindParam(':userId', $userId, PDO::PARAM_INT);
        $db->bindParam(':is_public', $is_public, PDO::PARAM_BOOL);
        $db->execute();
    }
}