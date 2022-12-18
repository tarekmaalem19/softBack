<?php

namespace App\Repository;

use App\Entity\Project;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Project>
 *
 * @method Project|null find($id, $lockMode = null, $lockVersion = null)
 * @method Project|null findOneBy(array $criteria, array $orderBy = null)
 * @method Project[]    findAll()
 * @method Project[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Project::class);
    }

    /**
     * @param array $params
     * @return array Returns an array of Project objects
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findByProjects($params = []): array
    {
        $itemPerPage = isset($params['itemPerPage']) ?: 10;
        $page = isset($params['itemPerPage']) ?: 1;
        $query = $this->createQueryBuilder('p');
        $query->innerJoin('p.status', 's');

        if (isset($params['title']) && !empty($params['title'])) {
            $query->andWhere('p.title LIKE :title')
                ->setParameter('title', $params['title']);
        }

        if (isset($params['status']) && !empty($params['status'])) {
            $query->andWhere('s.id = :status')
                ->setParameter('status', $params['status']);
        }

        if (isset($params['path']) && !empty($params['path'])) {
            $query->andWhere('p.path LIKE :path')
                ->setParameter('path', $params['path']);
        }

        if (isset($params['fileName']) && !empty($params['fileName'])) {
            $query->andWhere('p.fileName LIKE :fileName')
                ->setParameter('fileName', $params['fileName']);
        }

        $count = $query->select('count(p.id)')->getQuery()->getSingleScalarResult();
        $query->select('p.id','p.title','p.description','p.tasksNumber','p.fileName','p.path','s.title as status');

        $result = $query
            ->setFirstResult($itemPerPage * ($page - 1))
            ->setMaxResults($itemPerPage)
            ->orderBy('p.id', 'ASC')
            ->getQuery()
            ->getResult();

        return ['projects' => $result , 'total' => $count, 'page' =>  $page, 'itemPerPage' => $itemPerPage];
    }
}
