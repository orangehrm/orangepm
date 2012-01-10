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
        $this->_setStartDateWidgets();
        $this->_setEndDateWidgets();
        if($this->getOption('user')) {
            $this->_setProjectAdminWidgets();
        }
        $this->_setDescriptionWidgets();
        $this->setWidgets($this->formWidgets);
        $this->setValidators($this->formValidators);

        $this->widgetSchema->setNameFormat('project[%s]');
        
    }
    
    private function _setNameWidgets() {
        
        $this->formWidgets['name'] = new sfWidgetFormInputText(array(), array('size'=>'85'));
        $this->formValidators['name'] = new sfValidatorString(array('required' => true), array('required' => __('The Project Name is required')));
        $this->formWidgets['name']->setLabel(__("Name")."<span class='mandatoryStar'>*</span>");
    }
    private function _setEndDateWidgets() {
        
        $this->formWidgets['endDate'] = new sfWidgetFormInputText(array('label' => 'Committed End Date'), array('size'=>'20'));
        $this->formValidators['endDate'] = new sfValidatorString(array('required' => false), array('required' => __('Commited End Date is required')));
        
    }
    
    private function _setStartDateWidgets() {
        
        $this->formWidgets['startDate'] = new sfWidgetFormInputText(array(), array('size'=>'20'));
        $this->formValidators['startDate'] = new sfValidatorString(array('required' => true), array('required' => __('Development Start Date is required')));
        $this->formWidgets['startDate']->setLabel(__("Development Start Date")."<span class='mandatoryStar'>*</span>");
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
        $projectAdmins[0] = __('--Select--');
        
        $this->formWidgets['projectAdmin'] = new sfWidgetFormSelect(array('choices' => $projectAdmins), array('label' => 'Project Admin'));
        $this->formValidators['projectAdmin'] = new sfValidatorChoice(array('choices' => array_keys($projectAdmins)));
    }
    
    private function _setDescriptionWidgets() {
        
        $this->formWidgets['description'] = new sfWidgetFormTextarea(array('label' => 'Description'));
        $this->formValidators['description'] = new sfValidatorString(array('required' => false));
        
    }
    
}