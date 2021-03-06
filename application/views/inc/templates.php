<script type="text/ng-template" id="ciuis-sidebar.html">
<aside ng-controller="Sidebar_Controller" class="page-aside hidden-md hidden-sm hidden-xs ciuis-sag-sidebar-xs">
	<div id="events">
		<div class="ciuis-1 col-xs-12 nopadding">
			<div class="col-xs-6 nopadding date-and-time-ciuis">
				<i class="ion-ios-clock-outline"></i>
				<span id="time-ciuis" ng-bind="clock | date:'HH:mm'"></span>
			</div>
			<div class="col-xs-6 date-a" ng-bind="date | date:'dd, MMMM yyyy EEEE'"></div>
		</div>
		<div class="row">
			<div class="events events_xs col-md-12">
				<ul>
					<li ng-repeat="invoice in overdueinvoices" class="next overdueinvoices">
						<label class="date"> <i class="ion-alert"></i> </label>
						<a href="{{appurl + 'invoices/invoice/' + invoice.id}}"> <span class='class="text-white" ion-ios-arrow-forward'></span> </a>
						<h3 class="text-bold overduetext" ng-bind='lang.overdueinvoices'> ( INV-<span ng-bind="invoice.id"></span> )</h3>
						<p>
							<span class='duration text-bold' ng-bind="invoice.customer"></span>
							<span class='location text-bold'><span ng-bind-html="invoice.total | currencyFormat:cur_code:null:true:cur_lct"></span></span><br>
							<span style="color: #ee7a6b;" class='text-bold'><span class="text-default mdi mdi-timer-off"></span> <span class="text-default"><b ng-bind="invoice.status"> Over</b></span></span>
						</p>
					</li>
					<li ng-repeat="invoice in dueinvoices" class="next dueinvoices">
						<label class="date"> <i class="ion-alert"></i> </label>
						<a href="{{appurl + 'invoices/invoice/' + invoice.id}}"> <span class='ion-ios-arrow-forward'></span> </a>
						<h3 class="text-bold" ng-bind='lang.duepayment'>( INV-<span ng-bind="invoice.id"></span> )</h3>
						<p>
							<span style="color:black;" class='duration text-bold' ng-bind="invoice.customer"></span>
							<span style="color:black;" class='location text-bold'><span ng-bind-html="invoice.total | currencyFormat:cur_code:null:true:cur_lct"></span></span>
						</p>
					</li>
					<li ng-repeat="transaction in transactions" class="next {{transaction.type}}">
						<label class="detail"> <i class="{{transaction.icon}}"></i> </label>
						<a href=""> <span class='ion-ios-arrow-forward'></span> </a>
						<h3 class="text-bold text-uppercase" ng-bind="transaction.title"><small data-toggle="popover" data-trigger="hover" title="Test" data-content="Next Features, testing." data-placement="top"><i style="font-size:14px;" class="icon ion-help-circled text-muted"></i></small></h3>
						<p><span style="color:black;" class='duration text-bold'><span ng-bind-html="transaction.amount | currencyFormat:cur_code:null:true:cur_lct"></span></span>
						</p>
					</li>
					<li ng-repeat="newticket in newtickets" class="next newticketsidebar">
						<label class="date"> <i class="mdi mdi-ticket-star"></i> </label>
						<a href="{{appurl + 'tickets/ticket/' + newticket.id}}"> <span class='ion-ios-arrow-forward'></span> </a>
						<h3 class="text-bold" ng-bind="newticket.subject"></h3>
						<p>
							<span style="color:black;" class='duration text-bold' ng-bind="newticket.contactname +' '+ newticket.contactsurname"></span>
							<span style="color:black;" class='location text-bold' ng-bind="newticket.priority"></span>
						</p>
					</li>
					<li ng-repeat="event in events | filter:{date: curDate}" class="{{event.status}}">
						<label class="date"> <span class="weekday" ng-bind="event.day"></span><span class="day" ng-bind="event.aday"></span> </label>
						<a href=""><span class='ion-ios-arrow-forward'></span></a>
						<h3 ng-bind="event.title"></h3>
						<p>
							<span class='duration' ng-bind="event.start_iso_date | date : 'MMM d, y h:mm:ss a'"></span>
							<span class='location' ng-bind="event.staff"></span>
						</p>
					</li>
					<li ng-repeat="reminder in reminders.public" class="next reminder">
					<md-tooltip md-direction="bottom"><?php echo lang('reminder_created_by') ?> {{reminder.creator}}</md-tooltip>
					<label class="detail"><i class="ion-ios-bell"></i></label>
						<a ng-click='ReminderRead(reminder.id)' style="cursor: pointer">
							<i class="mark-read-reminder ion-checkmark-round"></i>
							<md-tooltip md-direction="top"> <?php echo lang('mark_as').' '.lang('read') ?></md-tooltip>
						</a>
						<h3 class="text-bold" style="margin-bottom: 5px" ng-bind="reminder.title"></h3>
						<span class="reminder-detail" style="display: table-cell;" ng-bind="reminder.description"></span>
						<p style="display: table-footer-group;"><span style="color:black;" class='duration text-bold'><span ng-bind="reminder.date | date : 'MMM d, y h:mm:ss a'"></span></span></p>
					</li>
					<li ng-repeat="reminder in reminders.private" class="next reminder">
					<md-tooltip md-direction="bottom"><?php echo lang('reminder_created_by') ?> {{reminder.creator}}</md-tooltip>
					<label class="detail"><i class="ion-ios-bell"></i></label>
						<a ng-click='ReminderRead(reminder.id)' style="cursor: pointer">
							<i class="mark-read-reminder ion-checkmark-round"></i>
							<md-tooltip md-direction="top"> <?php echo lang('mark_as').' '.lang('read') ?></md-tooltip>
						</a>
						<h3 class="text-bold" style="margin-bottom: 5px" ng-bind="reminder.title"></h3>
						<span class="reminder-detail" style="display: table-cell;" ng-bind="reminder.description"></span>
						<p style="display: table-footer-group;"><span style="color:black;" class='duration text-bold'><span ng-bind="reminder.date | date : 'MMM d, y h:mm:ss a'"></span></span></p>
					</li>
				</ul>
			</div>
			<div class="ciuis-activity-line col-md-12">
				<ul class="ciuis-activity-timeline">
					<li ng-repeat="log in logs | limitTo: LogLimit" class="ciuis-activity-detail">
						<div class="ciuis-activity-title" ng-bind="log.date"></div>
						<div class="ciuis-activity-detail-body">
							<div ng-bind-html="log.detail|trustAsHtml"></div>
							<div style="margin-right: 15px; border-radius: 3px; background: transparent; color: #2f3239; font-weight: 400;" class="pull-right label label-default">
								<small class="log-date"><i class="ion-android-time"></i> <span ng-bind="log.logdate | date : 'MMM d, y h:mm:ss a'"></span></small>
							</div>
						</div>
					</li>
					<load-more></load-more>
				</ul>
			</div>
		</div>
	</div>
</aside>
</script>
<script type="text/ng-template" id="custom-fields.html">
	<md-subheader ng-if="custom_fields != false" class="md-primary"><?php echo lang('custom_fields') ?></md-subheader>
	<md-content ng-if="custom_fields != false" layout-padding class="bg-white">
		<div ng-repeat="field in custom_fields" style="padding: 0;"> 
		<md-input-container  class="md-block" ng-show="field.type === 'input'">
			<label>{{field.name}}</label>
			<input type="text" ng-model="custom_fields[$index].data"/>
		</md-input-container>
		<md-input-container  class="md-block" ng-show="field.type === 'date'">
			<label>{{field.name}}</label>
			<md-datepicker name="datepayment" ng-model="custom_fields[$index].data"></md-datepicker>
		</md-input-container>
		<md-input-container  class="md-block" ng-show="field.type === 'select'">
			<label>{{field.name}}</label>
			<md-select placeholder="{{field.name}}" ng-model="custom_fields[$index].selected_opt">
				<md-option ng-value="opt" ng-repeat="opt in field.data">{{opt.name}}</md-option>
			</md-select><br>
		</md-input-container>
		<md-input-container class="md-block" ng-show="field.type === 'textarea'">
          <label>{{field.name}}</label>
          <textarea ng-model="custom_fields[$index].data" md-maxlength="150" rows="3" md-select-on-focus></textarea>
        </md-input-container>
		</div>
	</md-content>
</script>