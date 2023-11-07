<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Task;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;


/**
 * @Route("/api/tasks")
 */
class TaskController extends AbstractController
{
    /**
     * @Route("/", name="create_task", methods={"POST"})
     */
    public function createTask(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $task = new Task();
        $task->setText($data['text']);
        $task->setStatus('new');
        $task->setViewCount(0);

        $entityManager->persist($task);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Task created'], Response::HTTP_CREATED);
    }

    /**
     * @Route("/{id}/complete", name="complete_task", methods={"PUT"})
     */
    public function completeTask(Task $task, EntityManagerInterface $entityManager): JsonResponse
    {
        $task->setStatus('completed');
        $entityManager->flush();

        return new JsonResponse(['message' => 'Task marked as completed'], Response::HTTP_OK);
    }

    /**
     * @Route("/{id}", name="delete_task", methods={"DELETE"})
     */
    public function deleteTask(Task $task, EntityManagerInterface $entityManager): JsonResponse
    {
        $entityManager->remove($task);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Task deleted'], Response::HTTP_OK);
    }

    /**
     * @Route("/{id}", name="update_task", methods={"PUT"})
     */
    public function updateTask(Request $request, Task $task, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $task->setText($data['text']);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Task updated'], Response::HTTP_OK);
    }

    /**
     * @Route("/", name="get_tasks", methods={"GET"})
     */
    public function getTasks(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $page = $request->query->get('page', 1);
        $limit = $request->query->get('limit', 10);

        $repository = $entityManager->getRepository(Task::class);
        $tasks = $repository->findBy([], ['id' => 'DESC'], $limit, ($page - 1) * $limit);

        $tasksArray = [];
        foreach ($tasks as $task) {
            // Increase the number of views per call
            $task->setViewCount($task->getViewCount() + 1);

            // Logic for setting task status as required
            if ($task->getStatus() === 'new' && $task->getViewCount() > 0) {
                $task->setStatus('viewed');
            }
            if ($task->getStatus() === 'viewed') {
                $task->setStatus('important');
            }

            $entityManager->persist($task);
            $entityManager->flush();

            $tasksArray[] = [
                'id' => $task->getId(),
                'text' => $task->getText(),
                'status' => $task->getStatus(),
                'viewCount' => $task->getViewCount(),
            ];
        }

        return new JsonResponse($tasksArray, Response::HTTP_OK);
    }

    /**
     * @Route("/{id}/view", name="view_task", methods={"GET"})
     */
    public function viewTask(Task $task, EntityManagerInterface $entityManager): JsonResponse
    {
        // Increasing Views
        $task->setViewCount($task->getViewCount() + 1);

        $entityManager->persist($task);
        $entityManager->flush();

        return new JsonResponse(['message' => 'Task viewed'], Response::HTTP_OK);
    }

}
