<?php

namespace App\Controller;

use App\Entity\Usercommission;
use App\Repository\UserRepository;
use App\Repository\CommissionRepository;
use App\Form\UserCommissionType;
use App\Repository\UsercommissionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UsercommissionController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
}