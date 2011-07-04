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
    public function configure() {
        
        $projectService = new ProjectService();        
        $statusChoices = $projectService->getAllStatusAsArray();        
        
        $this->setWidgets(array(
            'name' => new sfWidgetFormInputText(),
            'status' => new sfWidgetFormSelect(array('choices' => $statusChoices)),
        ));

        $this->widgetSchema->setNameFormat('project[%s]');


        $this->setValidators(array(
            'name' => new sfValidatorString(array('required' => true), array('required' => __('The Project Name is required'))),
            'status' => new sfValidatorPass(),
        ));

    }

}
