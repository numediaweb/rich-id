<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/admin/users/{id}", name="admin_user")
     */
    public function single(User $user, ManagerRegistry $doctrine): Response
    {
        return $this->render('admin.html.twig', [
            'template_part' => 'admin_user',
            'user' => $user,
        ]);
    }

    /**
     * @Route("/admin/user/new", name="admin_user_new")
     */
    public function add(
        Request $request,
        ManagerRegistry $doctrine,
        UserPasswordHasherInterface $userPasswordHasher
    ): Response {
        $entityManager = $doctrine->getManager();

        // creates an user object
        $user = new User();

        $userForm = $this->createForm(UserType::class, $user);

        // `$user` variable has also been updated
        $userForm->handleRequest($request);

        if ($userForm->isSubmitted() && $userForm->isValid()) {
            $encodedPassword = $userPasswordHasher->hashPassword($user, $user->getPlainPassword());
            $user->setPassword($encodedPassword);

            try {
                $entityManager->persist($user);
                $entityManager->flush();
            } catch (\Exception $e) {
                return $this->renderForm('admin.html.twig',
                    [
                        'template_part' => 'admin_user_new',
                        'userForm' => $userForm,
                        'error' => $e->getMessage(),
                    ]);
            }

            return $this->redirectToRoute('admin_user', ['id' => $user->getId()]);
        }

        return $this->renderForm('admin.html.twig',
            ['template_part' => 'admin_user_new', 'userForm' => $userForm]);
    }
}
