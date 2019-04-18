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

    public function getCrudService()
    {
        return $this->get('boo.crud.service');
    }

    /**
     * @Route("api/user/list",methods={"GET"})
     *
     */
    public function indexAction()
    {
        $_boo_user_list = $this->getCrudService()->findAll(User::class);
        $_boo_serializer = $this->container->get('jms_serializer');

        $_boo_user_list = $_boo_serializer->serialize($_boo_user_list,'json');

        return $this->response($_boo_user_list);
    }

    /**
     * @Route("api/user/new",methods={"GET","POST"})
     * @param Request $_boo_request
     * @return Response
     */
    public function newAction(Request $_boo_request)
    {
        $_boo_user_name = $_boo_request->request->get('_username');
        $_boo_user_pass = $_boo_request->request->get('_password');
        $_boo_user_mail = $_boo_request->request->get('_email');
        $_boo_nom = $_boo_request->request->get('_nom');
        $_boo_prenom = $_boo_request->request->get('_prenom');
        $_boo_adresse = $_boo_request->request->get('_adresse');

        $_boo_user = new User();
        $_boo_user->setUsername($_boo_user_name);
        $_boo_user->setPlainPassword($_boo_user_pass);
        $_boo_user->setEmail($_boo_user_mail);
        $_boo_user->setPrenom($_boo_prenom);
        $_boo_user->setNom($_boo_nom);
        $_boo_user->setAdresse($_boo_adresse);
        $_boo_user->setEnabled(true);

        try {
            $this->getCrudService()->saveEntity($_boo_user, 'new', '');
            return $this->response('user create successful');
        } catch (OptimisticLockException $e) {
        } catch (ORMException $e) {
            return $this->response(array($e->getMessage(),$e->getCode()));
        }


    }

    public function updateAction()
    {

    }

    public function deleteAction()
    {

    }
}