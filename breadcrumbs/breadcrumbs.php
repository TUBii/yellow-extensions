<?php
// Breadcrumbs plugin, https://github.com/datenstrom/yellow-plugins/tree/master/breadcrumbs
// Copyright (c) 2013-2017 Datenstrom, https://datenstrom.se
// This file may be used and distributed under the terms of the public license.

class YellowBreadcrumbs {
    const VERSION = "0.6.3";
    public $yellow;         //access to API
    
    // Handle initialisation
    public function onLoad($yellow) {
        $this->yellow = $yellow;
        $this->yellow->config->setDefault("breadcrumbsSeparator", ">");
        $this->yellow->config->setDefault("breadcrumbsStyle", "breadcrumbs");
    }
    
    // Handle page content of custom block
    public function onParseContentBlock($page, $name, $text, $shortcut) {
        $output = null;
        if ($name=="breadcrumbs" && $shortcut) {
            list($separator, $style) = $this->yellow->toolbox->getTextArgs($text);
            if (empty($separator)) $separator = $this->yellow->config->get("breadcrumbsSeparator");
            if (empty($style)) $style = $this->yellow->config->get("breadcrumbsStyle");
            $pages = $this->yellow->pages->path($page->getLocation(true), true);
            $page->setLastModified($pages->getModified());
            $output = "<div class=\"".htmlspecialchars($style)."\">";
            foreach ($pages as $pageBreadcrumb) {
                $output .= "<a href=\"".$pageBreadcrumb->getLocation(true)."\">".$pageBreadcrumb->getHtml("titleNavigation")."</a>";
                if ($pageBreadcrumb->getLocation(true)!=$page->getLocation(true)) $output .= " ".htmlspecialchars($separator)." ";
            }
            $output .= "</div>\n";
        }
        return $output;
    }
    
    // Handle page extra data
    public function onParsePageExtra($page, $name) {
        return $this->onParseContentBlock($page, $name, "", true);
    }
}
