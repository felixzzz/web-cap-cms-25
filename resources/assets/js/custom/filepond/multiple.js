var token = $('meta[name="csrf-token"]').attr('content');

const filePondElement = document.getElementById('filepond_single');
const pond = FilePond.create( filePondElement );

FilePond.setOptions({
    server: {
        url: '/ajax/upload/filepond',
        headers: {
            'X-CSRF-TOKEN' : token,
        }
    },
});
