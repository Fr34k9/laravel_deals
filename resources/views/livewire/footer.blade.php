<div>
    <footer class="mt-3 bg-white rounded-lg dark:bg-gray-700">
        <div class="w-full p-4 mx-auto md:flex md:items-center md:justify-between">
            <span class="text-sm text-gray-500 sm:text-center dark:text-gray-400">
                Made with ♥
                @if( !empty( env('OWN_WEBPAGE')))
                    by
                    <a href="https://{{ env('OWN_WEBPAGE') }}" class="hover:underline">{{ env('OWN_WEBPAGE') }}</a>
                @endif
            </span>
            <ul class="flex flex-wrap items-center mt-3 text-sm font-medium text-gray-500 dark:text-gray-400 sm:mt-0">
                <li>
                    @if(!empty(env('GITHUB_REPO_URL')))
                        <a href="{{ env('GITHUB_REPO_URL') }}" class="hover:underline" target="_blank" >GitHub</a>
                    @endif
                </li>
            </ul>
        </div>
    </footer>
</div>
