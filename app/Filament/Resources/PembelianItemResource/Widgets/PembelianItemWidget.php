<?php

namespace App\Filament\Resources\PembelianItemResource\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use App\Models\PembelianItem;
use Illuminate\Support\Facades\DB;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\DeleteAction;
use Filament\Widgets\TableWidget as BaseWidget;
use Filament\Tables\Columns\Summarizers\Summarizer;

class PembelianItemWidget extends BaseWidget
{


    public $pembelianId;
    public function mount($record)
    {
        $this->pembelianId = $record;
    }




    public function table(Table $table): Table
    {
        return $table
            ->query(
                PembelianItem::query()->where('pembelian_id', $this->pembelianId),
            )
            ->columns([
                TextColumn::make('barang.nama')->label('Nama Barang')->alignCenter(),
                TextColumn::make('jumlah')->label('Jumlah Barang')->alignCenter(),
                TextColumn::make('harga')->label('Harga Barang')->alignCenter(),
                TextColumn::make('total')->label('Total Harga')
                    ->getStateUsing(function ($record) {
                        return $record->jumlah * $record->harga;
                    })->money('IDR')->alignEnd()
                    ->summarize(
                        Summarizer::make()
                        ->using(function ($query) {
                            return $query->sum(DB::raw('jumlah * harga'));
                        })
                            ->label('Total Pembelian')
                            ->money('IDR')
                    ),

            ])->actions([
                Tables\Actions\EditAction::make()
                ->form([
                    TextInput::make('jumlah')->required(),
                ]),
                DeleteAction::make(),
            ]);
    }
}
