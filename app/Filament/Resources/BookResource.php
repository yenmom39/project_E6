<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookResource\Pages;
use App\Filament\Resources\BookResource\RelationManagers;
use App\Models\Book;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\BorrowRecord;

class BookResource extends Resource
{
    protected static ?string $model = Book::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    protected static ?string $modelLabel = 'Books Collection';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')->rules(['required']),
                Forms\Components\Select::make('author_id')
                    ->relationship('author', 'name')
                    ->rules(['required']),
                Forms\Components\Select::make('category_id')->relationship('category', 'name')->rules(['required']),
                Forms\Components\TextInput::make('isbn')->unique()->rules(['required']),
                Forms\Components\TextInput::make('published_year')->numeric()->rules(['required']),
                Forms\Components\TextInput::make('total_copies')->numeric()->rules(['required']),
                Forms\Components\TextInput::make('available_copies')->numeric()->rules(['required']),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('ID')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('title')->label('TITLE')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('author.name')->label('AUTHOR')->sortable(),
                Tables\Columns\TextColumn::make('category.name')->label('CATEGORY')->sortable(),
                Tables\Columns\TextColumn::make('isbn')->label('ISBN'),
                Tables\Columns\TextColumn::make('published_year')->label('PUBLISHED YEAR')->sortable(),
                Tables\Columns\TextColumn::make('total_copies')->label('TOTAL COPY'),
                Tables\Columns\TextColumn::make('available_copies')->label('AVAILABLE COPY'),
            ])
            ->filters([
                SelectFilter::make('category_id')->relationship('category', 'name'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            'index' => Pages\ListBooks::route('/'),
            'create' => Pages\CreateBook::route('/create'),
            'edit' => Pages\EditBook::route('/{record}/edit'),
        ];
    }
}
