<?php

namespace App\Manager;

use App\Entity\Image;
use App\Entity\Project;
use App\Entity\Status;
use App\Exceptions\FileNotFoundException;
use App\Exceptions\ProjectNotFoundException;
use App\Exceptions\StatusNotFoundException;
use App\Services\SendMail;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Exception;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\Exception\OptionDefinitionException;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class ProjectManager
{
    /**
     * @var ParameterBagInterface
     */
    private $params;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    /**
     * @var SendMail
     */
    private $sendMail;

    public function __construct(ValidatorInterface $validator,
                                EntityManagerInterface $entityManager,
                                SendMail $sendMail)
    {
        $this->entityManager = $entityManager;
        $this->validator = $validator;
        $this->sendMail = $sendMail;
    }

    public function create(Request $request)
    {
        $status = $this->entityManager
            ->getRepository(Status::class)
            ->find(['id' => (int) $request->get('status')]);

        if (!($status instanceof Status)) {
            throw new StatusNotFoundException();
        }

        $project = new Project();
        $project->setTitle($request->get('title'))
            ->setDescription($request->get('description'))
            ->setTasksNumber($request->get('tasksNumber'))
            ->setPath($request->get('path'))
            ->setFileName($request->get('fileName'))
            ->setStatus($status);

        $validatorErrors = $this->validator->validate($project);

        if (count($validatorErrors) > 0) {
            throw new \Exception('Please check data');
        }

        $this->entityManager->persist($project);
        $this->entityManager->flush();

        return $project;
    }

    public function all(array $params)
    {
        return $projects = $this->entityManager
            ->getRepository(Project::class)
            ->findByProjects($params);
    }

    public function update(Request $request, $id)
    {
        $status = $this->entityManager
            ->getRepository(Status::class)
            ->find(['id' => (int) $request->get('status')]);

        if (!($status instanceof Status)) {
            throw new StatusNotFoundException();
        }

        $project = $this->find(['id' => (int) $id]);

        $project->setTitle($request->get('title'))
            ->setDescription($request->get('description'))
            ->setTasksNumber($request->get('tasksNumber'))
            ->setPath($request->get('path'))
            ->setFileName($request->get('fileName'))
            ->setStatus($status);

        $validatorErrors = $this->validator->validate($project);

        if (count($validatorErrors) > 0) {
            throw new \Exception('Please check data');
        }
        $this->entityManager->flush();


        return $project;
    }

    public function delete($id)
    {
        $project = $this->find($id);

        try {
            $this->entityManager->remove($project);
            $this->entityManager->flush();
        } catch(Exception $exception) {
            throw new \Exception($exception);
        }
    }

    public function find($id) {
        $project = $this->entityManager
            ->getRepository(Project::class)
            ->findOneBy(['id' => $id]);

        if (!($project instanceof Project)) {
            throw new ProjectNotFoundException();
        }

        return $project;
    }
}