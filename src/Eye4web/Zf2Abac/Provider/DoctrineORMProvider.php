<?php

namespace Eye4web\Zf2Abac\Provider;

use Doctrine\ORM\EntityManagerInterface;
use Eye4web\Zf2Abac\Collections\PermissionCollection;
use Eye4web\Zf2Abac\Entity\PermissionInterface;
use Zend\Validator\ValidatorInterface;
use Zend\Validator\ValidatorPluginManager;
use Eye4web\Zf2Abac\Exception;

class DoctrineORMProvider implements ProviderInterface
{
    /** @var EntityManagerInterface */
    protected $objectManager;

    /** @var ValidatorPluginManager */
    protected $validatorPluginManager;

    public function __construct(EntityManagerInterface $objectManager, ValidatorPluginManager $validatorPluginManager)
    {
        $this->objectManager = $objectManager;
        $this->validatorPluginManager = $validatorPluginManager;
    }

    /**
     * Get permissions from name and value
     *
     * @param string $name
     * @param string $value
     * @return PermissionCollection
     */
    public function getPermissions($name, $value)
    {
        $collection = new PermissionCollection();

        $permissions = $this->objectManager->createQuery('select p from \Eye4web\Zf2Abac\Entity\Permission p where p.name = :name and p.value = :value');
        $permissions->setParameters([
            'name' => $name,
            'value' => $value,
        ]);

        /** @var PermissionInterface $permission */
        foreach ($permissions->getResult() as $permission) {
            $collection->add($permission);
        }

        return $collection;
    }

    /**
     * Get validator from entity
     *
     * @param PermissionInterface $permission
     * @return null|ValidatorInterface
     * @throws \Eye4web\Zf2Abac\Exception\ValidatorNotFound
     * @throws \Eye4web\Zf2Abac\Exception\RuntimeException
     */
    public function getValidator(PermissionInterface $permission)
    {
        /** @var ValidatorInterface|null $validator */
        $validator = $this->validatorPluginManager->get($permission->getValidator());

        if (!$validator) {
            throw new Exception\ValidatorNotFound(sprintf(
                'The validator \"%s\" could not be found',
                is_object($validator) ? get_class($validator) : gettype($validator)
            ));
        }

        if ($permission->getValidatorOptions()) {
            $json = $permission->getValidatorOptions();
            $options = json_decode($json, true);

            if (!$options) {
                throw new Exception\RuntimeException(sprintf(
                    'The options for validator \"%s\" must be in json format',
                    is_object($validator) ? get_class($validator) : gettype($validator)
                ));
            }

            $validator->setOptions($options);
        }

        return $validator;
    }
}
