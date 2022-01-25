<?php

namespace App\DataTransformer\User;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use ApiPlatform\Core\Validator\ValidatorInterface;
use App\DTO\User\UserPostInput;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserPostInputDataTransformer implements DataTransformerInterface
{
    private UserPasswordHasherInterface $passwordHasher;

    private ValidatorInterface $validator;

    public function __construct(UserPasswordHasherInterface $passwordHasher, ValidatorInterface $validator)
    {
        $this->passwordHasher = $passwordHasher;
        $this->validator = $validator;
    }

    /**
     * @param UserPostInput $userPostInput
     */
    public function transform($userPostInput, string $to, array $context = []): User
    {
        $this->validator->validate($userPostInput);

        $user = new User();
        $user->setEmail($userPostInput->email);
        $user->setPassword($this->passwordHasher->hashPassword($user, $userPostInput->password));
        $user->setRoles(['ROLE_USER']);

        return $user;
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        if ($data instanceof User) {
            return false;
        }

        if ('collection' !== $context['operation_type']) {
            return false;
        }

        return User::class === $to && null !== ($context['input']['class'] ?? null) && 'post' === $context['collection_operation_name'];
    }
}
