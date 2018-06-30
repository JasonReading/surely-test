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
