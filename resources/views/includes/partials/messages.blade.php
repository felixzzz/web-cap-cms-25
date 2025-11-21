@if(isset($errors) && $errors->any())
    <x-utils.alert type="danger" class="d-flex align-items-center p-5 mb-10">
        @foreach($errors->all() as $error)
            {{ $error }}<br/>
        @endforeach
    </x-utils.alert>
@endif

@push('toast')
    <script>
        window.addEventListener('load', function () {
            toastr.options = {
                "closeButton": true,
                "debug": false,
                "newestOnTop": false,
                "progressBar": false,
                "positionClass": "toast-top-right",
                "preventDuplicates": false,
                "onclick": null,
                "showDuration": "60000",
                "hideDuration": "1000",
                "timeOut": "5000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            }

            @if(session()->get('flash_success'))
            toastr.success('{{ session()->get('flash_success') }}', 'Success')
            @endif

            @if(session()->get('flash_warning'))
            toastr.warning('{{ session()->get('flash_warning') }}', 'Oops')
            @endif

            @if(session()->get('flash_info') || session()->get('flash_message'))
            toastr.info('{{ session()->get('flash_info') }}', 'Info')
            @endif

            @if(session()->get('flash_danger'))
            toastr.error('{{ session()->get('flash_danger') }}', 'Oops')
            @endif

            @if(session()->get('status'))
            toastr.success('{{ session()->get('status') }}')
            @endif

            @if(session()->get('resent'))
            toastr.success('A fresh verification link has been sent to your email address.')
            @endif

            @if(session()->get('verified'))
            toastr.success('Thank you for verifying your e-mail address.')
            @endif

        })

    </script>

@endpush
