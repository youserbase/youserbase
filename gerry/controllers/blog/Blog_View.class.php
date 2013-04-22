<?php
class Blog_View extends Controller 
{
	public function Index(){
		$template = $this->get_template(true);
		$rss = RSS::Read('http://www.youserblog.com/feed/');
		$template->assign('rss' , $rss);
	}
}
?>