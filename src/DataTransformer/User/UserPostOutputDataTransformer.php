<?php

namespace App\DataTransformer\User;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\DTO\User\UserPostOutput;
use App\Entity\User;

class UserPostOutputDataTransformer implements DataTransformerInterface
{
    /**
     * @param User $user
     */
    public function transform($user, string $to, array $context = []): UserPostOutput
    {
        $userPostOutput = new UserPostOutput();
        $userPostOutput->email = $user->getEmail();
        $userPostOutput->id = $user->getId();

        return $userPostOutput;
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        if ('collection' !== $context['operation_type']) {
            return false;
        }

        return $data instanceof User && $to === UserPostOutput::class && 'post' === $context['collection_operation_name'];
    }
}
