<?php

namespace App\Application\Actions\Category;

use App\Application\Actions\Actions;
use App\Domain\Category\CategoryServices;
use App\Domain\User\UserServices;
use Exception;
use Psr\Log\LoggerInterface;
use App\Application\Actions\Validator;

abstract class CategoryAction extends Actions {
    protected $categoryServices;
    protected $userServices;
    protected $validator;
    public function __construct(CategoryServices $categoryServices, LoggerInterface $logger, UserServices $userServices, Validator $validator)
    {
        parent::__construct($logger);
        $this->categoryServices = $categoryServices;
        $this->userServices = $userServices;
        $this->validator = $validator;
    }

    public function checkUserExist($id) {
        try {
            $user = $this->userServices->findUserById($id);
            if(count($user) > 0) {
                return true;
            } else {
                return false;
            }
        } catch(Exception $ex) {
            throw $ex->getMessage();
        }
    }
}