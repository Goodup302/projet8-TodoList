<?php

namespace App\Controller;

use App\Entity\Role;
use App\Entity\Task;
use App\Entity\User;
use App\Form\TaskType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class TaskController extends AbstractController
{
    const PERMISSION_DENY = "Vous n'avez pas la permission pour réaliser cette action.";

    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

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
     * @Route("/tasks/{filter}", defaults={"filter": "all"}, name="task_list")
     */
    public function listAction(string $filter)
    {
        /** @var User $user */
        $user = $this->getUser();
        $repositoryTask = $this->getDoctrine()->getRepository(Task::class);
        if ($user->getRoleName() === Role::ADMIN) {
            $repositoryRole = $this->getDoctrine()->getRepository(Role::class);
            $repositoryUser = $this->getDoctrine()->getRepository(User::class);
            $anonymousRole = $repositoryRole->findOneBy(['name' => Role::ANONYMOUS]);
            $anonymousUser = $repositoryUser->findOneBy(['role' => $anonymousRole]);
            $userFilter = [$user, $anonymousUser];
        } else {
            $userFilter = [$user];
        }

        if ($filter == "done") {
            $tasks = $repositoryTask->findBy(['isDone' => true, 'user' => $userFilter]);
            $title = "Liste des tâches terminée";
        } else if ($filter == "todo") {
            $tasks = $repositoryTask->findBy(['isDone' => false, 'user' => $userFilter]);
            $title = "Liste des tâches à faire";
        } else {
            $tasks = $repositoryTask->findBy(['user' => $userFilter]);
            $title = "Liste de toutes les tâches";
        }
        return $this->render('task/list.html.twig', [
            'tasks' => $tasks,
            'filter' => $filter,
            'title' => $title,
        ]);
    }

    /**
     * @Route("/task/create", name="task_create")
     * @IsGranted("IS_AUTHENTICATED_FULLY"))
     */
    public function createAction(Request $request)
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task)->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $task->setUser($this->getUser());
            $this->em->persist($task);
            $this->em->flush();

            $this->addFlash('success', 'La tâche a été bien été ajoutée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/create.html.twig', ['form' => $form->createView()]);
    }


    ///////////////////////
    // UPDATE TASK ROUTE //
    ///////////////////////


    /**
     * @Route("/task/{id}/edit", name="task_edit")
     * @IsGranted("IS_AUTHENTICATED_FULLY"))
     */
    public function editAction(Task $task, Request $request)
    {
        if ($this->taskProtection($task)) {
            //Form
            $form = $this->createForm(TaskType::class, $task)->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $this->em->flush();
                $this->addFlash('success', 'La tâche a bien été modifiée.');
                return $this->redirectToRoute('task_list');
            }

            return $this->render('task/edit.html.twig', [
                'form' => $form->createView(),
                'task' => $task,
            ]);
        } else {
            return $this->redirect($request->headers->get('referer'));
        }
    }

    /**
     * @Route("/task/{id}/toggle", name="task_toggle")
     * @IsGranted("IS_AUTHENTICATED_FULLY"))
     */
    public function toggleTaskAction(Task $task, Request $request)
    {
        if ($this->taskProtection($task)) {
            $task->toggle(!$task->isDone());
            $this->em->flush();
            //Flash alert
            $status = $task->isDone() ? "faite" : "non terminée";
            $this->addFlash('success', "La tâche {$task->getTitle()} a bien été marquée comme $status.");
        }
        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("/task/{id}/delete", name="task_delete")
     * @IsGranted("IS_AUTHENTICATED_FULLY"))
     */
    public function deleteTaskAction(Task $task, Request $request)
    {
        if ($this->taskProtection($task)) {
            $this->em->remove($task);
            $this->em->flush();
            $this->addFlash('success', 'La tâche a bien été supprimée.');
        }
        return $this->redirect($request->headers->get('referer'));
    }

    public function taskProtection(Task $task): bool
    {
        /** @var User $user */
        $user = $this->getUser();
        $userTask = $task->getUser();
        if ($userTask->getId() === $user->getId()) return true;
        if ($user->getRoleName() === Role::ADMIN && $userTask->getRoleName() === Role::ANONYMOUS) return true;
        $this->addFlash('error', self::PERMISSION_DENY);
        return false;
    }
}
