<?php

namespace App\Domain\Assets;

use PDO;

class AssetsServices
{
    private $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function fetchAsset($page = 1, $limit = 10, $name)
    {
        $offset = $limit * ($page - 1);
        
        $sql = "SELECT * FROM images where name like :nameString LIMIT :limit offset :offset;";
        $db = $this->connection->prepare($sql);
        $db->bindParam(':offset', $offset, PDO::PARAM_INT);
        $db->bindParam(':limit', $limit, PDO::PARAM_INT);
        $nameString = '%' . $name . '%';
        $db->bindParam(':nameString', $nameString, PDO::PARAM_STR);
        $db->execute();
        $images = $db->fetchAll();
        
        $sqlCount = "SELECT count(id) as total from images where name like :nameString;";
        $db = $this->connection->prepare($sqlCount);
        $db->bindParam(':nameString', $nameString, PDO::PARAM_STR);
        $db->execute();
        $total = $db->fetchAll()[0]['total'];
        return ['total' => $total, 'images' => $images];
    }

    public function fetchAssetByPostId($postId) {
        $sql = "SELECT i.id FROM images i, post_image p WHERE p.post=:postId and i.id=image; ";
        $db = $this->connection->prepare($sql);
        $db->bindParam(':postId', $postId, PDO::PARAM_INT);
        $db->execute();
        $images = $db->fetchAll();
        return $images;
    }

    public function checkAssetInUse($imageId) {
        $sql = "SELECT * FROM post_image WHERE image=:imageId;";
        $db = $this->connection->prepare($sql);
        $db->bindParam(':imageId', $imageId, PDO::PARAM_INT);
        $db->execute();
        $images = $db->fetchAll();
        if(count($images) > 0) {
            return true;
        }
        return false;
    }
    public function createAsset($url, $name, $size, $userId)
    {
        $sql = "INSERT INTO images (url, name, size, created_by, updated_by) VALUES (:url, :name, :size, :userId, :userId);";
        $db = $this->connection->prepare($sql);
        $db->bindParam(':url', $url, PDO::PARAM_STR);
        $db->bindParam(':name', $name, PDO::PARAM_STR);
        $db->bindParam(':size', $size, PDO::PARAM_STR);
        $db->bindParam(':userId', $userId, PDO::PARAM_INT);
        $db->execute();
        return (int)$this->connection->lastInsertId();
    }

    public function countEntries()
    {
        $sql = "SELECT count(id) as total from images;";
        $db = $this->connection->prepare($sql);
        $db->execute();
        $total = $db->fetchAll();
        $total = $total[0]['total'];
        return $total;
    }

    public function fetchAssetById($id)
    {
        $sql = "SELECT * from images where id=:id;";
        $db = $this->connection->prepare($sql);
        $db->bindParam(':id', $id, PDO::PARAM_INT);
        $db->execute();
        $assets = $db->fetchAll();
        return $assets;
    }

    public function unLinkAssetCretures($creatureId)
    {
        $sql = "DELETE FROM assets_creatures where creature=:creatureId";
        $db = $this->connection->prepare($sql);
        $db->bindParam(':creatureId', $creatureId, PDO::PARAM_INT);
        $db->execute();
    }

    public function unLinkImagePost($postId)
    {
        $sql = "DELETE FROM post_image where post=:post";
        $db = $this->connection->prepare($sql);
        $db->bindParam(':post', $postId, PDO::PARAM_INT);
        $db->execute();
    }

    // delete all row in post_image with image Id
    public function unLink($image) {
        $sql = "DELETE FROM post_image where image=:image";
        $db = $this->connection->prepare($sql);
        $db->bindParam(':image', $image, PDO::PARAM_INT);
        $db->execute();
    }


    public function unlinkBaseOnImageAndPost($image, $post) {
        $sql = "DELETE FROM post_image where image=:image and post=:post";
        $db = $this->connection->prepare($sql);
        $db->bindParam(':image', $image, PDO::PARAM_INT);
        $db->bindParam(':post', $post, PDO::PARAM_INT);
        $db->execute();
    }

    public function deleteAsset($id) {
        $sql = "DELETE FROM images WHERE id=:id;";
        $db = $this->connection->prepare($sql);
        $db->bindParam(':id', $id, PDO::PARAM_INT);
        $db->execute();
    }

    public function findImageByUrl($url) {
        $sql = "SELECT id from images where url=:url;";
        $db = $this->connection->prepare($sql);
        $db->bindParam(":url", $url, PDO::PARAM_STR);
        $db->execute();
        $images = $db->fetchAll();
        return $images;
    }

    public function useImage($imageId, $isUse) {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $date = date("Y-m-d H:i:s");
        $sql = 'UPDATE images set in_use=:isUse, updated_at=:date where id=:imageId;';
        $db = $this->connection->prepare($sql);
        $db->bindParam(":isUse", $isUse, PDO::PARAM_BOOL);
        $db->bindParam(":imageId", $imageId, PDO::PARAM_INT);
        $db->bindParam(":date", $date, PDO::PARAM_STR);
        $db->execute();
    }
    
}
