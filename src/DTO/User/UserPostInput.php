<?php

namespace App\DTO\User;

use App\Validator\User\UniqueEmail;
use Symfony\Component\Validator\Constraints as Assert;

class UserPostInput
{
    /**
     * @UniqueEmail()
     * @Assert\Email()
     * @Assert\NotBlank()
     */
    public string $email;

    /**
     * @Assert\NotBlank()
     */
    public string $password;
}
