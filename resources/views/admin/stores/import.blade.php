@extends('layouts.metronic.base')

@section('content')
<div class="kt-portlet">
    <div class="kt-portlet__head">
        <div class="kt-portlet__head-label">
            <h3 class="kt-portlet__head-title">
                Import Products
            </h3>
        </div>
    </div>

    <form action="{{ route('admin.stores.import-products', $store) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="kt-portlet__body">
            <div class="form-group">
                <label for="file">Select Excel File</label>
                <div class="custom-file">
                    <input type="file" class="custom-file-input" id="file" name="file" accept=".xlsx,.xls,.csv" required>
                    <label class="custom-file-label" for="file">Choose file</label>
                </div>
                @error('file')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="kt-portlet__foot">
            <div class="kt-form__actions">
                <button type="submit" class="btn btn-primary">Import</button>
                <a href="{{ route('admin.stores.show', $store) }}" class="btn btn-secondary">Cancel</a>
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
    // Update file input label with selected file name
    document.querySelector('.custom-file-input').addEventListener('change', function(e) {
        var fileName = e.target.files[0].name;
        var nextSibling = e.target.nextElementSibling;
        nextSibling.innerText = fileName;
    });
</script>
@endsection
