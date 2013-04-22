<?php
class Administration_Navigation extends Controller
{
	private static function GetNavigationId()
	{
		$navigation_ids = Navigation::GetNavigationIds();
		return Session::Get('Temp', 'navigation_id')===null
			? reset($navigation_ids)
			: Session::Get('Temp', 'navigation_id');
	}

	public function Index_POST()
	{
		FrontController::Relocate();
	}

	public function Index()
	{
		$template = $this->get_template(false, '');
	}

	public function SiteTitles_POST()
	{
		Sitemap::ClearParentSites();

		foreach ($_POST['parent_sites'] as $site_id=>$parent_site_id)
		{
			Sitemap::InsertParentSite($site_id, $parent_site_id);
		}
		Dobber::ReportSuccess('Seitenhierarchie erfolgreich aktualisiert');

		return false;
	}

	public function SiteTitles()
	{
		$template = $this->get_template(true);

		$sites = FrontController::GetAvailableActions(false, true);
		$template->assign('sites', $sites);
		$template->assign('parent_sites', Sitemap::GetParentSites());
	}

	public function ChangeNavigation()
	{
		Session::Set('Temp', 'navigation_id', $_POST['navigation_id']);
		FrontController::Relocate('Administration');
	}

	public function Administration_POST()
	{
		// Get current navigation_id
		$navigation_id = self::GetNavigationId();

		foreach ($_POST['section'] as $section_id=>$section)
		{
			foreach ($section['items'] as $unique_id=>$item)
			{
				$link = $item['link'];
				$type = 'site';
				switch ($link)
				{
					case '-2':
						$type = 'page';
						$link = $item['external'];
						break;
					case '-1':
						$type = 'link';
						$link = $item['external'];
						break;
				}
				Navigation::Set($navigation_id, $section_id, $unique_id, $type, $item['title'], $link, $item['roles']);
			}
			if ($section_id!=$section['name'])
			{
				Navigation::RenameSection($navigation_id, $section_id, $section['name']);
			}
		}
		FrontController::Relocate();
	}

	public function Administration()
	{
		$template = $this->get_template(true);
		$template->assign('roles', Navigation::GetRoles());
		$template->assign('current_navigation', Navigation::Get( self::GetNavigationId() ));
		$template->assign('actions', FrontController::GetAvailableActions(true));
		$template->assign('plugins', Event::GetHooks('Navigation:Plugin', true));
		$template->assign('navigation_ids', Navigation::GetNavigationIds());
		$template->assign('current_navigation_id', self::GetNavigationId());
	}


	public function MoveItem()
	{
		if (empty($_GET['section_id']) or empty($_GET['item_id']) or empty($_GET['direction']))
		{
			Dobber::Error('Invalid parameters for '.__METHOD__);
			FrontController::Relocate('Administration');
		}

		Navigation::MoveSectionItem(self::GetNavigationId(), $_GET['section_id'], $_GET['item_id'], $_GET['direction']);
		FrontController::Relocate('Administration');
	}

	public function MoveItemToSection()
	{
		if (empty($_GET['section_id']) or empty($_GET['item_id']) or empty($_GET['new_section_id']))
		{
			Dobber::Error('Invalid parameters for '.__METHOD__);
			FrontController::Relocate('Administration');
		}

		Navigation::MoveItemToSection(self::GetNavigationId(), $_GET['section_id'], $_GET['item_id'], $_GET['new_section_id']);
		FrontController::Relocate('Administration');
	}

	public function MoveSection()
	{
		if (empty($_GET['section_id']) or empty($_GET['direction']))
		{
			Dobber::Error('Invalid parameters for '.__METHOD__);
			FrontController::Relocate('Administration');
		}

		Navigation::MoveSection(self::GetNavigationId(), $_GET['section_id'], $_GET['direction']);
		FrontController::Relocate('Administration');
	}

	public function RemoveItem()
	{
		if (empty($_GET['section_id']) or empty($_GET['item_id']))
		{
			Dobber::Error('Invalid parameters for '.__METHOD__);
			FrontController::Relocate('Administration');
		}
		Navigation::RemoveItem(self::GetNavigationId(), $_GET['section_id'], $_GET['item_id']);
		FrontController::Relocate('Administration');
	}

	public function RemoveSection()
	{
		if (empty($_GET['section_id']))
		{
			Dobber::Error('Invalid parameters for '.__METHOD__);
			FrontController::Relocate('Administration');
		}
		Navigation::RemoveSection(self::GetNavigationId(), $_GET['section_id']);
		FrontController::Relocate('Administration');
	}

	public function RelocateItem()
	{
		if (empty($_GET['section_id']) or empty($_GET['item_id']) or empty($_GET['position']))
		{
			Dobber::Error('Invalid parameters for '.__METHOD__);
			FrontController::Relocate('Administration');
		}
		Navigation::RelocateItem(self::GetNavigationId(), $_GET['section_id'], $_GET['item_id'], $_GET['position']);
		FrontController::Relocate('Administration');
	}

	public function Insert_POST()
	{
		$navigation_id = self::GetNavigationId();

		$nav_id = $_POST['navigation_id'];
		if ($nav_id=='-1')
		{
			$nav_id = $_POST['new_navigation'];
			Session::Set('Temp', 'navigation_id', $nav_id);
		}

		$section_id = $_POST['section_id'];
		if ($section_id=='-1')
		{
			$section_id = $_POST['new_section_id'];
			Navigation::CreateSection($nav_id, $section_id);
		}

		$link = $_POST['link'];
		$type = 'site';
		switch ($link)
		{
			case '-2':
				$type = 'page';
				$link = $_POST['external_link'];
				break;
			case '-1':
				$type = 'link';
				$link = $_POST['external_link'];
				break;
		}

		Navigation::CreateItem($nav_id, $section_id, $type, $_POST['title'], $link, $_POST['roles']);
		FrontController::Relocate();
	}

	public function Insert()
	{
		$template = $this->get_template(true);
		$template->assign('navigation_ids', Navigation::GetNavigationIds());
		$template->assign('roles', Navigation::GetRoles());
		$template->assign('current_navigation', Navigation::Get( self::GetNavigationId() ));
		$template->assign('current_navigation_id', self::GetNavigationId());
		$template->assign('actions', FrontController::GetAvailableActions(true));
		$template->assign('plugins', Event::GetHooks('Navigation:Plugin', true));

	}
}
?>