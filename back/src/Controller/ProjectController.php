<?php

namespace App\Controller;

use App\Exceptions\UserNotAllowedException;
use App\Manager\ProjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Security\Core\Security;

class ProjectController extends AbstractController
{
    /**
     * @var ProjectManager
     */
    private $projectManager;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var Security
     */
    private $user;

    public function __construct(ProjectManager $projectManager, Security $security, SerializerInterface $serializer)
    {
        $this->projectManager = $projectManager;
        $this->serializer = $serializer;
        $this->user = $security->getUser();
    }

    #[Route('/api/projects', methods: ['GET'])]
    public function getAllProject(Request $request) {
        $projects = $this->projectManager->all($request->query->all());

        return new JsonResponse($projects, 200);
    }

    #[Route('/api/projects', methods: ['POST'])]
    public function createProject(Request $request) {

        if(!in_array("ADMIN", $this->user->getRoles())) {
            throw new UserNotAllowedException();
        }

        $data = json_decode($request->getContent(),true);
        $request->request->replace($data);
        $project = $this->projectManager->create($request);
        $serialize = $this->serializer->serialize($project, 'json');

        return new Response($serialize, 201);
    }

    #[Route('/api/projects/{id}', methods: ['PUT'])]
    public function updateProject(Request $request, $id) {

        if(!in_array("ADMIN", $this->user->getRoles())) {
            throw new UserNotAllowedException();
        }

        $data = json_decode($request->getContent(),true);
        $request->request->replace($data);
        $project = $this->projectManager->update($request, $id);
        $serialize = $this->serializer->serialize($project, 'json');

        return new Response($serialize, 200);
    }

    #[Route('/api/projects/{id}', methods: ['DELETE'])]
    public function deleteProject($id) {

        if(!in_array("ADMIN", $this->user->getRoles())) {
            throw new UserNotAllowedException();
        }

        $this->projectManager->delete($id);

        return new JsonResponse([], 204);
    }

    #[Route('/api/projects/{id}', methods: ['GET'])]
    public function findProject($id) {
        $project = $this->projectManager->find($id);
        $serialize = $this->serializer->serialize($project, 'json');

        return new Response($serialize, 200);
    }

    public static function getSubscribedServices(): array
    {
        return [];
    }
}
