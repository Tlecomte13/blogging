<?php


namespace App\Service;


use App\Entity\Account\User;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Hmac\Sha256;

class MercureCookieGeneratorService
{
    /**
     * @var string
     */
    private $secret;

    public function __construct(string $secret)
    {

        $this->secret = $secret;
    }

    /**
     * @param User $user
     * @return array
     */
    public function generate(User $user)
    {

        $id = $user->getId();
        $token = (new Builder())
            ->set('mercure', ['subscribe' => ["http://blogging.local/ping/$id"]])
            ->sign(new Sha256(), $this->secret)
            ->getToken();

        return [
            'twig' => sprintf("%s", $token),
            'all' => $token
        ];
    }
}