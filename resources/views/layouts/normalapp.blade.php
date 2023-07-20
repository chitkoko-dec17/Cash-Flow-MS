@extends('cfms.layouts.admin.master')

@section('title') Admin Management
 {{ $title }}
@endsection

@push('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/datatables.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/owlcarousel.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/rating.css')}}">
@livewireStyles
@endpush

@section('content')
    
@endsection