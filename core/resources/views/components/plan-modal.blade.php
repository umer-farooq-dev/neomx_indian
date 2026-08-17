@auth
 <!-- Modal -->
  <div class="modal fade" id="planModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="planModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title method-name" id="planModalLabel"></h3>
          <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close">
            <i class="las la-times"></i>
          </button>
        </div>
        <div class="modal-body">
          <form action="{{route('user.investment')}}" method="post" class="account-form login-form">
            @csrf

            <div class="form-group">
                <input type="hidden" name="id" required>
            </div>

            <div class="form-group">
                <label>@lang('Enter Amount')</label>
                <div class="input-group">
                    <input id="amount" type="text" class="form--control" name="amount" required  value="{{old('amount')}}" onkeyup="this.value = this.value.replace (/^\.|[^\d\.]/g, '')">
                    <span class="input-group-text bg--base">{{__(gs('cur_text'))}}</span>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-6 mb-2">
                    <button type="button" class="btn w-100 bg--danger text-white" data-bs-dismiss="modal">@lang('Close')</button>
                </div>
                <div class="col-lg-6">
                    <button type="submit" class="btn btn--base w-100">@lang('Confirm')</button>
                </div>
            </div>
          </form>

        </div>
      </div>
    </div>
  </div>
@endauth



@auth
    @push('script')
    <script>
        (function ($) {
            "use strict";
            $('.planModal').on('click', function () {
                var modal = $('#planModal');
                modal.find('input[name=id]').val($(this).data('id'));
                modal.find($('#planModalLabel').text($(this).data('name')));
            });
        })(jQuery);
    </script>
    @endpush
@endauth