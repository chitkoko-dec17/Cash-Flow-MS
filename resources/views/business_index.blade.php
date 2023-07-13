@extends('cfms.layouts.admin.master')

@section('title')Business Unit list
 {{ $title }}
@endsection

@push('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/datatables.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/owlcarousel.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/rating.css')}}">
@livewireStyles
@endpush

@section('content')
    <div class="container-fluid list-products">
        @livewire('business-unit-crud')
    </div>
@endsection
