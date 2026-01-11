<div class="p-6 sm:px-20 bg-white border-b border-gray-200">
    <div class="mt-8 text-2xl">
        Items
    </div>
    {{ $active }}
    <textarea name="query" id="query" cols="80" rows="1">{{ $query }}</textarea>
    <div class="mt-6">
        <div class="flex justify-between">
            <div>
                <input type="search" id="q" name="q" wire:model.live.debounce.800ms="q" placeholder="검색어" class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-brand focus:border-brand block w-full px-3 py-2.5 shadow-xs placeholder:text-body" />
            </div>
            <div class="mr-2">
                <input type="checkbox" class="mr-6 leading-tight" wire:model.live="active" /> 동작함
            </div>
        </div>
        <table class="table-auto w-full">
            <thead>
                <tr>
                    <th class="px-4 py-2"><div class="flex items-center">번호</div></th>
                    <th class="px-4 py-2"><div class="flex items-center">이름</div></th>
                    <th class="px-4 py-2"><div class="flex items-center">가격</div></th>
                    <th class="px-4 py-2">상태</th>
                    <th class="px-4 py-2">처리</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $item)
                <tr>
                    <td class="order px-4 py-2">{{ $item->id }}</td>
                    <td class="order px-4 py-2">{{ $item->name }}</td>
                    <td class="order px-4 py-2">{{ number_format($item->price, 2) }}</td>
                    <td class="order px-4 py-2">{{ $item->status ? '동작' : '동작안함' }}</td>
                    <td class="order px-4 py-2">수정버튼 삭제버튼</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        {{$items->links()}}
    </div>
</div>