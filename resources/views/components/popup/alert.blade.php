<div class="alert alert-important alert-{{ $type }} alert-dismissible" role="alert">
    <div class="d-flex">
        @if ($type === 'success')
            <div>
                <!-- Download SVG icon from http://tabler-icons.io/i/check -->
                <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24"
                    stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M5 12l5 5l10 -10" /></svg>
            </div>
        @elseif ($type === 'warning')
            <div>
                <!-- Download SVG icon from http://tabler-icons.io/i/alert-triangle -->
                <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24"
                    stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path
                        d="M10.24 3.957l-8.422 14.06a1.989 1.989 0 0 0 1.7 2.983h16.845a1.989 1.989 0 0 0 1.7 -2.983l-8.423 -14.06a1.989 1.989 0 0 0 -3.4 0z" />
                    <path d="M12 9v4" />
                    <path d="M12 17h.01" /></svg>
            </div>
        @elseif ($type === 'danger')
            <div>
                <!-- Download SVG icon from http://tabler-icons.io/i/alert-circle -->
                <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24"
                    stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0" />
                    <path d="M12 8v4" />
                    <path d="M12 16h.01" /></svg>
            </div>
        @elseif ($type === 'info')
            <div>
                <!-- Download SVG icon from http://tabler-icons.io/i/information-circle -->
                <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24" viewBox="0 0 24 24"
                    stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <circle cx="12" cy="12" r="9" />
                    <path d="M9 12l2 2l4 -4" /></svg>
            </div>
        @endif
        <div>
            <span class="me-2">{{ $message }}</span>
            @if ($type === 'info')
            <button class="btn btn-sm btn-success" onclick="copyToClipboard('{{ $message }}')">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24"
                    stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                    <path d="M14 3v4a1 1 0 0 0 1 1h4" />
                    <path d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z" /></svg>
            </button>
                
            @endif
        </div>
    </div>
    <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
</div>

@push('after-scripts')
    <script>
        function copyToClipboard(text) {
            const input = document.createElement('textarea');
            document.body.appendChild(input);
            input.value = text;
            input.select();
            document.execCommand('copy');
            input.remove();
            alert('Berhasil disalin ke clipboard!');
        }
    </script>
@endpush

