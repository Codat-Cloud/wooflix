<!-- DELIVERY CHECK -->
<div class="delivery-info">

    <h5 class="delivery-title">
        Delivery & Service Information
    </h5>

    <div class="delivery-checker mb-3">

        {{-- 
    <div class="d-flex gap-2">
        <input
            type="text"
            wire:model.lazy="pincode"
            class="form-control"
            placeholder="Enter Pincode"
            maxlength="6"
        >

        <button
            wire:click="check"
            wire:loading.attr="disabled"
            wire:target="check"
            class="btn btn-dark d-flex align-items-center justify-content-center"
            style="min-width: 90px;"
            class="btn btn-warning"
        >
            <span wire:loading.remove wire:target="check">
                Check
            </span>

            <span wire:loading wire:target="check">

            <span
                class="spinner-border spinner-border-sm"
                role="status"
                aria-hidden="true"
            ></span>

            </span>
        </button> 
        
    </div>

    @error('pincode')

        <small class="text-danger">
            {{ $message }}
        </small>

    @enderror --}}

</div>

<div class="delivery-items">
    @php
        $deliveryList = $deliveryServiceInfo ?? [
            ['icon' => 'lightning', 'dynamic_type' => 'express_availability', 'text' => 'Check delivery availability', 'highlight' => ''],
            ['icon' => 'truck',     'dynamic_type' => 'delivery_date',        'text' => 'Enter pincode for delivery date', 'highlight' => ''],
            ['icon' => 'package',   'dynamic_type' => 'static',               'text' => 'No Exchange & Returns', 'highlight' => ''],
            ['icon' => 'free',      'dynamic_type' => 'static',               'text' => 'Enjoy Free Delivery above', 'highlight' => '₹699'],
        ];
    @endphp

    @foreach($deliveryList as $item)
        @php
            $type = $item['dynamic_type'] ?? 'static';
            $icon = $item['icon'] ?? 'lightning';
            $isBadgeTag = in_array($icon, ['free', 'cod']) || ($icon === 'custom' && strlen($item['custom_icon'] ?? '') > 2);
        @endphp

        <div class="delivery-item">
            {{-- ICON / BADGE --}}
            <span class="delivery-icon {{ $isBadgeTag ? 'free' : '' }}">
                @switch($icon)
                    @case('lightning') ⚡ @break
                    @case('truck')     🚚 @break
                    @case('package')   📦 @break
                    @case('free')      FREE @break
                    @case('cod')       COD @break
                    @case('shield')    🛡️ @break
                    @case('paw')       🐾 @break
                    @case('support')   🎧 @break
                    @case('clock')     ⏱️ @break
                    @case('star')      ⭐ @break
                    @case('custom')    {{ $item['custom_icon'] ?? '⚡' }} @break
                    @default           ⚡
                @endswitch
            </span>

            {{-- TEXT CONTENT --}}
            <span>
                @if($type === 'express_availability')
                    @if($deliveryAvailable === false)
                        Express delivery unavailable
                    @elseif($deliveryDate)
                        Get it <strong class="text-success">{{ $deliveryText }}</strong>
                    @else
                        {{ $item['text'] }}
                    @endif
                @elseif($type === 'delivery_date')
                    @if($deliveryAvailable === false)
                        Delivery not available
                    @elseif($deliveryDate)
                        Expected delivery date – <strong class="text-success">{{ $deliveryDate }}</strong>
                    @else
                        {{ $item['text'] }}
                    @endif
                @else
                    {{ $item['text'] }}
                    @if(!empty($item['highlight']))
                        <strong>{{ $item['highlight'] }}</strong>
                    @endif
                @endif
            </span>
        </div>
    @endforeach
</div>

</div>