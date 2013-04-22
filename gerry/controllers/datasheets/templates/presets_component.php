<div class="rbox">
	<h2><phrase id="ADMINISTRATION FOR"> <phrase id="<?=strtoupper($table_name)?>"/></h2>
	<div class="back">
		<a href="<?=FrontController::GetLink('Index')?>"><phrase id="BACK"/></a>
	</div>
	<?php if(isset($data)):?>
		<div class="new">
			<a href="<?=FrontController::GetLink('datasheets', 'Presets','Edit', array('table_name' => $table_name))?>" class="lightbox"><phrase id="NEW_PRESET"/></a>
		</div>
		<table>
			<thead>
				<tr>
					<th>
						<phrase id="name"/>
					</th>
					<th>
						<phrase id="value"/>
					</th>
					<th colspan="2">
						<phrase id="OPTIONS"/>
					</th>
				</tr>
			</thead>	
			<?$count = 1?>
			<?php foreach ($data as $content):?>
				<tbody>
					<tr>
						<th colspan="4">
							<?=$count++?>
						</th>
					</tr>
					<?php foreach ($content as $line_name => $line_content):?>
						<?php if(strpos($line_name, '_id') == false && $line_name != 'timestamp'):?>
								<tr>
									<td>
										<phrase id="<?=strtoupper($line_name)?>"/>
									</td>
									<td>
										<?=is_numeric($line_content)?$line_content:'<phrase id="'.$line_content.'"/>'?>
									</td>
									<td>
										<a href="<?=FrontController::GetLink('datasheets', 'Presets','Edit', array('table_name' => $table_name, 'primary' => $content[$table_name.'_id']))?>" class="lightbox"><phrase id="EDIT"/></a>
									</td>
									<td>
										<a href="<?=FrontController::GetLink('datasheets', 'Presets','Delete', array('table_name' => $table_name, 'primary' => $content[$table_name.'_id']))?>"><phrase id="DELETE"/></a>
									</td>
								</tr>
						<?php endif;?>
					<?php endforeach;?>
				</tbody>
			<?php endforeach;?>
		</table>
	<?php endif;?>
</div>