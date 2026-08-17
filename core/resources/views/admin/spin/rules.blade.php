@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--md  table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('S.N.')</th>
                                    <th>@lang('Name')</th>
                                    <th>@lang('Trigger')</th>
                                    <th>@lang('Spins Granted')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($rules as $rule)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ __($rule->name) }}</td>
                                        <td>
                                            @if ($rule->trigger_type == 'signup')
                                                <span class="badge badge--info">@lang('On Signup')</span>
                                            @else
                                                <span class="badge badge--info">@lang('Every') {{ $rule->trigger_value }} @lang('active referrals')</span>
                                            @endif
                                        </td>
                                        <td>{{ $rule->spins_granted }} @lang('spin(s)')</td>
                                        <td>@php echo $rule->statusBadge @endphp</td>
                                        <td>
                                            <div class="button--group">
                                                <button type="button" class="btn btn-sm btn-outline--primary isEdit cuModalBtn"
                                                    data-resource="{{ $rule }}" data-modal_title="@lang('Edit Rule')">
                                                    <i class="la la-pencil"></i>@lang('Edit')
                                                </button>
                                                @if ($rule->status == Status::DISABLE)
                                                    <button type="button" class="btn btn-sm btn-outline--success confirmationBtn"
                                                        data-action="{{ route('admin.spin.rules.status', $rule->id) }}"
                                                        data-question="@lang('Are you sure to enable this rule?')">
                                                        <i class="la la-eye"></i> @lang('Enable')
                                                    </button>
                                                @else
                                                    <button type="button" class="btn btn-sm btn-outline--danger confirmationBtn"
                                                        data-action="{{ route('admin.spin.rules.status', $rule->id) }}"
                                                        data-question="@lang('Are you sure to disable this rule?')">
                                                        <i class="la la-eye-slash"></i> @lang('Disable')
                                                    </button>
                                                @endif
                                                <button type="button" class="btn btn-sm btn-outline--danger confirmationBtn"
                                                    data-action="{{ route('admin.spin.rules.delete', $rule->id) }}"
                                                    data-question="@lang('Are you sure to remove this rule?')">
                                                    <i class="la la-trash"></i> @lang('Delete')
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--Cu Modal -->
    <div id="cuModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="{{ route('admin.spin.rules.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>@lang('Rule Name')</label>
                            <input type="text" name="name" class="form-control" placeholder="@lang('e.g. Welcome Spin')" required>
                        </div>
                        <div class="form-group">
                            <label>@lang('Trigger')</label>
                            <select name="trigger_type" class="form-control select2" data-minimum-results-for-search="-1" required>
                                <option value="signup">@lang('On Signup (new account created)')</option>
                                <option value="referral_count">@lang('Every N Active Referrals')</option>
                            </select>
                        </div>
                        <div class="form-group trigger-value-group d-none">
                            <label>@lang('Active Referrals Needed')</label>
                            <input type="number" name="trigger_value" class="form-control" min="1" placeholder="@lang('e.g. 2')">
                            <small class="text-muted">@lang('Fires again every time the user\'s active-referral count crosses another multiple of this number.')</small>
                        </div>
                        <div class="form-group">
                            <label>@lang('Spins Granted')</label>
                            <input type="number" name="spins_granted" class="form-control" min="1" value="1" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn--primary h-45 w-100">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
    <a href="{{ route('admin.spin.rewards') }}" class="btn btn-sm btn-outline--primary h-45"><i class="la la-gift"></i> @lang('Rewards')</a>
    <button type="button" class="btn btn-sm btn-outline--primary h-45 cuModalBtn" data-modal_title="@lang('Add Rule')">
        <i class="la la-plus"></i>@lang('Add New Rule')
    </button>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";

            function toggleTriggerValue(type) {
                if (type === 'referral_count') {
                    $('.trigger-value-group').removeClass('d-none');
                    $('[name=trigger_value]').attr('required', true);
                } else {
                    $('.trigger-value-group').addClass('d-none');
                    $('[name=trigger_value]').removeAttr('required');
                }
            }

            $('[name=trigger_type]').on('change', function() {
                toggleTriggerValue($(this).val());
            }).trigger('change');

            $('.isEdit').on('click', function() {
                let type = $(this).data('resource').trigger_type;
                $('[name=trigger_type]').val(type).trigger('change');
            });

        })(jQuery);
    </script>
@endpush
