
<?php echo javascript_include_tag(plugin_web_path('orangehrmAttendancePlugin', 'js/viewMyAttendanceRecordSuccess')); ?>

<div class="box">
    <div class="head">
        <h1><?php echo __('My Attendance Records'); ?></h1>
    </div>
    <div class="inner">
        <div id="validationMsg">
            <?php echo isset($messageData) ? templateMessage($messageData) : ''; ?>
        </div>
        <form action="<?php echo url_for("attendance/viewAttendanceRecord"); ?>" id="reportForm" method="post">
            <fieldset>
                <ol class="normal">
                    <li>
                        <?php echo $form['dateFrom']->renderLabel(__('From')); ?>
                        <?php echo $form['dateFrom']->render(); ?>
                    </li>
                    <li>
                        <?php echo $form['dateTo']->renderLabel(__('To')); ?>
                        <?php echo $form['dateTo']->render(); ?>
                        <?php echo $form->renderHiddenFields(); ?>
                    </li>
                </ol>
                    <input type="button" class="" id="btView" value="<?php echo __('View') ?>" />
            </fieldset>
        </form>
    </div>
</div>

<div id="recordsTable1"><!-- To appear table when search success --></div>

<script type="text/javascript">
    var datepickerDateFormat = '<?php echo get_datepicker_date_format($sf_user->getDateFormat()); ?>';
    var displayDateFormat = '<?php echo str_replace('yy', 'yyyy', get_datepicker_date_format($sf_user->getDateFormat())); ?>';
    var errorForInvalidFormat='<?php echo __js(ValidationMessages::DATE_FORMAT_INVALID,
            array('%format%' => str_replace('yy', 'yyyy', get_datepicker_date_format($sf_user->getDateFormat())))) ?>';
    var errorMsge;
    var linkForGetRecords='<?php echo url_for('attendance/getRelatedAttendanceRecords'); ?>';
    var employeeId='<?php echo $employeeId; ?>';
    var actionRecorder='<?php echo $actionRecorder; ?>';
    var datefSelected='<?php echo $datef; ?>';
    var datetSelected='<?php echo $datet; ?>';
    var trigger='<?php echo $trigger; ?>';
    var lang_dateError = '<?php echo __js("To date should be after from date") ?>';
</script>
