<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Barang;
use Filament\Forms\Set;
use Filament\Forms\Form;
use App\Models\Pembelian;
use Filament\Tables\Table;
use App\Models\PembelianItem;
use Filament\Resources\Resource;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PembelianItemResource\Pages;
use App\Filament\Resources\PembelianItemResource\RelationManagers;

class PembelianItemResource extends Resource
{
    protected static ?string $model = PembelianItem::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        $pembelian = new Pembelian();
        if (request()->filled('pembelian_id')) {
            $pembelian = Pembelian::find(request('pembelian_id'));
        }

        return $form
            ->schema([
                DatePicker::make('tanggal')
                    ->required()
                    ->label('Tanggal Pembelian')
                    ->default($pembelian->tanggal)
                    ->disabled()
                    ->columnSpanFull(),
                TextInput::make('supplier_id')
                    ->label('Supplier')
                    ->default($pembelian->supplier?->nama_perusahaan)
                    ->disabled(),
                TextInput::make('email_supplier')
                    ->label('Supplier')
                    ->default($pembelian->supplier?->email)
                    ->disabled(),
                Select::make('barang_id')
                    ->label('Barang')
                    ->options(
                        Barang::pluck('nama', 'id')
                    )
                    ->reactive()
                    ->afterStateUpdated(function ($state, Set $set) {
                        $barang = Barang::find($state);
                        $set('harga', $barang->harga ?? null);
                    }),
                TextInput::make('jumlah')
                    ->label('Jumlah Barang')
                    ->required(),
                TextInput::make('harga')
                    ->label('Harga')
                    ->default($pembelian->harga)
                    ,
                Hidden::make('pembelian_id')
                    ->default(request('pembelian_id')),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPembelianItems::route('/'),
            'create' => Pages\CreatePembelianItem::route('/create'),
            'edit' => Pages\EditPembelianItem::route('/{record}/edit'),
        ];
    }
}
