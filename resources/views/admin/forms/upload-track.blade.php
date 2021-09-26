<form action="{{ url("admin/upload-track") }}" method="POST" id="add-track" enctype="multipart/form-data">
@csrf
    <div class="modal fade" id="addTrack" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add new track</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body d-flex flex-column">
                    <label class="pt-1">
                        Title
                        <input type="text" class="form-control" name="title" id="title">
                    </label>
                    <label class="pt-3">
                        Name
                        <input type="text" class="form-control" name="name" id="name">
                        <input type="hidden" name="oldName" id="oldName">
                    </label>
                    <label class="pt-3">
                        Image
                        <input type="file" class="form-control" name="image" id="image">
                        <input type="hidden" name="oldImageUrl" id="oldImageUrl">
                    </label>
                    <label class="pt-3">
                        Theme
                        <select class="form-select" aria-label="Default select example" name="theme" id="theme">
                            <option selected>dark</option>
                            <option>light</option>
                        </select>
                    </label>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#addSocialLinks">Next</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal Add Track Page 2 -->
    <div class="modal fade" id="addSocialLinks" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add social links</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body d-flex flex-column">
                    <label class="pt-1">
                        SoundCloud
                        <input type="text" class="form-control" name="soundcloud" id="soundcloud">
                    </label>
                    <label class="pt-3">
                        Spotify
                        <input type="text" class="form-control" name="spotify" id="spotify">
                    </label>
                    <label class="pt-3">
                        iTunes
                        <input type="text" class="form-control" name="itunes" id="itunes">
                    </label>
                    <label class="pt-3">
                        YouTube Music
                        <input type="text" class="form-control" name="youtube-music" id="youtube-music">
                    </label>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#addTrack">Previous</button>
                    <button type="submit" class="btn btn-primary" id="uploadTrack">Save track</button>
                </div>
            </div>
        </div>
    </div>
</form>
@push('footer_scripts')
    <script>
        $(document).ready(function (){
            const MODAL_ADD_TEXT = 'Add new track';
            const MODAL_EDIT_TEXT = 'Edit track';

            $('.edit-track').on('click', function () {
                const trackName = $(this).parent().find('.track-card__name').text();

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: '{{ url('/admin/get-track/') }}' + '/' + trackName,
                    processData: false,
                    contentType : false,
                    success: function (response) {
                        $('#add-track').find('.modal-title').text(MODAL_EDIT_TEXT);

                        response = JSON.parse(response);
                        const social = JSON.parse(response.social_links);

                        // set form params
                        $('#title').val(response.title);
                        $('#name').val(response.name);
                        $('#oldName').val(response.name);
                        $('#oldImageUrl').val(response.image_url);
                        $('#theme').val(response.page_theme);
                        $('#addTrack').modal('show');

                        if(social !== null) {
                            $('#soundcloud').val(social.SoundCloud);
                            $('#spotify').val(social.Spotify);
                            $('#itunes').val(social.iTunes);
                            $('#youtube-music').val(social.YouTube);
                        }

                        // replace form action and method when user open modal page 2
                        $('#addSocialLinks').on('show.bs.modal', function(){
                            $('#add-track').attr('action', '/admin/update-track/' + response.name);
                        });

                        // if user close modal 2 without submit, return action and method back
                        $('#addSocialLinks').on('hidden.bs.modal', function () {
                            $('#add-track').find('.modal-title').text(MODAL_ADD_TEXT);
                            $('#add-track').attr('action', '/admin/update-track/' + response.name);
                        });

                        $('#addTrack').on('hidden.bs.modal', function () {
                            $('#add-track').find('.modal-title').text(MODAL_ADD_TEXT);
                        });
                    }
                });
            });
        });
    </script>
@endpush
