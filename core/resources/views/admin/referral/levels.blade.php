@extends('admin.layouts.app')
@section('panel')
    <div class="row mb-3">
        <div class="col-lg-12">
            <div class="card b-radius--10">
                <div class="card-body">
                    <h5 class="mb-3">@lang('Referral Settings')</h5>
                    <form action="{{ route('admin.referral.settings.update') }}" method="POST">
                        @csrf
                        <div class="row align-items-end">
                            <div class="col-lg-4">
                                <div class="form-group mb-0">
                                    <label>@lang('Minimum Qualifying Deposit')</label>
                                    <div class="input-group">
                                        <input type="number" step="any" name="referral_min_deposit" class="form-control"
                                            value="{{ old('referral_min_deposit', gs('referral_min_deposit')) }}" required>
                                        <button type="button" class="input-group-text">{{ gs('cur_text') }}</button>
                                    </div>
                                    <small class="text-muted">@lang('A deposit smaller than this amount will not trigger any referral commission. Set to 0 for no minimum.')</small>
                                </div>
                            </div>
                            <div class="col-lg-2">
                                <button type="submit" class="btn btn--primary w-100">@lang('Update')</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10">
                <div class="card-body p-0">
                    <div class="table-responsive--md table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('S.N.')</th>
                                    <th>@lang('Level')</th>
                                    <th>@lang('Commission')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($levels as $level)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>@lang('Level') {{ $level->level }}</td>
                                        <td>{{ showAmount($level->percent, currencyFormat: false) }}%</td>
                                        <td>@php echo $level->statusBadge @endphp</td>
                                        <td>
                                            <div class="button--group">
                                                <button type="button" class="btn btn-sm btn-outline--primary isEdit cuModalBtn"
                                                    data-resource="{{ $level }}" data-modal_title="@lang('Edit Referral Level')">
                                                    <i class="la la-pencil"></i>@lang('Edit')
                                                </button>
                                                @if ($level->status == Status::DISABLE)
                                                    <button type="button" class="btn btn-sm btn-outline--success confirmationBtn"
                                                        data-action="{{ route('admin.referral.levels.status', $level->id) }}"
                                                        data-question="@lang('Are you sure to enable this level?')">
                                                        <i class="la la-eye"></i> @lang('Enable')
                                                    </button>
                                                @else
                                                    <button type="button" class="btn btn-sm btn-outline--danger confirmationBtn"
                                                        data-action="{{ route('admin.referral.levels.status', $level->id) }}"
                                                        data-question="@lang('Are you sure to disable this level?')">
                                                        <i class="la la-eye-slash"></i> @lang('Disable')
                                                    </button>
                                                @endif
                                                <button type="button" class="btn btn-sm btn-outline--danger confirmationBtn"
                                                    data-action="{{ route('admin.referral.levels.delete', $level->id) }}"
                                                    data-question="@lang('Are you sure to remove this level?')">
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
                <form action="{{ route('admin.referral.levels.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>@lang('Level Number')</label>
                            <input type="number" name="level" class="form-control" min="1" required>
                            <small class="text-muted">@lang('e.g. 1 for direct referrals, 2 for their referrals, and so on.')</small>
                        </div>
                        <div class="form-group">
                            <label>@lang('Commission Percentage')</label>
                            <div class="input-group">
                                <input type="number" step="any" name="percent" class="form-control" min="0" max="100" required>
                                <button type="button" class="input-group-text">%</button>
                            </div>
                            <small class="text-muted">@lang('Percentage of the deposit amount paid to this level, credited to the Referral Wallet.')</small>
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
    <button type="button" class="btn btn-sm btn-outline--primary h-45 cuModalBtn" data-modal_title="@lang('Add Referral Level')">
        <i class="la la-plus"></i>@lang('Add New Level')
    </button>
@endpush
