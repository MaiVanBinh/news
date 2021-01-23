<?php

namespace App\Application\Actions\Posts;

use App\Application\Actions\Posts\PostsActions;
use Exception;
use Slim\Exception\HttpInternalServerErrorException;
use Respect\Validation\Validator as v;
use App\Requests\CustomRequestHandler;
class PublicPost extends PostsActions
{
    public function action()
    {
        try {
            $token = $this->request->getAttribute('token');
            $id = (int) $this->resolveArg('id');
            $this->validator->validate($this->request, [
                "is_public" => v::boolType()
            ]);

            if ($this->validator->failed()) {
                $responseMessage = $this->validator->errors;
                return $this->respondWithData($responseMessage, 400);
            }

            $is_public = CustomRequestHandler::getParam($this->request, "is_public");
            if($id) {
                $this->postsServices->publicPost($id, $token['id'], $is_public);
                if($is_public) {
                    return $this->respondWithData("Post is published");
                } else {
                    return $this->respondWithData("Post is privated");
                }
            } 
            
        } catch (Exception $e) {
            throw new HttpInternalServerErrorException($this->request, $e->getMessage());
        }
    }
}
