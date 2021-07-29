<?php

namespace App\Controller;

use App\Entity\Constructeur;
use App\Form\ConstructeurType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ConstructeurRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class ConstructeurController extends AbstractController
{
    /**
     * @Route("/constructeur", name="constructeur")
     */
    public function index(ConstructeurRepository $repo): Response
    {
        $constructeurs = $repo->findAll();

        return $this->render('constructeur/index.html.twig', [
            'controller_name' => 'ConstructeurController',
            'constructeurs' => $constructeurs
        ]);
    }

    /**
     *
     * @Route("/constructeur/{id}", name="show_constructeur", requirements={"id"="\d+"})
     *
     */
    public function show(Constructeur $constructeur): Response
    {
        return $this->render('constructeur/show.html.twig', [
            'controller_name' => 'ConstructeurController',
            'constructeur' => $constructeur
        ]);
    }

    /**
     *
     * @Route("/constructeur/create/", name="create_constructeur")
     * @Route("/constructeur/edit/{id}", name="edit_constructeur", requirements={"id"="\d+"})
     */
    public function create(EntityManagerInterface $manager, Request $requete, Constructeur $constructeur = null)
    {
        $modeCreation = false;
        if (!$constructeur) {
            $constructeur = new Constructeur();
            $modeCreation = true;
        }

        $formulaire = $this->createForm(ConstructeurType::class, $constructeur);

        $formulaire->handleRequest($requete);

        if ($formulaire->isSubmitted() && $formulaire->isValid()) {
            $image = $formulaire->get('imageLogo')->getData();

            if ($image) {
                try {
                    $nomImage = uniqid() . '.' . $image->guessExtension();

                    $image->move(
                        $this->getParameter('images_constructeurs'),
                        $nomImage
                    );
                    if ($modeCreation || (!$modeCreation && $image)) {
                        $constructeur->setImageLogo($nomImage);
                    }
                } catch (FileException $e) {
                    throw $e;
                    return $this->redirectToRoute('create_constructeur');
                }
            }
            $manager->persist($constructeur);
            $manager->flush();

            return $this->redirectToRoute('show_constructeur', [
                'id' => $constructeur->getId()
            ]);
        }
        return $this->render('constructeur/create.html.twig', [
            'formulaire' => $formulaire->createView(),
            'creation' => $modeCreation
        ]);
    }

    /**
     *
     * @Route("/constructeur/delete/{id}", name="delete_constructeur")
     *
     */
    public function delete(Constructeur $constructeur, EntityManagerInterface $manager)
    {
        $manager->remove($constructeur);
        $manager->flush();

        return $this->redirectToRoute('constructeur');
    }
}
