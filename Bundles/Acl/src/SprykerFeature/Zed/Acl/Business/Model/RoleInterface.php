<?php

namespace SprykerFeature\Zed\Acl\Business\Model;

use SprykerFeature\Shared\Acl\Transfer\Role as transferRole;
use SprykerFeature\Shared\Acl\Transfer\RoleCollection;
use SprykerFeature\Zed\Acl\Business\Exception\GroupNotFoundException;
use SprykerFeature\Zed\Acl\Business\Exception\RoleNameExistsException;
use SprykerFeature\Zed\Acl\Business\Exception\RoleNotFoundException;

interface RoleInterface
{
    /**
     * @param string $name
     * @param int $idGroup
     *
     * @return transferRole
     * @throws RoleNameExistsException
     */
    public function addRole($name, $idGroup);

    /**
     * @param transferRole $data
     *
     * @return transferRole
     * @throws RoleNameExistsException
     * @throws RoleNotFoundException
    d     */
    public function save(transferRole $data);

    /**
     * @param int $idRole
     *
     * @return bool
     */
    public function hasRoleId($idRole);

    /**
     * @param string $name
     *
     * @return bool
     */
    public function hasRoleName($name);

    /**
     * @param int $idUser
     *
     * @return RoleCollection
     */
    public function getUserRoles($idUser);

    /**
     * @param int $idGroup
     *
     * @return RoleCollection
     * @throws GroupNotFoundException
     */
    public function getGroupRoles($idGroup);

    /**
     * @param int $id
     *
     * @return transferRole
     */
    public function getRoleById($id);

    /**
     * @param int $id
     *
     * @return bool
     * @throws RoleNotFoundException
     */
    public function removeRoleById($id);

    /**
     * @param string $name
     *
     * @return transferRole
     */
    public function getByName($name);
}
