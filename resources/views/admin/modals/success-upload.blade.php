<!-- Modal Success Uploaded -->
<div class="modal fade" id="successUploaded" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Track was successfully updated!</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Now your track is available at this link:</p>
                <a href="" id="trackLink" target="_blank"></a>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@push('footer_scripts')
    <script>
        $(document).ready(function (){
            const createdTrackModal = new bootstrap.Modal(document.querySelector('#successUploaded'));

            @if(\Session::has('trackLink'))
                $('#trackLink').text('{!! \Session::get('trackLink') !!}');
                {{ \Session::forget('trackLink') }}
                createdTrackModal.show();
            @endif

            if(sessionStorage.getItem('removedTrack') !== null) {
                $('.modal-body').find('p').text('Track was successfully removed');
                $('#trackLink').text('');
                createdTrackModal.show();
                sessionStorage.removeItem('removedTrack');
            }
        });
    </script>
@endpush
