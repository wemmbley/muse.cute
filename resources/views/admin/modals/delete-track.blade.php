<!-- Modal Delete Track -->
<div class="modal fade" id="deleteTrack" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Are you sure want to remove this track?</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="removeButton">Yes, remove</button>
            </div>
        </div>
    </div>
</div>
@push('footer_scripts')
    <script>
        $(document).ready(function (){
            $('.delete-track').on('click', function (){
                const that = this;
                const deleteTrackModal = new bootstrap.Modal(document.querySelector('#deleteTrack'));
                deleteTrackModal.show();

                $('#removeButton').on('click', function() {
                    const trackName = $(that).parent().find('.track-card__name').text();

                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: '{{ url('/admin/delete-track') }}',
                        contentType: "application/json; charset=utf-8",
                        processData: false,
                        method: 'POST',
                        data: JSON.stringify({
                            trackName: trackName,
                        }),
                        dataType: 'json',
                        success: function () {
                            sessionStorage.setItem('removedTrack', '1');
                            location.reload();
                        }
                    });
                });
            })
        });
    </script>
@endpush
