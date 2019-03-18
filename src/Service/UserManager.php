<?php

namespace App\Service;


use App\Entity\Main\User;
use App\Repository\Main\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class UserManager
{
    private $em;
    private $userRepo;

    public function __construct(EntityManagerInterface $em, UserRepository $userRepo)
    {
        $this->em = $em;
        $this->userRepo = $userRepo;
    }

    public function getUsersList($page = 1, $limit = 10)
    {
        return $this->userRepo->getUsersListByPagesSortedByName($page, $limit);
    }

    public function get($id): User
    {
        return $this->userRepo->find($id);
    }

    public function remove($id)
    {
        $user = $this->get($id);
        $this->em->remove($user);
        $this->em->flush();
    }
}