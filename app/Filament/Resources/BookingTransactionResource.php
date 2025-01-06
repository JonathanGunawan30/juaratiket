<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookingTransactionResource\Pages;
use App\Filament\Resources\BookingTransactionResource\RelationManagers;
use App\Jobs\SendBookingApprovedEmail;
use App\Models\BookingTransaction;
use App\Models\Ticket;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Wizard;

class BookingTransactionResource extends Resource
{
    protected static ?string $model = BookingTransaction::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getNavigationBadge(): ?string
    {
        return (string) BookingTransaction::where('is_paid', false)->count();
    }

    protected static ?string $navigationGroup = 'Customer';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Wizard::make([
                    Wizard\Step::make('Product and Price')
                        ->schema([
                            // ...
                            Forms\Components\Select::make('ticket_id')
                            ->relationship('ticket', 'name')
                            ->label('Ticket')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set){
                                $ticket = Ticket::find($state);
                                $set('price', $ticket ? $ticket->price : 0);
                            }),

                            Forms\Components\TextInput::make('total_participant')
                            ->required()
                            ->numeric()
                            ->prefix('People')
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $get, callable $set){
                                $price = $get('price');
                                $subTotal = $price * $state;
                                $totalPpn = $subTotal * 0.11;
                                $totalAmount = $subTotal + $totalPpn;

                                $set('total_amount', $totalAmount);
                            }),

                            Forms\Components\TextInput::make('total_amount')
                            ->required()
                            ->numeric()
                            ->prefix('IDR')
                            ->readOnly()
                            ->helperText('Harga sudah include PPN 12%'),
                        ]),
                    Wizard\Step::make('Customer Information')
                        ->schema([
                            // ...
                            Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255),

                            Forms\Components\TextInput::make('phone_number')
                            ->required()
                            ->maxLength(255),

                            Forms\Components\TextInput::make('email')
                            ->required()
                            ->maxLength(255)
                            ->email(),

                            Forms\Components\TextInput::make('booking_trx_id')
                            ->required()
                            ->maxLength(255)
                        ]),
                    Wizard\Step::make('Payment Information')
                        ->schema([
                            // ...
                            Forms\Components\ToggleButtons::make('is_paid')
                            ->label('Apakah sudah membayar?')
                            ->boolean()
                            ->grouped()
                            ->icons([
                                true => 'heroicon-o-pencil',
                                false => 'heroicon-o-clock',
                            ])
                            ->required(),

                            Forms\Components\FileUpload::make('proof')
                            ->image()
                            ->required(),

                            Forms\Components\DatePicker::make('started_at')
                            ->required(),
                        ]),
                ])
                ->columnSpan('full')
                ->columns(1)
                ->skippable()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
                Tables\Columns\ImageColumn::make('ticket.thumbnail')
                ->circular(),

                Tables\Columns\TextColumn::make('name')
                ->searchable(),

                Tables\Columns\TextColumn::make('booking_trx_id')
                ->searchable(),

                Tables\Columns\IconColumn::make('is_paid')
                ->boolean()
                ->trueColor('success')
                ->falseColor('danger')
                ->trueIcon('heroicon-o-check-circle')
                ->falseIcon('heroicon-o-x-circle')
                ->label('Terverifikasi')
            ])
            ->filters([
                //
                Tables\Filters\SelectFilter::make('ticket_id')
                ->label('ticket')
                ->relationship('ticket', 'name')
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),

                Tables\Actions\Action::make('approve')
                ->label('Approve')
                ->icon('heroicon-o-check-circle')
                ->action(function (BookingTransaction $record){
                    $record->is_paid = true;
                    $record->save();

                    SendBookingApprovedEmail::dispatch($record);

                    Notification::make()
                        ->title('Ticket Approved')
                        ->success()
                        ->body('The ticket has been successfully approved.')
                        ->send();
                })
                ->color('success')
                ->requiresConfirmation()
                ->visible(fn(BookingTransaction $record) => !$record->is_paid)
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
            'index' => Pages\ListBookingTransactions::route('/'),
            'create' => Pages\CreateBookingTransaction::route('/create'),
            'edit' => Pages\EditBookingTransaction::route('/{record}/edit'),
        ];
    }
}
