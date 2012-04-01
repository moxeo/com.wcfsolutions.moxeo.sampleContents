<?php
/**
 * @author	Sebastian Oettl
 * @copyright	2009-2012 WCF Solutions <http://www.wcfsolutions.com/>
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 */
$packageID = $this->installation->getPackageID();
$parentPackageID = $this->installation->getPackage()->getParentPackageID();
$instanceNo = $this->installation->getPackage()->getParentPackage()->getInstanceNo();
$filename = 'sample-theme.tgz';

// extract theme tar
$sourceFile = $this->installation->getArchive()->extractTar($filename, 'theme_');

// import theme
require_once(WCF_DIR.'lib/data/theme/ThemeEditor.class.php');
$theme = ThemeEditor::import($sourceFile, $packageID);

// delete tmp file
@unlink($sourceFile);

// create default contents
if ($theme->themeID) {
	// create theme layout
	require_once(WCF_DIR.'lib/data/theme/layout/ThemeLayoutEditor.class.php');
	$themeLayout = ThemeLayoutEditor::create($theme->themeID, 'Default', "global\nlayout\nnavigation\nfile\nimage\nnews\ncomment\nform", $packageID);
	$themeLayout->setAsDefault($parentPackageID);

	// create default modules
	require_once(WCF_DIR.'lib/data/theme/module/ThemeModuleEditor.class.php');
	$userPanelThemeModule = ThemeModuleEditor::create($theme->themeID, 'Page Title', 'userPanel', '', 'html', array(
		'code' => '<div id="userNote">{if $this->user->userID}Angemeldet als {$this->user->username}.{else}Sie sind nicht angemeldet.{/if}</div>',
		'dynamicCode' => '<div id="userNote"><?php if ($this->v[\'this\']->user->userID) { ?>Angemeldet als <?php echo StringUtil::encodeHTML($this->v[\'this\']->user->username); ?>.<?php } else { ?>Sie sind nicht angemeldet.<?php } ?></div>'
	), $packageID);
	$headerThemeModule = ThemeModuleEditor::create($theme->themeID, 'Page Title', 'pageTitle', '', 'html', array(
		'code' => '<a href="./">{PAGE_TITLE}</a>',
		'dynamicCode' => '<a href="./"><?php echo StringUtil::encodeHTML(PAGE_TITLE); ?></a>'
	), $packageID);
	$mainNavigationThemeModule = ThemeModuleEditor::create($theme->themeID, 'Main Menu', 'mainMenu', '', 'navigation', array('levelOffset' => 0, 'levelLimit' => 1), $packageID);
	$subNavigationThemeModule = ThemeModuleEditor::create($theme->themeID, 'Sub Menu', 'subMenu', '', 'navigation', array('levelOffset' => 1, 'levelLimit' => 5), $packageID);
	$breadCrumbThemeModule = ThemeModuleEditor::create($theme->themeID, 'Bread Crumbs', '', '', 'breadCrumb', array(), $packageID);
	$articleThemeModule = ThemeModuleEditor::create($theme->themeID, 'Article', '', '', 'article', array(), $packageID);
	$footerThemeModule = ThemeModuleEditor::create($theme->themeID, 'Footer', '', '', 'html', array(
		'code' => '<a href="http://www.moxeo.org/">Software: <strong>Moxeo Open Source CMS</strong>, entwickelt von WCF Solutions</a> | {@TIME_NOW|fulldate}',
		'dynamicCode' => '<?php
if (!isset($this->pluginObjects[\'TemplatePluginModifierFulldate\'])) {
require_once(WCF_DIR.\'lib/system/template/plugin/TemplatePluginModifierFulldate.class.php\');
$this->pluginObjects[\'TemplatePluginModifierFulldate\'] = new TemplatePluginModifierFulldate;
}
?><a href=\"http://www.moxeo.org/\">Software: <strong>Moxeo Open Source CMS</strong>, entwickelt von WCF Solutions</a> | <?php echo $this->pluginObjects[\'TemplatePluginModifierFulldate\']->execute(array(TIME_NOW), $this); ?>'
	), $packageID);

	// add modules to layout
	$themeLayout->addThemeModule($userPanelThemeModule->themeModuleID, 'header');
	$themeLayout->addThemeModule($headerThemeModule->themeModuleID, 'header');
	$themeLayout->addThemeModule($mainNavigationThemeModule->themeModuleID, 'header');
	$themeLayout->addThemeModule($subNavigationThemeModule->themeModuleID, 'left');
	$themeLayout->addThemeModule($breadCrumbThemeModule->themeModuleID, 'main');
	$themeLayout->addThemeModule($articleThemeModule->themeModuleID, 'main');
	$themeLayout->addThemeModule($footerThemeModule->themeModuleID, 'footer');

	// create index page
	$sql = "INSERT INTO	moxeo".WCF_N."_".$instanceNo."_content_item
				(languageID, parentID, title, contentItemAlias)
		VALUES		(".WCF::getLanguage()->getLanguageID().", 0, 'Startseite', 'startseite')";
	WCF::getDB()->sendQuery($sql);
	$contentItemID = WCF::getDB()->getInsertID("moxeo".WCF_N."_".$instanceNo."_content_item", 'contentItemID');

	// create article
	$sql = "INSERT INTO	moxeo".WCF_N."_".$instanceNo."_article
				(contentItemID, themeModulePosition, title)
		VALUES		(".$contentItemID.", 'main', 'Startseite')";
	WCF::getDB()->sendQuery($sql);
	$articleID = WCF::getDB()->getInsertID("moxeo".WCF_N."_".$instanceNo."_article", 'articleID');

	// save article section
	$articleSectionData = array(
		'headline' => 'Willkommen bei Moxeo Open Source CMS!',
		'headlineSize' => 1,
		'code' => '<p>Gl&uuml;ckwunsch, Sie haben <em>Moxeo Open Source CMS</em> erfolgreich installiert! Sie k&ouml;nnen nun &uuml;ber die <a href="./acp/">Administrationsoberfl&auml;che</a> neue Inhalte hinzuf&uuml;gen.</p>',
		'thumbnail' => '',
		'thumbnailCaption' => '',
		'thumbnailAlternativeTitle' => '',
		'thumbnailURL' => '',
		'enableThumbnail' => 0,
		'thumbnailEnableFullsize' => 0
	);
	$sql = "INSERT INTO	moxeo".WCF_N."_".$instanceNo."_article_section
				(articleID, articleSectionType, articleSectionData)
		VALUES		(".$articleID.", 'text', '".escapeString(serialize($articleSectionData))."')";
	WCF::getDB()->sendQuery($sql);
}
?>