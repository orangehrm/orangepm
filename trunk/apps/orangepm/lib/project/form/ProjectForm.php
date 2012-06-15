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
    private $nonSelected=array();
    private $all=array();
    private $selected=array();
    
   
    public function configure() {
        
        $this->_setNameWidgets();
        $this->_setStatusWidgets();
        $this->_setStartDateWidgets();
        $this->_setEndDateWidgets();
        if($this->getOption('user')) {
            $this->_setProjectAdminWidgets();
        }
        if($this->getOption('newproject')) {
            $this->_setProjectUsersForNewProject($this->getOption('removeUserId'));
        }
        else{
            $this->_setProjectUsers($this->getOption('projectid'),$this->getOption('removeUserId'));
        }
        $this->_setDescriptionWidgets();
        $this->_setProjectUserWidgets();
        $this->setWidgets($this->formWidgets);
        $this->setValidators($this->formValidators);

        $this->widgetSchema->setNameFormat('project[%s]');
        
        
    }
    private function _setProjectUsers($projectId,$removeUserId){
        $projectService = new ProjectService();
        $userService = new UserService();
        $this->all=$userService->getAllUsersAsArray();
        $this->selected=$projectService->getUsersForProjectAsArrayOnlyName($projectId);
        $this->nonSelected=array_diff($this->all, $this->selected);
        //die($removeUserId);
        if($removeUserId!=null){
            unset($this->selected[$removeUserId]);
            unset($this->all[$removeUserId]);
        }
    }
    private function _setProjectUsersForNewProject($removeUserId){
        $userService = new UserService();
        $this->nonSelected=$userService->getAllUsersAsArray();
        if($removeUserId!=null){
            unset($this->nonSelected[$removeUserId]);
        }
    }
    private function _setNameWidgets() {
        
        $this->formWidgets['name'] = new sfWidgetFormInputText(array(), array());
        $this->formValidators['name'] = new sfValidatorString(array('required' => true), array('required' => __('The Project Name is required')));
        $this->formWidgets['name']->setLabel(__("Name")."<span class='mandatoryStar'>*</span>");
    }
    private function _setEndDateWidgets() {
        
        $this->formWidgets['endDate'] = new sfWidgetFormInputText(array('label' => 'Committed End Date'), array());
        $this->formValidators['endDate'] = new sfValidatorString(array('required' => false), array('required' => __('Commited End Date is required')));
        
    }
    
    private function _setStartDateWidgets() {
        
        $this->formWidgets['startDate'] = new sfWidgetFormInputText(array(), array());
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
        
        $this->formWidgets['projectAdmin'] = new sfWidgetFormSelect(array('choices' => $projectAdmins) ,array('label' => 'Project Admin'));
        $this->formValidators['projectAdmin'] = new sfValidatorChoice(array('choices' => array_keys($projectAdmins)));
    }
    
    private function _setDescriptionWidgets() {
        
        $this->formWidgets['description'] = new sfWidgetFormTextarea(array('label' => 'Description'));
        $this->formValidators['description'] = new sfValidatorString(array('required' => false));
        
    }
    private function _setProjectUserWidgets() {
        
        $this->formWidgets['projectUserAll'] = new sfWidgetFormSelectMany(array('choices' => $this->nonSelected));
        $this->formValidators['projectUserAll'] = new sfValidatorChoice(array('choices' => array_keys($this->nonSelected),'required' => false));
        $this->formWidgets['projectUserAll']->setLabel(__("Avalable Users"));
        $this->formWidgets['projectUserSelected'] = new sfWidgetFormSelectMany(array('choices' => $this->selected));
        $this->formValidators['projectUserSelected'] = new sfValidatorChoice(array('choices' => array_keys($this->selected),'required' => false));
        $this->formWidgets['projectUserSelected']->setLabel(__("Selected Users"));

    }
    
}
