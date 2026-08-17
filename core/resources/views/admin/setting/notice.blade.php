@extends('admin.layouts.app')
@section('panel')
<div class="row mb-none-30">
    <div class="col-lg-12">
        <div class="card">
            <form method="post">
                @csrf
                <div class="card-body">
                  <div class="row">
                    <div class="col-md-3">
                      <div class="form-group">
                        <label>@lang('Status')</label>
                        <input type="checkbox" data-width="100%" data-height="50" data-onstyle="-success" data-offstyle="-danger" data-bs-toggle="toggle" data-on="@lang('Enable')" data-off="@lang('Disabled')" @if(@$notice->data_values->status) checked @endif name="status">
                      </div>
                    </div>
                  </div>
                    <div class="form-group">
                      <label>@lang('Notice Text')</label>
                        <textarea class="form-control" rows="4" required maxlength="500" name="text">{{ @$notice->data_values->text }}</textarea>
                        <small class="text-muted">@lang('Shown as a slim banner across the top of every page, on the live site, while enabled.')</small>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn--primary w-100 h-45">@lang('Submit')</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
