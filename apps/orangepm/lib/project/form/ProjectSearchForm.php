<?php
/**
 * Description of Status form
 *
 * @author orangehrm
 */

class ProjectSearchForm extends sfForm {

    /**
	 * ConfigureProject form
	 *
	 */
    public function configure() {
        $projectService = new ProjectService();
        $status = $projectService->getAllProjectStatusesAsArray();
        $status[0] = __('All');
        $this->setWidgets(array(
            'status' => new sfWidgetFormSelect(array('choices' => $status)),

        ));

        $this->widgetSchema->setNameFormat('projectSearch[%s]');

        $this->widgetSchema->setLabel('status', __('Search by status'));


        $this->setValidators(array(
            'status' => new sfValidatorChoice(array('choices' => array_keys($status))),

        ));

    }

}
