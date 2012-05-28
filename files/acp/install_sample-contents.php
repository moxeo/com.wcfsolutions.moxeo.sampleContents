<?php
/**
 * @author	Sebastian Oettl
 * @copyright	2009-2012 WCF Solutions <http://www.wcfsolutions.com/>
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 */
/**
 * Compiles the given string.
 *
 * @param	string		$string
 * @return	string
 */
function compileTPL($string) {
	if (strpos($string, '{') !== false) {
		require_once(WCF_DIR.'lib/system/template/TemplateScriptingCompiler.class.php');
		$scriptingCompiler = new TemplateScriptingCompiler(WCF::getTPL());
		try {
			return $scriptingCompiler->compileString('moxeoInstallation', $string);
		}
		catch (SystemException $e) {}
	}
	return $string;
}

// vars
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
	// language items
	$languages = array(
		'de' = array(
			'contentItem.index' => 'Startseite',
			'contentItem.index.alias' => 'startseite',
			'contentItem.index.welcome' => 'Willkommen bei Moxeo Open Source CMS!',
			'contentItem.index.welcome.description' => '<p>Gl&uuml;ckwunsch, Sie haben <em>Moxeo Open Source CMS</em> erfolgreich installiert! Das war einfach, oder? Sie k&ouml;nnen nun &uuml;ber die <a href="./acp/">Administrationsoberfl&auml;che</a> neue Inhalte hinzuf&uuml;gen.</p>',
			'copyright' => '<a href="http://www.moxeo.org/">Software: <strong>Moxeo Open Source CMS</strong>, entwickelt von WCF Solutions</a>',
			'theme.default' => 'Standard',
			'theme.module.userPanel' => 'Benutzerpanel',
			'theme.module.pageTitle' => 'Seitentitel',
			'theme.module.mainMenu' => 'Hauptmenü',
			'theme.module.subMenu' => 'Untermenü',
			'theme.module.breadCrumbs' => 'Brotkrümel-Navigation',
			'theme.module.article' => 'Artikel',
			'theme.module.footer' => 'Footer',
			'userNote' => '{if $this->user->userID}Angemeldet als {$this->user->username}.{else}Sie sind nicht angemeldet.{/if}'
		),
		'en' = array(
			'contentItem.index' => 'Index page',
			'contentItem.index.alias' => 'index',
			'contentItem.index.welcome' => 'Welcome to Moxeo Open Source CMS!',
			'contentItem.index.welcome.description' => '<p>Congratulations, you have installed <em>Moxeo Open Source CMS</em> successfully! That was easy, wasn\'t it? You can now add new contents using the <a href="./acp/">Administration Control Panel</a></p>',
			'copyright' => '<a href="http://www.moxeo.org/">Software: <strong>Moxeo Open Source CMS</strong>, developed by WCF Solutions</a>',
			'theme.default' => 'Default',
			'theme.module.userPanel' => 'User Panel',
			'theme.module.pageTitle' => 'Page Title',
			'theme.module.mainMenu' => 'Main Menu',
			'theme.module.subMenu' => 'Sub Menu',
			'theme.module.breadCrumbs' => 'Bread Crumbs',
			'theme.module.article' => 'Article',
			'theme.module.footer' => 'Footer',
			'userNote' => '{if $this->user->userID}Welcome {$this->user->username}.{else}You are not logged in.{/if}'
		)
	);

	// map language items
	$languageCode = WCF::getLannguage()->getLanguageCode();
	$language = (isset($languages[$languageCode]) ? $languages[$languageCode] : $languages['en']);

	// create theme layout
	require_once(WCF_DIR.'lib/data/theme/layout/ThemeLayoutEditor.class.php');
	$themeLayout = ThemeLayoutEditor::create($theme->themeID, $language['theme.default'], "global\nlayout\nnavigation\nfile\nimage\nnews\ncomment\nform", $packageID);
	$themeLayout->setAsDefault($parentPackageID);

	// create default modules
	require_once(WCF_DIR.'lib/data/theme/module/ThemeModuleEditor.class.php');
	$userPanelThemeModule = ThemeModuleEditor::create($theme->themeID, $language['theme.module.userPanel'], 'userPanel', '', 'html', array(
		'code' => '<div id="userNote">'.$language['userNote'].'</div>',
		'dynamicCode' => compileTPL('<div id="userNote">'.$language['userNote'].'</div>')
	), $packageID);
	$headerThemeModule = ThemeModuleEditor::create($theme->themeID, $language['theme.module.pageTitle'], 'pageTitle', '', 'html', array(
		'code' => '<a href="./">{PAGE_TITLE}</a>',
		'dynamicCode' => compileTPL('<a href="./">{PAGE_TITLE}</a>')
	), $packageID);
	$mainNavigationThemeModule = ThemeModuleEditor::create($theme->themeID, $language['theme.module.mainMenu'], 'mainMenu', '', 'navigation', array('levelOffset' => 0, 'levelLimit' => 1), $packageID);
	$subNavigationThemeModule = ThemeModuleEditor::create($theme->themeID, $language['theme.module.subMenu'], 'subMenu', '', 'navigation', array('levelOffset' => 1, 'levelLimit' => 5), $packageID);
	$breadCrumbThemeModule = ThemeModuleEditor::create($theme->themeID, $language['theme.module.breadCrumbs'], '', '', 'breadCrumb', array(), $packageID);
	$articleThemeModule = ThemeModuleEditor::create($theme->themeID, $language['theme.module.article'], '', '', 'article', array(), $packageID);
	$footerThemeModule = ThemeModuleEditor::create($theme->themeID, $language['theme.module.footer'], '', '', 'html', array(
		'code' => $language['copyright'].' | {@TIME_NOW|fulldate}',
		'dynamicCode' => compileTPL($language['copyright'].' | {@TIME_NOW|fulldate}')
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
		VALUES		(".WCF::getLanguage()->getLanguageID().", 0, '".escapeString($language['contentItem.index'])."', '".escapeString($language['contentItem.index.alias'])."')";
	WCF::getDB()->sendQuery($sql);
	$contentItemID = WCF::getDB()->getInsertID("moxeo".WCF_N."_".$instanceNo."_content_item", 'contentItemID');

	// create article
	$sql = "INSERT INTO	moxeo".WCF_N."_".$instanceNo."_article
				(contentItemID, themeModulePosition, title)
		VALUES		(".$contentItemID.", 'main', '".escapeString($language['contentItem.index'])."')";
	WCF::getDB()->sendQuery($sql);
	$articleID = WCF::getDB()->getInsertID("moxeo".WCF_N."_".$instanceNo."_article", 'articleID');

	// save article section
	$articleSectionData = array(
		'headline' => $language['contentItem.index.welcome'],
		'headlineSize' => 1,
		'code' => $language['contentItem.index.welcome.description'],
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