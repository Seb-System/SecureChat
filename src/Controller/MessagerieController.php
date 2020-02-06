<?php

namespace App\Controller;

use App\Entity\Groupe;
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

    	$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

    	$user = $this->getUser();
    	$username = $user->getUsername();

     //   $repo = $this->getDoctrine()->getRepository(GroupeRepository::class);
        $groupes = $this->getUser()->getGroupes();

        //Formulaire
        $groupe = new Groupe;
        $form = $this->createForm(GroupeFormType::class, $groupe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            #code
        }

        return $this->render('messagerie/index.html.twig', [
        	'username' => $username,
            'controller_name' => 'MessagerieController',
            'groupes' => $groupes,
            'form' => $form->createView(),
        ]);
    }
}
