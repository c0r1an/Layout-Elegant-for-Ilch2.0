<?php /** @var $this \Ilch\Layout\Frontend */ ?>
<?php
$siteName = trim((string) $this->getLayoutSetting('siteName'));
$siteTagline = trim((string) $this->getLayoutSetting('siteTagline'));
$footerCopyright = trim((string) $this->getLayoutSetting('footerCopyright'));
$footerBlockThreeHtmlRaw = trim((string) $this->getLayoutSetting('footerBlockThreeHtml'));
$footerBlockFourHtmlRaw = trim((string) $this->getLayoutSetting('footerBlockFourHtml'));
$siteLogo = trim((string) $this->getLayoutSetting('siteLogo'));
$pageHeroBackgroundImage = trim((string) $this->getLayoutSetting('pageHeroBackgroundImage'));
$headerMainSticky = $this->getLayoutSetting('headerMainSticky') == 1;
$showRootMenuItems = $this->getLayoutSetting('showRootMenuItems') == 1;
$request = $this->getRequest();
$requestModule = strtolower((string) $request->getModuleName());
$requestController = strtolower((string) $request->getControllerName());
$requestAction = strtolower((string) $request->getActionName());
$startPage = strtolower((string) $this->getConfigKey('start_page'));
$isHomePage = false;

if (strpos($startPage, 'module_') === 0) {
    $startModule = substr($startPage, 7);
    $isHomePage = $requestModule === $startModule
        && ($requestController === '' || $requestController === 'index')
        && ($requestAction === '' || $requestAction === 'index');
} elseif (strpos($startPage, 'page_') === 0) {
    $startPageId = substr($startPage, 5);
    $isHomePage = $requestModule === 'admin'
        && $requestController === 'page'
        && $requestAction === 'show'
        && (string) $request->getParam('id') === (string) $startPageId;
} elseif (strpos($startPage, 'layouts_') === 0) {
    $startLayoutModule = substr($startPage, 8);
    $isHomePage = $requestModule === $startLayoutModule
        && ($requestController === '' || $requestController === 'index')
        && ($requestAction === '' || $requestAction === 'index');
} else {
    $isHomePage = $requestModule === 'index'
        && ($requestController === '' || $requestController === 'index')
        && ($requestAction === '' || $requestAction === 'index');
}
$isElegantLandingPage = $requestModule === 'elegant'
    && ($requestController === '' || $requestController === 'index')
    && ($requestAction === '' || $requestAction === 'index');
$isLayoutHomePage = $isHomePage && !$isElegantLandingPage;

$showSidebarGlobally = $this->getLayoutSetting('sidebarBoxes') == 1;
$showSidebarOnHome = $this->getLayoutSetting('sidebarBoxesHome') == 1;
$showSidebar = $showSidebarGlobally && (!$isLayoutHomePage || $showSidebarOnHome);
$sidebarBoxes = $showSidebar ? trim($this->getMenu(2, '<section class="elegant-widget elegant-widget-boxes"><h3>%s</h3><div class="elegant-widget-body">%c</div></section>')) : '';
$contentMarkup = (string) $this->getContent();
$hasContent = trim(strip_tags($contentMarkup)) !== '';
$sliderBoxHtml = trim((string) $this->getBox('elegant', 'slider'));
$introBoxHtml = trim((string) $this->getBox('elegant', 'intro'));
$platformCardsBoxHtml = trim((string) $this->getBox('elegant', 'platformCards'));
$featureCardsBoxHtml = trim((string) $this->getBox('elegant', 'featureCards'));
$newsBoxHtml = trim((string) $this->getBox('elegant', 'newsBox'));
$socialTopbarHtml = trim((string) $this->getBox('elegant', 'socialWidget', 'socialTopbar'));
$socialFooterHtml = trim((string) $this->getBox('elegant', 'socialWidget', 'socialFooter'));
$videoWidgetHtml = $showSidebar ? trim((string) $this->getBox('elegant', 'videoWidget')) : '';
$sideColumnHtml = trim($videoWidgetHtml . $sidebarBoxes);
$hasSideColumn = $sideColumnHtml !== '';
$copyrightText = $footerCopyright !== ''
    ? str_replace('{year}', date('Y'), $footerCopyright)
    : 'Copyright ' . date('Y') . ' ' . $siteName;
$topBarText = $siteTagline !== '' ? $siteTagline : 'Welcome to ' . ($siteName !== '' ? $siteName : 'Elegant*');
$moduleLandingUrl = $this->getBaseUrl('index.php/elegant/index/index');
$footerBlockThreeFallback = '<h3>Companion Module</h3><p>Use the attached Elegant* module for a dedicated landing page and direct admin access from the layout overview.</p><a class="elegant-text-link" href="' . $moduleLandingUrl . '">Open module page</a>';
$footerBlockFourFallback = '<h3>Layout Boxes</h3><p>Slider, platform cards, feature cards, social widget and video widget are now rendered as dedicated Elegant* boxes.</p>';
$footerBlockThreeHtml = $footerBlockThreeHtmlRaw !== ''
    ? $this->purify($footerBlockThreeHtmlRaw)
    : $footerBlockThreeFallback;
$footerBlockFourHtml = $footerBlockFourHtmlRaw !== ''
    ? $this->purify($footerBlockFourHtmlRaw)
    : $footerBlockFourFallback;
$pageHeroBackgroundImageUrl = '';
if ($pageHeroBackgroundImage !== '') {
    $pageHeroBackgroundImageUrl = preg_match('~^(?:https?:)?//|^data:~i', $pageHeroBackgroundImage)
        ? $pageHeroBackgroundImage
        : $this->getBaseUrl($pageHeroBackgroundImage);
}
$breadcrumbHtml = (string) $this->getHmenu();
$pageHeroTitle = '';

if (preg_match_all('~<a\b[^>]*>(.*?)</a>~is', $breadcrumbHtml, $linkMatches) && !empty($linkMatches[1])) {
    $lastBreadcrumbLink = trim(html_entity_decode(strip_tags((string) end($linkMatches[1])), ENT_QUOTES, 'UTF-8'));
    if ($lastBreadcrumbLink !== '') {
        $pageHeroTitle = $lastBreadcrumbLink;
    }
}

if ($pageHeroTitle === '' && preg_match_all('~<li\b[^>]*>(.*?)</li>~is', $breadcrumbHtml, $breadcrumbMatches) && !empty($breadcrumbMatches[1])) {
    $lastBreadcrumb = trim(html_entity_decode(strip_tags((string) end($breadcrumbMatches[1])), ENT_QUOTES, 'UTF-8'));
    if ($lastBreadcrumb !== '') {
        $pageHeroTitle = $lastBreadcrumb;
    }
}

if ($pageHeroTitle === '') {
    $pageHeroTitle = trim(html_entity_decode(strip_tags($breadcrumbHtml), ENT_QUOTES, 'UTF-8'));
}

if ($pageHeroTitle === '') {
    $pageHeroTitle = $siteName !== '' ? $siteName : 'Elegant*';
}

$groupIds = [3];
$adminAccess = false;

if ($this->getUser()) {
    $groupIds = array_map(
        static fn($group) => $group->getId(),
        $this->getUser()->getGroups()
    );
    $adminAccess = $this->getUser()->isAdmin();
}

$menuMapper = new \Modules\Admin\Mappers\Menu();
$pageMapper = new \Modules\Admin\Mappers\Page();
$accessMapper = new \Ilch\Accesses($request);
$menuId = (int) ($menuMapper->getMenuIdForPosition(1) ?? 1);
$menuItems = $menuMapper->getMenuItems($menuId);
$menuTree = [];
$menuItemsById = [];
$menuLocale = '';
$config = \Ilch\Registry::get('config');

if ((bool) $config->get('multilingual_acp') && $this->getTranslator()->getLocale() != $config->get('content_language')) {
    $menuLocale = $this->getTranslator()->getLocale();
}

foreach ($menuItems as $menuItem) {
    $menuItemsById[$menuItem->getId()] = $menuItem;
    $menuTree[$menuItem->getParentId()][] = $menuItem->getId();
}

$hasMenuItemAccess = static function (\Modules\Admin\Models\MenuItem $menuItem) use ($groupIds, $adminAccess): bool {
    $accessGroups = array_filter(array_map('trim', explode(',', (string) $menuItem->getAccess())), 'strlen');

    if ($adminAccess || $accessGroups === []) {
        return true;
    }

    return array_intersect($groupIds, $accessGroups) !== [];
};

$resolveMenuItemLink = function (\Modules\Admin\Models\MenuItem $menuItem) use ($accessMapper, $pageMapper, $menuLocale): ?array {
    $href = '#';
    $target = '';
    $rel = '';

    if ($menuItem->isPageLink()) {
        if (!$accessMapper->hasAccess('Module', (string) $menuItem->getSiteId(), $accessMapper::TYPE_PAGE)) {
            return null;
        }

        $page = $pageMapper->getPageByIdLocale($menuItem->getSiteId(), $menuLocale) ?: $pageMapper->getPageByIdLocale($menuItem->getSiteId());
        $href = $this->getUrl($page ? $page->getPerma() : '');
    } elseif ($menuItem->isModuleLink()) {
        if (!$accessMapper->hasAccess('Module', $menuItem->getModuleKey())) {
            return null;
        }

        $href = $this->getUrl([
            'module' => $menuItem->getModuleKey(),
            'controller' => 'index',
            'action' => 'index',
        ]);
    } elseif ($menuItem->isLink()) {
        $href = $menuItem->getHref();
        $targetValue = trim((string) $menuItem->getTarget());

        if ($targetValue !== '') {
            $target = ' target="' . $this->escape($targetValue) . '"';
            if ($targetValue === '_blank') {
                $rel = ' rel="noopener"';
            }
        }
    } elseif ($menuItem->isBox()) {
        return null;
    }

    return [
        'href' => $href !== '' ? $href : '#',
        'target' => $target,
        'rel' => $rel,
    ];
};

$renderMenuItem = null;
$renderMenuBranch = null;

$renderMenuItem = function (int $itemId, bool $asRootItem = false) use (&$renderMenuItem, &$renderMenuBranch, $menuItemsById, $hasMenuItemAccess, $resolveMenuItemLink): string {
    if (empty($menuItemsById[$itemId])) {
        return '';
    }

    $menuItem = $menuItemsById[$itemId];
    if (!$hasMenuItemAccess($menuItem)) {
        return '';
    }

    $link = $resolveMenuItemLink($menuItem);
    if ($link === null) {
        return '';
    }

    $childrenHtml = $renderMenuBranch($menuItem->getId(), false);
    $liClasses = [$asRootItem ? 'elegant-nav-item' : 'elegant-subnav-item'];

    if ($childrenHtml !== '') {
        $liClasses[] = 'has-children';
    }

    if ($link['href'] === $this->getCurrentUrl()) {
        $liClasses[] = 'is-active';
    }

    $html = '<li class="' . implode(' ', $liClasses) . '">';
    $html .= '<a class="elegant-nav-link" href="' . $this->escape($link['href']) . '"' . $link['target'] . $link['rel'] . '>';
    $html .= $this->escape($menuItem->getTitle());
    $html .= '</a>';
    $html .= $childrenHtml;
    $html .= '</li>';

    return $html;
};

$renderMenuBranch = function (int $parentId, bool $isRoot = false) use (&$renderMenuBranch, &$renderMenuItem, $menuTree, $menuItemsById, $showRootMenuItems): string {
    if (empty($menuTree[$parentId])) {
        return '';
    }

    $html = $isRoot ? '<ul class="elegant-nav-list">' : '<ul class="elegant-subnav">';

    foreach ($menuTree[$parentId] as $itemId) {
        if (empty($menuItemsById[$itemId])) {
            continue;
        }

        if ($isRoot && !$showRootMenuItems && !empty($menuTree[$itemId])) {
            foreach ($menuTree[$itemId] as $childItemId) {
                $html .= $renderMenuItem($childItemId, true);
            }
            continue;
        }

        $html .= $renderMenuItem($itemId, $isRoot);
    }

    $html .= '</ul>';

    return $html;
};

$navHtml = $renderMenuBranch(0, true);
?>
<!DOCTYPE html>
<html lang="de" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <?=$this->getHeader() ?>
    <link href="<?=$this->getVendorUrl('twbs/bootstrap/dist/css/bootstrap.min.css') ?>" rel="stylesheet">
    <link href="<?=$this->getLayoutUrl('assets/css/ckeditor-frontend.css') ?>" rel="stylesheet">
    <link href="<?=$this->getLayoutUrl('assets/css/global.css') ?>" rel="stylesheet">
    <link href="<?=$this->getLayoutUrl('assets/css/style.css') ?>" rel="stylesheet">
    <?=$this->getCustomCSS() ?>
    <script src="<?=$this->getVendorUrl('twbs/bootstrap/dist/js/bootstrap.bundle.min.js') ?>"></script>
    <style>
        :root {
            --elegant-accent: <?=$this->getLayoutSetting('accentColor') ?>;
            --elegant-accent-soft: <?=$this->getLayoutSetting('accentSoftColor') ?>;
            --elegant-page-max-width: <?=trim((string) $this->getLayoutSetting('contentMaxWidth')) ?: '1480px' ?>;
        }
    </style>
</head>
<body class="elegant-theme">
<div class="elegant-shell">
    <div class="elegant-topbar">
        <div class="elegant-container elegant-topbar-inner">
            <div class="elegant-topbar-copy"><?=$this->escape($topBarText) ?></div>
            <?=$socialTopbarHtml ?>
        </div>
    </div>

    <header class="elegant-header<?=$headerMainSticky ? ' is-sticky-enabled' : '' ?>" data-elegant-header>
        <div class="elegant-header-main<?=$headerMainSticky ? ' is-sticky' : '' ?>">
            <div class="elegant-container elegant-header-main-inner">
                <a class="elegant-logo" href="<?=$this->getUrl() ?>">
                    <?php if ($siteLogo !== ''): ?>
                        <img src="<?=$this->getBaseUrl($siteLogo) ?>" alt="<?=$this->escape($siteName) ?>">
                    <?php else: ?>
                        <span class="elegant-logo-wordmark"><?=$this->escape($siteName !== '' ? $siteName : 'Elegant*') ?></span>
                    <?php endif; ?>
                </a>

                <button class="elegant-nav-toggle" type="button" data-elegant-nav-toggle aria-label="Navigation">
                    <span></span><span></span><span></span>
                </button>

                <nav class="elegant-nav" data-elegant-nav>
                    <?=$navHtml ?>
                </nav>
            </div>
        </div>
    </header>

    <main class="elegant-main">
        <?php if ($isElegantLandingPage): ?>
            <?=$contentMarkup ?>
        <?php elseif ($isLayoutHomePage): ?>
            <?php if ($sliderBoxHtml !== ''): ?>
                <?=$sliderBoxHtml ?>
            <?php endif; ?>

            <?php if ($introBoxHtml !== ''): ?>
                <?=$introBoxHtml ?>
            <?php endif; ?>

            <?php if ($platformCardsBoxHtml !== ''): ?>
                <?=$platformCardsBoxHtml ?>
            <?php endif; ?>

            <?php if ($featureCardsBoxHtml !== ''): ?>
                <?=$featureCardsBoxHtml ?>
            <?php endif; ?>

            <?php if ($newsBoxHtml !== ''): ?>
                <?=$newsBoxHtml ?>
            <?php endif; ?>

            <section class="elegant-content-zone">
                <div class="elegant-container">
                    <div class="elegant-columns<?=$hasSideColumn ? '' : ' elegant-columns-full' ?>">
                        <div class="elegant-main-column">
                            <h2><em>About Us</em></h2>

                            <div class="elegant-panel">
                                <div class="elegant-panel-content">
                                    <?php if ($hasContent): ?>
                                        <?=$contentMarkup ?>
                                    <?php else: ?>
                                        <div class="elegant-placeholder-copy">
                                            <p><?=$this->escape($siteTagline !== '' ? $siteTagline : 'Elegant* is ready for your pages, articles and custom module output.') ?></p>
                                            <p>This section automatically shows the page or module content provided by Ilch. If you assign a start page or frontend module, its output appears here in the new elegant-style frame.</p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <?php if ($hasSideColumn): ?>
                        <aside class="elegant-side-column">
                            <?=$videoWidgetHtml ?>

                            <?=$sidebarBoxes ?>
                        </aside>
                        <?php endif; ?>
                    </div>
                </div>
            </section>
        <?php else: ?>
            <?php if ($sliderBoxHtml !== ''): ?>
                <?=$sliderBoxHtml ?>
            <?php endif; ?>

            <section class="elegant-page-hero">
                <?php if ($pageHeroBackgroundImageUrl !== ''): ?>
                    <div class="elegant-page-hero-media" style="background-image: url('<?=$this->escape($pageHeroBackgroundImageUrl) ?>');"></div>
                <?php endif; ?>
                <div class="elegant-container">
                    <div class="elegant-page-hero-inner">
                        <p class="elegant-page-kicker"><?=$this->escape($siteName !== '' ? $siteName : 'Elegant*') ?></p>
                        <h1><?=$this->escape($pageHeroTitle) ?></h1>
                        <div class="elegant-breadcrumbs"><?=$breadcrumbHtml ?></div>
                    </div>
                </div>
            </section>

            <?php if ($introBoxHtml !== ''): ?>
                <?=$introBoxHtml ?>
            <?php endif; ?>

            <?php if ($platformCardsBoxHtml !== ''): ?>
                <?=$platformCardsBoxHtml ?>
            <?php endif; ?>

            <?php if ($featureCardsBoxHtml !== ''): ?>
                <?=$featureCardsBoxHtml ?>
            <?php endif; ?>

            <?php if ($newsBoxHtml !== ''): ?>
                <?=$newsBoxHtml ?>
            <?php endif; ?>

            <section class="elegant-content-zone">
                <div class="elegant-container">
                    <div class="elegant-columns<?=$hasSideColumn ? '' : ' elegant-columns-full' ?>">
                        <div class="elegant-main-column">
                            <div class="elegant-panel">
                                <div class="elegant-panel-content">
                                    <?php if ($hasContent): ?>
                                        <?=$contentMarkup ?>
                                    <?php else: ?>
                                        <div class="elegant-placeholder-copy">
                                            <p><?=$this->escape($siteTagline !== '' ? $siteTagline : 'Elegant* is ready for your pages, articles and custom module output.') ?></p>
                                            <p>This section automatically shows the page or module content provided by Ilch. If you assign a start page or frontend module, its output appears here in the new elegant-style frame.</p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <?php if ($hasSideColumn): ?>
                        <aside class="elegant-side-column">
                            <?=$videoWidgetHtml ?>

                            <?=$sidebarBoxes ?>
                        </aside>
                        <?php endif; ?>
                    </div>
                </div>
            </section>
        <?php endif; ?>
    </main>

    <footer class="elegant-footer">
        <div class="elegant-container">
            <div class="elegant-footer-grid">
                <div class="elegant-footer-block">
                    <h3>Social</h3>
                    <?php if ($socialFooterHtml !== ''): ?>
                        <?=$socialFooterHtml ?>
                    <?php else: ?>
                        <p>Use the Elegant* settings to define your social channels.</p>
                    <?php endif; ?>
                </div>

                <div class="elegant-footer-block">
                    <h3><?=$this->escape($siteName !== '' ? $siteName : 'Elegant*') ?></h3>
                    <p><?=$this->escape($siteTagline !== '' ? $siteTagline : 'A clean and elegant presentation for communities, magazines and premium brand sites.') ?></p>
                </div>

                <div class="elegant-footer-block">
                    <?=$footerBlockThreeHtml ?>
                </div>

                <div class="elegant-footer-block">
                    <?=$footerBlockFourHtml ?>
                </div>
            </div>

            <div class="elegant-footer-bottom">
                <div><?=$this->escape($copyrightText) ?></div>
                <div class="elegant-footer-links">
                    <a href="<?=$this->getBaseUrl('index.php') ?>">Home</a>
                    <a href="<?=$moduleLandingUrl ?>">Elegant*</a>
                </div>
            </div>
        </div>
    </footer>

    <button class="elegant-backtop" type="button" data-elegant-backtop aria-label="Back to top">
        <i class="fa-solid fa-arrow-up"></i>
    </button>
</div>

<?=$this->getFooter() ?>
<script>window.jQuery || document.write('<script src="<?=$this->getVendorUrl('npm-asset/jquery/dist/jquery.min.js') ?>">\x3C/script>')</script>
<script src="<?=$this->getLayoutUrl('assets/js/main.js') ?>"></script>
</body>
</html>
