<!-- Form Textarea Component -->
<div class="mb-3">
    <label for="{{ $name }}" class="form-label">
        {{ $label }}
        @if($required ?? false)
            <span class="text-danger">*</span>
        @endif
    </label>
    <textarea 
        class="form-control @error($name) is-invalid @enderror"
        id="{{ $name }}"
        name="{{ $name }}"
        rows="{{ $rows ?? 4 }}"
        {{ $required ?? false ? 'required' : '' }}
        {{ $attributes }}
    >{{ old($name, $value ?? '') }}</textarea>
    @error($name)
        <div class="invalid-feedback d-block">
            <i class="bi bi-exclamation-circle"></i> {{ $message }}
        </div>
    @enderror
</div>
