{{ Form::open(array('method' => 'post', 'class'=>'form-horizontal', 'role'=>'form')) }}
{{ Form::setModel($address); }}
<?php Form::token(); ?>
         
	@include('addresses::fields')
   
<button type="submit" class="btn btn-primary">Save Address</button>  
{{ Form::close() }}