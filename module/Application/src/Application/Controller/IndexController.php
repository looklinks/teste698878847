<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController,
    Zend\View\Model\ViewModel,
    Login\Form\LoginForm;
    
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session as SessionStorage;
use Zend\Session\Container;

class IndexController extends AbstractActionController {
    
    /**
     *
     * @var EntityManager
     */
    protected $em;
    
        /*
     * @return EntityManager
     */

    protected function getEm() {
        if (null === $this->em)
            $this->em = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');

        return $this->em;
    }

    public function indexAction()
    {

        $msg = array();
        $msg['ref']      = $this->params()->fromRoute('ref', 0);
        $msg['tipo']     = $this->params()->fromRoute('tipo', 0);
        $msg['cod_msg']  = $this->params()->fromRoute('cod_msg', 0);

        $request = $this->getRequest();
        $error = false;

        return new ViewModel(array('form' => $form,'msg' => $msg));    

    }

	public function logoutAction() {
        $auth = new AuthenticationService;
        $auth->setStorage(new SessionStorage('Login'));
        $auth->clearIdentity();
        $session = new \Zend\Session\Container('carrinho'); 
        $session->getManager()->destroy(); 
        return $this->redirect()->toRoute('home');
    }
    


}
