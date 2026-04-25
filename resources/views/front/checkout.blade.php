@extends('layouts.front')

@section('content')

    <!-- Breadcrumbs -->
    <nav aria-label="breadcrumb" class="breadcrumb-wrapper">
      <div class="container-xxl">
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="/">Home</a>
          </li>

          <li class="breadcrumb-item">
            <a href="{{route('front.checkout')}}">Checkout</a>
          </li>
        </ol>
      </div>
    </nav>

    @livewire('front.checkout')

@endsection