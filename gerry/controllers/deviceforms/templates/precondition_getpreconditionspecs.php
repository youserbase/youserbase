<div id="listing" class="rbox">
	<h3 for="<?=$table?>"><phrase id="<?=strtoupper($table)?>"/></h2>
	<form class="validate" action="<?=FrontController::GetLink('getPreconditionSpecs', array('table' => $table, 'new' => 'alter'))?>" method="POST">
		<ul>
			<dt/>
			<dd>
				<?php if(isset($value_id)):?>
					<input type="hidden" name="<?=$table?>_id" value="<?=$value_id?>"/>
				<?php elseif(isset($value_name)):?>
					<input type="hidden" name="<?=$table?>_name" value="<?=$value_name?>"/>
				<?php endif;?>
			</dd>
			<dt>
				<label for="<?=$table?>"><phrase id="<?=strtoupper($table)?>"/></label>
			</dt>
			<dd>
				<select multiple class='required'
					<?php foreach ($preconditions as $precondition):?>
						<?php if(isset($precondition[$table.'_id'])):?>
							name="<?=$table.'_id'?>">
						<?php elseif(isset($precondition[$table.'_name'])):?>
							name="<?=$table.'_name'?>">
						<?php endif;?>
					<?php endforeach;?>
					<?php foreach ($preconditions as $precondition):?>
						<option
						<?php if(isset($precondition[$table.'_id'])):?>
							value="<?=$precondition[$table.'_id']?>"
						<?php elseif(isset($precondition[$table.'_name'])):?>
							value="<?=$precondition[$table.'_name']?>"
						<?php endif;?>
						>
						<?php if(isset($precondition[$table.'_shortname'])):?>
							<phrase id=<?=strtoupper($precondition[$table.'_shortname'])?>/>
						<?php elseif(isset($precondition[$table.'_name'])):?>
							<phrase id="<?=$precondition[$table.'_name']?>"/>
						<?php endif;?>
						<?php if(isset($precondition[$table.'_x']) && isset($precondition[$table.'_x'])):?>
							<?=$precondition[$table.'_x']?> x <?=$precondition[$table.'_y']?>
						<?php endif;?>
						<?php if(isset($precondition[$table.'_standard'])):?>
							<phrase id="<?=$precondition[$table.'_standard']?>"/>
						<?php endif;?>
						<?php if(isset($precondition[$table.'_amount'])):?>
							<phrase id="<?=$precondition[$table.'_amount']?>"/>
						<?php endif;?>
						<?php if(isset($precondition[$table.'_allocation'])):?>
							<phrase id="<?=$precondition[$table.'_allocation']?>"/>
						<?php endif;?>
						<?php if(isset($precondition[$table.'_compression'])):?>
							<?php if(!empty($precondition[$table.'_compression']) && $precondition[$table.'_compression'] != 0):?>
								<?=$precondition[$table.'_compression']?> <phrase id="KBIT"/>
							<?php endif;?>
						<?php endif;?>
						<?php if(isset($precondition[$table.'_frequency'])):?>
							<?php if(!empty($precondition[$table.'_frequency']) && $precondition[$table.'_frequency'] != 0):?>
								<?=$precondition[$table.'_frequency']?>
							<?php endif;?>
						<?php endif;?>
						<?php if(isset($precondition[$table.'_speed'])):?>
							<?=$precondition[$table.'_speed']?> <phrase id="MHZ"/>
						<?php endif;?>
						<?php if(isset($precondition[$table.'_version'])):?>
							<phrase id="<?=$precondition[$table.'_version']?>"/>
						<?php endif;?>
						<?php if(isset($precondition['device_type_name'])):?>
							<phrase id="<?=$precondition['device_type_name']?>"/>
						<?php endif;?>
						<?php if(isset($precondition[$table.'_short'])):?>
							<?php if(!empty($precondition[$table.'_short'])):?>
								( .<?=strtolower($precondition[$table.'_short'])?>)
							<?php endif;?>
						<?php endif;?>
						</option>
					<?php endforeach;?>
				</select>
			</dd>
			<dt/>
			<dd>
				<input type="submit" value_phrase="ALTER_PRECONDITIONS"/>
			</dd>
		</ul>
	</form>
</div>
<?php if(!isset($preconSpecs)):?>
<div>
	<a href="<?=FrontController::GetLink('getPreconditionSpecs', array('table' => $table, 'new' => 'new'))?>"><input type="submit" value_phrase="NEW_<?=$table?>"/></a>
</div>
<?php else:?>
	<form class="validate" action="<?=FrontController::GetLink('getPreconditionSpecs', array('table' => $table, 'new' => 'save'))?>", method="POST">
		<dl>
			<?php foreach($preconSpecs as $line):?>
				<?php if(isset($line['label'])):?>
				<dt>
					<?=$line['label']?>
				</dt>
				<?endif;?>
				<?php if(isset($line['input']['form'])):?>
				<dd>
					<?=$line['input']['form']?>
				</dd>
				<?php endif;?>
			<?php endforeach;?>
			<dt/>
			<dd>
				<input type="submit" value_phrase="SAVE"> <a href="<?=FrontController::GetLink('getPreconditionSpecs', array('table' => $table))?>"><input type="button" value_phrase="CANCEL"/></a>
			</dd>
		</dl>
	</form>
<?php endif;?>

<script>
	$(document).ready(function()
	{
		$('input').removeAttr('style');
		$('select').removeAttr('style');
	});
</script>