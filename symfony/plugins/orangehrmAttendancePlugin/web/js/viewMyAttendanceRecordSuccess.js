/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(function()
{
    var rDateF = trim($("#attendance_from").val());
    var rDateT = trim($("#attendance_to").val());
    if (rDateF == '') {
        $("#attendance_from").val(displayDateFormat);
    }
    if (rDateT == '') {
        $("#attendance_to").val(displayDateFormat);
    }
    
    if(trigger){
        $("#recordsTable").hide();
     
        getRelatedAttendanceRecords(employeeId,datefSelected,datetSelected,actionRecorder);
    
        $('#attendance_from').change(function() {
            var isValidDate= validateInputDate();
                
            if(isValidDate){
                   
                var datef=$("#attendance_from").val();
                var datet=$("#attendance_to").val();
                var parsedDatef = $.datepicker.parseDate(datepickerDateFormat, datef);
                var parsedDatet = $.datepicker.parseDate(datepickerDateFormat, datet);
                getRelatedAttendanceRecords(employeeId,$.datepicker.formatDate("yy-mm-dd", parsedDatef)
                                                      ,$.datepicker.formatDate("yy-mm-dd", parsedDatet),actionRecorder);
            }  

        });
        
        $('#attendance_to').change(function() {
            var isValidDate= validateInputDate();
                
            if(isValidDate){
                var datef=$("#attendance_from").val();
                var datet=$("#attendance_to").val();
                var parsedDatef = $.datepicker.parseDate(datepickerDateFormat, datef);
                var parsedDatet = $.datepicker.parseDate(datepickerDateFormat, datet);
                if(parsedDatef.getTime()>0){}
                
                getRelatedAttendanceRecords(employeeId,$.datepicker.formatDate("yy-mm-dd", parsedDatef)
                                                      ,$.datepicker.formatDate("yy-mm-dd", parsedDatet),actionRecorder);
            }  

        });
    }
    
    else{
        
        $("#recordsTable").hide();
     
        var rDatef = trim($("#attendance_from").val());
        if (rDatef == '') {
            $("#attendance_from").val(datepickerDateFormat);
        }

        var rDatet = trim($("#attendance_to").val());
        if (rDatet == '') {
            $("#attendance_to").val(datepickerDateFormat);
        }

        $('#attendance_to').change(function() {
    
            var isValidDate= validateInputDate();
                
            if(isValidDate){
                   
                var datef=$("#attendance_from").val();
                var datet=$("#attendance_to").val();
               var parsedDatef = $.datepicker.parseDate(datepickerDateFormat, datef);
               var parsedDatet = $.datepicker.parseDate(datepickerDateFormat, datet);
                
                getRelatedAttendanceRecords(employeeId,$.datepicker.formatDate("yy-mm-dd", parsedDatef)
                                                      ,$.datepicker.formatDate("yy-mm-dd", parsedDatet),actionRecorder);
                    
            }  

        });       
        
    }
    
});
function validateInputDate(){
  
    errFlag = false;
    $(".messageBalloon_success").remove();
    $('#validationMsg').removeAttr('class');
    $('#validationMsg').html("");
    $("#attendance_from").removeAttr('style');
    $("#attendance_to").removeAttr('style');

    var errorStyle = "background-color:#FFDFDF;";
        
    var datef=$("#attendance_from").val();
    var datet=$("#attendance_to").val();
    
    var fromDate=($.datepicker.parseDate(datepickerDateFormat, datef)).getTime();
    var toDate=($.datepicker.parseDate(datepickerDateFormat, datet)).getTime();
	if(fromDate>toDate){
        $('#validationMsg').attr('class', "message error");
        $('#validationMsg').html(lang_dateError);
        $("#attendance_from").attr('style', errorStyle);
        $("#attendance_to").attr('style', errorStyle);
        errFlag = true;
	}        
    if(!validateDate(datef, datepickerDateFormat)){
        $('#validationMsg').attr('class', "message warning");
        $('#validationMsg').html(errorForInvalidFormat);
        $("#attendance_from").attr('style', errorStyle);
        errFlag = true;
    }   
    if(!validateDate(datet, datepickerDateFormat)){
        $('#validationMsg').attr('class', "message warning");
        $('#validationMsg').html(errorForInvalidFormat);
        $("#attendance_to").attr('style', errorStyle);
        errFlag = true;
    }  
    return !errFlag ;
    
}

function getRelatedAttendanceRecords(employeeId,datef,datet,actionRecorder){
    $.get(
        linkForGetRecords,
        {
            employeeId: employeeId,
            dateFrom: datef,
            dateTo: datet,
            actionRecorder:actionRecorder
        },
        
        function(data, textStatus) {
                      
            if( data != ''){
                $("#recordsTable").show();
                $('#recordsTable1').html(data);    
            }
                    
        });
                    
    return false;
        
}

String.prototype.isValidDate = function() {
    var IsoDateRe = new RegExp("^([0-9]{4})-([0-9]{2})-([0-9]{2})$");
    var matches = IsoDateRe.exec(this);
    if (!matches) return false;
  

    var composedDate = new Date(matches[1], (matches[2] - 1), matches[3]);

    return ((composedDate.getMonth() == (matches[2] - 1)) &&
        (composedDate.getDate() == matches[3]) &&
        (composedDate.getFullYear() == matches[1]));

}