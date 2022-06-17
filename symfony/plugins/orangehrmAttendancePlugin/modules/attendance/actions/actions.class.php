<?php

/**
 * OrangeHRM is a comprehensive Human Resource Management (HRM) System that captures
 * all the essential functionalities required for any enterprise.
 * Copyright (C) 2006 OrangeHRM Inc., http://www.orangehrm.com
 *
 * OrangeHRM is free software; you can redistribute it and/or modify it under the terms of
 * the GNU General Public License as published by the Free Software Foundation; either
 * version 2 of the License, or (at your option) any later version.
 *
 * OrangeHRM is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
 * without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with this program;
 * if not, write to the Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor,
 * Boston, MA  02110-1301, USA
 */
class AttendanceActions extends sfActions {

    private $attendanceService;
    private $punchService;
    private $employeeService;

    public function getAttendanceService() {

        if (is_null($this->attendanceService)) {

            $this->attendanceService = new AttendanceService();
        }

        return $this->attendanceService;
    }

    public function setAttendanceService(AttendanceService $attendanceService) {

        $this->attendanceService = $attendanceService;
    }
    
    public function getEmployeeService() {
        
        if (is_null($this->employeeService)) {
            
            $this->employeeService = new EmployeeService();
        }
        
        return $this->employeeService;
    }
    
    public function setEmployeeService(EmployeeService $employeeService) {
        
        $this->employeeService = $employeeService;
    }
    
    public function getPunchService() {
        
        if (is_null($this->punchService)) {
            
            $this->punchService = new PunchService();
        }
        
        return $this->punchService;
    }
    
    public function setPunchService(PunchService $punchService) {
        
        $this->punchService = $punchService;
    }

    public function executeHello($request) {

        $this->currentTime = date('H:i:s');
    }

    public function executeValidatePunchOutOverLapping($request) {

        $temppunchInTime = $request->getParameter('punchInTime');
        $temppunchOutTime = $request->getParameter('punchOutTime');
        $timezone = $request->getParameter('timezone');
        $recordId = $request->getParameter('recordId');

        $ti = strtotime($temppunchInTime);
        $to = strtotime($temppunchOutTime) - $timezone;


        $punchInDate = date("Y-m-d", $ti);
        $punchInTime = date("H:i:s", $ti);
        $punchIn = $punchInDate . " " . $punchInTime;

        $punchOutDate = date("Y-m-d", $to);
        $punchOutTime = date("H:i:s", $to);
        $punchOut = $punchOutDate . " " . $punchOutTime;


        $employeeId = $request->getParameter('employeeId');
        $this->isValid = $this->getAttendanceService()->checkForPunchOutOverLappingRecords($punchIn, $punchOut, $employeeId, $recordId);
    }

    public function executeValidatePunchInOverLapping($request) {

        $temppunchInTime = $request->getParameter('punchInTime');
        $timezone = $request->getParameter('timezone');

        $ti = strtotime($temppunchInTime) - $timezone;
        $punchInDate = date("Y-m-d", $ti);
        $punchInTime = date("H:i:s", $ti);
        $punchIn = $punchInDate . " " . $punchInTime;

        $employeeId = $request->getParameter('employeeId');

        $this->isValid = $this->getAttendanceService()->checkForPunchInOverLappingRecords($punchIn, $employeeId);
    }

    public function executeValidatePunchInOverLappingWhenEditing($request) {

        $temppunchInTime = $request->getParameter('punchInTime');
        $timezone = $request->getParameter('timezone');
        $recordId = $request->getParameter('recordId');
        $punchOutUtcTime = $request->getParameter('punchOutUtcTime');

        $ti = strtotime($temppunchInTime) - $timezone * 3600;
        $punchInDate = date("Y-m-d", $ti);
        $punchInTime = date("H:i:s", $ti);
        $punchIn = $punchInDate . " " . $punchInTime;

        $to = $punchOutUtcTime / 1000;
        $punchOutDate = date("Y-m-d", $to);
        $punchOutTime = date("H:i:s", $to);
        $punchOut = $punchOutDate . " " . $punchOutTime;

        $employeeId = $request->getParameter('employeeId');
// $this->isValid=$punchIn;
        $this->isValid = $this->getAttendanceService()->checkForPunchInOverLappingRecordsWhenEditing($punchIn, $employeeId, $recordId, $punchOut);
    }

    public function executeValidatePunchOutOverLappingWhenEditing($request) {

        $temppunchInTime = $request->getParameter('punchInTime');
        $temppunchOutTime = $request->getParameter('punchOutTime');
        $inTimezone = $request->getParameter('inTimezone');
        $outTimezone = $request->getParameter('outTimezone');
        $recordId = $request->getParameter('recordId');
        $ti = strtotime($temppunchInTime) - $inTimezone;
        $to = strtotime($temppunchOutTime) - $outTimezone;


        $punchInDate = date("Y-m-d", $ti);
        $punchInTime = date("H:i:s", $ti);
        $punchIn = $punchInDate . " " . $punchInTime;

        $punchOutDate = date("Y-m-d", $to);
        $punchOutTime = date("H:i:s", $to);
        $punchOut = $punchOutDate . " " . $punchOutTime;


        $employeeId = $request->getParameter('employeeId');
        $this->isValid = $this->getAttendanceService()->checkForPunchOutOverLappingRecordsWhenEditing($punchIn, $punchOut, $employeeId, $recordId);
    }

    public function executeGetCurrentTime($request) {
        $timeZoneOffset = $request->getParameter('timeZone');
        $timeStampDiff = $timeZoneOffset - date('Z');
        $currentDate = date('Y-m-d', time() + $timeStampDiff);
        $currentTime = date('H:i', time() + $timeStampDiff);

        $this->values = $currentDate . "_" . $currentTime;
    }

    public function executeGetRelatedAttendanceRecords($request) {


        $this->allowedToDelete = array();
        $this->allowedActions = array(
            'Delete' => false,
            'Edit' => false,
            'PunchIn' => false,
            'PunchOut' => false,
        );
        
        $userRoleManager = $this->getContext()->getUserRoleManager();        

        $userEmployeeNumber = $userRoleManager->getUser()->getEmpNumber();

        $this->employeeId = $request->getParameter('employeeId');
        $this->datef = $request->getParameter('dateFrom');
        $this->datet = $request->getParameter('dateTo');
        $this->actionRecorder = $request->getParameter('actionRecorder');
        
        $this->listForm = new DefaultListForm();

        $excludeRoles = array();
        $includeRoles = array();
        $entities = array('Employee' => $this->employeeId);
                
        if ($userEmployeeNumber == $this->employeeId || $this->actionRecorder == "viewMy") {
            $includeRoles = array('ESS');            
        } else if ($this->actionRecorder == "viewEmployee") {
            
        }
        $this->records = $this->getAttendanceService()->getAttendanceRecord($this->employeeId,$this->datef,$this->datet);
        $actions = array(PluginWorkflowStateMachine::ATTENDANCE_ACTION_EDIT_PUNCH_OUT_TIME, PluginWorkflowStateMachine::ATTENDANCE_ACTION_EDIT_PUNCH_IN_TIME);
        $actionableStates = $userRoleManager->getActionableStates(WorkflowStateMachine::FLOW_ATTENDANCE, 
                            $actions, $excludeRoles, $includeRoles, $entities);
        
        $recArray = array();
        if( $this->records == null and strtotime($this->datef)>1){
            $empIds=$this->getEmployeeService()->getEmployeeIdList();
            foreach ($empIds as $empId){
                $pontingDate=strtotime($this->datef);
                while(strtotime("today",$pontingDate)<strtotime("today")){
                    $existente = $this->getAttendanceService()->getAttendanceRecord($empId, date('Y-m-d', $pontingDate), date('Y-m-d', $pontingDate));
                    if($existente==null){
                        $recordsP = $this->getPunchService()->getPunchRecord($empId, date('Y-m-d', $pontingDate));
                        if($recordsP!=null){
                            $fromTime=0;
                            $toTime=0;
                            $stat=WorkflowStateMachine::ATTENDANCE_ACTION_CREATE;
                            foreach ($recordsP as $punche) {
                                if($fromTime==0 || $punche->getPunchUserTime()<$fromTime){
                                    $fromTime=$punche->getPunchUserTime();
                                    $stat=WorkflowStateMachine::ATTENDANCE_ACTION_PUNCH_IN;
                                }else
                                    if($toTime==0 || $punche->getPunchUserTime()>$toTime){
                                        $toTime=$punche->getPunchUserTime();
                                        $stat=WorkflowStateMachine::ATTENDANCE_ACTION_PUNCH_OUT;
                                }
                            }
                            
                            $newAttendanceRecord=new AttendanceRecord();
                            $newAttendanceRecord->setEmployeeId($empId);
                            $newAttendanceRecord->setPunchInUserTime($fromTime);
                            $newAttendanceRecord->setPunchInUtcTime($fromTime);
                            $newAttendanceRecord->setPunchOutUserTime($toTime);
                            $newAttendanceRecord->setPunchOutUtcTime($toTime);
                            $newAttendanceRecord->setPunchInTimeOffset(-6);
                            $newAttendanceRecord->setPunchOutTimeOffset(-6);
                            $newAttendanceRecord->setState($stat);
                            $poutNote="";
                            $pinNote="";
                            $fromTS=strtotime($fromTime);
                            $toTS=strtotime($toTime);
                            if($fromTS>strtotime("9:00:00",$fromTS)){
                                $pinNote="Retardo";
                            }
                            if($toTS<strtotime("18:00:00",$toTS)){
                                $poutNote="Salida temprana ";
                            }
                            $duration=$newAttendanceRecord->getDuration();
                            if($duration<8){
                                $poutNote.="Jornada incompleta";
                            }
                            
                            $newAttendanceRecord->setPunchInNote($pinNote);
                            $newAttendanceRecord->setPunchOutNote($poutNote);
                            
                            $this->getAttendanceService()->savePunchRecord($newAttendanceRecord);
                        }else if($pontingDate!=strtotime("today")){
                            $strPointDate=date('Y-m-d', $pontingDate);
                            $pinNote="Ausente";
                            $newAttendanceRecord=new AttendanceRecord();
                            $newAttendanceRecord->setEmployeeId($empId);
                            $newAttendanceRecord->setPunchInUserTime($strPointDate);
                            $newAttendanceRecord->setPunchInUtcTime($strPointDate);
                            $newAttendanceRecord->setPunchInTimeOffset(-6);
                            $newAttendanceRecord->setState(WorkflowStateMachine::ATTENDANCE_ACTION_CREATE);
                            $newAttendanceRecord->setPunchInNote($pinNote);
                            
                            $this->getAttendanceService()->savePunchRecord($newAttendanceRecord);
                        }
                    }
                    $pontingDate=strtotime("tomorrow",$pontingDate);
                }
                
            }
            $this->records = $this->getAttendanceService()->getAttendanceRecord($this->employeeId,$this->datef,$this->datet);
        }
        if ($this->records != null) {

            if ($actionableStates != null) {

                foreach ($actionableStates as $state) {

                    foreach ($this->records as $record) {

                        if ($state == $record->getState()) {

                            $this->allowedActions['Edit'] = false;
                            break;
                        }
                    }
                }
            }

            $actions = array(PluginWorkflowStateMachine::ATTENDANCE_ACTION_DELETE);
            $actionableStates = $userRoleManager->getActionableStates(WorkflowStateMachine::FLOW_ATTENDANCE, 
                            $actions, $excludeRoles, $includeRoles, $entities);            

            if ($actionableStates != null) {
                foreach ($actionableStates as $state) {

                    foreach ($this->records as $record) {

                        if ($state == $record->getState()) {

                            $this->allowedActions['Delete'] = false;
                            break;
                        }
                    }
                }
            }

            foreach ($this->records as $record) {
                $this->allowedToDelete[] = $userRoleManager->isActionAllowed(WorkflowStateMachine::FLOW_ATTENDANCE, $record->getState(), PluginWorkflowStateMachine::ATTENDANCE_ACTION_DELETE, 
                        $excludeRoles, $includeRoles, $entities);
                $recArray[] = $record;
            }
        } else {
            
        }

        $actions = array(PluginWorkflowStateMachine::ATTENDANCE_ACTION_PROXY_PUNCH_IN, PluginWorkflowStateMachine::ATTENDANCE_ACTION_PROXY_PUNCH_OUT);
        $allowedActionsList = array();

        $actionableStates = $userRoleManager->getActionableStates(WorkflowStateMachine::FLOW_ATTENDANCE, 
                        $actions, $excludeRoles, $includeRoles, $entities);
            
        if ($actionableStates != null) {

            if (!empty($recArray)) {
                $lastRecordPunchOutTime = $recArray[count($this->records) - 1]->getPunchOutUserTime();
                if (empty($lastRecordPunchOutTime)) {
                    $attendanceRecord = "";
                } else {
                    $attendanceRecord = null;
                }
            }

            foreach ($actionableStates as $actionableState) {

                $allowedWorkflowItems = $userRoleManager->getAllowedActions(PluginWorkflowStateMachine::FLOW_ATTENDANCE, 
                    $actionableState, $excludeRoles, $includeRoles, $entities);
                $allowedActionsArray = array_keys($allowedWorkflowItems);
                
                if (!is_null($allowedActionsArray)) {

                    $allowedActionsList = array_unique(array_merge($allowedActionsArray, $allowedActionsList));
                }
            }

            if ((is_null($attendanceRecord)) && (in_array(WorkflowStateMachine::ATTENDANCE_ACTION_PROXY_PUNCH_IN, $allowedActionsList))) {

                $this->allowedActions['PunchIn'] = false;
            }
            if ((!is_null($attendanceRecord)) && (in_array(WorkflowStateMachine::ATTENDANCE_ACTION_PROXY_PUNCH_OUT, $allowedActionsList))) {

                $this->allowedActions['PunchOut'] = false;
            }
        }
    }
    public function executeGetRelatedPunchRecords($request) {
        
        
        $this->allowedToDelete = array();
        $this->allowedActions = array(
            'Delete' => false,
            'Edit' => false,
            'PunchIn' => false,
            'PunchOut' => false,
        );
        
        $this->employeeId = $request->getParameter('employeeId');
        $this->date = $request->getParameter('date');
        $this->actionRecorder = $request->getParameter('actionRecorder');
        
        $this->listForm = new DefaultListForm();
        $this->records = $this->getPunchService()->getPunchRecord($this->employeeId, $this->date);

        

    }
    public function executeDeleteAttendanceRecords($request) {
        $form = new DefaultListForm();
        $form->bind($request->getParameter($form->getName()));
        if ($form->isValid()) {
            $attendanceRecordId = $request->getParameter('id');
            $this->isDeleted = $this->getAttendanceService()->deleteAttendanceRecords($attendanceRecordId);

            $this->getUser()->setFlash('success', __(TopLevelMessages::DELETE_SUCCESS));

        return $this->renderText($this->isDeleted);
        }
    }

    public function executeProxyPunchInPunchOut($request) {
        /* For highlighting corresponding menu item */
        $request->setParameter('initialActionName', 'viewAttendanceRecord');

        $this->punchInTime = null;
        $this->punchInUtcTime = null;
        $this->punchInNote = null;
        $this->action = array();
        $this->action['PunchIn'] = false;
        $this->action['PunchOut'] = false;
        $this->employeeId = $request->getParameter('employeeId');
        $this->date = $request->getParameter('date');
        $this->actionRecorder = $request->getParameter('actionRecorder');
        
        $userRoleManager = $this->getContext()->getUserRoleManager();
        
        $this->attendancePermissions = $this->getDataGroupPermissions('attendance_records');

        $timeZoneOffset = $this->getUser()->getUserTimeZoneOffset();

        $timeStampDiff = $timeZoneOffset * 3600 - date('Z');
        $this->currentDate = date('Y-m-d', time() + $timeStampDiff);

        $this->currentTime = date('H:i', time() + $timeStampDiff);

        $this->timezone = $timeZoneOffset * 3600;

        $actions = array(PluginWorkflowStateMachine::ATTENDANCE_ACTION_PROXY_PUNCH_IN, PluginWorkflowStateMachine::ATTENDANCE_ACTION_PROXY_PUNCH_OUT);
        $actionableStates = $userRoleManager->getActionableStates(WorkflowStateMachine::FLOW_ATTENDANCE, 
                            $actions, array(), array(), array('Employee' => $this->employeeId));

        $attendanceRecord = $this->getAttendanceService()->getLastPunchRecord($this->employeeId, $actionableStates);

        if (is_null($attendanceRecord)) {

            $this->action['PunchIn'] = true;
        } else {

            $this->action['PunchOut'] = true;
        }
        $param = array('timezone' => $timeZoneOffset, 'date' => $this->date);
        $this->form = new ProxyPunchInPunchOutForm(array(), $param, true);

        if ($this->action['PunchIn']) {

            $allowedWorkflowItems = $userRoleManager->getAllowedActions(PluginWorkflowStateMachine::FLOW_ATTENDANCE, 
                    AttendanceRecord::STATE_INITIAL, array(), array(), array('Employee' => $this->employeeId));
            
            $this->allowedActions = array_keys($allowedWorkflowItems);
 
            if (!in_array(PluginWorkflowStateMachine::ATTENDANCE_ACTION_PROXY_PUNCH_IN, $this->allowedActions)) {
                $this->forward(sfConfig::get('sf_secure_module'), sfConfig::get('sf_secure_action'));
            } else {
                if ($request->getParameter('path')) {

                    if ($request->isMethod('post')) {

                        $attendanceRecord = new AttendanceRecord();
                        $attendanceRecord->setEmployeeId($this->employeeId);


                        $this->form->bind($request->getParameter('attendance'));

                        if ($this->form->isValid()) {

                            $punchInDate = $this->form->getValue('date');
                            $punchIntime = $this->form->getValue('time');
                            $punchInNote = $this->form->getValue('note');
                            $timeValue = $this->form->getValue('timezone');
                            $employeeTimezone = $this->getAttendanceService()->getTimezone($timeValue);
                            if ($employeeTimezone == 'GMT') {
                                $employeeTimezone = 0;
                            }
                            $punchInEditModeTime = mktime(date('H', strtotime($punchIntime)), date('i', strtotime($punchIntime)), 0, date('m', strtotime($punchInDate)), date('d', strtotime($punchInDate)), date('Y', strtotime($punchInDate)));

                            $proxyPunchInWorkflowItem = $allowedWorkflowItems[WorkflowStateMachine::ATTENDANCE_ACTION_PROXY_PUNCH_IN];
                            $nextState = $proxyPunchInWorkflowItem->getResultingState();

                            $attendanceRecord->setState($nextState);
                            $attendanceRecord->setPunchInUtcTime(date('Y-m-d H:i', $punchInEditModeTime - $employeeTimezone * 3600));
                            $attendanceRecord->setPunchInNote($punchInNote);
                            $attendanceRecord->setPunchInUserTime(date('Y-m-d H:i', $punchInEditModeTime));
                            $attendanceRecord->setPunchInTimeOffset($employeeTimezone);

                            $this->getAttendanceService()->savePunchRecord($attendanceRecord);

                            $this->redirect("attendance/viewAttendanceRecord?employeeId=" . $this->employeeId . "&date=" . $this->date . "&trigger=" . true . "&actionRecorder=" . $this->actionRecorder);
                        }
                    }
                }
            }
        }

        if ($this->action['PunchOut']) {

            $allowedWorkflowItems = $userRoleManager->getAllowedActions(PluginWorkflowStateMachine::FLOW_ATTENDANCE, 
                    AttendanceRecord::STATE_PUNCHED_IN, array(), array(), array('Employee' => $this->employeeId));
            
            $this->allowedActions = array_keys($allowedWorkflowItems);            

           
            $tempPunchInTime = $attendanceRecord->getPunchInUserTime();
            $this->punchInTime = date('Y-m-d H:i', strtotime($tempPunchInTime));
            $this->punchInUtcTime = date('Y-m-d H:i', strtotime($attendanceRecord->getPunchInUtcTime()));
            $this->punchInNote = $attendanceRecord->getPunchInNote();
            
            if (!in_array(PluginWorkflowStateMachine::ATTENDANCE_ACTION_PROXY_PUNCH_OUT, $this->allowedActions)) {
                $this->forward(sfConfig::get('sf_secure_module'), sfConfig::get('sf_secure_action'));
            } else {
                if ($request->getParameter('path')) {
                    if ($request->isMethod('post')) {
                        $this->form->bind($request->getParameter('attendance'));
                        if ($this->form->isValid()) {

                            $punchOutTime = $this->form->getValue('time');
                            $punchOutNote = $this->form->getValue('note');
                            $punchOutDate = $this->form->getValue('date');
                            $timeValue = $this->form->getValue('timezone');
                            $employeeTimezone = $this->getAttendanceService()->getTimezone($timeValue);
                            if ($employeeTimezone == 'GMT') {
                                $employeeTimezone = 0;
                            }

                            $punchOutEditModeTime = mktime(date('H', strtotime($punchOutTime)), date('i', strtotime($punchOutTime)), 0, date('m', strtotime($punchOutDate)), date('d', strtotime($punchOutDate)), date('Y', strtotime($punchOutDate)));

                            $proxyPunchOutWorkflowItem = $allowedWorkflowItems[WorkflowStateMachine::ATTENDANCE_ACTION_PROXY_PUNCH_OUT];
                            $nextState = $proxyPunchOutWorkflowItem->getResultingState();

                            $attendanceRecord->setState($nextState);
                            $attendanceRecord->setPunchOutUtcTime(date('Y-m-d H:i', $punchOutEditModeTime - $employeeTimezone * 3600));
                            $attendanceRecord->setPunchOutNote($punchOutNote);
                            $attendanceRecord->setPunchOutUserTime(date('Y-m-d H:i', $punchOutEditModeTime));
                            $attendanceRecord->setPunchOutTimeOffset($employeeTimezone);
                            $this->getAttendanceService()->savePunchRecord($attendanceRecord);
                            $this->getUser()->setFlash('templateMessage', array('success', __(TopLevelMessages::SAVE_SUCCESS)));
                            $this->redirect("attendance/viewAttendanceRecord?employeeId=" . $this->employeeId . "&date=" . $this->date . "&trigger=" . true);
                        }
                    }
                }
            }
        }
    }

    public function executeUpdatePunchInOutNote($request) {
        $comment = $request->getParameter('comment');
        $id = $request->getParameter('id');
        $punchInOut = $request->getParameter('punchInOut');

        $attendanceRecord = $this->getAttendanceService()->getAttendanceRecordById($id);

        if ($punchInOut == 3) {

            $attendanceRecord->setPunchInNote($comment);
            $this->getAttendanceService()->savePunchRecord($attendanceRecord);
        }

        if ($punchInOut == 4) {

            $attendanceRecord->setPunchOutNote($comment);
            $this->getAttendanceService()->savePunchRecord($attendanceRecord);
        }
        return sfView::NONE;
    }

    public function executeGetDaylightSavingTimeZone($request) {
        
    }

    public function getDataGroupPermissions($dataGroups) {
        return $this->getContext()->getUserRoleManager()->getDataGroupPermissions($dataGroups, array(), array(), false, array());
    }

}

