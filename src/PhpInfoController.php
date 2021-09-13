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
		$dom->loadStr('<div>' . $info . '</div>');
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

		$logo = $dom->find('img');
		$logo->delete();

		$title = $dom->find('h1');
		$title->delete();
		$title->setAttribute('class', 'text-center');

		$tables = $dom->find('table');
		$tables->each(function ($table) {
			$table->setAttribute('class', 'table');
		});

		$menu = implode('<br/>', $menu);

		$template = file_get_contents(__DIR__ . '/../template/template.phtml');
		$template = str_replace('${menu}', $menu, $template);
		$template = str_replace('${content}', $dom, $template);
		$template = str_replace('${logo}', $logo, $template);
		$template = str_replace('${title}', $title, $template);
		return $template;
	}

}
