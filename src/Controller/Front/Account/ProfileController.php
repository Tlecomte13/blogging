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

            // this condition is needed because the 'brochure' field is not required
            // so the PDF file must be processed only when a file is uploaded
            if ($avatar) {
                $originalFilename = pathinfo($avatar->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $slugger = new Slugify();
                $safeFilename = $slugger->slugify($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$avatar->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $avatar->move(
                        $this->getParameter('avatar_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $this->getUser()->setAvatar($newFilename);
            }

            // ... persist the $product variable or any other work


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
