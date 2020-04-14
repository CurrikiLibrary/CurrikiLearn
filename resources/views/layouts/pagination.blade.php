@if ($paginator->hasPages())
    <nav class="pagination-block bg-white-block" aria-label="Page navigation">
        <ul class="pagination pagination-circular justify-content-center">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage() === false)
                <li class="page-item disabled">
					<a class="page-link page-prev" href="#" aria-disabled="true" aria-label="@lang('pagination.previous')">
						<span aria-hidden="true"></span>
						<span class="sr-only">Previous</span>
					</a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="page-item disabled" aria-disabled="true"><span>{{ $element }}</span></li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
							<li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                        @else
						<li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
				<li class="page-item">
					<a class="page-link page-next" href="{{ $paginator->nextPageUrl() }}" rel="next" aria-label="@lang('pagination.next')">
						<span aria-hidden="true"></span>
						<span class="sr-only">Next</span>
					</a>
				</li>
            @endif
        </ul>
    </nav>
@endif