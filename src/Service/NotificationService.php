<?php


namespace App\Service;


use App\Entity\Account\Notification;
use App\Repository\Account\NotificationRepository;
use App\Repository\Account\UserRepository;
use App\Websocket\NotificationHandler;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class NotificationService
{
    private $userRepository, $notificationRepository, $manager, $user, $websocket;

    /**
     * NotificationService constructor.
     * @param UserRepository $userRepository
     * @param EntityManagerInterface $manager
     * @param NotificationRepository $notificationRepository
     * @param Security $security
     * @param NotificationHandler $notificationHandler
     */
    public function __construct(UserRepository $userRepository, EntityManagerInterface $manager,
                                NotificationRepository $notificationRepository,
                                Security $security, NotificationHandler $notificationHandler)
    {
        $this->userRepository           = $userRepository;
        $this->manager                  = $manager;
        $this->notificationRepository   = $notificationRepository;
        $this->user                     = $security->getUser();
        $this->websocket                = $notificationHandler;
    }

    /**
     * @param $arr
     * @param $icon
     * @param $message
     * @param $status
     * @param $color
     */
    public function newNotification($arr, $icon, $message, $status, $color)
    {
        /**
         * S'il n'a pas de followers pas besoin d'envoyer une notification
         */
        if (!empty($arrUsersId)){

            $notification = new Notification();
            $notification->setIcon($icon)
                ->setMessage($message)
                ->setStatus($status)
                ->setColor($color)
                ->setUsers($arr)
            ;

            $this->manager->persist($notification);
            $this->manager->flush();

        }

        $this->websocket->onMessage();
    }

    public function getNotification()
    {
        $notifications = $this->notificationRepository->findAll();

        $i = 0;

        foreach ($notifications as $notification){
            $arr = $notification->getUsers();

            if (in_array($this->user->getId(), $arr)){
                $i++;
                $listNotifications[] = $notification;
            }
        }

        if (!empty($listNotifications)){
            return [
                'Notification' => $listNotifications,
                'Length'       => $i
            ];
        }

        return [
            'Notification' => null,
            'Length'       => 0
        ];
    }
}