<?php

namespace App\Manager;

use App\Entity\User;
use App\Exceptions\InvalidPasswordException;
use App\Exceptions\UserAlreadyExistException;
use App\Exceptions\UserNotFoundException;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use function PHPUnit\Framework\throwException;

class AuthManager
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    /**
     * @var JWTTokenManagerInterface
     */
    private $jwtManger;

    public function __construct(UserRepository $userRepository,
                                UserPasswordHasherInterface $encoder,
                                EntityManagerInterface $entityManager,
                                JWTTokenManagerInterface $jwtManger)
    {
        $this->userRepository = $userRepository;
        $this->encoder = $encoder;
        $this->entityManager = $entityManager;
        $this->jwtManger = $jwtManger;
    }

    /**
     * @param Request $request
     * @return array
     * @throws \Exception
     */
    public function login(Request $request)
    {
        $user = $this->find($request);

        if (empty($user)) {
            throw new UserNotFoundException($user->getUsername());
        }

        if (!$this->encoder->isPasswordValid($user, $request->get('password'))) {
            throw new InvalidPasswordException();
        }

        $token = $this->jwtManger->create($user);

        return array(
            'id' => $user->getId(),
            'username' => $user->getUsername(),
            'email' => $user->getEmail(),
            'roles' => $user->getRoles(),
            'token' => $token
        );
    }

    /**
     * @param Request $request
     *
     * @throws \Exception
     */
    public function register(Request $request)
    {
        $user = $this->find($request);

        if (!empty($user)) {
            throw new UserAlreadyExistException($user->getUsername());
        }

        try {
            $user = new User();
            $user->setUsername($request->get('username'))
                ->setEmail($request->get('email'))
                ->setRoles($request->get('roles'))
                ->setPassword($this->encoder->hashPassword(
                    $user, $request->get('password'))
                );

            $this->entityManager->persist($user);
            $this->entityManager->flush();

        } catch (\Exception $exception) {
            throwException($exception);
        }

        return array(
            'id' => $user->getId(),
            'username' => $user->getUsername(),
            'email' => $user->getEmail(),
            'roles' => $user->getRoles(),
        );
    }

    public function find(Request $request)
    {
        return $this->userRepository->findOneBy([
            'username' => $request->get('username')
        ]);
    }
}