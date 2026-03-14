<div class="space-y-4 px-1 animate-pulse">
    @foreach(range(1, 3) as $i)
        <div class="flex gap-3 py-2">
            <div class="w-9 h-9 rounded-full bg-zinc-200 dark:bg-zinc-700 shrink-0"></div>
            <div class="flex-1 space-y-2">
                <div class="bg-zinc-100 dark:bg-zinc-800 rounded-2xl rounded-tl-sm px-4 py-3 space-y-2">
                    <div class="flex items-center justify-between">
                        <div class="h-3 w-24 bg-zinc-200 dark:bg-zinc-700 rounded-full"></div>
                        <div class="h-2.5 w-14 bg-zinc-200 dark:bg-zinc-700 rounded-full"></div>
                    </div>
                    <div class="h-3 w-full bg-zinc-200 dark:bg-zinc-700 rounded-full"></div>
                    <div class="h-3 w-3/4 bg-zinc-200 dark:bg-zinc-700 rounded-full"></div>
                    <div class="flex justify-end gap-2 pt-2 border-t border-zinc-200 dark:border-zinc-700">
                        <div class="h-6 w-14 bg-zinc-200 dark:bg-zinc-700 rounded-xl"></div>
                        <div class="h-6 w-14 bg-zinc-200 dark:bg-zinc-700 rounded-xl"></div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>