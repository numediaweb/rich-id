<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        // no need for pagination as it is not on the requirements!
        $users = $doctrine->getRepository(User::class)->findAll();

        return $this->render('admin.html.twig', ['template_part' => 'admin_users', 'users' => $users]);
    }
}
