<?php
namespace App\Application\Actions\Assets;
use App\Application\Actions\Assets\AssetsAction;
use Exception;
use Slim\Exception\HttpInternalServerErrorException;

class  FetchAsset extends AssetsAction {
    public function action() {
        try {
            $filter = $this->request->getQueryParams();
            $page = array_key_exists('page', $filter) ? intval($filter['page']) : 1; 
            $limit = array_key_exists('limit', $filter) ? intval($filter['limit']) : 10; 
            $name = array_key_exists('name', $filter) ? $filter['name'] : ''; 
            $assets = $this->assetsServices->fetchAsset($page, $limit, $name);

            return $this->respondWithData($assets, 200);
        } catch(Exception $ex) {
            throw new HttpInternalServerErrorException($this->request, $ex->getMessage());
        }
    }
}