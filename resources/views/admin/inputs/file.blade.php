@include('admin.inputs.label', ['label' => $label, 'name' => $name])
<input
    type="file"
    name="{{ $name }}"
    id="input-{{ $name }}"
    class="@error($name) border-red-200 @else border-gray-200 @enderror block w-full mt-1 p-1 border-solid border-2  focus:border-blue-300 outline-none"
    placeholder="{{ $placeholder }}"
    value="{{ $value }}"
/>
@include('admin.inputs.error', ['name' => $name])

