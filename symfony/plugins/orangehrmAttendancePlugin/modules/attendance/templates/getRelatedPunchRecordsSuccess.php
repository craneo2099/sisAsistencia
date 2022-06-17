<?php echo javascript_include_tag(plugin_web_path('orangehrmAttendancePlugin', 'js/getMyRelatedPunchRecordsSuccess')); ?>

    
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
                    <input type="button" class="punch" id="btnPunchIn" value="<?php echo __('Add punch Records'); ?>" />
                <?php endif; ?>
                <?php if ($allowedActions['PunchOut']) : ?>
                    <input type="button" class="punch" id="btnPunchOut" value="<?php echo __('Add punch Records'); ?>" />
                <?php endif; ?>
            </div>
            <table class="table">
                <thead id="tableHead" >
                    <tr>
                        <th style="width: 2%;" id="checkBox"></th>
                        <th style="width: 40%;"><?php echo __("Punch"); ?></th>
                        <th style="width: 50%;"><?php echo __("Punch Device"); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $class = 'odd'; ?>
                    <?php $i = 0; ?>
                    <?php $total = 0; ?>
                    <?php if ($records == null): ?>  
                        <tr>
                            <td id="noRecordsColumn" colspan="6">
                                <?php echo __("No punch records to display") ?>
                            </td>
                        </tr> 
                    <?php else: ?>                
                        <?php foreach ($records as $record): ?>
                            <tr class="<?php echo $class; ?>">
                                <?php $class = $class == 'odd' ? 'even' : 'odd'; ?>
                                <?php $inUserTimeArray = explode(" ", $record->getPunchUserTime()) ?>
                                <td id="checkBox">
                                    <?php if ($allowedToDelete[$i]): ?>
                                    <input type="checkbox" id="<?php echo $record->getId() ?>" class="toDelete" value="" >
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php echo set_datepicker_date_format($inUserTimeArray[0]) . " " . $inUserTimeArray[1] ?>
                                </td>
                                <td>
                                    <?php echo $record->getPunchDevice() ?>
                                </td>
                            </tr>
                            <?php $i++; ?>
                        <?php endforeach; ?>
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
    var linkToEdit='<?php echo url_for('attendance/editPunchRecord'); ?>'
    var linkToDeleteRecords='<?php echo url_for('attendance/deletePunchRecords'); ?>'
    var linkForGetRecords='<?php echo url_for('attendance/getRelatedPunchRecords'); ?>'
    var actionRecorder='<?php echo $actionRecorder; ?>';
    var lang_noRowsSelected='<?php echo __js(TopLevelMessages::SELECT_RECORDS); ?>';
</script>
