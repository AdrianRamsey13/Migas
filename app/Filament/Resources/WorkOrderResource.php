<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WorkOrderResource\Pages;
use App\Models\WorkOrder;
use App\Models\User;
use App\Models\Asset;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;

class WorkOrderResource extends Resource
{
    protected static ?string $model = WorkOrder::class;
    protected static ?string $navigationIcon = 'heroicon-o-wrench-screwdriver';
    protected static ?string $navigationLabel = 'Work Orders';
    protected static ?int $navigationSort = 3;
    protected static ?string $navigationGroup = 'Work Management';


    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Informasi Work Order')
                ->schema([
                    Forms\Components\Select::make('asset_id')
                        ->label('Asset')
                        ->options(Asset::all()->pluck('name', 'id'))
                        ->searchable()
                        ->required(),
                    Forms\Components\TextInput::make('title')
                        ->label('Judul')
                        ->required()
                        ->maxLength(255),
                    Forms\Components\Textarea::make('description')
                        ->label('Deskripsi')
                        ->required()
                        ->columnSpanFull(),
                ])->columns(2),

            Forms\Components\Section::make('Detail')
                ->schema([
                    Forms\Components\Select::make('type')
                        ->label('Tipe')
                        ->options([
                            'corrective'  => 'Corrective',
                            'preventive'  => 'Preventive',
                            'inspection'  => 'Inspection',
                        ])
                        ->required(),
                    Forms\Components\Select::make('priority')
                        ->label('Prioritas')
                        ->options([
                            'low'      => 'Low',
                            'medium'   => 'Medium',
                            'high'     => 'High',
                            'critical' => 'Critical',
                        ])
                        ->required(),
                    Forms\Components\Select::make('assigned_to')
                        ->label('Assign Teknisi')
                        ->options(User::role('technician')->pluck('name', 'id'))
                        ->searchable()
                        ->nullable(),
                    Forms\Components\DatePicker::make('scheduled_date')
                        ->label('Tanggal Rencana')
                        ->nullable(),
                ])->columns(2),

            Forms\Components\Section::make('Catatan')
                ->schema([
                    Forms\Components\Textarea::make('notes')
                        ->label('Notes')
                        ->nullable()
                        ->columnSpanFull(),
                    Forms\Components\Textarea::make('rejection_reason')
                        ->label('Alasan Penolakan')
                        ->nullable()
                        ->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('wo_number')
                    ->label('No. WO')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('title')
                    ->label('Judul')
                    ->searchable()
                    ->limit(40),
                Tables\Columns\TextColumn::make('asset.name')
                    ->label('Asset')
                    ->sortable(),
                Tables\Columns\BadgeColumn::make('type')
                    ->label('Tipe')
                    ->colors([
                        'warning' => 'corrective',
                        'success' => 'preventive',
                        'info'    => 'inspection',
                    ]),
                Tables\Columns\BadgeColumn::make('priority')
                    ->label('Prioritas')
                    ->colors([
                        'secondary' => 'low',
                        'warning'   => 'medium',
                        'danger'    => 'high',
                        'danger'    => 'critical',
                    ]),
                Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'secondary' => 'draft',
                        'warning'   => 'submitted',
                        'info'      => 'approved',
                        'primary'   => 'in_progress',
                        'success'   => 'completed',
                        'success'   => 'closed',
                        'danger'    => 'rejected',
                    ]),
                Tables\Columns\TextColumn::make('assignedTo.name')
                    ->label('Teknisi')
                    ->default('-'),
                Tables\Columns\TextColumn::make('scheduled_date')
                    ->label('Tgl Rencana')
                    ->date('d M Y')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'draft'       => 'Draft',
                        'submitted'   => 'Submitted',
                        'approved'    => 'Approved',
                        'in_progress' => 'In Progress',
                        'completed'   => 'Completed',
                        'closed'      => 'Closed',
                        'rejected'    => 'Rejected',
                    ]),
                SelectFilter::make('priority')
                    ->options([
                        'low'      => 'Low',
                        'medium'   => 'Medium',
                        'high'     => 'High',
                        'critical' => 'Critical',
                    ]),
                SelectFilter::make('type')
                    ->options([
                        'corrective'  => 'Corrective',
                        'preventive'  => 'Preventive',
                        'inspection'  => 'Inspection',
                    ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListWorkOrders::route('/'),
            'create' => Pages\CreateWorkOrder::route('/create'),
            'edit'   => Pages\EditWorkOrder::route('/{record}/edit'),
        ];
    }
}
