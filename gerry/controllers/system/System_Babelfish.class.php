<?php
class System_Babelfish extends Controller
{
	public function Load()
	{
		$translation = BabelFish::Get($_REQUEST['phrase_id'], $_REQUEST['language']);
		if ($translation===null)
		{
			$translation = $_REQUEST['default'];
		}
		echo $translation;
		die;
	}

	public function Tell()
	{
		$this->get_template(false, '<phrase id="'.$_REQUEST['phrase_id'].'" language="'.$_REQUEST['language'].'"/>');
	}

	public function Update()
	{
		if ($this->Posted('phrase_id', 'translation', 'language'))
		{
			BabelFish::UpdatePhrase($_POST['language'], $_POST['phrase_id'], $_POST['translation'], null, Youser::Id());
			echo $_POST['translation'];
		}
		die;
	}

	public function Translate()
	{
		$limit = 20;
		$skip = isset($_REQUEST['page']) ? $_REQUEST['page']*$limit : 0;

		$nonce = $_REQUEST['nonce'];
		$memory = Session::Get('memory', 'phrases');
		if (!isset($memory[$nonce]))
		{
			Dobber::ReportError('NONCE_OUT_OF_DATE');
			$template = $this->get_template(false, '<div>Error</div>');
			return;
		}

		$template = $this->get_template(true)->assign(compact('limit', 'skip'));
		$template->assign('total', max(array_map('count', $memory[$nonce])));

		$memory[$nonce]['untranslated'] = array_slice(array_reverse($memory[$nonce]['untranslated']), $skip, $limit);
		$memory[$nonce]['translated'] = array_slice(array_reverse($memory[$nonce]['translated']), $skip, $limit);
		$template->assign('phrases', $memory[$nonce]);
	}
}
?>