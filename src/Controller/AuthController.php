<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Config\Doctrine\Orm\EntityManagerConfig;

class AuthController extends AbstractController
{
    /**
     * @Route("/auth", name="auth")
     */
    public function index(): Response
    {
        return $this->render('auth/index.html.twig', [
            'controller_name' => 'AuthController',
        ]);
    }

    /**
     *
     * @Route("/register", name="register")
     *
     */
    public function register(Request $requete, EntityManagerInterface $manager, UserPasswordHasherInterface $hasher)
    {
        $user = new User();
        $formulaire = $this->createForm(UserType::class, $user);

        $formulaire->handleRequest($requete);

        if($formulaire->isSubmitted() && $formulaire->isValid()){
            $hashedPassword = $hasher->hashPassword($user, $user->getPassword());

            $user->setPassword($hashedPassword);
                $manager->persist($user);
                $manager->flush();

            return $this->redirectToRoute('telephone');
        }
            return $this->render('auth/register.html.twig', [
                'formulaire' => $formulaire->createView()
            ]);
    }

    /**
     *
     * @Route("/login", name="login")
     *
     */
    public function login(){
        return $this->render('auth/login.html.twig');
    }

    /**
     *
     * @Route("/logout", name="logout")
     *
     */
    public function logout(){

    }
}
