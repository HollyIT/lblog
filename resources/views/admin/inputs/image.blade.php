@include('admin.inputs.label', ['label' => $label, 'name' => $name])
<div class="flex gap-2 border-2 border-solid border-gray-200 p-4">
    <div>
        @if($value)
            <div>
                <img src="{{ $value }}" />
            </div>

            <label class="inline-flex items-center" for="remove-image-{{ $name }}">
                <input id="remove-image-{{ $name }}" type="checkbox" name="{{ $removeName ?? $name . '_remove' }}" />
                <span class="ml-2">Remove Image</span>
            </label>
            @endif
    </div>
    <div>
        <input
            type="file"
            name="{{ $name }}"
            id="input-{{ $name }}"
            class="@error($name) border-red-200 @else border-gray-200 @enderror block w-full mt-1 p-1 border-solid border-2  focus:border-blue-300 outline-none"
            placeholder="{{ $placeholder ?? '' }}"
            value="{{ $value }}"
        />
        @include('admin.inputs.error', ['name' => $name])
    </div>
</div>


