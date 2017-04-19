<?php
namespace Ecompassaro\Acesso;

use Zend\View\Model\ViewModel as ZendViewModel;

/**
 * Gerador da estrutura da página do site
 */
class ViewModel extends ZendViewModel
{

    protected $acesso;

    /**
     * Injeta dependências
     * @param \Acesso\Acesso
     */
    public function __construct(Acesso $acesso)
    {
        $this->acesso = $acesso;
    }

    /**
     * Verifica se o usuário autenticado pode acessar o recurso
     * @param string $resource
     * @return boolean
     */
    public function podeAcessar($resource)
    {
        if (! ($this->acesso instanceof Acesso)) {
            throw new MalUsoException('Acesso\AcessoViewModel->acesso deve ser um instância de Acesso\Acesso');
        }
        return $this->acesso->isRoleAtualAllowed($resource);
    }
}
