@extends('admin.layouts.app')

@section('panel')
<div class="row">

    <div class="col-lg-12">
        <div class="show-filter mb-3 text-end">
            <button type="button" class="btn btn-outline--primary showFilterBtn btn-sm"><i class="las la-filter"></i> @lang('Filter')</button>
        </div>
        <div class="card responsive-filter-card mb-4">
            <div class="card-body">
                <form action="">
                    <div class="d-flex flex-wrap gap-4">
                        <div class="flex-grow-1">
                            <label>@lang('TRX/Username')</label>
                            <input type="text" name="search" value="{{ request()->search }}" class="form-control">
                        </div>
                        <div class="flex-grow-1">
                            <label>@lang('Interest Type')</label>
                            <select name="interest_type" class="form-control select2" data-minimum-results-for-search="-1" >
                                <option value="">@lang('All')</option>
                                <option value="1" @selected(request()->interest_type == 1)>@lang('Percent') (%)</option>
                                <option value="2" @selected(request()->interest_type == 2)>@lang('Fixed') ({{ gs('cur_text')}})</option>
                            </select>
                        </div>
                        <div class="flex-grow-1">
                            <label>@lang('Status')</label>
                                <select name="status" class="form-control select2" data-minimum-results-for-search="-1">
                                    <option value="">@lang('All')</option>
                                    <option value="2" @selected(request()->status == 2)>@lang('Running')</option>
                                    <option value="1" @selected(request()->status == 1)>@lang('Completed')</option>
                                </select>
                            
                        </div>
                        <div class="flex-grow-1">
                            <label>@lang('Date')</label>
                            <input name="date" type="text" data-range="true" data-multiple-dates-separator=" - " data-language="en" class="datepicker-here form-control" data-position='bottom right' placeholder="@lang('Start date - End date')" autocomplete="off" value="{{ request()->date }}">
                        </div>
                        <div class="flex-grow-1 align-self-end">
                            <button class="btn btn--primary w-100 h-45"><i class="fas fa-filter"></i> @lang('Filter')</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div class="card b-radius--10 ">
            <div class="card-body p-0">
                <div class="table-responsive--sm table-responsive">
                    <table class="table table--light style--two">
                        <thead>
                            <tr>
                                <th>@lang('User')</th>
                                <th>@lang('TRX')</th>
                                <th>@lang('Plan | Amount')</th>
                                <th>@lang('Per Return Interest')</th>
                                <th>@lang('Total Return')</th>
                                <th>@lang('Total Paid')</th>
                                <th>@lang('Status')</th>
                                <th>@lang('Invested At')</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($investments as $invest)
                                <tr>
                                    <td>
                                       {{ $invest->user->fullname }}
                                        <br>
                                        <span class="small"> <a href="{{ appendQuery('search',$invest->user->username) }}"><span>@</span>{{ $invest->user->username }}</a> </span>
                                    </td>
                                    <td>{{ $invest->trx }}</td>
                                    <td>
                                        {{ $invest->plan->name }}
                                        <br>
                                       <b>{{showAmount($invest->amount)}} </b> 
                                    </td>
                                    <td>
                                        @if ($invest->interest_type == 1)
                                            {{ showAmount($invest->interest_amount, 0) }}%
                                        @else
                                        {{ showAmount($invest->interest_amount, 0) }} 
                                        @endif
                                    </td>

                                    <td>{{ $invest->total_return }} @lang('Times')</td>
                                    <td>{{ $invest->total_paid }} @lang('Times')</td>
                                    <td>
                                        @php
                                            echo $invest->statusBadge;
                                        @endphp
                                    </td>
                                    <td>
                                        {{ showDateTime($invest->created_at) }}<br>{{ diffForHumans($invest->created_at) }}
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
        @if($investments->hasPages())
        <div class="card-footer py-4">
            {{ paginateLinks($investments) }}
        </div>
        @endif
    </div><!-- card end -->
</div>
</div>

@endsection

@push('style-lib')
    <link rel="stylesheet" href="{{asset('assets/admin/css/vendor/datepicker.min.css')}}">
@endpush


@push('script-lib')
  <script src="{{ asset('assets/admin/js/vendor/datepicker.min.js') }}"></script>
  <script src="{{ asset('assets/admin/js/vendor/datepicker.en.js') }}"></script>
@endpush
@push('script')
  <script>
    (function($){
        "use strict";
        if(!$('.datepicker-here').val()){
            $('.datepicker-here').datepicker();
        }
    })(jQuery)
  </script>
@endpush
