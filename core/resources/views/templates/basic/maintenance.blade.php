@extends($activeTemplate . 'layouts.app')
@section('panel')
    <div class="main-wrapper">
        <section class="maintenance-page flex-column justify-content-center py-5">
            <div class="container">
                <div class="row justify-content-center align-items-center">
                    <div class="col-lg-8 text-center">
                        <div class="row justify-content-center">
                            <div class="col-sm-6 col-8 col-lg-12 mt-3">
                                <img class="img-fluid mx-auto mb-5" src="{{ getImage(getFilePath("maintenance")."/".@$maintenance->data_values->image,getFileSize("maintenance")) }}" alt="@lang('image')">
                            </div>
                        </div>
                        <p class="mx-auto text-center">@php echo $maintenance->data_values->description @endphp</p>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
