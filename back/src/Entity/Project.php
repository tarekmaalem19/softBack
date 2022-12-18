<?php

namespace App\Entity;

use App\Repository\ProjectRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProjectRepository::class)]
class Project
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(length: 100, nullable: false)]
    #[Assert\NotBlank]
    private $title;

    #[ORM\Column(nullable: false)]
    #[Assert\NotBlank]
    private $tasksNumber;

    #[ORM\Column(length: 255, nullable: false)]
    #[Assert\NotBlank]
    private $description;

    #[ORM\Column(length: 50, nullable: false)]
    #[Assert\NotBlank]
    private $fileName;

    #[ORM\Column(length: 255, nullable: false)]
    #[Assert\NotBlank]
    private $path;

    #[ORM\ManyToOne(targetEntity: Status::class)]
    #[ORM\JoinColumn(nullable: false)]
    private $status;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getTasksNumber(): ?int
    {
        return $this->tasksNumber;
    }

    public function setTasksNumber(int $tasksNumber): self
    {
        $this->tasksNumber = $tasksNumber;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getStatus(): ?status
    {
        return $this->status;
    }

    public function setStatus(?status $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * @return mixed
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param mixed $fileName
     * @return Project
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;
        return $this;
    }

    /**
     * @param mixed $path
     * @return Project
     */
    public function setPath($path)
    {
        $this->path = $path;
        return $this;
    }
}
