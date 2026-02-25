<!-- Form Input Component -->
<div class="mb-3">
    <label for="{{ $name }}" class="form-label">
        {{ $label }}
        @if($required ?? false)
            <span class="text-danger">*</span>
        @endif
    </label>
    <input 
        type="{{ $type ?? 'text' }}"
        class="form-control @error($name) is-invalid @enderror"
        id="{{ $name }}"
        name="{{ $name }}"
        value="{{ old($name, $value ?? '') }}"
        {{ $required ?? false ? 'required' : '' }}
        {{ $attributes }}
    >
    @error($name)
        <div class="invalid-feedback d-block">
            <i class="bi bi-exclamation-circle"></i> {{ $message }}
        </div>
    @enderror
</div>
