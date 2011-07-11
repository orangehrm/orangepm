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
        
        $userService = new UserService();        
        $projectAdmins = $userService->getAllUsersAsArray();
        
        $projectService = new ProjectService();        
        $statusChoices = $projectService->getAllProjectStatusesAsArray();
        
        $this->setWidgets(array(
            'name' => new sfWidgetFormInputText(array(), array('size'=>'27')),
            'status' => new sfWidgetFormSelect(array('choices' => $statusChoices)),
            'projectAdmin' => new sfWidgetFormSelect(array('choices' => $projectAdmins)),
        ));

        $this->widgetSchema->setNameFormat('project[%s]');
        $this->widgetSchema->setLabel('name', 'Name');
        $this->widgetSchema->setLabel('status', 'Status');
        $this->widgetSchema->setLabel('projectAdmin', 'Project Admin');


        $this->setValidators(array(
            'name' => new sfValidatorString(array('required' => true), array('required' => __('The Project Name is required'))),
            'status' => new sfValidatorChoice(array('choices' => array_keys($statusChoices))),
            'projectAdmin' => new sfValidatorChoice(array('choices' => array_keys($projectAdmins))),
        ));

    }

}
