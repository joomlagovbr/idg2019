/**
 * @copyright  Copyright (C) 2013 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

if (!Joomla) {
    throw new Error('Joomla API is not properly initialised');
}

Joomla.apps = {
    view: "dashboard",
    id: 0,
    ordering: "",
    cssfiles: [],
    jsfiles: [],
    list: 0,
    loaded: 0,
    update: false,
    options: Joomla.getOptions('plg_installer_webinstaller', {}),
};

// The following global vars are deprecated, they are retained for compatibility with the IFW server where the markup is rendered
var apps_base_url = Joomla.apps.options.base_url;

Joomla.loadweb = function (url) {
    if ('' == url) {
        return false;
    }

    var pattern1 = new RegExp(Joomla.apps.options.base_url);
    var pattern2 = new RegExp("^index\.php");

    if (!(pattern1.test(url) || pattern2.test(url))) {
        window.open(url, "_blank");

        return false;
    }

    url += '&product=' + Joomla.apps.options.product + '&release=' + Joomla.apps.options.release + '&dev_level=' + Joomla.apps.options.dev_level + '&list=' + (Joomla.apps.list ? 'list' : 'grid') + '&pv=' + Joomla.apps.options.pv;

    var ordering = Joomla.apps.ordering;

    if (ordering !== "" && jQuery('#com-apps-ordering').val()) {
        ordering = jQuery('#com-apps-ordering').val();
        url += '&ordering=' + ordering;
    }

    jQuery('html, body').animate({scrollTop: 0}, 0);

    if (jQuery('#myTabContent').length) {
        Joomla.loadingLayer('show', jQuery('#myTabContent')[0]);
    }

    jQuery.ajax({
        url: url,
        dataType: 'jsonp',
        cache: true,
        jsonpCallback: 'jedapps_jsonpcallback',
        timeout: 20000,
        success: function (response) {
            jQuery('#web-loader').hide();
            jQuery('#jed-container').html(response.data.html);

            if (!Joomla.apps.update && response.data.pluginuptodate < 1) {
                Joomla.apps.update = true;
                var txt = Joomla.JText._('COM_INSTALLER_WEBINSTALLER_INSTALL_OBSOLETE');
                var btn = Joomla.JText._('JLIB_INSTALLER_UPDATE');

                if (response.data.pluginuptodate == 0) {
                    txt = Joomla.JText._('COM_INSTALLER_WEBINSTALLER_INSTALL_UPDATE_AVAILABLE');
                }

                if (Joomla.apps.options.is_hathor) {
                    jQuery('#element-box').prepend(jQuery('<dl id="system-message"><dt class="info">info</dt><dd class="info message"><ul><li>' + txt + '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input class="btn" type="button" value="' + btn + '" onclick="Joomla.submitbuttonInstallWebInstaller()" /></li></ul></dd></dl>'));
                } else {
                    jQuery('#web').prepend(jQuery('<div class="alert alert-info j-jed-message" style="margin-bottom: 20px; line-height: 2em; color:#333333;">' + txt + '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input class="btn" type="button" value="' + btn + '" onclick="Joomla.submitbuttonInstallWebInstaller()" /></div>'));
                }
            }

            Joomla.apps.hideLoadingLayer();
        },
        fail: function () {
            jQuery('#web-loader').hide();
            jQuery('#web-loader-error').show();

            Joomla.apps.hideLoadingLayer();
        },
        complete: function () {
            if (jQuery('#joomlaapsinstallatinput')) {
                jQuery('#joomlaapsinstallatinput').val(Joomla.apps.options.installat_url);
            }

            Joomla.apps.clickforlinks();
            Joomla.apps.clicker();

            if (Joomla.apps.list) {
                jQuery(".list-view").click();
            }

            Joomla.apps.hideLoadingLayer();
        },
        error: function (request, status, error) {
            if (request.responseText) {
                jQuery('#web-loader-error').html(request.responseText);
            }

            jQuery('#web-loader').hide();
            jQuery('#web-loader-error').show();

            Joomla.apps.hideLoadingLayer();
        }
    });

    return true;
};

Joomla.webpaginate = function (url, target) {
    jQuery('#web-paginate-loader').show();

    jQuery.get(url, function (response) {
        jQuery('#web-paginate-loader').hide();
        jQuery('#' + target).html(response.data.html);
    }, 'jsonp').fail(function () {
        jQuery('#web-paginate-loader').hide();
        //jQuery('#web-paginate-error').hide();
    });
};

Joomla.installfromwebexternal = function (redirect_url) {
    var redirect_confirm = confirm(Joomla.JText._('PLG_INSTALLER_WEBINSTALLER_REDIRECT_TO_EXTERNAL_SITE_TO_INSTALL').replace('[SITEURL]', redirectUrl));

    if (true == redirect_confirm) {
        jQuery('#adminForm').attr('action', redirect_url);
        jQuery("input[name=task]").prop("disabled", true);
        jQuery("input[name=install_directory]").prop("disabled", true);
        jQuery("input[name=install_url]").prop("disabled", true);
        jQuery("input[name=installtype]").prop("disabled", true);
        jQuery("input[name=filter_search]").prop("disabled", true);

        return true;
    }

    return false;
};

Joomla.installfromweb = function (install_url, name) {
    if ('' == install_url) {
        alert(Joomla.JText._('PLG_INSTALLER_WEBINSTALLER_CANNOT_INSTALL_EXTENSION_IN_PLUGIN'));

        return false;
    }

    jQuery('#install_url').val(install_url);
    jQuery('#uploadform-web-url').text(install_url);

    if (name) {
        jQuery('#uploadform-web-name').text(name);
        jQuery('#uploadform-web-name-label').show();
    } else {
        jQuery('#uploadform-web-name-label').hide();
    }

    jQuery('#jed-container').slideUp(300);
    jQuery('#uploadform-web').show();

    return true;
};

Joomla.installfromwebcancel = function () {
    jQuery('#uploadform-web').hide();
    jQuery('#jed-container').slideDown(300);

    if (Joomla.apps.list) {
        jQuery(".list-view").click();
    }
};

Joomla.installfromwebajaxsubmit = function () {
    var tail = '&view=' + Joomla.apps.view;

    if (Joomla.apps.id) {
        tail += '&id=' + Joomla.apps.id;
    }

    if (jQuery('#com-apps-searchbox').val()) {
        var value = encodeURI(jQuery('#com-apps-searchbox').val().toLowerCase().replace(/ +/g, '_').replace(/[^a-z0-9-_]/g, '').trim());
        tail += '&filter_search=' + value;
    }

    var ordering = Joomla.apps.ordering;

    if (ordering !== "" && jQuery('#com-apps-ordering').val()) {
        ordering = jQuery('#com-apps-ordering').val();
    }

    if (ordering) {
        tail += '&ordering=' + ordering;
    }

    Joomla.loadweb(Joomla.apps.options.base_url + 'index.php?format=json&option=com_apps' + tail);
};

Joomla.apps.clickforlinks = function () {
    jQuery('a.transcode').each(function (index, value) {
        var ajaxurl = jQuery(this).attr('href');

        (function () {
            var ajax_url = ajaxurl;

            jQuery(value).live('click', function (event) {
                var pattern1 = new RegExp(Joomla.apps.options.base_url);
                var pattern2 = new RegExp("^index\.php");

                if (pattern1.test(ajax_url) || pattern2.test(ajax_url)) {
                    Joomla.apps.view = ajax_url.replace(/^.+[&\?]view=(\w+).*$/, '$1');

                    if (Joomla.apps.view == 'dashboard') {
                        Joomla.apps.id = 0;
                    } else if (Joomla.apps.view == 'category') {
                        Joomla.apps.id = ajax_url.replace(/^.+[&\?]id=(\d+).*$/, '$1');
                    }

                    event.preventDefault();
                    Joomla.loadweb(Joomla.apps.options.base_url + ajax_url);
                } else {
                    event.preventDefault();
                    Joomla.loadweb(ajax_url);
                }
            });
        })();

        jQuery(this).attr('href', '#');
    });
};

Joomla.apps.initialize = function () {
    Joomla.apps.loaded = 1;

    Joomla.loadweb(Joomla.apps.options.base_url + 'index.php?format=json&option=com_apps&view=dashboard');

    Joomla.apps.clickforlinks();

    jQuery('#com-apps-searchbox').live('keypress', function (event) {
        if (event.which == 13) {
            Joomla.apps.initiateSearch();
        }
    });

    jQuery('#search-reset').live('click', function (event) {
        jQuery('#com-apps-searchbox').val('');
        Joomla.apps.initiateSearch();
    });

    jQuery('#com-apps-ordering').live('change', function (event) {
        Joomla.apps.ordering = jQuery(this).prop("selectedIndex");
        Joomla.installfromwebajaxsubmit();
    });

    if (Joomla.apps.options.installfrom_url != '') {
        Joomla.installfromweb(Joomla.apps.options.installfrom_url);
    }
};

Joomla.apps.initiateSearch = function () {
    Joomla.apps.view = 'dashboard';
    Joomla.installfromwebajaxsubmit();
};

Joomla.apps.clicker = function () {
    jQuery(".grid-view").live("click", function () {
        Joomla.apps.list = 0;
        jQuery(".list-container").addClass("hidden");
        jQuery(".grid-container").removeClass("hidden");
        jQuery("#btn-list-view").removeClass("active");
        jQuery("#btn-grid-view").addClass("active");
    });
    jQuery(".list-view").live("click", function () {
        Joomla.apps.list = 1;
        jQuery(".grid-container").addClass("hidden");
        jQuery(".list-container").removeClass("hidden");
        jQuery("#btn-grid-view").removeClass("active");
        jQuery("#btn-list-view").addClass("active");
    });
};

Joomla.apps.hideLoadingLayer = function () {
    if (jQuery('#myTabContent').length) {
        Joomla.loadingLayer('hide', jQuery('#myTabContent')[0]);
    }
};

Joomla.submitbutton5 = function (pressbutton) {
    var form = document.getElementById('adminForm');

    // do field validation
    if (form.install_url.value != "" && form.install_url.value != "http://") {
        Joomla.submitbutton4();
    } else if (form.install_url.value == "") {
        alert(Joomla.JText._('COM_INSTALLER_MSG_INSTALL_ENTER_A_URL'));
    } else {
        if (!Joomla.apps.options.is_hathor) {
            jQuery('#appsloading').css('display', 'block');
        }

        form.installtype.value = 'web';
        form.submit();
    }
};
