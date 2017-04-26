<?php
namespace Ecompassaro\Acesso;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;

class Controller extends AbstractActionController
{

    protected $resource;

    protected $defaultRoute = 'site';

    protected $viewModel;

    public function __construct(ViewModel $viewModel, $resource = null, $defaultRoute = null)
    {
        $this->viewModel = $viewModel;
        
        if ($resource) {
            $this->resource = $resource;
        }
        
        if ($defaultRoute) {
            $this->defaultRoute = $defaultRoute;
        }
    }

    /**
     *
     * @return \Zend\Http\Response
     */
    private function requerPermissao()
    {
        $redirect = $this->params()->fromQuery('routeRedirect');
        
        if (! ($this->defaultRoute == $this->getEvent()
            ->getRouteMatch()
            ->getMatchedRouteName()) && ! $this->getViewModel()->podeAcessar($this->resource)) {
            
            $this->flashMessenger()->addWarningMessage('Você não tem acesso a esse recurso. Por favor efetue o login com um usuário que tenha esse acesso.');
            return $this->redirect()->toRoute($redirect ? $redirect : $this->defaultRoute);
        }
    }

    /**
     * Obtem a view model dessa controller
     *
     * @return \Zend\View\Model\ViewModel
     */
    protected function getViewModel()
    {
        if (! ($this->viewModel instanceof ViewModel)) {
            throw new MalUsoException('Acesso\AcessoControler->viewModel deve ser um instância de Acesso\AcessoViewModel');
        }
        
        return $this->viewModel;
    }

    /**
     * No evento de disparo, é verificado se o usuário é logado
     * se não for é redirecionado.
     *
     * @see \Zend\Mvc\Controller\AbstractActionController::onDispatch()
     */
    public function onDispatch(MvcEvent $e)
    {
        $this->requerPermissao();
        parent::onDispatch($e);
    }
}
