<button 
    class="wishlist-btn {{ $isWishlisted ? 'active' : '' }}"
    wire:click="toggle"
>
    {!! $isWishlisted ? '❤' : '♡' !!}
</button>