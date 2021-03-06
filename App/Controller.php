<?php
class App_Controller
{
    protected $_http;
    protected $_view;

    public function __construct()
    {
        $this->_http = App_Http::getInstance();
        $this->_view = App_View::getInstance();  
    }

    
    public function indexAction()
    {
        
    }


    protected function getModel()
    {
    	$ctrlNameArr = explode('_', get_class($this));
    	$modelName = 'App_Model_DbTable_'.$ctrlNameArr[count($ctrlNameArr)-1];
    	return new $modelName();
    }
    
    protected function preDispatch()
    {
        
    }

    public function dispatch($actionName)
    {
        $this->preDispatch();
        
        if(!method_exists($this, $actionName)){
        	throw new Exception("invalid ActionName --- {$actionName} --- In ---".get_class($this));
        }
        $this->$actionName();
        $this->postDispatch();
    }

    protected function postDispatch()
    {

        if($this->_http->getParam('parse') == 'json'){
            $this->_view->returnJson();
        }else{
            $this->_view->render();
        }

    }
    
    public function redirect(array $data,$absolute=false)
    {
    	App_Headers::redirect($data,$absolute);
    }
    
    protected function getFilters(){

        $yearDbTable = new App_Model_DbTable_Years();
    	$this->_view->years = $yearDbTable->fetchAll(array());

    	$seriesDbTable = new App_Model_DbTable_Seriess();
    	$this->_view->series = $seriesDbTable->fetchAll(array());



        $lecturersDbTable = new App_Model_DbTable_Lecturers();

    	$this->_view->lecturers = $lecturersDbTable->fetchAll(array());


    	$locationsDbTable = new App_Model_DbTable_Locations();
    	$this->_view->places = $locationsDbTable->fetchAll(array());
    	
    }
    
}