<?php

namespace App\Controller;

use App\Entity\Telephone;
use App\Form\TelephoneType;
use App\Repository\TelephoneRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Security\Core\User\UserInterface;

class TelephoneController extends AbstractController
{
    /**
     * @Route("/telephone", name="telephone")
     */
    public function index(TelephoneRepository $repo): Response
    {
        $telephones = $repo->findAll();

        return $this->render('telephone/index.html.twig', [
            'controller_name' => 'Chez Yass',
            'telephones' => $telephones
        ]);
    }

    /**
     *
     * @Route("/telephone/{id}", name="show_telephone", requirements={"id"="\d+"})
     *
     */
    public function show(Telephone $telephone): Response
    {
        return $this->render('telephone/show.html.twig', [
            'controller_name' => 'Chez Yass',
            'telephone' => $telephone
        ]);
    }

    /**
     *
     * @Route("/telephone/create/", name="create_telephone")
     * @Route("/telephone/edit/{id}", name="edit_telephone", requirements={"id"="\d+"})
     */
    public function create(EntityManagerInterface $manager, Request $requete, Telephone $telephone = null, UserInterface $user)
    {
        $modeCreation = false;
        if (!$telephone) {
            $telephone = new Telephone();
            $modeCreation = true;
        }

        if($user != $telephone->getUser() && !$modeCreation){
            return $this->redirectToRoute('telephone');
        }

        $formulaire = $this->createForm(TelephoneType::class, $telephone);

        $formulaire->handleRequest($requete);

        if ($formulaire->isSubmitted() && $formulaire->isValid()) {
            $image = $formulaire->get('image')->getData();

            if ($image) {
                try {
                    $nomImage = uniqid() . '.' . $image->guessExtension();

                    $image->move(
                        $this->getParameter('images_telephones'),
                        $nomImage
                    );
                    if ($modeCreation || (!$modeCreation && $image)) {
                        $telephone->setImage($nomImage);
                        $telephone->setCreatedAt(new \DateTime());
                        $telephone->setUser($user);
                    }
                } catch (FileException $e) {
                    throw $e;
                    return $this->redirectToRoute('create_telephone');
                }
            }
            $manager->persist($telephone);
            $manager->flush();

            return $this->redirectToRoute('show_telephone', [
                'id' => $telephone->getId()
            ]);
        }

        return $this->render('telephone/create.html.twig', [
            'formulaire' => $formulaire->createView(),
            'creation' => $modeCreation
        ]);
    }

    /**
     *
     * @Route("/telephone/delete/{id}", name="delete_telephone")
     *
     */
    public function delete(Telephone $telephone, EntityManagerInterface $manager, UserInterface $user)
    {
        if($user != $telephone->getUser()){
            return $this->redirectToRoute('telephone');
        }
        $manager->remove($telephone);
        $manager->flush();

        return $this->redirectToRoute('telephone');
    }
}
