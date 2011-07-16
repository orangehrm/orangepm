<?php
/**
 * Description of Project form
 *
 * @author orangehrm
 */

class ProjectForm extends sfForm {

    /**
	 * ConfigureProject form
	 *
	 */
    private $formWidgets = array();
    private $formValidators  = array();
   
    public function configure() {
        
        $this->_setNameWidgets();
        $this->_setStatusWidgets();
        
        if($this->getOption('user')) {
            $this->_setProjectAdminWidgets();
        }
        $this->setWidgets($this->formWidgets);
        $this->setValidators($this->formValidators);

        $this->widgetSchema->setNameFormat('project[%s]');

    }
    
    private function _setNameWidgets() {
        
        $this->formWidgets['name'] = new sfWidgetFormInputText(array('label' => 'Name'), array('size'=>'40'));
        $this->formValidators['name'] = new sfValidatorString(array('required' => true), array('required' => __('The Project Name is required')));
        
    }
    
    private function _setStatusWidgets() {
        
        $projectService = new ProjectService();        
        $statusChoices = $projectService->getAllProjectStatusesAsArray();
        
        $this->formWidgets['status'] = new sfWidgetFormSelect(array('choices' => $statusChoices), array('label' => 'Status'));
        $this->formValidators['status'] = new sfValidatorChoice(array('choices' => array_keys($statusChoices)));
    }
    
    private function _setProjectAdminWidgets() {
        
        $userService = new UserService();        
        $projectAdmins = $userService->getAllUsersAsArray();
        
        $this->formWidgets['projectAdmin'] = new sfWidgetFormSelect(array('choices' => $projectAdmins), array('label' => 'Project Admin'));
        $this->formValidators['projectAdmin'] = new sfValidatorChoice(array('choices' => array_keys($projectAdmins)));
    }
    
}
