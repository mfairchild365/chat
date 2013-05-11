<form action="<?php echo $context->getEditURL()?>" method="post" class="form-horizontal">
    <div class="control-group">
        <label class="control-label" for="login-username">Username Or Email</label>
        <div class="controls">
            <input type="text" id="login-username" name="username" placeholder="User Name">
        </div>
    </div>
    <div class="control-group">
        <label class="control-label" for="login-password">Password</label>
        <div class="controls">
            <input type="password" id="login-password" name="password" required placeholder="Password">
        </div>
    </div>
    <div class="control-group">
        <div class="controls">
            <button type="submit" class="btn">Login</button>
        </div>
    </div>
</form>