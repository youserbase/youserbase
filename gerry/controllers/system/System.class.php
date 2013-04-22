<?php
class System extends Controller
{
	public function Confirm()
	{
		$result = false;
		if (!$this->Getted('key'))
		{
			Dobber::ReportError('INVALID_REQUEST');
		}
		elseif (($confirmation_data = Confirmation::Confirm($_GET['key']))===null)
		{
			Dobber::ReportError('ALREADY_ACTIVATED');
		}
		else
		{
			$result = Event::Dispatch('grab', 'Confirmed:'.$confirmation_data['scope'], $confirmation_data['confirmed'], $confirmation_data['youser_id'], $confirmation_data['subject']);
		}
		if ($result===false)
		{
			FrontController::Relocate('System', 'System', 'Error');
		}
		elseif (is_object($result) and is_a($result, 'Template'))
		{
			$template = $this->get_template($result);
		}
		else
		{
			$template = $this->get_template(true);
			if (is_string($result))
			{
				$template->assign('message', $result);
			}
		}
	}

	public function DeclineConfirmation()
	{
		if (!$this->Getted('key'))
		{
			Dobber::ReportError('INVALID_REQUEST');
		}
		elseif (($confirmation_data = Confirmation::Confirm($_GET['key']))===null)
		{
			Dobber::ReportError('ERROR_DENIAL');
			return;
		}
		Event::Dispatch('alert', 'Declined:'.$confirmation_data['scope'], $confirmation_data['youser_id'], $confirmation_data['subject']);

			Dobber::ReportSuccess('SUCCESS_DENIAL');
		if (!empty($_GET['redirect_to']))
		{
			FrontController::Relocate($_GET['redirect_to']);
		}
	}

	public function Index()
	{
		$this->get_template(true);
	}

	public function Page_404($location=null)
	{
		Header('HTTP/1.1 404 Not found');

		$template = $this->get_template(true);
		$template->assign('location', $location);
	}

	public function Error()
	{
		$this->get_template(true);
	}

	public function Maintenance()
	{
		Header($_SERVER['SERVER_PROTOCOL'].' 503 Service unavailable');
		Header('Status: 503 Service unavailable');
		$this->get_template(true)->set_layout('layouts/gerry_teaser.php');;
	}
}
?>