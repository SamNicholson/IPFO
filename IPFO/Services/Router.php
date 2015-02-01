<?php

namespace IPFO\Services;
use IPFO\Exceptions\InvalidAddressException;

/**
 * Class Router
 */
class Router {
    /**
     * The controller that will be run, by default set to global DEFAULT_CONTROLLER for security reasons
     * @var string
     */
    protected $controller    = 'Request';
    /**
     * The action that will be run, by default set to global DEFAULT_ACTION
     * @var string
     */
    protected $action        = 'Search';
    /**
     * @var array
     */
    protected $params        = array();    /**
     * @var array
     */
    protected $service        = array();
    /**
     * @var string
     */
    protected $basePath;

    /**
     * This constructor sets the core requirements of the class and then processes any controller, action or parameters which is the GET request. Note it doesn't run the generated commands, simply processes the variables
     * @param array $options
     */
    function __construct(array $options = array()){
       $this->parseURI();
    }

    /**
     * This is the function that is responsible for parsing the parameters from the URL. It looks for controller and action and then will save everything else as a parameter.
     */
    function parseURI(){
        //the parameters are to be passed through in a GET request, we only accept GET requests, POST is used for data
        try{

            $requestPath = str_replace('/ipfo/v1/','',$_SERVER['REQUEST_URI']);

            $addressParams = explode('/',$requestPath);

            $this->setController($addressParams[0]);
            $this->setAction($addressParams[0],$addressParams[1]);

            $this->params = array(
                'number' => $addressParams[2]
            );
        }
        catch(InvalidAddressException $e){
            //TODO handle the error here
            echo($e->getMessage());
        }

    }

    /**
     * This is the setter for the private controller variable
     * @param $controller
     * @return $this
     * @throws InvalidAddressException
     */
    function setController($controller){
        $controller = ucfirst($controller);
        $controller = $this->getControllerName($controller);
        if (!$this->checkController($controller)) {
            throw new InvalidAddressException("The action controller '$controller' has not been defined.");
        }
        $this->controller = $controller;
        return $this;
    }

    /**
     * This checks whether the requested controller is defined as a class in the controller namespace which can be called.
     * @param $controller
     * @return bool
     */
    function checkController($controller){
        $controller = $this->getControllerName($controller);
        if (class_exists($controller)) {
            return true;
        }
        return false;
    }

    /**
     * This is the setter for any other parameters in GET or POST. POST parameters will be made into a requests entry in the params array that is passed onwards.
     * @param array $params
     * @return $this
     */
    function setParams(array $params){
        $params['request'] = $_POST;
        $this->params = $params;
        return $this;
    }

    /**
     * This is the setter for the action
     * @param $controller
     * @param $action
     * @return $this
     * @throws InvalidAddressException
     */
    function setAction($controller,$action){
        if (!$this->checkAction($controller,$action)) {
            throw new InvalidAddressException("Type of search number on ".$controller." was invalid");
        }
        $this->action = $action;
        return $this;
    }

    /**
     * This checks that the action specified exists within the controller we have chosen and is also a method of it.
     * @param $controller
     * @param $action
     * @return bool
     */
    function checkAction($controller,$action){

        $controller = $this->getControllerName($controller);
        $reflector = new \ReflectionClass($controller);

        if ($reflector->hasMethod($action)) {
            return true;
        }
        return false;
    }

    /**
     * @param $controller
     * @return string
     */
    function getControllerName($controller){
        if(!stristr($controller,"Controller")){
            $controller = $controller."Controller";
        }
        if(!stristr($controller,"IPFO\\Controllers\\")){
            $controller = "IPFO\\Controllers\\".$controller;
        }
        return $controller;
    }

    /**
     * Checks if the route requested is valid and can be called.
     * @param $controller
     * @param $action
     * @return bool
     */
    function checkRoute($controller,$action){
        if(!$this->checkController($controller) || !$this->checkAction($controller,$action)){
            return false;
        }
        return true;
    }

//    /**
//     * Will return a URL string to the to the user which points to the specified controller, action and parameters
//     * This is also where the system is set as either https or http. It uses the global USE_SSL.
//     * @param string $controller
//     * @param string $action
//     * @param array $params
//     * @return string
//     */
//    function getRoute($controller = DEFAULT_CONTROLLER,$action = DEFAULT_ACTION,$params = array()){
//        $params['controller'] = $controller;
//        $params['action'] = $action;
//        if($this->checkRoute($controller,$action)){
//            if(USE_SSL){
//                $prefix = 'https://';
//            }
//            else{
//                $prefix = 'http://';
//            }
//            return $prefix.$this->basePath.'index.php?'.http_build_query($params);
//        }
//        else{
//            return '#';
//        }
//    }

    /**
     * This function runs the action and the controller which have been set in the class.
     * It feeds through dependencies to the controller and parameters to the action.
     */
    function run(){
        call_user_func_array(array(new $this->controller(), $this->action), array($this->params));
    }

} 