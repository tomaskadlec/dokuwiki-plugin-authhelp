<?php
/**
 * DokuWiki Plugin authhelp (Action Component)
 *
 * Intercepts login page rendering. Creates a container around the login form
 * and optionaly login help area. Login help area It help page
 * is configured it is rentdere
 *
 * @author  Tomas Kadlec http://www.tomaskadlec.net
 * @license https://opensource.org/licenses/MIT The MIT License
 * @link  https://github.com/tomaskadlec/dokuwiki-plugin-authhelp Homepage
 */

// must be run within Dokuwiki
if (! defined('DOKU_INC'))
    die();

if (! defined('DOKU_LF'))
    define('DOKU_LF', "\n");
if (! defined('DOKU_TAB'))
    define('DOKU_TAB', "\t");
if (! defined('DOKU_PLUGIN'))
    define('DOKU_PLUGIN', DOKU_INC . 'lib/plugins/');

require_once DOKU_PLUGIN . 'action.php';


class action_plugin_authhelp extends DokuWiki_Action_Plugin
{
    const CONF_HELP_PAGE = 'help_page';

    const CONF_RENAME_LOCAL = 'rename_local';

    public function register(Doku_Event_Handler &$controller)
    {
        $controller->register_hook('HTML_LOGINFORM_OUTPUT', 'BEFORE', $this, 'alterLoginPageBefore');
        $controller->register_hook('HTML_LOGINFORM_OUTPUT', 'AFTER', $this, 'alterLoginPageAfter');
    }

    /**
     * Alters login page via HTML_LOGINFORM_OUTPUT event
     * @param $event
     * @param $param
     */
    public function alterLoginPageBefore($event, $param)
    {
        print '<div class="login container">'.NL;

        $helpId = $this->getConf(self::CONF_HELP_PAGE);

        global $conf;
        if (!empty($conf['lang'])) {
            $lang = $conf['lang'];
            if (!empty($conf['plugin']['translation']['translations'])
                && preg_match("/$lang/", $conf['plugin']['translation']['translations']))
                $helpId = ':' . $lang . $helpId;
        }

        if (page_exists($helpId)) {
            print '<div class="login help">' . p_wiki_xhtml($helpId) . '</div>' .NL;
        }

        if (!empty($this->getConf(self::CONF_RENAME_LOCAL))) {
            /** @var Doku_Form $form */
            $form = $event->data;
            $form->_content[0]['_legend'] = $this->getLang('login_local');
        }
    }

    /**
     * Alters login page via HTML_LOGINFORM_OUTPUT event
     * @param $event
     * @param $param
     */
    public function alterLoginPageAfter($event, $param)
    {
        print '</div>' . NL;
    }

}