<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(Request $request, ManagerRegistry $doctrine): Response
    {
        $userRepository = $doctrine->getRepository(User::class);

        // retrieves search keyword
        $keyword = $request->request->get('keyword');

        // Ajax requests
        if ($request->isXmlHttpRequest()) {
            $users = $userRepository->findByKeyword($keyword);

            return $this->render('sections/admin_users.html.twig', ['users' => $users, 'keyword' => $keyword]);
        }

        // Non Ajax requests
        $users = $userRepository->findAll();

        // Return the results
        return $this->render('admin.html.twig', ['template_part' => 'admin_users', 'users' => $users, 'keyword' => $keyword]);
    }
}
