<li class="relative px-6 py-3">
              <span
                  class="absolute inset-y-0 left-0 w-1 rounded-tr-lg rounded-br-lg @if($active) bg-purple-600  @endif"
                  aria-hidden="true"
              ></span>
    <a
        class="inline-flex items-center w-full text-sm font-semibold text-white transition-colors duration-150 hover:text-blue-300 dark:hover:text-gray-200 dark:text-gray-100"
        href="{{ $to }}"
    >
        <span class="nav-icon">
                    {{ $slot }}
        </span>
        <span class="ml-4">{{ $label }}</span>
    </a>
</li>
