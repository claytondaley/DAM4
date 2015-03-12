<?php
/**
 * Created by PhpStorm.
 * User: Clayton Daley
 * Date: 1/18/2015
 * Time: 4:10 PM
 */

namespace DAM4\Role;

use ZfcRbac\Role\RoleProviderInterface;

class RoleProvider implements RoleProviderInterface {

    private $entityManager;

    public function setEntityManager($entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Get the roles from the provider
     *
     * @param  string[] $roleNames
     * @return \Rbac\Role\RoleInterface[]
     */
    public function getRoles(array $roleNames)
    {
        // TODO: Filter using User permissions
        if (count($roleNames) > 0) {
            $roles = $this->entityManager->getRepository('LegacyRS\Entity\Usergroup')->findby(array('name' => $roleNames));
        } else {
            $roles = $this->entityManager->getRepository('LegacyRS\Entity\Usergroup')->findAll();
        }
        return $roles;
    }
}