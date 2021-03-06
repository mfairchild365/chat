<script language='javascript' type='text/javascript'>
    function verifyPasswordCheck(input) {
        if (input.value != document.getElementById('input-password').value) {
            input.setCustomValidity('The two passwords must match.');
        } else {
            // input is valid -- reset the error message
            input.setCustomValidity('');
        }
    }
</script>
<form action="<?php echo $context->getEditURL()?>" method="post" class="form-horizontal">
    <div class="control-group">
        <label class="control-label" for="input-firstname">First Name</label>
        <div class="controls">
            <input type="text" id="input-firstname" name="first_name" placeholder="First Name">
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="input-lastname">Last Name</label>
        <div class="controls">
            <input type="text" id="input-lastname" name="last_name" placeholder="Last Name">
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="input-username">Username</label>
        <div class="controls">
            <input type="text" id="input-username" name="username" placeholder="User Name">
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="input-email">Email</label>
        <div class="controls">
            <input type="email" id="input-email" name="email" required placeholder="Email">
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="input-password">Password</label>
        <div class="controls">
            <input type="password" id="input-password" name="password" required placeholder="Password">
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="input-password-verify">Verify Password</label>
        <div class="controls">
            <input type="password" id="input-password-verify" name="password_verify" required placeholder="Password" oninput="verifyPasswordCheck(this)">
        </div>
    </div>
    <?php
    if (\Chat\Setting\Service::getSettingValue('SITE_PASSWORD')) {
        ?>
        <div class="control-group">
            <label class="control-label" for="input-site-password">Site Password</label>
            <div class="controls">
                <input type="password" id="input-site-password" name="site_password" required placeholder="Site Password">
            </div>
        </div>
        <?php
    }
    ?>
    <div class="control-group">
        <div class="controls">
            <button type="submit" class="btn">Register</button>
        </div>
    </div>
</form>