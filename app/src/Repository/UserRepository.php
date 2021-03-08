<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

use function get_class;
use function sprintf;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method |array<User>    findAll()
 * @method |array<User>    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    private EncoderFactoryInterface $encoderFactory;

    public function __construct(ManagerRegistry $registry, EncoderFactoryInterface $encoderFactory)
    {
        $this->encoderFactory = $encoderFactory;
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
        }

        $user->setPassword($newEncodedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    public function create(User $user): void
    {
        $this->encodePassword($user);

        $this->_em->persist($user);
        $this->_em->flush();
    }

    public function update(User $user): void
    {
        if ($user->getPlainPassword() !== null) {
            $this->encodePassword($user);
        }

        $this->_em->persist($user);
        $this->_em->flush();
    }

    public function getRandomProfile(): ?User
    {
        return $this->createQueryBuilder("u")
            ->orderBy('RAND()')
            ->setMaxResults(1)
            ->getQuery()
            ->getSingleResult();
    }

    private function encodePassword(User $user): void
    {
        $user->setPassword(
            $this->encoderFactory->getEncoder($user)->encodePassword($user->getPlainPassword(), null),
        );
    }

}
