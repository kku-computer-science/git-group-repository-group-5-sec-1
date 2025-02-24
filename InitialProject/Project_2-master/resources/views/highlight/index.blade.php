@extends('dashboards.users.layouts.user-dash-layout')
<link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.2.3/css/fixedHeader.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.12.0/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.2.3/css/fixedHeader.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@6.x/css/materialdesignicons.min.css">
<style type="text/css">
.dropdown-toggle {
        height: 40px;
        width: 400px !important;
    }

    body label:not(.input-group-text) {
        margin-top: 10px;
    }

    body .my-select {
        background-color: #EFEFEF;
        color: #212529;
        border: 0 none;
        border-radius: 10px;
        padding: 6px 20px;
        width: 100%;
    }

    .highlight-box {
        cursor: pointer;
        border: 2px solid #00000021;
        border-radius: 10px;
        height: 200px;
        display: flex;
        position: relative;
    }

    .highlight-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .highlight-placeholder {
        width: 100%;
        height: 100%;
        display: flex;
        justify-content: center;
        align-items: center;
        background-color: #0000000a;
    }

    .highlight-box .close-icon {
        position: absolute;
        top: 5px;
        right: 5px;
        font-size: 2vw;
        color: red;
        display: none;
        opacity: 0.75;
    }

    .highlight-box .close-icon:hover {
        color: darkred;
        opacity: 1;
    }

    .highlight-box:hover .close-icon {
        display: block;
    }

    .highlight-box:hover .topic {
        display: block;
    }

    .topic {
        display: none;
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background-color: #000000a1;
        color: white;
        padding: 5px;
        border-bottom-left-radius: 10px;
        border-bottom-right-radius: 10px;
        text-align: center;
    }

    .divider {
        margin: 20px 0;
        border-bottom: 1px solid #00000021;
    }

    #all-highlights {
        display: none;
        grid-template-columns: repeat(6, minmax(0, 1fr));
        gap: 10px;
    }

    #all-highlights .highlight-box {
        width: 100%;
        height: 100px;
        padding: 10px;
    }

    #all-highlights .highlight-box:hover {
        border: 2px solid #2781ff;
    }

    .selected {
        border: 2px solid #2781ff;
    }

    .selected::after {
        content: '✓';
        position: absolute;
        top: 5px;
        left: 5px;
        color: #2781ff;
        font-size: 16px;
        font-weight: bold;
    }

    .save-button {
        margin-top: 20px;
        padding: 10px 20px;
        background-color: #2781ff;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .save-button:hover {
        background-color: #1a66cc;
    }

    .save-button,
    .reset-button {
        margin-top: 20px;
        padding: 10px 20px;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    .save-button {
        background-color: #2781ff;
        margin-right: 10px;
    }

    .save-button:hover {
        background-color: #1a66cc;
    }

    .reset-button {
        background-color: #ff4444;
    }

    .reset-button:hover {
        background-color: #cc3333;
    }

    .save-button:disabled {
        background-color: #cccccc;
        cursor: not-allowed;
    }

    .close-icon.disabled {
        color: #cccccc;
        cursor: not-allowed;
        pointer-events: none;
    }
</style>
@section('content')
    <div class="container">
        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
        @endif
        <div class="card" style="padding: 16px;">
            <div class="card-body">
                <h4 class="card-title" style="text-align: center;">ไฮไลท์</h4>
                <div class="row" style="gap: 10px; justify-content: center; user-select: none;">
                    @for ($i = 1; $i <= 3; $i++)
                        <div id="highlight-{{ $i }}" onclick="selectHighlight('{{ $i }}', this)"
                            class="col-md-4 highlight-box" style="padding: 0; width: 30%;"
                            data-id="{{ $highlights->firstWhere('selected', $i) ? $highlights->firstWhere('selected', $i)->id : '' }}">
                            @if ($highlight = $highlights->firstWhere('selected', $i))
                                <i class="menu-icon mdi mdi-close-circle close-icon"
                                    onclick="deleteHighlight('{{ route('highlight.destroy', $highlight->id) }}', this)"></i>
                                <img src="{{ url('/highlight-image/' . $highlight->banner) }}"
                                    class="rounded img-fluid float-start" alt="{{ $highlight->topic }}">
                                <div class="topic">
                                    {{ Str::limit($highlight->topic, 90) }}
                                </div>
                            @else
                                <div class="highlight-placeholder">
                                    <i class="menu-icon mdi mdi-library-plus" style="font-size: 4vw; opacity: 0.75"></i>
                                </div>
                            @endif
                        </div>
                    @endfor
                </div>
                <div class="divider"></div>
                <h4 id="all-highlights-title" class="mb-3" style="text-align: center; display: none;">เลือกไฮไลท์</h4>
                <div id="all-highlights">
                    @for ($j = 0; $j < count($highlights); $j++)
                        @php
                            $highlight = $highlights[$j];
                            $isSelected = ($highlights->firstWhere('selected', 1) && $highlights->firstWhere('selected', 1)->id == $highlight->id) ||
                                        ($highlights->firstWhere('selected', 2) && $highlights->firstWhere('selected', 2)->id == $highlight->id) ||
                                        ($highlights->firstWhere('selected', 3) && $highlights->firstWhere('selected', 3)->id == $highlight->id);
                        @endphp
                        <div id="all-highlight-{{ $highlight->id }}"
                            class="highlight-box {{ $isSelected ? 'selected' : '' }}" style="padding: 0;"
                            data-id="{{ $highlight->id }}">
                            <img src="{{ url('/highlight-image/' . $highlight->banner) }}"
                                class="rounded img-fluid float-start" alt="{{ $highlight->topic }}">
                            <div class="topic">
                                {{ Str::limit($highlight->topic, 30) }}
                            </div>
                        </div>
                    @endfor
                </div>
                <div style="text-align: center; display: flex; justify-content: flex-end;">
                    <button class="save-button" id="saveButton" onclick="saveHighlights()" disabled>บันทึก</button>
                    <button class="reset-button" onclick="resetHighlights()">รีเซ็ต</button>
                </div>
            </div>
        </div>
    </div>
@stop
@section('javascript')
    <script>
        let initialHighlights = {};
        let selectedHighlightId = null;
        const allHighlights = document.getElementById('all-highlights');
        const allHighlightsTitle = document.getElementById('all-highlights-title');
        const saveButton = document.getElementById('saveButton');

        window.onload = function() {
            for (let i = 1; i <= 3; i++) {
                const highlight = document.getElementById(`highlight-${i}`);
                initialHighlights[i] = highlight.getAttribute('data-id') || null;
            }
            updateDeleteButtons();
        }

        function hasChanges() {
            for (let i = 1; i <= 3; i++) {
                const highlight = document.getElementById(`highlight-${i}`);
                const currentId = highlight.getAttribute('data-id') || null;
                if (currentId !== initialHighlights[i]) {
                    return true;
                }
            }
            return false;
        }

        function updateSaveButton() {
            saveButton.disabled = !hasChanges();
        }

        function updateDeleteButtons() {
            const hasUnsavedChanges = hasChanges();
            const closeIcons = document.querySelectorAll('.close-icon');
            closeIcons.forEach(icon => {
                if (hasUnsavedChanges) {
                    icon.classList.add('disabled');
                } else {
                    icon.classList.remove('disabled');
                }
            });
        }

        function deleteHighlight(url, element) {
            if (hasChanges()) {
                alert('Please save your changes before deleting a highlight!');
                return;
            }

            if (confirm('Are you sure you want to delete this highlight?')) {
                fetch(url, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        const highlightBox = element.closest('.highlight-box');
                        highlightBox.innerHTML = `
                            <div class="highlight-placeholder">
                                <i class="menu-icon mdi mdi-library-plus" style="font-size: 4vw; opacity: 0.75"></i>
                            </div>
                        `;
                        const highlightId = highlightBox.getAttribute('data-id');
                        const allHighlightItem = document.getElementById(`all-highlight-${highlightId}`);
                        if (allHighlightItem) {
                            allHighlightItem.classList.remove('selected');
                        }
                        highlightBox.setAttribute('data-id', '');
                        updateSaveButton();
                        updateDeleteButtons();
                    } else {
                        alert(data.message || 'Failed to delete highlight');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert(`An error occurred while deleting the highlight: ${error.message}`);
                });
            }
        }

        function selectHighlight(id, element) {
            allHighlights.style.display = 'grid';
            allHighlightsTitle.style.display = 'block';

            if (selectedHighlightId && selectedHighlightId !== id) {
                const previousElement = document.getElementById('highlight-' + selectedHighlightId);
                if (previousElement) {
                    previousElement.classList.remove('selected');
                }
            }

            if (selectedHighlightId === id) {
                element.classList.remove('selected');
                selectedHighlightId = null;
                allHighlights.style.display = 'none';
                allHighlightsTitle.style.display = 'none';
            } else {
                element.classList.add('selected');
                selectedHighlightId = id;

                const highlightBoxes = allHighlights.querySelectorAll('.highlight-box');
                highlightBoxes.forEach(box => {
                    box.onclick = function(e) {
                        e.stopPropagation();

                        const mainHighlight = document.getElementById(`highlight-${id}`);
                        const currentSelectedId = mainHighlight.getAttribute('data-id');

                        const selectedIds = [];
                        for (let i = 1; i <= 3; i++) {
                            const highlight = document.getElementById(`highlight-${i}`);
                            const highlightId = highlight.getAttribute('data-id');
                            if (highlightId && highlightId !== currentSelectedId) {
                                selectedIds.push(highlightId);
                            }
                        }

                        const newSelectedId = this.getAttribute('data-id');
                        if (selectedIds.includes(newSelectedId)) {
                            alert('This highlight is already selected in another position!');
                            return;
                        }

                        if (currentSelectedId) {
                            const prevSelected = document.getElementById(`all-highlight-${currentSelectedId}`);
                            if (prevSelected) {
                                prevSelected.classList.remove('selected');
                            }
                        }

                        this.classList.add('selected');
                        const imgSrc = this.querySelector('img').src;
                        const topic = this.querySelector('.topic').textContent;
                        mainHighlight.innerHTML = `
                            <i class="menu-icon mdi mdi-close-circle close-icon"
                               onclick="deleteHighlight('${mainHighlight.dataset.id}', this)"></i>
                            <img src="${imgSrc}" class="rounded img-fluid float-start" alt="${topic}">
                            <div class="topic">${topic}</div>
                        `;
                        mainHighlight.setAttribute('data-id', newSelectedId);
                        updateSaveButton();
                        updateDeleteButtons();
                    };
                });
            }
        }

        function saveHighlights() {
            const highlightsData = {};
            for (let i = 1; i <= 3; i++) {
                const highlight = document.getElementById(`highlight-${i}`);
                const id = highlight.getAttribute('data-id');
                highlightsData[i] = id ? parseInt(id) : null;
            }

            fetch('{{ route('highlight.save') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(highlightsData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Highlights saved successfully!');
                    // Reload the page after successful save
                    window.location.reload();
                } else {
                    alert(data.message || 'Failed to save highlights');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while saving highlights');
            });
        }

        function resetHighlights() {
            if (confirm('Are you sure you want to reset highlights to default order?')) {
                fetch('{{ route('highlight.reset') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const highlights = data.highlights;
                        for (let i = 1; i <= 3; i++) {
                            const highlightBox = document.getElementById(`highlight-${i}`);
                            const highlight = highlights[i - 1];
                            if (highlight) {
                                highlightBox.innerHTML = `
                                    <i class="menu-icon mdi mdi-close-circle close-icon"
                                       onclick="deleteHighlight('${highlight.destroy_route}', this)"></i>
                                    <img src="${highlight.image_url}"
                                         class="rounded img-fluid float-start" alt="${highlight.topic}">
                                    <div class="topic">${highlight.topic.substring(0, 90)}</div>
                                `;
                                highlightBox.setAttribute('data-id', highlight.id);
                            } else {
                                highlightBox.innerHTML = `
                                    <div class="highlight-placeholder">
                                        <i class="menu-icon mdi mdi-library-plus" style="font-size: 4vw; opacity: 0.75"></i>
                                    </div>
                                `;
                                highlightBox.setAttribute('data-id', '');
                            }
                        }

                        const allHighlightBoxes = allHighlights.querySelectorAll('.highlight-box');
                        allHighlightBoxes.forEach(box => {
                            box.classList.remove('selected');
                            const id = box.getAttribute('data-id');
                            if (highlights.some(h => h.id == id)) {
                                box.classList.add('selected');
                            }
                        });

                        alert('Highlights reset successfully!');
                        allHighlights.style.display = 'none';
                        allHighlightsTitle.style.display = 'none';
                        if (selectedHighlightId) {
                            document.getElementById(`highlight-${selectedHighlightId}`).classList.remove('selected');
                            selectedHighlightId = null;
                        }
                        for (let i = 1; i <= 3; i++) {
                            initialHighlights[i] = highlights[i-1] ? highlights[i-1].id : null;
                        }
                        updateSaveButton();
                        updateDeleteButtons();
                    } else {
                        alert(data.message || 'Failed to reset highlights');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while resetting highlights');
                });
            }
        }
    </script>
@endsection
