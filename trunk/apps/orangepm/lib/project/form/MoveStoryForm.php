<?php

class MoveStoryForm extends sfForm {
        
    public $projectSerivce;
    public $projectId;
    public $loggedUser;
    private $formWidgets = array();
    private $formValidators  = array();
    
    
    public function getProjectService() {

        $this->projectSerivce = new StoryService();
        return $this->projectSerivce;
    }
     
    public function configure() {
        
        $this->projectId = $this->getOption('projectId');
        $this->loggedUser = $this->getOption('loggedUserId');
        $this->_setProjectWidgets();
        $this->setWidgets($this->formWidgets);
        $this->widgetSchema->setNameFormat('moveForm[%s]');
        $this->setValidators($this->formValidators);
    }
    
    
    private function _setProjectWidgets() {
        
        $projects = $this->_getProjects();
       
        $this->formWidgets['project'] = new sfWidgetFormSelect(array('choices' => $projects));
        $this->formWidgets['project']->setLabel(__("Select Project"));
        $this->formWidgets['storyId'] = new sfWidgetFormInputHidden();
        $this->formWidgets['projectId']= new sfWidgetFormInputHidden();
        $this->formValidators['project'] = new sfValidatorChoice(array('choices' => array_keys($projects)));
        $this->formValidators['storyId'] = new sfValidatorInteger();
        $this->formValidators['projectId'] = new sfValidatorInteger();
        
    }
    
      
    

    /*
    * getprojects
    * @return project array
    */
    
    private function _getProjects() {
        
        $list = array();
        
        if($this->loggedUser == User::USER_TYPE_SUPER_ADMIN){
            
            $projects = $this->getProjectService()->getProjectList();
            
        } else {
            
        $projects = $this->getProjectService()->getProjectByUserType($this->loggedUser);
        
        }
        
        foreach ($projects as $project) {
            
            if($project->getDeleted() != 0 && $project->getId() != $this->projectId)    {
                
                $list[$project->getId()] = $project->getName();
                
            }
            
        } 

        return $list;
    }
}

?>