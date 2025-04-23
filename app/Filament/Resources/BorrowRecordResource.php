<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BorrowRecordResource\Pages;
use App\Filament\Resources\BorrowRecordResource\RelationManagers;
use App\Models\BorrowRecord;
use App\Models\Staff;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Actions\Action;
use App\Models\Book;
use App\Models\User;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\DateFilter;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Actions\DropdownAction;
use Filament\Tables\Actions\ButtonAction;



class BorrowRecordResource extends Resource
{
    protected static ?string $model = BorrowRecord::class;

    protected static ?string $navigationIcon = 'heroicon-o-inbox-arrow-down';

    protected static ?string $modelLabel = 'Book Borrow and Return';

    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                ->label('Users')
                ->options(User::all()->pluck('name', 'id'))
                ->rules(['required']),

                Forms\Components\Select::make('book_id')
                ->label('Books Collection')
                ->searchable() // Allows searching
                ->options(Book::all()->pluck('title', 'id'))
                ->rules(['required']),

                Forms\Components\Select::make('staff_id')
                ->label('Library Staff')
                ->searchable() // Allows searching
                ->options(Staff::all()->pluck('name', 'id'))
                ->rules(['required']),

                Forms\Components\DatePicker::make('borrow_date')
                ->label('Borrow Date')
                ->rules(['required']),

                Forms\Components\DatePicker::make('return_date')
                ->label('Return Date')
                ->rules(['required'])
                ->nullable(),

                Forms\Components\Select::make('status')
                ->label('Status')
                ->options([
                    'borrowed' => 'Borrowed',
                    'returned' => 'Returned',
                    'overdue' => 'Overdue',
                ])
                ->default('borrowed')
                ->rules(['required']),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                ->label('Users')
                ->sortable()
                ->searchable(),

                Tables\Columns\TextColumn::make('book.title')
                ->label('Books Collection')
                ->sortable()
                ->searchable(),

                Tables\Columns\TextColumn::make('staff.name')
                ->label('Library Staff')
                ->sortable()
                ->searchable(),

                Tables\Columns\TextColumn::make('borrow_date')
                ->label('Borrow Date')
                ->sortable()
                ->date(),

                Tables\Columns\TextColumn::make('return_date')
                ->label('Return Date')
                ->sortable()
                ->date()
                ->formatStateUsing(fn ($state) => $state ? $state : 'N/A'), // Handles nullable values

                Tables\Columns\TextColumn::make('status')
                ->label('Status')
                ->sortable()
                ->badge()
                ->color(fn ($record) => match ($record->status) {
                    'borrowed' => 'warning',  // Color for 'borrowed'
                    'returned' => 'success',   // Color for 'returned'
                    'overdue' => 'danger',      // Color for 'overdue'
                    default => 'gray',       // Default color (if no match)
                })
                ->searchable(),

            ])
            ->filters([
                SelectFilter::make('status')
                ->options([
                    'Borrowed' => 'Borrowed',
                    'Returned' => 'Returned',
                    'Overdue' => 'Overdue',
                ])
                ->label('Filter by Status'),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make()->color('info'),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                    Action::make('borrow')
                        ->label('Borrow Book')
                        ->color('success')
                        ->icon('heroicon-o-book-open')
                        ->requiresConfirmation()
                        ->action(fn ($record) => self::borrowBook($record))
                        ->hidden(fn ($record) => $record->status !== 'borrowed'),

                    Action::make('return')
                        ->label('Return Book')
                        ->color('warning')
                        ->icon('heroicon-o-check-circle')
                        ->requiresConfirmation()
                        ->action(fn ($record) => self::returnBook($record))
                        ->hidden(fn ($record) => $record->status !== 'borrowed'),
                    ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function borrowBook(BorrowRecord $record)
    {
        $book = Book::find($record->book_id);

         // Define overdue threshold, for example, 30 days
        $overdueThreshold = now()->subDays(30);
          // Check if the book is overdue (if the record exists and its status is 'borrowed' but the return date is not set)
        if ($record->status === 'borrowed' && $record->borrow_date < $overdueThreshold) {
            // Handle overdue book, you can notify the user or prevent borrowing
            throw new \Exception('This book is overdue and cannot be borrowed again.');
        }
        if ($book->available_copies > 0) {
            $record->update([
                'borrow_date' => now(),
                'status' => 'borrowed',
            ]);
            $book->decrement('available_copies');
        }else {
            throw new \Exception('No available copies of this book.');
        }

    }

    public static function returnBook(BorrowRecord $record)
    {
        $record->update([
            'return_date' => now(),
            'status' => 'returned',
        ]);
        $record->book->increment('available_copies');
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
            'index' => Pages\ListBorrowRecords::route('/'),
            'create' => Pages\CreateBorrowRecord::route('/create'),
            'edit' => Pages\EditBorrowRecord::route('/{record}/edit'),
        ];
    }
}