<div class="form-group">
	{{ Form::label('addressee', 'Name', array('class'=>'col-sm-3 control-label')); }}
	<div class="col-sm-8">
		{{ Form::text('addressee', null, array('class'=>'form-control', 'placeholder'=>'Full Name')); }}
		<p class="help-block"></p>
	</div>
</div>

<div class="form-group">
	{{ Form::label('organization', 'Organization', array('class'=>'col-sm-3 control-label')); }}
	<div class="col-sm-8">
		{{ Form::text('organization', null, array('class'=>'form-control', 'placeholder'=>'Organization')); }}
		<p class="help-block"></p>
	</div>
</div>

<div class="form-group">
	{{ Form::label('street', 'Street Address', array('class'=>'col-sm-3 control-label')); }}
	<div class="col-sm-8">
		{{ Form::text('street', null, array('class'=>'form-control', 'placeholder'=>'Street Address')); }}
		{{ Form::text('street_extra', null, array('class'=>'form-control', 'placeholder'=>'', 'style'=>'margin-top:6px;')); }}
	</div>
</div>

<div class="form-group">
	{{ Form::label('city', 'City / Town', array('class'=>'col-sm-3 control-label')); }}
	<div class="col-sm-8">
		{{ Form::text('city', null, array('class'=>'form-control', 'placeholder'=>'Town or City Name')); }}
	</div>
</div>

<div class="form-group">
	{{ Form::label('state', 'State / Province', array('class'=>'col-sm-3 control-label')); }}
	<div class="col-sm-8">
		{{ Addresses::selectState('state', null, array('class'=>'form-control', 'country'=>'US')); }}
	</div>
</div>
	
<div class="form-group">
	{{ Form::label('zip', 'Zip / Postal Code', array('class'=>'col-sm-3 control-label')); }}
	<div class="col-sm-8">
		{{ Form::text('zip', null, array('class'=>'form-control', 'placeholder'=>'Postal Code')); }}
	</div>
</div>
		
<div class="form-group">
	{{ Form::label('country', 'Country', array('class'=>'col-sm-3 control-label')); }}
	<div class="col-sm-8">
		{{ Addresses::selectCountry('country', 'US', array('class'=>'form-control')); }}
	</div>
</div>
	
<div class="form-group">
	{{ Form::label('phone', 'Phone Number', array('class'=>'col-sm-3 control-label')); }}
	<div class="col-sm-8">
		{{ Form::text('phone', null, array('class'=>'form-control', 'placeholder'=>'Phone Number')); }}
	</div>
</div>
	
<div class="form-group">
	<div class="col-sm-offset-3 col-sm-8">
		<div class="checkbox">
			<label>
			{{ Form::checkbox('is_primary'); }}
			Set as Primary Address</label>
		</div>
	</div>
</div>
	
<div class="form-group">
	<div class="col-sm-offset-3 col-sm-8">
		<div class="checkbox">
			<label>
			{{ Form::checkbox('is_billing'); }}
			Set as Billing Address</label>
		</div>
	</div>
</div>
	
<div class="form-group">
	<div class="col-sm-offset-3 col-sm-8">
		<div class="checkbox">
			<label>
			{{ Form::checkbox('is_shipping'); }}
			Set as Shipping Address</label>
		</div>
	</div>
</div>