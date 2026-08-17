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
                                    <th>@lang('Color')</th>
                                    <th>@lang('Name')</th>
                                    <th>@lang('Amount')</th>
                                    <th>@lang('Weight (odds)')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($rewards as $reward)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td><span class="d-inline-block" style="width:22px;height:22px;border-radius:5px;background:{{ $reward->color }};border:1px solid rgba(255,255,255,.2)"></span></td>
                                        <td>{{ __($reward->name) }}</td>
                                        <td>{{ showAmount($reward->amount) }}</td>
                                        <td>{{ $reward->weight }}</td>
                                        <td>@php echo $reward->statusBadge @endphp</td>
                                        <td>
                                            <div class="button--group">
                                                <button type="button" class="btn btn-sm btn-outline--primary isEdit cuModalBtn"
                                                    data-resource="{{ $reward }}" data-modal_title="@lang('Edit Reward')">
                                                    <i class="la la-pencil"></i>@lang('Edit')
                                                </button>
                                                @if ($reward->status == Status::DISABLE)
                                                    <button type="button" class="btn btn-sm btn-outline--success confirmationBtn"
                                                        data-action="{{ route('admin.spin.rewards.status', $reward->id) }}"
                                                        data-question="@lang('Are you sure to enable this reward?')">
                                                        <i class="la la-eye"></i> @lang('Enable')
                                                    </button>
                                                @else
                                                    <button type="button" class="btn btn-sm btn-outline--danger confirmationBtn"
                                                        data-action="{{ route('admin.spin.rewards.status', $reward->id) }}"
                                                        data-question="@lang('Are you sure to disable this reward?')">
                                                        <i class="la la-eye-slash"></i> @lang('Disable')
                                                    </button>
                                                @endif
                                                <button type="button" class="btn btn-sm btn-outline--danger confirmationBtn"
                                                    data-action="{{ route('admin.spin.rewards.delete', $reward->id) }}"
                                                    data-question="@lang('Are you sure to remove this reward?')">
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
                <form action="{{ route('admin.spin.rewards.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>@lang('Reward Name')</label>
                            <input type="text" name="name" class="form-control" placeholder="@lang('e.g. 50 Bonus')" required>
                        </div>
                        <div class="form-group">
                            <label>@lang('Amount Credited (Reward Wallet)')</label>
                            <div class="input-group">
                                <input type="number" step="any" name="amount" class="form-control" min="0" required>
                                <button type="button" class="input-group-text">{{ gs('cur_text') }}</button>
                            </div>
                            <small class="text-muted">@lang('0 is allowed if you want a non-cash / "try again" segment.')</small>
                        </div>
                        <div class="form-group">
                            <label>@lang('Weight')</label>
                            <input type="number" name="weight" class="form-control" min="1" value="1" required>
                            <small class="text-muted">@lang('Relative odds of landing on this reward. Higher weight = more likely. Not tied to the wheel\'s visual slice size.')</small>
                        </div>
                        <div class="form-group">
                            <label>@lang('Wheel Segment Color')</label>
                            <input type="color" name="color" class="form-control form-control-color" value="#7A1E1E" required>
                            <small class="text-muted">@lang('For a classic casino-wheel look, alternate two colors across your rewards (e.g. deep red / cream).')</small>
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
    <a href="{{ route('admin.spin.rules') }}" class="btn btn-sm btn-outline--primary h-45"><i class="la la-cogs"></i> @lang('Unlock Rules')</a>
    <button type="button" class="btn btn-sm btn-outline--primary h-45 cuModalBtn" data-modal_title="@lang('Add Reward')">
        <i class="la la-plus"></i>@lang('Add New Reward')
    </button>
@endpush
