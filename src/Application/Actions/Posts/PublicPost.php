<?php

namespace App\Application\Actions\Posts;

use App\Application\Actions\Posts\PostsActions;
use Exception;
use Slim\Exception\HttpInternalServerErrorException;

class PublicPost extends PostsActions
{
    public function action()
    {
        try {
            $token = $this->request->getAttribute('token');
            $id = (int) $this->resolveArg('id');
            if($id) {
                // create new Post
                $this->postsServices->publicPost($id, $token['id']);
                return $this->respondWithData("Post is published");
            } 
            
        } catch (Exception $e) {
            throw new HttpInternalServerErrorException($this->request, $e->getMessage());
        }
    }
}
