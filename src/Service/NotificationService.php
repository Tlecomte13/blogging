<?php


namespace App\Service;


use App\Entity\Account\Notification;
use App\Repository\Account\NotificationRepository;
use App\Repository\Account\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class NotificationService
{
    private $userRepository, $notificationRepository, $manager, $user;

    /**
     * NotificationService constructor.
     * @param UserRepository $userRepository
     * @param EntityManagerInterface $manager
     * @param NotificationRepository $notificationRepository
     * @param Security $security
     */
    public function __construct(UserRepository $userRepository, EntityManagerInterface $manager,
                                NotificationRepository $notificationRepository, Security $security)
    {
        $this->userRepository           = $userRepository;
        $this->manager                  = $manager;
        $this->notificationRepository   = $notificationRepository;
        $this->user                     = $security->getUser();
    }

    /**
     * @param $user
     * @param $icon
     * @param $message
     * @param $status
     * @param $color
     */
    public function newNotification($user, $icon, $message, $status, $color)
    {
        $arrUsersId = $this->userRepository->followIdList($user);

        /**
         * S'il n'a pas de followers pas besoin d'envoyer une notification
         */
        if (!empty($arrUsersId)){

            $notification = new Notification();
            $notification->setIcon($icon)
                ->setMessage($message)
                ->setStatus($status)
                ->setColor($color)
                ->setUsers($arrUsersId)
            ;

            $this->manager->persist($notification);
            $this->manager->flush();

        }
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