<?php echo javascript_include_tag(plugin_web_path('orangehrmAttendancePlugin', 'js/getMyRelatedAttendanceRecordsSuccess')); ?>

    
<div class="box miniList noHeader" id="recordsTable">
    <div class="inner">
        <?php include_partial('global/flash_messages'); ?>
        <?php echo isset($listForm) ? $listForm->render() : ''; ?>
        <form action="" id="employeeRecordsForm" method="post">
            <div class="top">
                <?php if ($allowedActions['Edit']) : ?>
                    <input type="button" class="edit" id="btnEdit" value="<?php echo __('Edit'); ?>" />
                <?php endif; ?>
                <?php if ($allowedActions['Delete']) : ?>
                    <input type="button" class="delete" id="btnDelete" value="<?php echo __('Delete'); ?>" />
                <?php endif; ?>
                <?php if ($allowedActions['PunchIn']) : ?>
                    <input type="button" class="punch" id="btnPunchIn" value="<?php echo __('Add Attendance Records'); ?>" />
                <?php endif; ?>
                <?php if ($allowedActions['PunchOut']) : ?>
                    <input type="button" class="punch" id="btnPunchOut" value="<?php echo __('Add Attendance Records'); ?>" />
                <?php endif; ?>
            </div>
            <table class="table">
                <thead id="tableHead" >
                    <tr>
                        <th style="width: 2%;" id="checkBox"></th>
                        <th style="width: 15%;"><?php echo __("Punch date in"); ?></th>
                        <th style="width: 10%;"><?php echo __("Punch In"); ?></th>
                        <th style="width: 20%;"><?php echo __("Punch In Note"); ?></th>
                        <th style="width: 15%;"><?php echo __("Punch date Out"); ?></th>
                        <th style="width: 10%;"><?php echo __("Punch Out"); ?></th>
                        <th style="width: 20%;"><?php echo __("Punch Out Note"); ?></th>
                        <th style="width: 8%;"><?php echo __("Duration")."(".__("Hours").")"; ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $class = 'odd'; ?>
                    <?php $i = 0; ?>
                    <?php $total = 0; ?>
                    <?php if ($records == null): ?>  
                        <tr>
                            <td id="noRecordsColumn" colspan="6">
                                <?php echo __("No attendance records to display") ?>
                            </td>
                        </tr> 
                    <?php else: ?>                
                        <?php foreach ($records as $record): ?>
                            <tr class="<?php echo $class; ?>">
                                <?php $class = $class == 'odd' ? 'even' : 'odd'; ?>
                                <?php $inUserTimeArray = explode(" ", $record->getPunchInUserTime()) ?>
                                <td id="checkBox">
                                    <?php if ($allowedToDelete[$i]): ?>
                                    <input type="checkbox" id="<?php echo $record->getId() ?>" class="toDelete" value="" >
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php echo date($sf_user->getDateFormat(),strtotime($record->getPunchInUserTime())) ?>
                                </td>
                                <td>
                                    <?php echo date('H:i:s',strtotime($record->getPunchInUserTime())) ?>
                                </td>
                                <td>
                                    <?php echo $record->getPunchInNote() ?>
                                </td>
                                <?php if ($record->getPunchOutUserTime() == null): ?>
                                <td></td>
                                <td></td>
                                <td></td>
                                <?php elseif (date('Y-m-d', strtotime($record->getPunchOutUserTime())) != $date): ?>
                                    <td>
                                        <?php echo date($sf_user->getDateFormat(),strtotime($record->getPunchOutUserTime())) ?>
                                    </td>
                                    <td>
                                        <span><?php echo date('H:i:s',strtotime($record->getPunchOutUserTime())) ?></span>
                                    </td>
                                    <td>
                                        <?php echo $record->getPunchOutNote() ?>
                                    </td>
                                <?php else: ?>
                                    <?php $outUserTimeArray = explode(" ", $record->getPunchOutUserTime()) ?>
                                    <td>
                                    	<?php echo date('H:i:s',strtotime($record->getPunchOutUserTime())) ?>
                                    </td>
                                    <td>
                                        <?php echo $record->getPunchOutNote() ?>
                                    </td>
                                <?php endif; ?>
                                    <td>
                                        <?php echo $record->getDuration(); ?>
                                    </td>
                                    <?php $total = $total + $record->getDuration() ?>
                            </tr>
                            <?php $i++; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <?php if ($records != null): ?>
                            <tr class="total">
                                <td></td>
                                <td id="totalVerticalValue"><?php echo __("Total"); ?></td>
                                <td colspan="5">
                                <td id="totalVerticalValue"><?php echo $total; ?></td>
                            </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </form>
    </div>
</div>

<!-- Delete-confirmation -->
<div class="modal hide" id="dialogBox">
    <div class="modal-header">
        <a class="close" data-dismiss="modal">×</a>
        <h3><?php echo __('OrangeHRM - Confirmation Required'); ?></h3>
            </div>
    <div class="modal-body">
        <p><?php echo __(CommonMessages::DELETE_CONFIRMATION); ?></p>
            </div>
    <div class="modal-footer">
        <input type="button" class="btn" id="dialogOk" data-dismiss="modal" value="<?php echo __('Ok'); ?>" />
        <input type="button" class="btn reset" data-dismiss="modal" value="<?php echo __('Cancel'); ?>" />
        </div>
</div>
<!-- Confirmation box HTML: Ends -->

<script type="text/javascript">
    var employeeId='<?php echo $employeeId; ?>';
    var date='<?php echo $date; ?>';
    var linkToEdit='<?php echo url_for('attendance/editAttendanceRecord'); ?>'
    var linkToDeleteRecords='<?php echo url_for('attendance/deleteAttendanceRecords'); ?>'
    var linkForGetRecords='<?php echo url_for('attendance/getRelatedAttendanceRecords'); ?>'
    var actionRecorder='<?php echo $actionRecorder; ?>';
    var lang_noRowsSelected='<?php echo __js(TopLevelMessages::SELECT_RECORDS); ?>';
</script>
