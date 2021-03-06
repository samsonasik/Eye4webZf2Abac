<?php

namespace Eye4web\Zf2Abac\View\Helper;

use Eye4web\Zf2Abac\Service\AuthorizationServiceInterface;
use Zend\View\Helper\AbstractHelper;

class HasPermissionHelper extends AbstractHelper
{
    /** @var AuthorizationServiceInterface */
    private $authorizationService;

    /**
     * @param AuthorizationServiceInterface $authorizationService
     */
    public function __construct(AuthorizationServiceInterface $authorizationService)
    {
        $this->authorizationService = $authorizationService;
    }

    /**
     * @param string $name
     * @param string $value
     * @param array $attributes
     * @return boolean
     */
    public function __invoke($name, $value, array $attributes)
    {
        return $this->authorizationService->hasPermission($name, $value, $attributes);
    }
}
