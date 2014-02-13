<div class="addresses">

	<a href="/account/address/create" class="btn btn-info btn-large">Create New Address</a>
	
	<hr>
	
	<ul class="list-unstyled">
	@foreach($addresses as $address)
	<li>
		<div class="addresses-address">
			@include('addresses::view')
		</div>
		<a href="/account/address/edit/{{{$address->id}}}" class="btn btn-primary">Edit Address</a>
		<a href="/account/address/primary/{{{$address->id}}}" class="btn btn-primary">Primary</a>
		<a href="/account/address/delete/{{{$address->id}}}" class="btn btn-danger">Delete</a>
	<li>
	@endforeach
	</ul>
	
</div>