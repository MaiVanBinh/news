<?php

use App\Application\Actions\Actions;

class HomeAction extends Actions {
    public function action() {
        return $this->respondWithData("Hello you", 200);
    }
}