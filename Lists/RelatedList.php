<?php
namespace Sonata\AdminBundle\Lists;

use Sonata\AdminBundle\Admin\Admin;
use Symfony\Component\HttpFoundation\Request;

class RelatedList
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var callable
     */
    protected $controller = 'Sonata\AdminBundle\Controller\CRUDController::relatedListAction';

    /**
     * @var \Sonata\AdminBundle\Admin\Admin
     */
    protected $admin;

    /**
     * @var array
     */
    protected $dataGridValues = array();

    protected $serviceContainer;

    public function __construct(Admin $admin, \Symfony\Component\DependencyInjection\ContainerInterface $serviceContainer)
    {
        $this->admin = $admin;
        $this->serviceContainer = $serviceContainer;
    }

    public function addDataGridValue($field, $type, $value)
    {
        $this->dataGridValues[] = array($field, $type, $value);
    }

    public function getDataGridValues()
    {
        return $this->dataGridValues;
    }

    public function getController()
    {
        return $this->controller;
    }

    public function getAdmin()
    {
        return $this->admin;
    }

    public function getRequest()
    {
        $request = new Request();
        $request->attributes->set('_controller', $this->getController());
        $request->attributes->set('_sonata_admin', $this->getAdmin());

        return $request;
    }

    public function getControllerObject()
    {
        $request = $this->getRequest();
        $controller = $this->serviceContainer->get('debug.controller_resolver')->getController($request);
        $controller[0]->setRequest($request);
        $controller[0]->configure();
        foreach ($this->getDataGridValues() as $value)
        {
            call_user_func_array(array($this->getAdmin()->getDataGrid(), 'setValue'), $value);
        }

        return $controller;
    }

    public function getContent()
    {
        $controller = $this->getControllerObject();
        $response = call_user_func($controller);

        return $response->getContent();
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }
}