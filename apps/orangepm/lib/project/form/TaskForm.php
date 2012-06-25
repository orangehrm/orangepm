<?php
/**
 * Description of Task form
 * * @author orangehrm
 */

class TaskForm extends sfForm {

    /**
     * Configure Task form
     *
     */
    private $formWidgets = array();
    private $formValidators  = array();
   
    public function configure() {
        $this->_setNameWidgets();
        $this->_setEffortWidgets();
        $this->_setEstimatedEndDate();
        $this->_setStatusWidgets();
        $this->_setOwnedByWidgets();
        $this->_setDescriptionWidgets();
        
        $this->setWidgets($this->formWidgets);
        $this->setValidators($this->formValidators);
        $this->widgetSchema->setNameFormat('task[%s]');
        $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);
    }
    
    private function _setNameWidgets() {
        $this->formWidgets['name'] = new sfWidgetFormInputText(array(), array('size'=>'71'));
        $this->formValidators['name'] = new sfValidatorString(array('required' => true), array('required' => __('The Task Name is required')));
        $this->formWidgets['name']->setLabel(__("Name")."<span class='mandatoryStar'>*</span>");
    }
    
    private function _setEffortWidgets() {
        $this->formWidgets['effort'] = new sfWidgetFormInputText(array(), array('size'=>'20'));
        $this->formValidators['effort'] = new sfValidatorString(array('required' => false));
        $this->formWidgets['effort']->setLabel(__("Estimated Effort"));
    }
    
    private function _setEstimatedEndDate() {
        $this->formWidgets['estimatedEndDate'] = new sfWidgetFormInputText(array(), array('readonly'=>true));
        $this->formValidators['estimatedEndDate'] = new sfValidatorString(array('required' => false), array('required' => __('Estimated End Date')));
        $this->formWidgets['estimatedEndDate']->setLabel(__("Estimated End Date"));
    }


    private function _setStatusWidgets() {
        
        $taskService = new TaskService();
        $statusChoices = $taskService->getAllTaskStatusArray();
        
        $this->formWidgets['status'] = new sfWidgetFormSelect(array('choices' => $statusChoices), array('label' => 'Status'));
        $this->formValidators['status'] = new sfValidatorChoice(array('choices' => array_keys($statusChoices)));
    }
    
    private function _setOwnedByWidgets() {
        $this->formWidgets['ownedBy'] = new sfWidgetFormInputText(array(), array('size'=>'71'));
        $this->formValidators['ownedBy'] = new sfValidatorString(array('required' => false));
        $this->formWidgets['ownedBy']->setLabel(__("Owned By"));
    }
    
    private function _setDescriptionWidgets() {
        $this->formWidgets['description'] = new sfWidgetFormTextarea(array('label' => 'Description'));
        $this->formValidators['description'] = new sfValidatorString(array('required' => false));
    }
}