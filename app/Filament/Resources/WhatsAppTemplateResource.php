<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WhatsAppTemplateResource\Pages;
use App\Models\WhatsAppTemplate;
use BackedEnum;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Table;
use UnitEnum;

class WhatsAppTemplateResource extends Resource
{
    protected static ?string $model = WhatsAppTemplate::class;

    protected static ?string $slug = 'whatsapp-templates';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-document-text';

    protected static string|UnitEnum|null $navigationGroup = 'Sistem & Pengaturan';

    protected static ?string $pluralModelLabel = 'Templat WhatsApp';

    protected static ?string $modelLabel = 'Templat WhatsApp';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Forms\Components\TextInput::make('nama')
                    ->label('Nama (Key)')
                    ->helperText('Identitas unik template, dipakai di pengaturan (contoh: pelanggaran_peringatan).')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(100),
                Forms\Components\TextInput::make('judul')
                    ->label('Judul')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('pesan')
                    ->label('Isi Pesan')
                    ->helperText('Placeholder yang didukung: {nama_santri}, {nama_kategori}, {poin}, {total_poin}, {nama_wali}')
                    ->required()
                    ->rows(6)
                    ->columnSpanFull(),
                Forms\Components\Toggle::make('aktif')
                    ->label('Aktif')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama (Key)')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('judul')
                    ->label('Judul')
                    ->searchable(),
                Tables\Columns\TextColumn::make('pesan')
                    ->label('Isi Pesan')
                    ->limit(60),
                Tables\Columns\IconColumn::make('aktif')
                    ->label('Aktif')
                    ->boolean(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->recordActions([
                \Filament\Actions\EditAction::make(),
                \Filament\Actions\DeleteAction::make(),
            ])
            ->toolbarActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageWhatsAppTemplates::route('/'),
        ];
    }
}
