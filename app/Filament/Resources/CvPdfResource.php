<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CvPdfResource\Pages;
use App\Models\CvPdf;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CvPdfResource extends Resource
{
    protected static ?string $model = CvPdf::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'CV - Fichier PDF';

    protected static ?string $modelLabel = 'CV PDF';

    protected static ?string $navigationGroup = 'CV';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form->schema([
                       FileUpload::make('fichier')
                ->label('Fichier PDF')
                ->disk('public')
                ->directory('cv')
                ->acceptedFileTypes(['application/pdf'])
                ->previewable(false)
                ->downloadable()
                ->required(),
            \Filament\Forms\Components\TextInput::make('nom_original')
                ->label('Nom du fichier affiché')
                ->required()
                ->maxLength(255),
            Toggle::make('actif')
                ->label('Actif (téléchargeable sur le site)')
                ->default(true)
                ->helperText('Un seul CV doit être actif à la fois.'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nom_original')->label('Nom')->searchable(),
                IconColumn::make('actif')->boolean(),
                TextColumn::make('created_at')->date('d/m/Y')->label('Ajouté le'),
            ])
            ->defaultSort('created_at', 'desc')
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCvPdfs::route('/'),
            'create' => Pages\CreateCvPdf::route('/create'),
            'edit' => Pages\EditCvPdf::route('/{record}/edit'),
        ];
    }
}
