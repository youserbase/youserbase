<div class="admin_comments">
	<?php if(isset($comments)):?>
	<h3><phrase id="NEWEST_COMMENTS"/></h3>
		<div class="commets">
			<?php if (!empty($comments)):?>
				<form action="<?=FrontController::GetLink('Mass_Action')?>" method="post">
					<button>
						<span>
							<phrase id="DO_IT"/>
						</span>
					</button>
					<?php foreach ($comments as $comment):?>
						<div>
							<div class="form">
								<label for="comment_delete"><phrase id="DELETE"/></label>
								<input type="checkbox" name="comments_delete[]" value="<?=$comment['comments_id']?>"/>
								<label for="comment_notify"><phrase id="NOTIFY"/></label>
								<input type="checkbox" name="comments_notify[]" value="<?=$comment['comments_id']?>"/>
							</div>
							<div><device id="<?=$comment['device_id']?>"/></div>
							<div>
								<?=$this->render_partials('comment', (array)$comments, compact('burn'))?>
							</div>
						</div>
					<?php endforeach;?>
					<button>
						<span>
							<phrase id="DO_IT"/>
						</span>
					</button>
				</form>
			<?php else:?>
				<phrase id="NO_COMMENTS"/>
			<?php endif;?>
		</div>
	<?php endif;?>
	<?php if(isset($offensive_comments)):?>
	<h3><phrase id="BURNED_COMMENTS"/></h3>
		<div class="commets">
			<?php if(!empty($offensive_comments)):?>
				<form action="<?=FrontController::GetLink('Mass_Action')?>" method="post">
					<button>
						<span>
							<phrase id="DO_IT"/>
						</span>
					</button>
					<?php foreach ($offensive_comments as $comment):?>
						<div>
							<div class="form">
								<label for="comment_delete"><phrase id="DELETE"/></label>
								<input type="checkbox"  name="comments_delete[]" value="<?=$comment['comments_id']?>"/>
								<label for="comment_unnotify"><phrase id="UNNOTIFY"/></label>
								<input type="checkbox" name="comments_notify[]" value="<?=$comment['comments_id']?>"/>
							</div>
							<div><device id="<?=$comment['device_id']?>"/></div>
							<div>
							<?=$this->render_partial('comment', array('comment' => $comment, 'burn' => 2))?>
							</div>
						</div>
					<?php endforeach;?>
					<button>
						<span>
							<phras id="DO_IT"/>
						</span>
					</button>
				</form>
			<?php else:?>
				<phrase id="NO_NOTIFICATIONS"/>
			<?php endif;?>
		</div>
	<?php endif;?>
	<h3><phrase id="DELETED_COMMENTS"/></h3>
	<?php if(isset($deleted)):?>
		<div class="commets">
			<?php if(!empty($deleted)):?>
				<form action="<?=FrontController::GetLink('Mass_Action')?>" method="post">
					<button>
						<span>
							<phras id="DO_IT"/>
						</span>
					</button>
					<?php foreach ($deleted as $comment):?>
						<div class="form">
							<label for="comment_undelete"><phrase id="UNDELETE"/></label>
							<input type="checkbox" name="comments_delete[]" value="<?=$comment['comments_id']?>"/>
							<label for="comment_delete"><phrase id="DELETE"/></label>
							<input type="checkbox" name="comments_delete_final[]" value="<?=$comment['comments_id']?>"/>
						</div>
						<div><device id="<?=$comment['device_id']?>"/></div>
						<div>
						<?=$this->render_partial('deletedcomments', array('comment' => $comment, 'burn' => 2))?>
						</div>
					<?php endforeach;?>
					<button>
						<span>
							<phras id="DO_IT"/>
						</span>
					</button>
				</form>
			<?php else:?>
				<phrase id="NO_DELETED_COMMENTS"/>
			<?php endif;?>
		</div>
	<?php endif;?>
</div>