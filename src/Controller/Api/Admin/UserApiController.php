<?php

namespace App\Controller\Api\Admin;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Doctrine\ORM\EntityManagerInterface;

#[Route('/api/admin/user', name: 'api_admin_user_')]
class UserApiController extends AbstractController
{
    #[Route('/list', name: 'list', methods: ['GET'])]
    public function list(UserRepository $repository): JsonResponse
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $users = $repository->findAll();

        $data = array_map(static function (User $user) {
            return [
                'id' => $user->getId(),
                'email' => $user->getEmail(),
                'roles' => $user->getRoles(),
            ];
        }, $users);

        return $this->json(['items' => $data]);
    }

    #[Route('', name: 'create', methods: ['POST'])]
    public function create(
        Request $request,
        ValidatorInterface $validator,
        UserPasswordHasherInterface $hasher,
        EntityManagerInterface $em,
        UserRepository $repository
    ): JsonResponse {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $payload = json_decode($request->getContent() ?: '{}', true) ?: [];

        $constraints = new Assert\Collection([
            'email' => [
                new Assert\NotBlank(),
                new Assert\Email(),
                new Assert\Length(['max' => 180]),
            ],
            'password' => [
                new Assert\NotBlank(),
                new Assert\Length(['min' => 6]),
            ],
            'roles' => new Assert\Optional([
                new Assert\Type('array'),
            ]),
        ]);

        $errors = $validator->validate($payload, $constraints);
        if (count($errors) > 0) {
            $formatted = [];
            foreach ($errors as $error) {
                $formatted[$error->getPropertyPath()] = $error->getMessage();
            }
            return $this->json(['ok' => false, 'errors' => $formatted], 422);
        }

        $email = strtolower($payload['email']);
        if ($repository->findOneBy(['email' => $email])) {
            return $this->json(['ok' => false, 'errors' => ['email' => 'E-Mail existiert bereits.']], 409);
        }

        $user = new User();
        $user->setEmail($email);
        $user->setRoles($payload['roles'] ?? ['ROLE_ADMIN']);
        $hashed = $hasher->hashPassword($user, $payload['password']);
        $user->setPassword($hashed);

        $em->persist($user);
        $em->flush();

        return $this->json([
            'ok' => true,
            'user' => [
                'id' => $user->getId(),
                'email' => $user->getEmail(),
                'roles' => $user->getRoles(),
            ],
        ], 201);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(User $user, EntityManagerInterface $em): JsonResponse
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        if ($this->getUser() && $this->getUser()->getUserIdentifier() === $user->getUserIdentifier()) {
            return $this->json(['ok' => false, 'message' => 'Eigenes Konto kann nicht gelöscht werden.'], 400);
        }

        $em->remove($user);
        $em->flush();

        return $this->json(['ok' => true]);
    }
}
