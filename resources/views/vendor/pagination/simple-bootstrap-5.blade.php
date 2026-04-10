@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="datatable-pagination">
        <ul class="pagination-list">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="pagination-item disabled">
                    <button type="button" class="pagination-btn" disabled aria-label="Página anterior">
                        ⟨
                    </button>
                </li>
            @else
                <li class="pagination-item">
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="pagination-btn" aria-label="Página anterior">
                        ⟨
                    </a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li class="pagination-item disabled">
                        <span class="pagination-dots">{{ $element }}</span>
                    </li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="pagination-item active">
                                <button type="button" class="pagination-btn active" aria-current="page">
                                    {{ $page }}
                                </button>
                            </li>
                        @else
                            <li class="pagination-item">
                                <a href="{{ $url }}" class="pagination-btn" aria-label="Página {{ $page }}">
                                    {{ $page }}
                                </a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="pagination-item">
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="pagination-btn" aria-label="Página siguiente">
                        ⟩
                    </a>
                </li>
            @else
                <li class="pagination-item disabled">
                    <button type="button" class="pagination-btn" disabled aria-label="Página siguiente">
                        ⟩
                    </button>
                </li>
            @endif
        </ul>
    </nav>
@endif
