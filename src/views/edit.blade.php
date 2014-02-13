<h2>Edit Address</h2>

@include('addresses::form')

<br><br>

<a href="/account/address/primary/{{{$address->id}}}" class="btn btn-info">Set as Primary</a>
<a href="/account/address/shipping/{{{$address->id}}}" class="btn btn-info">Set as Shipping</a>
<a href="/account/address/billing/{{{$address->id}}}" class="btn btn-info">Set as Billing</a>

<br><br>

<a href="/account/address/delete/{{{$address->id}}}" class="btn btn-danger">Delete</a>