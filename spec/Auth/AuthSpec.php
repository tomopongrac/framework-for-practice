<?php

namespace spec\App\Auth;

use App\Auth\Auth;
use App\Auth\Hashing\HasherInterface;
use App\Auth\Recaller;
use App\Cookie\CookieJar;
use App\Models\User;
use App\Session\Session;
use App\Session\SessionStoreInterface;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class AuthSpec extends ObjectBehavior
{
    function let(EntityManager $entityManager, HasherInterface $hasher, SessionStoreInterface $session, Recaller $recaller, CookieJar $cookie)
    {
        $this->beConstructedWith($entityManager, $hasher, $session, $recaller, $cookie);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Auth::class);
    }

    function it_should_return_false_if_email_dont_exist_in_database(
        EntityManager $entityManager,
        EntityRepository $entityRepository
    ) {
        $entityManager->getRepository(User::class)->shouldBeCalled()->willReturn($entityRepository);
        $entityRepository->findOneBy(['email' => 'don-exists@example.com'])->shouldBeCalled()->willReturn(null);

        $this->attempt('don-exists@example.com', 'password')->shouldReturn(false);
    }

    function it_should_return_true_if_credentials_is_corrects(
        EntityManager $entityManager,
        EntityRepository $entityRepository,
        HasherInterface $hasher,
        User $user,
        SessionStoreInterface $session
    ) {
        $entityManager->getRepository(User::class)->shouldBeCalled()->willReturn($entityRepository);
        $entityRepository->findOneBy(['email' => 'john@example.com'])->shouldBeCalled()->willReturn($user);
        $hasher->check('password', $user->password)->shouldBeCalled()->willReturn(true);

        $session->set('id', $user->id)->shouldBeCalled()->willReturn(null);
        $this->attempt('john@example.com', 'password')->shouldReturn(true);
    }

    function it_should_return_false_if_password_is_incorrect(
        EntityManager $entityManager,
        EntityRepository $entityRepository,
        HasherInterface $hasher,
        User $user
    ) {
        $entityManager->getRepository(User::class)->shouldBeCalled()->willReturn($entityRepository);
        $entityRepository->findOneBy(['email' => 'john@example.com'])->shouldBeCalled()->willReturn($user);
        $hasher->check('password', $user->password)->shouldBeCalled()->willReturn(false);

        $this->attempt('john@example.com', 'password')->shouldReturn(false);
    }

    function it_can_check_if_user_id_is_in_session(SessionStoreInterface $session)
    {
        $session->exists('id')->willReturn(true);

        $this->hasUserInSession()->shouldReturn(true);
    }

    function it_can_check_if_is_signin(SessionStoreInterface $session)
    {
        $session->exists('id')->willReturn(true);

        $this->check()->shouldReturn(true);
    }

    function it_can_get_user_from_session_id(
        EntityManager $entityManager,
        EntityRepository $entityRepository,
        User $user,
        SessionStoreInterface $session
    ) {
        $session->get('id')->willReturn($userId = 1);
        $entityManager->getRepository(User::class)->shouldBeCalled()->willReturn($entityRepository);
        $entityRepository->find($userId)->shouldBeCalled()->willReturn($user);

        $this->setUserFromSession();
        $this->user()->shouldReturn($user);
    }

    function it_will_throw_exception_if_user_dont_exists(
        EntityManager $entityManager,
        EntityRepository $entityRepository,
        SessionStoreInterface $session
    ) {
        $session->get('id')->willReturn($userId = 1);
        $entityManager->getRepository(User::class)->shouldBeCalled()->willReturn($entityRepository);
        $entityRepository->find($userId)->shouldBeCalled()->willReturn(null);

        $this->shouldThrow(new \Exception())->during('setUserFromSession');
    }
}
