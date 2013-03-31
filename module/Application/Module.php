<?php

namespace Application;

use Zend\Mvc\ModuleRouteListener,
    Zend\Mvc\MvcEvent,
    Zend\ModuleManager\ModuleManager;
use Zend\Authentication\AuthenticationService,
    Zend\Authentication\Storage\Session as SessionStorage;
	
use DoctrineModule\Validator\NoObjectExists as NoObjectExistsValidator;
use DoctrineModule\Validator\ObjectExists as ObjectExistsValidator;
   
//use Login\Form\ReminderForm as ReminderForm;    

class Module {

    public function getConfig() {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig() {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                	'Admin' 	  => __DIR__ . '/src/' . "Admin",
                    'Login' 	  => __DIR__ . '/src/' . "Login",
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function onBootstrap($e) {
    	
		
        $e->getApplication()->getEventManager()->getSharedManager()->attach('Zend\Mvc\Controller\AbstractActionController', 'dispatch', function($e) {
                    $controller = $e->getTarget();
                    $controllerClass = get_class($controller);
                    $moduleNamespace = substr($controllerClass, 0, strpos($controllerClass, '\\'));
                    $config = $e->getApplication()->getServiceManager()->get('config');
                    if (isset($config['module_layouts'][$moduleNamespace])) {
                    	$controller->layout($config['module_layouts'][$moduleNamespace]);
                    }
					
					//seta variavel no layout
					$e->getViewModel()->setVariable('controllerclass', $controllerClass);
                }, 98);
    }
	

    public function init(ModuleManager $moduleManager) {
        $sharedEvents = $moduleManager->getEventManager()->getSharedManager();
		
        $sharedEvents->attach("Application", 'dispatch', function($e) {
        				
        			$auth = new AuthenticationService;
                    $auth->setStorage(new SessionStorage("Login"));

                    $controller = $e->getTarget();
                    $matchedRoute = $controller->getEvent()->getRouteMatch()->getMatchedRouteName();

                    if (!$auth->hasIdentity() and ($matchedRoute == "meus-anuncios")) {
                        return $controller->redirect()->toRoute('home');
                    }
                }, 99);
    }

    public function getServiceConfig() {

        return array(
            'factories' => array(
                
				'service_helper_session_login' => function($service){
					$helper = $service->get('viewhelpermanager')->get('UserIdentity');
					$session =$helper('Login'); 
					return ( $session != false? $session : false);
				},
				
                'service_faleconosco_form' => function ($service) {
                     $form = new \Application\Form\FaleConoscoForm();
                     return $form;
                },
                
                'service_faleconosco' => function($service) {
                    $obj = new \Application\Service\FaleConosco($service->get('Doctrine\ORM\EntityManager'));
                    $obj->setMessageRenderer($service->get('Zend\View\Renderer\PhpRenderer'));
                    return $obj;
                },
                
                'Login\Auth\Adapter' => function($service) {
                    return new \Login\Auth\Adapter($service->get('Doctrine\ORM\EntityManager'));
                },
                
				'Admin\Auth\Adapter' => function($service) {
                    return new \Admin\Auth\Adapter($service->get('Doctrine\ORM\EntityManager'));
                },
                
                
                
				'service_servico_form' => function ($service) {
                     $form = new \Admin\Form\ServicoForm();
                     return $form;
                },

                
				
				'service_resolver_view_servico' => function($sm) {
                  
                  $map = new \Zend\View\Resolver\TemplateMapResolver(array(
                            'admin/servico/edit' => __DIR__ . '/view/admin/servico/index.phtml',
                        ));
                  return new \Zend\View\Resolver\TemplateMapResolver($map);
                }
                
            ),
        );
    }

    public function getViewHelperConfig() {
        return array(
            'invokables' => array(
                'UserIdentity' => new View\Helper\UserIdentity(),
                'SessionSelection' => new View\Helper\SessionSelection()
            )
        );
    }


}
