@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Edit Static Page</h4>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin.static-pages.update', $page->id) }}">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="title_en">English Title</label>
                    <input type="text" class="form-control" id="title_en" name="title_en"
                           value="{{ old('title_en', $page->title_en) }}" required>
                </div>

                <div class="form-group">
                    <label for="title_ar">Arabic Title</label>
                    <input type="text" class="form-control" id="title_ar" name="title_ar"
                           value="{{ old('title_ar', $page->title_ar) }}" required>
                </div>

                <div class="form-group">
                    <label for="details_en">English Content</label>
                    <textarea class="form-control" id="details_en" name="details_en" rows="5" required>
                        {{ old('details_en', $page->details_en) }}
                    </textarea>
                </div>

                <div class="form-group">
                    <label for="details_ar">Arabic Content</label>
                    <textarea class="form-control" id="details_ar" name="details_ar" rows="5" required>
                        {{ old('details_ar', $page->details_ar) }}
                    </textarea>
                </div>

                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('admin.static-pages.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection
