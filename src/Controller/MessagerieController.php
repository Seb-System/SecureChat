<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MessagerieController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {

    	$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

    	$user = $this->getUser();
    	$username = $user->getUsername();


        return $this->render('messagerie/index.html.twig', [
        	'username' => $username,
            'controller_name' => 'MessagerieController',
        ]);
    }
}
