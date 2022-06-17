<?php
$imagePath = theme_path("images/login");
echo stylesheet_tag(theme_path('css/login.css'));
$loginImage = sfConfig::get('sf_web_dir') . DIRECTORY_SEPARATOR . sfConfig::get('ohrm_resource_dir')
    . DIRECTORY_SEPARATOR . '/themes/default/images/login/login.svg';

    {//carga de datos de lector a base
        $conexion = json_decode( file_get_contents('http://localhost:8081/Dispositivo/Conectar?cadenaC=192.168.0.201'), true );
        $url='http://localhost:8081/Dispositivo/GetRegistros?token='.$conexion['Resultado'];
        $registros = json_decode( file_get_contents($url), true );
        if($registros['Codigo']==1 ){
            $dbconnect=mysqli_connect('127.0.0.1', 'root', 'entrarleya','koinobor_webe578');
            $lastDate=0;
            foreach ($registros['Resultado'] as $value) {
                $SQLSel='SELECT id FROM ohrm_punch_record where punch_user_time="'.$value['VDate'].'"';
                $res=mysqli_query($dbconnect, $SQLSel );
                $lastDate=$value['VDate'];
                if(mysqli_num_rows($res)){
                    continue;
                }
                $SQLInsert="INSERT INTO ohrm_punch_record 
                (employee_id, punch_utc_time, punch_time_offset, punch_user_time,
                                  punch_device
                ) VALUES 
                ( '". $value['Id']."', '".$value['VDate']."', '-6', '".$value['VDate']."', '1')";
                mysqli_query($dbconnect, $SQLInsert);
                
                $lastDate=$value['VDate'];
    
    
            }
            $url='http://localhost:8081/Dispositivo/DeleteRegistros';
            $json='{
                "Token":"'.$conexion['Resultado'].'",
                "PeriodTo":"'.$lastDate.'"
            }';
            $head[]="Content-Type: application/json";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_HTTPHEADER , $head);
            curl_exec($ch);
            curl_close($ch);
        }
    }
?>

<div>
    <input type="text" class="loginSuccessMessage" id="loginSuccessMessage" value="" readonly="readonly"/>
</div>

<div id="divLogin">
    <div id="divLoginImageContainer">
        <div id="divLoginImage"><?php echo
            file_get_contents($loginImage);?>
        </div>
        <div id="divLoginForm">
            <form id="frmLogin" method="post" action="<?php echo url_for('auth/validateCredentials'); ?>">
                <input type="hidden" name="actionID"/>
                <input type="hidden" name="hdnUserTimeZoneOffset" id="hdnUserTimeZoneOffset" value="0" />
                <?php
                    echo $form->renderHiddenFields(); // rendering csrf_token
                ?>
                <div id="logInPanelHeading"><?php echo __('LOGIN Panel'); ?></div>

                <div id="divUsername" class="textInputContainer">
                    <?php echo $form['Username']->render(); ?>
                  <span class="form-hint" ><?php echo __('Username'); ?></span>
                </div>
                <div id="divPassword" class="textInputContainer">
                    <?php echo $form['Password']->render(); ?>
                 <span class="form-hint" ><?php echo __('Password'); ?></span>
                </div>
                <div id="divLoginHelpLink"><?php
                    include_component('core', 'ohrmPluginPannel', array(
                        'location' => 'login-page-help-link',
                    ));
                    ?></div>
                <div id="divLoginButton">
                    <input type="submit" name="Submit" class="button" id="btnLogin" value="<?php echo __('LOGIN'); ?>" />
                    <?php if (!empty($message)) : ?>
                    <span id="spanMessage"><?php echo __($message); ?></span>
                    <?php endif; ?>
                    <div id="forgotPasswordLink">
                        <a href="<?php echo url_for('auth/requestPasswordResetCode'); ?>"><?php echo __('Forgot your password?'); ?></a>
                    </div>
                </div>

            </form>
        </div>
    </div>

</div>

<div style="text-align: center">
    <?php include_component('core', 'ohrmPluginPannel', array(
                'location' => 'other-login-mechanisms',
            )); ?>
</div>

<?php include_partial('global/footer_copyright_social_links'); ?>

<script type="text/javascript">
    
    function calculateUserTimeZoneOffset() {
        var myDate = new Date();
        var offset = (-1) * myDate.getTimezoneOffset() / 60;
        return offset;
    }
            
    function addHint(inputObject, hintImageURL) {
        if (inputObject.val() == '') {
            inputObject.css('background', "url('" + hintImageURL + "') no-repeat 10px 3px");
        }
    }
            
    function removeHint() {
       $('.form-hint').css('display', 'none');
    }
    
    function showMessage(message) {
        if ($('#spanMessage').length == 0) {
            $('<span id="spanMessage"></span>').insertAfter('#btnLogin');
        }

        $('#spanMessage').html(message);
    }
    
    function validateLogin() {
        var isEmptyPasswordAllowed = false;
        
        if ($('#txtUsername').val() == '') {
            showMessage('<?php echo __js('Username cannot be empty'); ?>');
            return false;
        }
        
        if (!isEmptyPasswordAllowed) {
            if ($('#txtPassword').val() == '') {
                showMessage('<?php echo __js('Password cannot be empty'); ?>');
                return false;
            }
        }
        
        return true;
    }
    
    function refreshSession() {
        setTimeout(function() {
            location.reload();
        }, 20 * 60 * 1000);
    }

    $(document).ready(function() {
        if ($('#installation').val())  {
            var login = $('#installation').val();

            $("#loginSuccessMessage").attr("value", login);
        }

        refreshSession();

        /*Set a delay to compatible with chrome browser*/
        setTimeout(checkSavedUsernames,100);
        
        $('#txtUsername').focus(function() {
            removeHint();
        });
        
        $('#txtPassword').focus(function() {
             removeHint();
        });
        
        $('.form-hint').click(function(){
            removeHint();
            $('#txtUsername').focus();
        });
        
        $('#hdnUserTimeZoneOffset').val(calculateUserTimeZoneOffset().toString());
        
        $('#frmLogin').submit(validateLogin);
        
    });

    function checkSavedUsernames(){
        if ($('#txtUsername').val() != '') {
            removeHint();
        }
    }

    if (window.top.location.href != location.href) {
        window.top.location.href = location.href;
    }
</script>
