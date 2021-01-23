<?php

namespace App\Application\Actions\Posts;

use App\Application\Actions\Posts\PostsActions;
use Respect\Validation\Validator as v;
use App\Requests\CustomRequestHandler;
use Exception;
use Slim\Exception\HttpInternalServerErrorException;

class CreatePost extends PostsActions
{
    public function action()
    {
        try {
            $token = $this->request->getAttribute('token');
            $this->validator->validate($this->request, [
                "title" => v::notEmpty(),
                "description" => v::notEmpty(),
                "content" => v::notEmpty(),
                'category' => v::digit()
            ]);
            if ($this->validator->failed()) {
                $responseMessage = $this->validator->errors;
                return $this->respondWithData($responseMessage, 404);
            }

            $title = CustomRequestHandler::getParam($this->request, "title");
            $category = CustomRequestHandler::getParam($this->request, "category");
            $description = CustomRequestHandler::getParam($this->request, "description");
            $content = CustomRequestHandler::getParam($this->request, "content");

            // get list images
            $images = CustomRequestHandler::getParam($this->request, "images");
            $imagesExists = [];
            for($i = 0; $i < count($images); $i++) {
                $isImageExist = (int)$this->checkImageExistByUrl($images[$i]);
                array_push($imagesExists, $isImageExist);
                if(!$isImageExist) {
                    return $this->respondWithData("Link Image: {$images[$i]} is not exits", 400);
                }
            }
            // create new Post
            $newId = $this->postsServices->createPost($title, $category, $description, $content, $token['id']);
            
            // link image with post
            for($i = 0; $i < count($imagesExists); $i++) {
                $this->PIServices->linkPostWithImage($newId, $imagesExists[$i], $token['id']);
                $this->assetsServices->useImage($imagesExists[$i], true);
            }
            $newPost = $this->postsServices->fetchPostById($newId);
            return $this->respondWithData($newPost);
        } catch (Exception $e) {
            throw new HttpInternalServerErrorException($this->request, $e->getMessage());
        }
    }
}
