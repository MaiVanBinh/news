<?php

namespace App\Application\Actions\Posts;
use App\Application\Actions\Posts\PostsActions;
use Exception;
use Slim\Exception\HttpInternalServerErrorException;

class FetchPosts extends PostsActions {
    public function action() {
        try {
            $query = $this->request->getQueryParams();
            $limit = array_key_exists('limit', $query) && $query['limit'] ? $query['limit'] : 5;
            $page = array_key_exists('page', $query) && $query['page'] ? $query['page'] : 1;
            $category = array_key_exists('category', $query) && $query['category'] ? json_decode($query['category']) : '';
            $title = array_key_exists('title', $query) && $query['title'] ? $query['title'] : null;
            $dateFrom = array_key_exists('dateFrom', $query) && $query['dateFrom'] ? $query['dateFrom'] : null;
            $dateTo = array_key_exists('dateTo', $query) && $query['dateTo'] ? $query['dateTo'] : null;
            $posts = $this->postsServices->fetchPosts($category, $limit, $page, $title, $dateFrom, $dateTo);
            return $this->respondWithData($posts);
        } catch(Exception $err) {
            throw new HttpInternalServerErrorException($this->request, $err->getMessage());
        }
    }
} 