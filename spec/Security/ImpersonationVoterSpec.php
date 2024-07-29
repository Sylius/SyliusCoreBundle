<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Sylius Sp. z o.o.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace spec\Sylius\Bundle\CoreBundle\Security;

use PhpSpec\ObjectBehavior;
use Sylius\Bundle\CoreBundle\Security\ImpersonationVoter;
use Symfony\Bundle\SecurityBundle\Security\FirewallMap;
use Symfony\Component\HttpFoundation\Exception\SessionNotFoundException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\AuthenticatedVoter as VoterAuthenticatedVoter;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

final class ImpersonationVoterSpec extends ObjectBehavior
{
    private const FIREWALL_NAME = 'test';

    function let(RequestStack $requestStack, FirewallMap $firewallMap): void
    {
        $this->beConstructedWith($requestStack, $firewallMap);
    }

    function it_supports_sylius_authenticated_attribute(): void
    {
        $this->supportsAttribute(ImpersonationVoter::IS_IMPERSONATOR_SYLIUS)->shouldReturn(true);
    }

    function it_votes_granted_when_token_user_is_impersonated(
        TokenInterface $token,
        RequestStack $requestStack,
        SessionInterface $session,
    ): void {
        $requestStack->getSession()->willReturn($session);
        $session->get(sprintf('_security_impersonate_sylius_%s', self::FIREWALL_NAME), false)->willReturn(true);

        $this->vote($token, self::FIREWALL_NAME, [ImpersonationVoter::IS_IMPERSONATOR_SYLIUS])->shouldReturn(VoterInterface::ACCESS_GRANTED);
    }

    function it_votes_denied_when_token_user_is_not_impersonated(
        TokenInterface $token,
        RequestStack $requestStack,
        SessionInterface $session,
    ): void {
        $requestStack->getSession()->willReturn($session);
        $session->get(sprintf('_security_impersonate_sylius_%s', self::FIREWALL_NAME), false)->willReturn(false);

        $this->vote($token, self::FIREWALL_NAME, [ImpersonationVoter::IS_IMPERSONATOR_SYLIUS])->shouldReturn(VoterInterface::ACCESS_DENIED);
    }
    function it_votes_abstain_when_session_does_not_exists(
        TokenInterface $token,
        RequestStack $requestStack,
    ): void {
        $requestStack->getSession()->willThrow(SessionNotFoundException::class);

        $this->vote($token, self::FIREWALL_NAME, [ImpersonationVoter::IS_IMPERSONATOR_SYLIUS])->shouldReturn(VoterInterface::ACCESS_ABSTAIN);
    }

    function it_votes_abstain_when_firewall_is_not_set(
        TokenInterface $token,
        RequestStack $requestStack,
        Request $request,
        FirewallMap $firewallMap,
    ): void {
        $requestStack->getMainRequest()->willReturn($request);
        $firewallMap->getFirewallConfig($request)->willReturn(null);

        $this->vote($token, null, [ImpersonationVoter::IS_IMPERSONATOR_SYLIUS])->shouldReturn(VoterInterface::ACCESS_ABSTAIN);
    }
}
