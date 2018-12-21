<?php

$mypage = 'redaxo';

if (rex::isBackend()) {
    rex_extension::register('BE_STYLE_SCSS_FILES', function (rex_extension_point $ep) use ($mypage) {
        $subject = $ep->getSubject();
        $file = rex_plugin::get('be_style', $mypage)->getPath('scss/default.scss');
        array_unshift($subject, $file);
        return $subject;
    }, rex_extension::EARLY);

    if (rex::getUser() && $this->getProperty('compile')) {
        rex_addon::get('be_style')->setProperty('compile', true);

        rex_extension::register('PACKAGES_INCLUDED', function () {
            $compiler = new rex_scss_compiler();
            $compiler->setRootDir($this->getPath('scss/'));
            $compiler->setScssFile($this->getPath('scss/master.scss'));

            // Compile in backend assets dir
            $compiler->setCssFile($this->getPath('assets/css/styles.css'));

            $compiler->compile();

            // Compiled file to copy in frontend assets dir
            rex_file::copy($this->getPath('assets/css/styles.css'), $this->getAssetsPath('css/styles.css'));
        });
    }

    rex_view::addCssFile($this->getAssetsUrl('css/styles.css'));
    rex_view::addJsFile($this->getAssetsUrl('javascripts/redaxo.js'));

    rex_view::addJsFile($this->getAssetsUrl('libs/cookie.js'));
    rex_view::addJsFile($this->getAssetsUrl('libs/tippy.all.min.js'));
    rex_view::addJsFile($this->getAssetsUrl('javascripts/be_theme.js'));

    rex_extension::register('PAGE_HEADER', function (rex_extension_point $ep) {
        $icons = [];
        foreach (['57', '60', '72', '76', '114', '120', '144', '152'] as $size) {
            $size = $size . 'x' . $size;
            $icons[] = '<link rel="apple-touch-icon-precomposed" sizes="' . $size . '" href="' . $this->getAssetsUrl('images/apple-touch-icon-' . $size . '.png') . '" />';
        }
        foreach (['16', '32', '96', '128', '196'] as $size) {
            $size = $size . 'x' . $size;
            $icons[] = '<link rel="icon" type="image/png" href="' . $this->getAssetsUrl('images/favicon-' . $size . '.png') . '" sizes="' . $size . '" />';
        }

        $icons[] = '<meta name="msapplication-TileColor" content="#FFFFFF" />';
        $icons[] = '<meta name="msapplication-TileImage" content="' . $this->getAssetsUrl('images/mstile-144x144.png') . '" />';

        foreach (['70', '150', '310'] as $size) {
            $size = $size . 'x' . $size;
            $icons[] = '<meta name="msapplication-square' . $size . 'logo" content="' . $this->getAssetsUrl('images/mstile-' . $size . '.png') . '" />';
        }
        $icons[] = '<meta name="msapplication-wide310x150logo" content="' . $this->getAssetsUrl('images/mstile-310x150.png') . '" />';

        $icons = implode("\n    ", $icons);
        $ep->setSubject($icons . $ep->getSubject());
    });

    /**
     * Replace header branding
     */
    rex_extension::register('OUTPUT_FILTER', function (rex_extension_point $ep) {

        $logo = "<img src='". $this->getAssetsUrl('images/logo.png') ."' alt='Logo'>";
        $suchmuster = '<a class="navbar-brand" href="index.php"><img class="rex-js-svg rex-redaxo-logo" src="../assets/core/redaxo-logo.svg" /></a>';
        $ersetzen = '<a class="navbar-brand" href="index.php">'. $logo .'</a>';

        $ep->setSubject(str_replace($suchmuster, $ersetzen, $ep->getSubject()));

    });

    /**
     * Clearly serverside navigation hide
     */
    if (!empty(rex_cookie('cs-nav', 'string'))) {
        rex_extension::register('PAGE_BODY_ATTR', function (rex_extension_point $ep) {
            $attrs = $ep->getSubject();
            $attrs['data-rex-nav-hide'] = ['true'];
            $ep->setSubject($attrs);
        });
    }

    /**
     * Support for Customizer
     * Clearly prevent add showlink from customizer (position change)
     */
    if (rex_addon::get('be_style')->getPlugin('customizer')->getConfig('showlink')) {
        rex_view::setJsProperty('customizer_showlink', '');

        rex_extension::register('META_NAVI', function (rex_extension_point $ep) {

            $subject = $ep->getSubject();
            $button = '<li><span class="text-muted">'. rex::getServerName() .'</span><a class="rex-website" href="'. rex::getServer() .'" target="_blank" rel="noreferrer noopener" title="zur Website"><i class="rex-icon fa-home"></i></a></li>';

            array_unshift( $subject, $button);
            $ep->setSubject($subject);
        });
    }

    /**
     * Support for quick_navigation AddOn
     */
    if (rex_addon::get('quick_navigation')->isAvailable()) {
        rex_extension::register('META_NAVI', function (rex_extension_point $ep) {
            $subject = $ep->getSubject();

            if (rex_be_controller::getCurrentPageObject()->isPopup()) {
                return $subject;
            }

            $clang = rex_request('clang', 'int');
            $clang = rex_clang::exists($clang) ? $clang : rex_clang::getStartId();
            $category_id = rex_request('category_id', 'int');
            $article_id = rex_request('article_id', 'int');

            $params = [
                'clang' => $clang,
                'category_id' => $category_id,
                'article_id' => $article_id
            ];

            $button = '<li><div id="rex-quicknavigation-structure" data-url="'. rex_url::currentBackendPage($params + rex_api_quicknavigation_render::getUrlParams()) .'"></div></li>';

            array_unshift( $subject, $button);
            $ep->setSubject($subject);
        });
    }
}
