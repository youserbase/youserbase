<form action="<?=FrontController::GetOriginalLink()?>" method="post" id="login" class="yet2validate">
    <div class="kernel">
        <div id="logo" class="closed_beta">
        </div>
        <label for="nickname">
            <phrase id="NICKNAME"/>
        </label><input type="text" name="nickname" id="nickname" class="required" value="<?=empty($_POST['nickname'])?'':$_POST['nickname']?>"/>
        <br/>
        <label for="password">
            <phrase id="PASSWORD"/>
        </label><input type="password" name="password" id="password" class="required"/>
        <br/>
        <label for="autologin">
            <phrase id="AUTOLOGIN"/>
        </label><input type="checkbox" name="autologin" id="autologin" value="1"/>
        <br/>
        <p>
            <button type="submit">
                <span><phrase id="LOGIN"/></span>
            </button>
        </p>
		<input type="hidden" name="return_to" value="<?=FrontController::GetOriginalLink()?>"/>
   </div>
</form>
<div id="join_youserbase">
<?php if (!Session::Get('beta_aspired')): ?>
	<p id="join_header">
		<a onclick="$('#sigin_w').toggle(); return false;" href="#"><phrase id="BETA_ASPIRE"/></a>
	</p>
	<div id="sigin_w">
		<form action="<?=FrontController::GetLink('Aspire')?>" method="post" id="sigin" class="validate">
			<p id="join_email">
				<label>
					<phrase id="BETA_ASPIRE_REASON"/>
				</label>
				<input type="text" name="email" class="email"/>
			</p>
			<p id="join_send">
				<button type="submit" class="send">
					<span><phrase id="SEND"/></span>
				</button>
				<input type="hidden" name="return_to" value="<?=FrontController::GetOriginalLink()?>"/>
			</p>
		</form>
	</div>
<?php else: ?>
    <p id="join_header" class="aspired">
    	<phrase id="BETA_ASPIRE"/>!
    </p>
<?php endif; ?>
</div>