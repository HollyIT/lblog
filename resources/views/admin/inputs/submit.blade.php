@if(!empty($secondary))
    <button
        class="p-2 pl-5 pr-5 bg-gray-500 text-gray-100 text-lg rounded-lg focus:border-4 border-gray-300">{{ $label }}</button>
@else
    <button
        class="p-2 pl-5 pr-5 bg-blue-500 text-gray-100 text-lg rounded-lg focus:border-4 border-blue-300">{{ $label }}</button>
@endif
