<?php

class CopyStoryForm extends sfForm {
        
    public $projectService;
    public $projectId;
    public $loggedUser;
    private $formWidgets = array();
    private $formValidators  = array();
    private $authenticationService;
    
    public function getProjectService() {
        
        if(!$this->projectSerivce) {
            $this->projectSerivce = new StoryService();
        }
        return $this->projectSerivce; 
        
    }
     
    public function configure() {
        
        $this->projectId = $this->getOption('projectId');
        $this->loggedUser = $this->getOption('loggedUserId');
        $this->_setProjectWidgets();
        $this->setWidgets($this->formWidgets);
        $this->widgetSchema->setNameFormat('copyForm[%s]');
        $this->setValidators($this->formValidators);
        
    }
    
    
    private function _setProjectWidgets() {
        
        $projects = $this->_getProjects();
        if (empty($projects) ) {
            $projects['$'] = "No Projects";          
        }
        
        $this->formWidgets['project'] = new sfWidgetFormSelect(array('choices' => $projects));
        $this->formWidgets['project']->setLabel(__("Select Project"));
        $this->formWidgets['storyId'] = new sfWidgetFormInputHidden();
        $this->formWidgets['projectId']= new sfWidgetFormInputHidden();
        $this->formValidators['project'] = new sfValidatorChoice(array('choices' => array_keys($projects)));
        $this->formValidators['storyId'] = new sfValidatorInteger();
        $this->formValidators['projectId'] = new sfValidatorInteger();
        
    }
       
    /*
    * Get Projects
    * @return Doctrine_objectt array
    */  
    private function _getProjects() {
        $this->authenticationService = new AuthenticationService();
        $this->projectAccessLevel = $this->authenticationService->projectAccessLevel($this->loggedUser, $this->projectId);
        $projectList = array();
        
        if($this->projectAccessLevel == User::USER_TYPE_SUPER_ADMIN) {
            $projects = $this->getProjectService()->getProjectList(); 
        } else {            
            $projects = $this->getProjectService()->getProjectByUserType($this->loggedUser);
        }
        
        foreach ($projects as $project) {
            
            if(($project->getDeleted() != 0) && ($project->getId() != $this->projectId)) {               
                $projectList[$project->getId()] = $project->getName();               
            }
            
        } 
        return $projectList;
    }
}

?>