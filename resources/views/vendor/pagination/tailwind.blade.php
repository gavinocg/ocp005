@if ($paginator->hasPages())
    <div class="datatable-pagination">
        <div class="pagination-wrapper">
            {{-- Info Section --}}
            <div class="pagination-info">
                <span class="info-text">
                    Mostrando <strong>{{ $paginator->firstItem() ?? 0 }}</strong> a 
                    <strong>{{ $paginator->lastItem() ?? 0 }}</strong> de 
                    <strong>{{ $paginator->total() }}</strong> registros
                </span>
            </div>

            {{-- Pagination Controls --}}
            <nav aria-label="Table pagination" class="pagination-nav">
                <ul class="pagination-list">
                    {{-- First Page --}}
                    @if ($paginator->onFirstPage())
                        <li class="pagination-item disabled">
                            <button type="button" class="pagination-btn" disabled aria-label="Primera página" title="Primera página">
                                ⟨⟨
                            </button>
                        </li>
                    @else
                        <li class="pagination-item">
                            <a href="{{ $paginator->url(1) }}" class="pagination-btn" aria-label="Primera página" title="Primera página">
                                ⟨⟨
                            </a>
                        </li>
                    @endif

                    {{-- Previous Page --}}
                    @if ($paginator->onFirstPage())
                        <li class="pagination-item disabled">
                            <button type="button" class="pagination-btn" disabled aria-label="Página anterior" title="Anterior">
                                ⟨
                            </button>
                        </li>
                    @else
                        <li class="pagination-item">
                            <a href="{{ $paginator->previousPageUrl() }}" class="pagination-btn" rel="prev" aria-label="Página anterior" title="Anterior">
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
                                        <button type="button" class="pagination-btn active" aria-current="page" aria-label="Página {{ $page }}, página actual">
                                            {{ $page }}
                                        </button>
                                    </li>
                                @else
                                    <li class="pagination-item">
                                        <a href="{{ $url }}" class="pagination-btn" aria-label="Página {{ $page }}" title="Página {{ $page }}">
                                            {{ $page }}
                                        </a>
                                    </li>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next Page --}}
                    @if ($paginator->hasMorePages())
                        <li class="pagination-item">
                            <a href="{{ $paginator->nextPageUrl() }}" class="pagination-btn" rel="next" aria-label="Página siguiente" title="Siguiente">
                                ⟩
                            </a>
                        </li>
                    @else
                        <li class="pagination-item disabled">
                            <button type="button" class="pagination-btn" disabled aria-label="Página siguiente" title="Siguiente">
                                ⟩
                            </button>
                        </li>
                    @endif

                    {{-- Last Page --}}
                    @if ($paginator->hasMorePages())
                        <li class="pagination-item">
                            <a href="{{ $paginator->url($paginator->lastPage()) }}" class="pagination-btn" aria-label="Última página" title="Última página">
                                ⟩⟩
                            </a>
                        </li>
                    @else
                        <li class="pagination-item disabled">
                            <button type="button" class="pagination-btn" disabled aria-label="Última página" title="Última página">
                                ⟩⟩
                            </button>
                        </li>
                    @endif
                </ul>
            </nav>
        </div>
    </div>
@endif
