<?php
class System_Pages extends Controller
{
	public function Display()
	{
		$template = $this->get_template(true);
		$template->assign('page', $_REQUEST['page']);
		$template->assign('PAGE_TITLE_APPEND', md5($_REQUEST['page']));
	}

	public function Update_POST()
	{
		if (!Youser::May('edit_pages'))
		{
			Dobber::ReportError('INVALID_REQUEST');
			FrontController::Relocate('Display', array('page'=>$_REQUEST['page']));
		}

		$page = Pages::GetPage($_REQUEST['page'], BabelFish::GetLanguage());
		if (md5($page['contents']) != md5(trim($_REQUEST['content'])))
		{
			Pages::UpdatePage($_REQUEST['page'], BabelFish::GetLanguage(), trim($_REQUEST['content']), Youser::Id());
			Dobber::ReportSuccess('SUCCESS_PAGE_UPDATE', array('page'=>$_REQUEST['page']));
		}
		else
		{
			Dobber::ReportNotice('NOTICE_PAGE_UPDATE_NO_CHANGE', array('page'=>$_REQUEST['page']));
		}

		if ($GLOBALS['VIA_AJAX'])
		{
			$this->get_template(false, trim($_REQUEST['content']));
		}
		else
		{
			FrontController::Relocate('Display', array('page'=>$_REQUEST['page']));
		}
	}

	public function Update()
	{
		if (!Youser::May('edit_pages'))
		{
			Dobber::ReportError('INVALID_REQUEST');
			FrontController::Relocate('Display', array('page'=>$_REQUEST['page']));
		}

		$template = $this->get_template(true);
		$template->assign('page', $_REQUEST['page']);
		$template->assign('data', Pages::GetPage($_REQUEST['page'], BabelFish::GetLanguage()));
	}

	public function Load()
	{
		$content = empty($_REQUEST['language'])
			? Pages::GetPage($_REQUEST['page'])
			: Pages::GetPage($_REQUEST['page'], $_REQUEST['language']);
		$this->get_template(false, $content['contents']);
	}
}
?>