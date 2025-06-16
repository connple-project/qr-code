<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;
use tbQuar\Facades\Quar;

class QrGenerator extends Component
{
    use WithFileUploads;

    public string $input = '';
    public string $color = '#000000';
    public int $size = 200;
    public int $margin = 1;
    public $qrCodeSvg = '';
    public $qrCodePng = '';
    public $showHistory = false;
    public $showShare = false;
    public $shareUrl = '';
    public $logo = null;

    // history 등 배열/객체 property는 모두 제거!
    // accessor(getHistoryProperty)로만 접근

    protected $rules = [
        'input' => 'required|string|max:500',
        'color' => 'required|string',
        'size' => 'required|integer|min:100|max:600',
        'margin' => 'required|integer|min:0|max:10',
        'logo' => 'nullable|image|max:1024',
    ];

    public function generate(): void
    {
        if (empty($this->input)) {
            $this->qrCodeSvg = '';
            $this->qrCodePng = '';
            return;
        }

        $margin = max(1, $this->margin);

        $qrSvg = Quar::format('svg')
            ->color($this->color)
            ->size($this->size)
            ->margin($margin);

        if ($this->logo) {
            $qrSvg = $qrSvg->merge($this->logo->getRealPath(), 0.2, true);
        }
        $this->qrCodeSvg = (string) $qrSvg->generate($this->input);

        $filename = 'qr-preview-' . session()->getId() . '-' . uniqid() . '.png';
        $pngPath = storage_path('app/public/' . $filename);

        $qrPng = Quar::format('png')
            ->color($this->color)
            ->size($this->size)
            ->margin($margin);

        if ($this->logo) {
            $qrPng = $qrPng->merge($this->logo->getRealPath(), 0.2, true);
        }
        $qrPng->generate($this->input, $pngPath);

        $this->qrCodePng = asset('storage/' . $filename);

        $this->addToHistory([
            'input' => $this->input,
            'color' => $this->color,
            'size' => $this->size,
            'margin' => $this->margin,
            'file' => $filename,
            'created_at' => now()->toDateTimeString(),
        ]);

        ray([
            'SVG' => $this->qrCodeSvg,
            'PNG' => $this->qrCodePng,
            'Input' => $this->input,
            'Color' => $this->color,
            'Size' => $this->size,
            'Margin' => $this->margin,
        ]);
    }

    public function download()
    {
        $margin = max(1, $this->margin);
        $filename = 'qr_code_' . now()->format('Ymd_His') . '.png';
        $path = storage_path('app/public/' . $filename);
        $qrPng = Quar::format('png')
            ->color($this->color)
            ->size($this->size)
            ->margin($margin);
        if ($this->logo) {
            $qrPng = $qrPng->merge($this->logo->getRealPath(), 0.2, true);
        }
        $qrPng->generate($this->input, $path);
        $this->addToHistory([
            'input' => $this->input,
            'color' => $this->color,
            'size' => $this->size,
            'margin' => $this->margin,
            'file' => $filename,
            'created_at' => now()->toDateTimeString(),
        ]);
        return response()->download($path)->deleteFileAfterSend();
    }

    public function addToHistory($item)
    {
        $history = session()->get('qr_history', []);
        array_unshift($history, $item);
        session(['qr_history' => array_slice($history, 0, 10)]);
    }

    public function clearHistory()
    {
        session()->forget('qr_history');
    }

    public function share($index)
    {
        $item = $this->history[$index] ?? null;
        if ($item) {
            $this->shareUrl = url('storage/' . $item['file']);
            $this->showShare = true;
        }
    }

    public function getHistoryProperty()
    {
        $history = session()->get('qr_history', []);
        return is_array($history) ? $history : [];
    }

    public function render()
    {
        return view('livewire.qr-generator');
    }
}
