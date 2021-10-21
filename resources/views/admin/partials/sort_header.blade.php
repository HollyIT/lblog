<?php
/**
 * @var string $key
 * @var string $sorted
 * @var string $label
 * @var string $name
 */
$sortKey = $key;
if ($sorted === $key) {
    $sortDir = 'asc';
    $sortKey = '-' . $key;
} elseif ($sorted === '-'.$key) {
    $sortDir = 'desc';
} else {
    $sortDir = null;

}


?>
<th class="px-5 py-3 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase bg-gray-100 border-b-2 border-gray-200">
    <form method="get">
        <button class="flex items-center" name="sort" value="{{ $sortKey }}">
          <span>
            @if($sortDir === 'desc')
                <svg style="width:24px;height:24px" viewBox="0 0 24 24">
                 <path fill="currentColor" d="M7,10L12,15L17,10H7Z"/>
                </svg>
              @elseif ($sortDir ==='asc')
                  <svg style="width:24px;height:24px" viewBox="0 0 24 24">
                    <path fill="currentColor" d="M7,15L12,10L17,15H7Z"/>
                </svg>
              @else
                  <svg style="width:24px;height:24px" viewBox="0 0 24 24">
                    <path fill="currentColor" d="M12,6L7,11H17L12,6M7,13L12,18L17,13H7Z"/>
                </svg>
              @endif
          </span>
            <span>
                {{ $label }}
            </span>
        </button>
    </form>
</th>
