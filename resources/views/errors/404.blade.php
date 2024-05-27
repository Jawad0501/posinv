<x-frontend.layouts.app>
    <section class="section bg-light">
        <div class="container">
            <div class="row items-center justify-center">
                <div>
                    <img src="{{ Vite::image('error/404.svg') }}" alt="404" />
                    <div class="text-center mt-4">
                        <h1 class="text-4xl font-bold text-primary-800">ERROR 404</h1>
                        <p class="text-gray-600 font-secondary font-bold w-1/2 mx-auto mb-4">
                            The page you are looking for might have been moved, renamed, or might never existed.
                        </p>
                        <a href="{{ route('home') }}" class="bg-primary-500 px-5 rounded-md py-2 font-bold text-white">Go Back Home</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</x-frontend.layouts.app>
