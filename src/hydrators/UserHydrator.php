<?php


namespace BotConstructor\User;

use DenisPm\EasyFramework\core\MainHydrator;
use BotConstructor\User;


class UserHydrator extends MainHydrator
{
    const NAMESPACE_CLASSES = "BotConstructor\\User\\";

    const PARAMS_MAPPER = [
        'From' => 'User',
//        'User::id' => 'telegramId',
    ];

    private ?User $userObject = null;

    /**
     * @return User|null
     */
    public function getUserObject(): ?User
    {
        return $this->userObject;
    }

    /**
     * @param User|null $userObject
     */
    public function setUserObject(?User $userObject): void
    {
        $this->userObject = $userObject;
    }

}