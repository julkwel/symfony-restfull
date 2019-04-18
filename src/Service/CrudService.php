<?php
/**
 * Created by PhpStorm.
 * User: julkwel
 * Date: 4/18/19
 * Time: 1:25 PM
 */

namespace App\Service;


use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\JsonResponse;

class CrudService
{
    private $_boo__entity_manager;
    private $_boo_container;
    private $_boo_web_root;

    /**
     * CrudService constructor.
     *
     * @param EntityManager $entityManager
     * @param Container $container
     * @param $_boo_root_dir
     */
    public function __construct(EntityManager $entityManager, Container $container, $_boo_root_dir)
    {
        $this->_boo__entity_manager = $entityManager;
        $this->_boo_container = $container;
        $this->_boo_web_root = realpath($_boo_root_dir . '/../public');
    }

    /**
     * @param $_boo_entity
     * @return array|object[]
     */
    public function findAll($_boo_entity)
    {
        return $this->_boo__entity_manager->getRepository($_boo_entity)->findAll();
    }

    /**
     * @param $_boo_entity
     * @return null|object
     */
    public function findById($_boo_entity)
    {
        return $this->_boo__entity_manager->getRepository($_boo_entity)->find($_boo_entity->getId());
    }

    /**
     * Ajout crud service check si update ou noveau
     *
     * @param $_boo_entity
     * @param $_boo_action
     *
     * @param $_boo_image
     * @return mixed $_boo_entity
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function saveEntity($_boo_entity, $_boo_action, $_boo_image)
    {
        if ('new' === $_boo_action) {
            if (!null === $_boo_image) {
                $this->upload($_boo_entity, $_boo_image);
            }
            $this->_boo__entity_manager->persist($_boo_entity);
        } elseif ('update' === $_boo_action) {
            if (!null === $_boo_image) {
                $this->deleteImageById($_boo_entity);
                $this->upload($_boo_entity, $_boo_image);
            }
        }
        $this->_boo__entity_manager->flush();

        return $_boo_entity;
    }

    /**
     * @param $_boo_entity
     * @return bool
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete($_boo_entity)
    {
        if (method_exists($_boo_entity, 'getImgUrl'))
            $this->deleteImageById($_boo_entity);
        $this->_boo__entity_manager->remove($_boo_entity);
        $this->_boo__entity_manager->flush();

        return true;
    }

    // Pour les entités ayant des images

    /**
     * @param $_boo_entity
     * @param $_boo_image
     */
    public function upload($_boo_entity, $_boo_image)
    {
        $_filename_image = md5(uniqid()) . '.' . $_boo_image->guessExtension();
        $_uri_file = '/upload/image/' . $_filename_image;
        $_dir = $this->_boo_web_root . '/upload/image/';
        $_boo_image->move(
            $_dir,
            $_filename_image
        );
        $_boo_entity->setImgUrl($_uri_file);
    }

    /**
     * @param $_boo_entity
     * @return JsonResponse
     */
    public function deleteImageById($_boo_entity)
    {
        $_boo_image = $this->findById($_boo_entity);
        if ($_boo_image) {
            try {
                $_path = $this->_boo_web_root . $_boo_image->getImgUrl();

                @unlink($_path);
                // Suppression dans la base
                $_boo_image->setImgUrl(null);
                $this->_boo__entity_manager->persist($_boo_image);
                $this->_boo__entity_manager->flush();
                return new JsonResponse('suppression avec success', 200);
            } catch (\Exception $_exc) {
                return new JsonResponse('Une erreur se produite', 200);
            }
        }
    }

    /**
     * @param $_boo_entity
     */
    public function deleteImage($_boo_entity)
    {
        $_boo_image = $this->findById($_boo_entity);
        if ($_boo_image) {
            $_path = $this->_boo_web_root . $_boo_entity->getImgUrl();
            @unlink($_path);
        }
    }
}