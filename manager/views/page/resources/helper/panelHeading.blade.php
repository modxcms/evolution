<div class="panel-heading">
    <span class="panel-title">
        <a class="accordion-toggle" id="toggle{{ $tabName }}{{ $catid }}" href="#collapse{{ $tabName }}{{ $catid }}" data-cattype="{{ $tabName }}" data-catid="{{ $catid }}" title="Click to toggle collapse. Shift+Click to toggle all.">
            <span class="category_name">
                <strong>
                    {{ $category }}
                    <small>({{ $cat->id }})</small>
                </strong>
            </span>
        </a>
    </span>
</div>
