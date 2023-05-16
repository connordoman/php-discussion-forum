<form id="change_password_form">
    <span class="textbox">
        <input type="password" name="old_password" placeholder="Old Password" required/>
    </span>
    <span class="textbox">
        <input type="password" name="password" placeholder="New Password" required/>
    </span>
    <span class="textbox">
        <input type="password" name="password_confirm" placeholder="Confirm Password" required/>
    </span>
    <input type="submit" name="change_passowrd" value="Change Password"/>
</form>
<br/>
<span class="error"></span>
<span class="success"></span>
<script type="text/javascript">
prepareForm('change_password_form', '../api/change_password.php', null, 'Password changed successfully', 'Failed to change password');
</script>
