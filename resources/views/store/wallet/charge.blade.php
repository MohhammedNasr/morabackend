@extends('layouts.metronic.store')
@section('content')
<div class="container">
    <h1>Charge Wallet</h1>
    <form action="{{ route('store.wallet.charge') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="amount">Amount</label>
            <input type="number" name="amount" id="amount" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea name="description" id="description" class="form-control" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Charge</button>
    </form>
</div>
@endsection
