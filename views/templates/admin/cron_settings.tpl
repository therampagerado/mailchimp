{*
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
 *  @author    Thirty Bees <modules@thirtybees.com>
 *  @copyright 2017-2018 thirty bees
 *  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*}
<h2>{l s='The following CLI commands are available:' mod='mailchimp'} <div class="badge badge-success">{l s='RECOMMENDED' mod='mailchimp'}</div></h2>
{if $id_profile == 1}
  <ul style="-ms-word-break: break-all;word-break: break-all;">
    <li>{l s='Export all products:' mod='mailchimp'}<br><kbd>{$cron_all_products_cli|escape:'htmlall':'UTF-8'}</kbd></li>
    <li>{l s='Export remaining products:' mod='mailchimp'}<br><kbd>{$cron_remaining_products_cli|escape:'htmlall':'UTF-8'}</kbd></li>
    <li>{l s='Export all carts:' mod='mailchimp'}<br><kbd>{$cron_all_carts_cli|escape:'htmlall':'UTF-8'}</kbd></li>
    <li>{l s='Export remaining carts:' mod='mailchimp'}<br><kbd>{$cron_remaining_carts_cli|escape:'htmlall':'UTF-8'}</kbd></li>
    <li>{l s='Export all orders:' mod='mailchimp'}<br><kbd>{$cron_all_orders_cli|escape:'htmlall':'UTF-8'}</kbd></li>
    <li>{l s='Export remaining orders:' mod='mailchimp'}<br><kbd>{$cron_remaining_orders_cli|escape:'htmlall':'UTF-8'}</kbd></li>
  </ul>
{else}
  <p>{l s='You require SuperAdmin permissions to view the available commands' mod='mailchimp'}</p>
{/if}

<h2>{l s='The following cron URLs are available:' mod='mailchimp'}</h2>
<ul style="-ms-word-break: break-all;word-break: break-all;">
  <li>{l s='Export all products:' mod='mailchimp'} <a href="{$cron_all_products|escape:'htmlall':'UTF-8'}">{$cron_all_products|escape:'htmlall':'UTF-8'}</a></li>
  <li>{l s='Export remaining products:' mod='mailchimp'} <a href="{$cron_remaining_products|escape:'htmlall':'UTF-8'}">{$cron_remaining_products|escape:'htmlall':'UTF-8'}</a></li>
  <li>{l s='Export all carts:' mod='mailchimp'} <a href="{$cron_all_carts|escape:'htmlall':'UTF-8'}">{$cron_all_carts|escape:'htmlall':'UTF-8'}</a></li>
  <li>{l s='Export remaining carts:' mod='mailchimp'} <a href="{$cron_remaining_carts|escape:'htmlall':'UTF-8'}">{$cron_remaining_carts|escape:'htmlall':'UTF-8'}</a></li>
  <li>{l s='Export all orders:' mod='mailchimp'} <a href="{$cron_all_orders|escape:'htmlall':'UTF-8'}">{$cron_all_orders|escape:'htmlall':'UTF-8'}</a></li>
  <li>{l s='Export remaining orders:' mod='mailchimp'} <a href="{$cron_remaining_orders|escape:'htmlall':'UTF-8'}">{$cron_remaining_orders|escape:'htmlall':'UTF-8'}</a></li>
</ul>
