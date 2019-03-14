<?php
/**
 * Created by PhpStorm.
 * User: atairakhunov
 * Date: 014, 14 мар 2019
 * Time: 14:45
 */

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
}