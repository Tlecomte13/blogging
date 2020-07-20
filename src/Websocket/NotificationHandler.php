<?php
namespace App\Websocket;

use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;
use Symfony\Component\Security\Core\Security;


class NotificationHandler implements MessageComponentInterface
{
    private $users = [];
    private $currentUser;

    public function __construct(Security $security)
    {
        $this->users = new \SplObjectStorage();
        $this->currentUser = $security->getUser();
    }

    public function onOpen(ConnectionInterface $conn)
    {
        $this->users[$conn->resourceId] = [
            'connection' => $conn,
            'id' => $this->currentUser,
            'channels' => []
        ];
    }

    public function onClose(ConnectionInterface $closedConnection)
    {
        unset($this->users[$closedConnection->resourceId]);
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        $conn->send('An error has occurred: '.$e->getMessage());
        $conn->close();
    }

    public function onMessage(ConnectionInterface $from, $message)
    {
        foreach($this->users as $connection)
        {
            if($connection === $from)
            {
                continue;
            }
            $connection->send($message);
        }
    }



}