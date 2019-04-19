<?php
/**
 * @Author Julien Rajerison <julienrajerison5@gmail.com>
 *
 * @Description Symfony rest api
 *
 * @Content CrudService
 */

namespace App\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\Container;

class CrudService
{
    private $_boo__entity_manager;
    private $_boo_container;
    private $_boo_web_root;

    /**
     * CrudService constructor.
     *
     * @param EntityManager $entityManager
     * @param Container     $container
     * @param $_boo_root_dir
     */
    public function __construct(EntityManager $entityManager, Container $container, $_boo_root_dir)
    {
        $this->_boo__entity_manager = $entityManager;
        $this->_boo_container = $container;
        $this->_boo_web_root = $_boo_root_dir;
    }

    /**
     * @param $_boo_entity
     *
     * @return array|object[]
     */
    public function findAll($_boo_entity)
    {
        return $this->_boo__entity_manager->getRepository($_boo_entity)->findAll();
    }

    /**
     * @param $_boo_entity
     * @param $_boo_id
     *
     * @return object|null
     */
    public function findById($_boo_entity, $_boo_id)
    {
        return $this->_boo__entity_manager->getRepository($_boo_entity)->find($_boo_id);
    }

    /**
     * Ajout crud service check si update ou noveau.
     *
     * @param $_boo_entity
     * @param $_boo_action
     * @param $_boo_image
     *
     * @return mixed $_boo_entity
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function saveEntity($_boo_entity, $_boo_action, $_boo_image)
    {
        if ('new' === $_boo_action) {
            $this->_boo__entity_manager->persist($_boo_entity);
        }
        $this->_boo__entity_manager->flush();

        return $_boo_entity;
    }

    /**
     * @param $_boo_entity
     *
     * @return bool
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete($_boo_entity)
    {
        $this->_boo__entity_manager->remove($_boo_entity);
        $this->_boo__entity_manager->flush();

        return true;
    }
}
