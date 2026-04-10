@if ($paginator->hasPages())
    <div style="width: 100%; padding: 1.5rem; background: #ffffff; border: 1px solid #e0e3e8; border-radius: 0.5rem; margin-top: 1.5rem; box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);">
        <div style="display: flex; justify-content: space-between; align-items: center; gap: 2rem; flex-wrap: wrap;">
            <!-- Información de registros -->
            <div style="min-width: 280px; flex-shrink: 0;">
                <span style="font-size: 0.9rem; color: #55627d; font-weight: 500; letter-spacing: 0.2px;">
                    Mostrando <strong style="color: #1f2937; font-weight: 700;">{{ $paginator->firstItem() ?? 0 }}</strong> a 
                    <strong style="color: #1f2937; font-weight: 700;">{{ $paginator->lastItem() ?? 0 }}</strong> de 
                    <strong style="color: #1f2937; font-weight: 700;">{{ $paginator->total() }}</strong> registros
                </span>
            </div>

            <!-- Controles de paginación -->
            <nav aria-label="Paginación" style="margin: 0; padding: 0; display: flex; align-items: center; gap: 0.5rem;">
                <ul style="list-style: none; margin: 0; padding: 0; display: flex; gap: 0.25rem; align-items: center; background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 0.375rem; padding: 0.5rem;">
                    <!-- Primera página -->
                    @if ($paginator->onFirstPage())
                        <li style="display: inline-flex; margin: 0; padding: 0;">
                            <button type="button" disabled style="min-width: 36px; height: 36px; padding: 0.375rem 0.75rem; display: inline-flex; align-items: center; justify-content: center; border: 1px solid #d1d5db; background: #ffffff; color: #9ca3af; font-size: 0.875rem; font-weight: 500; border-radius: 0.375rem; cursor: not-allowed; opacity: 0.4;">«</button>
                        </li>
                        <li style="display: inline-flex; margin: 0; padding: 0;">
                            <button type="button" disabled style="min-width: 36px; height: 36px; padding: 0.375rem 0.75rem; display: inline-flex; align-items: center; justify-content: center; border: 1px solid #d1d5db; background: #ffffff; color: #9ca3af; font-size: 0.875rem; font-weight: 500; border-radius: 0.375rem; cursor: not-allowed; opacity: 0.4;">‹</button>
                        </li>
                    @else
                        <li style="display: inline-flex; margin: 0; padding: 0;">
                            <a href="{{ $paginator->url(1) }}" style="min-width: 36px; height: 36px; padding: 0.375rem 0.75rem; display: inline-flex; align-items: center; justify-content: center; border: 1px solid #d1d5db; background: #ffffff; color: #4b5563; font-size: 0.875rem; font-weight: 500; border-radius: 0.375rem; cursor: pointer; text-decoration: none; transition: all 0.15s ease;" onmouseover="this.style.background='#f3f4f6';this.style.borderColor='#9ca3af';" onmouseout="this.style.background='#ffffff';this.style.borderColor='#d1d5db';">«</a>
                        </li>
                        <li style="display: inline-flex; margin: 0; padding: 0;">
                            <a href="{{ $paginator->previousPageUrl() }}" style="min-width: 36px; height: 36px; padding: 0.375rem 0.75rem; display: inline-flex; align-items: center; justify-content: center; border: 1px solid #d1d5db; background: #ffffff; color: #4b5563; font-size: 0.875rem; font-weight: 500; border-radius: 0.375rem; cursor: pointer; text-decoration: none; transition: all 0.15s ease;" onmouseover="this.style.background='#f3f4f6';this.style.borderColor='#9ca3af';" onmouseout="this.style.background='#ffffff';this.style.borderColor='#d1d5db';">‹</a>
                        </li>
                    @endif

                    <!-- Números de página -->
                    @foreach ($elements as $element)
                        @if (is_string($element))
                            <li style="display: inline-flex; margin: 0; padding: 0;">
                                <span style="padding: 0.375rem 0.5rem; color: #9ca3af; font-weight: 500; font-size: 0.875rem;">{{ $element }}</span>
                            </li>
                        @endif

                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <li style="display: inline-flex; margin: 0; padding: 0;">
                                        <span style="min-width: 36px; height: 36px; padding: 0.375rem 0.75rem; display: inline-flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #0d6efd 0%, #0b5ed7 100%); border: 1px solid #0b5ed7; color: #ffffff; font-size: 0.875rem; font-weight: 600; border-radius: 0.375rem; cursor: default; box-shadow: 0 2px 6px rgba(13, 110, 253, 0.35);">{{ $page }}</span>
                                    </li>
                                @else
                                    <li style="display: inline-flex; margin: 0; padding: 0;">
                                        <a href="{{ $url }}" style="min-width: 36px; height: 36px; padding: 0.375rem 0.75rem; display: inline-flex; align-items: center; justify-content: center; border: 1px solid #d1d5db; background: #ffffff; color: #4b5563; font-size: 0.875rem; font-weight: 500; border-radius: 0.375rem; cursor: pointer; text-decoration: none; transition: all 0.15s ease;" onmouseover="this.style.background='#f3f4f6';this.style.borderColor='#9ca3af';" onmouseout="this.style.background='#ffffff';this.style.borderColor='#d1d5db';">{{ $page }}</a>
                                    </li>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    <!-- Última página y siguiente -->
                    @if ($paginator->hasMorePages())
                        <li style="display: inline-flex; margin: 0; padding: 0;">
                            <a href="{{ $paginator->nextPageUrl() }}" style="min-width: 36px; height: 36px; padding: 0.375rem 0.75rem; display: inline-flex; align-items: center; justify-content: center; border: 1px solid #d1d5db; background: #ffffff; color: #4b5563; font-size: 0.875rem; font-weight: 500; border-radius: 0.375rem; cursor: pointer; text-decoration: none; transition: all 0.15s ease;" onmouseover="this.style.background='#f3f4f6';this.style.borderColor='#9ca3af';" onmouseout="this.style.background='#ffffff';this.style.borderColor='#d1d5db';">›</a>
                        </li>
                        <li style="display: inline-flex; margin: 0; padding: 0;">
                            <a href="{{ $paginator->url($paginator->lastPage()) }}" style="min-width: 36px; height: 36px; padding: 0.375rem 0.75rem; display: inline-flex; align-items: center; justify-content: center; border: 1px solid #d1d5db; background: #ffffff; color: #4b5563; font-size: 0.875rem; font-weight: 500; border-radius: 0.375rem; cursor: pointer; text-decoration: none; transition: all 0.15s ease;" onmouseover="this.style.background='#f3f4f6';this.style.borderColor='#9ca3af';" onmouseout="this.style.background='#ffffff';this.style.borderColor='#d1d5db';">»</a>
                        </li>
                    @else
                        <li style="display: inline-flex; margin: 0; padding: 0;">
                            <button type="button" disabled style="min-width: 36px; height: 36px; padding: 0.375rem 0.75rem; display: inline-flex; align-items: center; justify-content: center; border: 1px solid #d1d5db; background: #ffffff; color: #9ca3af; font-size: 0.875rem; font-weight: 500; border-radius: 0.375rem; cursor: not-allowed; opacity: 0.4;">›</button>
                        </li>
                        <li style="display: inline-flex; margin: 0; padding: 0;">
                            <button type="button" disabled style="min-width: 36px; height: 36px; padding: 0.375rem 0.75rem; display: inline-flex; align-items: center; justify-content: center; border: 1px solid #d1d5db; background: #ffffff; color: #9ca3af; font-size: 0.875rem; font-weight: 500; border-radius: 0.375rem; cursor: not-allowed; opacity: 0.4;">»</button>
                        </li>
                    @endif
                </ul>
            </nav>
        </div>
    </div>
@endif
