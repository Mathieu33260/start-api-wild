<?php

namespace App\DataTransformer\User;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\DTO\User\UserPutOutput;
use App\Entity\User;

class UserPutOutputDataTransformer implements DataTransformerInterface
{
    /**
     * @param User $user
     */
    public function transform($user, string $to, array $context = []): UserPutOutput
    {
        $userPutOutput = new UserPutOutput();
        $userPutOutput->email = $user->getEmail();
        $userPutOutput->id = $user->getId();
        $userPutOutput->displayName = $user->getDisplayName();

        return $userPutOutput;
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        if ('item' !== $context['operation_type']) {
            return false;
        }

        return $data instanceof User && $to === UserPutOutput::class && 'put' === $context['item_operation_name'];
    }
}
