<div id="mod_modifycompare" class="mod_rating">
	<div class="submit">
		<form action="<?=FrontController::GetLink('Index', array('tab' => $tab))?>" method="post">
			<table border="1">
			<?php foreach ($sheet as $rated_tab=>$empty): ?>
				<tr>
					<td class="mod_tab">
						<label for="<?=$rated_tab?>">
							<phrase id="<?=$rated_tab?>"/>
						</label>
					</td>
					<td class="mod_tab">
						<select class="slider_" name="<?=$rated_tab?>_rating_mod">
						<?php foreach ($mod_values as $value): ?>
							<option	<?=(isset($tab_mods[$rated_tab]) and ($value==$tab_mods[$rated_tab]))?'selected="selected"':''?>>
								<?=$value?>
							</option>
						<?php endforeach; ?>
						</select>
					</td>
				</tr>
			<?php endforeach; ?>
				<tr>
					<td class="action" colspan="2">
						<button type="submit">
							<span><phrase id="CHANGE"/></span>
						</button>
					</td>
				</tr>
			</table>
		</form>
	</div>
	<div class="reset">
		<form action="<?=FrontController::GetLink('Index', array('tab' => $tab))?>" method="post">
			<fieldset>
				<button type="submit" class="cancel">
					<span><phrase id="CANCEL"/></span>
				</button>
			<?php foreach ($sheet as $rated_tab=>$empty): ?>
				<input type="hidden" name="<?=$rated_tab?>_rating_mod" value="1"/>
			<?php endforeach; ?>
			</fieldset>
		</form>
	</div>
</div>