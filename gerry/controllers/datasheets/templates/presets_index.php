<div class="rbox">
	<h2><phrase id="PRESETS_ADMINISTRATION"/></h2>
	<?php if(isset($tables)):?>
		<?php $counter = 1+$skip_components?>
		<div class="pagination">
			<?php if($skip_components >= 40):?>
				<a href="<?=FrontController::GetLink('Index', array('skip_components' => -20))?>">
			<?php endif;?>
					<phrase id="FIRST"/>
			<?php if($skip_components >= 40):?>
				</a>
			<?php endif;?>
			<?php if($skip_components >= 20):?>
				<a href="<?=FrontController::GetLink('Index', array('skip_components' => $skip_components-40))?>">
			<?php endif;?>
				<phrase id="PREVIOUS"/>
			<?php if($skip_components >= 20):?>
				</a>
			<?php endif;?>
			<?php if($skip_components <= $components_count-20):?>
				<a href="<?=FrontController::GetLink('Index', array('skip_components' => $skip_components))?>">
			<?php endif?>
				<phrase id="NEXT"/>
			<?php if($skip_components <= $components_count-20):?>
				</a>
			<?php endif;?>
			<?php if($skip_components != $components_count-20):?>
				<a href="<?=FrontController::GetLink('Index', array('skip_components' => $components_count-20))?>">
			<?php endif?>
				<phrase id="LAST"/>
			<?php if($skip_components != $components_count-20):?>
				</a>
			<?php endif?>
		</div>
		<table>
		<?php foreach ($tables as $table_name => $table_content):?>
			<tr>
				<th>
					<?=$table_name?>
				</th>
				<th>
					<a href="<?=FrontController::GetLink('component', array('table_name' => $table_content))?>"><phrase id="EDIT"/></a>
				</th>
			</tr>
		<?php endforeach;?>
		</table>
		<div class="pagination">
			<?php if($skip_components >= 40):?>
				<a href="<?=FrontController::GetLink('Index', array('skip_components' => -20))?>">
			<?php endif;?>
					<phrase id="FIRST"/>
			<?php if($skip_components >= 40):?>
				</a>
			<?php endif;?>
			<?php if($skip_components >= 20):?>
				<a href="<?=FrontController::GetLink('Index', array('skip_components' => $skip_components-40))?>">
			<?php endif;?>
				<phrase id="PREVIOUS"/>
			<?php if($skip_components >= 20):?>
				</a>
			<?php endif;?>
			<?php if($skip_components <= $components_count-20):?>
				<a href="<?=FrontController::GetLink('Index', array('skip_components' => $skip_components))?>">
			<?php endif?>
				<phrase id="NEXT"/>
			<?php if($skip_components <= $components_count-20):?>
				</a>
			<?php endif;?>
			<?php if($skip_components != $components_count-20):?>
				<a href="<?=FrontController::GetLink('Index', array('skip_components' => $components_count-20))?>">
			<?php endif?>
				<phrase id="LAST"/>
			<?php if($skip_components != $components_count-20):?>
				</a>
			<?php endif?>
		</div>
	<?php endif;?>
</div>