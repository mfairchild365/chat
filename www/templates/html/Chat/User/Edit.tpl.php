<h2><?php echo $context->username; ?></h2>

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
            <input type="text" id="input-firstname" name="first_name" placeholder="First Name" value="<?php echo $context->first_name?>" />
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="input-lastname">Last Name</label>
        <div class="controls">
            <input type="text" id="input-lastname" name="last_name" placeholder="Last Name" value="<?php echo $context->first_name?>" />
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="input-email">Email</label>
        <div class="controls">
            <input type="email" id="input-email" name="email" required placeholder="Email" value="<?php echo $context->email?>" />
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="input-password">Password</label>
        <div class="controls">
            <input type="password" id="input-password" name="password" placeholder="Leave blank to keep current" />
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="input-password-verify">Verify Password</label>
        <div class="controls">
            <input type="password" id="input-password-verify" name="password_verify" placeholder="Leave blank to keep current" oninput="verifyPasswordCheck(this)" />
        </div>
    </div>
    <div class="control-group">
        <div class="controls">
            <button type="submit" class="btn">Update</button>
        </div>
    </div>
</form>