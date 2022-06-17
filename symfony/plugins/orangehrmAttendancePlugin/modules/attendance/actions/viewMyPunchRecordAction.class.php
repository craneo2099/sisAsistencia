<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of viewMyAttendanceRecordAction
 *
 * @author orangehrm
 */
class viewMyPunchRecordAction extends sfAction {

    public function execute($request) {

        $this->punchService = $this->getPunchService();
        $this->employeeId = $this->getContext()->getUser()->getEmployeeNumber();
        $this->date = $this->request->getParameter('date');
        $this->trigger = $request->getParameter('trigger');
        $this->actionRecorder="viewMy";
        $values = array('date' => $this->date, 'employeeId' => $this->employeeId, 'trigger' => $this->trigger);
        $this->form = new PunchRecordSearchForm(array(), $values);

        if (!($this->trigger)) {
            if ($request->isMethod('post')) {

                $this->form->bind($request->getParameter('attendance'));


                if ($this->form->isValid()) {
                    
                }
            }
        }
    }

    public function getPunchService() {

        if (is_null($this->punchService)) {

            $this->punchService = new AttendanceService();
        }

        return $this->punchService;
    }

    public function setPunchService(PunchService $punchService) {

        $this->punchService = $punchService;
    }

}

?>
