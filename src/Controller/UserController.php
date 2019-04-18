<?php
/**
 * Created by PhpStorm.
 * User: julkwel
 * Date: 4/18/19
 * Time: 1:23 PM
 */

namespace App\Controller;


use App\Entity\User;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends Controller
{
    /**
     * @param $_data
     * @return Response
     */
    public function response($_data)
    {
        $_list = new Response($_data);
        $_list->headers->set('Content-Type', 'application/json');
        $_list->headers->set('Access-Control-Allow-Origin', '*');
        return $_list;
    }

    /**
     * Get crud service
     *
     * @return \App\Service\CrudService|object
     */
    public function getCrudService()
    {
        return $this->get('boo.crud.service');
    }

    /**
     * @return \JMS\Serializer\Serializer|object
     */
    public function getSerializer()
    {
        return $this->container->get('jms_serializer');
    }

    /**
     * @Route("api/user/list",name="user_list",methods={"GET","POST"})
     * @Route("api/user/list/{_boo_user}",name="user_list_id",methods={"GET","POST"})
     * @param User $_boo_user
     * @return Response
     */
    public function indexAction($_boo_user = null)
    {
        if ($_boo_user){
            $_boo_user_list = $this->getCrudService()->findById(User::class,$_boo_user);
        } else
            $_boo_user_list = $this->getCrudService()->findAll(User::class);

        $_boo_user_list = $this->getSerializer()->serialize($_boo_user_list, 'json');

        return $this->response($_boo_user_list);
    }

    /**
     * @Route("api/user/new",name="user_new",methods={"GET","POST"})
     * @Route("api/user/edit/{_boo_user}",name="user_edit",methods={"GET","POST"})
     * @param User $_boo_user
     * @param Request $_boo_request
     * @return Response
     * @throws \Exception
     */
    public function newAction(Request $_boo_request, $_boo_user = null)
    {

        $_boo_user_name = $_boo_request->request->get('_username');
        $_boo_user_pass = $_boo_request->request->get('_password');
        $_boo_user_mail = $_boo_request->request->get('_email');
        $_boo_nom = $_boo_request->request->get('_nom');
        $_boo_prenom = $_boo_request->request->get('_prenom');
        $_boo_adresse = $_boo_request->request->get('_adresse');
        $_boo_image = $_boo_request->files->get('_image');

        $_action = '';
        $_message = '';

        $fileName = $_boo_image->getClientOriginalName();

        try {
            $_boo_image->move(
                $this->getParameter('brochures_directory'),
                $fileName
            );
        } catch (FileException $e) {
            $_message = $e->getMessage();
        }

        if ($_boo_user) {
            $_boo_user = $this->getCrudService()->findById(User::class, $_boo_user);
            $_action = 'update';
            $_message = 'user update successful';
        } elseif ($_boo_user === null) {
            $_boo_user = new User();
            $_action = 'new';
            $_message = 'user create successful';
        }


        $_boo_pass = $_boo_user->setPlainPassword($_boo_user_pass ? $_boo_user_pass : $_boo_user->getPlainPassword());
        $_boo_user->setUsername($_boo_user_name ? $_boo_user_name : $_boo_user->getUsername());
        $_boo_user->setPassword($_boo_pass);
        $_boo_user->setEmail($_boo_user_mail ? $_boo_user_mail : $_boo_user->getEmail());
        $_boo_user->setPrenom($_boo_prenom);
        $_boo_user->setNom($_boo_nom);
        $_boo_user->setAdresse($_boo_adresse ? $_boo_adresse : $_boo_user->getAdresse());
        $_boo_user->setEnabled(true);
        $_boo_user->setImgUrl($fileName);

        $this->getCrudService()->saveEntity($_boo_user, $_action, $_boo_image);
        $_message = $this->getSerializer()->serialize($_message, 'json');

        return $this->response($_message);
    }

    /**
     * @param User $_boo_user
     * @Route("api/user/delete/{_boo_user}",name="user_dlete",methods={"GET","POST"})
     * @return Response
     */
    public function deleteAction($_boo_user)
    {
        $_boo_user = $this->getCrudService()->findById(User::class, $_boo_user);
        if (null === $_boo_user) {
            $_message = 'Aucun utilisateur correspond';
            $_message = $this->getSerializer()->serialize($_message, 'json');
            return $this->response($_message);
        }

        try {
            $this->getCrudService()->delete($_boo_user);
            $_message = 'suppression avec success';

            $_message = $this->getSerializer()->serialize($_message, 'json');
            return $this->response($_message);

        } catch (OptimisticLockException $e) {
        } catch (ORMException $e) {
            $_message = 'suppression avec success';
            $_message = $this->getSerializer()->serialize($_message, 'json');
            return $this->response($_message);
        }

    }
}