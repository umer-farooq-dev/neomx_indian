@extends($activeTemplate . 'layouts.master')
@section('content')
<section class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <div class="show-filter mb-3 text-end">
                        <button type="button" class="btn btn--base showFilterBtn btn-sm"><i class="las la-filter"></i>
                            @lang('Filter')</button>
                    </div>
                    <div class="responsive-filter-card mb-4">
                        <form action="">
                            <div class="d-flex flex-wrap gap-4">
                                <div class="flex-grow-1">
                                    <label>@lang('Transaction Number')</label>
                                    <input type="search" name="search" value="{{ request()->search }}"
                                        class="form--control">
                                </div>
                                <div class="flex-grow-1 select2-parent">
                                    <label>@lang('Type')</label>
                                    <select name="trx_type" class="form--control select2-basic"  data-minimum-results-for-search="-1">
                                        <option value="">@lang('All')</option>
                                        <option value="+" @selected(request()->trx_type == '+')>@lang('Plus')</option>
                                        <option value="-" @selected(request()->trx_type == '-')>@lang('Minus')</option>
                                    </select>
                                </div>
                                <div class="flex-grow-1 select2-parent">
                                    <label>@lang('Remark')</label>
                                    <select class="form--control select2-basic" name="remark" >
                                        <option value="">@lang('Any')</option>
                                        @foreach ($remarks as $remark)
                                            <option value="{{ $remark->remark }}" @selected(request()->remark == $remark->remark)>
                                                {{ __(keyToTitle($remark->remark)) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="flex-grow-1 align-self-end">
                                    <button class="btn btn--base w-100"><i class="la la-filter"></i>
                                        @lang('Filter')</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="table-responsive--md">
                        <table class="table custom--table">
                            <thead>
                                <tr>
                                    <th>@lang('Trx')</th>
                                    <th>@lang('Transacted')</th>
                                    <th>@lang('Amount')</th>
                                    <th>@lang('Charge')</th>
                                    <th>@lang('Post Balance')</th>
                                    <th>@lang('Detail')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transactions as $trx)
                                    <tr>
                                        <td>{{ $trx->trx }}</td>

                                        <td>
                                            {{ showDateTime($trx->created_at) }}<br>{{ diffForHumans($trx->created_at) }}
                                        </td>

                                        <td class="budget">
                                            <span
                                                class="fw-bold @if ($trx->trx_type == '+') text-success @else text-danger @endif">
                                                {{ $trx->trx_type }} {{ showAmount($trx->amount) }}
                                         
                                            </span>
                                        </td>
                                        <td>{{ showAmount($trx->charge) }} </td>

                                        <td class="budget">
                                            {{ showAmount($trx->post_balance) }} 
                                        </td>

                                        <td>{{ __($trx->details) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    @if ($transactions->hasPages())
                    {{ paginateLinks($transactions) }}
                @endif
                </div>
            </div>
        </div>
</section>
@endsection

@push('style')
    <style>
        .responsive-filter-card {
            background-color: #131340;
        }

    </style>
@endpush


@push('style-lib')
<link rel="stylesheet" href="{{ asset('assets/global/css/select2.min.css') }}">
@endpush

@push('script-lib')
<script src="{{ asset('assets/global/js/select2.min.js') }}"></script>
@endpush
