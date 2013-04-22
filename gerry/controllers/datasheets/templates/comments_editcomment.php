<h3>
    <?php if (isset($comment)): ?>
    <phrase id="EDIT_YOUR_COMMENT"/><?php else : ?>
    <phrase id="ADD_A_COMMENT"/><?php endif; ?>
</h3>
<form class="new_comment ajax yform" method="POST" action="<?=FrontController::GetLink('Comments','saveComment', array('device_id' => $device_id, 'comments_id' => $comments_id))?>">
    <div class="type-select">
        <label for="your-id">
            <phrase id="CATEGORY"/>
        </label>
        <select name="category" id="select_category">
            <option><phrase id="COMMON" quiet="true"/></option><option><phrase id="TECHNOLOGY" quiet="true"/></option><option><phrase id="COMMUNICATION" quiet="true"/></option><option><phrase id="AUDIO" quiet="true"/></option><option><phrase id="CAMERA" quiet="true"/></option><option><phrase id="VIDEOCAMERA" quiet="true"/></option><option><phrase id="GPS" quiet="true"/></option><option><phrase id="MESSAGING" quiet="true"/></option><option><phrase id="ORGANIZATION" quiet="true"/></option>
        </select>
    </div>
    <div class="type-text">
        <textarea name="comment" cols="60" rows="4">
            <?php if (isset($comment)): ?><?= $comment?><?php endif; ?>
        </textarea>
    </div>
    <div class="type-button">
        <button>
            <span><phrase id="SAVE" quiet="true"/></span>
        </button>
        <button class="cancel">
            <span><phrase id="CANCEL" quiet="true"/></span>
        </button>
    </div>
</form>