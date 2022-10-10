@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">
                <a href="{{ route('messages.index') }}" class="mr-4"
                    ><i class="icon ion-md-arrow-back"></i
                ></a>
                @lang('crud.messages.show_title')
            </h4>

            <div class="mt-4">
                <div class="mb-4">
                    <h5>@lang('crud.messages.inputs.content')</h5>
                    <span>{{ $message->content ?? '-' }}</span>
                </div>
                <div class="mb-4">
                    <h5>@lang('crud.messages.inputs.sender_id')</h5>
                    <span>{{ optional($message->sender)->name ?? '-' }}</span>
                </div>
                <div class="mb-4">
                    <h5>@lang('crud.messages.inputs.receiver_id')</h5>
                    <span>{{ optional($message->receiver)->name ?? '-' }}</span>
                </div>
                <div class="mb-4">
                    <h5>@lang('crud.messages.inputs.group_id')</h5>
                    <span>{{ optional($message->group)->name ?? '-' }}</span>
                </div>
                <div class="mb-4">
                    <h5>@lang('crud.messages.inputs.message_id')</h5>
                    <span
                        >{{ optional($message->message)->content ?? '-' }}</span
                    >
                </div>
            </div>

            <div class="mt-4">
                <a href="{{ route('messages.index') }}" class="btn btn-light">
                    <i class="icon ion-md-return-left"></i>
                    @lang('crud.common.back')
                </a>

                @can('create', App\Models\Message::class)
                <a href="{{ route('messages.create') }}" class="btn btn-light">
                    <i class="icon ion-md-add"></i> @lang('crud.common.create')
                </a>
                @endcan
            </div>
        </div>
    </div>
</div>
@endsection
