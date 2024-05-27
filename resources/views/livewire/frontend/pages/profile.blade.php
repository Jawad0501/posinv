<x-frontend.auth-section>
    <div class="bg-white border border-gray-100 rounded-md">
        <ul>
            <li class="px-4 py-3 border-b border-gray-100">
                <p>First Name</p>
                <p>{{ auth()->user()->first_name }}</p>
            </li>
            <li class="px-4 py-3 border-b border-gray-100">
                <p>Last Name</p>
                <p>{{ auth()->user()->last_name }}</p>
            </li>
            <li class="px-4 py-3 border-b border-gray-100">
                <p>Email</p>
                <p>{{ auth()->user()->email }}</p>
            </li>
            <li class="px-4 py-3 border-b border-gray-100">
                <p>Phone</p>
                <p>{{ auth()->user()->phone }}</p>
            </li>
            <li class="px-4 py-3 border-b border-gray-100">
                <p>Date of Birth</p>
                <p>{{ date('jS M Y', strtotime(auth()->user()->date_of_birth)) }}</p>
            </li>
            <li class="px-4 py-3 border-b border-gray-100">
                <p>Date of Anniversary</p>
                <p>{{ date('jS M Y', strtotime(auth()->user()->date_of_anniversary)) }}</p>
            </li>
        </ul>
    </div>
</x-frontend.auth-section>
