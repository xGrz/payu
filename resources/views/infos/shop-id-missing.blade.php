<x-p-paper>
    <section class="bg-red-700 text-white p-2">
        <h1 class="text-xl font-semibold mb-1">[PAYU_SHOP_ID] key configuration missing</h1>
    </section>
    <div class="px-2 mt-3">
        <h2 class="text-lg text-white">How to solve problem:</h2>
        <ul class="list-disc px-3">
            <li class="mb-4 mt-2">
                <h3 class="text-lg">Public sandbox API (without personal sandbox account)</h3>
                If you are using public sandbox shop_id is unavailable. Package is limited to transactions and
                refunds
                only.
            </li>
            <li class="my-4">
                <h3 class="text-lg">Personal sandbox API (with personal sandbox account)</h3>
                <ul class="list-decimal ps-5">
                    <li>Please fill PAYU_SHOP_ID* key in your .env file</li>
                    <li>Clear your app cache by running in console [php artisan cache:clear]</li>
                    <li>Refresh page</li>
                </ul>
            </li>
            <li class="my-4">
                <h3 class="text-lg">Production API</h3>
                <ul class="list-decimal ps-5">
                    <li>Please fill PAYU_SHOP_ID* key in your .env file</li>
                    <li>Clear your app cache by running in console [php artisan cache:clear]</li>
                    <li>Refresh page</li>
                </ul>
            </li>
        </ul>

        <h2 class="text-lg text-white">How to get PAYU_SHOP_ID?</h2>
        <ul class="list-decimal ps-5 mt-2 pb-2">
            <li>Please log in into your PayU account (PayU Website/PayU sandbox Website)</li>
            <li>From left menu select [My shops]</li>
            <li>Click on shop name</li>
            <li>In first tab (Shop data) you will find your Shop ID</li>
        </ul>
    </div>
</x-p-paper>
