{extends file="helpers/form/form.tpl"}

{block name="input"}
	{if $input.type == 'mailchimp_shops'}
		<div class="col-lg-9">
			<div class="row">
				<div class="row table-responsive clearfix ">
					<div>
						<table class="table">
							<thead>
							<tr>
								<th>
									<span class="title_box">{l s='Shop ID' mod='mailchimp'}</span>
								</th>
								<th>
									<span class="title_box">{l s='Name' mod='mailchimp'}</span>
								</th>
								<th>
									<span class="title_box">{l s='List' mod='mailchimp'}</span>
								</th>
								<th>
									<span class="title_box">{l s='Synced' mod='mailchimp'}</span>
								</th>
							</tr>
							</thead>
							<tbody>
							{foreach from=$input['shops'] item=shop}
								<tr>
									<td>
										<span>{$shop['id_shop']|intval}</span>
									</td>
									<td>
										<span>{$shop['name']|escape:'htmlall':'UTF-8'}</span>
									</td>
									<td>
										{html_options name="shop_list_id[{$shop['id_shop']}]" options=$input['lists'] selected=$shop['list_id']}
									</td>
									<td>
										{if $shop['synced']}YES{else}NO{/if}
									</td>
								</tr>
							{/foreach}
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	{elseif $input.type == 'mailchimp_products'}
		<div class="col-lg-9">
			<div class="row">
				<div class="row table-responsive clearfix ">
					<div>
						<table class="table">
							<thead>
							<tr>
								<th>
									<span class="title_box">{l s='Shop ID' mod='mailchimp'}</span>
								</th>
								<th>
									<span class="title_box">{l s='Name' mod='mailchimp'}</span>
								</th>
								<th>
									<span class="title_box">{l s='List' mod='mailchimp'}</span>
								</th>
								<th>
									<span class="title_box">{l s='Sync remaining products' mod='mailchimp'}</span>
								</th>
								<th>
									<span class="title_box">{l s='Sync all products' mod='mailchimp'}</span>
								</th>
							</tr>
							</thead>
							<tbody>
							{foreach from=$input['shops'] item=shop}
								<tr>
									<td>
										<span>{$shop['id_shop']|intval}</span>
									</td>
									<td>
										<span>{$shop['name']|escape:'htmlall':'UTF-8'}</span>
									</td>
									<td>
										<span>{$shop['list_id']|escape:'htmlall':'UTF-8'}</span>
									</td>
									<td>
										<div class="btn btn-default" id="sync-remaining-products-btn-{$shop['id_shop']|intval}"><i class="icon icon-refresh"></i> {l s='Sync remaining products'}</div>
									</td>
									<td>
										<div class="btn btn-default" id="sync-all-products-btn-{$shop['id_shop']|intval}"><i class="icon icon-refresh"></i> {l s='Sync all products'}</div>
									</td>
								</tr>
							{/foreach}
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	{elseif $input.type == 'mailchimp_carts'}
		<div class="col-lg-9">
			<div class="row">
				<div class="row table-responsive clearfix ">
					<div>
						<table class="table">
							<thead>
							<tr>
								<th>
									<span class="title_box">{l s='Shop ID' mod='mailchimp'}</span>
								</th>
								<th>
									<span class="title_box">{l s='Name' mod='mailchimp'}</span>
								</th>
								<th>
									<span class="title_box">{l s='List' mod='mailchimp'}</span>
								</th>
								<th>
									<span class="title_box">{l s='Sync remaining carts' mod='mailchimp'}</span>
								</th>
								<th>
									<span class="title_box">{l s='Sync all carts' mod='mailchimp'}</span>
								</th>
							</tr>
							</thead>
							<tbody>
							{foreach from=$input['shops'] item=shop}
								<tr>
									<td>
										<span>{$shop['id_shop']|intval}</span>
									</td>
									<td>
										<span>{$shop['name']|escape:'htmlall':'UTF-8'}</span>
									</td>
									<td>
										<span>{$shop['list_id']|escape:'htmlall':'UTF-8'}</span>
									</td>
									<td>
										<div class="btn btn-default" id="sync-remaining-carts-btn-{$shop['id_shop']|intval}"><i class="icon icon-refresh"></i> {l s='Sync remaining carts'}</div>
									</td>
									<td>
										<div class="btn btn-default" id="sync-all-carts-btn-{$shop['id_shop']|intval}"><i class="icon icon-refresh"></i> {l s='Sync all carts'}</div>
									</td>
								</tr>
							{/foreach}
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	{elseif $input.type == 'mailchimp_orders'}
		<div class="col-lg-9">
			<div class="row">
				<div class="row table-responsive clearfix ">
					<div>
						<table class="table">
							<thead>
							<tr>
								<th>
									<span class="title_box">{l s='Shop ID' mod='mailchimp'}</span>
								</th>
								<th>
									<span class="title_box">{l s='Name' mod='mailchimp'}</span>
								</th>
								<th>
									<span class="title_box">{l s='List' mod='mailchimp'}</span>
								</th>
								<th>
									<span class="title_box">{l s='Sync remaining orders' mod='mailchimp'}</span>
								</th>
								<th>
									<span class="title_box">{l s='Sync all orders' mod='mailchimp'}</span>
								</th>
							</tr>
							</thead>
							<tbody>
							{foreach from=$input['shops'] item=shop}
								<tr>
									<td>
										<span>{$shop['id_shop']|intval}</span>
									</td>
									<td>
										<span>{$shop['name']|escape:'htmlall':'UTF-8'}</span>
									</td>
									<td>
										<span>{$shop['list_id']|escape:'htmlall':'UTF-8'}</span>
									</td>
									<td>
										<div class="btn btn-default" id="sync-remaining-orders-btn-{$shop['id_shop']|intval}"><i class="icon icon-refresh"></i> {l s='Sync remaining orders'}</div>
									</td>
									<td>
										<div class="btn btn-default" id="sync-all-orders-btn-{$shop['id_shop']|intval}"><i class="icon icon-refresh"></i> {l s='Sync all orders'}</div>
									</td>
								</tr>
							{/foreach}
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	{else}
		{$smarty.block.parent}
	{/if}
{/block}