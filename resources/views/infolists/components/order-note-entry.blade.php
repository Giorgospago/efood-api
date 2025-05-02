<x-dynamic-component :component="$getEntryWrapperView()" :entry="$entry">
    <div class="p-4 rounded-xl" style="background-color: dodgerblue">
        {{ $getState() }}
    </div>
</x-dynamic-component>
