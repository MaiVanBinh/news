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
            $sql = "SELECT p.*, c.title as categoryTitle FROM posts p, categories c WHERE p.id=:postId and p.category=c.id;";
            $db = $this->connection->prepare($sql);
            $db->execute(['postId' => $id]);
            $posts = $db->fetchAll();
            if (count($posts) === 0) {
                throw new Exception('Not Found');
            }
            $post = $posts[0];
            $sql = "SELECT i.id, i.url FROM images i, post_image p WHERE p.post=:postId and p.image=i.id;";
            $db = $this->connection->prepare($sql);
            $db->execute(['postId' => $id]);
            $images = $db->fetchAll();
            $post['images'] = $images;
            return $post;
        } catch (Exception $err) {
            throw new Exception($err->getMessage());
        }
    }

    public function fetchPosts($category, $limitPost = 10, $page = 1, $title = null,  $dateFrom = null, $dateTo = null, $is_publish = 1)
    {
        $offset = ($page - 1) * $limitPost;
        $sql = "SELECT * FROM posts where is_publish=:is_publish order by id LIMIT :limitPost OFFSET :offset";
        $sqlCount = "SELECT count(id) as total FROM posts where is_publish=:is_publish;";
        $db = $this->connection->prepare($sql);
        $dbCount = $this->connection->prepare($sqlCount);
        if ($title) {
            $sql = "SELECT * FROM posts where is_publish=:is_publish and title like :title order by id LIMIT :limitPost OFFSET :offset";
            $titleString = '%' . $title . '%';
            $db = $this->connection->prepare($sql);
            $db->bindParam(':title', $titleString, PDO::PARAM_STR);

            $sqlCount = "SELECT count(id) as total FROM posts where is_publish=:is_publish and title like :title;";
            $dbCount = $this->connection->prepare($sqlCount);
            $dbCount->bindParam(':title', $titleString, PDO::PARAM_STR);
        }
        if ($category) {
            $categoryString = '(' . join(", ", $category) . ')';
            $sql = "SELECT * FROM posts where is_publish=:is_publish and category in {$categoryString} order by id LIMIT :limitPost OFFSET :offset";
            // $db->bindParam('categoryString', $categoryString, PDO::PARAM_STR);
            $sqlCount = "SELECT count(id) as total FROM posts where is_publish=:is_publish and category in {$categoryString};";
            $db = $this->connection->prepare($sql);
            $dbCount = $this->connection->prepare($sqlCount);
        }
        if ($category && $dateFrom && $dateTo) {
            $categoryString = '(' . join(", ", $category) . ')';
            $sql = "SELECT * FROM posts where is_publish=:is_publish and category in {$categoryString} and (created_at between :dateFrom and :dateTo) order by id LIMIT :limitPost OFFSET :offset;";

            $titleString = '%' . $title . '%';
            
            $db = $this->connection->prepare($sql);
            $db->bindParam(':dateFrom', $dateFrom, PDO::PARAM_STR);
            $db->bindParam(':dateTo', $dateTo, PDO::PARAM_STR);
            // $db->bindParam('categoryString', , PDO::PARAM);
            
            $sqlCount = "SELECT count(id) as total FROM posts where is_publish=:is_publish and category in {$categoryString} and (created_at between :dateFrom and :dateTo);";
            $dbCount = $this->connection->prepare($sqlCount); //and category in :categoryString;
            $dbCount->bindParam(':dateFrom', $dateFrom, PDO::PARAM_STR);
            $dbCount->bindParam(':dateTo', $dateTo, PDO::PARAM_STR);
        }
        if ($title && $dateFrom && $dateTo) {
            $sql = "SELECT * FROM posts where is_publish=:is_publish and title like :title and (created_at between :dateFrom and :dateTo) order by id LIMIT :limitPost OFFSET :offset;";
            $titleString = '%' . $title . '%';
            $db = $this->connection->prepare($sql);
  
            $db->bindParam(':title', $titleString, PDO::PARAM_STR);
            $db->bindParam(':dateFrom', $dateFrom, PDO::PARAM_STR);
            $db->bindParam(':dateTo', $dateTo, PDO::PARAM_STR);
            // $db->bindParam('categoryString', , PDO::PARAM);
            
            $sqlCount = "SELECT count(id) as total FROM posts where is_publish=:is_publish and title like :title and (created_at between :dateFrom and :dateTo);";
            $dbCount = $this->connection->prepare($sqlCount); //and category in :categoryString;
            $dbCount->bindParam(':title', $titleString, PDO::PARAM_STR);
            $dbCount->bindParam(':dateFrom', $dateFrom, PDO::PARAM_STR);
            $dbCount->bindParam(':dateTo', $dateTo, PDO::PARAM_STR);
        }

        if ($title && $category) {
            $categoryString = '(' . join(", ", $category) . ')';
            $sql = "SELECT * FROM posts where is_publish=:is_publish and title like :title and category in {$categoryString} order by id LIMIT :limitPost OFFSET :offset;";

            $titleString = '%' . $title . '%';
            
            $db = $this->connection->prepare($sql);
            $db->bindParam(':title', $titleString, PDO::PARAM_STR);
            // $db->bindParam('categoryString', , PDO::PARAM);
            
            $sqlCount = "SELECT count(id) as total FROM posts where is_publish=:is_publish and title like :title and category in {$categoryString};";
            $dbCount = $this->connection->prepare($sqlCount); //and category in :categoryString;
            $dbCount->bindParam(':title', $titleString, PDO::PARAM_STR);
            // $dbCount->bindParam('categoryString', $categoryString, PDO::PARAM_STR);
        }

        if($title && $category && $dateFrom && $dateTo) {
            $categoryString = '(' . join(", ", $category) . ')';
            $sql = "SELECT * FROM posts where is_publish=:is_publish and title like :title and category in {$categoryString} and (created_at between :dateFrom and :dateTo) order by id LIMIT :limitPost OFFSET :offset;";

            $titleString = '%' . $title . '%';
            
            $db = $this->connection->prepare($sql);
            $db->bindParam(':title', $titleString, PDO::PARAM_STR);
            $db->bindParam(':dateFrom', $dateFrom, PDO::PARAM_STR);
            $db->bindParam(':dateTo', $dateTo, PDO::PARAM_STR);
            // $db->bindParam('categoryString', , PDO::PARAM);
            
            $sqlCount = "SELECT count(id) as total FROM posts where is_publish=:is_publish and title like :title and category in {$categoryString} and (created_at between :dateFrom and :dateTo);";
            $dbCount = $this->connection->prepare($sqlCount); //and category in :categoryString;
            $dbCount->bindParam(':title', $titleString, PDO::PARAM_STR);
            $dbCount->bindParam(':dateFrom', $dateFrom, PDO::PARAM_STR);
            $dbCount->bindParam(':dateTo', $dateTo, PDO::PARAM_STR);
            // $dbCount->bindParam('categoryString', $categoryString, PDO::PARAM_STR);
        }

        $dbCount->bindParam(':is_publish', $is_publish, PDO::PARAM_BOOL);
        $dbCount->execute();
        $total = $dbCount->fetchAll()[0]['total'];
        $db->bindParam(':limitPost', $limitPost, PDO::PARAM_INT);
        $db->bindParam(':offset', $offset, PDO::PARAM_INT);
        $db->bindParam(':is_publish', $is_publish, PDO::PARAM_BOOL);
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
                'is_publish' => $posts[$i]['is_publish'],
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

    public function fetchPostsAuth($category, $limitPost = 10, $page = 1, $title = null,  $dateFrom = null, $dateTo = null, $is_publish = 'all')
    {
        if($is_publish!='all') {
            if($is_publish) {
                return $this->fetchPosts($category, $limitPost, $page, $title,  $dateFrom, $dateTo, 1);
            } else {
                
                return $this->fetchPosts($category, $limitPost, $page, $title,  $dateFrom, $dateTo, 0);
            }
        } else {
            $offset = ($page - 1) * $limitPost;
            $sql = "SELECT * FROM posts order by id LIMIT :limitPost OFFSET :offset";
            $sqlCount = "SELECT count(id) as total FROM posts;";
            $db = $this->connection->prepare($sql);
            $dbCount = $this->connection->prepare($sqlCount);
            if ($title) {
                $sql = "SELECT * FROM posts where title like :title order by id LIMIT :limitPost OFFSET :offset";
                $titleString = '%' . $title . '%';
                $db = $this->connection->prepare($sql);
                $db->bindParam(':title', $titleString, PDO::PARAM_STR);
    
                $sqlCount = "SELECT count(id) as total FROM posts where title like :title;";
                $dbCount = $this->connection->prepare($sqlCount);
                $dbCount->bindParam(':title', $titleString, PDO::PARAM_STR);
            }
            if ($category) {
                $categoryString = '(' . join(", ", $category) . ')';
                $sql = "SELECT * FROM posts where category in {$categoryString} order by id LIMIT :limitPost OFFSET :offset";
                // $db->bindParam('categoryString', $categoryString, PDO::PARAM_STR);
                $sqlCount = "SELECT count(id) as total FROM posts where category in {$categoryString};";
                $db = $this->connection->prepare($sql);
                $dbCount = $this->connection->prepare($sqlCount);
            }
            if ($category && $dateFrom && $dateTo) {
                $categoryString = '(' . join(", ", $category) . ')';
                $sql = "SELECT * FROM posts where category in {$categoryString} and (created_at between :dateFrom and :dateTo) order by id LIMIT :limitPost OFFSET :offset;";
                $titleString = '%' . $title . '%';
                
                $db = $this->connection->prepare($sql);
                $db->bindParam(':dateFrom', $dateFrom, PDO::PARAM_STR);
                $db->bindParam(':dateTo', $dateTo, PDO::PARAM_STR);
                // $db->bindParam('categoryString', , PDO::PARAM);
                
                $sqlCount = "SELECT count(id) as total FROM posts where category in {$categoryString} and (created_at between :dateFrom and :dateTo);";
                $dbCount = $this->connection->prepare($sqlCount); //and category in :categoryString;
                $dbCount->bindParam(':dateFrom', $dateFrom, PDO::PARAM_STR);
                $dbCount->bindParam(':dateTo', $dateTo, PDO::PARAM_STR);
            }
            if ($title && $dateFrom && $dateTo) {
                $sql = "SELECT * FROM posts where title like :title and (created_at between :dateFrom and :dateTo) order by id LIMIT :limitPost OFFSET :offset;";
                $titleString = '%' . $title . '%';
                $db = $this->connection->prepare($sql);
      
                $db->bindParam(':title', $titleString, PDO::PARAM_STR);
                $db->bindParam(':dateFrom', $dateFrom, PDO::PARAM_STR);
                $db->bindParam(':dateTo', $dateTo, PDO::PARAM_STR);
                // $db->bindParam('categoryString', , PDO::PARAM);
                
                $sqlCount = "SELECT count(id) as total FROM posts where title like :title and (created_at between :dateFrom and :dateTo);";
                $dbCount = $this->connection->prepare($sqlCount); //and category in :categoryString;
                $dbCount->bindParam(':title', $titleString, PDO::PARAM_STR);
                $dbCount->bindParam(':dateFrom', $dateFrom, PDO::PARAM_STR);
                $dbCount->bindParam(':dateTo', $dateTo, PDO::PARAM_STR);
            }
    
            if ($title && $category) {
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
            } else if($title && $category && $dateFrom && $dateTo) {
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
                    'is_publish' => $posts[$i]['is_publish'],
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
       
    }

    public function updatePost($id, $title, $category, $description, $content, $is_publish, $userId)
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
                updated_by=:updated_by,
                is_publish=:is_publish
                 WHERE id=:id";

        $db = $this->connection->prepare($sql);
        $db->bindParam(':updated_at', $date, PDO::PARAM_STR);
        $db->bindParam(':title', $title, PDO::PARAM_STR);
        $db->bindParam(':category', $category, PDO::PARAM_INT);
        $db->bindParam(':description', $description, PDO::PARAM_STR);
        $db->bindParam(':content', $content, PDO::PARAM_STR);
        $db->bindParam(':updated_by', $userId, PDO::PARAM_INT);
        $db->bindParam(':id', $id, PDO::PARAM_INT);
        $db->bindParam(':is_publish', $is_publish, PDO::PARAM_BOOL);
        $db->execute();
    }

    public function createPost($title, $category, $description, $content, $userId, $is_publish)
    {
        $sql = "INSERT 
            posts (title, category, description, content, created_by, updated_by, is_publish) 
        values (:title, :category, :description, :content, :userId, :userId, :is_publish);";
        $db = $this->connection->prepare($sql);
        $db->bindParam(':title', $title, PDO::PARAM_STR);
        $db->bindParam(':category', $category, PDO::PARAM_INT);
        $db->bindParam(':description', $description, PDO::PARAM_STR);
        $db->bindParam(':content', $content, PDO::PARAM_STR);
        $db->bindParam(':userId', $userId, PDO::PARAM_INT);
        $db->bindParam(':userId', $userId, PDO::PARAM_INT);
        $db->bindParam(':is_publish', $is_publish, PDO::PARAM_BOOL);
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
        $sql = "UPDATE posts SET is_publish=:is_publishis_public, updated_at=:updated_at, updated_by=:userId where id=:id;";
        $db = $this->connection->prepare($sql);
        $db->bindParam(':id', $id, PDO::PARAM_INT);
        $db->bindParam(':updated_at', $date, PDO::PARAM_STR);
        $db->bindParam(':userId', $userId, PDO::PARAM_INT);
        $db->bindParam(':is_public', $is_public, PDO::PARAM_BOOL);
        $db->execute();
    }

    public function fetchListIdPostsOfCategory($categoryId) {
        $sql = 'SELECT id from posts where category=:categoryId;';
        $db = $this->connection->prepare($sql);
        $db->bindParam(':categoryId', $categoryId, PDO::PARAM_INT);
        $db->execute();
        $postsId = $db->fetchAll();
        return $postsId;
    }
}
