BE_THEME = {
    CustomizerOverride: {
        init: function () {
            /**
             * Redaxo Customizer override
             * Redaxo > System > Customizer > Erkennungsfarbe
             */

            if (typeof rex.customizer_labelcolor !== "undefined" && rex.customizer_labelcolor != '') {
                $('.rex-nav-top').css('background-color', rex.customizer_labelcolor)
            }
        }
    },
    PageNavigation: {
        init: function () {
            /**
             * Prettify page navigation
             * @type {jQuery.fn.init|jQuery|HTMLElement}
             */
            var $structureHeaderNavigation = $('.rex-page-header .rex-page-nav');
            if ($($structureHeaderNavigation).has('.navbar').length < 1 ) {
                $structureHeaderNavigation.addClass('cs-rex-light')
            }
        }
    },
    Tooltips: {
        init: function() {
            $('.rex-nav-meta .navbar-right li').each(function () {
                var $a = $( this ).find('a');
                var text = $a.text();

                if (text === undefined || text === '' || text === null) {
                    text = $( this ).find('span').text();
                }

                //$a.attr('data-tippy', text);
                tippy($a[0], {
                    content: text,
                    delay: 100,
                    arrow: true,
                    arrowType: 'round',
                    size: 'large',
                    duration: 500,
                    animation: 'scale'
                })
            });

            $('#rex-js-nav-main .rex-nav-main-list li').each(function () {
                var $a = $( this ).find('a');
                //$a.attr('data-tippy-placement', 'right');
                //$a.attr('data-tippy', $a.text());

                tippy($a[0], {
                    content: $a.text(),
                    delay: 100,
                    arrow: true,
                    arrowType: 'round',
                    size: 'large',
                    duration: 500,
                    animation: 'scale',
                    placement: 'right'
                })
            });
        }
    },
    Navigation: {
        hamburger: function () {
            var hamburger = '';

            hamburger += '<div id="cs-toggle-rex-nav" data-tippy="Navigation" class="hamburger hamburger--arrow js-hamburger">';
            hamburger += '<div class="hamburger-box">';
            hamburger += '<div class="hamburger-inner"></div>';
            hamburger += '</div>';
            hamburger += '</div>';

            return hamburger;
        },
        init: function () {
            var $existsElement = $('#cs-toggle-rex-nav');

            if (!$existsElement.length > 0) {
                var $wrapper = $('.rex-nav-top .navbar-header').append(this.hamburger());
                var $hamburger = $wrapper.find('#cs-toggle-rex-nav');

                if(!Cookies.get('cs-nav')) {
                    $hamburger.addClass('is-active');
                } else {
                    $hamburger.removeClass('is-active');
                }

                $hamburger.on('click', function (event) {
                    if(!Cookies.get('cs-nav')) {

                        Cookies.set('cs-nav', 'toggeld');

                        $('body').attr('data-rex-nav-hide', 'true');
                        $hamburger.removeClass('is-active');
                    } else {

                        Cookies.remove('cs-nav');

                        $('body').attr('data-rex-nav-hide', 'false');
                        $hamburger.addClass('is-active');
                    }
                });
            }
        }
    },
    Search: {
        /* Vorlage für Eingabefeld im Header */
        template: function () {
            var template = '';

            template += '<div id="cs-header-search">';
            template += '<input type="text" placeholder="Suche"/>';
            template += '</div>';

            return template;
        },
        init: function () {
            var $wrapper = $('.rex-nav-top .navbar-header').append(this.template());
        }
    },
    Page: {
        init: function () {
            var $mainFrame = $('#rex-page-content-edit');

            if ($mainFrame.length > 0) {

                $mainFrame.find('.rex-main-frame > .row .col-lg-8').removeClass('col-lg-8').addClass('col-lg-12');
                $mainFrame.find('.rex-main-frame > .row .col-lg-4').remove();
            }
        },
        initSmoothPage: function () {
            $('.rex-page-main').addClass('active');
        }
    }
};

$(document).on('rex:ready', function (event, container) {
    BE_THEME.Page.init();
    BE_THEME.CustomizerOverride.init();
    BE_THEME.PageNavigation.init();
    BE_THEME.Tooltips.init();
    BE_THEME.Navigation.init();
});

$(document).ready(function () {
    BE_THEME.Page.initSmoothPage();

    $(document).on('rex:ready', function (event, container) {
        BE_THEME.Page.initSmoothPage();
    });
});





