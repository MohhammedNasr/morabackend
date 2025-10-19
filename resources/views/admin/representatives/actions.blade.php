<div class="btn-group">
    <button type="button" class="btn btn-sm btn-primary" onclick="editRepresentative({{ $representative->id }})">
        <i class="fas fa-edit"></i>
    </button>
    <button type="button" class="btn btn-sm btn-danger" onclick="deleteRepresentative({{ $representative->id }})">
        <i class="fas fa-trash"></i>
    </button>
</div>
