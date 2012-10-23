<?php

class MoveStoryForm extends sfForm {
        
    public $projectSerivce;
    public $projectId;
    public $loggedUser;
    private $formWidgets = array();
    private $formValidators  = array();
       
     
    public function configure() {
        
        $this->projectId = $this->getOption('projectId');
        $this->loggedUser = $this->getOption('loggedUserId');
        $this->_setProjectWidgets();
        $this->setWidgets($this->formWidgets);
        $this->widgetSchema->setNameFormat('moveForm[%s]');
        $this->setValidators($this->formValidators);
         }
    
    
    private function _setProjectWidgets() {
        
        $this->projectSerivce = new ProjectService();
        $projects = $this->projectSerivce->getProjectsByUserId($this->loggedUser,$this->projectId);
        
        if (empty($projects) ) {
            $projects['#'] = "NO Projects";          
        }
        
        $this->formWidgets['project'] = new sfWidgetFormSelect(array('choices' => $projects));
        $this->formWidgets['project']->setLabel(__("Select Project"));
        $this->formWidgets['storyId'] = new sfWidgetFormInputHidden();
        $this->formWidgets['projectId']= new sfWidgetFormInputHidden();
        $this->formValidators['project'] = new sfValidatorChoice(array('choices' => array_keys($projects)));
        $this->formValidators['storyId'] = new sfValidatorInteger();
        $this->formValidators['projectId'] = new sfValidatorInteger();
        
    }
    
}

?>