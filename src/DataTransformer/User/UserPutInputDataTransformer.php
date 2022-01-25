<?php

namespace App\DataTransformer\User;

use ApiPlatform\Core\DataTransformer\DataTransformerInitializerInterface;
use ApiPlatform\Core\Serializer\AbstractItemNormalizer;
use ApiPlatform\Core\Validator\ValidatorInterface;
use App\DTO\User\UserPutInput;
use App\Entity\User;

class UserPutInputDataTransformer implements DataTransformerInitializerInterface
{
    private ValidatorInterface $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    public function initialize(string $inputClass, array $context = []): UserPutInput
    {
        /** @var User $existingUser */
        $existingUser = $context[AbstractItemNormalizer::OBJECT_TO_POPULATE] ?? null;
        if (!$existingUser) {
            return new UserPutInput();
        }

        $userPutInput = new UserPutInput();
        $userPutInput->firstName = $existingUser->getFirstName();
        $userPutInput->lastName = $existingUser->getLastName();

        return $userPutInput;
    }

    /**
     * @param UserPutInput $userPutInput
     */
    public function transform($userPutInput, string $to, array $context = []): User
    {
        $this->validator->validate($userPutInput);

        /** @var User $existingUser */
        $existingUser = $context[AbstractItemNormalizer::OBJECT_TO_POPULATE];
        $existingUser->setFirstName($userPutInput->firstName);
        $existingUser->setLastName($userPutInput->lastName);

        return $existingUser;
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        if ($data instanceof User) {
            return false;
        }

        if ('item' !== $context['operation_type']) {
            return false;
        }

        return User::class === $to && null !== ($context['input']['class'] ?? null) && 'put' === $context['item_operation_name'];
    }
}
