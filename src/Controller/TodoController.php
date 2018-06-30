<?php

namespace App\Controller;

use App\Entity\Todo;
use App\Form\TodoType;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\FOSRestController as Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;

class TodoController extends Controller
{
    /**
     * @Rest\Get("/api/todo", name="todo.list")
     * @View(serializerGroups={"list"})
     */
    public function list()
    {
        return $this->getDoctrine()->getRepository(Todo::class)->findAll();
    }

    /**
     * @Rest\Post("/api/todo", name="todo.create")
     * @View(serializerGroups={"detail"}, statusCode=201)
     * @param Request $request
     * @return array|mixed
     */
    public function create(Request $request)
    {
        return $this->edit($request);
    }

    /**
     * @Rest\Post("/api/todo/{id}", name="todo.edit")
     * @View(serializerGroups={"detail"}, statusCode=200)
     * @param Request   $request
     * @param Todo|null $todo
     * @return array|mixed
     */
    public function edit(Request $request, Todo $todo = null)
    {
        $form = $this->get('form.factory')->create(TodoType::class, $todo);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $todo = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($todo);
            $em->flush();
            return $todo;
        }

        return $this->view($form, 400);
    }

    public function delete(Todo $todo)
    {


    }
}
