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
                                    <th>@lang('Limit Amount')</th>
                                    <th>@lang('Returns')</th>
                                    <th>@lang('Type')</th>
                                    <th>@lang('Interest')</th>
                                    <th>@lang('Referral Boost')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($plans as $plan)
                                    <tr>
                                        <td>{{ $plans->firstItem() + $loop->index }}</td>
                                        <td>{{ __($plan->name) }}</td>
                                        <td>
                                            {{ showAmount($plan->min_amount) }} -
                                            {{ showAmount($plan->max_amount) }}
                                        </td>

                                        <td>
                                            {{ $plan->total_return }} @lang('Times')
                                        </td>

                                        <td>
                                            @if ($plan->interest_type == Status::PERCENT)
                                                (%)
                                                @lang('Percent')
                                            @else
                                                @lang('Fixed')
                                            @endif
                                        </td>

                                        <td>
                                            @if ($plan->interest_type == Status::PERCENT)
                                                {{ showAmount($plan->interest, currencyFormat: false) }}%
                                            @else
                                                {{ showAmount($plan->interest) }}
                                            @endif
                                        </td>

                                        <td>
                                            @if ($plan->referral_threshold > 0)
                                                <span class="badge badge--success">
                                                    {{ $plan->referral_threshold }} @lang('refs') →
                                                    @if ($plan->interest_type == Status::PERCENT)
                                                        {{ showAmount($plan->boost_interest, currencyFormat: false) }}%
                                                    @else
                                                        {{ showAmount($plan->boost_interest) }}
                                                    @endif
                                                </span>
                                            @else
                                                <span class="text-muted">@lang('Off')</span>
                                            @endif
                                        </td>

                                        <td>
                                            @php echo $plan->statusBadge @endphp
                                        </td>

                                        <td>
                                            <div class="button--group">

                                                <button type="button" class="btn btn-sm btn-outline--primary isEdit cuModalBtn"
                                                    data-resource="{{ $plan }}" data-modal_title="@lang('Edit Plan')" data-has_status="1">
                                                    <i class="la la-pencil"></i>@lang('Edit')
                                                </button>
                                                @if ($plan->status == Status::DISABLE)
                                                    <button type="button" class="btn btn-sm btn-outline--success confirmationBtn"
                                                        data-action="{{ route('admin.plan.status', $plan->id) }}" data-question="@lang('Are you sure to enable this plan?')">
                                                        <i class="la la-eye"></i> @lang('Enable')
                                                    </button>
                                                @else
                                                    <button type="button" class="btn btn-sm btn-outline--danger confirmationBtn"
                                                        data-action="{{ route('admin.plan.status', $plan->id) }}" data-question="@lang('Are you sure to disable this plan?')">
                                                        <i class="la la-eye-slash"></i> @lang('Disable')
                                                    </button>
                                                @endif
                                            </div>

                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse

                            </tbody>
                        </table><!-- table end -->
                    </div>
                </div>
                @if ($plans->hasPages())
                    <div class="card-footer py-4">
                        @php echo paginateLinks($plans) @endphp
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!--Cu Modal -->
    <div id="cuModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="las la-times"></i>
                    </button>
                </div>
                <form action="{{ route('admin.plan.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>@lang('Name')</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>@lang('Minimum')</label>
                                    <div class="input-group">
                                        <input type="number" name="min_amount" class="form-control" required>
                                        <button type="button" class="input-group-text">{{ gs('cur_text') }}</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>@lang('Maximum')</label>
                                    <div class="input-group">
                                        <input type="number" name="max_amount" class="form-control" required>
                                        <button type="button" class="input-group-text">{{ gs('cur_text') }}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>@lang('How Many Return')</label>
                            <div class="input-group">
                                <input type="number" name="total_return" class="form-control" required>
                                <button type="button" class="input-group-text">@lang('Times')</button>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>@lang('Interest Type')</label>

                            <select name="interest_type" class="form-control select2" data-minimum-results-for-search="-1" required>
                                <option value="1">@lang('Percent')</option>
                                <option value="2">@lang('Fixed')</option>
                            </select>

                        </div>

                        <div class="form-group percent">
                            <label class="type-label">@lang('Percent Amount')</label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="interest" value="{{ old('interest') }}" autocomplete="off" />
                                <button type="button" class="input-group-text">
                                    <span class="percent-sym">%</span>
                                    <span class="fixed-sym d-none">{{ gs('cur_text') }}</span>
                                </button>
                            </div>
                        </div>

                        <hr>
                        <p class="mb-2 fw-bold">@lang('Referral-Linked Return Boost')</p>
                        <p class="text-muted small mb-3">@lang('Optional. When an investor has this many active (deposited) direct referrals, their daily return upgrades to the boosted rate below. Leave the referral count empty or 0 to disable this for the plan.')</p>
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>@lang('Active Referrals Needed')</label>
                                    <input type="number" min="0" name="referral_threshold" class="form-control" value="{{ old('referral_threshold') }}" placeholder="@lang('e.g. 5, or leave blank to disable')">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group boost-percent">
                                    <label class="boost-type-label">@lang('Boosted Percent Amount')</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" name="boost_interest" value="{{ old('boost_interest') }}" autocomplete="off" />
                                        <button type="button" class="input-group-text">
                                            <span class="boost-percent-sym">%</span>
                                            <span class="boost-fixed-sym d-none">{{ gs('cur_text') }}</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
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
    <x-search-form placeholder="Search by name here..." />
    <button type="button" class="btn btn-sm btn-outline--primary h-45 cuModalBtn" data-modal_title="@lang('Add Plan')">
        <i class="la la-plus"></i>@lang('Add New')
    </button>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";

            function typeMaintence(interestType) {
                if (interestType == 2) {
                    $('.type-label').text(`@lang('Fixed Amount')`);
                    $('.fixed-sym').removeClass('d-none');
                    $('.percent-sym').addClass('d-none');

                    $('.boost-type-label').text(`@lang('Boosted Fixed Amount')`);
                    $('.boost-fixed-sym').removeClass('d-none');
                    $('.boost-percent-sym').addClass('d-none');
                } else {
                    $('.type-label').text(`@lang('Percent Amount')`);
                    $('.fixed-sym').addClass('d-none');
                    $('.percent-sym').removeClass('d-none');

                    $('.boost-type-label').text(`@lang('Boosted Percent Amount')`);
                    $('.boost-fixed-sym').addClass('d-none');
                    $('.boost-percent-sym').removeClass('d-none');
                }
            }

            //add
            $('[name=interest_type]').on('change', function() {
                let interestType = $(this).find(':selected').val();
                typeMaintence(interestType)
            }).trigger('change');

            //edit
            $('.isEdit').on('click', function() {
                let interestType = $(this).data('resource').interest_type;
                $('[name=interest_type]').val(interestType).trigger('change');

                typeMaintence(interestType)
            })

        })(jQuery);
    </script>
@endpush
