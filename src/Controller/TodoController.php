<?php

namespace App\Controller;

use App\Entity\Todo;
use App\Form\TodoType;
use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\FOSRestController as Controller;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
     * @Rest\Patch("/api/todo/{id}", name="todo.edit")
     * @View(serializerGroups={"detail"}, statusCode=200)
     * @param Request   $request
     * @param Todo|null $todo
     * @return array|mixed
     */
    public function edit(Request $request, Todo $todo = null)
    {
        $form = $this->get('form.factory')->create(TodoType::class, $todo, ['method' => $request->getMethod()]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $todo = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($todo);
            $em->flush();
            return $todo;
        }

        $a = (string) $form->getErrors(true, false);

        return $this->view($form, 400);
    }

    /**
     * Note: I haven't put any validation on deleting, it'll return 404 if not found
     * @Rest\Delete("/api/todo/{id}", name="todo.delete")
     * @param Todo $todo
     * @return Response
     */
    public function delete(Todo $todo)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($todo);
        $em->flush();
        return new Response("", 204);
    }
}
