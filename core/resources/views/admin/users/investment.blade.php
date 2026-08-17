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
                                <th>@lang('Plan')</th>
                                <th>@lang('Return')</th>
                                <th>@lang('Interest Amount')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Invested')</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($investments as $data)
                            <tr>
                                <td>
                                    <span class="fw-bold" data-toggle="tooltip" data-original-title="@lang('Plan Name')">
                                        <a href="{{ route('admin.plan.index') }}">{{ $data->plan->name }}</a>
                                    </span>
                                    <br/>
                                    <span class="fw-bold" data-toggle="tooltip" data-original-title="@lang('Transaction Number')">
                                        {{ $data->trx }}
                                    </span>
                                </td>

                                <td>
                                    <span class="fw-bold" data-toggle="tooltip" data-original-title="@lang('Transaction Number')">
                                        @lang('Total') {{ $data->total_return }} @lang('Times')
                                    </span>
                                    <br>
                                    <span class="fw-bold" data-toggle="tooltip" data-original-title="@lang('Transaction Number')">
                                        @lang('Paid') {{ $data->total_paid }} @lang('Times')
                                    </span>
                                </td>

                                <td>
                                    {{ showAmount($data->interest_amount) }}
                              
                                </td>

                                <td>
                                    @php echo $data->statusBadge @endphp
                                </td>

                                <td>
                                    {{ showDateTime($data->created_at) }} <br> {{ diffForHumans($data->created_at) }}
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
                @if ($investments->hasPages())
                <div class="card-footer py-4">
                    {{ paginateLinks($investments) }}
                </div>
                @endif
            </div>
        </div>

    </div>


@endsection

@push('breadcrumb-plugins')
    <x-search-form placeholder="Plan" />
@endpush
