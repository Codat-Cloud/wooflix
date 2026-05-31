@extends('layouts.front')

@section('content')
<div class="container-xxl py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            
            <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white p-4">
                <h4 class="fw-bold mb-2">Track Your Wooflix Package 🐾</h4>
                <p class="text-muted small mb-4">Enter your tracking ID / Air Waybill (AWB) number to review your package dispatch status metrics.</p>
                
                <form action="{{ route('order.track') }}" method="GET">
                    <div class="input-group mb-2">
                        <input type="text" 
                               name="awb" 
                               value="{{ $awb }}" 
                               class="form-control form-control-lg border-light-subtle shadow-none" 
                               placeholder="e.g. 143245112534" 
                               required>
                        <button type="submit" class="btn btn-orange text-white px-4 fw-bold">Track Order</button>
                    </div>
                </form>

                @if($error)
                    <div class="alert alert-danger border-0 mt-3 small py-2">{{ $error }}</div>
                @endif
            </div>

            @if($trackingData)
                <div class="card border-0 shadow-sm rounded-4 bg-white p-4 mb-4">
                    
                    <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-4 flex-wrap gap-2">
                        <div>
                            <span class="text-muted d-block small text-uppercase">Courier Partner</span>
                            <strong class="text-dark fs-5">{{ $trackingData['courier_name'] ?? 'In-Transit' }}</strong>
                        </div>
                        <div class="text-end">
                            <span class="text-muted d-block small text-uppercase">Current Status</span>
                            <span class="badge bg-orange text-white px-3 py-2 fw-bold text-uppercase">
                                {{ $trackingData['shipment_status_string'] ?? 'Processing' }}
                            </span>
                        </div>
                    </div>

                    <h5 class="fw-bold mb-3">Journey History Timeline</h5>
                    <div class="position-relative ps-4 border-start ms-2" style="border-color: #f26522 !important; border-width: 2px !important;">
                        
                        @forelse($trackingData['shipment_track_activities'] as $activity)
                            <div class="mb-4 position-relative">
                                
                                <span class="position-absolute rounded-circle bg-orange" 
                                      style="width: 12px; height: 12px; left: -31px; top: 4px; border: 2px solid #fff; box-shadow: 0 0 0 2px #f26522;">
                                </span>
                                
                                <div class="d-flex justify-content-between align-items-start flex-wrap">
                                    <div>
                                        <strong class="text-dark d-block" style="font-size: 0.95rem;">
                                            {{ $activity['activity'] }}
                                        </strong>
                                        <small class="text-muted d-block mt-1">
                                            📍 Location: {{ $activity['location'] ?? 'Hub Facility' }}
                                        </small>
                                    </div>
                                    <div class="text-sm-end mt-1 mt-sm-0">
                                        <span class="badge bg-light text-dark border small fw-normal">
                                            {{ \Carbon\Carbon::parse($activity['date'])->format('d M Y, h:i A') }}
                                        </span>
                                    </div>
                                </div>

                            </div>
                        @empty
                            <p class="text-muted small">Package registered, waiting for initial origin hub courier pickup scanning transitions.</p>
                        @endforelse

                    </div>

                </div>
            @elseif($awb && !$error)
                <div class="text-center py-4 text-muted">
                    <p>No historical updates recorded. Please verify the network connection loop strings.</p>
                </div>
            @endif

        </div>
    </div>
</div>
@endsection