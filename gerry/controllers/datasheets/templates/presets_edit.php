<div class="rbox">
	<h2><phrase id="EDIT"/> <phrase id="<?=strtoupper($table_name)?>"/></h2>
	<form method="post" action="<?=FrontController::GetLink('save', array('table_name' => $table_name))?>">
		<table>
			<tr>
				<th colspan="2">
					<phrase id="<?=$table_name?>"/>
				</th>
			</tr>
			<?php foreach ($data[0] as $line_name => $value):?>
				<?php if ($line_name != 'youser_id' && $line_name != 'timestamp' && strpos($line_name, 'id') === false):?>
					<tr>
						<td>
							<?=$line_name?>	
						</td>
						<td>
							<input type="text" name="<?=$line_name?>" value="<?=$new===false?$value:''?>"/>
						</td>
					</tr>
				<?php endif;?>
			<?php endforeach;?>
			<tr>
				<td>
					<input type="hidden" name="<?=$table_name?>_id" value="<?=$new===false?$data[0][$table_name.'_id']:''?>"/>
					<input type="hidden" name="<?=$table_name?>_id_int" value="<?=$new===false?$data[0][$table_name.'_id_int']:''?>"/>
				</td>
				<td>
					<button><span><phrase id="SAVE"/></span></button>
				</td>
			</tr>
		</table>
	</form>
</div>