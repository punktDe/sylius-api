<?php
declare(strict_types=1);

namespace PunktDe\Sylius\Api\Aop;

/*
 *  (c) 2018 punkt.de GmbH - Karlsruhe, Germany - http://punkt.de
 *  All rights reserved.
 */

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Aop\JoinPointInterface;
use Psr\Log\LoggerInterface;

/**
 * @Flow\Aspect
 */
class ClientLoggingAspect
{
    /**
     * @Flow\Inject
     * @var LoggerInterface
     */
    protected $systemLogger;

    /**
     * @param JoinPointInterface $joinPoint
     * @Flow\Around("method(PunktDe\Sylius\Api\Client->.*Async())")
     * @return mixed
     */
    public function logRequest(JoinPointInterface $joinPoint)
    {
        $timeStart = microtime(true);

        $result = $joinPoint->getAdviceChain()->proceed($joinPoint);

        $time = (int)((microtime(true) - $timeStart) * 1000);

        $this->systemLogger->debug(sprintf('%s: %s | Time: %s ms', $joinPoint->getMethodName(), $joinPoint->getMethodArgument('url'), $time), [
            'FLOW_LOG_ENVIRONMENT' => [
                'packageKey' => 'PunktDe.Sylius.Api',
                'className' => __CLASS__,
                'methodName' => __FUNCTION__
            ]
        ]);

        return $result;
    }
}
