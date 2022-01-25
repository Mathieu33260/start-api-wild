<?php

namespace App\DataTransformer\User;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\DTO\User\UserListOutput;
use App\Entity\User;

class UserListOutputDataTransformer implements DataTransformerInterface
{
    /**
     * @param User $user
     */
    public function transform($user, string $to, array $context = []): UserListOutput
    {
        $userListOutput = new UserListOutput();
        $userListOutput->email = $user->getEmail();
        $userListOutput->id = $user->getId();
        $userListOutput->displayName = $user->getDisplayName();

        return $userListOutput;
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        if ('collection' !== $context['operation_type']) {
            return false;
        }

        return $data instanceof User && $to === UserListOutput::class && 'get' === $context['collection_operation_name'];
    }
}
