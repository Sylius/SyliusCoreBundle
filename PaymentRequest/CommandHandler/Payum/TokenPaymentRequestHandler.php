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

namespace Sylius\Bundle\CoreBundle\PaymentRequest\CommandHandler\Payum;

use Sylius\Bundle\CoreBundle\PaymentRequest\Command\PaymentRequestHashAwareInterface;
use Sylius\Bundle\CoreBundle\PaymentRequest\Provider\PaymentRequestProviderInterface;
use Sylius\Bundle\PayumBundle\Factory\TokenAggregateRequestFactoryInterface;
use Sylius\Bundle\PayumBundle\PaymentRequest\Factory\PayumTokenFactoryInterface;
use Sylius\Bundle\PayumBundle\PaymentRequest\Processor\AfterTokenRequestProcessorInterface;
use Sylius\Bundle\PayumBundle\PaymentRequest\Processor\RequestProcessorInterface;
use Sylius\Bundle\PayumBundle\PaymentRequest\Resolver\DoctrineProxyObjectResolverInterface;
use Sylius\Component\Payment\Exception\NonExistingPayumTokenException;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class TokenPaymentRequestHandler
{
    public function __construct(
        private PaymentRequestProviderInterface $paymentRequestProvider,
        private DoctrineProxyObjectResolverInterface $doctrineProxyObjectResolver,
        private PayumTokenFactoryInterface $payumTokenFactory,
        private RequestProcessorInterface $requestProcessor,
        private AfterTokenRequestProcessorInterface $afterTokenRequestProcessor,
        private TokenAggregateRequestFactoryInterface $payumRequestFactory,
    ) {
    }

    public function __invoke(PaymentRequestHashAwareInterface $command): void
    {
        $paymentRequest = $this->paymentRequestProvider->provide($command);
        $this->doctrineProxyObjectResolver->resolve($paymentRequest);

        $token = $this->payumTokenFactory->createNew($paymentRequest);
        $request = $this->payumRequestFactory->createNewWithToken($token);

        $token = $request->getToken();
        if (null === $token) {
            throw new NonExistingPayumTokenException();
        }

        $this->requestProcessor->process($paymentRequest, $request, $token->getGatewayName());
        $this->afterTokenRequestProcessor->process($paymentRequest, $token);
    }
}
