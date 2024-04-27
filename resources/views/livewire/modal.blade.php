<div x-init class="bg-red-500" x-data="{ isOpen: @entangle('isOpen') }" @keydown.esc="isOpen = false">
    <div x-show="isOpen" x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center overflow-x-hidden overflow-y-auto">
        <div class="relative w-full h-full max-w-xl p-4 md:h-auto">
            <!-- Modal content -->
            <div class="relative p-4 bg-white rounded-lg shadow dark:bg-gray-800 sm:p-5">
                <!-- Modal header -->
                <div class="absolute top-3 right-3">
                    <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 inline-flex dark:hover:bg-gray-600 dark:hover:text-white"
                        @click="isOpen = false">
                        <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>
                <span>
                    Aktuell gibt es Crawler der folgenden Plattformen: <br><br>
                    <ul class="">
                        @foreach ($platforms as $platform)
                            <li>
                                <a href="{{ $platform->url }}" class="hover:underline">
                                    <img class="inline w-4 h-4 m-1" src="{{ $platform->image }}" /> {{ $platform->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </span>
            </div>
        </div>
    </div>
    <div x-show="isOpen" x-cloak class="fixed inset-0 z-40 bg-black opacity-25"></div>
</div>
