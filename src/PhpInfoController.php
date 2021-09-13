<?php

use PHPHtmlParser\Dom;

class PhpInfoController
{

	public function render()
	{
		ob_start();
		phpinfo();
		$info = ob_get_clean();
//		error_log(strlen($info));

		$dom = new Dom();
		$dom->loadStr($info);
		$style = $dom->find('style');
		if ($style->count()) {
			$style->delete();
		}
		$h2 = $dom->find('h2 a');
		foreach ($h2 as $tag) {
			$name = $tag->innerHtml();
			$slug = $tag->getAttribute('name');
			$menu[] = "<a href='#${slug}'>${name}</a>";
		}

		$menu = implode('<br/>', $menu);

		$template = file_get_contents(__DIR__ . '/../template/template.phtml');
		$template = str_replace('${menu}', $menu, $template);
		$template = str_replace('${content}', $info, $template);
		return $template;
	}

}
