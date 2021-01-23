<?php

use Slim\App;

use Slim\Interfaces\RouteCollectorProxyInterface as Group;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use App\Application\Actions\Posts\PostsFetchPostById;
use App\Application\Actions\Posts\FetchPosts;

use Slim\Exception\HttpNotFoundException;

return function(App $app) {
    
    $app->options('/{routes:.+}', function ($request, $response, $args) {
        return $response;
    });

    $app->get('/', function (Request $request, Response $response) {
        $response->getBody()->write('Hello World');
        return $response;
    });
    
    $app->group('/posts', function(Group $group) {
        $group->get('', FetchPosts::class);
        $group->get('/{id}', PostsFetchPostById::class);
    });

    $app->group('/users', function(Group $group) {
        $group->post('/login', \App\Application\Actions\User\Login::class);
        $group->post('/sign-up', \App\Application\Actions\User\Register::class);
    });
    
    $app->group('/auth/users', function(Group $group) {
        $group->post('/sign-up', \App\Application\Actions\User\CreateUser::class);
    });

    $app->group('/categories', function(Group $group) {
        $group->get('', \App\Application\Actions\Category\FetchCategory::class);
    });

    $app->group('/auth/categories', function(Group $group) {
        $group->post('', \App\Application\Actions\Category\CreateCategory::class);
        $group->delete('/{id}', \App\Application\Actions\Category\DeteleCatogory::class);
        $group->put('/{id}', \App\Application\Actions\Category\UpdateCategory::class);
    });

    $app->group('/auth/users', function(Group $group) {
        $group->get('/{id}', \App\Application\Actions\User\FetchUserById::class);
        $group->get('', \App\Application\Actions\User\FetchUsersAction::class);
        $group->delete('/{id}', \App\Application\Actions\User\UserDeleteAction::class);
    });

    $app->group('/auth/posts', function(Group $group) {
        $group->post('', \App\Application\Actions\Posts\CreatePost::class);
        $group->delete('/{id}', \App\Application\Actions\Posts\DeletePost::class);
        $group->put('/{id}', \App\Application\Actions\Posts\UpdatePost::class);
        $group->put('/{id}/public', \App\Application\Actions\Posts\PublicPost::class);
    });

    $app->get('/images/{fileName}', \App\Application\Actions\Assets\FetchAssetAction::class);

    $app->group('/auth/images', function(Group $group) {
        $group->get('', \App\Application\Actions\Assets\FetchAsset::class);
        $group->post('', \App\Application\Actions\Assets\CreateAsset::class);
        $group->get('/{id}/unlink', \App\Application\Actions\Assets\UnLinkImgae::class);
        $group->delete('/{id}', \App\Application\Actions\Assets\DeleteAsset::class);        
    });
    
    $app->map(['GET', 'POST', 'PUT', 'DELETE', 'PATCH'], '/{routes:.+}', function ($request, $response) {
        throw new HttpNotFoundException($request);
    });
};