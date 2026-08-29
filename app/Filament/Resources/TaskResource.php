<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TaskResource\Pages;
use App\Filament\Resources\TaskResource\RelationManagers;
use App\Models\Task;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TaskResource extends Resource
{
    protected static ?string $model = Task::class;

    protected static ?string $navigationGroup = 'Task';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Task';

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('task_name')
                    ->label('Nama Pekerjaan')
                    ->required(),
                Forms\Components\Select::make('pic_id')
                    ->label('PIC')
                    ->relationship('pic', 'name')
                    ->searchable()
                    ->required(),
                Forms\Components\DatePicker::make('deadline')
                    ->required(),
                Forms\Components\Select::make('status')
                    ->options([
                        'belum_mulai' => 'Belum Mulai',
                        'proses' => 'Proses',
                        'selesai' => 'Selesai',
                    ])
                    ->default('belum_mulai')
                    ->required(),
                Forms\Components\Select::make('priority')
                    ->label('Prioritas')
                    ->options([
                        'rendah' => 'Rendah',
                        'sedang' => 'Sedang',
                        'tinggi' => 'Tinggi',
                    ])
                    ->default('sedang')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('task_name')
                    ->label('Nama Pekerjaan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('pic.name')
                    ->label('PIC')
                    ->sortable(),
                Tables\Columns\TextColumn::make('deadline')
                    ->date('d/m/Y')
                    ->sortable()
                    ->color(fn ($record) =>
                        $record->status !== 'selesai' && $record->deadline->isPast() ? 'danger'
                        : ($record->status !== 'selesai' && $record->deadline->diffInDays(now(), true) <= 3 ? 'warning' : null)
                    ),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'belum_mulai' => 'Belum Mulai',
                        'proses' => 'Proses',
                        'selesai' => 'Selesai',
                    })
                    ->color(fn (string $state) => match ($state) {
                        'belum_mulai' => 'gray',
                        'proses' => 'warning',
                        'selesai' => 'success',
                    }),
                Tables\Columns\TextColumn::make('priority')
                    ->label('Prioritas')
                    ->badge()
                    ->formatStateUsing(fn (string $state) => match ($state) {
                        'rendah' => 'Rendah',
                        'sedang' => 'Sedang',
                        'tinggi' => 'Tinggi',
                    })
                    ->color(fn (string $state) => match ($state) {
                        'rendah' => 'gray',
                        'sedang' => 'warning',
                        'tinggi' => 'danger',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('pic_id')
                    ->label('PIC')
                    ->relationship('pic', 'name'),
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'belum_mulai' => 'Belum Mulai',
                        'proses' => 'Proses',
                        'selesai' => 'Selesai',
                    ]),

                Tables\Filters\Filter::make('deadline')
                    ->form([
                        Forms\Components\DatePicker::make('dari'),
                        Forms\Components\DatePicker::make('sampai'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['dari'], fn ($q) => $q->whereDate('deadline', '>=', $data['dari']))
                            ->when($data['sampai'], fn ($q) => $q->whereDate('deadline', '<=', $data['sampai']));
                    }),
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
            'index' => Pages\ListTasks::route('/'),
            'create' => Pages\CreateTask::route('/create'),
            'edit' => Pages\EditTask::route('/{record}/edit'),
        ];
    }
}
