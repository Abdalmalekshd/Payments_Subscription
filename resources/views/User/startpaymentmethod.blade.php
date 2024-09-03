
@extends('Layout.UserMaster')

@section('content')
<div class="container mt-5">

@php
$noheader='';
$noNavbar=''
@endphp
<form action="{{ route('start.verify.payment') }}" method="POST">
    @csrf
    <div class="form-group">
        <label for="email">Email address:</label>
        <input type="email" class="form-control" id="email" name="email" required>
    </div>
    <input type="submit" class="btn btn-primary" value="Proceed to Payment">
</form>
</div>
@endsection
