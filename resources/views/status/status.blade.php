<div>

    @if (session()->has('success'))
        <div class="py-1 px-2 bg-green-700 text-white font-bold rounded shadow-xl">
            {{ session('success') }}
        </div>
    @endif
    @if (session()->has('error'))
        <div class="py-1 px-2 bg-red-800 text-white font-bold rounded shadow-xl">
            {{ session('error') }}
        </div>
    @endif

</div>


