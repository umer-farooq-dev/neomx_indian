@php
    $latestTrx = getContent('latest_trx.content', true);
    $deposits = App\Models\Deposit::where('status', 1)->with('gateway')->latest()->limit(10)->get();
    $withdraws = App\Models\Withdrawal::where('status', 1)->with('method')->latest()->limit(10)->get();
@endphp

<!-- statistics section start -->
<section class="pt-100 pb-100 border-top">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="section-header text-center">
                    <h2 class="section-title">{{ __(@$latestTrx->data_values->heading) }}</h2>
                </div>
            </div>
        </div><!-- row end -->
        <div class="row gy-4 justify-content-center">
            <div class="col-lg-10">
                <ul class="nav nav-tabs custom--nav-tabs statistics--nav-tabs justify-content-center" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="deposit-tab" data-bs-toggle="tab" data-bs-target="#deposit" type="button" role="tab"
                            aria-controls="deposit" aria-selected="true">@lang('Latest Deposit')</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="withdraw-tab" data-bs-toggle="tab" data-bs-target="#withdraw" type="button" role="tab"
                            aria-controls="withdraw" aria-selected="false">@lang('Latest Withdraw')</button>
                    </li>
                </ul>
                <div class="tab-content mt-4" id="statisticsContent">
                    <div class="tab-pane fade show active" id="deposit" role="tabpanel" aria-labelledby="deposit-tab">

                        <div class="table-responsive--md">
                            <table class="table custom--table">
                                <thead>
                                    <tr>
                                        <th>@lang('Trx')</th>
                                        <th>@lang('Gateway')</th>
                                        <th>@lang('Amount')</th>
                                        <th>@lang('Status')</th>
                                        <th>@lang('Time')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($deposits as $deposit)
                                        <tr>
                                            <td>{{ $deposit->trx }}</td>
                                            <td>{{ __(@$deposit->gateway->name) }}</td>
                                            <td>
                                                {{ showAmount($deposit->amount) }}
                                            </td>
                                            <td>
                                                @php
                                                    echo $deposit->statusBadge;
                                                @endphp
                                            </td>
                                            <td>
                                                {{ showDateTime($deposit->created_at) }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="100%">{{ __($emptyMessage) }}</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="withdraw" role="tabpanel" aria-labelledby="withdraw-tab">
                        <div class="table-responsive--md">
                            <table class="table custom--table">
                                <thead>
                                    <tr>
                                        <th>@lang('Trx')</th>
                                        <th>@lang('Gateway')</th>
                                        <th>@lang('Amount')</th>
                                        <th>@lang('Status')</th>
                                        <th>@lang('Time')</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @forelse($withdraws as $withdraw)
                                        <tr>
                                            <td>{{ $withdraw->trx }}</td>
                                            <td>{{ __($withdraw->method->name) }}</td>
                                            <td>
                                                <strong>{{ showAmount($withdraw->amount) }}</strong>
                                            </td>
                                            <td>
                                                @php
                                                    echo $withdraw->statusBadge;
                                                @endphp
                                            </td>
                                            <td>
                                                {{ showDateTime($withdraw->created_at) }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="100%">{{ __($emptyMessage) }}</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- statistics section end -->
