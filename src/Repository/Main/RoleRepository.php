<?php
namespace App\Repository\Main;


use App\Entity\Main\Role;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class RoleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Role::class);
    }

    public function getAllOrderedByTitle()
    {
        return $this->findBy(array(), array('title' => 'ASC'));
    }
}