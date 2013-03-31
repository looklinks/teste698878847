<?php

namespace Application;

return array(
    'router' => array(
        'routes' => array(
            'home' => array(
              'type' => 'segment',
                'options' => array(
                    'route' => '/[:action][/:tipo]',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
              
              /*
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => '/',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                   
                    'action' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '[:action][/:msg_id]',
                            'defaults' => array(
                                'controller' => 'Application\Controller\Index',
                                'action'     => 'index'
                            )
                        )
                    ),
                    

                    
                ),
                
                */
            ),
            
			
            
            'home-message' => array(
              'type' => 'segment',
                'options' => array(
                    'route' => '/[:tipo][/:ref][/:cod_msg]',
                    'constraints' => array(
                        'tipo'=> '[a-z]+',
                        'cod_msg'=> '[0-9]+'
                    ),
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),
            'faleconosco' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/faleconosco',
                    'defaults' => array(
                      'controller' => 'Application\Controller\FaleConosco',
                        'action'  => 'index'
                        
                    )
                )
            ),

            'admin' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/admin',
                    'defaults' => array(
                      'controller' => 'Admin\Controller\Index',
                        'action'  => 'index'
                        
                    )
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    // Segment route for viewing one blog post
                    'servico' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/servico[/:action][/:id]',
                            'defaults' => array(
                            	'controller' => 'Admin\Controller\Servico',
                                'action' => 'index'
                            )
                        )
                    ),

                    'plano' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/plano[/:action][/:id]',
                            'defaults' => array(
                                'controller' => 'Admin\Controller\Plano',
                                'action' => 'index'
                            )
                        )
                    ),
                   
                )
            ),

            'logout' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/logout',
                    'defaults' => array(
                        'action' => 'logout',
                        'controller'=>'Login\Controller\Login'
                    ),
                ),
            ),
  
            
            
        ),    
    ),
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\Index'       => 'Application\Controller\IndexController',
            'Application\Controller\FaleConosco' => 'Application\Controller\FaleConoscoController',
             
        ),
    ),
    
    'module_layouts' => array(
      'Application' => 'layout/layout',
      //'Login'       => 'layout/layout'
      'Admin'       => 'layout/layout-admin'
    ),
    
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions' => true,
        'doctype' => 'HTML5',
        'not_found_template' => 'error/404',
        'exception_template' => 'error/index',
        'template_map' => array(
            'layout/layout' => __DIR__ . '/../view/layout/layout.phtml',
            //'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404' => __DIR__ . '/../view/error/404.phtml',
            'error/index' => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),
    'doctrine' => array(
        'driver' => array(
            __NAMESPACE__ . '_driver' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/' . __NAMESPACE__ . '/Entity')
            ),
            'orm_default' => array(
                'drivers' => array(
                    __NAMESPACE__ . '\Entity' => __NAMESPACE__ . '_driver'
                ),
            ),
        ),
    ),
);