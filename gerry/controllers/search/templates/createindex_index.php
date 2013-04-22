<?=$this->render_partial('search', array('needle' => isset($needle)?$needle:''))?>

<a href="<?=FrontController::GetLink('build_index')?>">Build new Index</a>