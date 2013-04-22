<h1><phrase id="CHANGE_PASSWORD" /></h1>
<form action="<?=FrontController::GetLink()?>" method="post" class="yform columnar validate">
    <div class="type-text">
        <label for="old_password">
            <phrase id="OLDPASSWORD"/>:
        </label><input type="password" class="required" id="old_password" name="password"/>
    </div>
    <div class="type-text">
        <label for="new_password">
            <phrase id="NEWPASSWORD"/>:
        </label><input type="password" id="new_password" name="new_password"/>
    </div>
    <div class="type-text">
        <label for="new_password_confirm">
            <phrase id="CONFIRMNEWPASSWORD"/>:
        </label><input type="password" id="new_password_confirm" name="new_password_confirm"/>
    </div>
    <div class="type-button">
        <label>
            &nbsp;
        </label>
        <button>
            <span><phrase id="CHANGE"/></span>
        </button>
    </div>
</form>