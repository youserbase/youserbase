<div class="view_type">
<form action="<?=FrontController::GetLink()?>" method="get" class="onchange_submit">
	<ul>
		<li class="gallery <?=Session::Get('catalogue', 'view')=='gallery'?'selected':''?>">
			<input id="view_gallery" type="radio" name="view" value="gallery" <?=Session::Get('catalogue', 'view')=='gallery'?'checked="checked"':''?>/>
			<label for="view_gallery">
				<phrase id="VIEW_TYPE_GALLERY"/>
			</label>
		</li>
		<li class="list <?=Session::Get('catalogue', 'view')=='list'?'selected':''?>">
			<input id="view_list" type="radio" name="view" value="list" <?=Session::Get('catalogue', 'view')=='list'?'checked="checked"':''?>/>
			<label for="view_list">
				<phrase id="VIEW_TYPE_LIST"/>
			</label>
		</li>
		<li>
			<button type="submit"> <span> <phrase id="CHANGE"/> </span> </button>
		</li>
	</ul>
</form>
</div>
