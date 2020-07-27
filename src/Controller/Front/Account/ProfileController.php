<?php

namespace App\Controller\Front\Account;

use App\Form\Account\MainEditType;
use App\Repository\Account\UserRepository;
use Cocur\Slugify\Slugify;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    /**
     * @Route("/account", name="account")
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function account(Request $request, EntityManagerInterface $manager)
    {

        $mainEdit = $this->createForm(MainEditType::class, $this->getUser());

        $mainEdit->handleRequest($request);

        if ($mainEdit->isSubmitted() && $mainEdit->isValid()){

            /** @var UploadedFile $avatar */
            $avatar = $mainEdit->get('avatar')->getData();

            if ($avatar) {
                $originalFilename = pathinfo($avatar->getClientOriginalName(), PATHINFO_FILENAME);

                $slugger = new Slugify();
                $safeFilename = $slugger->slugify($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$avatar->guessExtension();


                try {
                    $avatar->move($this->getParameter('avatar_directory'), $newFilename);
                } catch (FileException $e) {

                }

                $this->getUser()->setAvatar($newFilename);
            }

            $manager->persist($this->getUser());
            $manager->flush();

            $this->addFlash(
                'success',
                "<strong>Succès:</strong> Votre compte à bien été modifié"
            );

        }



        return $this->render('Front/Account/Profile/account.html.twig', [
            'user' => $this->getUser(),
            'mainEdit' => $mainEdit->createView()
        ]);
    }
}
