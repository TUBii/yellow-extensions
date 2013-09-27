<?php $pages = getBlogPages($yellow) ?>
<?php $yellow->snippet("header") ?>
<?php $yellow->snippet("navigation") ?>
<div class="content blog">
<?php if($yellow->page->isExisting("titleBlog")): ?>
<h1><?php echo $yellow->page->getHtml("titleBlog") ?></h1>
<?php endif ?>
<?php foreach($pages as $page): ?>
<div class="article">
<div class="entry-header"><h1><a href="<?php echo $page->getLocation() ?>"><?php echo $page->getHtml("title") ?></a></h1></div>
<div class="entry-meta"><?php echo $page->getHtml("published") ?> <?php echo $yellow->text->getHtml("blogBy") ?> <a href="<?php echo $yellow->page->getLocation()."author:".rawurlencode(strtoloweru($page->get("author"))) ?>"><?php echo $page->getHtml("author") ?></a></div>
<div class="entry-content"><?php echo $yellow->toolbox->createTextDescription($page->getContent(), 1024, false, "<!--more-->", " <a href=\"".$page->getLocation()."\">".$yellow->text->getHtml("blogMore")."</a>") ?></div>
</div>
<?php endforeach ?>
<?php $yellow->snippet("pagination", $pages) ?>
</div>
<?php $yellow->snippet("footer") ?>
<?php $yellow->header("Last-Modified: ".$pages->getModified(true)) ?>
<?php function getBlogPages($yellow)
{
	$pages = $yellow->page->getChildren();
	$pagesFilter = array();
	if($_REQUEST["tag"]) { $pages->filter("tag", $_REQUEST["tag"]); array_push($pagesFilter, ucfirst($_REQUEST["tag"])); }
	if($_REQUEST["author"]) { $pages->filter("author", $_REQUEST["author"]); array_push($pagesFilter, ucfirst($_REQUEST["author"])); }
	if($_REQUEST["published"]) { $pages->filter("published", $_REQUEST["published"], false); array_push($pagesFilter, $_REQUEST["published"]); }
	if(!empty($pagesFilter))
	{
		$title = implode(' ', $pagesFilter);
		$yellow->page->set("titleHeader", $title." - ".$yellow->page->getHtml("sitename"));
		$yellow->page->set("titleBlog", $yellow->text->getHtml("blogFilter")." ".$title);
	}
	return $pages->pagination(5);
}
?>