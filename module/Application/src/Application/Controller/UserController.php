<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Application\Service\UserManager;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class UserController extends AbstractActionController
{
    public function listAction()
    {
        /** @var UserManager $userManager */
        $userManager = $this->getServiceLocator()->get('userManager');
        
        $data = [
            'users' => $userManager->getList()
        ];
        
        return new ViewModel($data);
    }
    
    public function addAction()
    {
        return new ViewModel();
    }
    
    public function editAction()
    {
        /** @var UserManager $userManager */
        $userManager = $this->getServiceLocator()->get('userManager');
        
        $user = $userManager->get($this->params('id'));
        
        if ($this->getRequest()->isPost()) {
            $user->setFirstname($this->params()->fromPost('Firstname'));
            $user->setLastname($this->params()->fromPost('Lastname'));
            $userManager->save($user);
            $this->redirect()->toUrl('/user');
        }
        
        if (!$user) {
            $this->getResponse()->setStatusCode(404);
        } else {
            $data = [
                'user' => $user
            ];
            
            return new ViewModel($data);
        }
    }
}
