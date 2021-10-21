@include('admin.inputs.label', ['label' => $label, 'name' => $name])
<textarea
    name="{{ $name }}"
    id="input-{{ $name }}"
    rows="{{ isset($rows) ? $rows : 3 }}"
    class="@error($name) border-red-200 @else border-gray-200 @enderror block w-full mt-1 p-1 border-solid border-2  focus:border-blue-300 outline-none"
    placeholder="{{ $placeholder }}"
>{{old($name, $value) }}</textarea>
@include('admin.inputs.error', ['name' => $name])

