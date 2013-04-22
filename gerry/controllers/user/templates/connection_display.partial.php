<tr>
	<td>
		<a href="<?=FrontController::GetLink('Nickpage', 'Display', array('youser'=>$nickname))?>">
			<img src="<?=Youser_Image::GetLink($id, 'small')?>" alt=""/>
		</a>
	</td>
	<td>
		<a href="<?=FrontController::GetLink('Nickpage', 'Display', array('youser'=>$nickname))?>">
			<?=$nickname?>
		</a>
	</td>
</tr>