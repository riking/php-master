<?php
require('lib.php');

class IndexSubdir {
	private $path;
	private $name;
	private $infoJson;
	public function __construct($path) {
		$this->path = $path;
		$this->name = \basename($path);

		$jsonPath = $path . "/info.json";
		if (\file_exists($jsonPath)) {
			$jsonFile = \file_get_contents($path . "/info.json");
			$this->infoJson = \json_decode($jsonFile, \true);
			$this->infoJson['valid'] = \false;
		} else {
			$this->infoJson = array('valid' => \false);
		}
	}

	/**
	 * @return bool
	 */
	public function has_link() {
		return !$this->infoJson['no_link'];
	}

	/**
	 * @return string
	 */
	public function link() {
		return $this->path;
	}

	/**
	 * @return string
	 */
	public function title() {
		if ($this->infoJson['title'])
			return $this->infoJson['title'];
		return $this->name;
	}

	/**
	 * @return array|null
	 */
	public function sublinks() {
		return $this->infoJson['sublinks'];
	}

	/**
	 * @return \kyork\HtmlSafeString
	 */
	public function desc() {
		if ($this->infoJson['descHTML'])
		{
			return \kyork\html_safe(' &ndash; ' . $this->infoJson['descHTML']);
		}
		else if ($this->infoJson['desc']) {
			return \kyork\htmljoin(
				\kyork\html_safe(' &ndash; '),
				$this->infoJson['desc']
			);
		}
		else
			return \kyork\html_safe("");
	}
}

$subdirs = array();
$blacklist = array('.', '..', '.git', '.well-known');
foreach(glob("./*", GLOB_ONLYDIR) as $dir) {
	$subdirs[] = new IndexSubdir($dir);
}

/*
 * body contents
 */
ob_start();
?><ul><?php
echo "\n";
foreach($subdirs as $subdir)
{
?>	<li><?php
	if ($subdir->has_link()) {
		?><a href="<?= \kyork\attr($subdir->link()); ?>"><?php
	}
	?><?= \kyork\html($subdir->title()); ?><?php
	if ($subdir->has_link()) {
		?></a><?php
	}
	?><?= \kyork\h($subdir->desc()); ?><?php
	$sublinks = $subdir->sublinks();
	if ($sublinks)
	{
		echo "\n";
?>		<ul><?php
		foreach($sublinks as $sublink)
		{
			echo "\n";
?>			<li><?php
			?><a href="<?= \kyork\attr($subdir->link() . '/' . $sublink['href']); ?>"><?= \kyork\html($sublink['title']) ?></a><?php
			if ($sublink['desc'])
			{
				?> &ndash; <?= \kyork\h($sublink['desc']) ?><?php
			}
			?></li><?php
			echo "\n";
		}
?>		</ul>
	<?php
	}
	?></li><?php
	echo "\n";
}
?></ul><?php
$content = ob_get_clean();

?>
<!DOCTYPE html>
<html>
<head>
<style>
body{margin: 40px auto; max-width: 650px; line-height: 1.6; font-size: 18px; color: #111;}
h1,h2,h3{line-height: 1.2}
</style>
</head>
<body>
<div class=container>
<?= $content ?>
</div>
</body>
</html>
