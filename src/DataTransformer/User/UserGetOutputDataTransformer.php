<?php

namespace App\DataTransformer\User;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\DTO\User\UserGetOutput;
use App\Entity\User;

class UserGetOutputDataTransformer implements DataTransformerInterface
{
    /**
     * @param User $user
     */
    public function transform($user, string $to, array $context = []): UserGetOutput
    {
        $userGetOutput = new UserGetOutput();
        $userGetOutput->id = $user->getId();
        $userGetOutput->email = $user->getEmail();
        $userGetOutput->firstName = $user->getFirstName();
        $userGetOutput->lastName = $user->getLastName();
        $userGetOutput->displayName = $user->getDisplayName();

        return $userGetOutput;
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        if ('item' !== $context['operation_type']) {
            return false;
        }

        return $data instanceof User && $to === UserGetOutput::class && 'get' === $context['item_operation_name'];
    }
}
