<?php

namespace App\Application\Actions\Category;
use App\Application\Actions\Category\CategoryAction;
use Exception;
use Slim\Exception\HttpInternalServerErrorException;

class FetchCategory extends CategoryAction {
    public function action() {
        try {
            $query = $this->request->getQueryParams();

            $title = array_key_exists('title', $query) && $query['title'] ? $query['title'] : '';

            // $from and $ti is date datatype
            $from = array_key_exists('from', $query) && $query['from'] ?  $query['from'] : null;
            $to = array_key_exists('to', $query) && $query['to'] ?  $query['to'] : null;
            $page = array_key_exists('page', $query) && $query['page'] ?  $query['page'] : 1;
            $limit = array_key_exists('limit', $query) && $query['limit'] ?  $query['limit'] : 1;
            $isAll = array_key_exists('isAll', $query) ?  true : false;
            $category = $this->categoryServices->fetchCategory($title, $from, $to, $page, $limit, $isAll);
            return $this->respondWithData($category);
        } catch(Exception $err) {
            throw new HttpInternalServerErrorException($this->request, $err->getMessage());
        }
    }
}