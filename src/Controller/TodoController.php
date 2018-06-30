<?php

namespace App\Controller;

use App\Entity\Todo;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\FOSRestController as Controller;
use FOS\RestBundle\Controller\Annotations as Rest;

class TodoController extends Controller
{
    /**
     * @Rest\Get("/api/todo", name="todo.list")
     * @View(serializerGroups={"list"})
     */
    public function list()
    {
        $view = $this->view($this->getDoctrine()->getRepository(Todo::class)->findAll(), 200);
        return $this->handleView($view);
    }
}

// alias xunit='PHP_IDE_CONFIG="serverName=debug" php72 -dxdebug.remote_enable=1 -dxdebug.remote_host=127.0.0.1 -dxdebug.remote_port=12345 -dxdebug.idekey=PHPSTORM -dxdebug.remote_autostart=1 bin/phpunit'