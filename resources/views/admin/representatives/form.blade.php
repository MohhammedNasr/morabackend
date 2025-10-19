<div class="form-group">
    <label>Name</label>
    <input type="text" name="name" class="form-control" value="{{ $representative->name ?? old('name') }}" required>
</div>

<div class="form-group">
    <label>Phone</label>
    <input type="text" name="phone" class="form-control" value="{{ $representative->phone ?? old('phone') }}" required>
</div>

<div class="form-group">
    <label>Email</label>
    <input type="email" name="email" class="form-control" value="{{ $representative->email ?? old('email') }}">
</div>

@if(!isset($representative))
<div class="form-group">
    <label>Password</label>
    <input type="password" name="password" class="form-control" required>
</div>
@else
<div class="form-group">
    <label>Password (Leave blank to keep current)</label>
    <input type="password" name="password" class="form-control">
</div>
@endif

<div class="form-group">
    <label>Supplier</label>
    <select name="supplier_id" class="form-control" required>
        <option value="">Select Supplier</option>
        @foreach($suppliers as $supplier)
            <option value="{{ $supplier->id }}" 
                @if(isset($representative) {{ $representative->supplier_id == $supplier->id ? 'selected' : '' }}
                @else {{ old('supplier_id') == $supplier->id ? 'selected' : '' }} @endif>
                {{ $supplier->name }}
            </option>
        @endforeach
    </select>
</div>
