
<?php echo javascript_include_tag(plugin_web_path('orangehrmAttendancePlugin', 'js/viewPunchRecordSuccess')); ?>
<?php echo javascript_include_tag(plugin_web_path('orangehrmAttendancePlugin', 'js/getRelatedPunchRecordsSuccess')); ?>

<?php if($attendancePermissions->canRead()){?>
<div class="box">

    <div class="head">
        <h1><?php echo __('View Punch Record'); ?></h1>
    </div>
    
    <div class="inner">
        
        <div id="validationMsg">
            <?php echo isset($messageData[0]) ? displayMainMessage($messageData[0], $messageData[1]) : ''; ?>
        </div>
             
        <form action="<?php echo url_for("attendance/viewPunchRecord"); ?>" id="reportForm" method="post" name="frmPunchReport">
            <fieldset>
                <ol>
                    <?php
                    if ($form->hasErrors()) {
                        echo $form['employeeName']->renderError();
                    }
                    ?>
                    <?php echo $form->render(); ?>

                    <li class="required">
                        <em>*</em> <?php echo __(CommonMessages::REQUIRED_FIELD); ?>
                    </li>
                </ol>
                <p class="formbuttons">
                    <input type="button" class="" id="btView" value="<?php echo __('View') ?>" />
                    <input type="hidden" name="pageNo" id="pageNo" value="" />
                    <input type="hidden" name="hdnAction" id="hdnAction" value="search" />
                </p>
            </fieldset> 
        </form>
    </div>
</div>

<div id="recordsTable">
    <div id="msg" ><?php echo isset($messageData[0]) ? displayMainMessage($messageData[0], $messageData[1]) : ''; ?></div>
    <?php include_component('core', 'ohrmList', $parmetersForListCompoment); ?>
</div>

<div id="punchInOut">

</div>

<!-- Confirmation box HTML: Begins -->
<div class="modal hide" id="dialogBox">
    <div class="modal-header">
        <a class="close" data-dismiss="modal">×</a>
        <h3><?php echo __('OrangeHRM - Confirmation Required'); ?></h3>
    </div>
    <div class="modal-body">
        <p><?php echo __(CommonMessages::DELETE_CONFIRMATION); ?></p>
    </div>
    <div class="modal-footer">
        <input type="button" class="btn" data-dismiss="modal" id="okBtn" value="<?php echo __('Ok'); ?>" />
        <input type="button" class="btn reset" data-dismiss="modal" value="<?php echo __('Cancel'); ?>" />
    </div>
</div>
<!-- Confirmation box HTML: Ends -->
<?php }?>

<script type="text/javascript">
    
    var datepickerDateFormat = '<?php echo get_datepicker_date_format($sf_user->getDateFormat()); ?>';
    var displayDateFormat = '<?php echo str_replace('yy', 'yyyy', get_datepicker_date_format($sf_user->getDateFormat())); ?>';
    var errorForInvalidFormat='<?php echo __js(ValidationMessages::DATE_FORMAT_INVALID, array('%format%' => str_replace('yy', 'yyyy', get_datepicker_date_format($sf_user->getDateFormat())))) ?>';
    var errorMsge;
    var linkForGetRecords='<?php echo url_for('attendance/getRelatedPunchRecords'); ?>'
    var linkForProxyPunchInOut='<?php echo url_for('attendance/proxyPunchInPunchOut'); ?>'
    var trigger='<?php echo $trigger; ?>';
    var employeeAll='<?php echo __js('All'); ?>';
    var employeeId='<?php echo $employeeId; ?>';
    var dateSelected='<?php echo $date; ?>';
    var actionRecorder='<?php echo $actionRecorder; ?>';
    var employeeSelect = '<?php echo __js('Select an Employee') ?>';
    var invalidEmpName = '<?php echo __js('Invalid Employee Name') ?>';
    var noEmployees = '<?php echo __js('No Employees Available') ?>';
    var typeForHints = '<?php echo __js("Type for hints") . '...'; ?>';
    var date='<?php echo $date; ?>';
    var linkToEdit='<?php echo url_for('attendance/editPunchRecord'); ?>'
    var linkToDeleteRecords='<?php echo url_for('attendance/deletePunchRecords'); ?>'
    var lang_noRowsSelected='<?php echo __js(TopLevelMessages::SELECT_RECORDS); ?>';
    var closeText = '<?php echo __js('Close');?>';
    var lang_NameRequired = '<?php echo __js(ValidationMessages::REQUIRED); ?>';

    function submitPage(pageNo) {
        document.frmPunchReport.pageNo.value = pageNo;
        document.frmPunchReport.hdnAction.value = 'paging';
        document.getElementById('reportForm').submit();
    }
</script>
