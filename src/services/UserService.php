<?php


namespace BotConstructor\User;

use BotConstructor\User;

class UserService
{
    private ?User $user;

    /**
     * @throws \Exception
     */
    public function __construct(array $userData = []) {
        $this->user = new User();
        UserHydrator::hydrate($userData, $this->user);
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }


}