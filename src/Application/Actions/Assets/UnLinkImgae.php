<?php

namespace App\Application\Actions\Assets;

use App\Application\Actions\Assets\AssetsAction;
use Exception;
use Psr\Http\Message\UploadedFileInterface;

class UnLinkImgae extends AssetsAction
{
    public function action()
    {
        try {
            $token = $this->request->getAttribute('token');
            $id =$this->resolveArg('id');
            $this->assetsServices->unLink($id);
            $this->assetsServices->useImage($id, false);
            return $this->respondWithData('Unlink Success', 200);
        } catch (Exception $ex) {
            return $this->respondWithData($ex->getMessage(), 200);
        }
    }
    
    public function moveUploadedFile(string $directory, UploadedFileInterface $uploadedFile, string $title)
    {
        $filename = strtotime("now") . $title . '.png';
        $uploadedFile->moveTo($directory . DIRECTORY_SEPARATOR . $filename);

        return explode(".", $filename)[0];;
    }
}
