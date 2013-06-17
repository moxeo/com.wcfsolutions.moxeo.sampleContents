<?php
/**
 * @author	Sebastian Oettl
 * @copyright	2009-2013 WCF Solutions <http://www.wcfsolutions.com/>
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
		'de' => array(
			'contentItem.index' => 'Startseite',
			'contentItem.index.alias' => 'startseite',
			'contentItem.index.welcome' => 'Willkommen bei Moxeo Open Source CMS!',
			'contentItem.index.welcome.description' => '<p>Gl&uuml;ckwunsch, Sie haben <em>Moxeo Open Source CMS</em> erfolgreich installiert! Das war einfach, oder? Sie k&ouml;nnen nun &uuml;ber die <a href="./acp/">Administrationsoberfl&auml;che</a> neue Inhalte hinzuf&uuml;gen.</p>',
			'contentItem.login' => 'Anmelden',
			'contentItem.login.alias' => 'anmelden',
			'contentItem.logout' => 'Abmelden',
			'contentItem.logout.alias' => 'abmelden',
			'contentItem.register' => 'Registrieren',
			'contentItem.register.alias' => 'registrieren',
			'contentItem.accountManagement' => 'Accountverwaltung',
			'contentItem.accountManagement.alias' => 'account',
			'contentItem.error403' => 'Zutritt verwehrt',
			'contentItem.error403.description' => 'Der Zutritt zu dieser Seite ist Ihnen leider verwehrt. Sie besitzen nicht die notwendigen Zugriffsrechte, um diese Seite aufrufen zu können.',
			'contentItem.error404' => 'Seite nicht gefunden',
			'contentItem.error404.description' => 'Sie haben einen ungültigen oder nicht mehr gültigen Link aufgerufen.',
			'copyright' => '<a href="http://www.moxeo.org/">Software: <strong>Moxeo Open Source CMS</strong>, entwickelt von WCF Solutions</a>',
			'theme.default' => 'Standard',
			'theme.module.userPanel' => 'Benutzerpanel',
			'theme.module.pageTitle' => 'Seitentitel',
			'theme.module.login' => 'Anmelden',
			'theme.module.logout' => 'Abmelden',
			'theme.module.register' => 'Registrieren',
			'theme.module.accountManagement' => 'Accountverwaltung',
			'theme.module.mainMenu' => 'Hauptmenü',
			'theme.module.subMenu' => 'Untermenü',
			'theme.module.breadCrumbs' => 'Brotkrümel-Navigation',
			'theme.module.article' => 'Artikel',
			'theme.module.footer' => 'Footer',
			'userNote' => '<p id="userNote">{if $this->user->userID}Angemeldet als {$this->user->username}.{else}Sie sind nicht angemeldet.{/if}</p>
<div id="userMenu">
	<ul>
		{if $this->user->userID}
			<li><a href="{if \'URL_PREFIX\'|defined}{@URL_PREFIX}{else}index.php/{/if}logout/?t={@SECURITY_TOKEN}{@SID_ARG_2ND}">Abmelden</a></li>
			<li><a href="{if \'URL_PREFIX\'|defined}{@URL_PREFIX}{else}index.php/{/if}account/{@SID_ARG_1ST}">Accountverwaltung</a></li>
			{if $this->user->getPermission(\'admin.general.canUseAcp\')}<li><a href="acp/index.php">Administration</a></li>{/if}
		{else}
			<li><a href="{if \'URL_PREFIX\'|defined}{@URL_PREFIX}{else}index.php/{/if}login/{@SID_ARG_1ST}">Anmelden</a></li>
			<li><a href="{if \'URL_PREFIX\'|defined}{@URL_PREFIX}{else}index.php/{/if}register/{@SID_ARG_1ST}">Registrieren</a></li>
		{/if}
	</ul>
</div>'
		),
		'en' => array(
			'contentItem.index' => 'Index page',
			'contentItem.index.alias' => 'index',
			'contentItem.index.welcome' => 'Welcome to Moxeo Open Source CMS!',
			'contentItem.index.welcome.description' => '<p>Congratulations, you have installed <em>Moxeo Open Source CMS</em> successfully! That was easy, wasn\'t it? You can add new contents now using the <a href="./acp/">Administration Control Panel</a>.</p>',
			'contentItem.login' => 'Login',
			'contentItem.login.alias' => 'login',
			'contentItem.logout' => 'Logout',
			'contentItem.logout.alias' => 'logout',
			'contentItem.register' => 'Register',
			'contentItem.register.alias' => 'register',
			'contentItem.accountManagement' => 'Account Management',
			'contentItem.accountManagement.alias' => 'account',
			'contentItem.error403' => 'Permission Denied',
			'contentItem.error403.description' => 'You are not allowed to enter this page. You do not have the required permissions to enter this page.',
			'contentItem.error404' => 'Page Not Found',
			'contentItem.error404.description' => 'The link you are trying to reach is no longer available or is invalid.',
			'copyright' => '<a href="http://www.moxeo.org/">Software: <strong>Moxeo Open Source CMS</strong>, developed by WCF Solutions</a>',
			'theme.default' => 'Default',
			'theme.module.userPanel' => 'User Panel',
			'theme.module.pageTitle' => 'Page Title',
			'theme.module.login' => 'Login',
			'theme.module.logout' => 'Logout',
			'theme.module.register' => 'Register',
			'theme.module.accountManagement' => 'Account Management',
			'theme.module.mainMenu' => 'Main Menu',
			'theme.module.subMenu' => 'Sub Menu',
			'theme.module.breadCrumbs' => 'Bread Crumbs',
			'theme.module.article' => 'Article',
			'theme.module.footer' => 'Footer',
			'userNote' => '<p id="userNote">{if $this->user->userID}Welcome {$this->user->username}.{else}You are not logged in.{/if}</p>
<div id="userMenu">
	<ul>
		{if $this->user->userID}
			<li><a href="{if \'URL_PREFIX\'|defined}{@URL_PREFIX}{else}index.php/{/if}logout/?t={@SECURITY_TOKEN}{@SID_ARG_2ND}">Logout</a></li>
			<li><a href="{if \'URL_PREFIX\'|defined}{@URL_PREFIX}{else}index.php/{/if}account/{@SID_ARG_1ST}">Account Management</a></li>
			{if $this->user->getPermission(\'admin.general.canUseAcp\')}<li><a href="acp/index.php">Administration</a></li>{/if}
		{else}
			<li><a href="{if \'URL_PREFIX\'|defined}{@URL_PREFIX}{else}index.php/{/if}login/{@SID_ARG_1ST}">Login</a></li>
			<li><a href="{if \'URL_PREFIX\'|defined}{@URL_PREFIX}{else}index.php/{/if}register/{@SID_ARG_1ST}">Register</a></li>
		{/if}
	</ul>
</div>'
		)
	);

	// map language items
	$languageCode = WCF::getLanguage()->getLanguageCode();
	$language = (isset($languages[$languageCode]) ? $languages[$languageCode] : $languages['en']);

	// create theme layout
	require_once(WCF_DIR.'lib/data/theme/layout/ThemeLayoutEditor.class.php');
	require_once(WCF_DIR.'lib/data/theme/stylesheet/ThemeStylesheetEditor.class.php');
	$themeStylesheetIDs = array_keys(ThemeStylesheet::getThemeStylesheetOptions($theme->themeID));
	$themeLayout = ThemeLayoutEditor::create($theme->themeID, $language['theme.default'], $themeStylesheetIDs, $packageID);
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
	$loginThemeModule = ThemeModuleEditor::create($theme->themeID, $language['theme.module.login'], '', '', 'login', array(), $packageID);
	$logoutThemeModule = ThemeModuleEditor::create($theme->themeID, $language['theme.module.logout'], '', '', 'logout', array(), $packageID);
	$registerThemeModule = ThemeModuleEditor::create($theme->themeID, $language['theme.module.register'], '', '', 'register', array(), $packageID);
	$accountManagementThemeModule = ThemeModuleEditor::create($theme->themeID, $language['theme.module.accountManagement'], '', '', 'accountManagement', array(), $packageID);
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
	$themeLayout->addThemeModule($loginThemeModule->themeModuleID, 'left');
	$themeLayout->addThemeModule($breadCrumbThemeModule->themeModuleID, 'main');
	$themeLayout->addThemeModule($articleThemeModule->themeModuleID, 'main');
	$themeLayout->addThemeModule($footerThemeModule->themeModuleID, 'footer');

	$contentItems = array();

	// index page
	$contentItems[] = array(
		'languageID' => WCF::getLanguage()->getLanguageID(),
		'parentID' => 0,
		'title' => $language['contentItem.index'],
		'contentItemAlias' => $language['contentItem.index.alias'],
		'showOrder' => 1,
		'articles' => array(
			array(
				'themeModulePosition' => 'main',
				'title' => $language['contentItem.index'],
				'sections' => array(
					array(
						'articleSectionType' => 'text',
						'articleSectionData' => array(
							'headline' => $language['contentItem.index.welcome'],
							'headlineSize' => 1,
							'code' => $language['contentItem.index.welcome.description'],
							'thumbnail' => '',
							'thumbnailCaption' => '',
							'thumbnailAlternativeTitle' => '',
							'thumbnailURL' => '',
							'enableThumbnail' => 0,
							'thumbnailEnableFullsize' => 0
						)
					)
				)
			)
		)
	);

	// login page
	$contentItems[] = array(
		'languageID' => WCF::getLanguage()->getLanguageID(),
		'parentID' => 0,
		'title' => $language['contentItem.login'],
		'contentItemAlias' => $language['contentItem.login.alias'],
		'robots' => 'noindex/nofollow',
		'showOrder' => 2,
		'invisible' => 1,
		'restrictedAccess' => 'guests',
		'articles' => array(
			array(
				'themeModulePosition' => 'main',
				'title' => $language['contentItem.login'],
				'sections' => array(
					array(
						'articleSectionType' => 'themeModule',
						'articleSectionData' => array(
							'headline' => $language['contentItem.login'],
							'headlineSize' => 1,
							'themeModuleID' => $loginThemeModule->themeModuleID
						)
					)
				)
			)
		)
	);

	// register page
	$contentItems[] = array(
		'languageID' => WCF::getLanguage()->getLanguageID(),
		'parentID' => 0,
		'title' => $language['contentItem.register'],
		'contentItemAlias' => $language['contentItem.register.alias'],
		'robots' => 'noindex/nofollow',
		'showOrder' => 3,
		'invisible' => 1,
		'restrictedAccess' => 'guests',
		'articles' => array(
			array(
				'themeModulePosition' => 'main',
				'title' => $language['contentItem.register'],
				'sections' => array(
					array(
						'articleSectionType' => 'themeModule',
						'articleSectionData' => array(
							'headline' => $language['contentItem.register'],
							'headlineSize' => 1,
							'themeModuleID' => $registerThemeModule->themeModuleID
						)
					)
				)
			)
		)
	);

	// account management page
	$contentItems[] = array(
		'languageID' => WCF::getLanguage()->getLanguageID(),
		'parentID' => 0,
		'title' => $language['contentItem.accountManagement'],
		'contentItemAlias' => $language['contentItem.accountManagement.alias'],
		'robots' => 'noindex/nofollow',
		'showOrder' => 4,
		'invisible' => 1,
		'restrictedAccess' => 'users',
		'articles' => array(
			array(
				'themeModulePosition' => 'main',
				'title' => $language['contentItem.accountManagement'],
				'sections' => array(
					array(
						'articleSectionType' => 'themeModule',
						'articleSectionData' => array(
							'headline' => $language['contentItem.accountManagement'],
							'headlineSize' => 1,
							'themeModuleID' => $accountManagementThemeModule->themeModuleID
						)
					)
				)
			)
		)
	);

	// logout page
	$contentItems[] = array(
		'languageID' => WCF::getLanguage()->getLanguageID(),
		'parentID' => 0,
		'title' => $language['contentItem.logout'],
		'contentItemAlias' => $language['contentItem.logout.alias'],
		'robots' => 'noindex/nofollow',
		'showOrder' => 5,
		'invisible' => 1,
		'addSecurityToken' => 1,
		'restrictedAccess' => 'users',
		'articles' => array(
			array(
				'themeModulePosition' => 'main',
				'title' => $language['contentItem.logout'],
				'sections' => array(
					array(
						'articleSectionType' => 'themeModule',
						'articleSectionData' => array(
							'headline' => '',
							'headlineSize' => 1,
							'themeModuleID' => $logoutThemeModule->themeModuleID
						)
					)
				)
			)
		)
	);

	// error 403 page
	$contentItems[] = array(
		'languageID' => WCF::getLanguage()->getLanguageID(),
		'parentID' => 0,
		'title' => $language['contentItem.error403'],
		'contentItemAlias' => 'error403',
		'contentItemType' => 2,
		'showOrder' => 6,
		'articles' => array(
			array(
				'themeModulePosition' => 'main',
				'title' => $language['contentItem.error403'],
				'sections' => array(
					array(
						'articleSectionType' => 'text',
						'articleSectionData' => array(
							'headline' => $language['contentItem.error403'],
							'headlineSize' => 1,
							'code' => $language['contentItem.error403.description'],
							'thumbnail' => '',
							'thumbnailCaption' => '',
							'thumbnailAlternativeTitle' => '',
							'thumbnailURL' => '',
							'enableThumbnail' => 0,
							'thumbnailEnableFullsize' => 0
						)
					)
				)
			)
		)
	);

	// error 404 page
	$contentItems[] = array(
		'languageID' => WCF::getLanguage()->getLanguageID(),
		'parentID' => 0,
		'title' => $language['contentItem.error404'],
		'contentItemAlias' => 'error404',
		'contentItemType' => 3,
		'showOrder' => 7,
		'articles' => array(
			array(
				'themeModulePosition' => 'main',
				'title' => $language['contentItem.error404'],
				'sections' => array(
					array(
						'articleSectionType' => 'text',
						'articleSectionData' => array(
							'headline' => $language['contentItem.error404'],
							'headlineSize' => 1,
							'code' => $language['contentItem.error404.description'],
							'thumbnail' => '',
							'thumbnailCaption' => '',
							'thumbnailAlternativeTitle' => '',
							'thumbnailURL' => '',
							'enableThumbnail' => 0,
							'thumbnailEnableFullsize' => 0
						)
					)
				)
			)
		)
	);

	// create content items
	foreach ($contentItems as $contentItem) {
		if (!isset($contentItem['description'])) $contentItem['description'] = '';
		if (!isset($contentItem['pageTitle'])) $contentItem['pageTitle'] = '';
		if (!isset($contentItem['metaDescription'])) $contentItem['metaDescription'] = '';
		if (!isset($contentItem['metaKeywords'])) $contentItem['metaKeywords'] = '';
		if (!isset($contentItem['contentItemType'])) $contentItem['contentItemType'] = 0;
		if (!isset($contentItem['externalURL'])) $contentItem['externalURL'] = '';
		if (!isset($contentItem['publishingStartTime'])) $contentItem['publishingStartTime'] = 0;
		if (!isset($contentItem['publishingEndTime'])) $contentItem['publishingEndTime'] = 0;
		if (!isset($contentItem['themeLayoutID'])) $contentItem['themeLayoutID'] = 0;
		if (!isset($contentItem['cssClasses'])) $contentItem['cssClasses'] = '';
		if (!isset($contentItem['robots'])) $contentItem['robots'] = 'index/follow';
		if (!isset($contentItem['showOrder'])) $contentItem['showOrder'] = 0;
		if (!isset($contentItem['enabled'])) $contentItem['enabled'] = 1;
		if (!isset($contentItem['invisible'])) $contentItem['invisible'] = 0;
		if (!isset($contentItem['addSecurityToken'])) $contentItem['addSecurityToken'] = 0;

		$sql = "INSERT INTO	moxeo".WCF_N."_".$instanceNo."_content_item
					(languageID, parentID, title, contentItemAlias, description, pageTitle, metaDescription, metaKeywords, contentItemType, externalURL, publishingStartTime, publishingEndTime, themeLayoutID, cssClasses, robots, showOrder, enabled, invisible, addSecurityToken)
			VALUES		(".$contentItem['languageID'].", ".$contentItem['parentID'].", '".escapeString($contentItem['title'])."', '".escapeString($contentItem['contentItemAlias'])."', '".escapeString($contentItem['description'])."', '".escapeString($contentItem['pageTitle'])."', '".escapeString($contentItem['metaDescription'])."', '".escapeString($contentItem['metaKeywords'])."', ".$contentItem['contentItemType'].", '".escapeString($contentItem['externalURL'])."', ".$contentItem['publishingStartTime'].", ".$contentItem['publishingEndTime'].", ".$contentItem['themeLayoutID'].", '".escapeString($contentItem['cssClasses'])."', '".escapeString($contentItem['robots'])."', ".$contentItem['showOrder'].", ".$contentItem['enabled'].", ".$contentItem['invisible'].", ".$contentItem['addSecurityToken'].")";
		WCF::getDB()->sendQuery($sql);
		$contentItemID = WCF::getDB()->getInsertID("moxeo".WCF_N."_".$instanceNo."_content_item", 'contentItemID');

		if (isset($contentItem['restrictedAccess'])) {
			$allowedGroupID = 3;
			$disallowedGroupID = 1;
			if ($contentItem['restrictedAccess'] == 'guests') {
				$allowedGroupID = 2;
			}

			$sql = "INSERT INTO	moxeo".WCF_N."_".$instanceNo."_content_item_to_group
						(contentItemID, groupID, canViewContentItem, canEnterContentItem, canViewHiddenContentItem)
				VALUES		(".$contentItemID.", ".$allowedGroupID.", 1, 1, 1)";
			WCF::getDB()->sendQuery($sql);

			$sql = "INSERT INTO	moxeo".WCF_N."_".$instanceNo."_content_item_to_group
						(contentItemID, groupID, canViewContentItem, canEnterContentItem, canViewHiddenContentItem)
				VALUES		(".$contentItemID.", ".$disallowedGroupID.", 0, 0, 0)";
			WCF::getDB()->sendQuery($sql);
		}

		if (isset($contentItem['articles'])) {
			foreach ($contentItem['articles'] as $article) {
				if (!isset($article['cssID'])) $article['cssID'] = '';
				if (!isset($article['cssClasses'])) $article['cssClasses'] = '';
				if (!isset($article['showOrder'])) $article['showOrder'] = 0;

				$sql = "INSERT INTO	moxeo".WCF_N."_".$instanceNo."_article
							(contentItemID, themeModulePosition, title, cssID, cssClasses, showOrder)
					VALUES		(".$contentItemID.", '".escapeString($article['themeModulePosition'])."', '".escapeString($article['title'])."', '".escapeString($article['cssID'])."', '".escapeString($article['cssClasses'])."', ".$article['showOrder'].")";
				WCF::getDB()->sendQuery($sql);
				$articleID = WCF::getDB()->getInsertID("moxeo".WCF_N."_".$instanceNo."_article", 'articleID');

				if (isset($article['sections'])) {
					foreach ($article['sections'] as $section) {
						if (!isset($section['articleSectionData'])) $section['articleSectionData'] = array();
						if (!isset($section['cssID'])) $section['cssID'] = '';
						if (!isset($section['cssClasses'])) $section['cssClasses'] = '';
						if (!isset($section['showOrder'])) $section['showOrder'] = 0;

						$sql = "INSERT INTO	moxeo".WCF_N."_".$instanceNo."_article_section
									(articleID, articleSectionType, articleSectionData, cssID, cssClasses, showOrder)
							VALUES		(".$articleID.", '".escapeString($section['articleSectionType'])."', '".escapeString(serialize($section['articleSectionData']))."', '".escapeString($section['cssID'])."', '".escapeString($section['cssClasses'])."', ".$section['showOrder'].")";
						WCF::getDB()->sendQuery($sql);
					}
				}
			}
		}
	}
}
?>