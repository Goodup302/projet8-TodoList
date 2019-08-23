<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class TaskController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('task_list');
        } else {
            return $this->redirectToRoute('login');
        }

    }

    /**
     * @Route("/tasks-list/{filter}", defaults={"filter": "all"}, name="task_list")
     */
    public function listAction(string $filter)
    {
        $repository = $this->getDoctrine()->getRepository(Task::class);
        if ($filter == "done") {
            $tasks = $repository->findBy(['isDone' => true]);
            $title = "Liste des tâches terminée";
        } else if ($filter == "todo") {
            $tasks = $repository->findBy(['isDone' => false]);
            $title = "Liste des tâches à faire";
        } else {
            $tasks = $repository->findAll();
            $title = "Liste de toutes les tâches";
        }
        return $this->render('task/list.html.twig', [
            'tasks' => $tasks,
            'filter' => $filter,
            'title' => $title,
        ]);
    }

    /**
     * @Route("/tasks/create", name="task_create")
     * @IsGranted("IS_AUTHENTICATED_FULLY"))
     */
    public function createAction(Request $request)
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->persist($task);
            $em->flush();

            $this->addFlash('success', 'La tâche a été bien été ajoutée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/tasks/{id}/edit", name="task_edit")
     * @IsGranted("IS_AUTHENTICATED_FULLY"))
     */
    public function editAction(Task $task, Request $request)
    {
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'La tâche a bien été modifiée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/edit.html.twig', [
            'form' => $form->createView(),
            'task' => $task,
        ]);
    }

    /**
     * @Route("/tasks/{id}/toggle", name="task_toggle")
     * @IsGranted("IS_AUTHENTICATED_FULLY"))
     */
    public function toggleTaskAction(Task $task, Request $request)
    {
        $task->toggle(!$task->isDone());
        $this->getDoctrine()->getManager()->flush();

        $status = $task->isDone() ? "faite" : "non terminée";
        $this->addFlash('success', "La tâche {$task->getTitle()} a bien été marquée comme $status.");

        $referer = $request->headers->get('referer');
        return $this->redirect($referer);
    }

    /**
     * @Route("/tasks/{id}/delete", name="task_delete")
     * @IsGranted("IS_AUTHENTICATED_FULLY"))
     */
    public function deleteTaskAction(Task $task, Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($task);
        $em->flush();

        $this->addFlash('success', 'La tâche a bien été supprimée.');

        $referer = $request->headers->get('referer');
        return $this->redirect($referer);
    }
}
