<?php

namespace App\Controller;

use App\Entity\Groupe;
use App\Entity\Message;
use App\Form\AddMemberToGroupType;
use App\Form\GroupeFormType;
use App\Form\MessageFormType;
use App\Form\UploadFileType;
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
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $user = $this->getUser();
        $userId = $user->getId();
        $groupes = $user->getGroupes();

        //Formulaire
        $groupe = new Groupe;
        $form = $this->createForm(GroupeFormType::class, $groupe, [
            'idUser' => $userId
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (count($groupe->getUsers()) < 1) {
                $this->addFlash('error', 'Merci de cocher au moins une personne !');
                return $this->redirectToRoute('index');
            } else {
                $manager->persist($groupe);
                $groupe->setPicture("default-groupe.jpg");
                $groupe->setDate(new \DateTime('now'));
                $groupe->setUsersP($user);
                $groupe->setName($form->get('name')->getData());
                $groupe->addUser($user);
                foreach ($groupe->getUsers() as $users) {
                    $users->addGroupe($groupe);
                }
                $manager->flush();
                $newGroupeId = $groupe->getId();
                $this->addFlash('success', 'Votre groupe a bien été crée !');
                return $this->redirectToRoute('conv', ['id' => $newGroupeId]);
            }
        }

        $formImg = $this->createForm(UploadFileType::class, $user);
        $formImg->handleRequest($request);

        if ($formImg->isSubmitted() && $formImg->isValid()) {
                $manager->persist($user);
                $user->uploadFile();
                $manager->flush();
        }


        return $this->render('messagerie/index.html.twig', [
            'user' => $user,
            'groupes' => $groupes,
            'form' => $form->createView(),
            'formImg' => $formImg->createView(),
        ]);
    }

    /**
     * @Route("/conv/{id}", name="conv")
     */
    public function conv(Request $request, $id)
    {
        
        $manager = $this->getDoctrine()->getManager();
        $repoGroupe = $this->getDoctrine()->getRepository(Groupe::class);
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $user = $this->getUser();
        $userId = $user->getId();
        $groupes = $user->getGroupes();

        if ($repoGroupe->find($id) == null) {
            $this->addFlash('error', 'Vous ne pouvez pas acceder à ce groupe !');
            return $this->redirectToRoute('index');
        } else {
            //Get messages
            $messages = $repoGroupe->find($id)->getMessages();

            //Formulaire créer un groupe
            $groupe = new Groupe;
            $form = $this->createForm(GroupeFormType::class, $groupe, [
                'idUser' => $userId
            ]);
            $form->handleRequest($request);
            $currentGroupe = $repoGroupe->find($id);

            if ($form->isSubmitted() && $form->isValid()) {
                $manager->persist($groupe);
                $groupe->setPicture("default-groupe.jpg");
                $groupe->setDate(new \DateTime('now'));
                $groupe->setUsersP($user);
                $groupe->setName($form->get('name')->getData());
                $groupe->addUser($user);
                foreach ($groupe->getUsers() as $users) {
                    $users->addGroupe($groupe);
                }
                $manager->flush();
                $newGroupeId = $groupe->getId();
                $this->addFlash('success', 'Votre groupe a bien été crée !');
                return $this->redirectToRoute('conv', ['id' => $newGroupeId]);
            }

            //Formulaire envoyer un message
            $message = new Message;
            $sendMessage = $this->createForm(MessageFormType::class, $message);
            $sendMessage->handleRequest($request);

            if ($sendMessage->isSubmitted() && $sendMessage->isValid()) {
                $manager->persist($message);
                $message->setContent($sendMessage->get('content')->getData());
                $message->setDate(new \DateTime('now'));
                $message->setState(1);
                $message->setUser($user);
                $message->setGroupe($currentGroupe);
                $currentGroupe->setDate(new \DateTime('now'));
                $manager->flush();
                return $this->redirectToRoute('conv', ['id' => $id]);
            }

            $formAddMember = $this->createForm(AddMemberToGroupType::class, $currentGroupe, ['idUser' => $userId]);
            $formAddMember->handleRequest($request);

            //Formulaire ajout de membres
            if ($formAddMember->isSubmitted() && $formAddMember->isValid()) {
                $manager->persist($currentGroupe);
                $currentGroupe->addUser($user);
                foreach ($currentGroupe->getUsers() as $users) {
                    $users->addGroupe($currentGroupe);
                }
                $manager->flush();
                $this->addFlash('success', 'Vous venez de modifier les membres du groupe');
                return $this->redirectToRoute('conv', ['id' => $id]);
            }

            //Formulaire changer image de profile
            $formImg = $this->createForm(UploadFileType::class, $user);
            $formImg->handleRequest($request);

            if ($formImg->isSubmitted() && $formImg->isValid()) {
                    $manager->persist($user);
                    $user->uploadFile();
                    $manager->flush();
            }

            // $this->addFlash('error', 'Vous n\'avez pas accès à cette conversation !');
            // return $this->redirectToRoute('index');

            return $this->render('messagerie/conv.html.twig', [
                'user' => $user,
                'groupes' => $groupes,
                'form' => $form->createView(),
                'formAddMember' => $formAddMember->createView(),
                'messages' => $messages,
                'currentUsersGroupe' => $currentGroupe->getUsers(),
                'currentGroupe' => $currentGroupe,
                'sendMessage' => $sendMessage->createView(),
                'formImg' => $formImg->createView(),
                'formAddMember' => $formAddMember->createView(),
                'id' => $id,
            ]);
        }
    }
}
