
<div class="row p-0">
    <div class="col-md-12 clear-fix p-0">
        <div class="col-md-6 float-start p-3 ps-4">Showing {{ $paginator->firstItem() }} to {{ $paginator->lastItem() }} of {{ $paginator->total() }} entries</div>
        <div class="col-md-6 float-end">
            <nav aria-label="..." class="p-0 mt-2">
                <ul class="pagination justify-content-end pagination-primary">
                    @if ($paginator->onFirstPage())
                        <li class="page-item disabled"><a class="page-link" href="javascript:void(0)"
                                tabindex="-1">Previous</a></li>
                    @else
                        <li class="page-item"><a class="page-link"  href="{{ $paginator->previousPageUrl() }}"
                                tabindex="-1">Previous</a></li>
                    @endif

                    {{-- Pagination Element Here --}}
                    @foreach ($elements as $element)
                        {{-- Make dots here --}}
                        @if (is_string($element))
                            <li class="page-item disabled"><a class="page-link"><span>{{ $element }}</span></a></li>
                        @endif

                        {{-- Links array Here --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <li class="page-item active" aria-current="page">
                                        <a href="javascript:;"
                                            class="page-link"><span>{{ $page }}</span></a>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a href="{{ $url }}"
                                            class="page-link">{{ $page }}</a>
                                    </li>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                        <li class="page-item"><a class="page-link" href="{{ $paginator->nextPageUrl() }}">Next</a></li>
                    @else
                        <li class="page-item disabled"><a class="page-link" href="javascript:void(0)">Next</a></li>
                    @endif
                </ul>
            </nav>

        </div>
    </div>
</div>

