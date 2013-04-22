<h1><phrase id="CHOOSEDATABASEFORDUMP"/></h1>
<dl>
<?php foreach ($scopes as $scope): ?>
	<dt><?=$scope['name']?></dt>
	<dd>
		<a href="<?=FrontController::GetLink('Dump', array('scope'=>$scope['name']))?>"><?=$scope['database']?></a>
		(<?=numberformat($scope['tables'])?> <phrase id="TABLES"/>/<?=numberformat($scope['rows'])?> <phrase id="ROWS"/>)
	</dd>
<?php endforeach; ?>
</dl>
