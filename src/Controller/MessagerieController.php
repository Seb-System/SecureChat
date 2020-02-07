<?php

namespace App\Controller;

use App\Entity\Groupe;
use App\Entity\User;
use App\Form\GroupeFormType;
use App\Repository\GroupeRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MessagerieController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();
        $repo = $this->getDoctrine()->getRepository(User::class);
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $user = $this->getUser();
        $username = $user->getUsername();

        //$repo = $this->getDoctrine()->getRepository(GroupeRepository::class);
        $groupes = $this->getUser()->getGroupes();

        //Get all users
        $allUsers = $repo->findAll();
        $userNb = count($allUsers);

        //Formulaire
        $groupe = new Groupe;
        $form = $this->createForm(GroupeFormType::class, $groupe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($groupe);
            $groupe->setPicture("../public/dist/img/avatars/avatar-female-1.jpg");
            $groupe->setDate(new \DateTime('now'));
            $groupe->setUsersP($this->getUser());
            $groupe->setName($form->get('name')->getData());
            $groupe->addUser($this->getUser());
            foreach($groupe->getUsers() as $users) {
                $users -> addGroupe($groupe);
            }

            $manager->flush();
        }

        return $this->render('messagerie/index.html.twig', [
            'username' => $username,
            'controller_name' => 'MessagerieController',
            'groupes' => $groupes,
            'form' => $form->createView(),
            'allUsers' => $allUsers,
            'userNb' => $userNb
        ]);
    }
}
