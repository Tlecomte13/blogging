<?php


namespace App\Service\Image;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Security\Core\Security;

class UploadedBase64FileService
{
    private $params, $manager, $username;

    /**
     * UploadedBase64FileService constructor.
     * @param ParameterBagInterface $params
     * @param EntityManagerInterface $manager
     * @param Security $security
     */
    public function __construct(ParameterBagInterface $params, EntityManagerInterface $manager, Security $security)
    {
        $this->params   = $params;
        $this->manager  = $manager;
        $this->username = $security->getUser()->getUsername();
    }

    /**
     * @param string $content
     * @return string
     */
    public function extractContentImageBase64ToPNG(string $content)
    {
        $e = explode('src="', $content);
        $rest = substr($e[1], 0, 4098);

        dump($e);
        dump($rest);



//        $image_parts = explode(';base64,', $content);
//        $image_en_base64 = base64_decode($image_parts[1]);
//        $file_url = $this->params->get('comment_directory'). $this->username.'/' . uniqid() . '.png';
//
//
//        if (!is_dir($this->params->get('comment_directory'). $this->username)) {
//
//            mkdir($this->params->get('comment_directory'). $this->username);
//
//        }
//
//        file_put_contents($file_url, $image_en_base64);

//        return $file;
    }
}