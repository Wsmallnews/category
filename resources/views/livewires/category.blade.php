<div class="container mx-auto">

    <table class="fi-ta-table w-full table-auto divide-y divide-gray-200 text-start dark:divide-white/5"
    >

        <thead class="divide-y divide-gray-200 dark:divide-white/5">


            <tr class="bg-gray-50 dark:bg-white/5">
                <td>
                    那么

                </td>
                <td>
                    描述

                </td>
            </tr>
        </thead>


        <tbody
            class="divide-y divide-gray-200 whitespace-nowrap dark:divide-white/5"
        >
            <x-sn-category::category-item :categories="$categories" />

        </tbody>

        {{-- @if ($footer)
            <tfoot class="bg-gray-50 dark:bg-white/5">
                <tr>
                    {{ $footer }}
                </tr>
            </tfoot>
        @endif --}}
    </table>



    </table>

</div>
