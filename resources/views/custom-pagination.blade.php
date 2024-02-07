


<nav>
    <ul class="pagination">
        @if($paginator->hasPages())
            <li class="page-item">
                <a class="page-link" href="#" onclick="loadPage('{{$paginator->previousPageUrl()}}')"  rel="prev" aria-label="« Previous" >‹</a>
            </li>

    
            @if ($paginator->lastPage() <= 8)
                @for ($i = 1; $i <= $paginator->lastPage(); $i++)
                    <li class="page-item {{ ($paginator->currentPage() == $i) ? 'active' : '' }}">
                        <span class="page-link" onclick="loadPage('{{$paginator->url($i)}}')">{{ $i }}</span>
                    </li>
                @endfor
            @else
                @if ($paginator->currentPage() <= 4)
                    @for ($i = 1; $i <= 8; $i++)
                        <li class="page-item {{ ($paginator->currentPage() == $i) ? 'active' : '' }}">
                            <span class="page-link" onclick="loadPage('{{$paginator->url($i)}}')">{{ $i }}</span>
                        </li>
                    @endfor
                @elseif ($paginator->currentPage() >= $paginator->lastPage() - 3)
                    @for ($i = $paginator->lastPage() - 7; $i <= $paginator->lastPage(); $i++)
                        <li class="page-item {{ ($paginator->currentPage() == $i) ? 'active' : '' }}">
                            <span class="page-link" onclick="loadPage('{{$paginator->url($i)}}')">{{ $i }}</span>
                        </li>
                    @endfor
                @else
                    @for ($i = $paginator->currentPage() - 3; $i <= $paginator->currentPage() + 4; $i++)
                        <li class="page-item {{ ($paginator->currentPage() == $i) ? 'active' : '' }}">
                            <span class="page-link" onclick="loadPage('{{$paginator->url($i)}}')">{{ $i }}</span>
                        </li>
                    @endfor
                @endif
            @endif

            <li class="page-item">
                 <a class="page-link" href="#" onclick="loadPage('{{$paginator->nextPageUrl()}}')" rel="next" aria-label="Next »">›</a>
            </li>
        @endif
    </ul>
</nav>
