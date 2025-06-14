<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Epic;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\EpicResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\EpicResource\RelationManagers;

class EpicResource extends Resource
{
    protected static ?string $model = Epic::class;

    protected static ?string $navigationIcon = 'heroicon-o-flag';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->required(),
                Select::make('project_id')
                    ->relationship('project', 'name')
                    ->required(),
                Textarea::make('description'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable(),
                TextColumn::make('project.name')->label('Project')->searchable(),
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
            ])
            ->defaultSort('id', 'desc');
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
            'index' => Pages\ListEpics::route('/'),
            'create' => Pages\CreateEpic::route('/create'),
            'edit' => Pages\EditEpic::route('/{record}/edit'),
        ];
    }
}
