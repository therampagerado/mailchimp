<?php
/**
 * 2017 Thirty Bees
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@thirtybees.com so we can send you a copy immediately.
 *
 * @author    Thirty Bees <modules@thirtybees.com>
 * @copyright 2017 Thirty Bees
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

require_once dirname(__FILE__) . '/lib/autoload.php';
require_once dirname(__FILE__) . '/classes/MailChimpSubscriber.php';

class MailChimp extends Module
{
    const KEY_API_KEY = 'MAILCHIMP_API_KEY';
    const KEY_IMPORT_LIST = 'MAILCHIMP_IMPORT_LIST';
    const KEY_CONFIRMATION_EMAIL = 'MAILCHIMP_CONFIRMATION_EMAIL';
    const KEY_UPDATE_EXISTING = 'MAILCHIMP_UPDATE_EXISTING';
    const KEY_IMPORT_ALL = 'MAILCHIMP_IMPORT_ALL';
    const KEY_IMPORT_OPTED_IN = 'MAILCHIMP_IMPORT_OPTED_IN';
    const KEY_LAST_IMPORT = 'MAILCHIMP_LAST_IMPORT';

    private $_mailChimpLanguages = array(
        'en' => 'en',
        'ar' => 'ar',
        'af' => 'af',
        'be' => 'be',
        'bg' => 'bg',
        'ca' => 'ca',
        'zh' => 'zh',
        'hr' => 'hr',
        'cs' => 'cs',
        'da' => 'da',
        'nl' => 'nl',
        'et' => 'et',
        'fa' => 'fa',
        'fi' => 'fi',
        'fr' => 'fr',
        'qc' => 'fr_CA',
        'de' => 'de',
        'el' => 'el',
        'he' => 'he',
        'hi' => 'hi',
        'hu' => 'hu',
        'is' => 'is',
        'id' => 'id',
        'ga' => 'ga',
        'it' => 'it',
        'ja' => 'ja',
        'km' => 'km',
        'ko' => 'ko',
        'lv' => 'lv',
        'lt' => 'lt',
        'mt' => 'mt',
        'ms' => 'ms',
        'mk' => 'mk',
        'no' => 'no',
        'pl' => 'pl',
        'br' => 'pt',
        'pt' => 'pt_PT',
        'ro' => 'ro',
        'ru' => 'ru',
        'sr' => 'sr',
        'sk' => 'sk',
        'si' => 'sl',
        'mx' => 'es',
        'es' => 'es',
        'sw' => 'sw',
        'sv' => 'sv',
        'ta' => 'ta',
        'th' => 'th',
        'tr' => 'tr',
        'uk' => 'uk',
        'vi' => 'vi',
        'gb' => 'en',
    );
    private $_html = '';
    private $_idShop;

    public function __construct()
    {
        $this->name = 'mailchimp';
        $this->tab = 'advertising_marketing';
        $this->version = '1.0.0';
        $this->author = 'Thirty Bees';
        $this->need_instance = 0;
        $this->bootstrap = true;
        $this->ps_versions_compliancy = array(
            'min' => '1.5',
            'max' => _PS_VERSION_,
        );

        parent::__construct();

        $this->displayName = $this->l('MailChimp');
        $this->description = $this->l('Synchronize with MailChimp');
        // TODO: This can be asked (i.e. whether to import all shops)
        $this->_idShop = (int)Context::getContext()->shop->id;
    }

    public function install()
    {
        if (
            !parent::install()
            || !$this->registerHook('displayHome')
            || !$this->registerHook('displayHeader')
            || !$this->registerHook('displayBackOfficeHeader')
            || !$this->registerHook('displayAdminHomeQuickLinks')
        ) {
            return false;
        }
        return true;
    }

    public function uninstall()
    {
        if (
            !parent::uninstall()
            || !Configuration::deleteByName('KEY_API_KEY')
            || !Configuration::deleteByName('KEY_IMPORT_LIST')
            || !Configuration::deleteByName('KEY_CONFIRMATION_EMAIL')
            || !Configuration::deleteByName('KEY_UPDATE_EXISTING')
            || !Configuration::deleteByName('KEY_IMPORT_ALL')
            || !Configuration::deleteByName('KEY_IMPORT_OPTED_IN')
            || !Configuration::deleteByName('KEY_LAST_IMPORT')

        ) {
            return false;
        }
        return true;
    }

    public function getContent()
    {
        $this->_postProcess();
        $this->_displayForm();
        return $this->_html;
    }

    private function _postProcess()
    {
        if (Tools::isSubmit('submitApiKey')) {
            // Check if MailChimp API key is valid
            try {
                // TODO: Find a different way to validate API key rather than making a request
                $mailchimp = new \ThirtyBees\MailChimp\MailChimp(Tools::getValue('mailchimpApiKey'));
                $mailchimp->verifySsl = false;
                $getLists = $mailchimp->get('lists');
                $update = Configuration::updateValue('KEY_API_KEY', Tools::getValue('mailchimpApiKey'));
                if (!$getLists) {
                    $this->_html .= $this->displayError($this->l('An error occurred. Please check your API key.'));
                } else {
                    if ($update) {
                        $this->_html .= $this->displayConfirmation($this->l('You have successfully updated your MailChimp API key.'));
                    } else {
                        $this->_html .= $this->displayError($this->l('An error occurred while saving API key.'));
                    }
                }
            } catch (Exception $e) {
                // Remove existing value
                Configuration::deleteByName('KEY_API_KEY');
                $this->_html .= $this->displayError($e->getMessage());
            }
        } else if (Tools::isSubmit('submitSettings')) {
            // Update all the configuration
            // And check if updates were successful
            if (
                Configuration::updateValue('KEY_IMPORT_LIST', Tools::getValue('importList'))
                && Configuration::updateValue('KEY_CONFIRMATION_EMAIL', Tools::getValue('confirmationEmail'))
                && Configuration::updateValue('KEY_UPDATE_EXISTING', Tools::getValue('updateExisting'))
                && Configuration::updateValue('KEY_IMPORT_ALL', Tools::getValue('importAll'))
                && Configuration::updateValue('KEY_IMPORT_OPTED_IN', Tools::getValue('importOptedIn'))
            ) {
                $this->_html .= $this->displayConfirmation($this->l('Settings updated.'));
                // Check if asked for a manual import
                if (Tools::isSubmit('manualImport_0') && (bool)Tools::getValue('manualImport_0')) {
                    $import = true; // TODO: Make the import
                    $error = '';
                    if ($import) {
                        $this->_html .= $this->displayConfirmation($this->l('Import started. Please note that it might take a while to complete process.'));
                        // Save the last import
                        Configuration::updateValue('KEY_LAST_IMPORT', time());
                    } else {
                        $this->_html .= $this->displayError($error);
                    }
                }
            } else {
                $this->_html .= $this->displayError($this->l('Some of the settings could not be saved.'));
            }
        }
    }

    private function _displayForm()
    {
        $this->_html .= $this->_generateForm();
    }

    private function _generateForm()
    {
        $fields = array();

        $inputs1 = array();

        $inputs1[] = array(
            'type'  => 'text',
            'label' => $this->l('API Key'),
            'name'  => 'mailchimpApiKey',
            'desc'  => $this->l('Please enter your MailChimp API key. This can be found in your MailChimp Dashboard -> Account -> Extras -> API keys.'),
        );

        $fieldsForm1 = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('API Settings'),
                    'icon'  => 'icon-cogs',
                ),
                'input'  => $inputs1,
                'submit' => array(
                    'title' => $this->l('Save'),
                    'class' => 'btn btn-default pull-right',
                    'name'  => 'submitApiKey',
                ),
            ),
        );

        $fields[] = $fieldsForm1;

        // Show settings form only if API key is set and working
        $apiKey = Configuration::get('KEY_API_KEY');
        $validKey = false;
        $lists = array();
        if (isset($apiKey) && $apiKey != '') {
            // Check if API key is valid
            try {
                $mailchimp = new \ThirtyBees\MailChimp\MailChimp(Configuration::get('KEY_API_KEY'));
                $mailchimp->verifySsl = false;
                $getLists = $mailchimp->get('lists');
                if ($getLists) {
                    $lists = $getLists['lists'];
                    $validKey = true;
                }
            } catch (Exception $e) {
                $this->_html .= $this->displayError($e->getMessage());
            }
        }

        if ($validKey) {
            $inputs2 = array();

            $inputs2[] = array(
                'type'    => 'select',
                'label'   => $this->l('Import to List'),
                'name'    => 'importList',
                'desc'    => $this->l('Please select a MailChimp list to import subscriptions to.'),
                'options' => array(
                    'query' => $lists,
                    'id'    => 'id',
                    'name'  => 'name',
                ),
            );

            $inputs2[] = array(
                'type'   => 'switch',
                'label'  => $this->l('Confirmation Email'),
                'name'   => 'confirmationEmail',
                'desc'   => $this->l('If you turn this on, Mailchimp will send an email to customers asking them to confirm their subscription.'),
                'values' => array(
                    array(
                        'id'    => 'confirmationSwitch_on',
                        'value' => 1,
                        'label' => $this->l('Enabled'),
                    ),
                    array(
                        'id'    => 'confirmationSwitch_off',
                        'value' => 0,
                        'label' => $this->l('Disabled'),
                    ),
                ),
            );

            $inputs2[] = array(
                'type'   => 'switch',
                'label'  => $this->l('Update if exists'),
                'name'   => 'updateExisting',
                'desc'   => $this->l('Do you wish to update the subscriber details if they already exist?'),
                'values' => array(
                    array(
                        'id'    => 'updateSwitch_on',
                        'value' => 1,
                        'label' => $this->l('Enabled'),
                    ),
                    array(
                        'id'    => 'updateSwitch_off',
                        'value' => 0,
                        'label' => $this->l('Disabled'),
                    ),
                ),
            );

            $inputs2[] = array(
                'type'   => 'switch',
                'label'  => $this->l('Import All Customers'),
                'name'   => 'importAll',
                'desc'   => $this->l('Turn this on if you wish to import all of the users. This means that the module ignores the customer\'s subscription choice.'),
                'id'     => 'importAll',
                'values' => array(
                    array(
                        'id'    => 'importSwitch_on',
                        'value' => 1,
                        'label' => $this->l('Enabled'),
                    ),
                    array(
                        'id'    => 'importSwitch_off',
                        'value' => 0,
                        'label' => $this->l('Disabled'),
                    ),
                ),
            );

            $inputs2[] = array(
                'type'   => 'switch',
                'label'  => $this->l('Opted-In Only'),
                'name'   => 'importOptedIn',
                'desc'   => $this->l('This will only import customers that has opted-in to the newsletter.'),
                'id'     => 'importOptedIn',
                'values' => array(
                    array(
                        'id'    => 'optedInSwitch_on',
                        'value' => 1,
                        'label' => $this->l('Enabled'),
                    ),
                    array(
                        'id'    => 'optedInSwitch_off',
                        'value' => 0,
                        'label' => $this->l('Disabled'),
                    ),
                ),
            );

            $lastImport = Configuration::get('KEY_LAST_IMPORT');
            $lastImport = $lastImport == '' ? $this->l('No previous import has been found.') : date('Y-m-d H:i', $lastImport);
            $inputs2[] = array(
                'type'   => 'checkbox',
                'label'  => $this->l('Manual Import'),
                'name'   => 'manualImport',
                'desc'   => $this->l('Check this if you want Prestashop to do a manual import after you hit the Save button. Last import: ' . $lastImport),
                'values' => array(
                    'query' => array(
                        array(
                            'id_option' => 0,
                            'name'      => $this->l('Import Now'),
                        ),
                    ),
                    'id'    => 'id_option',
                    'name'  => 'name',
                ),
            );

            $fieldsForm2 = array(
                'form' => array(
                    'legend' => array(
                        'title' => $this->l('Import Settings'),
                        'icon'  => 'icon-cogs',
                    ),
                    'input'  => $inputs2,
                    'submit' => array(
                        'title' => $this->l('Save'),
                        'class' => 'btn btn-default pull-right',
                        'name'  => 'submitSettings',
                    ),
                ),
            );

            $fields[] = $fieldsForm2;
        }

        $helper = new HelperForm();
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false) . '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->tpl_vars = array(
            'fields_value' => $this->_getConfigFieldsValues(),
        );
        return $helper->generateForm($fields);
    }

    private function _getConfigFieldsValues()
    {
        return array(
            'mailchimpApiKey'   => Configuration::get('KEY_API_KEY'),
            'importList'        => Configuration::get('KEY_IMPORT_LIST'),
            'confirmationEmail' => Configuration::get('KEY_CONFIRMATION_EMAIL'),
            'updateExisting'    => Configuration::get('KEY_UPDATE_EXISTING'),
            'importAll'         => Configuration::get('KEY_IMPORT_ALL'),
            'importOptedIn'     => Configuration::get('KEY_IMPORT_OPTED_IN'),
        );
    }

    public function hookDisplayBackOfficeHeader($params)
    {
        if ($this->context->controller->controller_name) {
            if (Tools::isSubmit('module_name') && Tools::getValue('module_name') == 'mailchimp') {
                $this->context->controller->addJS($this->_path . 'views/js/mailchimp.js');
            }
        }
    }

    private function _getFinalSubscribersList($all = false, $optedIn = false)
    {
        // Get subscriptions made through Newsletter Block
        $list1 = $this->_getNewsletterBlockSubscriptions($optedIn);
        // Get subscriptions made through either registration form or during guest checkout
        $list2 = $this->_getCustomerSubscriptions($optedIn);
        return array_merge($list1, $list2);
    }

    private function _getNewsletterBlockSubscriptions($optedIn = false)
    {
        $list = array();
        // Check if the module exists
        $moduleNewsletter = \Module::getInstanceByName('blocknewsletter');
        if ($moduleNewsletter) {
            // TODO: Use helper methods to generate the query
            $sql = '
                SELECT pn.`email`, pn.`newsletter_date_add`, 
                pn.`ip_registration_newsletter`, pn.`active` 
                FROM `ps_newsletter` pn
                WHERE 1 
            ';
            // MARK: Loop through shop IDs if need be
            $sql .= 'AND pn.`id_shop` = ' . $this->_idShop . ' ';
            if ($optedIn) {
                $sql .= 'AND pn.`active` = 1 ';
            }

            $result = \Db::getInstance(_PS_USE_SQL_SLAVE_)->ExecuteS($sql);

            if ($result) {
                // If confirmation mail is to be sent, statuses must be post as pending to the MailChimp API
                $subscription = (bool)Configuration::get('KEY_CONFIRMATION_EMAIL') ? SUBSCRIPTION_PENDING : SUBSCRIPTION_SUBSCRIBED;
                // Get default shop language since Newsletter Block registrations don't contain any language info
                $lang = $this->_mailChimpLanguages[$this->context->language->iso_code];
                foreach ($result as $row) {
                    $list[] = new MailChimpSubscriber(
                        $row['email'],
                        $subscription,
                        null,
                        null,
                        $row['ip_registration_newsletter'],
                        $lang,
                        $row['newsletter_date_add']
                    );
                }
            }
        }
        return $list;
    }

    private function _getCustomerSubscriptions($optedIn = false)
    {
        return array();
    }
}