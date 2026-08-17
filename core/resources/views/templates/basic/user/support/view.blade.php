@extends($activeTemplate . 'layouts.' . $layout)
@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card custom--card">
                    <div class="card-header card-header-bg d-flex flex-wrap justify-content-between align-items-center">
                        <h5 class="text-white mt-0">
                            @php echo $myTicket->statusBadge; @endphp
                            [@lang('Ticket')#{{ $myTicket->ticket }}] {{ $myTicket->subject }}
                        </h5>
                        @if ($myTicket->status != Status::TICKET_CLOSE && $myTicket->user)
                            <button class="btn btn-danger close-button btn-sm confirmationBtn" type="button"
                                data-question="@lang('Are you sure to close this ticket?')" data-action="{{ route('ticket.close', $myTicket->id) }}"><i
                                    class="fas fa-lg fa-times-circle"></i>
                            </button>
                        @endif
                    </div>
                    <div class="card-body">
                        <form method="post" class="disableSubmission" action="{{ route('ticket.reply', $myTicket->id) }}"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row justify-content-between">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <textarea name="message" class="form-control form--control" rows="4" required>{{ old('message') }}</textarea>
                                    </div>
                                </div>

                                <div class="col-md-9">
                                    <button type="button" class="btn btn--base btn-sm addAttachment my-2"> <i
                                            class="fas fa-plus"></i> @lang('Add Attachment') </button>
                                    <p class="mb-2"><span class="text--info">@lang('Max 5 files can be uploaded | Maximum upload size is ' . convertToReadableSize(ini_get('upload_max_filesize')) . ' | Allowed File Extensions: .jpg, .jpeg, .png, .pdf, .doc, .docx')</span></p>
                                    <div class="row fileUploadsContainer">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <button class="btn btn--base btn-sm w-100 my-2" type="submit"><i
                                            class="la la-fw la-lg la-reply"></i> @lang('Reply')
                                    </button>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>
                <div class="contact-wrapper rounded-3 mt-4">
                    @foreach ($messages as $message)
                        @if ($message->admin_id == 0)
                            <div class="row border border-primary border-radius-3 my-3 py-3 mx-2">
                                <div class="col-md-3 border-end text-end">
                                    <h5 class="my-3">{{ $message->ticket->name }}</h5>
                                </div>
                                <div class="col-md-9">
                                    <small class="text-muted fw-bold my-3">
                                        @lang('Posted on') {{ $message->created_at->format('l, dS F Y @ H:i') }}</small>
                                    <p>{{ $message->message }}</p>
                                    @if ($message->attachments->count() > 0)
                                        <div class="mt-2">
                                            @foreach ($message->attachments as $k => $image)
                                                <a href="{{ route('ticket.download', encrypt($image->id)) }}"
                                                    class="me-3"><i class="la la-file"></i> @lang('Attachment')
                                                    {{ ++$k }} </a>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @else
                            <div class="row border border-warning border-radius-3 my-3 py-3 mx-2"
                                style="background-color: #ffd96729">
                                <div class="col-md-3 border-end text-end">
                                    <h5 class="my-3">{{ $message->admin->name }}</h5>
                                    <p class="lead text-muted">@lang('Staff')</p>
                                </div>
                                <div class="col-md-9">
                                    <p class="text-muted fw-bold my-3">
                                        @lang('Posted on') {{ $message->created_at->format('l, dS F Y @ H:i') }}</p>
                                    <p>{{ $message->message }}</p>
                                    @if ($message->attachments->count() > 0)
                                        <div class="mt-2">
                                            @foreach ($message->attachments as $k => $image)
                                                <a href="{{ route('ticket.download', encrypt($image->id)) }}"
                                                    class="me-3"><i class="la la-file"></i> @lang('Attachment')
                                                    {{ ++$k }} </a>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>

            </div>
        </div>
    </div>

    <!-- Close Confirmation Modal- --->
    <div id="confirmationModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Confirmation Alert!')</h5>
                    <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close">
                        <i class="la la-times"></i>
                    </button>
                </div>
                <form action="" method="POST">
                    @csrf
                    <div class="modal-body">
                        <p class="question"> @lang('Are you sure to close this ticket?')</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn--danger text-white"
                            data-bs-dismiss="modal">@lang('No')</button>
                        <button type="submit" class="btn btn--base">@lang('Yes')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('style')
    <style>
        .input-group-text:focus {
            box-shadow: none !important;
        }

        .reply-bg {
            background-color: #ffd96729
        }

        .empty-message img {
            width: 120px;
            margin-bottom: 15px;
        }
    </style>
@endpush



@push('style-lib')
<link rel="stylesheet" href="{{ asset('assets/global/css/select2.min.css') }}">
@endpush

@push('script-lib')
<script src="{{ asset('assets/global/js/select2.min.js') }}"></script>
@endpush


@push('script')
    <script>
        (function($) {
            "use strict";
            $(document).on('click', '.confirmationBtn', function() {
                var modal = $('#confirmationModal');
                let data = $(this).data();
                modal.find('.question').text(`${data.question}`);
                modal.find('form').attr('action', `${data.action}`);
                modal.modal('show');
            });
        })(jQuery);
    </script>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";
            var fileAdded = 0;
            $('.addAttachment').on('click', function() {
                fileAdded++;
                if (fileAdded == 5) {
                    $(this).attr('disabled', true)
                }
                $(".fileUploadsContainer").append(`
                    <div class="col-lg-4 col-md-12 removeFileInput">
                        <div class="form-group">
                            <div class="input-group">
                                <input type="file" name="attachments[]" class="form-control form--control" accept=".jpeg,.jpg,.png,.pdf,.doc,.docx" required>
                                <button type="button" class="input-group-text removeFile bg--danger border--danger"><i class="fas fa-times text-white"></i></button>
                            </div>
                        </div>
                    </div>
                `)
            });
            $(document).on('click', '.removeFile', function() {
                $('.addAttachment').removeAttr('disabled', true)
                fileAdded--;
                $(this).closest('.removeFileInput').remove();
            });
        })(jQuery);
    </script>
@endpush
