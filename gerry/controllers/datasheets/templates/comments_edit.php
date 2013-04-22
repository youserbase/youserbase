<form class="ajax new_comment yform stored:device_id:<?=$device_id?> stored:page:<?=$page?> validate" id="comment_form" method="POST" action="<?=FrontController::GetLink('Comments','Save', array('device_id' => $device_id, 'comments_id' => $comments_id, 'return_to' => $return_to, 'page' => $page))?>" style="width:420px;">
    <h3>
        <?php if (isset($comment)): ?>
        <phrase id="EDIT_COMMENT"/><?php else : ?>
        <phrase id="WRITE_NEW_COMMENT"/><?php endif; ?>
    </h3>
	<!--<input type="hidden" name="category" id="comment_category" value="$comment['category']">-->
	<input type="hidden" name="compare" value="<?=$compare?>">
	<?php if(!Youser::Id()):?>
		<div class="comment_id" style="width:380px;">
			<div class="fleft">
				<label for="email"><phrase id="EMAIL" quiet="true"/></label>
				<input type="text" name="email" class="required email">
			</div>
			<div class="fright">
				<label for="name"><phrase id="NAME" quiet="true"/></label>
				<input type="text" name="name" class="required name">
			</div>
			<div class="clr"></div>
			<div class="comment_url" id="comment_url">
				<label for="url"><phrase id="WEBSITE" quiet="true"/></label>
				<input type="text" name="url" class="url">
			</div>
		</div>
	<?php endif?>
    <div class="type-text">
        <label for="your-id">
            &nbsp;
        </label>
        <textarea name="comment" style="min-height: 125px;width:380px;" class="required" id="comment_comment"><?= isset($comment) ? strip_tags($comment) : ''?></textarea>
        
    </div>
    <div class="infodiv">
    </div>
    <?php if (Youser::Is('root', 'god')):?>
	<script type="text/javascript" src="http://www.google.com/recaptcha/api/challenge?k=6LeVv7wSAAAAADBsibIoZVJT87kdGLZaAQ9ibzHc"></script>
	<noscript>
		<iframe src="http://www.google.com/recaptcha/api/noscript?k=your_public_key" height="300" width="500" frameborder="0"></iframe><br>
		<textarea name="recaptcha_challenge_field" rows="3" cols="40">
		</textarea>
		<input type="hidden" name="recaptcha_response_field" value="manual_challenge">
	</noscript>
	<?php endif;?>
    <div class="type-button savecomment">
        <button>
            <span><phrase id="SAVE" quiet="true"/></span>
        </button>
        <button class="cancel">
            <span><phrase id="CANCEL" quiet="true"/></span>
        </button>
    </div>
</form>