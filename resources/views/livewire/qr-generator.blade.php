<div class="max-w-xl mx-auto p-8 bg-white rounded-xl shadow mt-10 space-y-8">
    <h1 class="text-3xl font-bold text-center mb-6">QR 코드 생성기</h1>
    <form wire:submit.prevent="generate" class="space-y-6">
        <x-field label="텍스트 또는 URL" for="input">
            <x-input id="input" wire:model="input" placeholder="텍스트 또는 URL을 입력하세요" />
            @error('input') <x-callout type="error">{{ $message }}</x-callout> @enderror
        </x-field>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <x-field label="색상" for="color">
                <input id="color" type="color" wire:model="color" class="w-10 h-10 border-none bg-transparent" />
            </x-field>
            <x-field label="크기(px)" for="size">
                <x-input id="size" type="number" min="100" max="600" wire:model="size" />
            </x-field>
            <x-field label="여백" for="margin">
                <x-input id="margin" type="number" min="0" max="10" wire:model="margin" />
            </x-field>
            <x-field label="로고(선택)" for="logo">
                <x-input id="logo" type="file" wire:model="logo" accept="image/*" />
            </x-field>
        </div>
        <x-button type="submit" color="primary" class="w-full mt-2">QR 코드 생성</x-button>
    </form>

    <div class="flex flex-col items-center space-y-2">
        @if($qrCodePng)
            <div class="bg-gray-100 p-4 rounded-lg flex flex-col items-center">
                <div class="mb-2 text-sm text-gray-600">미리보기</div>
                <div class="bg-white p-2 rounded shadow">
                    <img src="{{ $qrCodePng }}" alt="QR 미리보기" class="w-auto h-48" />
                </div>
                <a href="{{ $qrCodePng }}" download="qr_code.png">
                    <x-button color="accent" class="mt-4">PNG 다운로드</x-button>
                </a>
            </div>
        @else
            <div class="text-gray-400">QR 코드 미리보기</div>
        @endif
    </div>

    <div class="flex justify-between items-center mt-8">
        <x-button color="secondary" variant="outline" wire:click="">히스토리</x-button>
        <x-button color="error" variant="outline" wire:click="clearHistory">히스토리 초기화</x-button>
    </div>

    @if(is_countable($this->history) && count($this->history))
        <div class="mt-4 bg-gray-50 rounded-lg p-4">
            <div class="font-semibold mb-2">최근 생성한 QR 코드</div>
            <ul class="space-y-2">
                @foreach($this->history as $item)
                    <li class="flex items-center justify-between gap-2">
                        <span class="truncate text-xs">{{ $item['input'] }}</span>
                        <a href="{{ url('storage/'.$item['file']) }}" target="_blank">
                            <x-button size="xs" color="accent">보기</x-button>
                        </a>
                        <a href="{{ url('storage/'.$item['file']) }}" download>
                            <x-button size="xs" color="primary">다운로드</x-button>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    @if($showShare && $shareUrl)
        <x-modal open="true" max-width="sm">
            <x-slot name="title">QR 코드 공유</x-slot>
            <x-slot name="content">
                <x-input readonly value="{{ $shareUrl }}" class="mb-2" />
                <div class="flex gap-2">
                    <a href="https://twitter.com/intent/tweet?url={{ urlencode($shareUrl) }}" target="_blank">
                        <x-button size="sm" color="info">트위터</x-button>
                    </a>
                    <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($shareUrl) }}" target="_blank">
                        <x-button size="sm" color="primary">페이스북</x-button>
                    </a>
                    <x-button size="sm" variant="outline" onclick="navigator.clipboard.writeText('{{ $shareUrl }}')">링크 복사</x-button>
                </div>
            </x-slot>
            <x-slot name="footer">
                <x-button color="error" wire:click="$set('showShare', false)">닫기</x-button>
            </x-slot>
        </x-modal>
    @endif

</div>
