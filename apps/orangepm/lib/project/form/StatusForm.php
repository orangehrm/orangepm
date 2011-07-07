<?php
/**
 * Description of Status form
 *
 * @author orangehrm
 */

class StatusForm extends sfForm {

    /**
	 * ConfigureProject form
	 *
	 */
    public function configure() {
        $projectService = new ProjectService();
        $status = $projectService->getAllProjectStatusesAsArray();
        $status[0] = 'All';
        $this->setWidgets(array(
            'searchByStatus' => new sfWidgetFormSelect(array('choices' => $status)),

        ));

        $this->widgetSchema->setNameFormat('projectSearch[%s]');

        $this->widgetSchema->setLabel('searchByStatus', __('Search by status'));


        $this->setValidators(array(
            'searchByStatus' => new sfValidatorString(array('required' =>false)),

        ));

    }

}
