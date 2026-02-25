<!-- Form Select Component -->
<div class="mb-3">
    <label for="{{ $name }}" class="form-label">
        {{ $label }}
        @if($required ?? false)
            <span class="text-danger">*</span>
        @endif
    </label>
    <select 
        class="form-select @error($name) is-invalid @enderror"
        id="{{ $name }}"
        name="{{ $name }}"
        {{ $required ?? false ? 'required' : '' }}
        {{ $attributes }}
    >
        <option value="">-- Selecione uma opção --</option>
        @foreach($options as $optionValue => $optionLabel)
            <option value="{{ $optionValue }}" @selected(old($name, $value ?? '') == $optionValue)>
                {{ $optionLabel }}
            </option>
        @endforeach
    </select>
    @error($name)
        <div class="invalid-feedback d-block">
            <i class="bi bi-exclamation-circle"></i> {{ $message }}
        </div>
    @enderror
</div>
