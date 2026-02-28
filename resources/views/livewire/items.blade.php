<div class="p-6 sm:px-20 bg-white border-b border-gray-200">
    @if (session()->has('message'))
    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded relative" role="alert" x-data="{show: true}" x-show="show">
        <strong class="font-bold">알림!</strong>
        <span class="block sm:inline">{{ session('message') }}</span>
        <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
            <svg xmlns="http://www.w3.org/2000/svg" @click="show = false" role="button" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
            </svg>
        </span>
    </div>
    @endif
    <div class="mt-8 text-2xl flex justify-between">
        <div>Items</div>
        <div>
            <x-button wire:click="confirmAdd">신규 아이템 등록</x-button>
        </div>
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
                    <th class="px-4 py-2">
                        <div class="flex items-center">
                            <button type="button" wire:click="sortById">번호</button>
                            <x-sort-icon sortField="id" :sort-by="$sortBy" :sort-asc="$sortAsc" />
                        </div>
                    </th>
                    <th class="px-4 py-2">
                        <div class="flex items-center">
                            <button type="button" wire:click="sortByName">이름</button>
                            <x-sort-icon sortField="name" :sort-by="$sortBy" :sort-asc="$sortAsc" />
                        </div>
                    </th>
                    <th class="px-4 py-2">
                        <div class="flex items-center">
                            <button type="button" wire:click="sortByPrice">가격</button>
                            <x-sort-icon sortField="price" :sort-by="$sortBy" :sort-asc="$sortAsc" />
                        </div>
                    </th>
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
                    <td class="order px-4 py-2">
                        <x-button wire:click="confirmItemEdit( {{ $item->id }} )" wire:loading.attr="disabled">
                            수정
                        </x-button>
                        <x-danger-button wire:click="confirmItemDeletion( {{ $item->id }} )" wire:loading.attr="disabled">
                            삭제
                        </x-danger-button>
                        <!-- <button class="btn_delete" data-itemid="{{ $item->id }}">삭제</button> -->
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        {{$items->links()}}
    </div>
    <!-- Delete Item Confirmation Modal -->
    <x-dialog-modal wire:model.live="confirmingItemDeletion">
        <x-slot name="title">
            {{ __('Delete Item') }}
        </x-slot>
        <x-slot name="content">
            {{ __('삭제하시겠습니까?') }}
        </x-slot>
        <x-slot name="footer">
            <x-secondary-button wire:click="$set('confirmingItemDeletion', false)" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-secondary-button>
            <x-danger-button class="ms-3" wire:click="deleteItem( {{ $confirmingItemDeletion }} )" wire:loading.attr="disabled">
                {{ __('Delete Item') }}
            </x-danger-button>
        </x-slot>
    </x-dialog-modal>
    <!-- Add Item Confirmation Modal -->
    <x-dialog-modal wire:model.live="confirmingItemAdd">
        <x-slot name="title">
            {{ isset($this->item['id']) ? '아이템 수정' : '신규 아이템 등록' }}
        </x-slot>
        <x-slot name="content">
            <div class="col-span-6 sm:col-span-4">
                <x-label for="name" value="{{ __('이름') }}" />
                <x-input id="name" type="text" class="mt-1 block w-full" wire:model="item.name" />
                <x-input-error for="item.name" class="mt-2" />
            </div>
            <div class="col-span-6 sm:col-span-4 mt-3">
                <x-label for="price" value="{{ __('가격') }}" />
                <x-input id="price" type="text" class="mt-1 block w-full" wire:model="item.price" />
                <x-input-error for="item.price" class="mt-2" />
            </div>
            <div class="col-span-6 sm:col-span-4 mt-3">
                <label class="flex items-center">
                    <input type="checkbox" wire:model.live="itemStatus" />
                    <span class="text-sm text-gray-600 ml-2">동작</span>
                </label>
            </div>
        </x-slot>
        <x-slot name="footer">
            <x-secondary-button wire:click="$set('confirmingItemAdd', false)" wire:loading.attr="disabled">
                {{ __('취소') }}
            </x-secondary-button>
            <x-danger-button class="ms-3" wire:click="saveItem()" wire:loading.attr="disabled">
                {{ __('저장') }}
            </x-danger-button>
        </x-slot>
    </x-dialog-modal>
</div>

<script>
    const btn_deletes = document.querySelectorAll(".btn_delete");
    btn_deletes.forEach(
        // ajax : post로 delete 라우트
    );
</script>