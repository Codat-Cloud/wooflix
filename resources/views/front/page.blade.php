@extends('layouts.front')

@section('title', $page->seo_title ?? $page->title)
@section('meta_description', $page->seo_description)

@section('content')
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/">Home</a></li>
                    <li class="breadcrumb-item active">{{ $page->title }}</li>
                </ol>
            </nav>

            <h1 class="mb-4">{{ $page->title }}</h1>
            
            <div class="page-content">
                {!! $page->content !!}
            </div>
        </div>
    </div>
</div>
@endsection