<?php include_once( APPPATH . 'views/inc/ciuis_data_table_header.php' ); ?>
<?php $appconfig = get_appconfig(); ?>
<div class="ciuis-body-content" ng-controller="Invoices_Controller">
  <div class="main-content container-fluid col-xs-12 col-md-12 col-lg-9">
    <md-toolbar class="toolbar-white" ng-cloak>
      <div class="md-toolbar-tools">
        <md-button class="md-icon-button" aria-label="Invoice" ng-disabled="true">
          <md-icon><i class="ico-ciuis-invoices text-muted"></i></md-icon>
        </md-button>
        <h2 flex md-truncate><?php echo lang('newinvoice') ?></h2>
        <md-switch ng-model="invoice_status" aria-label="Status"><strong class="text-muted"><?php echo lang('paid') ?></strong></md-switch>
        <md-switch ng-model="invoice_recurring" aria-label="Recurring"> <strong class="text-muted"><?php echo lang('recurring') ?></strong> </md-switch>
        <md-button ng-href="<?php echo base_url('invoices')?>" class="md-icon-button" aria-label="Save">
          <md-tooltip md-direction="bottom"><?php echo lang('cancel') ?></md-tooltip>
          <md-icon><i class="ion-close-circled text-danger"></i></md-icon>
        </md-button>
        <md-button type="submit" ng-click="saveAll()" class="md-icon-button" aria-label="Save">
          <md-progress-circular ng-show="savingInvoice == true" md-mode="indeterminate" md-diameter="20"></md-progress-circular>
          <md-tooltip ng-hide="savingInvoice == true" md-direction="bottom"><?php echo lang('save') ?></md-tooltip>
          <md-icon ng-hide="savingInvoice == true"><i class="ion-checkmark-circled text-success"></i></md-icon>
        </md-button>
      </div>
    </md-toolbar>
    <div ng-show="invoiceLoader" layout-align="center" class="text-center" id="circular_loader">
        <md-progress-circular md-mode="indeterminate" md-diameter="30"></md-progress-circular>
        <p style="font-size: 15px;margin-bottom: 5%;">
         <span>
            <?php echo lang('please_wait') ?> <br>
           <small><strong><?php echo lang('loading').'...' ?></strong></small>
         </span>
       </p>
    </div>
    <md-content ng-show="!invoiceLoader" class="bg-white" layout-padding ng-cloak>
      <div layout-gt-xs="row">
        <md-input-container class="md-block" flex-gt-sm>
          <md-select ng-model="customer" ng-change="default_payment_method = customer.default_payment_method;
            invoice.billing_country_id = customer.billing_country_id;
            invoice.shipping_country_id = customer.shipping_country_id;
            invoice.billing_state_id = customer.billing_state_id; 
            invoice.shipping_state_id = customer.shipping_state_id;getBillingStates(customer.billing_country_id);getShippingStates(customer.shipping_country_id)" data-md-container-class="selectdemoSelectHeader">
          <md-select-header class="demo-select-header">
            <label style="display: none;"><?php echo lang('search').' '.lang('customer')?></label>
            <input ng-submit="search_customers(search_input)" ng-model="search_input" type="text" placeholder="<?php echo lang('search').' '.lang('customers')?>" class="demo-header-searchbox md-text" ng-keyup="search_customers(search_input)">
          </md-select-header>
          <md-optgroup label="customers">
            <md-option ng-value="customer" ng-repeat="customer in all_customers">
              <span class="blur" ng-bind="customer.customer_number"></span> 
              <span ng-bind="customer.name"></span><br>
              <span class="blur">(<small ng-bind="customer.email"></small>)</span>
            </md-option>
          </md-optgroup>
        </md-select>
        </md-input-container>
        <md-input-container class="md-block" flex-gt-sm>
          <label><?php echo lang('serie')?></label>
          <input ng-model="serie" name="serie">
        </md-input-container>
        <md-input-container class="md-block" flex-gt-sm>
          <label><?php echo lang('invoicenumber')?></label>
          <input ng-model="no" name="no">
        </md-input-container>
        <md-input-container>
          <label><?php echo lang('dateofissuance') ?></label>
          <md-datepicker name="created" ng-model="created" md-open-on-focus ></md-datepicker>
        </md-input-container>
      </div>
      <div layout-gt-xs="row">
        <md-input-container ng-show="invoice_status" class="md-block" flex-gt-xs>
          <label><?php echo lang('paidcashornank'); ?></label>
          <md-select placeholder="<?php echo lang('choiseaccount'); ?>" ng-model="account" name="account" style="min-width: 200px;">
            <md-option ng-value="account.id" ng-repeat="account in accounts">{{account.name}}</md-option>
          </md-select>
          <div ng-messages="userForm.customer" role="alert" multiple>
            <div ng-message="required" class="my-message"><?php echo lang('you_must_supply_a_customer') ?></div>
          </div>
        </md-input-container>
        <md-input-container ng-show="!invoice_status" class="md-block" flex-gt-sm>
          <label><?php echo lang('duenote') ?></label>
          <input ng-model="duenote" name="duenote">
        </md-input-container>
        <md-input-container class="md-block">
          <label><?php echo lang('payment_method'); ?></label>
          <md-select placeholder="<?php echo lang('default_payment_method'); ?>" ng-model="default_payment_method" name="default_payment_method" style="min-width: 200px;">
            <?php
            $gateways = get_active_payment_methods();
            foreach ($gateways as $gateway) { ?>
              <md-option ng-value='"<?php echo $gateway['relation'] ?>"'><?php echo lang($gateway['relation'])?lang($gateway['relation']):$gateway['name'] ?></md-option>
            <?php } ?>
          </md-select>
        </md-input-container>
        <md-input-container ng-show="invoice_status">
          <label><?php echo lang('datepaid') ?></label>
          <md-datepicker name="datepayment" ng-model="datepayment" md-open-on-focus ></md-datepicker>
        </md-input-container>
        <md-input-container ng-show="!invoice_status">
          <label><?php echo lang('duedate') ?></label>
          <md-datepicker md-min-date="created" name="duedate" ng-model="duedate" md-open-on-focus></md-datepicker>
        </md-input-container>
      </div>
      <div ng-show="invoice_recurring" layout-gt-xs="row">
        <md-input-container class="md-block" flex-gt-xs>
          <label><?php echo lang('recurring_period') ?></label>
          <input type="number" ng-model="recurring_period" name="recurring_period">
        </md-input-container>
        <md-input-container class="md-block" flex-gt-xs>
          <label><?php echo lang('recurring_type') ?></label>
          <md-select ng-model="recurring_type" name="recurring_type">
            <md-option value="0"><?php echo lang('days') ?></md-option>
            <md-option value="1" selected><?php echo lang('weeks') ?></md-option>
            <md-option value="2"><?php echo lang('months') ?></md-option>
            <md-option value="3"><?php echo lang('years') ?></md-option>
          </md-select>
        </md-input-container>
        <md-input-container>
          <label><?php echo lang('ends_on') ?></label>
          <md-datepicker md-min-date="date" name="EndRecurring" ng-model="EndRecurring" style="min-width: 100%;" md-open-on-focus></md-datepicker>
          <div >
            <div ng-message="required" class="my-message"><?php echo lang('leave_blank_for_lifetime') ?></div>
          </div>
        </md-input-container>
      </div>
    </md-content>
    <md-content ng-show="!invoiceLoader" class="bg-white" layout-padding ng-cloak>
      <md-list-item ng-repeat="item in invoice.items">
        <div layout-gt-sm="row">
          <md-autocomplete
          md-autofocus
          md-items="product in GetProduct(item.name)"
        md-search-text="item.name"
        md-item-text="product.name"   
        md-selected-item="selectedProduct"
        md-no-cache="true"
        md-min-length="0"
        md-floating-label="<?php echo lang('productservice'); ?>">
            <md-item-template> <span md-highlight-text="item.name">{{product.name}}</span> <strong ng-bind-html="product.price | currencyFormat:cur_code:null:true:cur_lct"></strong> </md-item-template>
          </md-autocomplete>
          <md-input-container class="md-block">
            <label><?php echo lang('description'); ?></label>
            <input class="min_input_width" type="hidden" ng-model="item.name">
            <bind-expression ng-init="selectedProduct.name = item.name" expression="selectedProduct.name" ng-model="item.name" />
            <textarea class="min_input_width" ng-model="item.description" placeholder="<?php echo lang('description'); ?>"></textarea>
            <bind-expression ng-init="selectedProduct.description = item.description" expression="selectedProduct.description" ng-model="item.description" />
            <input class="min_input_width" type="hidden" ng-model="item.product_id">
            <bind-expression ng-init="selectedProduct.product_id = item.product_id" expression="selectedProduct.product_id" ng-model="item.product_id" />
            <input class="min_input_width" type="hidden" ng-model="item.code" ng-value="selectedProduct.code">
            <bind-expression ng-init="selectedProduct.code = item.code" expression="selectedProduct.code" ng-model="item.code" />
          </md-input-container>
          <md-input-container class="md-block" flex-gt-sm>
            <label><?php echo lang('quantity'); ?></label>
            <input class="min_input_width" ng-model="item.quantity" >
          </md-input-container>
          <md-input-container class="md-block" flex-gt-xs>
            <label><?php echo lang('unit'); ?></label>
            <input class="min_input_width" ng-model="item.unit" >
          </md-input-container>
          <md-input-container class="md-block">
            <label><?php echo lang('price'); ?></label>
            <input class="min_input_width" ng-model="item.price">
            <bind-expression ng-init="selectedProduct.price = 0" expression="selectedProduct.price" ng-model="item.price" />
          </md-input-container>
          <md-input-container class="md-block" flex-gt-xs>
            <label><?php echo $appconfig['tax_label']; ?></label>
            <input class="min_input_width" ng-model="item.tax">
            <bind-expression ng-init="selectedProduct.tax = 0" expression="selectedProduct.tax" ng-model="item.tax" />
          </md-input-container>
          <md-input-container class="md-block" flex-gt-sm>
            <label><?php echo lang('discount'); ?></label>
            <input class="min_input_width" ng-model="item.discount">
          </md-input-container>
          <md-input-container class="md-block">
            <label><?php echo lang('total'); ?></label>
            <input class="min_input_width" ng-value="item.quantity * item.price + ((item.tax)/100*item.quantity * item.price) - ((item.discount)/100*item.quantity * item.price)">
          </md-input-container>
        </div>
        <md-icon aria-label="Remove Line" ng-click="remove($index)" class="md-secondary ion-trash-b text-muted"></md-icon>
      </md-list-item>
      <md-content class="bg-white" layout-padding ng-cloak>
        <div class="col-md-6">
          <md-button ng-click="add()" class="md-fab pull-left" ng-disabled="false" aria-label="Add Line">
            <md-icon class="ion-plus-round text-muted"></md-icon>
          </md-button>
        </div>
        <div class="col-md-6 md-pr-0" style="font-weight: 900; font-size: 16px; color: #c7c7c7;">
          <div class="col-md-7">
            <div class="text-right text-uppercase text-muted"><?php echo lang('subtotal') ?>:</div>
            <div ng-show="linediscount() > 0" class="text-right text-uppercase text-muted"><?php echo lang('total_discount') ?>:</div>
            <div ng-show="totaltax() > 0"class="text-right text-uppercase text-muted"><?php echo lang('total').' '.$appconfig['tax_label'] ?>:</div>
            <div class="text-right text-uppercase text-black"><?php echo lang('grandtotal') ?>:</div>
          </div>
          <div class="col-md-5">
            <div class="text-right" ng-bind-html="subtotal() | currencyFormat:cur_code:null:true:cur_lct"></div>
            <div ng-show="linediscount() > 0" class="text-right" ng-bind-html="linediscount() | currencyFormat:cur_code:null:true:cur_lct"></div>
            <div ng-show="totaltax() > 0"class="text-right" ng-bind-html="totaltax() | currencyFormat:cur_code:null:true:cur_lct"></div>
            <div class="text-right" ng-bind-html="grandtotal() | currencyFormat:cur_code:null:true:cur_lct"></div>
          </div>
        </div>
      </md-content>
    </md-content>
  </div>
  <div class="main-content container-fluid lg-pl-0 col-xs-12 col-md-12 col-lg-3" ng-cloak>
    <md-toolbar class="toolbar-white">
      <div class="md-toolbar-tools">
        <md-button class="md-icon-button" aria-label="Invoice" ng-disabled="true">
          <md-icon><i class="ico-ciuis-invoices text-muted"></i></md-icon>
        </md-button>
        <h2 flex md-truncate><?php echo lang('billing_and_shipping_details') ?></h2>
      </div>
    </md-toolbar>
    <md-subheader class="md-primary bg-white text-uppercase text-bold"><?php echo lang('billing_address') ?></md-subheader>
    <md-divider></md-divider>
    <md-content layout-padding class="bg-white" ng-cloak>
      <address class="m-t-5 m-b-5">
        <strong ng-bind="invoice.billing_street"></strong><br>
        <span ng-bind="invoice.billing_city"></span> / <span ng-bind="invoice.billing_state"></span> <span ng-bind="invoice.billing_zip"></span><br>
        <strong ng-bind="invoice.billing_country"></strong>
        <bind-expression ng-init="customer.billing_street = '------'" expression="customer.billing_street" ng-model="invoice.billing_street" />
        <bind-expression ng-init="customer.billing_city = ',---- '" expression="customer.billing_city" ng-model="invoice.billing_city" />
        <bind-expression ng-init="customer.billing_state = ',----'" expression="customer.billing_state" ng-model="invoice.billing_state" />
        <bind-expression ng-init="customer.billing_zip = '----'" expression="customer.billing_zip" ng-model="invoice.billing_zip" />
        <bind-expression ng-init="customer.billing_country = '----'" expression="customer.billing_country" ng-model="invoice.billing_country" />
      </address>
      <md-content ng-if='EditBilling == true' layout-padding class="bg-white" ng-cloak>
        <md-input-container class="md-block">
          <label><?php echo lang('address') ?></label>
          <textarea ng-model="invoice.billing_street" md-maxlength="500" rows="2" md-select-on-focus></textarea>
        </md-input-container>
        <md-input-container class="md-block">
          <md-select placeholder="<?php echo lang('country'); ?>" ng-model="invoice.billing_country_id" ng-change="getBillingStates(invoice.billing_country_id)" name="billing_country"  style="min-width: 200px;">
            <md-option ng-value="country.id" ng-repeat="country in countries">{{country.shortname}}</md-option>
          </md-select>
           <br/>
        </md-input-container>
        <md-input-container class="md-block">
          <md-select placeholder="<?php echo lang('state'); ?>" ng-model="invoice.billing_state_id" name="billing_state_id" style="min-width: 200px;">
            <md-option ng-value="state.id" ng-repeat="state in billingStates">{{state.state_name}}</md-option>
          </md-select>
        </md-input-container>
        <md-input-container class="md-block">
          <label><?php echo lang('city'); ?></label>
          <input name="city" ng-model="invoice.billing_city">
        </md-input-container>
        <md-input-container class="md-block">
          <label><?php echo lang('zipcode'); ?></label>
          <input name="zipcode" ng-model="invoice.billing_zip">
        </md-input-container>

        <bind-expression ng-init="invoice.billing_country = '----'" expression="customer.billing_country" ng-model="invoice.billing_country" />
      </md-content>
      <md-switch ng-model="NeedShippingAddress" aria-label="Status"><strong class="text-muted"><?php echo lang('need_shipping_address') ?></strong></md-switch>
      <md-button ng-show='EditBilling == false' ng-click="EditBilling = true" ng-init="EditBilling=false" class="md-icon-button pull-right" aria-label="Edit">
        <md-icon><i class="mdi mdi-edit text-muted"></i></md-icon>
        <md-tooltip md-direction="left"><?php echo lang('edit') ?></md-tooltip>
      </md-button>
      <md-button ng-show='EditBilling == true' ng-click="EditBilling = false" class="md-icon-button pull-right" aria-label="Hide Billing Form">
        <md-icon><i class="mdi mdi-minus-circle-outline text-muted"></i></md-icon>
        <md-tooltip md-direction="left"><?php echo lang('hide') ?></md-tooltip>
      </md-button>
      <md-button ng-click='CopyBillingFromCustomer()' class="md-icon-button pull-right" aria-label="Billing Copy">
        <md-icon><i class="mdi mdi-copy text-muted"></i></md-icon>
        <md-tooltip md-direction="left"><?php echo lang('copy_from_customer') ?></md-tooltip>
      </md-button>
    </md-content>
    <md-divider></md-divider>
    <md-subheader ng-show='NeedShippingAddress == true' class="md-primary bg-white text-uppercase text-bold"><?php echo lang('shipping_address') ?></md-subheader>
    <md-divider ng-show='NeedShippingAddress == true'></md-divider>
    <md-content  ng-show='NeedShippingAddress == true' layout-padding class="bg-white" ng-cloak>
      <address ng-hide='EditShipping == true' class="m-t-5 m-b-5">
      <strong ng-bind="invoice.shipping_street"></strong><br>
      <span ng-bind="invoice.shipping_city"></span> / <span ng-bind="invoice.shipping_state"></span> <span ng-bind="invoice.shipping_zip"></span><br>
      <strong ng-bind="invoice.shipping_country"></strong>
      <bind-expression ng-init="customer.shipping_street = '------'" expression="customer.shipping_street" ng-model="invoice.shipping_street" />
      <bind-expression ng-init="customer.shipping_city = ',---- '" expression="customer.shipping_city" ng-model="invoice.shipping_city" />
      <bind-expression ng-init="customer.shipping_state = ',----'" expression="customer.shipping_state" ng-model="invoice.shipping_state" />
      <bind-expression ng-init="customer.shipping_zip = '----'" expression="customer.shipping_zip" ng-model="invoice.shipping_zip" />
      <bind-expression ng-init="customer.shipping_country = '----'" expression="customer.shipping_country" ng-model="invoice.shipping_country" />
      </address>
      <md-content ng-show='EditShipping == true' layout-padding class="bg-white" ng-cloak>
        <md-input-container class="md-block">
          <label><?php echo lang('address') ?></label>
          <textarea ng-model="invoice.shipping_street" md-maxlength="500" rows="2" md-select-on-focus></textarea>
        </md-input-container>
        <md-input-container class="md-block">
          <md-select placeholder="<?php echo lang('country'); ?>" ng-model="invoice.shipping_country_id"  ng-change="getShippingStates(invoice.shipping_country_id)" name="shipping_country" style="min-width: 200px;">
            <md-option ng-value="{{country.id}}" ng-repeat="country in countries">{{country.shortname}}</md-option>
          </md-select>
          <br />
        </md-input-container>        
        <md-input-container class="md-block">
          <md-select placeholder="<?php echo lang('state'); ?>" ng-model="invoice.shipping_state_id" name="shipping_state_id" style="min-width: 200px;">
            <md-option ng-value="state.id" ng-repeat="state in shippingStates">{{state.state_name}}</md-option>
          </md-select>
        </md-input-container>
        <md-input-container class="md-block">
          <label><?php echo lang('city'); ?></label>
          <input name="city" ng-model="invoice.shipping_city">
        </md-input-container>
        <md-input-container class="md-block">
          <label><?php echo lang('zipcode'); ?></label>
          <input name="zipcode" ng-model="invoice.shipping_zip">
        </md-input-container>

        <bind-expression ng-init="invoice.shipping_country = '----'" expression="customer.shipping_country" ng-model="invoice.shipping_country" />
      </md-content>
      <md-button ng-show='EditShipping == false' ng-click="EditShipping = true" ng-init="EditShipping=false" class="md-icon-button pull-right" aria-label="Edit">
        <md-icon><i class="mdi mdi-edit text-muted"></i></md-icon>
        <md-tooltip md-direction="left"><?php echo lang('edit'); ?></md-tooltip>
      </md-button>
      <md-button ng-show='EditShipping == true' ng-click="EditShipping = false" class="md-icon-button pull-right" aria-label="Hide Form">
        <md-icon><i class="mdi mdi-minus-circle-outline text-muted"></i></md-icon>
        <md-tooltip md-direction="left"><?php echo lang('hide'); ?></md-tooltip>
      </md-button>
      <md-button ng-click='CopyShippingFromCustomer()'  class="md-icon-button pull-right" aria-label="Cop Shipping">
        <md-icon><i class="mdi mdi-copy text-muted"></i></md-icon>
        <md-tooltip md-direction="left"><?php echo lang('copy_from_customer'); ?></md-tooltip>
      </md-button>
    </md-content>
    <md-content class="bg-white">
      <custom-fields-vertical></custom-fields-vertical>
    </md-content>
    <md-divider></md-divider>
  </div>
</div>
<?php include_once( APPPATH . 'views/inc/other_footer.php' ); ?>
<script src="<?php echo base_url('assets/js/ciuis_data_table.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/invoices.js'); ?>"></script>